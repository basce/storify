<?php
namespace storify\project;

class status{

	private static $instance = null;

	private $tbl_project_status;

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_project_status = $wpdb->prefix."20project_status";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new status();
		}

		return self::$instance;
	}

	public function getTable(){
		return $this->tbl_project_status;
	}

	public function updateStatus($user_id, $project_id, $status){
		global $wpdb;

		//check if user id and project id exist
		$query = "SELECT id FROM `".$this->tbl_project_status."` WHERE user_id = %d AND project_id = %d";
		$status_id = $wpdb->get_var($wpdb->prepare($query, $user_id, $project_id));

		if($status_id){
			$query = "UPDATE `".$this->tbl_project_status."` SET status = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $status, $status_id));
		}else{
			$query = "INSERT INTO `".$this->tbl_project_status."` ( project_id, user_id, status ) VALUES ( %d, %d, %s)";
			$wpdb->query($wpdb->prepare($query, $project_id, $user_id, $status));
		}
	}

	public function getStatus($user_id, $project_id){
		global $wpdb;

		$query = "SELECT status FROM `".$this->tbl_project_status."` WHERE user_id = %d AND project_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, $project_id));
	}

	// get creator number of project according to project status : "open" "close"

	public function getCreatorNumberOfProjectByStatus($user_id, $project_status){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_project_status."` WHERE user_id = %d AND status = %s";
		$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, $project_status));
	}
}