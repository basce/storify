<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', "8G");
error_reporting(E_ALL);

set_time_limit(7200); //maximum execution time 2 hours
$debugTime = microtime(true);
function getDebugTime(){
    global $debugTime;
    return microtime(true) - $debugTime;
}

use \storify\main as main;
include("inc/main.php");

$main = new main();

$beginningTime = time();
function printLog($log){
	global $beginningTime;
	
		print_r("||". (time() - $beginningTime )." ".$log."\n");
}

$iger = array();


$total = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."pods_instagram_post_fast`");
$count = 0;
$page = 0;
do{
	
	$query = "SELECT id, name, created, modified, caption, post_created_time, likes, comments, ig_link, ig_type, ig_id, hidden FROM `".$wpdb->prefix."pods_instagram_post_fast` ORDER BY id ASC LIMIT %d, 100";

	$posts = $wpdb->get_results($wpdb->prepare($query, $page), ARRAY_A);

	$page += 100;
	// 83 image_thumbnail
	// 84 image_hires
	if(sizeof($posts)){

		foreach($posts as $key=>$value){
			$count++;
			$query2 = "SELECT meta_value FROM `".$wpdb->prefix."postmeta` a WHERE meta_key = %s AND post_id IN ( SELECT related_item_id FROM `".$wpdb->prefix."podsrel` WHERE item_id = %d AND field_id = %d )";
			$temp = unserialize($wpdb->get_var($wpdb->prepare($query2, "_wp_attachment_metadata", $value["id"], "84")));
			if(isset($temp["file"])){
				//file variable exist

				//get igusername
				$query3 = "SELECT related_item_id FROM `".$wpdb->prefix."podsrel` WHERE item_id = %d AND field_id = %d";
				$instagrammer_id = $wpdb->get_var($wpdb->prepare($query3, $value["id"], 92));
				if(!isset($iger["ig".$instagrammer_id])){
					$query3 = "SELECT ig_id, igusername FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE id = %d";
					$temp_instagrammer = $wpdb->get_row($wpdb->prepare($query3, $instagrammer_id), ARRAY_A);
					$iger["ig".$instagrammer_id] = array(
						"id"=>$instagrammer_id,
						"igusername"=>$temp_instagrammer["igusername"],
						"ig_id"=>$temp_instagrammer["ig_id"]
					);
				}
				//get tag, country, language
				$query4 = "SELECT b.term_id, b.name FROM `".$wpdb->prefix."podsrel` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.related_item_id = b.term_id WHERE a.item_id = %d AND a.field_id = %d";
				$tags = $wpdb->get_results($wpdb->prepare($query4, $value["id"], 93), ARRAY_A);
				$countries = $wpdb->get_results($wpdb->prepare($query4, $value["id"], 94), ARRAY_A);
				$languages = $wpdb->get_results($wpdb->prepare($query4, $value["id"], 95), ARRAY_A);

				//go to upload or replace document
				$doc_object = array(
					"id"=>$value["id"],
					"ig_id"=>$value["ig_id"],
					"name"=>$value["name"],
					"image_hires"=>$temp,
					"hr_image"=>$temp,
					"caption"=>$value["caption"],
					"likes"=>$value["likes"],
					"comments"=>$value["comments"],
					"link"=>$value["ig_link"],
					"post_tag"=>$tags,
					"post_country"=>$countries,
					"post_language"=>$languages,
					"created"=>strtotime($value["created"]),
					"modified"=>strtotime($value["modified"]),
					"hidden"=>$value["hidden"],
					"igaccount"=>$iger["ig".$instagrammer_id]
				);

				$result = $main->putSearchElastic('/ig_posts/_doc/'.$value["ig_id"], $doc_object);
				echo "Progress: ".number_format(100* $count / $total)." % ".$count." / ".$total." ".substr(json_encode($result), 107, 7)." \r";   
			}
		}

	}
}while(sizeof($posts));