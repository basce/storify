<?php  

if(isset($_POST["group_id"])){

	\storify\business_group::setDefaultRole($current_user->ID, $_POST["group_id"]);

	header("Location: /user/projects/ongoing");		
}
?>