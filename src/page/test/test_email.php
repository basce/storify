<?php

$wp_user = get_user_by('email', 'basce.yong@gmail.com');
print_r($wp_user);
//echo get_password_reset_key($wp_user);
//$email_result = wp_mail("cheewei.yong@noisycrayons.com", "test email", "<p>This is testing email.</p>", "header with Chinese范围");
//print_r($email_result);


//print_r(check_password_reset_key("zy6qVw6VfyYqTmsFWqdVJ","basce.yong@gmail.com"));
?>