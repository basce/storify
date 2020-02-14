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
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>