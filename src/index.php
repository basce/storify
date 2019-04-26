<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$debugTime = microtime(true);
function getDebugTime(){
    global $debugTime;
    return microtime(true) - $debugTime;
}

include_once("inc/main.php");
use storify\main as main;
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
$sortBy = isset($_GET["order"])?$_GET["order"]:"";
switch($sortBy){
    case "latest":
    case "likes":
    case "oldest":
    break;
    default:
        $sortBy = "";
    break;
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
                header("Location: /user@".$user_ID."/collections");
                exit();
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
        header("Location: /login");
        exit();
    }else if($userID){
        if(!($current_user->ID && $current_user->ID == $userID)){

            //check if a group page, if yes, redirect
            if(sizeof($pathquery) > 3 ){
                if($pathquery[1] == "collections" && ( $pathquery[2] == "people" || $pathquery[2] == "stories" ) && is_numeric($pathquery[3])){
                    header("Location: /collections/".$pathquery[2]."/".$userID."/".$pathquery[3]);
                    exit();
                }
            }

            $error_msg = "invalid authentication.";
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

                header("Location: /user@".$userID."/collections");
                exit();
            }
            if($pathquery[1] == "viewasbrand"){
                $main->changeDefaultRole("brand",$userID);

                header("Location: /user@".$userID."/collections");
                exit();
            }
            if($pathquery[1] == "welcomebrand"){
                $main->changeDefaultRole("brand",$userID);                

                header("Location: /user@".$userID."/collections");
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
                $role = $main->getProjectManager()->checkUserRoleInProject($current_user->ID, $pathquery[2]);
                if($role == "creator"){
                    //creator
                    //get project detail
                    if(isset($_POST) && sizeof($_POST)){

                    }else{
                        $project_detail = $main->getProjectManager()->getProjectDetail($pathquery[2], $current_user->ID);;

                        include_once("page/user/projects_creator_single_project.php");
                        exit();
                    }
                }else if($role == "admin"){
                    //brand
                    //get project detail
                    if(isset($_POST) && sizeof($_POST)){

                    }else{
                        $project_detail = $main->getProjectManager()->getProjectDetail($pathquery[2], $current_user->ID);;

                        include_once("page/user/projects_brand_single_project.php");
                        exit();
                    }
                }else{
                    //redirect user to project page
                    header("Location: /user@".$userID."/projects");
                    exit();
                }
            }
            if($pathquery[1] == "projects"){
                $pageSettings = $pageManager->getSettings("projects");
                if($_SESSION["role_view"] == "brand"){
                    //check if verified.
                    if($main->isBrandVerified($current_user->ID)){
                        include_once("page/user/projects_brand_not_approve.php");
                        exit();
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
                if($pathquery[2] == "stories" || $pathquery[2] == "people"){
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
                        //check if social connected, if yes, stay at current page, else go to social page
                        if($main->getUserIGAccounts($current_user->ID)){
                            header("Refresh:0");
                            exit();
                        }else{
                            header("Location: /user@".$user_ID."/showcase/");
                            exit();
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
        }
    }

    header("Location: /");
    exit();
}
?>