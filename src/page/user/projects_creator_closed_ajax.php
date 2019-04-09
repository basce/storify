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
        case "getCreatorCompletion":
            $obj["error"] = 0;
            $obj["msg"] = "";
            $obj["data"] = $main->getProjectManager()->getCreatorCompletionSummary($_POST["project_id"], $current_user->ID);
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
        case "getDeliverable":
            $result = $main->getProjectManager()->getDeliverables($_POST["project_id"], $current_user->ID);
            $obj["error"] = $result["error"];
            $obj["msg"] = $result["msg"];
            $obj["data"] = $result["data"];
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
        default:
            $obj["msg"] = "unknown method";
        break;
    }
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>