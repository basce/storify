<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("HOME_DIR", __dir__);
error_reporting(E_ALL);

if(!session_id()) {
    session_start();
}

$debugTime = microtime(true);
function getDebugTime(){
    global $debugTime;
    return microtime(true) - $debugTime;
}

include_once("inc/main.php");
use storify\main as main;
use storify\job as job;
use storify\pagesettings as pagesettings;

$main = new main();
$main->setCacheParams((int)get_option('custom_settings_enable_cache'), (int)get_option('custom_settings_cache_duration'));
$pathquery = $main->getQueryPath();

if(function_exists('stripslashes_deep')){
    $_GET    = stripslashes_deep( $_GET );
    $_POST   = stripslashes_deep( $_POST );
    $_REQUEST   = stripslashes_deep( $_REQUEST );
    $_COOKIE = stripslashes_deep( $_COOKIE );
}

$current_user = wp_get_current_user();

$pageManager = new pagesettings($current_user->ID);
if($current_user->ID){
    $current_user_meta = get_user_meta($current_user->ID);

    if(!isset($_SESSION["role_view"])){
        //get uesr defautl view
        $temp_default_view = get_user_meta($current_user->ID, "default_role", true);
        $_SESSION["role_view"] = $temp_default_view;
    }
}else{
    $current_user_meta = NULL;
}

if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){
    //get default business account page
    $default_group_id = \storify\business_group::getDefaultGroup($current_user->ID);
    $default_group = \storify\business_group::getDefaultGroupObject($current_user->ID);
}else{
    $default_group_id = NULL;
    $default_group = NULL;
}

