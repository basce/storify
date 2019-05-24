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
			$obj["result"] = $main->getProjectManager()->getProjectList($current_user->ID, $sort, $filters, 12, 1);
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>