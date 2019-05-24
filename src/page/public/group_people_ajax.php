<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);

	switch($_REQUEST["method"]){
		case "getItems":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$data = $main->getGroupItem($_REQUEST["folder"], "people", $_REQUEST["sort"], 24, $_REQUEST["page"]);

			//restructure
			$items = array();
			foreach($data["data"] as $key=>$value){
				$items[] = $value["data"];
			}

			if($current_user && $current_user->ID){
				$bookmark = $main->getBookmarkPeople();
				$filterIDs = $bookmark->filterBookmarkIDs($current_user->ID, $items);

				foreach($data["data"] as $key=>$value){
					if(in_array($value["item_id"], $filterIDs)){
						$data["data"][$key]["bookmark"] = 1;
					}else{
						$data["data"][$key]["bookmark"] = 0;
					}
				}
			}else{
				foreach($data["data"] as $key=>$value){
					$data["data"][$key]["bookmark"] = 0;
				}
			}

			$obj["result"] = $data;
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}

$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>