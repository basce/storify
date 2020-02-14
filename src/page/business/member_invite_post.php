<?php  

if(isset($_POST["group_id"])){

	$invited_role = \storify\business_group::getUserInvitation($current_user->ID, $_POST["group_id"]);

	if($invited_role){
		//set member

		\storify\business_group::setMember($current_user->ID, $_POST["group_id"], $invited_role);
		\storify\business_group::setDefaultRole($current_user->ID, $_POST["group_id"]);

	}

	//remove invitation
	\storify\business_group::removeInvitation($current_user->ID, $_POST["group_id"]);

	if($invited_role){
		// redirect to project on going page
		header("Location: /user/projects/ongoing");
		exit();
	}
}
?>