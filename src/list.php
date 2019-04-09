<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php include("inc/meta.php"); ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/user.css">
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script>
    <script src="assets/js/selectize.min.js"></script>
    <script src="assets/js/masonry.pkgd.min.js"></script>
    <script src="assets/js/icheck.min.js"></script>
    <script src="assets/js/jquery.validate.min.js"></script>
    <script src="assets/js/scrollreveal.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/nc_custom.js"></script>
    <script>
        window._query = {
            category:<?=isset($_GET["category"])?json_encode($_GET["category"]):"[]"?>,
            country:<?=isset($_GET["country"])?json_encode($_GET["country"]):"[]"?>,
            language:<?=isset($_GET["language"])?json_encode($_GET["language"]):"[]"?>
        };
    </script>
<?=get_option("custom_settings_header_js")?>
</head>
<body>
    <div class="page sub-page">
        <!--*********************************************************************************************************-->
        <!--************ HERO ***************************************************************************************-->
        <!--*********************************************************************************************************-->
        <header class="hero">
            <div class="hero-wrapper">
<?php
include("page/component/header.php"); ?>
                <!--============ Hero Form ==========================================================================-->
                <div class="collapse" id="collapseMainSearchForm">
                    <form class="hero-form form" method="GET" action="listing">
                    <div class="container">
                        <!--Main Form-->
                        <div class="main-search-form">
                            <div class="form-row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="category" class="col-form-label">Creators Of</label>
                                        <select name="category[]" id="category" data-placeholder="Select Passion" data-enable-search=true multiple>
                                            <option value="">Select Passion</option>
                                            <?php
                                                foreach($category_tags as $key=>$value){
                                            ?>
                                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                        <?php } ?>  
                                        </select>
                                    </div>
                                    <!--end form-group-->
                                </div>
                                <!--end col-md-3-->
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="country" class="col-form-label">From</label>
                                        <select name="country[]" id="country" data-placeholder="Select City" data-enable-search=true multiple>
                                            <option value="">Select City</option>
                                            <?php
                                                foreach($country_tags as $key=>$value){
                                            ?>
                                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                        <?php } ?>  
                                        </select>
                                    </div>
                                    <!--end form-group-->
                                </div>
                                <!--end col-md-3-->
                                <?php /*
                                <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <label for="language" class="col-form-label">Language?</label>
                                        <select name="language[]" id="language" data-placeholder="Select Language" data-enable-search=true multiple>
                                            <option value="">Select Language</option>
                                            <?php
                                                $language_tags = $main->getAllLanguages();
                                                foreach($language_tags as $key=>$value){
                                            ?>
                                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                        <?php } ?> 
                                        </select>
                                    </div>
                                    <!--end form-group-->
                                </div>
                                <!--end col-md-3-->
                                */ ?>
                                <div class="col-md-4 col-sm-4">
                                    <button type="submit" class="btn btn-primary width-100">Search</button>
                                </div>
                                <!--end col-md-3-->
                            </div>
                            <!--end form-row-->
                        </div>
                        <!--end main-search-form-->
                        <!--Alternative Form-->
                        <?php /*
                        <div class="alternative-search-form">
                            <a href="#collapseAlternativeSearchForm" class="icon" data-toggle="collapse"  aria-expanded="false" aria-controls="collapseAlternativeSearchForm"><i class="fa fa-plus"></i>More Options</a>
                            <div class="collapse" id="collapseAlternativeSearchForm">
                                <div class="wrapper">
                                    <div class="form-row">
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 d-xs-grid d-flex align-items-center justify-content-between">
                                            <label>
                                                <input type="checkbox" name="new">
                                                New
                                            </label>
                                            <label>
                                                <input type="checkbox" name="used">
                                                Used
                                            </label>
                                            <label>
                                                <input type="checkbox" name="with_photo">
                                                With Photo
                                            </label>
                                            <label>
                                                <input type="checkbox" name="featured">
                                                Featured
                                            </label>
                                        </div>
                                        <!--end col-xl-6-->
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-row">
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <input name="min_price" type="text" class="form-control small" id="min-price" placeholder="Minimal Price">
                                                        <span class="input-group-addon small">$</span>
                                                    </div>
                                                    <!--end form-group-->
                                                </div>
                                                <!--end col-md-4-->
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <input name="max_price" type="text" class="form-control small" id="max-price" placeholder="Maximal Price">
                                                        <span class="input-group-addon small">$</span>
                                                    </div>
                                                    <!--end form-group-->
                                                </div>
                                                <!--end col-md-4-->
                                                <div class="col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <select name="distance" id="distance" class="small" data-placeholder="Distance" >
                                                            <option value="">Distance</option>
                                                            <option value="1">1km</option>
                                                            <option value="2">5km</option>
                                                            <option value="3">10km</option>
                                                            <option value="4">50km</option>
                                                            <option value="5">100km</option>
                                                        </select>
                                                    </div>
                                                    <!--end form-group-->
                                                </div>
                                                <!--end col-md-3-->
                                            </div>
                                            <!--end form-row-->
                                        </div>
                                        <!--end col-xl-6-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end wrapper-->
                            </div>
                            <!--end collapse-->
                        </div>
                        */ ?>
                        <!--end alternative-search-form-->
                    </div>
                    <!--end container-->
                </form>
                </div>
                <!--end collapse-->
                <!--============ End Hero Form ======================================================================-->
                <!--============ Page Title =========================================================================-->
