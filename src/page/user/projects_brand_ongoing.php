<?php
use storify\staticparam as staticparam;
?><!doctype html>
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
    <link rel="stylesheet" href="/assets/css/datepicker.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
    <script src="/assets/js/moment.min.js"></script>
    <script src="/assets/js/bootstrap-datepicker.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/nc_custom.js"></script>
    <script src="/assets/js/owlcarousel/owl.carousel.js"></script>
    <script src="/assets/js/owlcarousel/owl.animate.js"></script>
    <script src="/assets/js/owlcarousel/owl.autoplay.js"></script>
    <script src="/assets/js/storify.loading.js"></script>
    <script src="/assets/js/storify.core.js"></script>
    <script src="/assets/js/storify.template.js"></script>
    <script src="/assets/js/storify.brand.detail.js"></script>
    <script src="/assets/js/storify.brand.projectlist.js"></script>
    <script src="/assets/js/storify.project.users.js"></script>
    <script src="/assets/js/storify.brand.invitation.js"></script>
    <script src="/assets/js/storify.brand.deliverable.js"></script>
    <script src="/assets/js/storify.brand.completion.js"></script>
    <script src="/assets/js/SendBird.min.js"></script>
    <script src="/assets/js/linkify.min.js"></script>
    <script src="/assets/js/linkify-jquery.min.js"></script>
    <!-- Main Quill library -->
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
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
                        <h1>Ongoing Projects</h1>
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
                            <div class="section-title clearfix">
                                <a href="/user@<?=$current_user->ID?>/project/new" class="btn btn-primary text-caps btn-rounded btn-framed width-100">+ NEW PROJECT</a>
                            </div>
                            <div class="project20-items" id="ongoing_grid" data-page="0" data-sort="closing_date" data-filter="ongoing">
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="ongoingloadmore">Load More</a>
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

        var _project_users = null;
        var _default_brand_id = <?=$default_group["default_brand"]?>;
        var _baseurl = "/user@<?=$current_user->ID?>/projects/closed/";

    //add project related
        
        function _checkIfObjectVisible(a, onVisible){
            if(a.is(":visible")){
                if(onVisible)onVisible();
            }else{
                setTimeout(function(){
                    _checkIfObjectVisible(a, onVisible);
                },10);
            }
        }

        function changeDateTime(str){
            if(str == "0000-00-00 00:00:00"){
                return "";
            }
            var date = str.split(" ")[0].split("-");
            return date[2]+"/"+date[1]+"/"+date[0].slice(2);
        }

        function convertToArray(input){
            var a = [];

            if(input.length){
                $.each(input, function(index,value){
                    a.push(value.term_id);
                });
            }

            return a;
        }

        function convertToOption(input){
            var a = [];
            if(input.length){
                $.each(input, function(index,value){
                    a.push({
                        value:value.term_id,
                        text:value.name
                    });
                });
            }

            return a;
        }

        //initial function

        $(function(){
            "use strict";

            $("#ongoingloadmore").click(function(e){
                e.preventDefault();

                storify.core.getProjectListing(
                    "#ongoing_grid", 
                    "#ongoingloadmore", 
                    "You have not created a project yet. Create one now!", 
                    (index,value)=>{
                        var div = $(storify.template.createProjectListItem(value, value.id, [{label:"Details", attr:{classname:"detail", href:"/user@<?=$current_user->ID?>/project/"+value.id,o:value.id}}]));
                        return div;
                    },
                    (rs)=>{
                        alert(rs.msg);
                    },
                    ()=>{
                        console.log("call back function");
                    }
                );

                //storify.brand.projectList.getProject();
            });

            if($("#ongoingloadmore").length){
                $("#ongoingloadmore").click();
            }
        });
    </script>
</body>
</html>