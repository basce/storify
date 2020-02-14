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
                        <?php if(isset($retrieve_success) && $retrieve_success){ ?>
                            <h1>Reset notification sent</h1>
                            <h2>An email containing instructions to reset your password has been sent. Check your inbox.</h2>
                        <?php }else{ 
                                if(isset($retrieve_error_msg)){
                                    if($retrieve_error_msg == "Email is not registered."){ ?>
                            <h1>Invalid account</h1>
                            <h2>We did not manage to identify your account. Please enter the correct email address to your account.</h2>
                                    <?php }else{ ?>
                            <h1>System error</h1>
                            <h2>Please try again later</h2>
                            <div style="display:none"><?=$retrieve_error_msg?></div>
                                    <?php }
                                }else{ ?>
                            <h1>Reset your password</h1>
                            <h2>We can help you reset your password. Enter your email address and follow the instructions in our email to you.</h2>
                                <?php }
                            ?>
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
                        <?php if(isset($retrieve_success) && $retrieve_success ){ ?>
                        <div class="col-md-6">
                            <p></p>
                        </div>
                        <?php }else{ ?>
                        <div class="col-md-4">
                            <form class="form storify-form clearfix" novalidate="novalidate" method="POST" id="retrieveform">
                                <div class="form-group">
                                    <div class="input-group-row">
                                        <label for="email" class="col-form-label required">Enter your email address</label>
                                        <input name="email" type="email" class="form-control" id="email" placeholder="Email">
                                    </div>
                                    <div class="alert alert-danger hide">This field is required.</div>
                                </div>
                                <!--end form-group-->
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p>
                                        <!-- <a href="#" class="link">Forgot your password?</a> <br > -->
                                    </p> 
                                    <button type="submit" class="btn btn-primary">Reset</button>
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
            function isEmail(b){var a=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;if(a.test(b)){return true}else{return false}}
            
            $("#retrieveform").submit(function(e){
                var er = 0;
                if($("#email").val() == ""){
                    $("#email").parents(".form-group").find(".alert").removeClass("hide").text('This field is required.');
                    er++;
                }else if(!isEmail($("#email").val())){
                    $("#email").parents(".form-group").find(".alert").removeClass("hide").text("This does not appear to be a valid email address. Enter again?");
                    er++;
                }else{
                    $("#email").parents(".form-group").find(".alert").addClass("hide");
                }

                if(er){
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>