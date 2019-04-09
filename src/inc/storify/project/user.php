<?php
namespace storify\project;

class user{
	private $tbl_user;

	public function __construct(){
		global $wpdb;

		$this->tbl_user = $wpdb->prefix."project_user";
	}

	public function getTable(){
		return $this->tbl_user;
	}

	public function addUser($user_id, $project_id, $user_role){
		global $wpdb;

		$query = "SELECT id FROM `".$this->tbl_user."` WHERE user_id = %d AND project_id = %d";
		$role_id = $wpdb->get_var($wpdb->prepare($query, $user_id, $project_id));
		if($role_id){
			//update
			$query = "UPDATE `".$this->tbl_user."` SET role = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query,$user_role, $role_id));
		}else{
			//insert
			$query = "INSERT INTO `".$this->tbl_user."` ( project_id, user_id, role ) VALUES ( %d, %d, %s )";
			$wpdb->query($wpdb->prepare($query, $project_id, $user_id, $user_role));
		}
	}

	public function getUsers($project_id){
		global $wpdb;

		$query = "SELECT user_id, role FROM `".$this->tbl_user."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function isUserInProject($project_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_user."` WHERE user_id = %d AND project_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, $project_id));
	}

	public function getUserRoleInProject($project_id, $user_id){
		global $wpdb;

		$query = "SELECT role FROM `".$this->tbl_user."` WHERE user_id = %d AND project_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, $project_id));
	}
}