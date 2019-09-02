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
$tasks = array();
foreach($jobs_group as $key=>$value){
	switch($value["type"]){
		case "project_invite_expire_before_1":
			if(!isset($tasks["project_invite_expire_before_1"])){
				$tasks["project_invite_expire_before_1"] = array();
			}
			$tasks["project_invite_expire_before_1"][] = $value;
		break;
		case "project_invite_expired":
			if(!isset($tasks["project_invite_expired"])){
				$tasks["project_invite_expired"] = array();
			}
			$tasks["project_invite_expired"][] = $value;
		break;
		case "project_brief_update":
			if(!isset($tasks["project_brief_update"])){
				$tasks["project_brief_update"] = array();
			}
			$tasks["project_brief_update"][] = $value;
		break;
		case "submission_closing_before_3":
			if(!isset($tasks["submission_closing_before_3"])){
				$tasks["submission_closing_before_3"] = array();
			}
			$tasks["submission_closing_before_3"][] = $value;
		break;
		case "submission_closing_before_1":
			if(!isset($tasks["submission_closing_before_1"])){
				$tasks["submission_closing_before_1"] = array();
			}
			$tasks["submission_closing_before_1"][] = $value;
		break;
		case "submission_close":
			if(!isset($tasks["submission_close"])){
				$tasks["submission_close"] = array();
			}
			$tasks["submission_close"][] = $value;
		break;
		case "submission_complete":
			if(!isset($tasks["submission_complete"])){
				$tasks["submission_complete"] = array();
			}
			$tasks["submission_complete"][] = $value;
		break;
		case "project_summary":
			if(!isset($tasks["project_summary"])){
				$tasks["project_summary"] = array();
			}
			$tasks["project_summary"][] = $value;
		break;
		case "project_status_change":
			if(!isset($tasks["project_status_change"])){
				$tasks["project_status_change"] = array();
			}
			$tasks["project_status_change"][] = $value;
		break;
		case "project_payment_confirm":
			if(!isset($tasks["project_payment_confirm"])){
				$tasks["project_payment_confirm"] = array();
			}
			$tasks["project_payment_confirm"][] = $value;
		break;
		case "project_close":
			if(!isset($tasks["project_close"])){
				$tasks["project_close"] = array();
			}
			$tasks["project_close"][] = $value;
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
					foreach($result["emaildatas"] as $key3=>$value3){
						if(!isset($value3["template"])){
							$batchEmailTask[$value3["template"]] = array();
						}
						$batchEmailTask[$value3["template"]][] = array(
							"id"=>$item["id"],
							"data"=>$value3["data"]
						);	
					}
				}else{
					job::update($item["id"],"complete");
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
			$email_result = $main->sendLambdaBatchEmail_test(
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
			}else{
				print_r($email_result);
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

function getAssetsCode($id){
	$code = "ASSET-000000000";
	$id_str = $id."";
	
	return substr($code, 0 , -1 * strlen($id_str)).$id_str;
}

function worker_job_project_close($data){
	global $wpdb, $main;

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user ( to get admin, but not creator that already accept the job )
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	$total_creator = 0;
	foreach($users as $key=>$value){
		if($value["role"] == "creator"){
			$total_creator++;
		}
	}

	$total_photo_submission = $total_creator * $project["data"]["detail"]->no_of_photo;
	$total_video_submission = $total_creator * $project["data"]["detail"]->no_of_video;

	//get summary 
	$query = "SELECT id, creator_id, `type`, status FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d ORDER BY id ASC";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"]), ARRAY_A);

	$photos_update_requested = 0;
	$videos_update_requested = 0;
	$photos_accepted_requested = 0;
	$videos_accepted_requested = 0;

	$total_submitted_items = 0;
	$total_accepted_items = 0;
	$creators_submission = array();
	foreach($submissions as $key=>$value){
		if(!isset($creators_submission[$value["creator_id"]])){
			$creators_submission[$value["creator_id"]] = array(
				"creator_id"=>$value["creator_id"],
				"total_items"=>0,
				"total_photos"=>0,
				"total_videos"=>0,
				"total_amount"=>0,
				"data"=>array(),
				"data_sentence"=>array(),
				"creator_data_sentence"=>array()
			);
		}

		if($value["type"] == "photo"){
			$creators_submission[$value["creator_id"]]["total_photos"]++;
			if($value["status"] == "accepted"){
				$photos_accepted_requested++;
				$total_accepted_items++;
			}
		}else{
			$creators_submission[$value["creator_id"]]["total_videos"]++;
			if($value["status"] == "accepted"){
				$videos_accepted_requested++;
				$total_accepted_items++;
			}
		}
		$total_submitted_items++;
		$creators_submission[$value["creator_id"]]["total_items"]++;
		$creators_submission[$value["creator_id"]]["data"][] = $value;
	}

	foreach($creators_submission as $key=>$value){
		$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
		$igusername = $wpdb->get_var($wpdb->prepare($query, $value["creator_id"]));
		$igusername = $igusername ? $igusername : "igusername";

		if(sizeof($value["data"])){
			foreach($value["data"] as $key2=>$value2){
				if( $value2["type"] == "photo" && $project["data"]["detail"]->cost_per_photo ){
					$creators_submission[$value["creator_id"]]["data_sentence"][] = getAssetsCode($value2["id"])." by @".$igusername.": S$".$project["data"]["detail"]->cost_per_photo;
					$creators_submission[$value["creator_id"]]["creator_data_sentence"][] = getAssetsCode($value2["id"]).": S$".$project["data"]["detail"]->cost_per_video;
					$creators_submission[$value["creator_id"]]["total_amount"] += $project["data"]["detail"]->cost_per_photo;
				}
				if( $value2["type"] == "video" && $project["data"]["detail"]->cost_per_video ){
					$creators_submission[$value["creator_id"]]["data_sentence"][] = getAssetsCode($value2["id"])." by @".$igusername.": S$".$project["data"]["detail"]->cost_per_video;
					$creators_submission[$value["creator_id"]]["creator_data_sentence"][] = getAssetsCode($value2["id"]).": S$".$project["data"]["detail"]->cost_per_video;
					$creators_submission[$value["creator_id"]]["total_amount"] += $project["data"]["detail"]->cost_per_video;
				}
			}
		}
	}

	$summary_line = "You have received ".( $total_accepted_items == 1 ? "1 submission" : $total_accepted_items." submissions" )." and accepted ".$total_submitted_items.".";

	$submissions_payment_detail_list = "<p>".$summary_line."</p>";
	$submissions_payment_detail_list_plain = $summary_line."\n";

	$count = 1;
	$payment_total = 0;
	foreach($creators_submission as $key=>$value){
		if(sizeof($value["data_sentence"])){
			$submissions_payment_detail_list .= "<p><ol start=\"".$count."\">";
			foreach($value["data_sentence"] as $key2=>$value2){
				$submissions_payment_detail_list .= "<li>".$value2."</li>";
				$submissions_payment_detail_list_plain .= "\n".$count.") ".$value2;
				$count++;
			}
			$submissions_payment_detail_list .= "</ol></p><hr><p>Final Due for @".$igusername.": S$".$value["total_amount"]."</p>";
			$submissions_payment_detail_list_plain .= "\n-----------------------------------------------\nFinal Due for @".$igusername.": S$".$value["total_amount"]."\n";
			$payment_total += $value["total_amount"];
		}
	}

	$submissions_payment_detail_list .= "<hr><p>Platform fee for ".( sizeof($creators_submission) == 1 ? "1 activated creator" : sizeof($creators_submission)." activated creators" ).": S$".( sizeof($creators_submission) * 99 )." (S$99 / creator)</p>";
	$submissions_payment_detail_list_plain .= "\n-----------------------------------------------\nPlatform fee for ".( sizeof($creators_submission) == 1 ? "1 activated creator" : sizeof($creators_submission)." activated creators" ).": S$".( sizeof($creators_submission) * 99 )." (S$99 / creator)\n";

	$payment_total += sizeof($creators_submission) * 99;

	if(!isset($data["creator_only"])){ // if creator_only, no admin email
		foreach($users as $key=>$value){
			if($value["role"] == "admin"){

				$user = get_user_by('id', $value["user_id"]);

				$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
				$email_url = $user->user_email;

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"first_name"=>$first_name,
						"brand"=>$brand,
						"submissions_payment_detail_list"=>$submissions_payment_detail_list,
						"submissions_payment_detail_list_plain"=>$submissions_payment_detail_list_plain,
						"payment_total"=>$payment_total,
						"final_link"=>$project_link."/final",
						"new_project_link"=>get_home_url()."/project/new",
						"submission_complete_list"=>"<p>".$summary_line."</p>".$submissions_payment_detail_list,
						"submission_complete_list_plain"=>$summary_line."\n".$submissions_payment_detail_list_plain,
						"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
						"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 ),
						"submit_link"=>$project_link."/submit"
					)
				);

				$pass_data[] = array(
					"template"=>"storify_project_close_brand",
					"data"=>$item_data
				);

			}else{
				//ignore
			}
		}

		//creator
		foreach($creators_submission as $key=>$value){
			if(sizeof($value["creator_data_sentence"])){
				//if user is in exlude list, skip email

				if(job::checkFlagExist("project_close_".$project["data"]["detail"]->id."_".$value["creator_id"])){
					continue;
				}

				job::addFlag("project_close_".$project["data"]["detail"]->id."_".$value["creator_id"],$project["data"]["detail"]->id,$value["creator_id"]);

				$user = get_user_by('id', $value["creator_id"]);

				$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
				$igusername = $wpdb->get_var($wpdb->prepare($query, $value["creator_id"]));
				$igusername = $igusername ? $igusername : "igusername";

				$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
				$email_url = $user->user_email;

				$submission_payment_sentence = $brand." has accepted ".( sizeof($value["creator_data_sentence"]) == 1 ? "1 submission" : sizeof($value["creator_data_sentence"])." submissions" )." and you will receive S$".$value["total_amount"].".";
				$submission_payment_detail_list = "";
				$submission_payment_detail_list_plain = "";

				$submission_payment_detail_list = "<p><ol>";
				$count = 1;
				foreach($value["creator_data_sentence"] as $key2=>$value2){
					$submission_payment_detail_list .= "<li>".$value2."</li>";
					$submission_payment_detail_list_plain .= "\n".$count.") ".$value2;
					$count++;
				}
				$submission_payment_detail_list = "</ol></p>";

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"first_name"=>$first_name,
						"igusername"=>$igusername,
						"submission_payment_sentence"=>$submission_payment_sentence,
						"submission_payment_detail_list"=>$submission_payment_detail_list,
						"submission_payment_detail_list_plain"=>$submission_payment_detail_list_plain,
						"payment_total"=>$value["total_amount"],
						"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 ),
						"final_link"=>$project_link."/final"
					)
				);

				$pass_data[] = array(
					"template"=>"storify_project_close_creator",
					"data"=>$item_data
				);
			}
		}
	}else{
		// single creator

		foreach($creators_submission as $key=>$value){
			if(sizeof($value["creator_data_sentence"]) && $value["creator_id"] == $data["creator_only"]){
				//if user is in exlude list, skip email

				if(job::checkFlagExist("project_close_".$project["data"]["detail"]->id."_".$value["creator_id"])){
					continue;
				}

				job::addFlag("project_close_".$project["data"]["detail"]->id."_".$value["creator_id"],$project["data"]["detail"]->id,$value["creator_id"]);

				$user = get_user_by('id', $value["creator_id"]);

				$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
				$igusername = $wpdb->get_var($wpdb->prepare($query, $value["creator_id"]));
				$igusername = $igusername ? $igusername : "igusername";

				$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
				$email_url = $user->user_email;

				$submission_payment_sentence = $brand." has accepted ".( sizeof($value["creator_data_sentence"]) == 1 ? "1 submission" : sizeof($value["creator_data_sentence"])." submissions" )." and you will receive S$".$value["total_amount"].".";
				$submission_payment_detail_list = "";
				$submission_payment_detail_list_plain = "";

				$submission_payment_detail_list = "<p><ol>";
				$count = 1;
				foreach($value["creator_data_sentence"] as $key2=>$value2){
					$submission_payment_detail_list .= "<li>".$value2."</li>";
					$submission_payment_detail_list_plain .= "\n".$count.") ".$value2;
					$count++;
				}
				$submission_payment_detail_list = "</ol></p>";

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"first_name"=>$first_name,
						"igusername"=>$igusername,
						"submission_payment_sentence"=>$submission_payment_sentence,
						"submission_payment_detail_list"=>$submission_payment_detail_list,
						"submission_payment_detail_list_plain"=>$submission_payment_detail_list_plain,
						"payment_total"=>$value["total_amount"],
						"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 ),
						"final_link"=>$project_link."/final"
					)
				);

				$pass_data[] = array(
					"template"=>"storify_project_close_creator",
					"data"=>$item_data
				);
			}
		}
	}


	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_payment_confirm($data){
	global $wpdb, $main;
	// storify_paid_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	if(!$cash){
		return array(
			"complete"=>1,
			"type"=>"emails",
			"emaildatas"=>$pass_data
		);
	}

	//get summary 
	$query = "SELECT id, `type`, status FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d AND creator_id = %d AND status = %s ORDER BY id ASC";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"], $data["creator_id"], "accepted"), ARRAY_A);

	$items = array();
	foreach( $submissions as $key=>$value ){
		$items[] = getAssetsCode($value["id"]).": S$".( $value["type"] == "photo" ? $project["data"]["detail"]->cost_per_photo : $project["data"]["detail"]->cost_per_video );
	}

	$submission_paid_list = "";
	$submission_paid_list_plain = "";

	$submission_paid_list .= "<p><ol>";
	$count = 1;
	foreach( $items as $key=>$value ){
		$submission_paid_list .= "<li>".$value."</li>";
		$submission_paid_list_plain .= "\n".$count.") ".$value;
		$count++;
	}
	$submission_paid_list .= "</ol></p>";

	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $data["creator_id"]));
	$igusername = $igusername ? $igusername : "igusername";

	$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	$item_data = array(
		"to"=>array(
			"name"=>$email_name,
			"email"=>$email_url
		),
		"data"=>array(
			"project_name"=>$project_name,
			"brand"=>$brand,
			"first_name"=>$first_name,
			"igusername"=>$igusername,
			"submission_paid_list"=>$submission_paid_list,
			"submission_paid_list_plain"=>$submission_paid_list_plain,
			"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 ),
			"final_link"=>$project_link."/final"
		)
	);

	$pass_data[] = array(
		"template"=>"storify_paid_creator",
		"data"=>$item_data
	);

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_status_change($data){
	global $wpdb, $main;
	// storify_submission_status_change_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get summary 
	$query = "SELECT creator_id, id, `type`, status FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d AND creator_id = %d AND status != %s ORDER BY id ASC";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"], $data["creator_id"], ""), ARRAY_A);

	$photos_update_requested = 0;
	$videos_update_requested = 0;
	$photos_accepted_requested = 0;
	$videos_accepted_requested = 0;

	$creators_submission = array();
	foreach($submissions as $key=>$value){
		if(!isset($creators_submission[$value["creator_id"]])){
			$creators_submission[$value["creator_id"]] = array(
				"creator_id"=>$value["creator_id"],
				"total_items"=>0,
				"total_photos"=>0,
				"total_videos"=>0,
				"data"=>array(),
				"data_sentence"=>array()
			);
		}

		if($value["type"] == "photo"){
			$creators_submission[$value["creator_id"]]["total_photos"]++;
			if($value["status"] == "rejected"){
				$photos_update_requested++;
			}else if($value["status"] == "accepted"){
				$photos_accepted_requested++;
			}
		}else{
			$creators_submission[$value["creator_id"]]["total_videos"]++;
			if($value["status"] == "rejected"){
				$videos_update_requested++;
			}else if($value["status"] == "accepted"){
				$videos_accepted_requested++;
			}
		}
		$creators_submission[$value["creator_id"]]["total_items"]++;
		$creators_submission[$value["creator_id"]]["data"][] = $value;
	}

	foreach($creators_submission as $key=>$value){
		if(sizeof($value["data"])){
			foreach($value["data"] as $key2=>$value2){
				$creators_submission[$value["creator_id"]]["data_sentence"][] = getAssetsCode($value2["id"])." - ".( $value2["status"] == "accepted" ? "Accepted" : "Updates Requested" );
			}
		}
	}

	$update_number_of_items = "";
	if($photos_update_requested == 0 && $videos_update_requested == 0){
		$update_number_of_items = "0";
	}else if($photos_update_requested == 0){
		$update_number_of_items = ( $videos_update_requested == 1 ) ? "1 video" : $videos_update_requested." videos";
	}else if($videos_update_requested == 0){
		$update_number_of_items = ( $photos_update_requested == 1 ) ? "1 photo" : $photos_update_requested." photos";
	}else{
		$update_number_of_items = ( ( $photos_update_requested == 1 ) ? "1 photo" : $photos_update_requested." photos" )." and ".( ( $videos_update_requested == 1 ) ? "1 video" : $videos_update_requested." videos" );
	}

	if($project["data"]["detail"]->no_of_photo == 0){
		$summary_line_1 = $videos_accepted_requested." / ".$project["data"]["detail"]->no_of_video." videos";
	}else if($project["data"]["detail"]->no_of_video == 0){
		$summary_line_1 = $photos_accepted_requested." / ".$project["data"]["detail"]->no_of_photo." photos";
	}else{
		$summary_line_1 = $photos_accepted_requested." / ".$project["data"]["detail"]->no_of_photo." photos and ".$videos_accepted_requested." / ".$project["data"]["detail"]->no_of_video." videos";
	}

	$summary_line_2 = $update_number_of_items;

	$item_detail_list = "";
	$item_detail_list_plain = "";

	$count = 1;
	foreach($creators_submission as $key=>$value){
		$item_detail_list .= "<p><ol>";
		foreach($value["data_sentence"] as $key2=>$value2){
			$item_detail_list .= "<li>".$value2."</li>";
			$item_detail_list_plain .= "\n".$count.") ".$value2;
			$count++;
		}
		$item_detail_list .= "</ol></p>";
	}

	$user = get_user_by('id', $data["creator_id"]);

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $data["creator_id"]));
	$igusername = $igusername ? $igusername : "igusername";

	$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	$item_data = array(
		"to"=>array(
			"name"=>$email_name,
			"email"=>$email_url
		),
		"data"=>array(
			"project_name"=>$project_name,
			"brand"=>$brand,
			"first_name"=>$first_name,
			"igusername"=>$igusername,
			"accepted_item"=>$summary_line_1,
			"update_item"=>$summary_line_2,
			"item_detail_list"=>$item_detail_list,
			"item_detail_list_plain"=>$item_detail_list_plain,
			"submit_link"=>$project_link."/submit",
			"project_close_date_before_7"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 604800 ),
			"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 )
		)
	);

	$pass_data[] = array(
		"template"=>"storify_submission_status_change_creator",
		"data"=>$item_data
	);

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_summary($data){
	global $wpdb, $main;
	// storify_submission_status_change_brand

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user ( to get admin, but not creator that already accept the job )
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	$total_creator = 0;
	foreach($users as $key=>$value){
		if($value["role"] == "creator"){
			$total_creator++;
		}
	}

	$total_photo_submission = $total_creator * $project["data"]["detail"]->no_of_photo;
	$total_video_submission = $total_creator * $project["data"]["detail"]->no_of_video;

	//get summary 
	$query = "SELECT id, creator_id, `type`, status FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d AND status != %s ORDER BY id ASC";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"], "rejected"), ARRAY_A);

	$photos_update_requested = 0;
	$videos_update_requested = 0;
	$photos_accepted_requested = 0;
	$videos_accepted_requested = 0;

	$creators_submission = array();
	if(sizeof($submissions)){
		foreach($submissions as $key=>$value){
			if(!isset($creators_submission[$value["creator_id"]])){
				$creators_submission[$value["creator_id"]] = array(
					"creator_id"=>$value["creator_id"],
					"total_items"=>0,
					"total_photos"=>0,
					"total_videos"=>0,
					"data"=>array(),
					"data_sentence"=>array()
				);
			}

			if($value["type"] == "photo"){
				$creators_submission[$value["creator_id"]]["total_photos"]++;
				if($value["status"] == ""){
					$photos_update_requested++;
				}else if($value["status"] == "accepted"){
					$photos_accepted_requested++;
				}
			}else{
				$creators_submission[$value["creator_id"]]["total_videos"]++;
				if($value["status"] == ""){
					$videos_update_requested++;
				}else if($value["status"] == "accepted"){
					$videos_accepted_requested++;
				}
			}
			$creators_submission[$value["creator_id"]]["total_items"]++;
			$creators_submission[$value["creator_id"]]["data"][] = $value;
		}
	}

	if(sizeof($creators_submission)){
		foreach($creators_submission as $key=>$value){
			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["creator_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			if(sizeof($value["data"])){
				foreach($value["data"] as $key2=>$value2){
					$creators_submission[$value["creator_id"]]["data_sentence"][] = getAssetsCode($value2["id"])." by @".$igusername." - ".( $value2["status"] == "accepted" ? "Accepted" : "Updates Requested" );
				}
			}
		}
	}

	if( $total_photo_submission == 0 ){
		$summary_line_1 = "Accepted: ".$videos_accepted_requested." / ".$total_video_submission." videos";
	}else if( $total_video_submission == 0 ){
		$summary_line_1 = "Accepted: ".$photos_accepted_requested." / ".$total_photo_submission." photos";
	}else{
		$summary_line_1 = "Accepted: ".$photos_accepted_requested." / ".$total_photo_submission." photos and ".$videos_accepted_requested." / ".$total_video_submission." videos";
	}

	if( $photos_update_requested == 0 && $videos_update_requested == 0 ){
		$summary_line_2 = "";
	}else if( $photos_update_requested == 0 ){
		$summary_line_2 = "Updates requested: ".( $videos_update_requested == 1 ? "1 video" : $videos_update_requested." videos" );
	}else if( $videos_update_requested == 0 ){
		$summary_line_2 = "Updates requested: ".( $photos_update_requested == 1 ? "1 photo" : $photos_update_requested." photos" );
	}else{
		$summary_line_2 = "Updates requested: ".( $photos_update_requested == 1 ? "1 photo" : $photos_update_requested." photos" ) . " and " . ( $videos_update_requested == 1 ? "1 video" : $videos_update_requested." videos" );
	}

	$submission_complete_list = "";
	$submission_complete_list_plain = "";

	$count = 1;
	if(sizeof($creators_submission)){
		foreach($creators_submission as $key=>$value){
			$submission_complete_list .= "<hr><p><ol start=\"".$count."\">";
			$submission_complete_list_plain .= "-----------------------------------------------\n";
			foreach($value["data_sentence"] as $key2=>$value2){
				$submission_complete_list .= "<li>".$value2."</li>";
				$submission_complete_list_plain .= $count.") ".$value2."\n";
				$count++;
			}
			$submission_complete_list .= "</ol></p>";
		}
	}

	if(sizeof($users)){
		foreach($users as $key=>$value){
			if($value["role"] == "admin"){

				$user = get_user_by('id', $value["user_id"]);

				$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
				$email_url = $user->user_email;

				if(job::checkFlagExist("project_submission_complete_".$project["data"]["detail"]->id)){
					// flag found, skip email
				}else{
					$item_data = array(
						"to"=>array(
							"name"=>$email_name,
							"email"=>$email_url
						),
						"data"=>array(
							"project_name"=>$project_name,
							"first_name"=>$first_name,
							"submission_complete_list"=>"<p>".$summary_line_1."<br />".$summary_line_2."</p>".$submission_complete_list,
							"submission_complete_list_plain"=>$summary_line_1."\n".$summary_line_2.$submission_complete_list_plain,
							"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
							"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"]) + 1209600 ),
							"submit_link"=>$project_link."/submit"
						)
					);

					$pass_data[] = array(
						"template"=>"storify_submission_status_change_brand",
						"data"=>$item_data
					);
				}

			}else{
				//ignore
			}
		}
	}

	//add flag if all submission is approved.
	if( ( $total_photo_submission + $total_video_submission - $photos_accepted_requested - $videos_accepted_requested ) == 0 ){
		job::addFlag("project_submission_complete_".$project["data"]["detail"]->id, $project["data"]["detail"]->id);
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_submission_complete($data){
	global $wpdb, $main;
	// data = { project_id , creator_id }
	// storify_submission_done_brand
	// storify_submission_done_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get submission by creator, needed for admin
	$query = "SELECT COUNT(*) as `cnt`, creator_id, `type` FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d GROUP BY creator_id, `type`";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"]), ARRAY_A);

	$creators_submission = array();
	foreach($submissions as $key=>$value){
		if(!isset($creators_submission[$value["creator_id"]])){
			$creators_submission[$value["creator_id"]] = array(
				"creator_id"=>$value["creator_id"],
				"total_items"=>0,
				"total_photos"=>0,
				"total_videos"=>0
			);
		}

		if($value["type"] == "photo"){
			$creators_submission[$value["creator_id"]]["total_photos"] = (int) $value["cnt"];
		}else{
			$creators_submission[$value["creator_id"]]["total_videos"] = (int) $value["cnt"];
		}
		$creators_submission[$value["creator_id"]]["total_items"] += (int) $value["cnt"];
	}

	$current_creator_photo_count = 0;
	$current_creator_video_count = 0;
	$current_creator_submitted_item = "";

	$creators_submission_sentence = array();
	//create listing
	foreach($creators_submission as $key=>$value){
		$photo_submitted = $value["total_photos"]." / ".$project["data"]["detail"]->no_of_photo." photos";
		$video_submitted = $value["total_videos"]." / ".$project["data"]["detail"]->no_of_video." videos";

		if($value["creator_id"] == $data["creator_id"]){
			if($value["total_photos"] == 0){
				$current_creator_submitted_item = $value["total_videos"]." / ".$project["data"]["detail"]->no_of_video." videos";
			}else if($value["total_videos"] == 0){
				$current_creator_submitted_item = $value["total_photos"]." / ".$project["data"]["detail"]->no_of_photo." photos";
			}else{
				$current_creator_submitted_item = $value["total_photos"]." / ".$project["data"]["detail"]->no_of_photo." photos and ".$value["total_videos"]." / ".$project["data"]["detail"]->no_of_video." videos";
			}
		}

		$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
		$igusername = $wpdb->get_var($wpdb->prepare($query, $value["creator_id"]));
		$igusername = $igusername ? $igusername : "igusername";

		$sub_sentence = "";
		if($project["data"]["detail"]->no_of_photo == 0){
			$sub_sentence = $video_submitted;
		}else if($project["data"]["detail"]->no_of_video == 0){
			$sub_sentence = $photo_submitted;
		}else{
			$sub_sentence = $photo_submitted." and ".$video_submitted;
		}

		$creators_submission_sentence[] = "@".$igusername.": ".$sub_sentence;
	}

	$creators_submission_sentence_plain = "";
	foreach($creators_submission_sentence as $key=>$value){
		$creators_submission_sentence_plain .= ($key + 1)." ".$value."\n";
	}

	//get all user ( to get admin, but not creator that already accept the job )
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	foreach($users as $key=>$value){
		if($value["role"] == "admin"){

			$user = get_user_by('id', $value["user_id"]);

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"project_name"=>$project_name,
					"first_name"=>$first_name,
					"creator_complete_list"=>"<p></ol><li>".implode("</li><li>",$creators_submission_sentence)."</p></ol></li>",
					"creator_complete_list_plain"=>$creators_submission_sentence_plain,
					"submit_link"=>$project_link."/submit",
					"brand"=>$brand,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items
				)
			);

			$pass_data[] = array(
				"template"=>"storify_submission_done_brand",
				"data"=>$item_data
			);

		}else{
			//ignore
		}
	}

	//send to creator

	$user = get_user_by('id', $data["creator_id"]);

	$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
	$email_url = $user->user_email;

	$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
	$igusername = $wpdb->get_var($wpdb->prepare($query, $data["creator_id"]));
	$igusername = $igusername ? $igusername : "igusername";

	$item_data = array(
		"to"=>array(
			"name"=>$email_name,
			"email"=>$email_url
		),
		"data"=>array(
			"project_name"=>$project_name,
			"first_name"=>$first_name,
			"igusername"=>$igusername,
			"brand"=>$brand,
			"submitted_items"=>$current_creator_submitted_item,
			"submit_link"=>$project_link."/submit",
			"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
			"cash_or_sponsorship"=>$cash_or_sponsorship,
			"bounty"=>$bounty,
			"number_of_items"=>$number_of_items			
		)
	);

	$pass_data[] = array(
		"template"=>"storify_submission_done_creator",
		"data"=>$item_data
	);

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_submission_close($data){
	global $wpdb, $main;
	// storify_submission_close_brand
	// storify_submit_close_brand_empty
	// storify_submission_close_creator
	// storify_submission_close_creator_empty

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user 
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	//check is no submission at all
	$query = "SELECT COUNT(*) as `cnt`, `type` FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d GROUP BY `type`";
	$submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"]), ARRAY_A);

	$total_submission = 0;
	$total_number_of_photo_count = 0;
	$total_number_of_video_count = 0;
	if(sizeof($submissions)){
		foreach($submissions as $key=>$value){
			$total_submission += $value["cnt"];
			if($value["type"] == "photo"){
				$total_number_of_photo_count = $value["cnt"];
			}else{
				$total_number_of_video_count = $value["cnt"];
			}
		}
	}	

	$total_number_of_items = "";
	//calculate cash & others
	$total_number_of_photo = $total_number_of_photo_count == 1 ? "1 photo" : $total_number_of_photo_count." photos";
	$total_number_of_video = $total_number_of_video_count == 1 ? "1 video" : $total_number_of_video_count." videos";

	if($total_number_of_photo_count == 0){
		$total_number_of_items = $total_number_of_video;
	}else if($total_number_of_video_count == 0){
		$total_number_of_items = $total_number_of_photo;
	}else{
		$total_number_of_items = $total_number_of_photo." and ".$total_number_of_video;
	}

	foreach($users as $key=>$value){
		if($value["role"] == "admin"){
			
			$user = get_user_by('id', $value["user_id"]);

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			if($total_submission){ //with submission

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])+1209600), //2 weeks after submission close date
						"first_name"=>$first_name,
						"total_number_of_items"=>$total_number_of_items,
						"number_of_items"=>$number_of_items,
						"submit_link"=>$project_link."/submit",
						"cash_or_sponsorship"=>$cash_or_sponsorship,
						"bounty"=>$bounty
					)
				);

				$pass_data[] = array(
					"template"=>"storify_submission_close_brand",
					"data"=>$item_data
				);

			}else{

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
						"first_name"=>$first_name,
						"number_of_items"=>$number_of_items,
						"submit_link"=>$project_link."/submit",
						"cash_or_sponsorship"=>$cash_or_sponsorship,
						"bounty"=>$bounty
					)
				);

				$pass_data[] = array(
					"template"=>"storify_submit_close_brand_empty",
					"data"=>$item_data
				);

			}
							
		}else if($value["role"] == "creator"){

			//skip creator that is closed 
			$query = "SELECT COUNT(*) as `cnt` WHERE `".$wpdb->prefix."_project_status` WHERE user_id = %d AND project_id = %d AND status = %s";
			if($wpdb->get_var($wpdb->prepare($query, $value["user_id"], $project["data"]["detail"]->id, "close"))){

				continue;
				
			}

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$query = "SELECT COUNT(*) as `cnt`, `type` FROM `".$wpdb->prefix."project_new_submission` WHERE project_id = %d AND creator_id = %d GROUP BY `type`";
			$individual_submissions = $wpdb->get_results($wpdb->prepare($query, $data["project_id"], $value["user_id"]), ARRAY_A);

			$individual_submission_count = 0;
			$individual_number_of_photo_count = 0;
			$individual_number_of_video_count = 0;
			if(sizeof($individual_submissions)){
				foreach($individual_submissions as $key2=>$value2){
					$individual_submission_count += $value2["cnt"];
					if($value2["type"] == "photo"){
						$individual_number_of_photo_count = $value2["cnt"];
					}else{
						$individual_number_of_video_count = $value2["cnt"];
					}
				}
			}

			$individual_number_of_items = "";
			//calculate cash & others
			$individual_number_of_photo = $individual_number_of_photo_count == 1 ? "1 photo" : $individual_number_of_photo_count." photos";
			$individual_number_of_video = $individual_number_of_video_count == 1 ? "1 video" : $individual_number_of_video_count." videos";

			if($individual_number_of_photo_count == 0){
				$individual_number_of_items = $individual_number_of_video;
			}else if($individual_number_of_video_count == 0){
				$individual_number_of_items = $individual_number_of_photo;
			}else{
				$individual_number_of_items = $individual_number_of_photo." and ".$individual_number_of_video;
			}

			if($individual_submission_count){

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
						"first_name"=>$first_name,
						"igusername"=>$igusername,
						"individual_number_of_items"=>$individual_number_of_items,
						"number_of_items"=>$number_of_items,
						"submit_link"=>$project_link."/submit",
						"cash_or_sponsorship"=>$cash_or_sponsorship,
						"bounty"=>$bounty
					)
				);

				$pass_data[] = array(
					"template"=>"storify_submission_close_creator",
					"data"=>$item_data
				);

			}else{

				$item_data = array(
					"to"=>array(
						"name"=>$email_name,
						"email"=>$email_url
					),
					"data"=>array(
						"project_name"=>$project_name,
						"brand"=>$brand,
						"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
						"first_name"=>$first_name,
						"igusername"=>$igusername,
						"cash_or_sponsorship"=>$cash_or_sponsorship,
						"bounty"=>$bounty,
						"number_of_items"=>$number_of_items,
						"submit_link"=>$project_link."/submit"
					)
				);

				$pass_data[] = array(
					"template"=>"storify_submission_close_creator_empty",
					"data"=>$item_data
				);

			}

		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_submission_closing_before_1($data){
	global $wpdb, $main;
	// storify_submission_close_before_1_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user 
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	$admin_first_name = "";
	foreach($users as $key=>$value){
		if($value["role"] == "creator"){

			//skip creator that is closed 
			$query = "SELECT COUNT(*) as `cnt` WHERE `".$wpdb->prefix."_project_status` WHERE user_id = %d AND project_id = %d AND status = %s";
			if($wpdb->get_var($wpdb->prepare($query, $value["user_id"], $project["data"]["detail"]->id, "close"))){

				continue;
				
			}

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"project_name"=>$project_name,
					"brand"=>$brand,
					"submit_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"number_of_items"=>$number_of_items,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"submit_link"=>$project_link."/submit"
				)
			);

			$pass_data[] = array(
				"template"=>"storify_submission_close_before_1_creator",
				"data"=>$item_data
			);

		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_submission_closing_before_3($data){
	global $wpdb, $main;
	// storify_submission_close_before_3_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user 
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	$admin_first_name = "";
	foreach($users as $key=>$value){
		if($value["role"] == "creator"){

			//skip creator that is closed 
			$query = "SELECT COUNT(*) as `cnt` WHERE `".$wpdb->prefix."_project_status` WHERE user_id = %d AND project_id = %d AND status = %s";
			if($wpdb->get_var($wpdb->prepare($query, $value["user_id"], $project["data"]["detail"]->id, "close"))){

				continue;
				
			}

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"project_name"=>$project_name,
					"brand"=>$brand,
					"project_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])+1209600), //2 weeks after submission close date
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"number_of_items"=>$number_of_items,
					"submission_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"submit_link"=>$project_link."/submit"
				)
			);

			$pass_data[] = array(
				"template"=>"storify_submission_close_before_3_creator",
				"data"=>$item_data
			);

		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_brief_update($data){
	global $wpdb, $main;
	// storify_brief_update_creator_accept
	// storify_brief_update_creator_not_accept

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user 
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	$admin_first_name = "";
	foreach($users as $key=>$value){
		if($value["role"] == "admin"){
			$user = get_user_by('id', $value["user_id"]);
			$admin_first_name = $user->first_name ? $user->first_name : $user->display_name;
		}
	}

	foreach($users as $key=>$value){
		if($value["role"] == "creator"){

			//skip creator that is closed 
			$query = "SELECT COUNT(*) as `cnt` WHERE `".$wpdb->prefix."_project_status` WHERE user_id = %d AND project_id = %d AND status = %s";
			if($wpdb->get_var($wpdb->prepare($query, $value["user_id"], $project["data"]["detail"]->id, "close"))){

				continue;

			}
			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"brand"=>$brand,
					"project_name"=>$project_name,
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"admin_first_name"=>$admin_first_name,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items,
					"submission_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
					"detail_link"=>$project_link
				)
			);

			$pass_data[] = array(
				"template"=>"storify_brief_update_creator_accept",
				"data"=>$item_data
			);

		}else{
			//ignore
		}
	}

	//get all creator that is being invited, but in pending
	$invited_creators = $main->getProjectManager()->getInvitationList($data["project_id"]);
	foreach($invited_creators as $key=>$value){
		//only interest on invitation pending
		if($value["invitation_status"] == "pending"){

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"brand"=>$brand,
					"project_name"=>$project_name,
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"admin_first_name"=>$admin_first_name,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items,
					"invite_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["invitation_closing_date"])),
					"detail_link"=>$project_link
				)
			);

			$pass_data[] = array(
				"template"=>"storify_brief_update_creator_not_accept",
				"data"=>$item_data
			);
		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_invite_expired($data){
	global $wpdb, $main;
	//storify_invite_close_brand
	//storify_invite_close_creator

	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	$pass_data = array();

	//get all user ( to get admin, but not creator that already accept the job )
	$users = $main->getProjectManager()->getUsers($data["project_id"]);

	//calculate how many creators there are
	$number_of_creator = 0;
	foreach($users as $key=>$value){
		if($value["role"] == "creator"){
			$number_of_creator++;
		}
	}

	$number_of_people_with_is = ( $number_of_creator == 1 ) ? "1 people is": $number_of_creator." people are";
	$number_of_creator_with_is = ( $number_of_creator == 1 ) ? "1 creator is": $number_of_creator." creators are";

	foreach($users as $key=>$value){
		if($value["role"] == "admin"){

			$user = get_user_by('id', $value["user_id"]);

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"project_name"=>$project_name,
					"first_name"=>$first_name,
					"invite_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["invitation_closing_date"])),
					"number_of_people_with_is"=>$number_of_people_with_is,
					"number_of_creator_with_is"=>$number_of_creator_with_is,
					"creator_link"=>$project_link."/creator",
					"brand"=>$brand,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items,
					"submission_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["closing_date"])),
					"submit_link"=>$project_link."/submit"
				)
			);

			$pass_data[] = array(
				"template"=>"storify_invite_close_brand",
				"data"=>$item_data
			);

		}else{
			//ignore
		}
	}

	//get all creator that is being invited, but in pending
	$invited_creators = $main->getProjectManager()->getInvitationList($data["project_id"]);
	foreach($invited_creators as $key=>$value){
		//only interest on invitation pending
		if($value["invitation_status"] == "pending"){

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"brand"=>$brand,
					"project_name"=>$project_name,
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items,
					"invite_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["invitation_closing_date"])),
					"invite_link"=>get_home_url()."/project/invited"
				)
			);

			$pass_data[] = array(
				"template"=>"storify_invite_close_creator",
				"data"=>$item_data
			);
		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}

