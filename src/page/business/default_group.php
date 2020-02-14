<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0">
    <meta name="robots" content="noindex, nofollow">
<?php include("page/component/meta.php"); ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/user.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script>
        window._startTime = new Date().getTime();

        window.getExecuteTime = function(str){
            var currentTime = new Date().getTime();
            console.log(str);
            console.log(currentTime - window._startTime);
        }
    </script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script> -->
    <!--<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>-->
    <script src="/assets/js/selectize.min.js"></script>
    <script src="/assets/js/masonry.pkgd.min.js"></script>
    <script src="/assets/js/icheck.min.js"></script>
    <script src="/assets/js/jquery.validate.min.js"></script>
    <script src="/assets/js/scrollreveal.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/nc_custom.js"></script>
    <script src="/assets/js/storify.template.js"></script>
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .box{
            margin-bottom: 3rem;
        }
        .box .profile{
            width:150px;
            height:150px;
            position:absolute;
            top:3rem;
            left:3rem;
            border-radius:50%;
        }
        .box .body{
            height:150px;
            padding-left:170px;
        }
        .box .body h2{
            margin-bottom:0;
        }
        .box .body button{
            position:absolute;
            right:3rem;
            bottom:3rem;
        }
    </style>
</head>
<body>
    <div class="page sub-page">
        <header class="hero">
            <div class="hero-wrapper">
<?php 
$header_without_toggle_button = true;
include("page/component/header.php"); ?>
                <!--============ Page Title =========================================================================-->
                <div class="page-title">
                    <div class="container">
                        <h1>Business group</h1>
                        <h2>[[description]]</h2>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Page Title =====================================================================-->
                <div class="background"></div>
                <!--end background-->
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <?php include("page/user/leftnav.php"); ?>
                        </div>
                        <div class="col-md-9">
                            <h2>Select active business group</h2>
                            <div class="list">
                                <?php
                                /*
                                Array ( [0] => Array ( [name] => Cheewei Group [default_brand] => 240 [default_country] => SN [about] => Nice Business [profile_image] => profiles/15670530822000x1050.png [brand_name] => Xiaomi ) ) 
                                */
                                $requests = \storify\business_group::getBusinessGroup($current_user->ID);

                                if(sizeof($requests)){
                                    foreach($requests as $key=>$value){
                                        //get ongoing project
                                        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project` WHERE group_id = %d AND hide = 0";
                                        $number_of_active_project = $wpdb->get_var($wpdb->prepare($query, $value["id"]));
                                        ?>
                                <div class="box">
                                    <img src="<?=$main->getCDNURL(get_home_url()."/".$value["profile_image"])?>" class="profile">
                                    <div class="body">
                                        <h2><?=$value["name"]?></h2>
                                        <?=$value["brand_name"]?"<h3>".$value["brand_name"]."</h3>":""?>
                                        <p>Number of active projects : <?=$number_of_active_project?></p>
                                        <?php 
                                        if($value["default_group"] == 0){
                                        ?>
                                        <form method="post">
                                            <input type="hidden" name="group_id" value="<?=$value["id"]?>" >
                                            <button href="#" type="submit" class="btn btn-primary small">Select</button>
                                        </form>
                                        <?php
                                        }else{
                                        ?>
                                        <button href="#" type="submit" class="btn btn-primary small disable" disabled>Selected</button>
                                        <?php    
                                        }
                                        ?>
                                    </div>
                                </div>
                                        <?php
                                    }
                                }else{
                                    ?>
                                    <p>No Group</p>
                                    <?php
                                }
                                    if($main->isBrandVerified($current_user->ID)){
                                ?>
                                <a href="/user@<?=$current_user->ID?>/business_add" class="btn btn-primary">Add New Group</a>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->
    <script type="text/javascript">
        $(function(){

        });
    </script>
</body>
</html>