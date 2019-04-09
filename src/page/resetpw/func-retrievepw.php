<?php  
$email = isset($_POST["email"])?$_POST["email"] : "";

$wp_user = get_user_by('email', $email);
if(!$wp_user){
	$retrieve_success = false;
	$retrieve_error_msg = "Email is not registered.";
}else{
	$retrieve_success = true;
	$reset_link = get_password_reset_key($wp_user);

	$email_result = wp_mail($wp_user->user_email, "Reset your Storify password", "<p>Lost your password for ".$wp_user->user_email."? We are here to help. Please visit the link below to set up a new password for your account.</p><a href=\"https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."\" target=\"_blank\">https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."</a></p><p>If you did not request to reset your password, ignore this email and the link will expire on its own.</p>", "Reset your Storify password");

	if($email_result){
		$retrieve_success = true;
	}else{
		$retrieve_success = false;
		$retrieve_error_msg = "System Error. Please try again later.";
	}
}
?>