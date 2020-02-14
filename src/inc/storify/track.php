<?php
namespace storify;

class track{

	function __construct(){

	}

	public static function trackSearch($passions, $countries, $user_id = NULL){
		global $wpdb;

		//get IP
		$ip = $_SERVER["REMOTE_ADDR"];
		if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}

		//insert history
		$query = "INSERT INTO `".$wpdb->prefix."track_history` ( ip, user_id, search_terms ) VALUES ( %s, %d, %s)";
		$wpdb->query($wpdb->prepare($query, $ip, $user_id, json_encode(array($countries, $passions))));

		//update count
		//get term name
		$query = "SELECT name, term_id FROM `".$wpdb->prefix."terms` WHERE term_id = %d";
		$query2 = "SELECT COUNT(*) FROM `".$wpdb->prefix."search_count` WHERE term_id = %d";
		$query3 = "INSERT INTO `".$wpdb->prefix."search_count` ( name, term_id, num, category ) VALUES ( %s, %d, %d, %s )";
		$query4 = "UPDATE `".$wpdb->prefix."search_count` SET num = num + 1 WHERE term_id = %d";

		$searchterms = array();
		if(sizeof($countries)){
			foreach($countries as $key=>$value){
				$term = $wpdb->get_row($wpdb->prepare($query, $value), ARRAY_A);
				if($term){
					$searchterms[] = array(
						"name"=>$term["name"],
						"term_id"=>$term["term_id"],
						"category"=>"country"
					);
					if(!$wpdb->get_var($wpdb->prepare($query2, $term["term_id"]))){
						$wpdb->query($wpdb->prepare($query3, $term["name"], $term["term_id"], 1, "country"));
					}else{
						$wpdb->query($wpdb->prepare($query4, $term["term_id"]));
					}
				}
			}
		}

		if(sizeof($passions)){
			foreach($passions as $key=>$value){
				$term = $wpdb->get_row($wpdb->prepare($query, $value), ARRAY_A);
				if($term){
					$searchterms[] = array(
						"name"=>$term["name"],
						"term_id"=>$term["term_id"],
						"category"=>"passion"
					);
					if(!$wpdb->get_var($wpdb->prepare($query2, $term["term_id"]))){
						$wpdb->query($wpdb->prepare($query3, $term["name"], $term["term_id"], 1, "passion"));
					}else{
						$wpdb->query($wpdb->prepare($query4, $term["term_id"]));
					}
				}	
			}
		}

		return array(
			"user"=>array(
				"id"=>$user_id,
				"ip"=>$ip
			),
			"search"=>$searchterms,
			"time"=>time()
		);
	}

}