<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "addCountry":
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
		case "addLanguage":
			$input = $_REQUEST["input"];
			if($input){
				$result = $main->createTag("language",$input);
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
		case "social_update":
			$result = $main->updateUserTags($_REQUEST["countries"],$_REQUEST["language"],$_REQUEST["category"],$pathquery[2]); 
			$obj["error"] = 0;
			$obj["msg"] = "tags updated.";
			$obj["social_data"] = $main->getSingleIger($pathquery[2]);
		break;
		case "updatePosts":
			$posts_result = $main->updateLatest30Posts($pathquery[2], $_REQUEST["iger"]);
			if($posts_result["error"]){
				$obj["msg"] = $posts_result["msg"];
				$obj["pq"] = $pathquery;
			}else{
				$obj["error"] = 0;
				$obj["msg"] = "";
				$obj["result"] = $posts_result["posts"];
				//get last udpate time
				$last_update_datetime = 0;
				if(isset($posts_result["posts"]["data"]) && sizeof($posts_result["posts"]["data"])){
					foreach($posts_result["posts"]["data"] as $key=>$value){
						if( $value["last_updated_time"] > $last_update_datetime){
							$last_update_datetime = $value["last_updated_time"];
						}
					}
				}
				$obj["last_update"] = $last_update_datetime ? date("j M y H:i" , $last_update_datetime) : NULL;
			}
		break;
		case "getPosts":
			$posts =  $main->getPosts($_REQUEST["iger"], 30, 1, "latest",true);
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
			$obj["last_update"] = $last_update_datetime ? date("j M y H:i" , $last_update_datetime) : NULL;
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>