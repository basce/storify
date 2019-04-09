<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
switch($_REQUEST["method"]){
	case "triggerbookmark":
		if(isset($current_user) && $current_user->ID){
			$obj["error"] = 0;
			$obj["msg"] = "";
			if($_REQUEST["bookmark"]){
				$main->addBookmark($_REQUEST["item_id"], $_REQUEST["type"]);
			}else{
				$main->removeBookmark($_REQUEST["item_id"], $_REQUEST["type"]);
			}
			$obj["bookmark"] = $main->checkBookmarkItem($_REQUEST["item_id"], $_REQUEST["type"]);
		}else{
			$obj["error"] = 1;
			$obj["msg"] = "require login";
		}
	break;
	case "getPosts":
		$posts = $main->getPosts($_REQUEST["iger"], 12, $_REQUEST["page"], $_REQUEST["sort"],false);
		$obj["error"] = 0;
		$obj["msg"] = "";
		$obj["result"] = $posts;
		//get last udpate time
		$last_update_datetime = 0;
		if(isset($posts["data"]) && sizeof($posts["data"])){
			foreach($posts["data"] as $key=>$value){
				if( $value["last_updated_time"] > $last_update_datetime){
					$last_update_datetime = $value["last_updated_time"];
				}
			}
		}
		$obj["last_update"] = date("j M y H:i" , $last_update_datetime);
	break;
	case "getlisting":
		 $igers = $main->getIger(isset($_REQUEST["category"])?json_decode($_REQUEST["category"], true):null,isset($_REQUEST["country"])?json_decode($_REQUEST["country"], true):null,isset($_REQUEST["langauge"])?json_decode($_REQUEST["langauge"], true):null,12,$_REQUEST["page"],$_REQUEST["sort"],false);
		 $obj["error"] = 0;
		 $obj["msg"] = "";
		 $obj["result"] = $igers;
	break;
	default:
		$obj["msg"] = "unknown method";
	break;
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>