<?php  
$cpassword = isset($_POST["cpassword"])?$_POST["cpassword"]:"";
$npassword = isset($_POST["npassword"])?$_POST["npassword"]:"";

if($cpassword && $npassword){
	//check current password match
	$auth_check = wp_authenticate_username_password(null, $current_user->user_login, $cpassword);

	if(is_wp_error($auth_check)){
		//login fail
		$update_success = false;
		$update_msg = "incorrect current password";
	}else{
		//password correct. update password
		wp_set_password($npassword, $current_user->ID);
		wp_set_auth_cookie($current_user->ID, true); //set cookie after update password
		$update_success = true;
		$update_msg = "password updated";
	}
}else{
	$update_success = false;
	$update_msg = "password cannot be empty";
}

?>