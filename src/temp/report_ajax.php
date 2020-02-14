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

switch($_REQUEST["method"]){
    case "updateRemark":
        $query = "INSERT INTO `".$wpdb->prefix."temp_report_remark` ( submission_id, msg ) VALUES ( %s, %s ) ON DUPLICATE KEY UPDATE msg = %s";
        $wpdb->query($wpdb->prepare($query, $_REQUEST["id"], $_REQUEST["msg"], $_REQUEST["msg"]));

        $obj["error"] = 0;
        $obj["msg"] = "";
    break;
}

echo json_encode($obj);