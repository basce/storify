<?php
namespace storify;

class notification{

	function __construct(){

	}

	public function addTask($name, $target_uid, $type, $data, $expected_time, $groupable){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."task` ( name, notify_to, type, data, expected_time, groupable, status ) VALUES ( ?, ?, ?, ?, ?, ?, ? )";
		$wpdb->query($wpdb->prepare($query, $name, $target_uid, $type, $data, $expected_time, $groupable, "open"));
	}

	public function addRecord($name, $target_uid, $type, $data, $expected_time){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."notification` ( name, uid, type, data, expected_time ) VALUES ( ?, ?, ?, ?, ?)";
		$wpdb->query($wpdb->prepare($query, $name, $target_uid, $type, $data, $expected_time));
	}	

	public function getTasks(){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."task WHERE status = %s`";
		$result = $wpdb->query($wpdb->prepare($query, "open"));

		//group all groupable task for 
		

		//group all uid 
		

		return $result;
	}

	public function getRecords($uid, $pagesize=24, $page=1){
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."notification` WHERE uid = %d ORDER BY tt DESC LIMIT %d, %d";
		$data = $wpdb->get_results($wpdb->prepare($query, $uid, ($page - 1)*$pagesize, $pagesize), ARRAY_A);

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."notification` WHERE uid = %d";
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