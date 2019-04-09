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

use \InstagramScraper\Instagram as Instagram;
use \storify\main as main;
include("inc/main.php");

$main = new main();

//get all id and igusername
$query = "SELECT id, igusername, ig_id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE hidden = %d ORDER BY last_automodified ASC LIMIT 50"; // 50 each time
$items = $wpdb->get_results($wpdb->prepare($query, 0), ARRAY_A);

$instagram = new Instagram();
$pod = pods('instagrammer_fast');

//begin pull
$query = "INSERT INTO `".$wpdb->prefix."crontask` ( name, start_date ) VALUES ( %s, NOW())";
$wpdb->insert(
	$wpdb->prefix."crontask",
	array(
		"name"=>"crontask ".date("y-m-d H:i:s"),
		"start_date"=>date("y-m-d H:i:s"),
		"msg"=>"total item :".sizeof($items)
	),
	array(
		'%s',
		'%s',
		'%s'
	)
);

$crontask_ID = $wpdb->insert_id;
$error = "";

foreach($items as $key=>$value){
	$igusername = $value["igusername"];
	$ig_id = $value["ig_id"];
	$instagrammer_id = $value["id"];
	try{	
		//update account data
		$account = $instagram->getAccount($igusername);

		if($account["id"] != $ig_id){ //id should be match, else the username is taken by other user, proceed to get new usernamw with ig_id
			$account = NULL;
			$error .= " id not match ";
		}
	}catch(Exception $e){
		//if error, user might change name
		$account = NULL;
		$error .= " get Account Error ".$e->getMessage()." ";
	}

	if(!$account){
		//account is NULL, try get media by id, then username by media
		
		try{
			
			$oldigusername = $igusername;
			$medias = $instagram->getMediasByUserId($ig_id);
			if($medias && sizeof($medias)){
				$media_id = $medias[0]->getId();
				$igusername = $instagram->getMediaById($media_id)->getOwner()->getUsername();
				
				$account = $instagram->getAccount($igusername);

				//account exist
				if($account){
					//update igusername
					$wpdb->update(
						$wpdb->prefix."pods_instagrammer_fast",
						array(
							'igusername'=>$igusername
						),
						array( 'ig_id' => $ig_id ),
						array( '%s' ),
						array( '%d' )
					);	

					//update igaccount connection, since it is using igusername
					$wpdb->update(
						$wpdb->prefix."igaccounts",
						array(
							'igusername'=>$igusername
						),
						array( 'igusername' => $oldigusername ),
						array( '%s'),
						array( '%s')
					);

					$error .= $oldigusername."(".$ig_id.") name change to ".$igusername.";";
				}
			}else{
				$error .= $oldigusername."(".$ig_id.") not able to be found, and no media, account deleted ? update modified date and revisit this item next round ";
				//update item, so will revisit in the next loop
				$query = "UPDATE `".$wpdb->prefix."pods_instagrammer_fast` SET last_automodified = NOW() WHERE id = %d";
				$wpdb->query($wpdb->prepare($query, $instagrammer_id));

			}

		}catch(exception $e){
			
			$account = NULL;
			$error .= $igusername."(".$ig_id.") >> ".$e->getMessage().";";
		}
	}

	if($account){
		$fullName = $account["fullName"] ? $account["fullName"] : $igusername;
		$profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];

		$result = $main->copyFileToWP($profileImage, $fullName, $igusername);


		$data = array(
			"name"=>$fullName,
			"ig_id"=>$account["id"],
			"igusername"=>$account["username"],
			"biography"=>$account["biography"],
			"media_count"=>$account["mediaCount"],
			"follows_count"=>$account["followsCount"],
			"follows_by_count"=>$account["followedByCount"],
			"external_url"=>$account["externalUrl"],
			"ig_profile_pic"=>array(
				"id"=>$result["media_id"],
				"title"=>$fullName
			)
		);

		$pod->save($data, null, $instagrammer_id);

		//update all posts and update
		$main->updateLatest30Posts($igusername, $instagrammer_id);

		$query = "INSERT INTO `".$wpdb->prefix."stats_no_followers` ( instagrammer_id, amount ) VALUES ( %d, %d )";
        $wpdb->query($wpdb->prepare($query, $instagrammer_id, $account["followedByCount"]));

        $query = "INSERT INTO `".$wpdb->prefix."stats_media_count` ( instagrammer_id, amount ) VALUES ( %d, %d )";
        $wpdb->query($wpdb->prepare($query, $instagrammer_id, $account["mediaCount"]));

        //update last_automodified
		$query = "UPDATE `".$wpdb->prefix."pods_instagrammer_fast` SET last_automodified = NOW() WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $instagrammer_id));

		//update msg
		
		$wpdb->update(
			$wpdb->prefix."crontask",
			array(
				'msg'=>" current progress ".( $key + 1 ) . " / " .sizeof($items)." total time spend :".getDebugTime()." - ".$error
			),
			array( 'id' => $crontask_ID ),
			array( '%s' ),
			array( '%d' )
		);	
	}
}

$wpdb->update(
	$wpdb->prefix."crontask",
	array(
		'msg'=>" Task Complete, total item :" .sizeof($items)." total time spend :".getDebugTime()." - ".$error,
		'complete_date'=>date("y-m-d H:i:s")
	),
	array( 'id' => $crontask_ID ),
	array( '%s', '%s' ),
	array( '%d' )
);	
?>