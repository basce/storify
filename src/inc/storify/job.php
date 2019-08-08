<?php
namespace storify;

class job{

	function __construct(){

	}

	public static function cancel($user_id, $type, $data = ""){
		global $wpdb;

		if($data){
			$query = "SELECT id FROM `".$wpdb->prefix."workjob` WHERE user_id = %d AND type = %s AND data = %s AND ( status = %s OR status = %s )";
			$id = $wpdb->get_var($wpdb->prepare($query, $user_id, $type, json_encode($data), "new", "retry"));

		}else{
			$query = "SELECT id FROM `".$wpdb->prefix."workjob` WHERE user_id = %d AND type = %s AND ( status = %s OR status = %s )";
			$id = $wpdb->get_var($wpdb->prepare($query, $user_id, $type, "new", "retry"));
		}

		if($id){
			$query = "UPDATE `".$wpdb->prefix."workjob` SET status = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, "cancel", $id));
		}
	}

	public static function add($user_id, $type, $data, $after){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."workjob` ( user_id, type, data, after, status ) VALUES ( %d, %s, %s, FROM_UNIXTIME( UNIX_TIMESTAMP() + %d ), %s )";
		$wpdb->query($wpdb->prepare($query, $user_id, $type, json_encode($data), $after, "new"));
	}

	public static function addUpdate($unique_code, $type, $data, $after){
		global $wpdb;

		$query = "SELECT id FROM `".$wpdb->prefix."workjob` WHERE unique_code = %s AND type = %s";
		$job_id = $wpdb->get_var($wpdb->prepare($query, $unique_code, $type));
		if($job_id){
			//job exist, update with new after and data
			$query = "UPDATE `".$wpdb->prefix."workjob` SET data = %s, after = FROM_UNIXTIME( UNIX_TIMESTAMP() + %d ), status = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, json_encode($data), $after, "new", $job_id)); //reset status back to new
		}else{
			//job not exist
			$query = "INSERT INTO `".$wpdb->prefix."workjob` ( unique_code, type, data, after, status ) VALUES ( %s, %s, %s, FROM_UNIXTIME( UNIX_TIMESTAMP() + %d ), %s )";
			$wpdb->query($wpdb->prepare($query, $unique_code, $type, json_encode($data), $after, "new"));
		}
	}

	public static function get(){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."workjob` WHERE after < NOW() AND ( status = %s OR status = %s )";
		return $wpdb->get_results($wpdb->prepare($query, 'new', 'retry'), ARRAY_A);
	}

	public static function update($id, $status){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."workjob` SET status = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $id));
	}

	public static function updateBatch($id, $status){
		global $wpdb;

		$count = sizeof($id);
		$placeholder = array_fill(0, $count, '%d');
		$format = implode(", ", $placeholder);
		$query = "UPDATE `".$wpdb->prefix."workjob` SET status = %s WHERE id IN (".$format.")";


		$wpdb->query($wpdb->prepare($query, array_merge(array($status), $id)));
	}

	public static function addLog($job_id, $log){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."workjoblog` ( job_id, content ) VALUES ( %d, %s )";
		$wpdb->query($wpdb->prepare($job_id, $log));
	}

	public static function getPassiveJob($user_id, $type){
		global $wpdb;
		
		$query = "SELECT * FROM `".$wpdb->prefix."workpassivejob` WHERE user_id = %d AND type = %s AND status = %s";
		return $wpdb->get_row($wpdb->prepare($query, $user_id, $type, "open"), ARRAY_A);
	}

	public static function addPassiveJob($user_id, $type){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."workpassivejob` WHERE user_id = %d AND type = %s AND status = %s";
		$result = $wpdb->get_results($wpdb->prepare($query, $user_id, $type, "open"), ARRAY_A);

		if(sizeof($result)){
			//do nothing, same passivejob already exist, won't allow duplicated job.
		}else{
			//new
			$query = "INSERT INTO `".$wpdb->prefix."workpassivejob` ( user_id, type, status ) VALUES ( %d, %s, %s )";
			$wpdb->query($wpdb->prepare($query, $user_id, $type, "open"));
		}
	}

	public static function updatePassiveJob($id, $status){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."workpassivejob` SET status = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $id));
	}

	public static function checkJobStatus($user_id, $type){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."workjob` WHERE user_id = %d AND type = %s";
		$result = $wpdb->get_results($wpdb->prepare($query, $user_id, $type), ARRAY_A);

		if(sizeof($result)){
			//item exist
			$job_detail = $result[0];
			$job_detail["exist"] = 1;
			return $job_detail;
		}else{
			return array(
				"exist"=>0
			);
		}
	}

	public static function addFlag($flag){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."flag` ( flag ) VALUES ( %s )";
		$wpdb->query($wpdb->prepare($query, $flag));
	}

	public static function checkFlagExist($flag){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."flag` WHERE flag = %s";
		return $wpdb->get_var($wpdb->prepare($query, $flag));
	}
}