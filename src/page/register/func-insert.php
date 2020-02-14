<?php  
use storify\job as job;
$email = isset($_POST["email"])?$_POST["email"] : "";
$password = isset($_POST["password"])?$_POST["password"] : "";
$name = isset($_POST["name"])?$_POST["name"] : "";
$gender = isset($_POST["gender"])?$_POST["gender"] : "";
$citycountry = isset($_POST["citycountry"])?$_POST["citycountry"] : "";
$newsletter = isset($_POST["newsletter"])? 1 : 0; // 1 or empty

$result = $main->createUser($email, $password, $name, $gender, $citycountry, $newsletter);

if($result["error"]){
	$insert_success = false;
	$error_msg = $result["msg"];
}else{
	//success
	$insert_success = true;

	\storify\wallet::createAccount($result["id"],"personal");

	job::add($result["id"], "new_register", array(
		"userid"=>$result["id"],
		"name"=>$name,
		"email"=>$email
	), 15);

	wp_set_auth_cookie($result["id"], true);
}


?>