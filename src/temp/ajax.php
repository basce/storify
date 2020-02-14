<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../inc/main.php");
use storify\main as main;
$main = new main();

$current_user = wp_get_current_user();

$obj = array(
    "error"=>1,
    "msg"=>"unknonw"
);

function getRandomString($len, $chars = "abcdefghijklmnopqrstuvwxyz0123456789_"){
    $str = "";
    while(strlen($str) < $len){
        $str .= substr($chars, mt_rand(0, strlen($chars)),1);
    }
    return $str;
}

switch($_REQUEST["method"]){
    case "addReport":
        if( isset($_REQUEST["data"]) && sizeof($_REQUEST["data"]) && isset($_REQUEST["name"]) && isset($_REQUEST["password"]) ){
            //get random code
            $tempcode = getRandomString(20);
            $query = "INSERT INTO `".$wpdb->prefix."temp_report` ( name, password, unique_code, data ) VALUES ( %s, %s, %s, %s )";
            $wpdb->query($wpdb->prepare($query, $_REQUEST["name"], $_REQUEST["password"], $tempcode, json_encode($_REQUEST["data"])));

            $obj["error"] = 0;
            $obj["msg"] = "";
        }else{
            $obj["msg"] = "missing parameters";
        }
    break;
    case "getAllReport":
        $query = "SELECT id, name, password, unique_code, CONCAT('".get_home_url()."/temp/report.php?code=', unique_code) as `url` FROM `".$wpdb->prefix."temp_report`";
        $data = $wpdb->get_results($query);

        $obj["error"] = 0;
        $obj["data"] = $data;
    break;
}

echo json_encode($obj);