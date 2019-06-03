<?php
namespace storify;

class notification{

	function __construct(){

	}

	public static function addTask($name, $target_uid, $type, $data, $expected_time, $groupable){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."project_task` ( name, notify_to, type, data, expected_time, groupable, status ) VALUES ( %s, %d, %s, %s, %s, %d, %s )";
		$wpdb->query($wpdb->prepare($query, $name, $target_uid, $type, $data, $expected_time, $groupable, "open"));
	}

	public static function updateTaskStatus($task_id, $status){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."project_task` SET status = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $status, $task_id));
	}

	public static function batchUpdateTaskStatus($task_ids, $status){
		global $wpdb;

		if(sizeof($task_ids)){
			$placeholders = array_fill(0, sizeof($task_ids), '%d');
			$format = implode(", ", $placeholders);
			$query = "UPDATE `".$wpdb->prefix."project_task` set status = %s WHERE id IN ( ".$format." )";

			$params = $task_ids;
			array_unshift($params, $status);
			$wpdb->query($wpdb->prepare($query, $params));
		}
	}

	public static function getActiveTasksByUID($uid){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."project_task` WHERE notify_to = %d AND status = %s";
		return $wpdb->get_results($wpdb->prepare($query, $uid, 'open'), ARRAY_A);
	}

	public static function getTasksByID($task_id){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."project_task` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $task_id), ARRAY_A);
	}

	public static function addRecord($name, $target_uid, $type, $data){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."project_notification` (  name, uid, type, data ) VALUES ( ?, ?, ?, ? )";
		$wpdb->query($wpdb->prepare($query, $name, $target_uid, $type, $data));
	}	

	public static function getRecords($uid, $pagesize=24, $page=1){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."project_notification` WHERE uid = %d ORDER BY tt DESC LIMIT %d, %d";
		$data = $wpdb->get_results($wpdb->prepare($query, $uid, ($page - 1)*$pagesize, $pagesize), ARRAY_A);

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project_notification` WHERE uid = %d";
		$totalsize = $wpdb->get_var($wpdb->prepare($query, $uid));

		$totalpage = ceil($totalsize / $pagesize);

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}
}