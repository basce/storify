<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(7200); //maximum execution time 2 hours
$debugTime = microtime(true);
function getDebugTime(){
    global $debugTime;
    return microtime(true) - $debugTime;
}

use storify\main as main;
use storify\job as job;

include("inc/main.php");

$main = new main();

$beginningTime = time();
function printLog($log){
	global $beginningTime;
	if(php_sapi_name() == "cli"){
		print_r("||". (time() - $beginningTime )." ".$log."\n");
	}
}

//cronjob need to be run on 9am SG time


//get project invitation closing, 3 day before
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) - 64 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) - 60 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(invitation_closing_date) - INTERVAL 2 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//nothing to trigger, no task required for invitation expired in 3 days
/*
foreach($project_ids as $key=>$value){
	
}
*/

//get project invitation closing, 1 day before
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) - 16 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) - 12 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = DATE(invitation_closing_date) ";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_invite_expire_before_1", array(
        "project_id"=>$value
    ), 1);
}

//get project invitation closed, on that day, should announce the next day
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) + 32 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) + 36 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(invitation_closing_date) + INTERVAL 2 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_invite_expired", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closing, 3 days before
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) - 64 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) - 60 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(closing_date) - INTERVAL 2 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_closing_before_3", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closing in 1 day, 1 day before
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) - 16 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) - 12 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = DATE(closing_date)";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_closing_before_1", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closed, should announce next day
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 32 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 36 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(closing_date) + INTERVAL 2 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_close", array(
        "project_id"=>$value
    ), 1);
}

//get project summary 2 days after
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 56 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) +  60 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(closing_date) + INTERVAL 3 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project summary 6 days after
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 152 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 156 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(closing_date) + INTERVAL 7 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project summary, 13 days after
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 320 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 324 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = ( DATE(closing_date) + INTERVAL 14 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project closed, on the day, announce next day
//$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 360 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 364 * 3600 )";
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND CURDATE() = DATE(closing_date)  + INTERVAL 1 DAY )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "close"));

//check if the task had execute before. since can trigger manually
foreach($project_ids as $key=>$value){
	if(job::checkFlagExist("project_close_".$value)){ 
		// flag found, skip this item

	}else{
		// send whole project close email
		job::addFlag("project_close_".$value, $value);
		job::add(0, "project_close", array(
	        "project_id"=>$value
	    ), 1);
	}
}