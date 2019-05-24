<?php  
$name = isset($_POST["name"])?$_POST["name"]:"";
$citycountry = isset($_POST["citycountry"])?$_POST["citycountry"]:"";
$gender = isset($_POST["gender"])?$_POST["gender"]:"";
$phone = isset($_POST["phone"])?$_POST["phone"]:"";
$default_view = isset($_POST["default_view"])?$_POST["default_view"]:"";

$result = wp_update_user(array(
	"ID"=>$current_user->ID,
	"display_name"=>$name
));

if(is_wp_error($result)){
	$update_success = false;
	$error_msg = json_encode($result);
}else{
	$update_success = true;

	update_user_meta($result, "gender", $gender);
	update_user_meta($result, "city_country", $citycountry);
	update_user_meta($result, "phone", $phone);
	$main->changeDefaultRole($default_view, $result);
}


?>