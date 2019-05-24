<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "addfolder":
			$groupid = $main->addGroup($_REQUEST["name"], 'people');
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $groupid;
		break;
		case "editfolder":
			if($main->checkGroupOwnerShip($_REQUEST["id"], "people")){
				$main->editGroup($_REQUEST["name"], $_REQUEST["id"], "people");
				$obj["error"] = 0;
				$obj["msg"] = "name updated";
			}else{
				$obj["error"] = 1;
				$obj["msg"] = "ownership denied";
			}
		break;
		case "deletefolder":
			if($main->checkGroupOwnerShip($_REQUEST["id"], "people")){
				$main->deleteGroup($_REQUEST["id"], "people");
				$obj["error"] = 0;
				$obj["msg"] = "";
			}else{
				$obj["error"] = 1;
				$obj["msg"] = "ownership denied";
			}
		break;
		case "getFolders":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $main->getGroup("people", $_REQUEST["sort"], 24, $_REQUEST["page"]);
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>