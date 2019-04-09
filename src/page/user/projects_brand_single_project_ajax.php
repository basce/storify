<?php  
$obj = array(
    "error"=>1,
    "msg"=>"unknown"
);
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
        case "addCategory":
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
        case "getInvitation":
            $obj["error"] = 0;
            $obj["msg"] = "";
        break;
        case "inviteCreator":
            $obj["error"] = 0;
            $obj["msg"] = "";

        break;
        case "removeInvitation":
            $obj["error"] = 0;
        break;
        case "addProject":
            $obj["error"] = 0;
            $project_id = $main->getProjectManager()->createNewProject($_POST["inputs"]["detail"]["name"], $current_user->ID);
            //save detail
            $main->getProjectManager()->save($project_id, $_POST["inputs"]);
            $obj["msg"] = "";
            $obj["project_id"] = $project_id;
        break;
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
        case "getDeliverable":
            $result = $main->getProjectManager()->getDeliverables($_POST["project_id"], $current_user->ID);
            if($result["error"]){
                $obj["error"] = 1;
                $obj["msg"] = $result["msg"];
            }else{
                $obj["error"] = 0;
                $obj["msg"] = "";
                $obj["data"] = $result["data"];
            }
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