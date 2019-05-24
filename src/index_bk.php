<?php
$debugTime = microtime(true);
function getDebugTime(){
    global $debugTime;
    return microtime(true) - $debugTime;
}

include("inc/class.main.php");
$main = new main();
$query = $main->getQueryPath();


$current_user = wp_get_current_user();
if($current_user->ID){
    $current_user_meta = get_user_meta($current_user->ID);
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

$pagefound = false;
switch(sizeof($query)){
    case 0:
        //main page
        $pagefound = true;

        $category_tags = $main->getAllTags();
        $country_tags = $main->getAllCountries();

        include_once("page/main/index.php");
    break;
    case 1:
        if($query[0] == ""){

            $category_tags = $main->getAllTags();
            $country_tags = $main->getAllCountries();

            $pagefound = true;
            include_once("page/main/index.php");
            break;
        }
        if($query[0] == "listing"){

            $category_tags = $main->getAllTags();
            $country_tags = $main->getAllCountries();

            $sortBy = isset($_GET["order"])?$_GET["order"]:"";
                switch($sortBy){
                    case "latest":
                    case "likes":
                    break;
                    default:
                        $sortBy = "";
                    break;
                }

            $page = isset($_GET["page"]) ? $_GET["page"] : 1;
            $pagesize = 12;
            $igers = $main->getIger(isset($_GET["category"])?$_GET["category"]:null, isset($_GET["country"])?$_GET["country"]:null, isset($_GET["language"])?$_GET["language"]:null, $pagesize, $page, $sortBy, false);

            $cause_text = $main->convertCauseToText($igers["cause"]);
            $category_text = "";
            $country_text = "";
            if(sizeof($cause_text["category"]) > 0){
                $category_text = implode(", ", $cause_text["category"])." ";
            }
            if(sizeof($cause_text["country"]) > 0){
                $country_text = implode(", ", $cause_text["country"]);
            }
            if($igers["total"] == 1){
                $h2_text = "You've uncovered 1 ".$category_text."creator";
            }else{
                $h2_text = "You've uncovered ".$igers["total"]." ".$category_text."creators";
            }

            if($country_text != ""){
                $h2_text .= " from ".$country_text.".";
            }else{
                $h2_text .= ".";
            }

            if($igers["total"] == 0){
                $h2_text = "We did not find anyone who fulfils your criteria. Enter other passions and cities, perhaps?";
            }
                
            $pagefound = true;
            include_once("page/main/list.php");
            break;
        }
        if($query[0] == "register"){
            if(isset($_POST) && sizeof($_POST)){
                //registration
                include_once("page/register/func-insert.php");

                if($insert_success){
                    header("Location: /dashboard");
                    exit();
                    break;
                }else{
                    $pagefound = true;
                    include_once("page/error/index.php");
                    break;    
                }
            }else{
                $pagefound = true;
                include_once("page/register/register.php");
                break;
            }
        }
        if($query[0] == "signout"){
            wp_logout();
            header("Location: /");
            exit();
        }
        if($query[0] == "login"){
            if(isset($_POST) && sizeof($_POST)){

                include_once("page/login/func-login.php");
                if($login_success){
                    header("Location: /user@".$user_ID."/dashboard");
                    exit();
                    break;
                }else{
                    $pagefound = true;
                    include_once("page/login/index.php");
                    break;       
                }
            }else{
                $pagefound = true;
                include_once("page/login/index.php");
                break;   
            }
        }
        if($query[0] == "iglogin"){
            $pagefound = true;
            include_once("iglogin.php");
            break;
        }
        if($query[0] == "fblogin"){
            $pagefound = true;
            include_once("fblogin.php");
            break;
        }
        //check if igusername
        $result = $main->getSingleIger($query[0]);
        if($result){
            include_once("iger.php");
            break;
        }

        //check if with user id
        $userID = $main->parseUserTerm($query[0]);
        if($userID){
            //user exist
            header("Location: /user@".$userID."/dashboard");
            exit();
            break;
        }
    break;
    case 2:
        if($query[0] == "register"){
            if($query[1] == "ajax"){
                $pagefound = true;
                include_once("page/register/ajax.php");
                break;
            }
        }
        if($query[0] == "user"){
            if($query[1] == "ajax"){
                $pagefound = true;
                include_once("page/user/ajax.php");
                break;
            }
        }
        $userID = $main->parseUserTerm($query[0]);
        if($userID){
            if($query[1] == "dashboard"){
                //need to check if user login
                $pagefound = true;
                include_once("page/user/dashboard.php");
                break;
            }
            if($query[1] == "social"){
                $socialIDs = $main->getUserIGAccounts($current_user->ID);
                if(sizeof($_POST)){
                    include_once("page/user/ajax.php");
                    break;
                }else{
                    if(sizeof($socialIDs)){
                        $social_account = $main->getSingleIger($socialIDs[0]);
                        include_once("page/user/social.php");
                        break;
                    }else{
                        include_once("page/user/social_empty.php");
                        break;
                    }
                }
            }
            if($query[1] == "myprofile"){
                if(isset($_POST) && sizeof($_POST)){
                    //registration
                    include_once("page/user/func-update.php");

                    if($update_success){
                        header("Refresh:0");
                        exit();
                        break;
                    }else{
                        $pagefound = true;
                        include_once("page/error/index.php");
                        break;    
                    }
                }else{
                    $pagefound = true;
                    include_once("page/user/myprofile.php");
                    break;
                }
            }
        }
    break;
}
?>