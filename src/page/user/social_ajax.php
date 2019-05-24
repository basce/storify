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
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>