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

//\storify\project\post_report::getInstance()->responsePostReport()

//\storify\project\submission::getInstance()->responseSubmission(2, "accepted", "");

$main->getProjectManager()->generateProjectSummary(5);

//get task
/*
\storify\project\submission::getInstance()->createSubmission(4, 6, "igpost", "addtional msg", json_encode(array(
	"files"=>array(
		array(
			"id"=>838,
			"filename"=>"01-TH-Mask-02.png",
			"mime"=>"image/png",
			"size"=>1908977
		),
		array(
			"id"=>839,
			"filename"=>"01-SG-Souffle-01.png",
			"mime"=>"image/png",
			"size"=>2437214	
		)
	),
	"captions"=>"some text"
)));
*/

/*
\storify\project\post_report::getInstance()->makePostReport(4, 6, "post performance image", json_encode(array(
	"files"=>array(
		array(
			"id"=>838,
			"filename"=>"01-TH-Mask-02.png",
			"mime"=>"image/png",
			"size"=>1908977
		)
	)
)));
*/
?>