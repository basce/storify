<?php
namespace storify;

class job{

	function __construct(){

	}

	public static function add($user_id, $type, $data, $after){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."workjob` ( user_id, type, data, after, status ) VALUES ( %d, %s, %s, DATE_ADD(NOW(), INTERVAL %d MINUTE), %s )";
		$wpdb->query($wpdb->prepare($query, $user_id, $type, json_encode($data), $after, "new"));
	}

	public static function get(){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."workjob` WHERE after < NOW() AND status = %s";
		return $wpdb->get_results($wpdb->prepare($query, 'new'), ARRAY_A);
	}

	public static function update($id, $status){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."workjob` SET status = %s WHERE user_id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $id));
	}

	public static function checkJobStatus($user_id, $type){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."workjob` WHERE user_id = %d AND type = %s";
		$result = $wpdb->get_results($wpdb->prepare($query, $user_id, $type), ARRAY_A);

		if(sizeof($result)){
			//item exist
		}else{
			return array(
				
			);
		}
	}
}