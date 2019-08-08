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
		case "ig_connect":
			if(!isset($tasks["ig_connect"])){
				$tasks["ig_connect"] = array();
			}
			$tasks["ig_connect"][] = $value;
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
					foreach($result["emaildatas"] as $key3=>$value3){
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

if(sizeof($batchEmailTask)){
	print_r($batchEmailTask);
	$main->addWorkJobLog("Total ".sizeof($batchEmailTask)." Sent", $batchEmailTask);
}else{
	print_r("no batch email");
}

//all worker function
function worker_job_project_invite_reject_brand($data){
	global $wpdb, $main;

	//get user id 
	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $user->ID));

	//project id 
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	//get admin ids
	$query = "SELECT user_id FROM `".$wpdb->prefix."project_user` WHERE project_id = %d AND role = %s";
	$admin_id = $wpdb->get_var($wpdb->prepare($query, $data["project_id"], "admin")); // only get 1, should get multiple in future
	$admin = get_user_by('id', $admin_id);

	$email_name = $admin->first_name ? $admin->first_name : $admin->display_name;
	$email_url = $admin->user_email;

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Someone";
	}
	$project_name = isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["name"]) ? $project["data"]["summary"]["name"] : "N/A";
	$project_link = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->id) ? get_home_url()."/project/".$project["data"]["detail"]->id : "N/A";
	$bounty_type = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->bounty_type) ? $project["data"]["detail"]->bounty_type : "N/A";

	//calculate cash & others
	$number_of_photo = $project["data"]["detail"]->no_of_photo == 1 ? "1 photo" : $project["data"]["detail"]->no_of_photo." photos";
	$number_of_video = $project["data"]["detail"]->no_of_video == 1 ? "1 video" : $project["data"]["detail"]->no_of_video." videos";

	if($project["data"]["detail"]->no_of_photo == 0){
		$number_of_items = $number_of_video;
	}else if($project["data"]["detail"]->no_of_video == 0){
		$number_of_items = $number_of_photo;
	}else{
		$number_of_items = $number_of_photo." and ".$number_of_video;
	}

	$cash = $project["data"]["detail"]->no_of_photo * $project["data"]["detail"]->cost_per_photo + $project["data"]["detail"]->no_of_video * $project["data"]["detail"]->cost_per_video;
	$reward_name = $project["data"]["detail"]->reward_name;

	$bounty = "N/A";
	$cash_or_sponsorship = "N/A";
	switch($bounty_type){
		case "gift":
			$cash_or_sponsorship = "Sponsorship";
			$bounty = $reward_name;
		break;
		case "cash":
			$cash_or_sponsorship = "Cash";
			$bounty = "$".$cash;
		break;
		case "both":
			$cash_or_sponsorship = "Cash and sponsorship";
			$bounty = "$".$cash." and ".$cash_or_sponsorship;
		break;
		default:
			$cash_or_sponsorship = "N/A";
			$bounty = "N/A";
		break;
	}

	$data = array(
		"to"=>array(
			"name"=>$email_name,
			"email"=>$email_url
		),
		"data"=>array(
			"igusername"=>$igusername,
			"project_name"=>$project_name,
			"first_name"=>$admin->first_name ? $admin->first_name : $admin->display_name,
			"brand"=>$brand,
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items,
			"detail_link"=>$project_link
		)
	);

	return array(
		"complete"=>1,
		"type"=>"email",
		"emaildata"=>array(
			"template"=>"storify_invite_reject_brand",
			"data"=>$data
		)
	);
}