<?php
    //get all instagrammer that match the giving
?>                
                <div class="page-title">
                    <div class="container">
                        <h1 class="">
<?php
    if($igers["total"] == 0){
        echo "There's nobody at home!";
    }else{
        echo "Found!";
    }
?>
                        </h1>
<?php
?>
                        <h2><?=$h2_text?></h2>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Page Title =====================================================================-->
                <div class="background"></div>
                <!--end background-->
            </div>
            <!--end hero-wrapper-->
        </header>
        <!--end hero-->

        <!--*********************************************************************************************************-->
        <!--************ CONTENT ************************************************************************************-->
        <!--*********************************************************************************************************-->
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="section-title clearfix" id="tellstory">
                        <div class="float-xl-left float-md-left float-sm-none story_tell_cont" style="display:none">
                            
                        </div>
                        <div class="float-xl-right float-md-right float-sm-none">
                            <!--
                            <select name="categories" id="categories" class="small width-200px" data-placeholder="Category" >
                                <option value="">Category</option>
                                <option value="1">Computers</option>
                                <option value="2">Real Estate</option>
                                <option value="3">Cars & Motorcycles</option>
                                <option value="4">Furniture</option>
                                <option value="5">Pets & Animals</option>
                            </select>
                        -->
                            <select name="order" id="order" class="small width-200px" data-placeholder="Default Sorting" >
                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest First</option>
                                <option value="likes" <?php if($sortBy == "likes"){ echo "selected";}?>>Most Popular First</option>
                            </select>
                        </div>
                    </div>
                    <!--============ Items ==========================================================================-->
                    <div class="items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-sort="<?=$sortBy?>" data-category="<?=isset($_GET["category"])?json_encode($_GET["category"], JSON_NUMERIC_CHECK):"[]"?>" data-country="<?=isset($_GET["country"])?json_encode($_GET["country"], JSON_NUMERIC_CHECK):"[]"?>" data-language="<?=isset($_GET["language"])?json_encode($_GET["language"],JSON_NUMERIC_CHECK):"[]"?>" data-nc_data="iger">
                    </div>
                    <div class="center">
                        <a href="#" class="btn btn-primary btn-framed btn-rounded" id="loadmore">Load More</a>
                    </div>
                    <!--end read-more-->
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
        <!--end content-->
        <script>
            $(document).ready(function($){
                "user strict";

                if($("#loadmore").length){
                    $("#loadmore").click();
                }
            });
        </script>
<?php include("inc/footer.php"); ?>
    </div>
    <!--end page-->
</body>
</html>