$pageSettings = $pageManager->getSettings("home");
if(sizeof($pathquery) == 0){
    $category_tags = $main->getAllTagsInUsed();
    $country_tags = $main->getAllCountriesInUsed();
    
    include_once("page/main/index.php");
    exit();
}else{
    if($pathquery[0] == ""){
        $category_tags = $main->getAllTagsInUsed();
        $country_tags = $main->getAllCountriesInUsed();
        $pageSettings = $pageManager->getSettings("home");

        include_once("page/main/index.php");
        exit();
    }else if($pathquery[0] == "stripe_authorize"){
        include_once("page/payment/stripe_authorize.php");
        exit();
    }else if($pathquery[0] == "listing"){
        $pageSettings = $pageManager->getSettings("listing");

        include_once("page/main/list.php");
        exit();
    }else if($pathquery[0] == "signup"){
        if(sizeof($pathquery) == 1){
            $pageSettings = $pageManager->getSettings("signup");
            if(isset($_POST) && sizeof($_POST)){
                //registration
                include_once("page/register/func-insert.php");

                if($insert_success){
                    header("Location: /welcome");
                    exit();
                }else{
                    include_once("page/error/index.php");
                    exit();
                }
            }else{
                include_once("page/register/register.php");
                exit();
            }
        }else{
            if($pathquery[1] == "ajax"){
                include_once("page/register/ajax.php");
                exit();
            }
        }
    }else if($pathquery[0] == "welcome"){
        $pageSettings = $pageManager->getSettings("signup");
        include_once("page/register/welcome.php");
        exit();
    }else if($pathquery[0] == "signout"){
        wp_logout();
        //also clear session
        //remove PHPSESSID from browser
        if ( isset( $_COOKIE[session_name()] ) )
        setcookie( session_name(), "", time()-3600, "/" );
        //clear session from globals
        $_SESSION = array();
        //clear session from disk
        session_destroy();

        header("Location: /");
        exit();
    }else if($pathquery[0] == "signin"){
        $pageSettings = $pageManager->getSettings("signin");
        if(isset($_POST) && sizeof($_POST)){
            include_once("page/login/func-login.php");
            if($login_success){
                if($login_redirect){
                    header("Location: ".$login_redirect);
                    exit();
                }else{
                    header("Location: /user@".$user_ID."/projects/ongoing");
                    exit();
                }
            }else{
                include_once("page/login/index.php");
                exit();      
            }
        }else{
            include_once("page/login/index.php");
            exit();
        }
    }else if($pathquery[0] == "iglogin"){
        include_once("iglogin.php");
        exit();
    }else if($pathquery[0] == "fblogin"){
        include_once("fblogin.php");
        exit();
    }else if($pathquery[0] == "json"){
        if(isset($_POST)){
            include_once("page/main/json.php");
            exit();
        }
    }else if($pathquery[0] == "resetpassword"){
        $pageSettings = $pageManager->getSettings("reset_password");
        if(isset($_POST) && sizeof($_POST)){
            include_once("page/resetpw/func-resetpw.php");
            include_once("page/resetpw/resetpw.php");
            exit();
        }else{
            $temp_wp_user = check_password_reset_key(isset($_GET["key"])?$_GET["key"]:"", isset($_GET["login"])?$_GET["login"]:"");
            if(is_wp_error($temp_wp_user)){
                $key_invalid = true;
            }else{
                $key_invalid = false;
            }
            include_once("page/resetpw/resetpw.php");
            exit();
        }
    }else if($pathquery[0] == "forgotpassword"){
        $pageSettings = $pageManager->getSettings("forgot_password");
        if(isset($_POST) && sizeof($_POST)){
            include_once("page/resetpw/func-retrievepw.php");
            include_once("page/resetpw/retrievepw.php");
            exit();
        }else{
            include_once("page/resetpw/retrievepw.php");
            exit();
        }
    }else if($pathquery[0] == "collections"){
        if(sizeof($pathquery) == 4){
            $temp_type = $pathquery[1];
            $temp_userID = $pathquery[2];
            $temp_groupID = $pathquery[3];

            //check if folder exist and belong to temp_userID
            if($main->checkGroupOwnerShipByID($temp_groupID, $temp_userID, $temp_type)){
                //setup page
                $group_detail = $main->getGroupDetail($temp_groupID, $temp_type);
                if($temp_type == "people"){
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/public/group_people_ajax.php");
                        exit();
                    }else{
                        include_once("page/public/group_people.php");
                        exit();
                    }
                }else{
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/public/group_stories_ajax.php");
                        exit();
                    }else{
                        include_once("page/public/group_stories.php");
                        exit();
                    }
                }
                exit();
            }else{
                // uid and group id doesn't match
            }
        }
    }else if($pathquery[0] == "submitcreator"){
        if(isset($_POST) && sizeof($_POST)){
            include_once("page/submitcreator/ajax.php");
            exit();
        }else{
            $pageSettings = $pageManager->getSettings("submitcreator");
            include_once("page/submitcreator/index.php");
            exit();
        }
    }else if($pathquery[0] == "test_email"){
        include_once("page/test/test_email.php");
        exit();
    }else if($pathquery[0] == "project" && sizeof($pathquery) > 1){
        if(!$current_user->ID){
            header("Location: /signin?redirect=".$_SERVER['REQUEST_URI']);
            exit();
        }else{
            if($pathquery[1] == "invited"){
                $main->changeDefaultRole("creator",$current_user->ID);
                header("Location: /user@".$current_user->ID."/projects/invited");
                exit();
            }else if($pathquery[1] == "new"){
                $main->changeDefaultRole("brand",$current_user->ID);
                header("Location: /user@".$current_user->ID."/projects/ongoing/new");
                exit();
            }else{
                $result = $main->getProjectManager()->getProjectLink($pathquery[1], $current_user->ID);
                if($result["redirect"]){
                    $main->changeDefaultRole($result["role"],$current_user->ID);
                    if(sizeof($pathquery) > 2){
                        header("Location: ".$result["redirect"]."/".$pathquery[2]);
                    }else{
                        header("Location: ".$result["redirect"]);
                    }
                    exit();
                }else{
                    //is null
                    $error_msg = "Invalid Authentication. You're trying to access a page that doesn't belong to you, please <a href=\"".get_home_url()."/signin?redirect=".$_SERVER['REQUEST_URI']."\">login</a> to your account and try again.";
                    include_once("page/error/index.php");
                    exit();
                }
            }
        }
    }else if(sizeof($pathquery) >= 2 && $pathquery[0] == "user" ){
        if(!$current_user->ID){
            header("Location: /signin?redirect=".$_SERVER['REQUEST_URI']);
            exit();
        }else{
            switch($pathquery[1]){
                case "showcase":
                    $main->changeDefaultRole("creator",$current_user->ID);
                    header("Location: /user@".$current_user->ID."/showcase");
                    exit();
                break;
                case "collections":
                    header("Location: /user@".$current_user->ID."/collections");
                    exit();
                break;
                case "profile":
                    header("Location: /user@".$current_user->ID."/profile");
                    exit();
                break;
                case "business_profile":
                    header("Location: /user@".$current_user->ID."/business_profile");
                    exit();
                break;
                case "business_add":
                    header("Location: /user@".$current_user->ID."/business_add");
                    exit();
                break;
                case "business_group":
                    header("Location: /user@".$current_user->ID."/business_group");
                    exit();
                break;
                case "business_invite":
                    header("Location: /user@".$current_user->ID."/business_invite");
                    exit();
                break;
                case "business_welcome":
                    header("Location: /user@".$current_user->ID."/business_welcome");
                    exit();
                break;
                case "business_member":
                    header("Location: /user@".$current_user->ID."/business_member");
                    exit();
                break;
                case "business_payment":
                    header("Location: /user@".$current_user->ID."/business_payment");
                    exit();
                break;
                case "password":
                    header("Location: /user@".$current_user->ID."/password");
                    exit();
                break;
                case "projects":
                    if(sizeof($pathquery) == 3){
                        if($pathquery[2] == "invited"){
                            $main->changeDefaultRole("creator",$current_user->ID);
                        }
                        header("Location: /user@".$current_user->ID."/projects/".$pathquery[2]);
                        exit();
                    }else{
                        header("Location: /user@".$current_user->ID."/projects");
                        exit();
                    }
                break;
            }
        }
    }

    $result = $main->getSingleIger($pathquery[0]);
    if($result){
        $pageSettings = $pageManager->getSettings("iger");
        include_once("page/main/iger.php");
        exit();
    }

    //check if with user id
    $userID = $main->parseUserTerm($pathquery[0]);
    if($userID == -1){
        header("Location: /signin?redirect=".$_SERVER['REQUEST_URI']);
        exit();
    }else if($userID){
        if(!$current_user->ID){
            header("Location: /signin?redirect=".$_SERVER['REQUEST_URI']);
            exit();
        }
        if($current_user->ID != $userID){

            //check if a group page, if yes, redirect
            if(sizeof($pathquery) > 3 ){
                if($pathquery[1] == "collections" && ( $pathquery[2] == "people" || $pathquery[2] == "stories" ) && is_numeric($pathquery[3])){
                    header("Location: /collections/".$pathquery[2]."/".$userID."/".$pathquery[3]);
                    exit();
                }
            }

            $error_msg = "Invalid Authentication. You're trying to access a page that doesn't belong to you, please <a href=\"".get_home_url()."/signin?redirect=".$_SERVER['REQUEST_URI']."\">login</a> to your account and try again.";
            include_once("page/error/index.php");
            exit();
        }
        if(sizeof($pathquery) == 1){
            //user exist
            header("Location: /user@".$userID."/collections");
            exit();
        }else{
            if($pathquery[1] == "viewascreator"){
                $main->changeDefaultRole("creator",$userID);

                header("Location: /user@".$userID."/projects/ongoing");
                exit();
            }
            if($pathquery[1] == "viewasbrand"){
                $main->changeDefaultRole("brand",$userID);

                header("Location: /user@".$userID."/projects/ongoing");
                exit();
            }
            if($pathquery[1] == "welcomebrand"){
                $main->changeDefaultRole("brand",$userID);                

                header("Location: /user@".$userID."/projects/ongoing");
                exit();
            }
            if($pathquery[1] == "performance"){
                $pageSettings = $pageManager->getSettings("performance");
                //need to check if user login
                include_once("page/user/dashboard.php");
                exit();
            }
            if($pathquery[1] == "project"){
                //check if user can access project
                if($pathquery[2] == "new"){
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/user/projects_brand_new_project_ajax.php");
                        exit();
                    }else{
                        include_once("page/user/projects_brand_new_project.php");
                        exit();
                    }
                }

                //check if user have offer
                $offer_status = \storify\project\offer::getInstance()->getUserLastOfferStatus($pathquery[2], $current_user->ID);
                

                if($offer_status){
                    if(isset($_POST) && sizeof($_POST)){

                    }else{
                        $project_detail = $main->getProjectManager()->getProjectDetail($pathquery[2], $current_user->ID);;

                        include_once("page/user/projects_creator_single_project.php");
                        exit();
                    }
                }else{
                    // check if brand exist
                    if($main->getProjectManager()->isHaveBusinessAccess($pathquery[2])){
                        //with brand access

                        if(isset($_POST) && sizeof($_POST)){

                        }else{
                            $project_detail = $main->getProjectManager()->getProjectDetail($pathquery[2], $current_user->ID);;

                            include_once("page/user/projects_brand_single_project.php");
                            exit();
                        }    

                    }

                }

                //redirect user to project page
                header("Location: /user@".$userID."/projects");
                exit();
            }
            if($pathquery[1] == "projects"){
                $pageSettings = $pageManager->getSettings("projects");
                if($_SESSION["role_view"] == "brand"){
                    //check if verified.
                    if(!$main->isBrandVerified($current_user->ID)){
                        include_once("page/user/projects_brand_not_approve.php");
                        exit();
                    }
                    if(!$default_group_id){
                        //no group id, go to brand welcome page
                        header("Location: /user@".$userID."/business_welcome");
                        exit();
                    }
                    //if not yet set payment method or invoice
                    if(!$default_group["invoice"]){
                        $allcards = \storify\stripe::getAllCards($default_group_id);
                        if($allcards && sizeof($allcards->data)){
                            
                        }else{
                            //no card,
                            header("Location: /user@".$userID."/business_payment");
                            exit();
                        }
                    }

                    //brand, ongoing, closed
                    if(sizeof($pathquery) == 2){
                        header("Location: /user@".$userID."/projects/ongoing");
                        exit();
                        /*
                        header("Location: /user@".$userID."/projects/ongoing");
                        exit();
                        */
                    }
                    if($pathquery[2] == "ongoing"){
                        $pageSettings = $pageManager->getSettings("projects_ongoing");
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/projects_brand_ongoing_ajax.php");
                            exit();
                        }else{
                            include_once("page/user/projects_brand_ongoing.php");
                            exit();
                        }
                    }

                    if($pathquery[2] == "closed"){
                        $pageSettings = $pageManager->getSettings("projects_closed");
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/projects_brand_closed_ajax.php");
                            exit();
                        }else{
                            include_once("page/user/projects_brand_closed.php");
                            exit();
                        }
                    }
                    
                }else{
                    //creator, invited, ongoing, closed
                    if(sizeof($pathquery) == 2){
                        header("Location: /user@".$userID."/projects/invited");
                        exit();
                        /*
                        header("Location: /user@".$userID."/projects/invited");
                        exit();
                        */
                    }
                    if($pathquery[2] == "invited"){
                        $pageSettings = $pageManager->getSettings("projects_invited");
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/projects_creator_invited_ajax.php");
                            exit();
                        }else{
                            include_once("page/user/projects_creator_invited.php");
                            exit();
                        }
                    }
                    if($pathquery[2] == "ongoing"){
                        $pageSettings = $pageManager->getSettings("projects_ongoing");
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/projects_creator_ongoing_ajax.php");
                            exit();
                        }else{
                            include_once("page/user/projects_creator_ongoing.php");
                            exit();
                        }
                    }
                    if($pathquery[2] == "closed"){
                        $pageSettings = $pageManager->getSettings("projects_closed");
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/projects_creator_closed_ajax.php");
                            exit();
                        }else{
                            include_once("page/user/projects_creator_closed.php");
                            exit();
                        }
                    }
                }
                    
                $error_msg = "Page not exist ".$pathquery[2];
                include_once("page/error/index.php");
                exit();
                
            }
            if($pathquery[1] == "people"){
                $pageSettings = $pageManager->getSettings("people_folder_listing");
                if(sizeof($pathquery) == 2){
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/user/folders_people_ajax.php");
                        exit();
                    }else{
                        include_once("page/user/folders_people.php");
                        exit();
                    }
                }
            }
            if($pathquery[1] == "stories"){
                $pageSettings = $pageManager->getSettings("story_folder_listing");
                if(sizeof($pathquery) == 2){
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/user/folders_stories_ajax.php");
                        exit();
                    }else{
                        include_once("page/user/folders_stories.php");
                        exit();
                    }
                }
            }
            if($pathquery[1] == "collections"){
                $pageSettings = $pageManager->getSettings("collections");
                if(sizeof($pathquery) > 2 ){
                    if(sizeof($pathquery) == 3){
                        if($pathquery[2] == "people"){
                            $pageSettings = $pageManager->getSettings("people_bookmark");
                        }else{
                            $pageSettings = $pageManager->getSettings("story_bookmark");
                        }
                        if(isset($_POST) && sizeof($_POST)){
                            include_once("page/user/collections_".$pathquery[2]."_ajax.php");
                            exit();
                        }else{
                            //page contain all bookmark items.
                            include_once("page/user/collections_".$pathquery[2].".php");
                            exit();
                        }
                    }else{
                        //individual page, need to check if the collection id below to user.
                        $temp_type = $pathquery[2] == "people" ? "people" : "story";
                        if($main->checkGroupOwnerShip($pathquery[3], $temp_type)){
                            $group_detail = $main->getGroupDetail($pathquery[3], $temp_type);
                            if(sizeof($pathquery) == 4){
                                $pageSettings = $pageManager->getSettings($temp_type."_folder");
                                $pageSettings["breadcrumb"][] = array(
                                    "label"=>$group_detail["name"],
                                    "href"=>""
                                );
                                $pageSettings["meta"]["canonical"]="https://storify.me/collections/".$pathquery[2]."/".$userID."/".$pathquery[3];
                                if($temp_type == "people"){
                                    if(isset($_POST) && sizeof($_POST)){
                                        include_once("page/user/folder_people_ajax.php");
                                        exit();
                                    }else{
                                        include_once("page/user/folder_people.php");
                                        exit();
                                    }
                                }else{
                                    if(isset($_POST) && sizeof($_POST)){
                                        include_once("page/user/folder_stories_ajax.php");
                                        exit();
                                    }else{
                                        include_once("page/user/folder_stories.php");
                                        exit();
                                    }
                                }
                            }else{
                                if($pathquery[4] == "add"){
                                    $pageSettings = $pageManager->getSettings($temp_type."_folder");
                                    $pageSettings["breadcrumb"][] = array(
                                        "label"=>$group_detail["name"],
                                        "href"=>"/user@".$current_user->ID."/collections/".$pathquery[2]."/".$group_detail["id"]
                                    );
                                    $pageSettings["breadcrumb"][] = array(
                                        "label"=>"Add",
                                        "href"=>""
                                    );
                                    if(isset($_POST) && sizeof($_POST)){
                                        include_once("page/user/collections_".$pathquery[2]."_add_ajax.php");
                                        exit();
                                    }else{
                                        //page contain all bookmark items.
                                        include_once("page/user/collections_".$pathquery[2]."_add.php");
                                        exit();
                                    }
                                }else{
                                    $error_msg = "unknown path";
                                    include_once("page/error/index.php");
                                    exit();
                                }
                            }
                        }else{
                            //not belong to user, go to public view
                            header("Location: /collections/".$pathquery[2]."/".$userID."/".$pathquery[3]);
                            exit();
                            /*
                            $error_msg = "invalid ownership.";
                            include_once("page/error/index.php");
                            exit();*/
                        }
                    }
                }
                if(isset($_POST) && sizeof($_POST)){
                    
                    include_once("page/user/collections_ajax.php");
                    exit();
                }else{
                    $pageSettings = $pageManager->getSettings("collections");
                    include_once("page/user/collections.php");
                    exit();
                }
            }
            if($pathquery[1] == "password"){
                $pageSettings = $pageManager->getSettings("updatepassword");
                if(sizeof($_POST)){
                    include_once("page/user/password-update.php");
                    include_once("page/user/password.php");
                    exit();
                }else{
                    include_once("page/user/password.php");
                    exit();
                }
            }
            if($pathquery[1] == "showcase"){
                if(sizeof($pathquery) == 2){
                    $pageSettings = $pageManager->getSettings("showcase");
                    if(sizeof($_POST)){
                        include_once("page/user/ajax.php");
                        exit();
                    }else{
                        $IGUsernames = $main->getUserIGAccounts($current_user->ID);
                        if(sizeof($IGUsernames)){
                            //$social_account = $main->getSingleIger($socialIDs[0]);
                            header("Location: /user@".$user_ID."/showcase/".$IGUsernames[0][0]);
                            exit();
                        }else{
                            include_once("page/user/social_empty.php");
                            exit();
                        }
                    }
                }else{
                    $pageSettings = $pageManager->getSettings("showcase");
                    //check if user belong to user
                    if($main->IGAccountBelongToUser($pathquery[2], $current_user->ID)){
                        if(sizeof($_POST)){
                            include_once("page/user/ajax.php");
                            exit();
                        }else{
                            $social_account = $main->getSingleIger($pathquery[2]);
                            include_once("page/user/social.php");
                            exit();
                        }
                    }else{
                        header("Location: /user@".$user_ID."/showcase/");
                        exit();
                    }
                }
            }
            if($pathquery[1] == "profile"){
                $pageSettings = $pageManager->getSettings("profile");
                if(isset($_POST) && sizeof($_POST)){
                    //registration
                    include_once("page/user/func-update.php");

                    if($update_success){
                        if($_SESSION["role_view"] == "brand"){
                            header("Refresh:0");
                            exit();
                        }else{
                            //check if social connected, if yes, stay at current page, else go to social page
                            if($main->getUserIGAccounts($current_user->ID)){
                                header("Refresh:0");
                                exit();
                            }else{
                                header("Location: /user@".$user_ID."/showcase/");
                                exit();
                            }
                        }
                    }else{
                        include_once("page/error/index.php");
                        exit();
                    }
                }else{
                    include_once("page/user/myprofile.php");
                    exit();
                }
            }
            if($pathquery[1] == "business_welcome"){
                $pageSettings = $pageManager->getSettings("business_welcome");
                include_once("page/business/welcome.php");
                exit();
            }
            if($pathquery[1] == "business_member"){
                if($default_group_id){
                    $pageSettings = $pageManager->getSettings("business_member");
                    if(isset($_POST) && sizeof($_POST)){
                        //business profile
                        include_once("page/business/member_ajax.php");
                        exit();
                    }else{
                        include_once("page/business/member.php");
                        exit();
                    }
                }else{
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
            }
            if($pathquery[1] == "business_group"){
                if($default_group_id){
                    $pageSettings = $pageManager->getSettings("business_group");
                    if(isset($_POST) && sizeof($_POST)){
                        include_once("page/business/default_group_post.php");
                        include_once("page/business/default_group.php");
                        exit();
                    }else{
                         $main->changeDefaultRole("brand",$current_user->ID);
                        include_once("page/business/default_group.php");
                        exit();
                    }
                }else{
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
            }
            if($pathquery[1] == "business_invite"){
                $pageSettings = $pageManager->getSettings("business_invite");
                if(isset($_POST) && sizeof($_POST)){
                    include_once("page/business/member_invite_post.php");
                    include_once("page/business/member_invite.php");
                    exit();
                }else{
                    $main->changeDefaultRole("brand",$current_user->ID);
                    include_once("page/business/member_invite.php");
                    exit();
                }
            }
            if($pathquery[1] == "business_payment"){
                if($default_group_id){
                    if($_SESSION["role_view"] == "brand"){
                        if(isset($_POST)){
                            include_once("page/payment/payment_post.php");
                            include_once("page/payment/payment.php");
                            exit();
                        }else{
                            include_once("page/payment/payment.php");
                            exit();
                        }
                    }else{
                        include_once("page/payment/payment_creator.php");
                        exit();
                    }
                }else{
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
            }
            if($pathquery[1] == "business_add"){
                if($main->isBrandVerified($current_user->ID)){
                    $pageSettings = $pageManager->getSettings("business_add");
                    if(isset($_POST) && sizeof($_POST)){
                        //business profile
                        if(isset($_POST["method"])){
                            header("Location: /user@".$user_ID."/business_member");
                            exit();
                        }else{
                            include_once("page/business/profile_post.php");
                            if($update_success){
                                header("Location: /user@".$user_ID."/business_group");
                                exit();
                            }else{
                                include_once("page/error/index.php");
                                exit();
                            }
                        }
                    }else{
                        include_once("page/business/add.php");
                        exit();
                    }
                }else{
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
            }
            if($pathquery[1] == "business_profile"){
                if($default_group_id){
                    $pageSettings = $pageManager->getSettings("business_profile");
                    if(isset($_POST) && sizeof($_POST)){
                        //business profile
                        if(isset($_POST["method"])){
                            include_once("page/business/ajax.php");
                            exit();
                        }else{
                            include_once("page/business/profile_post.php");
                            if($update_success){
                                header("Refresh:0");
                                exit();
                            }else{
                                include_once("page/error/index.php");
                                exit();
                            }
                        }
                    }else{
                        include_once("page/business/profile.php");
                        exit();
                    }
                }else{
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
            }
            if($pathquery[1] == "business_create"){
                if(!$default_group_id){
                    header("Location: /user@".$user_ID."/business_welcome");
                    exit();
                }
                $pageSettings = $pageManager->getSettings("business_create");
                if(isset($_POST) && sizeof($_POST)){
                    if(isset($_POST["method"])){
                        include_once("page/business/ajax.php");
                        exit();
                    }else{
                        include_once("page/business/profile_post.php");
                        if($update_success){
                            header("Refresh:0");
                            exit();
                        }else{
                            include_once("page/error/index.php");
                            exit();
                        }
                    }
                }else{
                    include_once("page/business/add.php");
                    exit();
                }
            }
        }
    }

    header("Location: /");
    exit();
}
?>