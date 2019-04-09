<?php

define("SB_EDGE","https://api.sendbird.com/v3");
define("SB_TOKEN","2655cdcaccf02febc4abb797b6320e08a9a435ad");
define("SB_ID","68CE9863-C07D-4505-A659-F384AB1DE478");
//n60mnhlb9mn0awh03i7655ajq2owygvw
$query = array(
	"user_id"=>"n60mnhlb9mn0awh03i7655ajq2owygvw",
	"nickname"=>"Yong Chee Wei",
	"profile_url"=>"https://cdn.storify.me/data/uploads/2019/01/basce.jpg",
	"issue_access_token"=>true
);

try{

	$headers = array("Content-Type: application/json, charset=utf8", "Api-Token: ".SB_TOKEN);
	$ch = curl_init();
	//post
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_URL, SB_EDGE."/users");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$contents = curl_exec($ch);

	print_r($contents);
	curl_close($ch);

	/*
	access_token:e8b31e1e4fa3eb83b91b4d1ca7b8d3155fe9b419
	 */

}catch(Exception $e){
	print_r($e);
}
?>