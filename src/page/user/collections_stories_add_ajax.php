<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "addItems":
			if(sizeof($_REQUEST["items"])){
				foreach($_REQUEST["items"] as $key=>$value){
					if($main->checkGroupOwnerShip($_REQUEST["folder"], 'story')){
						$main->addToGroup($value, 'story', $_REQUEST["folder"]);
					}
				}
			}
			$obj["error"] = 0;
			$obj["msg"] = "";
		break;
		case "getItems":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $main->getBookmark("story", $_REQUEST["sort"], 24, $_REQUEST["page"], $pathquery[3]);
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>