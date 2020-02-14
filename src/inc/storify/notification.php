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

	/*
	add notification query string
	*/
	public static function _query_add($input){
		global $wpdb;

		self::getInstance(); // initialize

		$default = array(
			"project_id"=>0,
			"user_id"=>0,
			"group_id"=>0,
			"type"=>"",
			"message"=>"",
			"data"=>array()
		);

		$a = array_merge(
			$default,
			$input
		);

		$query = "INSERT INTO `".self::$notification_table."` ( project_id, user_id, group_id, type, message, data ) VALUES ( %d, %d, %d, %s, %s, %s )";
		return $wpdb->prepare( $query, $a["project_id"], $a["user_id"], $a["group_id"], $a["type"], $a["message"], json_encode($a["data"]) );
	}

	private static function _add($input){
		global $wpdb;

		$wpdb->query( self::_query_add($input) );

		$id = $wpdb->insert_id;

		return $id;
	}

	public static function add($input){
		return self::_add($input);
	}

	/*
	get notification query string
	*/
	public static function _query_get($input){
		global $wpdb;

		self::getInstance(); // initialize

		$default = array(
			"project_id"=>0,
			"user_id"=>0,
			"group_id"=>0,
			"onlynew"=>0,
			"after"=>0,
			"size"=>24
		);

		$a = array_merge(
			$default,
			$input
		);
		
		if($a["user_id"] == 0){

			// get notification for brand

			if($a["onlynew"]){

				if($a["after"] == 0){

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d AND viewed = %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["group_id"], 0, $a["size"] );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d AND viewed = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["group_id"], 0, $a["after"], $a["size"] );

				}

			}else{
				
				if($a["after"] == 0){

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["group_id"], $a["size"] );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND group_id = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["group_id"], $a["after"], $a["size"] );

				}

			}

		}else{

			// get notification for creator

			if($a["onlynew"]){

				if($a["after"] == 0){

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d AND viewed = %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["user_id"], 0, $a["size"] );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d AND viewed = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["user_id"], 0, $a["after"], $a["size"] );

				}

			}else{

				if($a["after"] == 0){

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["user_id"], $a["size"] );

				}else{

					$query = "SELECT id, project_id, user_id, group_id, type, message, data, UNIX_TIMESTAMP(tt) as `timestamp`, tt, viewed FROM `".self::$notification_table."` WHERE project_id = %d AND user_id = %d AND UNIX_TIMESTAMP(tt) < %d ORDER BY tt DESC LIMIT %d";
					return $wpdb->prepare( $query, $a["project_id"], $a["user_id"], $a["after"], $a["size"] );

				}

			}
		}		
	}

	private static function _get($input){
		global $wpdb;

		$query = self::_query_get($input);
		$results = $wpdb->get_results($query, ARRAY_A);

		foreach($results as $key=>$value){
			$results[$key]["data"] = json_decode($value["data"], true);
		}

		return $results;
	}

	public static function get($input){
		return self::_get($input);
	}

	public static function _query_view($input){
		global $wpdb;

		self::getInstance(); // initialize

		$default = array(
			"id"=>0,
			"viewed"=>1
		);

		$a = array_merge(
			$default,
			$input
		);

		$query = "UPDATE `".self::$notification_table."` SET viewed = %d WHERE id = %d";
		return $wpdb->prepare( $query, $a["viewed"], $a["id"] );
	}

	private static function _view($input){
		global $wpdb;

		$wpdb->query( self::_query_view($input) );
	}

	public static function view($input){
		return self::_view($input);
	}

}