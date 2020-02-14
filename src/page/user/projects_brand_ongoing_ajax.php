<?php  
use storify\job as job;
$obj = array(
    "error"=>1,
    "msg"=>"unknown"
);
try{

if($current_user->ID){
    switch($_REQUEST["method"]){
        case "addLocation":
            $input = $_REQUEST["input"];
            if($input){
                $result = $main->createTag("country",$input);
                $obj["data"] = $result;
                $obj["error"] = 0;
                $obj["msg"] = "";
            }else{
                $obj["msg"] = "missing input";  
            }
        break;
        case "addTag":
            $input = $_REQUEST["input"];
            if($input){
                $result = $main->createTag("category",$input);
                $obj["data"] = $result;
                $obj["error"] = 0;
                $obj["msg"] = "";
            }else{
                $obj["msg"] = "missing input";  
            }
        break;
        case "addBrand":
            $input = $_REQUEST["input"];
            if($input){
                $result = $main->createTag("brand",$input);
                $obj["data"] = $result;
                $obj["error"] = 0;
                $obj["msg"] = "";
            }else{
                $obj["msg"] = "missing input";
            }
        break;
        case "getCreator":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $main->getCreator($_POST["name"]);
        break;
        case "editProject":
            //convert date
            $project_id = $_POST["project_id"];
            if(isset($_POST["data"]["detail"]["closing_date"]) && $_POST["data"]["detail"]["closing_date"]){
                //mm/dd/yyyy
                $tempdate = DateTime::createFromFormat('d/m/y', $_POST["data"]["detail"]["closing_date"]);
                $_POST["data"]["detail"]["closing_date"] = $tempdate->format('Y-m-d')." 00:00:00";
            }
            if(isset($_POST["data"]["detail"]["invitation_closing_date"]) && $_POST["data"]["detail"]["invitation_closing_date"]){
                $tempdate = DateTime::createFromFormat('d/m/y', $_POST["data"]["detail"]["invitation_closing_date"]);
                $_POST["data"]["detail"]["invitation_closing_date"] = $tempdate->format('Y-m-d')." 00:00:00";
            }
            $main->getProjectManager()->emptySample($project_id);
            if(isset($_POST["data"]["samples"]) && sizeof($_POST["data"]["samples"])){
                foreach($_POST["data"]["samples"] as $key=>$value){
                    $main->getProjectManager()->addSample($value, $project_id);
                }
            }
            $main->getProjectManager()->edit_save($project_id, $_POST["data"]);
            //save sample

            job::addUpdate("project_brief_change_".$project_id, "project_brief_update", array(
                "project_id"=>$project_id
            ), 300); //5 mins
            
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["project_id"] = $project_id;
        break;
        case "getProject":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["result"] = $main->getProjectManager()->getBrandProjectList($default_group_id, $_POST["filter"], 24, $_POST["page"]);
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
        case "getDeliverable":
            $result = $main->getProjectManager()->getDeliverables($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
                $obj["data"] = $result["data"];
                $obj["no_of_photo"] = $result["no_of_photo"];
                $obj["no_of_video"] = $result["no_of_video"];
            }
        break;
        case "getDownloadLink":
            if($main->getProjectManager()->checkFileAdminAccess($_POST["id"], $current_user->ID)){
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
            $obj["data"] = $main->getProjectManager()->getUsersByAdmin($_POST["project_id"]);
        break;
        case "getDeliverableHistory":
            $result = $main->getProjectManager()->getDeliverablesHistory($_POST["deliverable_id"], $_POST["creator"]);
            $obj["error"] = 0;
            $obj["msg"] = $wpdb->last_query;
            $obj["data"] = $result;
        break;
        case "response_submission":
            $result = $main->getProjectManager()->submission_admin_response($_POST["submission_id"], $current_user->ID, $_POST["status"], $_POST["status_remark"]);

            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
                $obj["data"] = $main->getProjectManager()->getDeliverable($_POST["submission_id"]);
            }
        break;
        case "getInvitationList":
            $result = $main->getProjectManager()->getInvitationList($_POST["project_id"]);
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $result;
        break;
        case "sendInvitation":
            $result = $main->getProjectManager()->setInvitationBatch($_POST["project_id"], $_POST["userids"]);
            foreach( $_POST["userids"] as $key=>$uid ){
                job::add($uid, "project_invite", array(
                    "creator_id"=>$uid,
                    "project_id"=>$_POST["project_id"]
                ), 1);
            }
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $result;
        break;
        case "editInvitation":
            if($_POST["command_type"] == 1){
                $result = $main->getProjectManager()->removeInvitation($_POST["id"]);
                if($result["error"]){
                    $obj["error"] = 1;
                    $obj["msg"] = $result["msg"];
                }else{
                    $obj["error"] = 0;
                    $obj["msg"] = $result["msg"];
                    if($result["userid"]){
                        job::cancel($result["userid"], "project_invite", array(
                            "creator_id"=>$result["userid"],
                            "project_id"=>$_POST["project_id"]
                        ));
                    }
                }
            }else if($_POST["command_type"] == 2){
                $result = $main->getProjectManager()->setInvitation($_POST["project_id"], $_POST["id"]);
                job::add($_POST["id"], "project_invite", array(
                    "creator_id"=>$_POST["id"],
                    "project_id"=>$_POST["project_id"]
                ), 1);
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
            }else if($_POST["command_type"] == 3){
                $obj["error"] = 1;
                $obj["msg"] = "This function will be available in future.";
            }else{
                $obj["error"] = 1;
                $obj["msg"] = "unknown command type";
            }
        break;
        case "getCompletion":
            $result = $main->getProjectManager()->get_project_completion($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
                $obj["data"] = $result["data"];
            }
        break;
        case "closeProject":
            $result = $main->getProjectManager()->close_project($_POST["project_id"], $current_user->ID);

            $obj["error"] = $result["error"];
            $obj["msg"] = $result["msg"];
            $obj["userid"] = $current_user->ID;
            $obj["project_id"] = $_POST["project_id"];
        break;
        case "closeAll":
            $result = $main->getProjectManager()->changeAllUserStatus($_POST["project_id"], "close", $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
            }
        break;
        case "changeUserStatus":
            switch($_POST["status"]){
                case "close":
                    $temp_status = "close";
                break;
                default:
                    $temp_status = "open";
                break;
            }
            $result = $main->getProjectManager()->changeUserStatus($_POST["project_id"], $_POST["user_id"], $temp_status, $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
            }
        break;
        default:
            $obj["msg"] = "unknown method";
        break;
    }
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);

}catch( Exception $e ){
    echo json_encode(array(
        "error"=>1,
        "msg"=>$e->getMessage()
    ));
}
?>