<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("inc/main.php");
use storify\main as main;

$query = "SELECT biography FROM `wp_pods_instagrammer_fast`";

$all = $wpdb->get_results($wpdb->prepare($query,array()), ARRAY_A);
foreach($all as $key=>$value){
	echo $value["biography"]." ";
}