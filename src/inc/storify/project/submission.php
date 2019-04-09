<?php
namespace storify\project;

class submission{
	
	private $tbl_submission;
	private $tbl_submission_history;

	public function __construct(){
		global $wpdb;

		$this->tbl_submission = $wpdb->prefix."project_new_submission";
		$this->tbl_submission_history = $wpdb->prefix."project_new_submission_history";
	}  

	public function getSubmissionTable(){
		return $this->tbl_submission;
	}

	public function getSubmissionHistoryTable(){
		return $this->tbl_submission_history;
	}

	public function submit($project_id, $type, $userid, $url, $remark){
		global $wpdb;

		$query = "INSERT INTO `".$this->tbl_submission."` ( project_id, creator_id, type, URL, remark ) VALUES ( %d, %d, %s, %s, %s )";
		$wpdb->query($wpdb->prepare($query, $project_id, $userid, $type, $url, $remark));

		return array(
			"error"=>0,
			"msg"=>"",
			"success"=>1
		);
	}

	public function responseSubmission($submission_id, $status, $admin_remark){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_submission."` SET status = %s, admin_remark = %s, admin_response_tt = NOW() WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $admin_remark, $submission_id));
	}

	public function removeSubmission($submission_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_submission."` WHERE id = %d";
		if($wpdb->get_var($wpdb->prepare($query, $submission_id))){
			//item exist
			$query = "SELECT * FROM `".$this->tbl_submission."` WHERE id = %d";
			$data = $wpdb->get_row($wpdb->prepare($query, $submission_id), ARRAY_A);

			$query = "INSERT INTO `".$this->tbl_submission_history."` ( submission_id, project_id, creator_id, type, URL, remark, status, admin_remark, admin_response_tt, tt ) VALUES ( %d, %d, %d, %s, %s, %s, %s, %s, %s, %s)";
			$wpdb->query($wpdb->prepare($query, $data["id"], $data["project_id"], $data["creator_id"], $data["type"], $data["URL"], $data["remark"], $data["status"], $data["admin_remark"], $data["admin_response_tt"], $data["tt"]));

			$query = "DELETE FROM `".$this->tbl_submission."` WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $submission_id));
		}else{
			//ignore silently
		}
	}

	public function getSubmissions($project_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_submission."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function getSubmissionsHistory($project_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_submission_history."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function getSubmissionDetail($submission_id){
		global $wpdb;

		$query = "SELECT user_id, project_id FROM `".$this->tbl_submission."` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $submission_id), ARRAY_A);
	}

	public function getHistoryByDeliverable($deliverable_id){

	}

	public function checkSubmission($deliverable_id, $userid){
	}

	public function admin_response($submission_id, $admin_user_id, $status, $status_remark){

	}
}