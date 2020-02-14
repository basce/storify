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
    <script>
        window._startTime = new Date().getTime();

        window.getExecuteTime = function(str){
            var currentTime = new Date().getTime();
            console.log(str);
            console.log( currentTime - window._startTime);
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
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .selectize-control.customselect{
            padding:0;
        }
        .selectize-control.customselect .selectize-input.items{
            box-shadow: none;
            border: none;
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
                        <h1><?=htmlspecialchars($current_user->display_name)?>'s showcase</h1>
                        <h2>The best portfolios show pride and tell a story.</h2>
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
                            <div class="clearfix">
                                <p>We'd love to show the world what you are up to on social media. Link up with your Instagram account now.</p>
                                <?php if(isset($_GET["error"])){
                                    ?>
                                    <div class="alert alert-danger">
                                      <?=$_GET["error"]?>
                                    </div>
                                    <?php
                                }?>
                            </div>
                            <div class="text-right">
                                <a href="https://api.instagram.com/oauth/authorize/?client_id=cb5c39433c444e3fb8161f72e632ea19&redirect_uri=<?=urlencode(get_home_url())?>%2Figlogin%2F&response_type=code" class="btn btn-primary">Connect</a>
                            </div>
                            <hr>
                            <?php
                                $step1_hide = 'style="display:none"';
                                $step2_hide = 'style="display:none"';
                                $igusername = "";
                                $verify_code = "";
                                $get_verify_result = $main->getIGVerifyCode($current_user->ID);
                                if(sizeof($get_verify_result)){
                                    $igusername = $get_verify_result["igusername"];
                                    $verify_code = $get_verify_result["code"];
                                    $step2_hide = "";
                                }else{
                                    $step1_hide = "";
                                }
                            ?>
                            <div class="clearfix form-group" id="step1" <?=$step1_hide?>>
                                <p>Alternatively, you may verify by this alternative method. Enter you IG name ( etc, https://instagram.com/[your IG name]/)
                                    <input class="form-control" type="text" value="" id="igusername">
                                </p>
                                <button type="btn btn-primary" id="get_verification">Get Verification Code</button>
                            </div>
                            <div class="clearfix form-group" id="step2" <?=$step2_hide?>>
                                <p>Please create a public post that contains the following text in your caption body and post it on htttps://instagram/<span id="user_igusername"><?=$igusername?></span>. And click on the button below to verify.
                                    <input class="form-control" type="text" value="<?=$verify_code?>" id="verification_code">
                                </p>
                                <div class="text-right">
                                    <a href="#" class="btn btn-primary" id="change_igaccount">change another IG Account</a>
                                    <a href="#" class="btn btn-primary" id="verify_button">verify post</a>
                                </div>
                            </div>
                            <div class="alert" id="verify_result">
                            </div>
                        <hr>
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

        function getUniqueCode(){
            if(!$("#igusername").val()) return;
            $.ajax({
                type:"POST",
                dataType:'json',
                data:{
                    method:"setVerifyCode",
                    igusername:$("#igusername").val()
                },
                error:function(){

                },
                success:function(res){
                    console.log(res);
                    if(res.code){
                        $("#verify_code").val(res.code);
                        $("#user_igusername").text(res.igusername);
                        $("#step1").css({display:"none"});
                        $("#step2").removeAttr("style");
                    }else{
                        console.log(res.msg);
                    }
                }
            });
        }

        var _verifying = false;
        function verifyPost(){
            $("#verify_button").html('<i class="fa fa-refresh fa-spin"></i> Checking').addClass("disabled");
            if(_verifying) return;
            _verifying = true;
            $.ajax({
                type:"POST",
                dataType:"json",
                data:{
                    method:"verifyCode"
                },
                error:function(){

                },
                success:function(res){
                    $("#verify_button").text("verify code");
                    if(res.error){
                        console.log(res.msg);
                    }else{
                        if(res.verified){
                            console.log(res);
                            $("#verify_result").removeClass().addClass("alert alert-success").text("IG verified");
                        }else{
                            $("#verify_result").removeClass().addClass("alert alert-danger").text("Code not found. Please check your post whether is public and contain the code.")
                        }
                    }
                }
            });
        }

        function changeIG(){
            $.ajax({
                type:"POST",
                dataType:"json",
                data:{
                    method:"removeVerifyCode"
                },
                error:function(){

                },
                success:function(res){
                    $("#step1").removeAttr("style");
                    $("#step2").css({display:"none"});
                }
            });
        }
        $(function(){
            $("#get_verification").click(function(e){
                e.preventDefault();
                getUniqueCode();
            });

            $("#verify_button").click(function(e){
                e.preventDefault();
                verifyPost();
            });

            $("#change_igaccount").click(function(e){
                e.preventDefault();
                changeIG();
            });
        });
    </script>
</body>
</html>