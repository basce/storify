<?php
namespace storify\project;

class tag{
	private $tbl_project_tag;
	
	public function __construct(){
		global $wpdb;

		$this->tbl_project_tag = $wpdb->prefix."project_tag";
	}	

	public function getTable(){
		return $this->tbl_project_tag;
	}

	public function updateTag($tag_ids, $project_id){
		global $wpdb;

		//remove all previous link
		$query = "DELETE FROM `".$this->tbl_project_tag."` WHERE project_id = %d";
		$wpdb->query($wpdb->prepare($query, $project_id));

		//insert linkage
		$query = "INSERT INTO `".$this->tbl_project_tag."` ( term_id, project_id ) VALUES ( %d, %d )";
		if($tag_ids && sizeof($tag_ids)){
			foreach($tag_ids as $key=>$value){
				$wpdb->query($wpdb->prepare($query, $value, $project_id));
			}
		}
	}

	public function getLocation($project_id){
		global $wpdb;

		$query = "SELECT term_id FROM `".$this->tbl_project_tag."` WHERE project_id = %d";
		return $wpdb->get_col($wpdb->prepare($query, $project_id));
	}
}