function worker_job_project_invite_reject_creator($data){
	global $wpdb, $main;

	//get user id 
	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $user->ID));

	//project id 
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], $data["creator_id"]);

	$email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Someone";
	}
	$project_name = isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["name"]) ? $project["data"]["summary"]["name"] : "N/A";
	$project_link = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->id) ? get_home_url()."/project/".$project["data"]["detail"]->id : "N/A";
	$bounty_type = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->bounty_type) ? $project["data"]["detail"]->bounty_type : "N/A";

	//calculate cash & others
	$number_of_photo = $project["data"]["detail"]->no_of_photo == 1 ? "1 photo" : $project["data"]["detail"]->no_of_photo." photos";
	$number_of_video = $project["data"]["detail"]->no_of_video == 1 ? "1 video" : $project["data"]["detail"]->no_of_video." videos";

	if($project["data"]["detail"]->no_of_photo == 0){
		$number_of_items = $number_of_video;
	}else if($project["data"]["detail"]->no_of_video == 0){
		$number_of_items = $number_of_photo;
	}else{
		$number_of_items = $number_of_photo." and ".$number_of_video;
	}

	$cash = $project["data"]["detail"]->no_of_photo * $project["data"]["detail"]->cost_per_photo + $project["data"]["detail"]->no_of_video * $project["data"]["detail"]->cost_per_video;
	$reward_name = $project["data"]["detail"]->reward_name;

	$bounty = "N/A";
	$cash_or_sponsorship = "N/A";
	switch($bounty_type){
		case "gift":
			$cash_or_sponsorship = "Sponsorship";
			$bounty = $reward_name;
		break;
		case "cash":
			$cash_or_sponsorship = "Cash";
			$bounty = "$".$cash;
		break;
		case "both":
			$cash_or_sponsorship = "Cash and sponsorship";
			$bounty = "$".$cash." and ".$cash_or_sponsorship;
		break;
		default:
			$cash_or_sponsorship = "N/A";
			$bounty = "N/A";
		break;
	}

	$data = array(
		"to"=>array(
			"name"=>$user->display_name,
			"email"=>$user->user_email
		),
		"data"=>array(
			"brand"=>$brand,
			"project_name"=>$project_name,
			"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
			"igusername"=>$igusername,
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items
		)
	);

	return array(
		"complete"=>1,
		"type"=>"email",
		"emaildata"=>array(
			"template"=>"storify_invite_reject_creator",
			"data"=>$data
		)
	);
}

function worker_job_project_invite_accept_brand($data){
	global $wpdb, $main;

	//get user id 
	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $user->ID));

	//project id 
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], $data["creator_id"]);

	//get admin ids
	$query = "SELECT user_id FROM `".$wpdb->prefix."project_user` WHERE project_id = %d AND role = %s";
	$admin_id = $wpdb->get_var($wpdb->prepare($query, $data["project_id"], "admin")); // only get 1, should get multiple in future
	$admin = get_user_by('id', $admin_id);

	$email_name = $admin->first_name ? $admin->first_name : $admin->display_name;
	$email_url = $admin->user_email;

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Someone";
	}
	$project_name = isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["name"]) ? $project["data"]["summary"]["name"] : "N/A";
	$project_link = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->id) ? get_home_url()."/project/".$project["data"]["detail"]->id : "N/A";
	$bounty_type = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->bounty_type) ? $project["data"]["detail"]->bounty_type : "N/A";

	//calculate cash & others
	$number_of_photo = $project["data"]["detail"]->no_of_photo == 1 ? "1 photo" : $project["data"]["detail"]->no_of_photo." photos";
	$number_of_video = $project["data"]["detail"]->no_of_video == 1 ? "1 video" : $project["data"]["detail"]->no_of_video." videos";

	if($project["data"]["detail"]->no_of_photo == 0){
		$number_of_items = $number_of_video;
	}else if($project["data"]["detail"]->no_of_video == 0){
		$number_of_items = $number_of_photo;
	}else{
		$number_of_items = $number_of_photo." and ".$number_of_video;
	}

	$cash = $project["data"]["detail"]->no_of_photo * $project["data"]["detail"]->cost_per_photo + $project["data"]["detail"]->no_of_video * $project["data"]["detail"]->cost_per_video;
	$reward_name = $project["data"]["detail"]->reward_name;

	$bounty = "N/A";
	$cash_or_sponsorship = "N/A";
	switch($bounty_type){
		case "gift":
			$cash_or_sponsorship = "Sponsorship";
			$bounty = $reward_name;
		break;
		case "cash":
			$cash_or_sponsorship = "Cash";
			$bounty = "$".$cash;
		break;
		case "both":
			$cash_or_sponsorship = "Cash and sponsorship";
			$bounty = "$".$cash." and ".$cash_or_sponsorship;
		break;
		default:
			$cash_or_sponsorship = "N/A";
			$bounty = "N/A";
		break;
	}

	$data = array(
		"to"=>array(
			"name"=>$email_name,
			"email"=>$email_url
		),
		"data"=>array(
			"igusername"=>$igusername,
			"project_name"=>$project_name,
			"first_name"=>$admin->first_name ? $admin->first_name : $admin->display_name,
			"brand"=>$brand,
			"submit_link"=>$project_link."/submit",
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items,
			"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])), //actually is submission close date
			"detail_link"=>$project_link
		)
	);

	return array(
		"complete"=>1,
		"type"=>"email",
		"emaildata"=>array(
			"template"=>"storify_invite_accept_brand",
			"data"=>$data
		)
	);
}

