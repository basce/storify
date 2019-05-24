<?php  
include("inc/class.main.php");

$main = new main();

//get all instagrammer

$result = $main->createUser("basce.yong@gmail.com","123456qwerty", "Cheewei Yong", "male", "Singapore");
if($result["error"]){
	//simulate 
	print_r($result);

	$id = 7; // ID

	//get user data
	$user_wp_obj = get_userdata($id);
	print_r($user_wp_obj);

	$extra_meta = get_user_meta($id);
	print_r($extra_meta);
}else{
	//no error, register success
	
	//add user meta
	$id = $result; // ID

	//get user data
	$user_wp_obj = get_userdata($id);
	print_r($user_wp_obj);
}
?>