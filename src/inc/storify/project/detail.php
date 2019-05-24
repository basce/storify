<?php
namespace storify\project;

class detail{
	private $tbl_project_detail;
	
	public function __construct(){
		global $wpdb;

		$this->tbl_project_detail = $wpdb->prefix."project_detail";
	}	

	public function getProjectDetailTable(){
		return $this->tbl_project_detail;
	}

	public function getDetail($project_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_project_detail."` WHERE project_id = %d";
		$result = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);
		return $result;
	}

	public function save($project_id, $input_pairs){
		global $wpdb;

		$query = "SELECT id FROM `".$this->tbl_project_detail."` WHERE project_id = %d";
		$project_detail_id = $wpdb->get_var($wpdb->prepare($query, $project_id));

		if(!$project_detail_id){
			$query = "INSERT INTO `".$this->tbl_project_detail."` ( project_id ) VALUES ( %d )";
			$wpdb->query($wpdb->prepare($query, $project_id));
			$project_detail_id = $wpdb->insert_id;
		}

		$wppairs = $this->convertToWpdbPairs($input_pairs);

		if(sizeof($wppairs["value"])){
			if($project_detail_id){
				//project data exist, update
				$wpdb->update(
					$this->tbl_project_detail,
					$wppairs["value"],
					array( 'id'=> $project_detail_id ),
					$wppairs["prepare"],
					array( '%d' )
				);
			}else{
				//project data not exist, insert
				$wpdb->insert(
					$this->tbl_project_detail,
					$wppairs["value"],
					$wppairs["prepare"]
				);
			}
		}else{
			die("Input pairs error");
		}
	}

	public function convertToWpdbPairs($input){
		$inputtype_pairs = array(
			"name"=>"%s",
			"description_brief"=>"%s",
			"deliverable_brief"=>"%s",
			"other_brief"=>"%s",
			"short_description"=>"%s",
			"no_of_photo"=>"%d",
			"no_of_video"=>"%d",
			"video_length"=>"%d",
			"bounty_type"=>"%s",
			"cost_per_photo"=>"%d",
			"cost_per_video"=>"%d",
			"reward_name"=>"%s",
			"closing_date"=>"%s",
			"invitation_closing_date"=>"%s"
		);

		$value_pairs = array();
		$prepare_pairs = array();

		foreach($input as $key=>$value){
			if(isset($inputtype_pairs[$key])){
				$value_pairs[$key] = $value;
				$prepare_pairs[] = $inputtype_pairs[$key];
			}
		}

		return array(
			"value"=>$value_pairs,
			"prepare"=>$prepare_pairs
		);
	}
}