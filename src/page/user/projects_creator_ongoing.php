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
    <script src="/assets/js/storify.creator.detail.js"></script>
    <script src="/assets/js/storify.project.users.js"></script>
    <script src="/assets/js/storify.creator.projectlist.js"></script>
    <script src="/assets/js/storify.creator.deliverable.js"></script>
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
                        <h1>Ongoing Projects</h1>
                        <h2>
<?php
                        $total_ongoing = $main->getProjectManager()->getNumberOfOnGoingCreator($current_user->ID);
                        if($total_ongoing == 0){
                            echo "You have not received any invitations.";
                        }else if($total_ongoing == 1){
                            echo "You have 1 project to complete.";
                        }else{
                            echo "You have ".$total_ongoing." projects to complete.";
                        }
?>  
                        </h2>
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
                            <div class="project-items" id="ongoing_grid" data-page="0" data-sort="date" data-filter="ongoing">
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

    <div class="modal fullscreen" tabindex="-1" role="dialog" id="detailModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <section class="section-container detailcontent" id="detailcontent">
                            </section>
                        </div>
                        <div class="col-md-6 actioncontent">
                            <section class="section-container deliverable-section">
                                <h1>Deliverable Management</h1>
                                <div class="deliverable-groups">
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
              </div>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="deliverable_dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Deliverables List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="custommsg" novalidate>
              <div class="modal-body deliverable_items">
                <div class="deliverable_item">
                    <div class="main_block">
                        <h3>Photo #1</h3>
                        <div class="remark">
                            Remark 1
                        </div>
                        <div class="input_group">
                            <div class="label">URL</div>
                            <input type="text" class="form-control">
                        </div>
                        <div class="input_group">
                            <div class="label">Instruction</div>
                            <textarea row="4"></textarea>
                        </div>
                    </div>
                    <div class="reply_block">
                        <div class="input_group">
                            <div class="label">Status</div>
                            <span class="value red">Reject</span>
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                        <div class="input_group">
                            <div class="reason_value">Reason the submission is rejected</div>
                        </div>
                    </div>
                    <div class="history_block">
                        <a href="#">history</a>
                        <div class="history_item" style="display:none">
                            <div class="submission_block">
                                <div class="input_group">
                                <div class="label">URL</div>
                                <input type="text" class="form-control">
                            </div>
                                <div class="instruction">
                                    Information for the the submission
                                </div>
                            </div>
                            <div class="reply_block">
                                <label>Status</label> Rejected
                                <div class="instruction">
                                    Reason on why the submission is rejected.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
              </div>
          </form>
        </div>
      </div>
    </div>

    <!--end page-->
    <script type="text/javascript">
        var APP_ID = '68CE9863-C07D-4505-A659-F384AB1DE478';
        var sb = new SendBird({appId: APP_ID});

        $(function(){
            "use strict";

            $("#ongoingloadmore").click(function(e){
                e.preventDefault();
                storify.creator.projectList.getProject();
            });

            storify.creator.projectList.getProject(function(){
                <?php
                if(sizeof($pathquery) == 4){
                    ?>
                    storify.creator.detail.viewDetail(<?=$pathquery[3]?>);
                    <?php
                }
                ?>
            });
        });
    </script>
</body>
</html>