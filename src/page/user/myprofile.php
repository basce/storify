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
                        <h1>Profile</h1>
                        <h2>Keep your contact details updated to stay in touch and receive the latest updates.</h2>
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
                            <form class="form storify-form custommsg" novalidate="novalidate" id="myprofile_form" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h2>Personal Information</h2>
                                        <section>
                                            <div class="form-group">
                                                <div class="input-group-row">
                                                    <label for="name" class="col-form-label required">Name</label>
                                                    <input name="name" type="text" class="form-control" id="name" placeholder="Your name" value="<?=$current_user->display_name?>">
                                                </div>
                                                <div class="alert alert-danger hide">Please enter Creator's country</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?php
                                                        $temp_gender = ($current_user_meta && $current_user_meta["gender"] && sizeof($current_user_meta["gender"]))?$current_user_meta["gender"][0] : "";
                                                        ?>
                                                        <label class="col-form-label required">Gender</label>
                                                        <figure>
                                                            <label>
                                                                <input type="radio" name="gender" value="male" <?php echo ($temp_gender=="male")?"checked":"";?>>
                                                                Male
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="gender" value="female" <?php echo ($temp_gender=="female")?"checked":"";?>>
                                                                Female
                                                            </label>
                                                        </figure>
                                                        <div class="alert alert-danger hide">Please enter Creator's country</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="citycountry" class="col-form-label required">Country / City</label>
                                                        <select name="citycountry" type="text" class="customselect singleselect form-control" id="citycountry" data-placeholder="Select country..." single>
        <?php
            foreach($user_country_ar as $key=>$val){
                $temp_val = ($current_user_meta && $current_user_meta["city_country"] && sizeof($current_user_meta["city_country"]))?$current_user_meta["city_country"][0]:"";
                if($key == $temp_val){
                    ?><option value="<?=$key?>" selected><?=$val?></option><?php
                }else{
                    ?><option value="<?=$key?>"><?=$val?></option><?php
                }
            }
        ?>
                                                        </select>
                                                        <div class="alert alert-danger hide">Please enter Creator's country</div>
                                                    </div>
                                                    <!--end form-group-->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                    $temp_default_view = get_user_meta($current_user->ID, "default_role", true);
                                                ?>
                                                <label for="default_view" class="col-form-label required">Default View</label>
                                                <figure>
                                                    <label>
                                                        <input type="radio" name="default_view" value="brand" <?php echo ($temp_default_view=="brand")?"checked":"";?>>
                                                        Brand
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="default_view" value="creator" <?php echo ($temp_default_view=="creator")?"checked":"";?>>
                                                        Creator
                                                    </label>
                                                </figure>
                                                <div class="alert alert-danger hide"></div>
                                            </div>
                                            <!--end form-group-->
                                        </section>
                                        <hr />
                                        <section>
                                            <h2>Contact</h2>
                                            <div class="form-group">
                                                <div class="input-group-row">
                                                    <label for="phone" class="col-form-label">Phone number</label>
                                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Your phone number" value="<?=($current_user_meta && $current_user_meta["phone"] && sizeof($current_user_meta["phone"]))?$current_user_meta["phone"][0]:""?>">
                                                </div>
                                                <div class="alert alert-danger hide"></div>
                                            </div>
                                            <!--end form-group-->
                                            <div class="form-group">
                                                <div class="input-group-row">
                                                    <label for="email" class="col-form-label">Email</label>
                                                    <input name="email" type="email" class="form-control" id="email" placeholder="Your email address" value="<?=($current_user && $current_user->user_email )?$current_user->user_email:""?>" disabled>
                                                </div>
                                                <div class="alert alert-danger hide"></div>
                                            </div>
                                            <!--end form-group-->
                                        </section>

                                           <section class="clearfix">
                                            <button type="submit" class="btn btn-primary float-right">Save</button>
                                        </section>
                                    </div>
                                    <!--end col-md-8-->
                                    <div class="col-md-4">
                                        
                                    </div>
                                    <!--end col-md-3-->
                                </div>
                            </form>
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
            $("#citycountry").selectize({
                create: false,
                sortField: 'text'
            });
            
            function isEmail(b){var a=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;if(a.test(b)){return true}else{return false}}

            function verifyfields(){
                var er = 0;

                if($("#name").val().length < 1){
                    er++;
                    $("#name").parents(".form-group").find(".alert").text("We will like to know how to address you. Enter again?").removeClass("hide");
                }else{
                    $("#name").parents(".form-group").find(".alert").addClass("hide");
                }

                //check Gender
                if(!$("input[name='gender']:checked").length){
                    er++;
                    $("input[name='gender']:checked").parents(".form-group").find(".alert").text("Please select your gender").removeClass("hide");
                }else{
                    $("input[name='gender']:checked").parents(".form-group").find(".alert").addClass("hide");
                }

                //check country
                if($("#citycountry").val() == ""){
                    er++;
                    $("#citycountry").parents(".form-group").find(".alert").text("Please select your country").removeClass("hide");
                }else{
                    $("#citycountry").parents(".form-group").find(".alert").addClass("hide");
                }  

                return er;   
            }

            $("#myprofile_form").submit(function(e){
                //validation
                if(verifyfields()){
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>