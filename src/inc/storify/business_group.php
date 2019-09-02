<?php
namespace storify;

class business_group{

	function __construct(){

	}

	public static function add($name, $brand, $country, $about, $profile_image=null){
		global $wpdb;

		if($profile_image){
			$query = "INSERT INTO `".$wpdb->prefix."business_group` ( name, default_brand, default_country, about, profile_image ) VALUES ( %s, %d, %s, %s, %s )";
			$wpdb->query($wpdb->prepare($query, $name, $brand, $country, $about, $profile_image));

			return $wpdb->insert_id;
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."business_group` ( name, default_brand, default_country, about ) VALUES ( %s, %d, %s, %s )";
			$wpdb->query($wpdb->prepare($query, $name, $brand, $country, $about));
			return $wpdb->insert_id;
		}		
	}

	public static function edit($name, $brand, $country, $about, $group_id, $profile_image=null){
		global $wpdb;

		if($profile_image){
			$query = "UPDATE `".$wpdb->prefix."business_group` SET name = %s, default_brand = %d, default_country = %s, about = %s, profile_image = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $name, $brand, $country, $about, $profile_image, $group_id));
		}else{
			$query = "UPDATE `".$wpdb->prefix."business_group` SET name = %s, default_brand = %d, default_country = %s, about = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $name, $brand, $country, $about, $group_id));
		}
	}

	public static function get($group_id){
		global $wpdb;

		$query = "SELECT name, default_brand, default_country, about, profile_image FROM `".$wpdb->prefix."business_group` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $group_id), ARRAY_A);
	}

