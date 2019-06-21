<?php  

$email = isset($_POST["email"])?$_POST["email"] : "";

$wp_user = get_user_by('email', $email);
if(!$wp_user){
	$retrieve_success = false;
	$retrieve_error_msg = "Email is not registered.";
}else{
	$retrieve_success = true;
	$reset_link = get_password_reset_key($wp_user);

	$emailbody = "<p>Lost your password for ".$wp_user->user_email."? We are here to help. Please visit the link below to set up a new password for your account.</p><a href=\"https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."\" target=\"_blank\">https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."</a></p><p>If you did not request to reset your password, ignore this email and the link will expire on its own.</p>";
	$previewText = "Please visit the link below to set up a new password for your account.";

	$email_result = $main->sendLambdaEmail(
		array(
			"name"=>$wp_user->display_name,
			"email"=>$wp_user->user_email
		),
		array(
			"body"=>$emailbody,
			"text_preview"=>$previewText
		),
		"storify_basic"
	);

	if($email_result["error"]){
		$retrieve_success = false;
		$retrieve_error_msg = $email_result["msg"];
	}else{
		$retrieve_success = true;
	}

	/*
	$mailer = $main->getEmailer();
	$emailbody = "<p>Lost your password for ".$wp_user->user_email."? We are here to help. Please visit the link below to set up a new password for your account.</p><a href=\"https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."\" target=\"_blank\">https://storify.me/resetpassword?key=".$reset_link."&login=".$wp_user->user_login."</a></p><p>If you did not request to reset your password, ignore this email and the link will expire on its own.</p>";

	//get current time in Singapore timezone
	$date = new DateTime("now", new DateTimeZone('Asia/Singapore') );
	$sent_time = $date->format("F j, Y @ H:i a");

	$email_result = $mailer->sendEmail(
		array(
			"name"=>$wp_user->display_name,
			"email"=>$wp_user->user_email
		),
		"Reset your Storify password",
		array(
			"body"=>$emailbody,
			"sent_time"=>$sent_time
		),
		HOME_DIR."/emailtemplates/basic.html"
	);

	if($email_result["error"]){
		$retrieve_success = false;
		$retrieve_error_msg = $email_result["msg"];
	}else{
		$retrieve_success = true;
	}
	*/
}
?>