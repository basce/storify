<?php
namespace storify\project;

class sample{

	private $tbl_sample;

	public function __construct(){
		global $wpdb;

		$this->tbl_sample = $wpdb->prefix."project_sample";
	}

	public function getSampleTable(){
		return $this->tbl_sample;
	}

	public function addSample($url, $project_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_sample."` WHERE URL = %s AND project_id = %d";
		if(!$wpdb->get_var($wpdb->prepare($query, $url, $project_id))){
			$query = "INSERT INTO `".$this->tbl_sample."` ( URL, project_id ) VALUES ( %s, %d )";
			$wpdb->query($wpdb->prepare($query, $url, $project_id));
		}

		return array(
			"error"=>0,
			"msg"=>""
		);
	}

	public function removeSample($sample_id){
		global $wpdb;

		$query = "DELETE FROM `".$this->tbl_sample."` WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $sample_id));

		return array(
			"error"=>0,
			"msg"=>""
		);
	}

	public function getSample($project_id){
		global $wpdb;

		$query = "SELECT id, URL FROM `".$this->tbl_sample."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function emptySample($project_id){
		global $wpdb;

		$query = "DELETE FROM `".$this->tbl_sample."` WHERE project_id = %d";
		$wpdb->query($wpdb->prepare($query, $project_id));
	}
}