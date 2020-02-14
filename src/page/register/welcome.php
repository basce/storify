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
<body>
    <div class="page sub-page">
        <header class="hero">
            <div class="hero-wrapper">
<?php 
$header_without_toggle_button = true;
include("page/component/header.php"); ?>
                <div class="page-title">
                    <div class="container">
                        <h1>Welcome. Your sign up is successful.</h1>
                        <h2>Which side are you on?</h2>
                    </div>
                    <!--end container-->
                </div>
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="box-container">
                                <div class="box box-3d card1">
                                    <div class="front">
                                        <div class="card-inner">
                                            <h2>I'm a</h2>
                                            <h1>Brand Owner</h1>
                                        </div>
                                    </div>
                                    <div class="rear">
                                        <div class="card-inner">
                                            <p>Brand owners are on the lookout for creators who produce beautiful stories and experiences. Will that be you?</p>
                                            <ul>
                                                <li>Add your favourite creators to Creator Boards</li>
                                                <li>Add your favourite stories to Story Boards</li>
                                                <li>Activate creators to make stories for you</li>
                                            </ul>
                                            <div class="d-flex justify-content-between align-items-baseline">
                                                <p></p>
                                                <a href="/user@<?=$current_user->ID?>/welcomebrand" class="btn btn-primary text-caps btn-rounded btn-framed">Go</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-container">
                                <div class="box box-3d card2">
                                    <div class="front">
                                        <div class="card-inner">
                                            <h2>I'm a</h2>
                                            <h1>Content Creator</h1>
                                        </div>
                                    </div>
                                    <div class="rear">
                                        <div class="card-inner">
                                            <p>Content creators share beautiful stories of the bands they love online. Will that be you?</p>
                                            <ul>
                                                <li>Link the stories you create on social media to your profile</li>
                                                <li>Add your favourite creators and stories to your own Boards</li>
                                                <li>Collaborate with the brands you love</li>
                                            </ul>
                                            <div class="d-flex justify-content-between align-items-baseline">
                                                <p></p>
                                                <a href="/user@<?=$current_user->ID?>/showcase" class="btn btn-primary text-caps btn-rounded btn-framed">Go</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
                <!--end container-->
            </section>
        </section>
        <script type="text/javascript">
            $(function(){
               
            });
        </script>
        <?php include("page/component/footer.php"); ?>
    </div>
</body>
</html>
