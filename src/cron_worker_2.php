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


/*
Job type
##new_register
data needed : uid
action : 
1 ) send email 1 when user has IG
2 ) send email 2 when user don't have IG
*/

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
$tasks = array();
foreach($jobs_group as $key=>$value){
	switch($value["type"]){
		case "new_register":
			if(!isset($tasks["new_register"])){
				$tasks["new_register"] = array();
			}
			$tasks["new_register"][] = $value;
		break;
		case "project_invite":
			if(!isset($tasks["project_invite"])){
				$tasks["project_invite"] = array();
			}
			$tasks["project_invite"][] = $value;
		break;
		case "project_invite_accept_creator":
			if(!isset($tasks["project_invite_accept_creator"])){
				$tasks["project_invite_accept_creator"] = array();
			}
			$tasks["project_invite_accept_creator"][] = $value;
		break;
		case "project_invite_reject_creator":
			if(!isset($tasks["project_invite_reject_creator"])){
				$tasks["project_invite_reject_creator"] = array();
			}
			$tasks["project_invite_reject_creator"][] = $value;
		break;
		case "project_invite_accept_brand":
			if(!isset($tasks["project_invite_accept_brand"])){
				$tasks["project_invite_accept_brand"] = array();
			}
			$tasks["project_invite_accept_brand"][] = $value;
		break;
		case "project_invite_reject_brand":
			if(!isset($tasks["project_invite_reject_brand"])){
				$tasks["project_invite_reject_brand"] = array();
			}
			$tasks["project_invite_reject_brand"][] = $value;
		break;
		default:
			//undefined task
		break;
	}
}

$batchEmailTask = array();

foreach($tasks as $key=>$task){
	$functionname = "worker_job_".$key;
	if(function_exists($functionname)){
		foreach($task as $key2=>$item){
			$result = $functionname($item["data"]);
			if( isset($result["complete"]) && $result["complete"] ){
				job::update($item["id"],"running");
				if( isset($result["type"]) && $result["type"] == "email" ){
					if(!isset($batchEmailTask[$result["emaildata"]["template"]])){
						$batchEmailTask[$result["emaildata"]["template"]] = array();
					}
					$batchEmailTask[$result["emaildata"]["template"]][] = array(
						"id"=>$item["id"],
						"data"=>$result["emaildata"]["data"]
					);
				}else if( isset($result["type"]) && $result["type"] == "emails" ){
					if(!isset($batchEmailTask[$result["emaildata"]["template"]])){
						$batchEmailTask[$result["emaildata"]["template"]] = array();
					}
					foreach($result["emaildata"]["data"] as $key3=>$value3){
						$batchEmailTask[$result["emaildata"]["template"]][] = array(
							"id"=>$item["id"],
							"data"=>$value3
						);	
					}
				}
			}else{
				job::update($item["id"],"fail");
			}
		}
	}
}

foreach( $batchEmailTask as $key => $value ){
	$chunk = array_chunk($value, 50);
	foreach( $chunk as $key2 => $value2 ){
		$emaildata = array();
		$job_ids = array();
		foreach( $value2 as $key3 => $value3 ){
			$job_ids[] = $value3["id"];
			$emaildata[] = $value3["data"];
		}

		if(sizeof($emaildata)){
			$email_result = $main->sendLambdaBatchEmail(
				$emaildata, 
				array(
					"name"=>"Storify",
					"email"=>"hello@storify.me"
				),
				$key
			);

			if(!$email_result["error"]){
				if(isset($email_result["status"]) && sizeof($email_result["status"])){
					foreach($email_result["status"] as $key4 => $value4 ){
						if( $value4["Status"] == "Success" ){
							job::update($job_ids[$key4],"complete");
						}else{
							job::update($job_ids[$key4],"retry");
						}
					}
				}else{
					//sent fail
					job::updateBatch($job_ids,"retry");
				}
			}
		}else{
			//no data
		}

	}
}


?>