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