function worker_job_project_invite_accept_creator($data){
	global $wpdb, $main;

	//get user id 
	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $user->ID));

	//project id 
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], $data["creator_id"]);

	$email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Someone";
	}
	$project_name = isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["name"]) ? $project["data"]["summary"]["name"] : "N/A";
	$project_link = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->id) ? get_home_url()."/project/".$project["data"]["detail"]->id : "N/A";
	$bounty_type = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->bounty_type) ? $project["data"]["detail"]->bounty_type : "N/A";

	//calculate cash & others
	$number_of_photo = $project["data"]["detail"]->no_of_photo == 1 ? "1 photo" : $project["data"]["detail"]->no_of_photo." photos";
	$number_of_video = $project["data"]["detail"]->no_of_video == 1 ? "1 video" : $project["data"]["detail"]->no_of_video." videos";

	if($project["data"]["detail"]->no_of_photo == 0){
		$number_of_items = $number_of_video;
	}else if($project["data"]["detail"]->no_of_video == 0){
		$number_of_items = $number_of_photo;
	}else{
		$number_of_items = $number_of_photo." and ".$number_of_video;
	}

	$cash = $project["data"]["detail"]->no_of_photo * $project["data"]["detail"]->cost_per_photo + $project["data"]["detail"]->no_of_video * $project["data"]["detail"]->cost_per_video;
	$reward_name = $project["data"]["detail"]->reward_name;

	$bounty = "N/A";
	$cash_or_sponsorship = "N/A";
	switch($bounty_type){
		case "gift":
			$cash_or_sponsorship = "Sponsorship";
			$bounty = $reward_name;
		break;
		case "cash":
			$cash_or_sponsorship = "Cash";
			$bounty = "$".$cash;
		break;
		case "both":
			$cash_or_sponsorship = "Cash and sponsorship";
			$bounty = "$".$cash." and ".$cash_or_sponsorship;
		break;
		default:
			$cash_or_sponsorship = "N/A";
			$bounty = "N/A";
		break;
	}

	$data = array(
		"to"=>array(
			"name"=>$user->display_name,
			"email"=>$user->user_email
		),
		"data"=>array(
			"igusername"=>$igusername,
			"project_name"=>$project_name,
			"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
			"brand"=>$brand,
			"submit_link"=>$project_link."/submit",
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items,
			"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])), //actually is submission
			"detail_link"=>$project_link
		)
	);

	return array(
		"complete"=>1,
		"type"=>"email",
		"emaildata"=>array(
			"template"=>"storify_invite_accept_creator",
			"data"=>$data
		)
	);
}

