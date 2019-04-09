<?php  
set_time_limit(0);
include("inc/class.main.php");

$main = new main();

$main->setCacheParams((int)get_option('custom_settings_enable_cache'), 4000);

//cache main page, singapore, malaysia, indonesia page
$main->getIger(null, null, null,12,1,"",true); // force cache
$main->getIger(null, array("4"), null,12,1,"",true); // force cache
$main->getIger(null, array("14"), null,12,1,"",true); // force cache
$main->getIger(null, array("60"), null,12,1,"",true); // force cache