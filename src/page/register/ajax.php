<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
switch($_REQUEST["method"]){
	case "uniqueemail":
		$exist = email_exists($_POST["email"]) ? true : false;
		$obj["error"] = 0;
		$obj["msg"] = "";
		$obj["exist"] = $exist;
	break;
	default:
		$obj["msg"] = "unknown method";
	break;
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>