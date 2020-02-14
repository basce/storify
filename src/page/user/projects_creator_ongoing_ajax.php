<?php  
$obj = array(
    "error"=>1,
    "msg"=>"unknown"
);
if($current_user->ID){
    switch($_REQUEST["method"]){
        case "getInvitation":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["result"] = $main->getProjectManager()->getCreatorInvitationList($current_user->ID, $_POST["sort"], $_POST["filter"], 24, $_POST["page"]);
        break;
        case "getProject":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["result"] = $main->getProjectManager()->getCreatorProjectList($current_user->ID, $_POST["sort"], $_POST["filter"], 24, $_POST["page"]);
        break;
        case "getDetail":
            $result = $main->getProjectManager()->getProjectDetail($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
                $obj["data"] = $result["data"];
            }
        break;
        case "getS3PresignedLink":
            $result = $main->getS3PresignedLink($_POST["project_id"], $current_user->ID, $_POST["type"], "put");
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
                $obj["url"] = $result["url"];
            }
        break;
        case "getDeliverable":
            $result = $main->getProjectManager()->getDeliverables($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
                $obj["data"] = $result["data"];
            }
        break;
        case "updateSubmission":
            $result = $main->getProjectManager()->submission_update_caption($_POST["id"], $current_user->ID, $_POST["caption"]);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
            }
        break;
        case "removeSubmission":
            $result = $main->getProjectManager()->submission_remove($_POST["id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
            }
        break;
        case "getSubmissions":
            $result = $main->getProjectManager()->getDeliverables($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
                $obj["data"] = null;
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
                $obj["data"] = $result["data"];
                $obj["no_of_video"] = $result["no_of_video"];
                $obj["no_of_photo"] = $result["no_of_photo"];
            }
        break;
        case "makeSubmissionFile":
            //$project_id, $type, $user_id, $filename, $filesize, $filemime
            $result = $main->getProjectManager()->submission_file_submit($_POST["project_id"], $_POST["type"], $current_user->ID, $_POST["file_name"], $_POST["file_size"], $_POST["file_mime"]);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                if($result["success"]){
                    //get presigned url
                    $presigned = $main->getS3UploadPresignedLink($result["key"], $_POST["file_mime"]);
                    $obj["error"] = 0;
                    $obj["url"] = $presigned["url"];
                    $obj["presigned"] = $presigned;
                    $obj["id"] = $result["id"];
                    $obj["msg"] = "presigned URL generated";
                    $obj["success"] = 1;
                }else{
                    $obj["error"] = 0;
                    $obj["msg"] = $result["msg"];  
                    $obj["success"] = $result["success"];
                }
            }
        break;
        case "confirmUpload":
            //check ownership
            if($main->getProjectManager()->checkFileOwnerShip($_POST["id"], $current_user->ID)){
                //is owner
                $result = $main->getProjectManager()->uploadComplete($_POST["id"], $_POST["caption"], $_POST["type"]);
                if($result["error"]){
                    $obj["error"] = 1;
                    $obj["msg"] = $result["msg"];
                }else{
                    $obj["error"] = 0;
                    $obj["msg"] = $result["msg"];  
                    $obj["success"] = $result["success"];
                }    
            }else{
                $obj["error"] = 1;
                $obj["msg"] = "Invalid ownership";
            }
        break;
        case "makeSubmission":
            $result = $main->getProjectManager()->submission_submit($_POST["project_id"], $_POST["type"],$current_user->ID, $_POST["URL"], $_POST["remark"]);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];  
                $obj["success"] = $result["success"];
            }
        break;
        case "getDownloadLink":
            if($main->getProjectManager()->checkFileOwnerShip($_POST["id"], $current_user->ID)){
                $file_data = $main->getProjectManager()->getFile($_POST["id"]);
                $url_result = $main->getS3presignedLink($file_data["file_url"]);
                if($url_result["error"]){
                    $obj["error"] = 1;
                    $obj["msg"] = $url_result["msg"];
                }else{
                    $obj["error"] = 0;
                    $obj["filelink"] = $url_result["url"];
                    $obj["filename"] = $file_data["filename"];
                    $obj["filesize"] = $file_data["size"];
                    $obj["filemime"] = $file_data["mime"];
                    $obj["msg"] = "";
                }
            }else{
                $obj["error"] = 1;
                $obj["msg"] = "Invalid ownership";
            }
        break;
        case "getUsers":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $main->getProjectManager()->getUsers($_POST["project_id"]);
        break;
        case "getDeliverableHistory":
            $result = $main->getProjectManager()->getDeliverablesHistory($_POST["deliverable_id"], $current_user->ID);
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $result;
        break;
        default:
            $obj["msg"] = "unknown method";
        break;
    }
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>