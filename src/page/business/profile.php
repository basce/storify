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
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .selectize-control.customselect{
            padding:0;
        }
        .selectize-control.customselect .selectize-input.items{
            box-shadow: none;
            border: none;
        }
        .single-file-input input[type="file"] {
            box-shadow: none;
            border: none;
            color: transparent;
            background-color: transparent;
            padding: 4rem 0 0;
            font-size: inherit;
        }
        #business_profile_form .profile-image .image {
            background-image:url(/assets/img/partner.png);
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
                        <h1>Business Profile</h1>
                        <h2><?php $business_account = \storify\business_group::get($default_group_id);print_r($business_account); ?></h2>
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
                            <form class="form storify-form custommsg" novalidate="novalidate" id="business_profile_form" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h2>Business Information</h2>
                                        <section>
                                            <div class="form-group">
                                                <div class="input-group-row">
                                                    <label for="business_name" class="col-form-label required">Business Name</label>
                                                    <input name="business_name" type="text" class="form-control" id="business_name" placeholder="Business Name" value="<?=$business_account["name"]?>">
                                                </div>
                                                <div class="alert alert-danger hide">Please enter business name</div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group-row">
                                                    <label for="brand" class="col-form-label">Brand</label>
                                                    <select name="brand" type="text" class="customselect singleselect form-control" id="brand" data-enable-input=true nc-method="addBrand" data-placeholder="Select brand..." single>
                                                        <option value="">Select Brand</option>
                                                        <?php
                                                            $brands = $main->getAllBrands(true);
                                                            foreach($brands as $key=>$value){
                                                                if($value["term_id"] == $business_account["default_brand"]){
                                                                ?>
                                                                <option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option>
                                                                <?php
                                                                }else{
                                                                ?>
                                                                <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                                                <?php
                                                                }                     
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="citycountry" class="col-form-label">Country / City</label>
                                                    <select name="citycountry" type="text" class="customselect singleselect form-control" id="citycountry" data-placeholder="Select country..." single>
                    <?php
                        foreach($main->getCountriesList() as $key=>$val){
                            if($key == $business_account["default_country"]){
                                ?><option value="<?=$key?>" selected><?=$val?></option><?php
                            }else{
                                ?><option value="<?=$key?>"><?=$val?></option><?php
                            }
                        }
                    ?>
                                                    </select>
                                                    <div class="alert alert-danger hide">Please enter Creator's country</div>
                                            </div>
                                            <div class="form-group">
                                                <label for="about" class="col-form-label">More About Business</label>
                                                <textarea name="about" id="about" class="form-control" rows="4"><?=$business_account["about"]?></textarea>
                                            </div>
                                            <!--end form-group-->
                                        </section>
                                        <section class="clearfix">
                                            <input type="hidden" name="group_id" value="<?=$default_group_id?>">
                                            <button type="submit" class="btn btn-primary float-right">Save Changes</button>
                                        </section>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="profile-image">
                                            <div class="image background-image" id="business_image_preview" style="background-image:url(<?=$main->getCDNURL(get_home_url()."/".$business_account["profile_image"])?>)">
                                                <img src="<?=$main->getCDNURL(get_home_url()."/".$business_account["profile_image"])?>">
                                            </div>
                                            <div class="single-file-input">
                                                <input type="file" id="business_image" name="business_image" accept="image/*">
                                                <div class="btn btn-framed btn-primary small">Upload a picture</div>
                                            </div>
                                        </div>
                                    </div>
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
            
            function verifyfields(){
                var er = 0;

                if($("#business_name").val().length < 1){
                    er++;
                    $("#business_name").parents(".form-group").find(".alert").text("[[empty input error]]").removeClass("hide");
                }else{
                    $("#business_name").parents(".form-group").find(".alert").addClass("hide");
                }

                return er;   
            }

            function readURL(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e){
                        $("#business_image_preview").css({"background-image":e.target.result});
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#business_image").change(function(){
                readURL(this);
            });

            $("#business_profile_form").submit(function(e){
                //validation
                if(verifyfields()){
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>