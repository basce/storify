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

//get all job
$jobs = job::get();

//group job, need to add in the function to group job together
$jobs_group = array();
foreach($jobs as $key=>$value){
	$temp_job = $value;
	$temp_job["data"] = json_decode($temp_job["data"], true);
	$jobs_group[] = $temp_job;
}

//loop though all job
foreach($jobs_group as $key=>$value){
	switch($value["type"]){
		case "new_register":

		break;
	}
}

function worker_job_new_register($data){
	global $wpdb, $main;

	//check whether user had been connect IG
	$query = "SELECT COUNT(*) FROM `".$wpdb."igaccounts` WHERE userid = %d";
	if($wpdb->get_var($wpdb->prepare($query, $data["userid"]))){
		//connected, send Connected Email
		$email_result = $main->sendLamdbaEmail(
			array(
				"name"=>$data["name"],
				"email"=>$data["email"]
			),
			array(
				"body"=>
			)
		);
		return array(
			"complete"=>1
		)
	}else{
		//no connected, send not connected Email

		return array(
			"complete"=>1
		)
	}
}
?>