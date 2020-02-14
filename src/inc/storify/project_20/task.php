<?php
namespace storify\project;

class task{

	private static $instance = null;

	private $tbl_task;
	private $tbl_task_status;

	// Construct initial value

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_task = $wpdb->prefix."20project_task";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new task();
		}

		return self::$instance;
	}

	public function getUTCStringFromLocal($datetimeStr, $hours){

		$split = explode("/", $datetimeStr);

		if(sizeof($split) == 3){
			$dateObj = \DateTime::createFromFormat("d/m/y H:i:s", $datetimeStr." 00:00:00");
			return date("Y-m-d H:i:s", $dateObj->getTimestamp() - $hours*3600);
		}else{
			return date("Y-m-d H:i:s", strtotime($datetimeStr) - $hours*3600);
		}
	}

	// Get task table

	public function getTaskTable(){
		return $this->tbl_task;
	}

	// Add new task

	public function addTask($project_id, $task_obj){
		global $wpdb;

		$img_url = isset($task_obj["image_url"]) ? json_encode($task_obj["image_url"]) : "[]";
		$number_of_video = isset($task_obj["number_of_video"]) ? $task_obj["number_of_video"] : 0;
		$number_of_photo = isset($task_obj["number_of_photo"]) ? $task_obj["number_of_photo"] : 0;

		if( $task_obj["post"] === "false" ){

			$query = "INSERT INTO `".$this->tbl_task."` ( project_id, img_url, submission_closing_date, name, number_of_video, number_of_photo, instruction, post ) VALUES ( %d, %s, %s, %s, %d, %d, %s, %d )";
			$wpdb->query($wpdb->prepare($query, $project_id, $img_url, $this->getUTCStringFromLocal( $task_obj["submission_closing_date"], 8 ), $task_obj["name"], $number_of_video, $number_of_photo, $task_obj["instruction"], 0 ));

		}else{

			$query = "INSERT INTO `".$this->tbl_task."` ( project_id, img_url, submission_closing_date, name, number_of_video, number_of_photo, instruction, post, posting_date, post_instruction ) VALUES ( %d, %s, %s, %s, %d, %d, %s, %d, %s, %s )";
			$wpdb->query($wpdb->prepare($query, $project_id, $img_url, $this->getUTCStringFromLocal( $task_obj["submission_closing_date"], 8 ), $task_obj["name"], $number_of_video, $number_of_photo, $task_obj["instruction"], 1, $this->getUTCStringFromLocal($task_obj["posting_date"], 8), $task_obj["post_instruction"] ));

		}

		$task_id = $wpdb->insert_id;

		return $task_id;
	}

	// update task

	public function updateTask($task_id, $task_obj){
		global $wpdb;

		

		// get the orignal task

		$query = "SELECT data FROM `".$this->tbl_task."` WHERE id = %d";
		$task_obj = json_decode($wpdb->get_var($wpdb->prepare($query, $task_id)), true);

		// 

	}

	public function getTasks($project_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_task."` WHERE project_id = %d";

		$results = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		return $results;
	}

	public function getTaskDetail($task_id){
		global $wpdb;

		$query = "SELECT data, tt FROM `".$this->tbl_task_detail."` WHERE task_id = %d ORDER BY tt DESC LIMIT 1";
		return $wpdb->get_row($wpdb->prepare($query, $task_id), ARRAY_A);
	}

	public function createSummary($task_data){
		//nothing to calculate
		return $task_data;
	}
}