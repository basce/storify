<?php  

/*
Array ( [business_name] => ThrowBack [brand] => 240 [citycountry] => SG [about] => Info about business ) Array ( [business_image] => Array ( [name] => 27_1561705762.png [type] => image/png [tmp_name] => /tmp/phpXrv2iB [error] => 0 [size] => 176611 ) )
*/

//modify and save the image

if(isset($_FILES) && isset($_FILES["business_image"]) && $_FILES["business_image"]["name"]){
	$image = wp_get_image_editor($_FILES["business_image"]["tmp_name"]);
	$upload_result = $main->handleUploadedSquareImage($_FILES["business_image"], 600);
	if($upload_result["error"]){
		$error_msg = $upload_result["msg"];
		$update_success = false;
	}else{
		//check if have group id
		if(isset($_POST["group_id"])){
			//update

			//remove previous image from AWS
			$business_account = \storify\business_group::get($_POST["group_id"]);
			if(isset($business_account["profile_image"])){
				$main->removeAWSImage($business_account["profile_image"]);
			}

			\storify\business_group::edit($_POST["business_name"], $_POST["brand"], $_POST["citycountry"], $_POST["about"], $_POST["group_id"], $upload_result["filename"]);
		}else{
			//insert
			$group_id = \storify\business_group::add($_POST["business_name"], $_POST["brand"], $_POST["citycountry"], $_POST["about"], $upload_result["filename"]);

			\storify\business_group::setMember($current_user->ID, $group_id, "admin");
			\storify\business_group::setDefaultRole($current_user->ID, $group_id);
		}
		$update_success = true;
	}
}else{
	if(isset($_POST["group_id"])){
		//update
		\storify\business_group::edit($_POST["business_name"], $_POST["brand"], $_POST["citycountry"], $_POST["about"], $_POST["group_id"]);
	}else{
		//insert
		$group_id = \storify\business_group::add($_POST["business_name"], $_POST["brand"], $_POST["citycountry"], $_POST["about"]);

		\storify\business_group::setMember($current_user->ID, $group_id, "admin");
		\storify\business_group::setDefaultRole($current_user->ID, $group_id);
	}
	$update_success = true;
}


?>