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
        case "reject":
            $result = $main->getProjectManager()->invitation_response($_POST["invitation_id"], "rejected", $current_user->ID, $_POST["reason"]);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = $result["msg"];
                $obj["added"] = $result["added"];
                $obj["remark_id"] = $result["remark_id"];
                $obj["project_items_status"] = $main->getProjectManager()->getProjectStats($current_user->ID);
            }
        break;
        case "accept":
            $result = $main->getProjectManager()->invitation_response($_POST["invitation_id"], "accepted", $current_user->ID, "");
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["user_id"] = $current_user->ID;
                $obj["msg"] = $result["msg"];
                $obj["added"] = $result["added"];
                $obj["project_items_status"] = $main->getProjectManager()->getProjectStats($current_user->ID);
            }
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
        default:
            $obj["msg"] = "unknown method";
        break;
    }
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>