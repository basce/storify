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
    <script src="/assets/js/storify.project.users.js"></script>
    <script src="/assets/js/storify.creator.detail_invite.js"></script>
    <script src="/assets/js/storify.creator.projectlist_invite.js"></script>
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
                        <h1>Invites received</h1>
                        <h2 class="dynamic_title_text">
<?php
                        //$total_invites = $main->getProjectManager()->getNumberOfInvitationCreator($current_user->ID);
                        $stats = $main->getProjectManager()->getProjectStats($current_user->ID);
                        $total_invites = $stats["invite"] ? $stats["invite"] : 0;
                        if($total_invites == 0){
                            echo "You have not received any invitations.";
                        }else if($total_invites == 1){
                            echo "Say yes to 1 project.";
                        }else{
                            echo "Say yes to ".$total_invites." projects.";
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
                            <div class="project-items" id="invite_grid" data-page="0" data-sort="invitation_closing_date" data-filter="pending">
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="invitationloadmore">Load More</a>
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
            storify.creator.projectList_invite.addElementIfNotExist();

            var _initial_prompt = true;

            //add listner on update leftnav
            storify.core.addListener("menu_project_item_amount_update", function(obj){
                console.log(obj);
                if(obj.invite !== false){
                    $(".left_menu_invite").text("("+obj.invite+")");
                }
                if(obj.open !== false){
                    $(".left_menu_ongoing").text("("+obj.open+")");
                }
                if(obj.closed !== false){
                    $(".left_menu_closed").text("("+obj.closed+")");
                }
            });

            //update title
            storify.core.addListener("menu_project_item_amount_update", function(obj){
                console.log(obj);
                if(obj.invite !== false){
                    if(obj.invite == 0){
                        $(".dynamic_title_text").text("You have not received any invitations.");
                    }else if(obj.invite == 1){
                        $(".dynamic_title_text").text("Say yes to 1 project.");
                    }else{
                        $(".dynamic_title_text").text("Say yes to "+obj.invite+" projects.");
                    }
                }
            });

            $("#invitationloadmore").click(function(e){
                e.preventDefault();

                storify.core.getProjectListing(
                    "#invite_grid",
                    "#invitationloadmore",
                    "No invites yet. But press on, we are working hard to showcase your work to the brands you love.",
                    (index, value)=>{
                        console.log(value);
                        var div = $(storify.template.createListItem(value, value.id, [{classname:"detail", label:"Details"}, {classname:"accept", label:"Accept"}, {classname:"reject", label:"Reject"}]));
                        div.find(".actions .detail").click(function(e){
                            e.preventDefault();
                            storify.creator.detail_invite.viewDetail(value.id, function(onComplete){
                                if(onComplete) onComplete();
                            });
                        });
                        div.find(".actions .accept").click(function(e){
                            e.preventDefault();
                            $("#acceptModal .deadline").text(value.summary.formatted_closing_date2);
                            storify.creator.projectList_invite.acceptInvitation(value.invitation_id);
                        });
                        div.find(".actions .reject").click(function(e){
                            e.preventDefault();
                            $("#rejectModal input[name='invitation_id']").val(value.invitation_id);
                            $("#rejectModal").attr({"data-project_id":value.id})
                            $("#rejectModal").modal("show");
                        });

                        return div;
                    },
                    (er)=>{
                        alert(er.msg);
                    },
                    ()=>{
                        if( _initial_prompt && _project_id){
                            _initial_prompt = false;
                            storify.creator.detail_invite.viewDetail(_project_id, function(onComplete){
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
                );
            });

            if($("#invitationloadmore").length){
                $("#invitationloadmore").click();
            }
        });
    </script>
</body>
</html>