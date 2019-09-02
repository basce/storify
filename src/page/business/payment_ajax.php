<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID){
	switch($_REQUEST["method"]){
		case "addBrand":
			$input = $_REQUEST["input"];
			if($input){
				$result = $main->createTag("brand", $input);
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