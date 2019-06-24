<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use storify\main as main;

include("inc/main.php");

$main = new main();

$post_pods = pods("instagram_post_fast");
$param = array(
	"limit"=>1000
);

$post_pods->find($param);
print_r("total post got issues :".$post_pods->total());

$instagrammerIDs = array();

function getInstagrammerID($igusername){
	global $instagrammerIDs, $wpdb;

	$id = 0;
	if(sizeof($instagrammerIDs)){
		foreach($instagrammerIDs as $key=>$value){
			if($value["igusername"] == $igusername){
				$id = $value["id"];
			}
		}
		if($id){
			return $id;
		}
	}

	$query = "SELECT id FROM `wp_pods_instagrammer_fast` WHERE igusername = %s";
	$id = $wpdb->get_var($wpdb->prepare($query, $igusername));
	if($id){
		$instagrammerIDs[] = array(
			"id"=>$id,
			"igusername"=>$igusername
		);
	}
	return $id;
}

$count = 10000;
if( 0 && 0 < $post_pods->total() ){
    while( $post_pods->fetch()){
    	/*
    	$name = $post_pods->field("name");
    	$accountname = substr($name, 0, strpos($name, " - IG Post"));

    	$id = getInstagrammerID($accountname);
    	if($id && $id != 16){
    		$temp_pod = pods('instagram_post_fast', $post_pods->field("id")); 
    		$temp_pod->save('instagrammer', $id);
    		print_r($post_pods->field("id")." ".json_encode($post_pods->field('instagrammer'))." attach to instagrammer ".$id."\n");
    	}else{
    		print_r("not able to find instagrammer");
    	}
		*/
		$image_url = pods_image_url($post_pods->field('image_hires')["ID"], 'large');
		if(!strpos($image_url, "cdn")){
			//doesn't contain cdn
			$get_attachment = new WP_Query(array(
				"post_type"=>"attachment",
				"name"=>$post_pods->field("name")
			));

			print_r($get_attachment->posts);
			exit();
		} 
    	$count--;
    	if($count < 0){
    		exit("max reach");
    	}
    }
 }
?>