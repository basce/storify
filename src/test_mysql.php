<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("inc/main.php");
use storify\main as main;
$main = new main();

$query = "SELECT UNIX_TIMESTAMP()";
echo $wpdb->get_var($query);
echo "<br>";
echo time();
echo "<br>";
$query = "SELECT CONVERT_TZ(NOW(), 'UTC', 'Singapore')";
echo $wpdb->get_var($query);
echo "<br>";
echo date("Y-m-d H:i:s");

$input_dt = "2019-06-06 00:00:00";
echo $input_dt;
echo "<br>";

?>