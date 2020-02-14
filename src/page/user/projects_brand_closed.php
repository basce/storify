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
    <!-- Theme included stylesheets -->
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
    <script src="/assets/js/owlcarousel/owl.carousel.js"></script>
    <script src="/assets/js/owlcarousel/owl.animate.js"></script>
    <script src="/assets/js/owlcarousel/owl.autoplay.js"></script>
    <script src="/assets/js/storify.loading.js"></script>
    <script src="/assets/js/storify.core.js"></script>
    <script src="/assets/js/storify.template.js"></script>
    <script src="/assets/js/storify.brand.detail_closed.js"></script>
    <script src="/assets/js/storify.brand.invitation_closed.js"></script>
    <script src="/assets/js/storify.brand.deliverable_closed.js"></script>
    <script src="/assets/js/storify.brand.completion_closed.js"></script>
    <script src="/assets/js/storify.project.users.js"></script>
    <script src="/assets/js/storify.brand.projectlist_closed.js"></script>
    <script src="/assets/js/SendBird.min.js"></script>
    <script src="/assets/js/linkify.min.js"></script>
    <script src="/assets/js/linkify-jquery.min.js"></script>
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
                        <h1>Closed Projects</h1>
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
                            <div class="project-items" id="closed_grid" data-page="0" data-sort="rev_closing_date" data-filter="close">
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="closeloadmore">Load More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--end block-->
        </section>
    
    <?php include("page/component/footer.php"); ?>
    </div>

    <!--end page-->
    <script type="text/javascript">
        var APP_ID = '68CE9863-C07D-4505-A659-F384AB1DE478';
        var sb = new SendBird({appId: APP_ID});
<?php
            if(sizeof($pathquery) > 3){
                if($pathquery[3] == "new" && isset($pathquery[4])){
                    //add new project
                    ?>
            var _project_id = 0;
            var _creator = <?=json_encode($main->getCreatorSingle($pathquery[4]))?>;
                    <?php
                }else{
                    // project id exist
                    ?>
            var _project_id = <?=$pathquery[3]?>;
            var _creator = <?=isset($pathquery[4])?"'".$pathquery[4]."'":"null"?>;
                    <?php
                }
            }else{
                ?>
            var _project_id = 0;
            var _creator = null;
                <?php
            }
?>
        $(function(){
            "use strict";

            var _initial_prompt = true;

            $("#closeloadmore").click(function(e){
                e.preventDefault();

                storify.core.getProjectListing(
                    "#closed_grid",
                    "#closeloadmore",
                    "No closed projects yet.",
                    (index, value)=>{
                        var div = $(storify.template.createListItemBrandView(value, value.id, [{classname:"detail", label:"Details"}]));
                        div.find(".actions .detail").click(function(e){
                            e.preventDefault();
                            storify.brand.detail_closed.viewDetail(value.id, function(onComplete){
                                if(onComplete)onComplete();
                            });
                        });
                        return div;
                    },
                    (rs)=>{
                        alert(rs.msg);
                    },
                    ()=>{
                        if( _initial_prompt && _project_id ){
                            storify.brand.detail_closed.viewDetail(_project_id, function(onComplete){
                                <?php
                                    if(sizeof($pathquery) > 4){
                                        switch($pathquery[4]){
                                            case "submit":
                                                $target_tab = "#submission-tab";
                                            break;
                                            case "final":
                                                $target_tab = "#final-tab";
                                            break;
                                            case "creator":
                                                $target_tab = "#creator-tab";
                                            break;
                                            case "bounty":
                                                $target_tab = "#bounty-tab";
                                            break;
                                        }
                                        ?>

                                        $("<?=$target_tab?>").one('shown.bs.tab',function(){
                                            if(onComplete)onComplete();
                                        }).tab("show");
                                        <?php
                                    }else{
                                ?>
                                    if(onComplete)onComplete();
                                <?php
                                    }
                                ?>
                            });
                        }
                    }
                )
            });

            if($("#closeloadmore").length){
                $("#closeloadmore").click();
            }
        });
    </script>
</body>
</html>