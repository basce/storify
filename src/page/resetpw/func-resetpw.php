<?php  
$login = isset($_POST["login"])?$_POST["login"]:"";
$key = isset($_POST["key"])?$_POST["key"]:"";
$new_password = isset($_POST["password"])?$_POST["password"]: "";

$wp_user = check_password_reset_key($key, $login);
if(is_wp_error($wp_user)){
	$key_invalid = true;
}else{
	$key_invalid = false;
	if(strlen($new_password) >= 6 ){
		wp_set_password($new_password, $wp_user->ID);
		$update_success = true;
	}else{
		$update_success = false;
		$key_invalid = true;
	}

	//update password
}
?>