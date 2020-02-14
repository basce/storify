<?php
namespace storify\project;

class submission{

	private static $instance = null;

	private $tbl_submission;

	// Construct initial value

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_submission = $wpdb->prefix."20project_submission";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new submission();
		}

		return self::$instance;
	}

	public function getSubmissionTable(){
		return $this->tbl_submission;
	}

	/**
	 *	make submission
	 *  
	 *	project_id
	 * 	task_id
	 * 	type
	 * 	msg
	 * 	data
	 * 
	 **/

	public function createSubmission($project_id, $task_id, $type, $msg, $data){
		global $wpdb, $current_user;

		$query = "INSERT INTO `".$this->tbl_submission."` ( type, task_id, project_id, user_id, msg, data, status ) VALUES ( %s, %d, %d, %d, %s, %s, %s )";
		$wpdb->query( $wpdb->prepare( $query, $type, $task_id, $project_id, $current_user->ID, $msg, json_encode($data), "submitted" ) );

		return $wpdb->insert_id;
	}

	/**
	 * 
	 * 	admin respond to submission
	 * 	
	 * 	require to check the current user has the right to perform the action before calling this function
	 * 
	 **/
	
	public function responseSubmission($submission_id, $status, $msg){
		global $wpdb, $current_user, $default_group_id;

		$query = "UPDATE `".$this->tbl_submission."` SET status = %s, response_msg = %s, response_by_who = %d, response_tt = NOW() WHERE id = %d";
		$wpdb->query( $wpdb->prepare( $query, $status, $msg, $current_user->ID, $submission_id ) );
	}

	public function getSubmission($submission_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_submission."` WHERE id = %d";
		return $wpdb->get_row( $wpdb->prepare( $query, $submission_id ) );
	}

	public function getSubmissionByProject_id($project_id){
		global $wpdb;

		$query = "
			SELECT a1.* FROM `".$this->tbl_submission."` a1 
			LEFT OUTER JOIN `".$this->tbl_submission."` a2 
			ON a1.task_id = a2.task_id AND a1.project_id = a2.project_id AND a1.user_id = a2.user_id AND a1.tt < a2.tt 
			WHERE a2.task_id IS NULL AND a1.project_id = %d
		";

		$result = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		return $result; // sorting by javascript
	}

	public function getSubmissionByCreator_id($user_id){
		global $wpdb;

		$query = "
			SELECT a1.* FROM `".$this->tbl_submission."` a1 
			LEFT OUTER JOIN `".$this->tbl_submission."` a2 
			ON a1.task_id = a2.task_id AND a1.project_id = a2.project_id AND a1.user_id = a2.user_id AND a1.tt < a2.tt 
			WHERE a2.task_id IS NULL AND a1.user_id = %d
		";

		$result = $wpdb->get_results($wpdb->prepare($query, $user_id), ARRAY_A);

		return $result;
	}

	/*
	build outside of this function

	public function getSubmissionByBusinessGroup(){
		global $wpdb, $default_group_id;

		$query = ""
	}
	*/
}