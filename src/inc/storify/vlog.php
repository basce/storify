<?php
namespace storify;

class vlog{

	private static $instance = null;
	private static $log_id = null;
	private static $paramObj = null;
	private static $log_table = null;
	private static $log_detail_table = null;

	// private constructor, can only access via within this class
	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		self::$paramObj = array();

		// cli or calling from browser
		$mode = "";
		if (php_sapi_name() == "cli") {
		    // In cli-mode
		    $mode = "cli";
		    
		} else {
		    // Not in cli-mode
		    $mode = "visit";
		    $ip = self::ip_address();
		    self::$paramObj["IP"] = $ip;
		}

		self::$log_table = $wpdb->prefix."vlog";
		self::$log_detail_table = $wpdb->prefix."vlog_detail";

		// add new log data, all other log will save under this data

		$query = "INSERT INTO `".self::$log_table."` ( name, mode, param ) VALUES ( %s, %s, %s )";
		$wpdb->query($wpdb->prepare($query, "log ". date("Y-m-d H:i:s"), $mode, json_encode(self::$paramObj)));

		self::$log_id = $wpdb->insert_id;
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new vlog();
		}

		return self::$instance;
	}


	private function _getTable($name){

		switch($name){
			case "log":
				return self::$log_table;
			break;
			case "detail":
				return self::$log_detail_table;
			break;
		}
	}

	public static function getTable($name){

		$singleton = self::getInstance();
		return $singleton->_getTable($name);

	}

	private function _trace($msg, $data = null){
		global $wpdb, $current_user, $default_group_id;

		$paramchange = false;

		$bt = debug_backtrace();
	    $caller = array_shift($bt);
	    $caller2 = array_shift($bt);

	    $msg = $caller2['file']." ".$caller2['line']." ".$msg;

		// check current user

		if(	!isset(self::$paramObj["current_user"]) ){
			if( isset($current_user) && $current_user->ID ){
				$paramchange = true;
				self::$paramObj["current_user"] = $current_user->ID;
			}
		}else{
			if( isset($current_user) && ( self::$paramObj["current_user"] != $current_user->ID ) ){
				$paramchange = true;
				self::$paramObj["current_user"] = $current_user->ID;
			}
		}

		// check default_group_id

		if( !isset(self::$paramObj["businesss_group_id"]) ){
			if( isset($default_group_id) ){
				$paramchange = true;
				self::$paramObj["business_group_id"] = $default_group_id;
			}
		}else{
			if( isset($default_group_id) && ( self::$paramObj["business_group_id"] != $default_group_id ) ){
				$paramchange = true;
				self::$paramObj["business_group_id"] = $default_group_id;
			}
		}

		// check session
		if( session_id() == '' || !isset($_SESSION) ){
			if( !isset(self::$paramObj["role"]) ){
				if( isset($_SESSION["role_view"]) && $_SESSION["role_view"] ){
					$paramchange = true;
					self::$paramObj["role"] = $_SESSION["role_view"];
				}
			}else{
				if( isset($_SESSION["role_view"]) && ( self::$paramObj["role"] != $_SESSION["role_view"] ) ){
					$paramchange = true;
					self::$paramObj["role"] = $_SESSION["role_view"];
				}
			}
		}

		// update DB if param 

		if( $paramchange ){

			$query = "UPDATE `".self::$log_table."` SET param = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare( $query, json_encode(self::$paramObj), self::$log_id ));

		}

		// insert log detail

		if($data){

			$query = "INSERT INTO `".self::$log_detail_table."` ( log_id, msg, data ) VALUES ( %d, %s, %s )";
			$wpdb->query($wpdb->prepare( $query, self::$log_id, $msg, json_encode($data) ));

		}else{

			$query = "INSERT INTO `".self::$log_detail_table."` ( log_id, msg ) VALUES ( %d, %s )";
			$wpdb->query($wpdb->prepare( $query, self::$log_id, $msg ));

		}

	}

	public static function trace($msg, $data = null){

		$singleton = self::getInstance();
		return $singleton->_trace($msg, $data);

	}

	private function _getLogList($after = null){
		global $wpdb;

		$pagesize = 100;
		if($after){
			$query = "SELECT * FROM `".self::$log_table."` WHERE id < %d ORDER BY id DESC LIMIT 100";
			return $wpdb->get_results($wpdb->prepare($query, $after));
		}else{
			$query = "SELECT * FROM `".self::$log_table."` ORDER BY id DESC LIMIT 100";
			return $wpdb->get_results($query);
		}
	}

	public static function getLogList($after){

		$singleton = self::getInstance();
		return $singleton->_getLogList($after);

	}

	private function _getLogDetail($log_id, $after = null){
		global $wpdb;

		$pagesize = 100;
		if($after){

			$query = "SELECT * FROM `".self::$log_detail_table."` WHERE id < %d AND log_id = %d ORDER BY id DESC LIMIT 100";
			return $wpdb->get_results($wpdb->prepare($query, $after, $log_id));

		}else{

			$query = "SELECT * FROM `".self::$log_detail_table."` WHERE log_id = %d ORDER BY id DESC LIMIT 100";
			return $wpdb->get_results($wpdb->prepare($query, $log_id));

		}

	}

	public static function getLogDetail($log_id, $after = null){

		$singleton = self::getInstance();
		return $singleton->_getLogDetail($log_id, $after);

	}


	// get IP address functions
	private static function valid_ip($ip)
	{
		$ip_segments = explode('.', $ip);

		// Always 4 segments needed
		if (count($ip_segments) != 4)
		{
			return FALSE;
		}
		// IP can not start with 0
		if ($ip_segments[0][0] == '0')
		{
			return FALSE;
		}
		// Check each segment
		foreach ($ip_segments as $segment)
		{
			// IP segments must be digits and can not be
			// longer than 3 digits or greater then 255
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
			{
				return FALSE;
			}
		}

		return TRUE;
	}
	
	private static function ip_address(){
		$ip_address = false;
		if (isset($_SERVER['REMOTE_ADDR']) AND isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ($_SERVER('HTTP_X_FORWARDED_FOR'))
		{
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if ($ip_address === FALSE)
		{
			return '0.0.0.0';
		}

		if (strpos($ip_address, ',') !== FALSE)
		{
			$x = explode(',', $ip_address);
			$ip_address = trim(end($x));
		}

		if ( ! self::valid_ip($ip_address))
		{
			return '0.0.0.0';
		}

		return $ip_address;
	}
}