<?php  
use \InstagramScraper\Instagram as Instagram;
$obj = array(
	"error"=>1,
	"msg"=>"unknown"
);

	switch($_REQUEST["method"]){
		case "addCountry":
			$input = $_REQUEST["input"];
			if($input){
				$result = $main->createTag("country",$input);
				$obj["data"] = $result;
				$obj["error"] = 0;
				$obj["msg"] = "";
			}else{
				$obj["msg"] = "missing input";	
			}
		break;
		case "addCategory":
			$input = $_REQUEST["input"];
			if($input){
				$result = $main->createTag("category",$input);
				$obj["data"] = $result;
				$obj["error"] = 0;
				$obj["msg"] = "";
			}else{
				$obj["msg"] = "missing input";	
			}
		break;
		case "submitcreator":
			$igname = strtolower($_REQUEST["igusername"]);
			$instagram = new Instagram();
			try{
				$account = $instagram->getAccount($igname);

				if($account){
					$fullName = $account["fullName"] ? $account["fullName"] : $igname;
		            $profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];

		            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
		            $prepare = $wpdb->prepare($query, $account["id"]);
		            $instagrammer_id = $wpdb->get_var($prepare);

		            if($instagrammer_id){
		            	//item exist, do nothing
		            	$obj["error"] = 0;
		            	$obj["msg"] = "item exist, ignore data";
		            }else{
		            	//item not exist
						$result = $main->copyFileToWP($profileImage, $fullName, $igname);

			            $pod = pods("instagrammer_fast");
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
			                ),
			                "display_image"=>array(
			                	"id"=>$result["media_id"],
			                	"title"=>$fullName
			                ),
			                "hidden"=>1
			            );

			            $instagrammer_id = $pod->add($data);

						$main->updateUserTags($_REQUEST["countries"], array(), $_REQUEST["category"], $igname);

						$obj["error"] = 0;
						$obj["msg"] = "igusername @".$igname." added";
		            }
				}else{
					$obj["error"] = 0;
					$obj["msg"] = "igusername @".$igname." not exist";
				}
			}catch(Exception $e){
				$obj["error"] = 0;
				$obj["msg"] = "IG error, account not exist, or service down.";
			}
		break;
		default:
			$obj["msg"] = "unknown method";
		break;
	}
$obj["response_time"] = getDebugTime();
echo json_encode($obj);
?>