function worker_job_project_invite_expire_before_1($data){
	global $wpdb, $main;
	// template name : storify_invite_close_before_1_creator

	//$data["project_id"]
	// get project data
	$project = $main->getProjectManager()->getProjectDetail($data["project_id"], null, true);

	if(isset($project["data"]) && isset($project["data"]["summary"]) && isset($project["data"]["summary"]["brand"]) && sizeof($project["data"]["summary"]["brand"])){
		$brandar = array();
		foreach($project["data"]["summary"]["brand"] as $key=>$value){
			$brandar[] = $value["name"];
		}
		$brand = implode(",", $brandar);
	}else{
		$brand = "Brand";
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

	// get creators data ( from invitation list )
	$invited_creators = $main->getProjectManager()->getInvitationList($data["project_id"]);

	// create 
	$pass_data = array();

	foreach($invited_creators as $key=>$value){
		//only interest on invitation pending
		if($value["invitation_status"] == "pending"){

			$user = get_user_by('id', $value["user_id"]);

			$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
			$igusername = $wpdb->get_var($wpdb->prepare($query, $value["user_id"]));
			$igusername = $igusername ? $igusername : "igusername";

			$first_name = $email_name = $user->first_name ? $user->first_name : $user->display_name;
			$email_url = $user->user_email;

			$item_data = array(
				"to"=>array(
					"name"=>$email_name,
					"email"=>$email_url
				),
				"data"=>array(
					"brand"=>$brand,
					"project_name"=>$project_name,
					"invite_close_date"=>date('d/m/y', strtotime($project["data"]["summary"]["invitation_closing_date"])),
					"first_name"=>$first_name,
					"igusername"=>$igusername,
					"project_link"=>$project_link,
					"cash_or_sponsorship"=>$cash_or_sponsorship,
					"bounty"=>$bounty,
					"number_of_items"=>$number_of_items,
					"detail_link"=>$project_link
				)
			);

			$pass_data[] = array(
				"template"=>"storify_invite_close_before_1_creator",
				"data"=>$item_data
			);
		}else{
			//ignore
		}
	}

	return array(
		"complete"=>1,
		"type"=>"emails",
		"emaildatas"=>$pass_data
	);
}
?>