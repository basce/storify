<?php
    $iger = $result["iger"];
    $posts = $result["posts"];

    $pageSettings["og"]["og:type"] = "profile";
    $pageSettings["og"]["og:url"] = $pageSettings["meta"]["canonical"] = "https://storify.me/".$iger["igusername"];
    $pageSettings["og"]["profile:username"] = $iger["igusername"];
    $pageSettings["meta"]["name"] = $pageSettings["og"]["og:title"] = $iger["name"]." (@".$iger["igusername"].") - Storify photos and videos";
    $pageSEttings["meta"]["description"] = $pageSettings["og"]["og:description"] = ($iger["follows_by_count"] == 1 ? "1 Follower":number_format($iger["follows_by_count"])." Followers" ).", ".( $iger["media_count"] == 1 ? "1 Post":number_format($iger["media_count"])." Posts")." - See photos and videos from ".$iger["name"]." (@".$iger["igusername"].")";

    $additional_og_image = array();
    $additional_og_image[] = $iger["display_image"];

    //update breadcumb
    $pageSettings["breadcrumb"] = array(
        array(
            "label"=>"Home",
            "href"=>"/"
        ),
        array(
            "label"=>$iger["igusername"],
            "href"=>""
        )
    );
?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php include("page/component/meta.php"); ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/user.css">
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script> -->
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
        <!--*********************************************************************************************************-->
        <!--************ HERO ***************************************************************************************-->
        <!--*********************************************************************************************************-->
        <section class="hero">
            <div class="hero-wrapper">
<?php
include("page/component/header.php"); ?>
                 <!--============ Hero Form ==========================================================================-->
                <div class="collapse" id="collapseMainSearchForm">
                    <form class="hero-form form" method="GET" action="/listing">
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
                                                $category_tags = $main->getAllTags();
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
                                                $country_tags = $main->getAllCountries();
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
                <div class="page-title">
                    <div class="container">
                        <h1><?=$iger["igusername"]?></h1>
                        <h2>Last modified on <?=$iger["modified"]?></h2>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Page Title =====================================================================-->
                <div class="background"></div>
                <!--end background-->
            </div>
            <!--end hero-wrapper-->
        </section>
        <!--end hero-->

        <!--*********************************************************************************************************-->
        <!--************ CONTENT ************************************************************************************-->
        <!--*********************************************************************************************************-->
        <section class="content">
            <section class="block">
                <section>
                    <div class="container">
                        <div class="author big">
                            <div class="author-image">
                                <div class="background-image">
                                    <img src="<?=$iger["ig_profile_pic"]?>" alt="">
                                </div>
                            </div>
                            <!--end author-image-->
                            <div class="author-description">
                                <div class="section-title">
                                    <h2><?=$iger["name"]?></h2>
                                    <?php
                                        if(sizeof($iger["instagrammer_country"])){
                                    ?><h4 class="location"><?php 
                                            foreach($iger["instagrammer_country"] as $key=>$value){
                                                ?><a href="/listing?country%5B%5D=<?=$value["term_id"]?>"><?=$value["name"]?></a><?php
                                            }
                                    ?></h4><?php       
                                        }
                                    ?>
                                    <figure>
                                        <div class="meta float-left">
                                            <figure>Avg <i class="fa fa-heart"></i><?=number_format($iger["average_likes"])?></figure>
                                            <figure><i class="fa fa-users"></i><?=number_format($iger["follows_by_count"])?></figure>
                                            <figure><i class="fa fa-image"></i><?=number_format($iger["media_count"])?></figure>
                                        </div>
                                        <div class="text-align-right social">
                                            <a class="fa add_project <?=$iger["verified"]?'active':''?>" href="#" o="<?=$iger["id"]?>"></a>
                                            <a class="fa bookmark bookmarkpeople <?=$iger["bookmark"]?'active':''?>" href="#" o="<?=$iger["id"]?>" c="people"></a>
                                            <a href="https://instagram.com/<?=$iger["igusername"]?>" target="_blank">
                                                <i class="fa fa-instagram"></i>
                                            </a>
                                        </div>
                                    </figure>
                                </div>
                                <div class="additional-info">
                                    <?php       
                                        if($iger["instagrammer_tag"] && sizeof($iger["instagrammer_tag"])){
                                            foreach($iger["instagrammer_tag"] as $key=>$value){
                                                ?><a href="/listing?category%5B%5D=<?=$value["term_id"]?>" class="tag"><?=$value["name"]?></a><?php
                                            }
                                        }
                                        if($iger["instagrammer_country"] && sizeof($iger["instagrammer_country"])){
                                            foreach($iger["instagrammer_country"] as $key=>$value){
                                                ?><a href="/listing?country%5B%5D=<?=$value["term_id"]?>" class="tag"><?=$value["name"]?></a><?php
                                            }
                                        }
                                    ?>
                                    <!--
                                    <ul>
                                        <li>
                                            <figure>Category</figure>
                                        <?php                                            
                                            foreach($iger["instagrammer_tag"] as $key=>$value){
                                                ?><aside><a href="listing?category%5B%5D=<?=$value["term_id"]?>"><?=$value["name"]?></a></aside><?php
                                            }
                                        ?>
                                        </li>
                                        <li>
                                            <figure>Country</figure>
                                        <?php                                            
                                            foreach($iger["instagrammer_country"] as $key=>$value){
                                                ?><aside><a href="listing?country%5B%5D=<?=$value["term_id"]?>"><?=$value["name"]?></a></aside><?php
                                            }
                                        ?>
                                        </li>
                                    </ul>
                                    -->
                                </div>
                                <!--end addition-info-->
                                <p>
                                    <?=$iger["biography"]?>
                                </p>
                            </div>
                            <!--end author-description-->
                        </div>
                        <!--end author-->
                    </div>
                    <div class="background">
                        <div class="background-image original-size background-repeat-x">
                            <img src="" alt="">
                        </div>
                        <!--end background-image-->
                    </div>
                    <!--end background-->
                </section>
                <div class="container">
                    <div class="section-title clearfix" id="tellstory">
                        <div class="float-xl-left float-md-left float-sm-none">
                            <h2>Featured Stories</h2>
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
                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest</option>
                                <option value="oldest" <?php if($sortBy == "oldest"){ echo "selected";}?>>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!--============ Items ==========================================================================-->
                    <div class="items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-sort="<?=$sortBy?>" data-nc_data="post" data-nc_iger="<?=$iger["id"]?>">
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

                <?php if(isset($_GET["order"])){ ?>
                    $('html, body').animate({
                        scrollTop: $("#tellstory").offset().top
                    }, 500);
                <?php } ?>
            });
        </script>
<?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->

    <!--modal-->
    <div class="modal animated fadeIn" tabindex="-1" role="dialog" id="memberonlymodal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Uh-oh, Members Only!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-10 offset-md-1">
                    <a href="/signin">Sign in</a> to add this creator or story to your collection
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
