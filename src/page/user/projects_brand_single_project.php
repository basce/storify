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
    <link rel="stylesheet" href="/assets/css/datepicker.css">
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
    <script src="/assets/js/moment.min.js"></script>
    <script src="/assets/js/bootstrap-datepicker.js"></script>
    <script src="/assets/js/storify.core.js"></script>
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

    <script src="/assets/js/dev/dev_template.js"></script>
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
                        <h1>Project Name</h1>
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
                            <ul class="nav nav-tabs" role="tablist" id="tab_control">
                                <li class="nav-item">
                                    <a class="nav-link active" id="brief-tab" data-toggle="tab" href="#brief" role="tab" aria-controls="brief" aria-expanded="true">Brief</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="deliverable-tab" data-toggle="tab" href="#deliverable" role="tab" aria-controls="deliverable" aria-expanded="true">Deliverable</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="creator-tab" data-toggle="tab" href="#creator" role="tab" aria-controls="creator" aria-expanded="true">Creator</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="final-tab" data-toggle="tab" href="#final" role="tab" aria-controls="final" aria-expanded="true">Final</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab_content">
                                <div class="tab-pane fade show active" id="brief" role="tabpanel" aria-labelledby="brief-tab">
                                </div>
                                <div class="tab-pane fade" id="deliverable" role="tabpanel" aria-labelledby="creator-tab">
                                    <table id="deliverable" class="display" style="100%">
                                        <thead>
                                            <th rowspan="2">Task #1</th>
                                            <th
                                        </thead>
                                    </table>

                                    <div class="creatorcontent" id="deliverable-content">
                                        <p>Task will be list out, will also show all creator's submissions if any</p>
                                        <?php
                                        $temp_task = $project_detail["summary"]["task"];

                                        // get creator that accepted offer
                                        $temp_creator = array();
                                        if(sizeof($project_detail["summary"]["offer"]["data"])){
                                            foreach( $project_detail["summary"]["offer"]["data"] as $key=>$value ){
                                                if($value["status"] == "accepted"){
                                                    $temp_creator[] = $value;
                                                }
                                            }
                                        }

                                        // re-arrange 

                                        $temp_submissions = $project_detail["summary"]["submission"]["data"];

                                        print_r($temp_submissions);

                                        $temp_port_report = $project_detail["summary"]["post_report"]["data"];

                                        print_r($temp_port_report);

                                        foreach( $temp_task as $key=>$value ){
                                            if(!isset($temp_task[$key]["creator"])){
                                                $temp_task[$key]["creator"] = array();
                                            }
                                            foreach( $temp_creator as $key2=>$value2 ){
                                                if(!isset($temp_task[$key]["creator"][$value2["user_id"]])){
                                                    $temp_task[$key]["creator"][$value2["user_id"]] = array();
                                                }

                                                foreach($temp_submissions as $key3=>$value3){
                                                    if($value3["user_id"] == $value2["user_id"] && $value3["task_id"] == $value["id"] ){
                                                        $temp_task[$key]["creator"][$value2["user_id"]][] = $value3;
                                                        unset($temp_submissions[$key3]);
                                                    }
                                                }

                                                foreach($temp_port_report as $key3=>$value3){
                                                    if($value3["user_id"] == $value2["user_id"] && $value3["task_id"] == $value["id"]){
                                                        $temp_task[$key]["creator"][$value2["user_id"]][] = $value3;
                                                        unset($temp_port_report[$key3]);
                                                    }
                                                }
                                            }
                                        }

                                        print_r($temp_task);

                                        ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="creator" role="tabpanel" aria-labelledby="submission-tab">
                                    <div class="creatorcontent" id="creator-content">
                                        <p>this is the body for creator</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
                                    <div class="finalcontent" id="final-content">
                                        <p>this is the body for final</p>
                                    </div>
                                </div>
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
        $(function(){
            "use strict";

            var project_detail = <?=json_encode($project_detail)?>;
            //update brief tab
            $("#brief").empty();
            $("#brief").append(storify.dev.template.project_brief(project_detail));
                                    
        });
    </script>
</body>
</html>