<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "addfolder":
			$groupid = $main->addGroup($_REQUEST["name"], $_REQUEST["type"]);
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $groupid;
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>