<?php  
$obj = array(
    "error"=>1,
    "msg"=>"unknown"
);
if($current_user->ID){
    switch($_REQUEST["method"]){
        case "getProject":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["result"] = $main->getProjectManager()->getBrandProjectList($current_user->ID, $_PORT["sort"], $_POST["filter"], 24, $_POST["page"]);
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
        case "getBrandCompletion":
            $result = $main->getProjectManager()->getBrandCompletionSummary($_POST["project_id"], $current_user->ID);
            $obj["error"] = $result["error"];
            $obj["msg"] = $result["msg"];
            $obj["data"] = $result["data"];
        break;
        case "getUsers":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $main->getProjectManager()->getUsersByAdmin($_POST["project_id"]);
        break;
        case "getDeliverableHistory":
            $result = $main->getProjectManager()->getDeliverablesHistory($_POST["deliverable_id"], $_POST["user_id"]);
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $result;
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
        case "getInvitationList":
            $result = $main->getProjectManager()->getInvitationList($_POST["project_id"]);
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $result;
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
        default:
            $obj["msg"] = "unknown method";
        break;
    }
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>