<?php
//iglogin

namespace iglogin;
use \InstagramScraper\Instagram as Instagram;
use \InstagramScraper\Exception\InstagramNotFoundException as InstagramNotFoundException;

if(isset($_GET["error"])){
    header("Location: /user@".$current_user->ID."/social/?error=".$_GET["error"]);
    exit();
}else if(isset($_GET["code"])){
    //check
    try{
    	$ch = curl_init();

    	$data = array(
    		"client_id"=>'cb5c39433c444e3fb8161f72e632ea19',
    		"client_secret"=>'e3f9cc618e6d41998e253ddd9efef96b',
    		"grant_type"=>"authorization_code",
    		"redirect_uri"=>"https://storify.me/iglogin/",
    		"code"=>$_GET["code"]
    	);
    	curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
    	curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    	$response = curl_exec($ch);

    	curl_close($ch);

        $igData = json_decode($response, true);

        if(isset($igData["error_message"])){
            header("Location: /user@".$current_user->ID."/showcase/?error=".$igData["error_message"]);
            exit();
        }else{

            $igname = $main->setUserIGAccount($current_user->ID, $igData["user"]["username"]);
            
            $instagram = new Instagram();
            $account = $instagram->getAccount($igname);

            $fullName = $account["fullName"] ? $account["fullName"] : $current_user->display_name;

            $profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];

            $tempobj = array(
                "name"=>$fullName,
                "ig_id"=>$account["id"],
                "igusername"=>$account["username"],
                "ig_profile_pic"=>$profileImage,
                "biography"=>$account["biography"],
                "media_count"=>$account["mediaCount"],
                "follows_by_count"=>$account["followsCount"],
                "external_url"=>$account["externalUrl"]
            );
            
            $result = $main->copyFileToWP($profileImage, $fullName, $igname);

            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
            $prepare = $wpdb->prepare($query, $account["id"]);
            $instagrammer_id = $wpdb->get_var($prepare);

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
                "verified"=>"1",
                "ig_profile_pic"=>array(
                    "id"=>$result["media_id"],
                    "title"=>$fullName
                )
            );

            // get country name
            $country_label = "";
            foreach($main->getCountriesList() as $key=>$value){
                if(sizeof( $current_user_meta["city_country"]) && ($key == $current_user_meta["city_country"][0])){
                    $country_label = $value;
                }
            }

            $country_pod = pods('instagrammer_country');
            $params = array(
                'where'=>'UPPER(t.name) = UPPER("'.$country_label.'")'
            );
            $country_pod->find($params);
            $tempobj = NULL;
            if($country_pod->total()){
                while($country_pod->fetch()){
                    $tempobj = array(
                        "id"=>$country_pod->field("term_id"),
                        "name"=>$country_pod->field("name"),
                        "hidden"=>sizeof($country_pod->field("hidden"))?$country_pod->field("hidden"):0
                    );
                }
            }

            if($tempobj){
                //item exist
                $temp_id = $tempobj["id"];
            }else if($country_label){
                //item not exist
                $temp_id = $pod->add(array(
                    "name"=>$country_label,
                    "hidden"=>0
                ));
            }

            // check if country category exist, else create
            // connect with the account

            if($instagrammer_id){
                $pod->save($data, null, $instagrammer_id);
            }else{
                $data["display_image"] = array(
                    "id"=>$result["media_id"],
                    "title"=>$fullName
                );
                $data["hidden"] = 1;
                $instagrammer_id = $pod->add($data);
            }

            //add country tag
            $pod2 = pods("instagrammer_fast", $instagrammer_id);
            $pod2->add_to("instagrammer_country", $temp_id);         

            header("Location: /user@".$current_user->ID."/showcase/".$igname);
            exit();
        }
    }catch(Exception $e){
        if($e instanceof InstagramNotFoundException){
            $error_msg = $e->getMessage();
        }else{
            $error_msg = $e->getMessage();
        }
        header("Location: /user@".$current_user->ID."/showcase/?error=".$error_msg);
        exit();
    }
}

?>