<?php
namespace storify\project;

class post_report{

	private static $instance = null;

	private $tbl_post_report;

	// Construct initial value

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_post_report = $wpdb->prefix."20project_post_report";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new post_report();
		}

		return self::$instance;
	}

	public function getPostReportTable(){
		return $this->tbl_post_report;
	}

	public function makePostReport($project_id, $task_id, $msg, $data){
		global $wpdb, $current_user;

		$query = "INSERT INTO `".$this->tbl_post_report."` ( project_id, task_id, user_id, msg, data, status ) VALUES ( %d, %d, %d, %s, %s, %s )";
		$wpdb->query( $wpdb->prepare( $query, $project_id, $task_id, $current_user->ID, $msg, json_encode($data), "submitted" ) );

		return $wpdb->insert_id;
	}

	public function responsePostReport($post_report_id, $status, $msg){
		global $wpdb, $current_user;

		$query = "UPDATE `".$this->tbl_post_report."` SET status = %s, feedback = %s, feedback_by = %d WHERE id = %d";
		$wpdb->query( $wpdb->prepare( $query, $status, $msg, $current_user->ID, $post_report_id ) );
	}

	public function getPostReport($post_report_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_post_report."` WHERE id = %d";
		return $wpdb->get_row( $wpdb->prepare( $query, $post_report_id ) );
	}

	public function getPostReportByProject_id($project_id){
		global $wpdb;

		$query = "
			SELECT a1.* FROM `".$this->tbl_post_report."` a1
			LEFT OUTER JOIN `".$this->tbl_post_report."` a2
			ON a1.task_id = a2.task_id AND a1.project_id = a2.project_id AND a1.user_id = a2.user_id AND a1.tt < a2.tt
			WHERE a2.task_id IS NULL AND a1.project_id = %d
		";

		$result = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		return $result;
	}

	public function getPostReportByCreator_id($user_id){
		global $wpdb;

		$query = "
			SELECT a1.* FROM `".$this->tbl_post_report."` a1
			LEFT OUTER JOIN `".$this->tbl_post_report."` a2
			ON a1.task_id = a2.task_id AND a1.user_id = a2.user_id AND a1.tt < a2.tt
			WHERE a2.task_id IS NULL AND a1.user_id = %d
		";
	}
}