<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                <div class="page-title">
                    <div class="container">
                        <?php if($update_success){ ?>
                            <h1>Password updated</h1>
                            <h2>Your password has been updated. Please sign in now.</h2>
                        <?php }else if($key_invalid){ ?>
                            <h1>Error</h1>
                            <h2>We did not manage to identify your account. Please try again.</h2>
                        <?php }else{ ?>
                            <h1>Enter your new password</h1>
                            <h2>Your new password should contain 6 characters.</h2>
                        <?php } ?>
                    </div>
                    <!--end container-->
                </div>
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row justify-content-center">
                        <?php if($update_success){ ?>
                        <div class="col-md-6">
                            <div class="text-center">
                                <a href="/signin" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <?php }else if($key_invalid){ ?>
                        <div class="col-md-6">
                            <div class="text-center">
                                <a href="/forgotpassword" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <div class="col-md-4">
                            <form class="form clearfix" novalidate="novalidate" method="POST" id="retrieve_form">
                                <div class="form-group">
                                    <label for="password" class="col-form-label required">New password</label>
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required="">
                                    <label for="password" class="forpassword error hide"></label>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-form-label required">Repeat Password</label>
                                    <input name="repeatpassword" type="password" class="form-control" id="repeatpassword" placeholder="Repeat Password" required="">
                                    <label for="password" class="forrepeatpassword error hide"></label>
                                </div>
                                <input type="hidden" name="login" value="<?=$_REQUEST["login"]?>" />
                                <input type="hidden" name="key" value="<?=$_REQUEST["key"]?>" />
                                <br>
                                <!--end form-group-->
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p>
                                        <!-- <a href="#" class="link">Forgot your password?</a> <br > -->
                                    </p> 
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                        <!--end col-md-6-->
                    </div>
                    <!--end row-->
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

            $("#password").on('input',function(e){
                if($("#password").val().length){
                    $(".forpassword").removeClass("badge-success badge-warning badge-danger");
                    var score = scorePassword($("#password").val());
                    if(score > 80){
                        $(".forpassword").addClass("badge-success").text("Password Strength : Strong");
                    }else if(score >50){
                        $(".forpassword").addClass("badge-warning").text("Password Strength : Medium");
                    }else{
                        $(".forpassword").addClass("badge-danger").text("Password Strength : Weak");
                    }
                    $(".forpassword").removeClass("hide");
                }else{
                    $(".forpassword").addClass("hide");
                }
            });

            function verifyfields(){
                var er = 0;
                
                //check password
                if($("#password").val().length < 6){
                    er++;
                    $(".forpassword.error").text("Please key in your password (min: 6 chars)").removeClass("hide").removeAttr("style");
                }else{
                    if($("#repeatpassword").val().length == 0){
                        er++;
                        $(".forrepeatpassword.error").text("Please enter your password again.").removeClass("hide").removeAttr("style");
                    }else if($("#password").val() !== $("#repeatpassword").val()){
                        er++;
                        $(".forrepeatpassword.error").text("Please confirm that your password is correct.").removeClass("hide").removeAttr("style");
                    }else{
                        $(".forrepeatpassword.error").addClass("hide");
                    }
                }

                return er;
            }

            $("#retrieve_form").submit(function(e){
                //validation
                if(verifyfields()){
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>