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
                        <h1>Update Password</h1>
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
                            <form class="form storify-form custommsg" novalidate="novalidate" id="password_form" method="post">
                                <div class="col-md-8">
                                    <section>
                                        <div class="form-group">
                                            <div class="input-group-row">
                                                <label for="cpassword" class="col-form-label required">Enter your current password</label>
                                                <input name="cpassword" type="password" class="form-control" id="cpassword" placeholder="Current password" value="">
                                            </div>
                                            <div class="alert alert-danger hide"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="input-group-row">
                                                <label for="npassword" class="col-form-label required">Enter your new password</label>
                                                <input name="npassword" type="password" class="form-control" id="npassword" placeholder="New password" value="">
                                            </div>
                                            <div class="alert alert-danger hide"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group-row">
                                                <label for="rpassword" class="col-form-label required">Repeat your new password</label>
                                                <input name="rpassword" type="password" class="form-control" id="rpassword" placeholder="Repeat password" value="">
                                            </div>
                                            <div class="alert alert-danger hide">Passwords don't match</div>
                                        </div>
                                    </section>
                                    <section class="clearfix">
                                        <button type="submit" class="btn btn-primary float-right">Change Password</button>
                                    </section>
                                    <?php
                                        if(isset($update_msg) && $update_msg){
                                            if($update_success){
                                                ?><div class="alert alert-success"><?=$update_msg?></div><?php
                                            }else{
                                                ?><div class="alert alert-danger"><?=$update_msg?></div><?php
                                            }
                                        }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!--end block-->
        </section>
        <script type="text/javascript">
            $(function(){
                function scorePassword(pass) {
                    var score = 0;
                    if (!pass)
                        return score;

                    // award every unique letter until 5 repetitions
                    var letters = new Object();
                    for (var i=0; i<pass.length; i++) {
                        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
                        score += 5.0 / letters[pass[i]];
                    }

                    // bonus points for mixing it up
                    var variations = {
                        digits: /\d/.test(pass),
                        lower: /[a-z]/.test(pass),
                        upper: /[A-Z]/.test(pass),
                        nonWords: /\W/.test(pass),
                    }

                    variationCount = 0;
                    for (var check in variations) {
                        variationCount += (variations[check] == true) ? 1 : 0;
                    }
                    score += (variationCount - 1) * 10;

                    return parseInt(score);
                }

                $("#npassword").on('input',function(e){
                    if($("#npassword").val().length){
                        $("#npassword").parents(".form-group").find(".alert").removeClass("alert-success alert-warning alert-danger");
                        var score = scorePassword($("#npassword").val());
                        if(score > 80){
                            $("#npassword").parents(".form-group").find(".alert").addClass("alert-success").text("Password Strength : Strong");
                        }else if(score >50){
                            $("#npassword").parents(".form-group").find(".alert").addClass("alert-warning").text("Password Strength : Medium");
                        }else{
                            $("#npassword").parents(".form-group").find(".alert").addClass("alert-danger").text("Password Strength : Weak");
                        }
                        $("#npassword").parents(".form-group").find(".alert").removeClass("hide");
                    }else{
                        $("#npassword").parents(".form-group").find(".alert").addClass("hide");
                    }
                });

                $("#rpassword").on('input',function(e){
                    if($("#npassword").val() && ($("#rpassword").val() != $("#npassword").val())){
                        $("#rpassword").parents(".form-group").find(".alert").removeClass("hide");
                    }else{
                        $("#rpassword").parents(".form-group").find(".alert").addClass("hide");
                    }
                });

                function verifyfields(){
                    var er = 0;
                    if($("#cpassword").val() == ""){
                        er++;
                        $("#cpassword").parents(".form-group").find(".alert").text("Require to provide your current password to update password.").removeClass("hide");
                    }else{
                        $("#cpassword").parents(".form-group").find(".alert").addClass("hide");
                    }

                    //check password
                    if($("#npassword").val().length < 6){
                        er++;
                        $("#npassword").parents(".form-group").find(".alert").text("Please key in your new password (min: 6 chars)").removeClass("hide");
                    }

                    if($("#npassword").val() != $("#rpassword").val()){
                        er++;
                        $("#rpassword").parents(".form-group").find(".alert").removeClass("hide");   
                    }

                    return er;
                }

                $("#password_form").submit(function(e){
                    if(verifyfields()){
                        e.preventDefault();
                    }
                });
            });
        </script>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->
</body>
</html>