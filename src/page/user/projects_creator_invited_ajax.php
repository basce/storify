<?php  
use storify\job as job;
$obj = array(
    "error"=>1,
    "msg"=>"unknown"
);
if($current_user->ID){
    switch($_REQUEST["method"]){
        case "getProject":
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
                $invitation_result = $main->getProjectManager()->getInvitationByID($_POST["invitation_id"]);
                job::add($current_user->ID, "project_invite_reject_creator", array(
                    "creator_id"=>$current_user->ID,
                    "project_id"=>$invitation_result["project_id"]
                ), 1);
                $query = "SELECT user_id FROM `".$wpdb->prefix."project_user` WHERE project_id = %d AND role = %s";
                $admin_id = $wpdb->get_var($wpdb->prepare($query, $invitation_result["project_id"], "admin"));
                job::add($admin_id, "project_invite_reject_brand", array(
                    "creator_id"=>$current_user->ID,
                    "project_id"=>$invitation_result["project_id"]
                ), 1);
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
                //get project id from invitation
                $invitation_result = $main->getProjectManager()->getInvitationByID($_POST["invitation_id"]);

                job::add($current_user->ID, "project_invite_accept_creator", array(
                    "creator_id"=>$current_user->ID,
                    "project_id"=>$invitation_result["project_id"]
                ), 1);

                $query = "SELECT user_id FROM `".$wpdb->prefix."project_user` WHERE project_id = %d AND role = %s";
                $admin_id = $wpdb->get_var($wpdb->prepare($query, $invitation_result["project_id"], "admin"));
                job::add($admin_id, "project_invite_accept_brand", array(
                    "creator_id"=>$current_user->ID,
                    "project_id"=>$invitation_result["project_id"]
                ), 1);
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