<?php  
use storify\job as job;

try{

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
        case "addProject":
            $obj["error"] = 0;
            
            \storify\vlog::trace("add project");
            $result = $main->getProjectManager()->createNewProject($_POST["data"]);
            $main->getProjectManager()->generateProjectSummary($result);
            
            $obj["data"] = $_POST["data"];
            $obj["result"] = $result;
            $obj["msg"] = "";
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