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


//get project invitation closing in 3 days, 60-64 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) - 64 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) - 60 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//nothing to trigger, no task required for invitation expired in 3 days
/*
foreach($project_ids as $key=>$value){
	
}
*/

//get project invitation closing in 1 day, 12-16 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) - 16 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) - 12 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_invite_expire_before_1", array(
        "project_id"=>$value
    ), 1);
}

//get project invitation closed, 32-36 hours after 
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(invitation_closing_date) + 32 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(invitation_closing_date) + 36 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_invite_expired", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closing in 3 days, 60-64 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) - 64 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) - 60 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_closing_before_3", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closing in 1 day, 12-16 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) - 16 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) - 12 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_closing_before_1", array(
        "project_id"=>$value
    ), 1);
}

//get project submission closed, 32-36 hours after 
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 32 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 36 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "submission_close", array(
        "project_id"=>$value
    ), 1);
}

//get project summary 2 days after, 2*24+8 - 2*24+12
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 56 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) +  60 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project summary 6 days after, 6*24+8 - 6*24+12 hours
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 152 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 156 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project summary, 13 days after, 13*24+8-13*24+12 hours
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 320 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 324 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

foreach($project_ids as $key=>$value){
	job::add(0, "project_summary", array(
        "project_id"=>$value
    ), 1);
}

//get project closed, 8-12 hours after
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE hide = 0 AND status = %s AND UNIX_TIMESTAMP() > ( UNIX_TIMESTAMP(closing_date) + 360 * 3600 ) AND UNIX_TIMESTAMP() < ( UNIX_TIMESTAMP(closing_date) + 364 * 3600 )";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "close"));

//check if the task had execute before. since can trigger manually
foreach($project_ids as $key=>$value){
	if(job::checkFlagExist("project_close_".$value)){ 
		// flag found, skip this item

	}else{
		// send whole project close email
		job::addFlag("project_close_".$value);
		job::add(0, "project_close", array(
	        "project_id"=>$value
	    ), 1);
	}
}


