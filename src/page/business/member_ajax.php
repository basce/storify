<?php  
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);
if($current_user->ID && $default_group_id){
	switch($_REQUEST["method"]){
		case "getMember":
			$members = \storify\business_group::getMember($default_group_id);
			$obj["error"] = 0;
			$obj["msg"] = "";
			$obj["members"] = $members;
		break;
		case "addMember":
			//check if have access
			if(\storify\business_group::getUserRole($current_user->ID, $default_group_id) == "admin"){

				if($_POST["email"]){
					//check if the given email is registered
					$query = "SELECT ID FROM `".$wpdb->prefix."users` WHERE LOWER(user_email) = LOWER(%s)";
					$user_id = $wpdb->get_var($wpdb->prepare($query, $_POST["email"]));

					switch($_POST["role"]){
						case "admin":
						case "owner":
							$role = "admin";
						break;
						default:
							$role = "manager";
						break;
					}

					if($user_id){
						//registered user
						//check if user is already in group

						$user_role_in_group = \storify\business_group::getUserRole($user_id, $default_group_id);

						if($user_role_in_group){
							//user in group, change user role if different role request.
							if($user_role_in_group == $role){
								//no change
								$obj["error"] = 1;
								$obj["msg"] = "User is already ".($role == "admin" ? "an owner":"a manager") ;
							}else{
								//change 
								if(\storify\business_group::getNumberOfAdmin($default_group_id) == 1){
									$obj["error"] = 1;
									$obj["msg"] = "cannot removed the only admin";
								}else{
									\storify\business_group::setMember($user_id, $default_group_id, $role);
									$obj["error"] = 0;
									$obj["msg"] = "User role changed";
								}
							}
						}else{
							//user not in group, check if there are an existing invitation
							$user_role = \storify\business_group::getUserInvitation($user_id, $default_group_id);
							if($user_role){
								// user in invitation, change role if different role request.
								if($user_role == $role){
									$obj["error"] = 1;
									$obj["msg"] = "A request of joining the group as ".$role." had already sent out";
								}else{
									\storify\business_group::addMemberInvitation($user_id, $default_group_id, $role);
									$obj["error"] = 0;
									$obj["msg"] = "request sent";
								}
							}else{
								// new invitation
								\storify\business_group::addMemberInvitation($user_id, $default_group_id, $role);
								$obj["error"] = 0;
								$obj["msg"] = "request sent";
							}
						}
					}else{
						//only have email
						// check if email is already in invite list
						$email_role = \storify\business_group::getEmailInvitation($_POST["email"], $default_group_id);
						if($email_role){
							if($email_role == $role){
								//no change
								$obj["error"] = 1;
								$obj["msg"] = "A request of joining the group as ".$role." had already sent out";
							}else{
								//change
								\storify\business_group::addEmailInvitation($_POST["email"], $default_group_id, $role);
								$obj["error"] = 0;
								$obj["msg"] = "request sent";
							}
						}else{
							\storify\business_group::addEmailInvitation($_POST["email"], $default_group_id, $role);
							$obj["error"] = 0;
							$obj["msg"] = "request sent";
						}
					}
				}else{
					$obj["msg"] = "Missing Email";
				}

			}else{
				$obj["msg"] = "Permission denied. Required owner role to make the request.";
			}
		break;
		case "removeMember":
			//check if have access
			if(\storify\business_group::getUserRole($current_user->ID, $default_group_id) == "admin"){
								
				//check if the given email is registered
				$query = "SELECT ID FROM `".$wpdb->prefix."users` WHERE LOWER(user_email) = LOWER(%s)";
				$user_id = $wpdb->get_var($wpdb->prepare($query, $_POST["email"]));

				if($user_id){
					$user_role_in_group = \storify\business_group::getUserRole($user_id, $default_group_id);
					if($user_role_in_group){
						if(\storify\business_group::getNumberOfAdmin($default_group_id) == 1){
							$obj["error"] = 1;
							$obj["msg"] = "cannot removed the only admin";
						}else{
							\storify\business_group::removeMember($user_id, $default_group_id);
							$obj["error"] = 0;
							$obj["msg"] = "request removed";
						}
					}else{
						// remove request
						\storify\business_group::removeInvitation($user_id, $default_group_id);
						$obj["error"] = 0;
						$obj["msg"] = "request invitation removed";
					}
				}else{
					\storify\business_group::removeEmailInvitation($_POST["email"], $default_group_id);
					$obj["error"] = 0;
					$obj["msg"] = "request email invitation removed";
				}

			}else{
				$obj["msg"] = "Permission denied. Required owner role to make the request.";
			}
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>