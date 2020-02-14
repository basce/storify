<?php
namespace storify\project;

class submission{
	
	private $tbl_submission;
	private $tbl_submission_file;
	private $tbl_submission_history;

	public function __construct(){
		global $wpdb;

		$this->tbl_submission = $wpdb->prefix."project_new_submission";
		$this->tbl_submission_file = $wpdb->prefix."project_new_submission_file";
		$this->tbl_submission_history = $wpdb->prefix."project_new_submission_history";
	}  

	public function getSubmissionTable(){
		return $this->tbl_submission;
	}

	public function getSubmissionFileTable(){
		return $this->tbl_submission_file;
	}

	public function getSubmissionHistoryTable(){
		return $this->tbl_submission_history;
	}

	public function submitFile($project_id, $type, $userid, $file_id, $remark){
		global $wpdb;

		//text submission
		$query = "INSERT INTO `".$this->tbl_submission."` ( project_id, creator_id, type, file_id, remark ) VALUES ( %d, %d, %s, %d, %s )";
		$wpdb->query($wpdb->prepare($query, $project_id, $userid, $type, $file_id, $remark));
		

		return array(
			"error"=>0,
			"msg"=>"",
			"success"=>1
		);
	}	

	public function submitText($project_id, $type, $userid, $url, $remark){
		global $wpdb;

		//text submission
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

	public function updateSubmissionCaption($submission_id, $caption){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_submission."` SET remark = %s, status = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $caption, "", $submission_id));
	}

	public function removeSubmission($submission_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_submission."` WHERE id = %d";
		if($wpdb->get_var($wpdb->prepare($query, $submission_id))){
			//item exist
			$query = "SELECT * FROM `".$this->tbl_submission."` WHERE id = %d";
			$data = $wpdb->get_row($wpdb->prepare($query, $submission_id), ARRAY_A);

			$query = "INSERT INTO `".$this->tbl_submission_history."` ( submission_id, project_id, creator_id, type, file_id, URL, remark, status, admin_remark, admin_response_tt, tt ) VALUES ( %d, %d, %d, %s, %d, %s, %s, %s, %s, %s, %s)";
			$wpdb->query($wpdb->prepare($query, $data["id"], $data["project_id"], $data["creator_id"], $data["type"], $data["file_id"], $data["URL"], $data["remark"], $data["status"], $data["admin_remark"], $data["admin_response_tt"], $data["tt"]));

			$query = "DELETE FROM `".$this->tbl_submission."` WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $submission_id));

			return array(
				"error"=>0,
				"msg"=>"",
				"file_id"=>$data["file_id"]
			);
		}else{
			//ignore silently
			return array(
				"error"=>1,
				"msg"=>"",
				"file_id"=>0
			);
		}
	}

	public function getSubmissions($project_id){
		global $wpdb;

		$query = "SELECT a.creator_id, a.project_id, a.file_id, a.URL, a.remark, a.status, a.admin_remark, a.admin_response_tt, a.tt, b.file_url, b.size, b.mime, b.filename FROM `".$this->tbl_submission."` a LEFT JOIN `".$this->tbl_submission_file."` b ON a.file_id = b.id WHERE a.project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function getSubmissionsHistory($project_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_submission_history."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function getSubmissionDetail($submission_id){
		global $wpdb;

		$query = "SELECT a.creator_id, a.project_id, a.file_id, a.URL, a.remark, a.status, a.admin_remark, a.admin_response_tt, a.tt, b.file_url, b.size, b.mime, b.filename FROM `".$this->tbl_submission."` a LEFT JOIN `".$this->tbl_submission_file."` b ON a.file_id = b.id WHERE a.id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $submission_id), ARRAY_A);
	}

	public function checkFileOwnerShip($key_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_submission_file."` WHERE id = %d AND user_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $key_id, $user_id));
	}

	public function addFileKey($project_id, $user_id, $filename, $filesize, $filemime){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_submission_file."` WHERE project_id = %d AND user_id = %d AND status != %s AND LOWER(filename) = LOWER(%s)";
		if($wpdb->get_var($wpdb->prepare($query, $project_id, $user_id, "deleted", $filename))){
			//filename exist, create a new filename
			
			if ($pos = strrpos($filename, '.')) {
				$name = substr($filename, 0, $pos);
				$ext = substr($filename, $pos);
			} else {
				$name = $filename;
				$ext = "";
			}

			$filename = $name."_".time().$ext;
		}

		//insert as new filename
		$fileKey = 'project/'.$project_id."/".$user_id."/".$filename;
		$query = "INSERT INTO `".$this->tbl_submission_file."` ( project_id, user_id, filename, file_url, size, mime, status ) VALUES ( %d, %d, %s, %s, %s, %s, %s )";
		$wpdb->query($wpdb->prepare($query, $project_id, $user_id, $filename, $fileKey, $filesize, $filemime, "ready"));

		$file_id = $wpdb->insert_id;
		return array(
			"error"=>0,
			"msg"=>"",
			"success"=>1,
			"id"=>$file_id,
			"filename"=>$filename,
			"key"=>$fileKey
		);
	}

	public function getFileKey($file_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_submission_file."` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $file_id), ARRAY_A);
	}

	public function changeKeyStatus($key_id, $status){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_submission_file."` SET status = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $key_id));

		//return data
		$query = "SELECT * FROM `".$this->tbl_submission_file."` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $key_id), ARRAY_A);
	}

	public function getHistoryByDeliverable($deliverable_id){

	}

	public function checkSubmission($deliverable_id, $userid){
	}

	public function admin_response($submission_id, $admin_user_id, $status, $status_remark){

	}
}