function worker_job_project_invite($data){
	global $wpdb, $main;

	//get user id 
	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $user->ID));

	//project id 
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], $data["creator_id"]);

	$email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Someone";
	}
	$project_name = isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["name"]) ? $project["data"]["summary"]["name"] : "N/A";
	$project_link = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->id) ? get_home_url()."/project/".$project["data"]["detail"]->id : "N/A";
	$bounty_type = isset($project["data"]) && isset($project["data"]["detail"]) && isset($project["data"]["detail"]->bounty_type) ? $project["data"]["detail"]->bounty_type : "N/A";

	//calculate cash & others
	$number_of_photo = $project["data"]["detail"]->no_of_photo == 1 ? "1 photo" : $project["data"]["detail"]->no_of_photo." photos";
	$number_of_video = $project["data"]["detail"]->no_of_video == 1 ? "1 video" : $project["data"]["detail"]->no_of_video." videos";

	if($project["data"]["detail"]->no_of_photo == 0){
		$number_of_items = $number_of_video;
	}else if($project["data"]["detail"]->no_of_video == 0){
		$number_of_items = $number_of_photo;
	}else{
		$number_of_items = $number_of_photo." and ".$number_of_video;
	}

	$cash = $project["data"]["detail"]->no_of_photo * $project["data"]["detail"]->cost_per_photo + $project["data"]["detail"]->no_of_video * $project["data"]["detail"]->cost_per_video;
	$reward_name = $project["data"]["detail"]->reward_name;

	$bounty = "N/A";
	$cash_or_sponsorship = "N/A";
	switch($bounty_type){
		case "gift":
			$cash_or_sponsorship = "Sponsorship";
			$bounty = $reward_name;
		break;
		case "cash":
			$cash_or_sponsorship = "Cash";
			$bounty = "$".$cash;
		break;
		case "both":
			$cash_or_sponsorship = "Cash and sponsorship";
			$bounty = "$".$cash." and ".$cash_or_sponsorship;
		break;
		default:
			$cash_or_sponsorship = "N/A";
			$bounty = "N/A";
		break;
	}

	$data = array(
		"to"=>array(
			"name"=>$user->display_name,
			"email"=>$user->user_email
		),
		"data"=>array(
			"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
			"igusername"=>$igusername,
			"brand"=>$brand,
			"project_link"=>$project_link,
			"project_name"=>$project_name,
			"invite_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["invitation_closing_date"])),
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items,
			"detail_link"=>$project_link,
			"accept_link"=>$project_link
		)
	);

	return array(
		"complete"=>1,
		"type"=>"email",
		"emaildata"=>array(
			"template"=>"storify_initial_invite",
			"data"=>$data
		)
	);
}

function worker_job_ig_connect($data){
	global $wpdb, $main;

	$user = get_user_by("id", $data["userid"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $data["userid"]));

	if($igusername){
		$data = array(
			"to"=>array(
				"name"=>$user->display_name,
				"email"=>$user->user_email
			),
			"data"=>array(
				"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
				"igusername"=>$igusername,
				"social_showcase_page_link"=>get_home_url()."/user/showcase",
				"invited_project_page_link"=>get_home_url()."/user/projects/invited"
			)
		);
		return array(
			"complete"=>1,
			"type"=>"email",
			"emaildata"=>array(
				"template"=>"storify_connected_with_ig",
				"data"=>$data
			)
		);
	}else{
		return array(
			"complete"=>0
		);
	}
}

function worker_job_new_register($data){
	global $wpdb, $main;

	//check whether user had been connect IG
	$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$contain = $wpdb->get_var($wpdb->prepare($query, $data["userid"]));
	$user = get_user_by('id', $data["userid"]); 
	if(!$user){
		return array(
			"complete"=>0,
			"error"=>"user doesn't exist"
		);
	}
	if($contain){
		//connected, send Connected Email

		$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
		$igusername = $wpdb->get_var($wpdb->prepare($query, $data["userid"]));

		$email_name = $user->first_name ? $user->first_name : $user->display_name;
		$email_url = $user->user_email;

		$data = array(
			"to"=>array(
				"name"=>$user->display_name,
				"email"=>$user->user_email
			),
			"data"=>array(
				"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
				"igusername"=>$igusername,
				"social_showcase_page_link"=>get_home_url()."/user/showcase",
				"invited_project_page_link"=>get_home_url()."/user/projects/"
			)
		);
		return array(
			"complete"=>1,
			"type"=>"email",
			"emaildata"=>array(
				"template"=>"storify_new_reg_with_ig",
				"data"=>$data
			)
		);
	}else{
		//no connected, send not connected Email, and insert a passiveJob
		$email_name = $user->first_name ? $user->first_name : $user->display_name;
		$email_url = $user->user_email;

		$data = array(
			"to"=>array(
				"name"=>$user->display_name,
				"email"=>$user->user_email
			),
			"data"=>array(
				"first_name"=>$user->first_name ? $user->first_name : $user->display_name,
				"social_showcase_page_link"=>get_home_url()."/user/showcase"
			)
		);

		job::addPassiveJob($user->ID, "waiting_for_ig");

		return array(
			"complete"=>1,
			"type"=>"email",
			"emaildata"=>array(
				"template"=>"storify_new_reg_without_ig",
				"data"=>$data
			)
		);
	}
}
?>