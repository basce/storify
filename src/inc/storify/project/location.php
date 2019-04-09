<?php
namespace storify\project;

class location{
	private $tbl_project_location;
	
	public function __construct(){
		global $wpdb;

		$this->tbl_project_location = $wpdb->prefix."project_location";
	}	

	public function getTable(){
		return $this->tbl_project_location;
	}

	public function updateLocation($location_ids, $project_id){
		global $wpdb;

		//remove all previous link
		$query = "DELETE FROM `".$this->tbl_project_location."` WHERE project_id = %d";
		$wpdb->query($wpdb->prepare($query, $project_id));

		//insert linkage
		if($location_ids && sizeof($location_ids)){
			$query = "INSERT INTO `".$this->tbl_project_location."` ( term_id, project_id ) VALUES ( %d, %d)";
			foreach($location_ids as $key=>$value){
				$wpdb->query($wpdb->prepare($query, $value, $project_id));
			}
		}
	}

	public function getLocation($project_id){
		global $wpdb;

		$query = "SELECT term_id FROM `".$this->tbl_project_location."` WHERE project_id = %d";
		return $wpdb->get_col($wpdb->prepare($query, $project_id));
	}
}