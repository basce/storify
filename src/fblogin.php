<?php

//iglogin

if(isset($_GET["error"])){
    if($_GET["error"] == "user_denied"){
        header("Location: /login");
        exit();
    }else{
        $error_msg = "A connection error has occured. We hope you will try connecting again.";
        include_once("page/error/index.php");
        exit();
    }
}else if(isset($_GET["code"])){
    //check
    //print_r($_GET["code"]);

    try{
    	$ch = curl_init();

        $data = array(
            "client_id"=>"310258729772529",
            "redirect_uri"=>get_home_url()."/fblogin/",
            "client_secret"=>"ba5028fba82a694a79cfa50a89ef92dc",
            "code"=>$_GET["code"]
        );
    	curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v3.1/oauth/access_token?'.http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    	$response = curl_exec($ch);

    	curl_close($ch);
    	
        $response_obj = json_decode($response, true);

        //print_r($response_obj);

        if(isset($response_obj["access_token"])){

            $data = array(
                "fields"=>"id,name,email",
                "access_token"=>$response_obj["access_token"]
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v3.1/me?'.http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $me_response = json_decode(curl_exec($ch), true);

            curl_close($ch);
            
            if(isset($me_response) && isset($me_response["email"]) && $me_response["email"]){

                $temp_userObj = get_user_by("email", $me_response["email"]);
                if($temp_userObj){
                    //user exist, 
                    wp_set_auth_cookie($temp_userObj->ID, true); // login

                    //redirect user to profile is not yet setup, check if country is set
                    $temp_country = get_user_meta($temp_userObj->ID, 'city_country', true);
                    if(!$temp_country){
                        //country not exist
                        header("Location: /user@".$temp_userObj->ID."/profile");
                        exit();
                    }

                    if(!isset($_SESSION["role_view"])){
                        //get uesr defautl view
                        $temp_default_view = get_user_meta($temp_userObj->ID, "default_role", true);
                        $_SESSION["role_view"] = $temp_default_view;
                    }
                    
                    //redirect user to social if not set
                    if($_SESSION["role_view"] && $_SESSION["role_view"] == "brand"){
                        header("Location: /user@".$temp_userObj->ID."/projects/ongoing");
                        exit();
                    }else{
                        $IGUsernames = $main->getUserIGAccounts($temp_userObj->ID);
                        if(!sizeof($IGUsernames)){
                            //social not exist
                            header("Location: /user@".$temp_userObj->ID."/showcase");
                            exit();   
                        }
                    }

                    if(isset($_SESSION["landingpage_redirect"])){
                        $redirect_url = $_SESSION["landingpage_redirect"];
                        unset($_SESSION["landingpage_redirect"]);

                        //redirect user to dashboard
                        header("Location: ".$redirect_url);
                        exit();
                    }else{
                        //redirect user to dashboard
                        header("Location: /user@".$temp_userObj->ID."/performance");
                        exit();
                    }

                }else{
                    $result = $main->createUser($me_response["email"], '', $me_response["name"], '','', 0);
                    if($result["error"]){
                        $error_msg = $result["msg"];
                        include_once("page/error/index.php");
                        exit();        
                    }else{
                        wp_set_auth_cookie($result["id"], true); //login
                        header("Location: /user@".$user_ID."/profile");
                        exit();
                    }
                }
            }else{
                //authentication error
                $error_msg = "Authentication error.";
                include_once("page/error/index.php");
                exit();
            }
            
        }else{
            $error_msg = json_encode(array("call"=>'https://graph.facebook.com/v3.1/oauth/access_token?'.http_build_query($data), "response"=>$response_obj));
            include_once("page/error/index.php");
            exit();
        }

    }catch(Exception $e){
        $error_msg = $e->getMessage();
        include_once("page/error/index.php");
        exit();
    }
}

?>