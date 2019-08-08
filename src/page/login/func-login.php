<?php  
$email = isset($_POST["email"])?$_POST["email"] : "";
$password = isset($_POST["password"])?$_POST["password"] : "";
$login_redirect = isset($_POST["redirect"])?$_POST["redirect"]: "";

$result = wp_signon(array(
	"user_login"=>$email,
	"user_password"=>$password,
	"remember"=>true
));

if(is_wp_error($result)){
	$login_success = false;
	foreach($result->errors as $key=>$value){
		switch($key){
			case "invalid_email":
				$login_error_msg = implode(";",$value);
			break;
			case "incorrect_password":
				$login_error_msg = implode(";",$value);
			break;
			default:
				$login_error_msg = implode(";",$value);
			break;
		}
	}

	$login_error_msg = str_replace('<a href="https://storify.me/ao/wp-login.php?action=lostpassword">Lost your password?</a>',"",$login_error_msg);
}else if($result->ID){
	$login_success = true;
	$user_ID = $result->ID;
}
?>