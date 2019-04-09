<?php
namespace storify\project;

class summary{

	private $tbl_summary;

	public function __construct(){
		global $wpdb;

		$this->tbl_summary = $wpdb->prefix."project_summary";
	}

	public function getSummaryTable(){
		return $this->tbl_summary;
	}

	public function getSummary($project_id){
		global $wpdb;

		$query = "SELECT summary FROM `".$this->tbl_summary."` WHERE project_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $project_id));
	}

	public function updateSummary($summary, $project_id){
		global $wpdb;

		$query = "SELECT id FROM `".$this->tbl_summary."` WHERE project_id = %d";
		$summary_id = $wpdb->get_var($wpdb->prepare($query, $project_id));

		if($summary_id){
			//update
			$query = "UPDATE `".$this->tbl_summary."` SET summary = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $summary, $summary_id));
		}else{
			//insert
			$query = "INSERT INTO `".$this->tbl_summary."` ( summary, project_id ) VALUES( %s, %d )";
			$wpdb->query($wpdb->prepare($query, $summary, $project_id));
		}
	}
}