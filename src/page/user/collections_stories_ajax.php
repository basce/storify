<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "getItems":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $main->getBookmark("story", $_REQUEST["sort"], 24, $_REQUEST["page"]);
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>