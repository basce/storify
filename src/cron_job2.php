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

//get project invitation closing in 3 days, 86-88 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(invitation_closing_date) - 309600 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(invitation_closing_date) - 316800 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project invitation closing in 1 day, 38-40 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(invitation_closing_date) - 136800 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(invitation_closing_date) - 144000 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project invitation closed, 8-10 hours after 
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(invitation_closing_date) + 28800 ) > UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(invitation_closing_date) + 36000 ) < UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project submission closing in 3 days, 86-88 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(closing_date) - 309600 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(closing_date) - 316800 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project submission closing in 1 day, 38-40 hours before
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(closing_date) - 136800 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(closing_date) - 144000 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project submission closed, 8-10 hours after 
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(closing_date) + 28800 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(closing_date) + 28800 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "open"));

//get project closed, 8-10 hours after
$query = "SELECT id FROM `".$wpdb->prefix."project` WHERE status = %s AND ( UNIX_TIMESTAMP(closing_date) + 28800 ) < UNIX_TIMESTAMP() AND ( UNIX_TIMESTAMP(closing_date) + 28800 ) > UNIX_TIMESTAMP()";
$project_ids = $wpdb->get_col($wpdb->prepare($query, "close"));


