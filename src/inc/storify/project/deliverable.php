<?php
namespace storify\project;

class deliverable{
	
	private $tbl_deliverable;

	public function __construct(){
		global $wpdb;

		$this->tbl_deliverable = $wpdb->prefix."project_deliverable";
	}

	public function getTable(){
		return $this->tbl_deliverable;
	}

	public function setupDeliverable($project_id, $no_of_photo, $no_of_video, $deliverable_details){
		global $wpdb;

		$query = "SELECT id FROM `".$this->tbl_deliverable."` WHERE project_id = %d AND type = %s ORDER BY id ASC";
		$photo_ids = $wpdb->get_col($wpdb->prepare($query, $project_id, "photo"));
		$video_ids = $wpdb->get_col($wpdb->prepare($query, $project_id, "video"));

		if(sizeof($photo_ids) > $no_of_photo ){
			//remove extra photos
			for($i = $no_of_photo; $i < sizeof($photo_ids); $i++){
				$wpdb->delete(
					$this->tbl_deliverable,
					array( 'id' => $photo_ids[$i] ),
					array( '%d' )
				);
			}
		}else if(sizeof($photo_ids) < $no_of_photo ){
			//need to add photos
			$query = "INSERT INTO `".$this->tbl_deliverable."` ( project_id, type ) VALUES ( %d, %s )";
			for($i = 0 ; $i < ($no_of_photo - sizeof($photo_ids)); $i++){
				$wpdb->query($wpdb->prepare($query, $project_id, "photo")); //insert new item
			}
		}else{
			//amount remain the same, no actions needed.
		}

		if(sizeof($video_ids) > $no_of_video ){
			//remove extra video
			for($i = $no_of_video; $i < sizeof($video_ids); $i++){
				$wpdb->delete(
					$this->tbl_deliverable,
					array( 'id' => $video_ids[$i] ),
					array( '%d' )
				);
			}
		}else if(sizeof($video_ids) < $no_of_video ){
			//need to add video
			$query = "INSERT INTO `".$this->tbl_deliverable."` ( project_id, type ) VALUES ( %d, %s )";
			for($i = 0; $i < ($no_of_video - sizeof($video_ids)); $i++ ){
				$wpdb->query($wpdb->prepare($query, $project_id, "video")); //insert new item
			}
		}else{
			//amount remain the same, no action needed
		}

		//update deliverable details, nolonger in use
		//$this->updateDeliverableWithoutID($deliverable_details, $project_id);
	}

	public function updateDeliverableWithoutID($deliverables_ar, $project_id){
		if($deliverables_ar && sizeof($deliverables_ar)){
			$deliverables = $this->getDeliverables($project_id);
			foreach($deliverables_ar as $key=>$value){
				$this->updateSingleDeliverable($value["remark"], $deliverables[$key]["id"]);
			}
		}
	}

	public function updateDeliverable($deliverables_ar){
		foreach($deliverables_ar as $key=>$value){
			$this->updateSingleDeliverable($value["remark"], $value["id"]);
		}
	}

	public function updateSingleDeliverable($remark, $deliverable_id){
		global $wpdb;

		$wpdb->update(
			$this->tbl_deliverable,
			array(
				"remark"=>$remark
			),
			array( 'id' => $deliverable_id ),
			array(
				"%s"
			),
			array( '%d' )
		);
	}

	public function getDeliverableDetail($deliverable_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_deliverable."` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $deliverable_id), ARRAY_A);
	}

	public function getDeliverables($project_id){
		global $wpdb;

		$query = "SELECT id, type, remark FROM `".$this->tbl_deliverable."` WHERE project_id = %d ORDER BY type ASC, id ASC";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	public function getDeliverablesFull($project_id){
		global $wpdb;

		$query = "SELECT ";
	}
}