<?php
namespace storify\project;

class draft{
	private $tbl_project_draft;
	
	public function __construct(){
		global $wpdb;

		$this->tbl_project_draft = $wpdb->prefix."project_darft";
	}	

	public function getDraftTable(){
		return $this->tbl_project_draft;
	}

	public function save($project_id, $save_data){
		global $wpdb;

		if(sizeof($save_data)){
			$wpdb->insert(
				$this->tbl_project_detail,
				array(
					"project_id"=>$project_id,
					"json"=>json_encode($save_data)
				),
				array(
					"%d",
					"%s"
				)
			);
		}
	}

	public function getDraftList($project_id){
		global $wpdb;

		$query = "SELECT id, json, tt FROM `".$this->tbl_project_detail."` WHERE project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}
}