<?php
namespace storify;

class notification{

	private static $instance = null;
	private static $notification_table = null;


	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		self::$notification_table = $wpdb->prefix."notification";

	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new notification();
		}

		return self::$instance;
	}

	private static function _test_add($project_id, $user_id, $group_id, $type, $message, $data){
		global $wpdb;

		$query = "INSERT INTO `".self::$notification_table."` ( project_id, user_id, group_id, type, message, data ) VALUES ( %d, %d, %d, %s, %s, %s )";
		$query_string = $wpdb->prepare( $query, $project_id, $user_id, $group_id, $type, $message, json_encode($data) );

		return array(
			$query_string;
		);
	}

	private static function _test_get($project_id, $user_id = 0, $group_id = 0, $onlynew = 0, $after = 0, $size = 30){

	}


	private static function _add($project_id, $user_id, $group_id, $type, $message, $data){
		global $wpdb;

		$query = "INSERT INTO `".self::$notification_table."` ( project_id, user_id, group_id, type, message, data ) VALUES ( %d, %d, %d, %s, %s, %s )";
		$wpdb->query( $wpdb->prepare( $query, $project_id, $user_id, $group_id, $type, $message, json_encode($data) ) );

		$id = $wpdb->insert_id;

		return $id;
	}

	private static function _get($project_id, $user_id = 0, $group_id = 0, $onlynew = 0, $after = 0, $size = 30){
		global $wpdb;

		if($user_id == 0){

			// get notification for brand

			if($onlynew){

				if($after = 0){

					$query = "SELECT id, project_id, user_id, group_id, type, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d AND viewed = %d AND ORDER BY tt DESC LIMIT %d";
					$results = $wpdb->get_results( $wpdb->prepare( $query, $project_id, $group_id, 0, $size ), ARRAY_A );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d AND viewed = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					$results = $wpdb->get_results( $wpdb->prepare( $query, $project_id, $group_id, 0, $after, $size ), ARRAY_A );

				}

				$nextAfter = $after;

				if(sizeof($results)){
					// get the last results timestamp

					$nextAfter = $results[sizeof($results) - 1]["timestamp"];
				}

			}else{
				
				if($after = 0){

					$query = "SELECT id, project_id, user_id, group_id, type, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d AND viewed = %d AND ORDER BY tt DESC LIMIT %d";
					$results = $wpdb->get_results( $wpdb->prepare( $query, $project_id, $user_id, 0, $size ), ARRAY_A );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d AND viewed = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					$results = $wpdb->get_results( $wpdb->prepare( $query, $project_id, $user_id, 0, $after, $size ), ARRAY_A );

				}

				$nextAfter = $after;

				if(sizeof($results)){
					// get the last results timestamp

					$nextAfter = $results[sizeof($results) - 1]["timestamp"];
				}

			}

		}else{

			// get notification for creator

		}

	}



}
*/