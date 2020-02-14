<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "setVerifyCode":
			$main->generateVerifyCode($current_user->ID, $_POST["igusername"]);
			$result = $main->getIGVerifyCode($current_user->ID);
			if(sizeof($result)){
				$obj["error"] = 0;
				$obj["code"] = $result["code"];
				$obj["igusername"] = $result["igusername"];
			}else{
				$obj["error"] = 0;
				$obj["code"] = "";
			}
		break;
		case "getVerifyCode":
			$result = $main->getIGVerifyCode($current_user->ID);
			if(sizeof($result)){
				$obj["error"] = 0;
				$obj["code"] = $result["code"];
				$obj["igusername"] = $result["igusername"];
			}else{
				$obj["error"] = 0;
				$obj["code"] = "";
			}
		break;
		case "verifyCode":
			$result = $main->verifyCode();
			if($result["error"]){
				$obj["error"] = 1;
				$obj["msg"] = $result["msg"];
			}else{
				//verify success
				if($result["verified"]){
					$main->removeVerifyCode($current_user->ID);
					$main->getIGAccountAndSet($result["igusername"], $current_user->ID, $current_user->display_name);
					$obj["error"] = 0;
					$obj["verified"] = $result["verified"];
					$obj["msg"] = "";
				}else{
					$obj["error"] = 0;
					$obj["verified"] = false;
					$obj["msg"] = "";
				}
			}
		break;
		case "removeVerifyCode":
			$main->removeVerifyCode($current_user->ID);
			$obj["error"] = 0;
			$obj["msg"] = "";
		break;
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
			$countries = isset($_REQUEST["countries"]) ? $_REQUEST["countries"] : array();
			$language = isset($_REQUEST["language"]) ? $_REQUEST["language"] : array();
			$category = isset($_REQUEST["category"]) ? $_REQUEST["category"] : array();
			$result = $main->updateUserTags($countries, $language, $category, $pathquery[2]); 
			$obj["error"] = 0;
			$obj["msg"] = "tags updated.";
			$obj["country_changed"] = $result["country_changed"];
			$obj["category_changed"] = $result["category_changed"];
			$obj["social_data"] = $main->getSingleIger($pathquery[2]);
		break;
		case "updatePostsAsync":
			$result = $main->updateLatest30PostsAsync($pathquery[2], $_REQUEST["iger"]);
			if($result["error"]){
				$obj["error"] = 1;
				$obj["msg"] = $result["msg"];
			}else{
				$obj["error"] = 0;
				$obj["msg"] = $result["msg"];
				$obj["status"] = 1;
			}
		break;
		case "checkPostsStatus":
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["status"] = $main->checkUpdatingRecord($pathquery[2]);
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
				$obj["social_data"] = $main->getSingleIger($pathquery[2]);	
			}
		break;
		case "getPosts":
			$posts =  $main->getPosts($_REQUEST["iger"], 30, 1, "latest",true);
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["result"] = $posts;
			//get last udpate time
			$obj["social_data"] = $main->getSingleIger($pathquery[2]);	
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>