<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "moveItem":
			if($main->checkGroupItemOwnerShip($_REQUEST["item"], 'story')){
				$main->moveGroupItemTo($_REQUEST["item"], $_REQUEST["position"], 'story');
				$obj["error"] = 0;
				$obj["msg"] = $_REQUEST["item"]." > ".$_REQUEST["position"];
			}else{
				$obj["error"] = 1;
				$obj["msg"] = "ownership denied";
			}
		break;
		case "removeItems":
			if(sizeof($_REQUEST["items"])){
				foreach($_REQUEST["items"] as $key=>$value){
					if($main->checkGroupItemOwnerShip($value, 'story')){
						$main->removeFromGroup($value, 'story');
					}else{
						//ignore error
					}
				}
			}
			$obj["error"] = 0;
			$obj["msg"] = "";
		break;
		case "getItems":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $main->getGroupItem($_REQUEST["folder"], "story", $_REQUEST["sort"], 2000, $_REQUEST["page"]);
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>