	public static function getDefaultGroup($user_id){
		global $wpdb;

		$query = "SELECT group_id FROM `".$wpdb->prefix."business_group_member` WHERE user_id = %d AND default_group = %d";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, 1));
	}

	public static function setDefaultRole($user_id, $group_id){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."business_group_member` SET default_group = %d WHERE user_id = %d";
		$wpdb->query($wpdb->prepare($query, 0, $user_id));

		$query = "UPDATE `".$wpdb->prefix."business_group_member` SET default_group = %d WHERE user_id = %d AND group_id = %d";
		$wpdb->query($wpdb->prepare($query, 1, $user_id, $group_id));
	}

	public static function getUserRole($user_id, $group_id){
		global $wpdb;

		$query = "SELECT role FROM `".$wpdb->prefix."business_group_member` WHERE group_id = %d AND user_id = %d";
		$role = $wpdb->get_var($wpdb->prepare($query, $group_id, $user_id));
		return $role; // null | admin | manager
	}

	public static function setMember($user_id, $group_id, $role){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."business_group_member` WHERE group_id = %d AND user_id = %d";
		$exist = $wpdb->get_var($wpdb->prepare($query, $group_id, $user_id));
		if($exist){
			$query = "UPDATE `".$wpdb->prefix."business_group_member` SET role = %s WHERE group_id = %d AND user_id = %d";
			$wpdb->query($wpdb->prepare($query, $role, $group_id, $user_id));
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."business_group_member` ( group_id, user_id, role ) VALUES ( %d, %d, %s )";
			$wpdb->query($wpdb->prepare($query, $group_id, $user_id, $role));
		}
	}

	public static function getMember($group_id){
		global $wpdb;

		//get all member
		$query = " ( SELECT a.user_id, a.role, b.display_name FROM `".$wpdb->prefix."business_group_member` a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID WHERE a.group_id = %d ) c LEFT JOIN `".$wpdb->prefix."igaccounts` d ON c.user_id = d.userid";

		
		//get all member that is in invite list


		//get all email that is in invite list
	}

	public static function removeMember($user_id, $group_id){
		global $wpdb;

		$query = "DELETE FROM `".$wpdb->prefix."business_group_member` WHERE group_id = %d AND user_id = %d";
		$wpdb->query($wpdb->prepare($query, $group_id, $user_id));
	}

	public static function getNumberOfAdmin($group_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."business_group_member` WHERE group_id = %d AND role = %s";
		return $wpdb->get_var($wpdb->prepare($query, $group_id, "admin"));
	}

	public static function getUserInvitation($user_id, $group_id){
		global $wpdb;

		$query = "SELECT role FROM `".$wpdb->prefix."business_group_member_invitation` WHERE user_id = %d AND group_id = %d";
		$role = $wpdb->get_var($wpdb->prepare($query, $user_id, $group_id));
		return $role; // null | admin | manager
	}

	public static function addMemberInvitation($user_id, $group_id, $role){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."business_group_member_invitation` WHERE group_id = %d AND user_id = %d";
		$exist = $wpdb->get_var($wpdb->prepare($query, $group_id, $user_id));
		if($exist){
			$query = "UPDATE `".$wpdb->prefix."business_group_member_invitation` SET role = %s WHERE group_id = %d AND user_id = %d";
			$wpdb->query($wpdb->prepare($query, $role, $group_id, $user_id));
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."business_group_member_invitation` ( user_id, group_id, role ) VALUES ( %d, %d, %s )";
			$wpdb->query($wpdb->prepare($query, $user_id, $group_id, $role));
		}
	}

	public static function getEmailInvitation($email, $group_id){
		global $wpdb;

		$query = "SELECT role FROM `".$wpdb->prefix."business_group_member_invitation_email` WHERE LOWER(email) = LOWER(%s) AND group_id = %d";
		$role = $wpdb->get_var($wpdb->prepare($query, $email, $group_id));
		return $role;
	}

	public static function addEmailInvitation($email, $group_id, $role){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."business_group_member_invitation_email` WHERE group_id = %d AND LOWER(email) = LOWER(%s)";
		$exist = $wpdb->get_var($wpdb->prepare($query, $group_id, $email));
		if($exist){
			$query = "UPDATE `".$wpdb->prefix."business_group_member_invitation_email` SET role = %s WHERE group_id = %d AND LOWER(email) = LOWER(%s)";
			$wpdb->query($wpdb->prepare($query, $role, $group_id, $email));
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."business_group_member_invitation_email` ( email, group_id, role ) VALUES ( %s, %d, %s )";
			$wpdb->query($wpdb->prepare($query, $email, $group_id, $role ));
		}
	}

	public static function removeEmailInvitation($email, $group_id){
		global $wpdb;

		$query = "DELETE FROM `".$wpdb->prefix."business_group_member_invitation_email` WHERE LOWER(email) = LOWER(%s) AND group_id = %d";
		$wpdb->query($wpdb->prepare($query, $email, $group_id));
	}

	public static function removeInvitation($group_id, $user_id){
		global $wpdb;

		$query = "DELETE `".$wpdb->prefix."business_group_member_invitation` WHERE group_id = %d AND user_id = %d";
		$wpdb->query($wpdb->prepare($query, $group_id, $user_id));
	}

	public static function getMemberInvitation($user_id){
		global $wpdb;

		$query = "SELECT name, default_brand, default_country, about, profile_image FROM `".$wpdb->prefix."business_group` WHERE id IN ( SELECT group_id FROM `".$wpdb->prefix."business_group_member_invitation` WHERE user_id = %d )";
		return $wpdb->get_results($wpdb->prepare($query, $user_id), ARRAY_A);
	}

	public static function updateEmailInvitation($user_id, $email){
		global $wpdb;

		$query = "SELECT group_id, role FROM `".$wpdb->prefix."business_group_member_invitation_email` WHERE LOWER(email) = LOWER(%s)";
		$invitations = $wpdb->get_results($wpdb->prepare($query, $email), ARRAY_A);

		if(sizeof($invitations)){
			foreach($invitations as $key=>$value){
				self::addMemberInvitation($user_id, $value["group_id"], $value["role"]);
			}
		}

		$query = "UPDATE `".$wpdb->prefix."business_group_member_invitation_email` SET status = %s WHERE LOWER(email) = LOWER(%s)";
		$wpdb->query($wpdb->prepare($query, "close", $email));
	}
}