<?php
namespace storify\project;

class brand{
	private $tbl_project_brand;
	
	public function __construct(){
		global $wpdb;

		$this->tbl_project_brand = $wpdb->prefix."project_brand";
	}	

	public function getTable(){
		return $this->tbl_project_brand;
	}

	public function updateBrand($brand_ids, $project_id){
		global $wpdb;

		$query = "DELETE FROM `".$this->tbl_project_brand."` WHERE project_id = %d";
		$wpdb->query($wpdb->prepare($query, $project_id));

		$query = "INSERT INTO `".$this->tbl_project_brand."` ( term_id, project_id ) VALUES ( %d, %d )";
		if($brand_ids && sizeof($brand_ids)){
			foreach($brand_ids as $key=>$value){
				$wpdb->query($wpdb->prepare($query, $value, $project_id));
			}
		}
	}

	public function getBrand($project_id){
		global $wpdb;

		$query = "SELECT term_id FROM `".$this->tbl_project_brand."` WHERE project_id = %d";
		return $wpdb->get_col($wpdb->prepare($query, $project_id));
	}
}