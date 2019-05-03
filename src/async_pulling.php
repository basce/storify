<?php  
set_time_limit(7200); //maximum execution time 2 hours

include_once("inc/main.php");
use storify\main as main;

$main = new main();

$igusername = isset($argv[1])?$argv[1]:"basce";
$userid = isset($argv[2])?$argv[2]:718;

$main->getCache()->set("nc_igplatform_post_updating_error", $igusername." ".$userid, 86400);

if($igusername && $userid && !$main->checkUpdatingRecord($igusername)){

	$main->getCache()->set("nc_igplatform_post_updating_error", "running in background", 86400);

	$temp_optionManager = new \NcIgPlatform_OptionsManager();
	$main->getCache()->set("nc_igplatform_post_updating_".$igusername, 1, 1800); // 30 mins
	$result = $temp_optionManager->autoPoll($igusername, $userid, 30);

	$main->getCache()->set("nc_igplatform_post_updating_".$igusername, 1, 7); // 7 seconds
	if($result["error"]){
		$main->getCache()->set("nc_igplatform_post_updating_error", $result, 86400); //keep error for 1 day for debugging
	}
}


?>