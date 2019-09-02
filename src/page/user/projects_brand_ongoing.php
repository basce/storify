<?php
use storify\staticparam as staticparam;
?><!doctype html>
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
    <link rel="stylesheet" href="/assets/css/datepicker.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- Theme included stylesheets -->
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-clipboard{
            position: fixed;
            display: none;

            left: 50%;
            top: 50%;
        }
    </style>
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
                                <a href="#" class="btn btn-primary text-caps btn-rounded btn-framed width-100" id="addproject">+ NEW PROJECT</a>
                            </div>
                            <div class="project-items" id="ongoing_grid" data-page="0" data-sort="closing_date" data-filter="ongoing">
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
    <div class="modal" tabindex="-1" role="dialog" id="editDialog" style="z-index:1051">
        <div class="modal-dialog modal-dialog-centered modal-custom-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="form-group">
                                <label for="edit_closing_date" class="required">Submission Closing Date</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="edit_closing_date" placeholder="dd/mm/yy" autoComplete="off" required>
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                                <div class="form-width">
                                    <div class="alert alert-danger hide">Some error message</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_invitation_closing_date" class="required">Invitation closing date</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="edit_invitation_closing_date" placeholder="dd/mm/yy" autoComplete="off" required>
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                                <div class="form-width">
                                    <div class="alert alert-danger hide">Some error message</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_project_name" class="required">Title</label>
                                <input type="text" class="form-control" id="edit_project_name" placeholder="Enter a name for this project." autoComplete="off" required>
                                <div class="form-width">
                                    <div class="alert alert-danger hide">Some error message</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_project_description" class="required">Details</label>
                                <div class="form-control quill-textarea" id="edit_project_description" placeholder="Please provide details to your campaign." required></div>
                                <div class="form-width">
                                    <div class="alert alert-danger hide">Some error message</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_project_short_description">Summary</label>
                                <textarea class="form-control" id="edit_project_short_description" rows="1" placeholder="Please include a short line to describe your project."></textarea>
                            </div>
                            <div class="form-group">
                                <labal for="project_brand">Brand</labal>
                                <select name="brand[]" id="edit_brand" data-placeholder="Select Brand." class="customselect" data-enable-input=true nc-method="addBrand" multiple>
                                    <option value="">Select Brand</option>
                            <?php
                                $brands = $main->getAllBrands(true);
                                foreach($brands as $key=>$value){
                                    
                                    ?>
                                    <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                    <?php
                                    
                                }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project_location">Location</label>
                                <select name="location[]" id="edit_location" data-placeholder="Select Location." class="customselect" data-enable-input=true nc-method="addLocation" multiple>
                                    <option value="">Select Location</option>
                            <?php
                                $country_tags = $main->getAllCountriesInUsed(true);
                                $default_country_id = 0;

                                foreach($country_tags as $key=>$value){
                                    //auto add the user current country
                                    //get full name from short name ( since it is storing short name for user data )
                                    $temp_val = "";
                                    if($current_user_meta && $current_user_meta["city_country"] && sizeof($current_user_meta["city_country"]) && isset(staticparam::$user_country_ar[$current_user_meta["city_country"][0]])){
                                        //get fullname
                                        $temp_val = staticparam::$user_country_ar[$current_user_meta["city_country"][0]];
                                    }
                                    if($value["name"] == $temp_val){
                                        $default_country_id = $value["term_id"];
                                        ?>
                                    <option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option>
                                    <?php
                                    }else if(!$value["hidden"]){
                                    ?>
                                    <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                    <?php
                                    }
                                }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project_tag">Passions</label>
                                <select name="tag[]" id="edit_tag" data-placeholder="Select Passions." class="customselect" data-enable-input=true nc-method="addTag" multiple>
                                    <option value="">Select Passions</option>
                            <?php
                                $category_tags = $main->getAllTagsInUsed(true);
                                foreach($category_tags as $key=>$value){
                                    if(!$value["hidden"]){
                                    ?>
                                    <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                    <?php
                                    }
                                }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="samples">Moodboard</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="edit_samples" placeholder="Insert a link to your image." autoComplete="off">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary edit_addSampleButton" type="button">Add Image</button>
                                    </div>
                                </div>
                            </div>
                            <div class="image-groups">
                                
                            </div>
                            <div class="form-group">
                                <label for="edit_deliverable_brief">Instructions to Creators</label>
                                <div class="form-control quill-textarea" id="edit_deliverable_brief" placeholder="Provide further details about style, creative angle and other expectations."></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="float-right bottom_panel">
                        <button class="btn btn-primary">Update</button>
                    </div>
              </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="newproject">
      <div class="modal-dialog modal-dialog-centered modal-custom-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Project</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">Step 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Brief</div>
                </div>
                
                <div class="col-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Deliverable</div>
                </div>
                
                <div class="col-3 bs-wizard-step active"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Bounty</div>
                </div>

                <div class="col-3 bs-wizard-step active"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Creators</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 offset-md-1" id="add_project_page_1" style="display:none;">
                    <div class="form-group">
                        <label for="project_name" class="required">Title</label>
                        <input type="text" class="form-control" id="project_name" placeholder="Enter a name for this project." autoComplete="off" required>
                        <!-- <input type="text" class="form-control" id="project_name" aria-describedby="projectNameHelp" placeholder="Enter Project Name">
                        <small id="projectNameHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="project_description" class="required">Details</label>
                        <div class="form-control quill-textarea" id="project_description" placeholder="Please provide details to your campaign." required></div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="project_short_description">Summary</label>
                        <textarea class="form-control" id="project_short_description" rows="1" placeholder="Please include a short line to describe your project."></textarea>
                    </div>
                    <div class="form-group">
                        <labal for="project_brand">Brand</labal>
                        <select name="brand[]" id="brand" data-placeholder="Select Brand." class="customselect" data-enable-input=true nc-method="addBrand" multiple>
                            <option value="">Select Brand</option>
                    <?php
                        $brands = $main->getAllBrands();
                        foreach($brands as $key=>$value){
                            if(!$value["hidden"]){
                            ?>
                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                            <?php
                            }
                        }
                    ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="project_location">Location</label>
                        <select name="location[]" id="location" data-placeholder="Select Location." class="customselect" data-enable-input=true nc-method="addLocation" multiple>
                            <option value="">Select Location</option>
                    <?php
                        $country_tags = $main->getAllCountriesInUsed();
                        $default_country_id = 0;
                        foreach($country_tags as $key=>$value){
                            //auto add the user current country
                            //get full name from short name ( since it is storing short name for user data )
                            $temp_val = "";
                            if($current_user_meta && $current_user_meta["city_country"] && sizeof($current_user_meta["city_country"])){
                                //get fullname
                                $temp_val = staticparam::$user_country_ar[$current_user_meta["city_country"][0]];
                            }
                            if($value["name"] == $temp_val){
                                $default_country_id = $value["term_id"];
                                ?>
                            <option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option>
                            <?php
                            }else if(!$value["hidden"]){
                            ?>
                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                            <?php
                            }
                        }
                    ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="project_tag">Passions</label>
                        <select name="tag[]" id="tag" data-placeholder="Select Passions." class="customselect" data-enable-input=true nc-method="addTag" multiple>
                            <option value="">Select Passions</option>
                    <?php
                        $category_tags = $main->getAllTagsInUsed();
                        foreach($category_tags as $key=>$value){
                            if(!$value["hidden"]){
                            ?>
                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                            <?php
                            }
                        }
                    ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1" id="add_project_page_2" style="display:none;">
                    <div class="form-group">
                        <label for="closing_date" class="required">Submission Closing Date</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" placeholder="dd/mm/yy" autoComplete="off" id="closing_date" required>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="number_of_photo">No. Of Photos Per Creator</label>
                        <input type="number" class="form-control" id="number_of_photo" placeholder="Enter number of photos." autoComplete="off" value="">
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="number_of_video">No. Of Videos Per Creator</label>
                        <input type="number" class="form-control" id="number_of_video" placeholder="Enter number of videos." autoComplete="off" value="">
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="video_length">Video Length</label>
                        <input type="number" class="form-control" id="video_length" autoComplete="off" placeholder="Enter length of videos in seconds.">
                        <small id="videoLengthHelp" class="form-text text-muted">Video length in seconds</small>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="samples">Moodboard</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="samples" autoComplete="off" placeholder="Insert a link to your image.">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary addSampleButton" type="button">Add Image</button>
                            </div>
                        </div>
                    </div>
                    <div class="image-groups">
                        
                    </div>
                    <div class="form-group">
                        <label for="deliverable_brief">Instructions to Creators</label>
                        <div class="form-control quill-textarea" id="deliverable_brief" placeholder="Provide further details about style, creative angle and other expectations."></div>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1" id="add_project_page_3" style="display:none">
                    <div class="form-group">
                        <label for="bounty_type">Entitlements</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="bounty_cash" value="cash">
                            <label class="form-check-label" for="bounty_cash">Cash</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="bounty_gift" value="gift">
                            <label class="form-check-label" for="bounty_gift">In-kind</label>
                        </div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost_per_photo">$ Per Photo</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-dollar"></span>
                            </span>
                            <input type="number" class="form-control" id="cost_per_photo" autoComplete="off" placeholder="Amount you will pay for each photo. If on sponsorship arrangement, leave this field blank.">
                        </div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost_per_video">$ Per Video</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-dollar"></span>
                            </span>
                            <input type="number" class="form-control" id="cost_per_video" autoComplete="off" placeholder="Amount you will pay for each video. If on sponsorship arrangement, leave this field blank.">
                        </div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gift_name">Sponsorship</label>
                        <input type="text" class="form-control" id="gift_name" autoComplete="off" placeholder="Specify vouchers, products and sponsorship-in-kind items. If not applicable, leave this field blank." value="">
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1" id="add_project_page_4">
                    <div class="form-group">
                        <label for="invitation_closing_date" class="required">Invitation closing date</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" autoComplete="off" placeholder="dd/mm/yy" id="invitation_closing_date" required>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                        <div class="form-width">
                            <div class="alert alert-danger hide">Some error message</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="creators">Creators To Invite</label>
                        <div class="input-group mb-3 creator-input">
                            <select name="creators[]" class="form-control customselect" id="creators" data-placeholder="Select creators for this project." multiple>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary addCreatorButton" type="button">Add Creators</button>
                            </div>
                        </div>
                    </div>
                    <div class="creator-groups row">
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
                <div class="float-right bottom_panel">
                    <button class="btn btn-primary">Back</button>
                    <button class="btn btn-primary">Next</button>
                </div>
          </div>
        </div>
      </div>
    </div>
    <!--end page-->
    <script type="text/javascript">
            
            var APP_ID = '68CE9863-C07D-4505-A659-F384AB1DE478';
            var sb = new SendBird({appId: APP_ID});
<?php
            if(sizeof($pathquery) > 3){
                if($pathquery[3] == "new" ){
                    //add new project
                    if(isset($pathquery[4])){
                    ?>
            var _project_id = 0;
            var _creator = <?=json_encode($main->getCreatorSingle($pathquery[4]))?>;
                    <?php
                    }else{
                        ?>
            var _project_id = 0;
            var _creator = "new";
                    <?php
                    }
                    
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

            var _project_users = null;
            var _default_country_id = <?=$default_country_id?>;
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

            $(".quill-textarea").each(function(i,e){
                _checkIfObjectVisible($(e), function(){
                    var temp_item = $(e);
                    var inner_content = temp_item.html();
                    var temp_quill = new Quill("#"+temp_item.attr("id"), {
                        placeholder: temp_item.attr("placeholder"),
                        theme: 'snow'
                    });
                    temp_item.data("quill", temp_quill);
                    /*
                    if(inner_content){
                        temp_quill.clipboard.dangerouslyPasteHTML(0, inner_content);
                    }*/
                });
            });

            $("#creators").selectize({
                plugins:['restore_on_backspace', 'no_results'],
                delimiter:',',
                valueField: 'userid',
                labelField: 'name',
                searchField: ['name','igusername'],
                persist: false,
                loadThrottle: 600,
                create: false,
                allowEmptyOption: true,
                render: {
                    option: function(item, escape) {
                        return '<div class="selectize_iger">' +
                            '<div class="img" style="background-image:url('+escape(item.image_url)+')" ></div>'+
                            '<div class="title">' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                                '<span class="igusername">' + escape(item.igusername) + '</span>' +
                            '</div>' +
                        '</div>';
                    }
                },
                load: function(query, callback){
                    if(query.length < 3) return callback();
                    $.ajax({
                        type:"POST",
                        dataType:'json',
                        data:{
                            name:query,
                            method:"getCreator"
                        },
                        error:function(){
                            callback();
                        },
                        success:function(res){
                            /*
                            [{name:xxx,value:xxxx},{name:xxx,value:xxxx}]
                             */
                            callback(res.data);
                        }
                    });
                },
                onItemAdd: function(value, item){
                    /*
                    var selected = $("#creators")[0].selectize.getValue();
                    if(selected.length){
                        $.each(selected, function(index,value){
                            //add invited creator
                            var tempitem = $("#creators")[0].selectize.options[value];
                            addInvitedCreator(tempitem);
                        });
                    }
                    $("#creators")[0].selectize.clear(true);
                    */
                }
            });

            $(".addCreatorButton").click(function(e){
                e.preventDefault();
                var selected = $("#creators")[0].selectize.getValue();
                $.each(selected, function(index,value){
                    var tempitem = $("#creators")[0].selectize.options[value];
                    addInvitedCreator(tempitem);
                });
                $("#creators")[0].selectize.clear(true);
            });

            function addInvitedCreator(item){
                if($('.creator-item[data-id="'+item.userid+'"]').length) return;
                var a = $("<div>").addClass("creator-item col-md-4").attr({"data-id":item.userid})
                    .append($("<div>").addClass("item-inner")
                        .append($("<div>").addClass("creator-image")
                                .css({"background-image":"url("+item.image_url+")"})
                        )
                        .append($("<div>").addClass("creator-description")
                                    .append($("<h3>").text(item.name)
                                        )
                                    .append($("<small>").text(item.igusername))

                        )
                        .append($("<button>").attr({type:"button", "aria-label":"Close"})
                            .addClass("close")
                            .append($("<span>").attr({"aria-hidden":true}).text("×"))
                            .click(function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                $(this).parents(".creator-item").remove();
                            })
                        )
                    );
                $(".creator-groups").append(a);
            }

            function addEditSample(url){
                $("#editDialog .image-groups").append(
                    $("<div>").addClass("image-item")
                        .append($("<a>").attr({href:url, target:"_blank"})
                                    .css({"background-image":"url("+url+")"})
                                )
                        .append($("<button>").attr({type:"button", "aria-label":"Close"})
                                .append($("<span>").attr({"aria-hidden":true}).text("×"))
                                .click(function(e){
                                    e.preventDefault();
                                    e.stopPropagation();
                                    $(this).parent(".image-item").remove();
                                })
                            )
                );
            }

            function addSample(url){
                $("#newproject .image-groups").prepend(
                    $("<div>").addClass("image-item")
                        .append($("<a>").attr({href:url, target:"_blank"})
                                    .css({"background-image":"url("+url+")"})
                                )
                        .append($("<button>").attr({type:"button", "aria-label":"Close"})
                                .append($("<span>").attr({"aria-hidden":true}).text("×"))
                                .click(function(e){
                                    e.preventDefault();
                                    e.stopPropagation();
                                    $(this).parent(".image-item").remove();
                                })
                            )
                );
            }

            function isEditPageReady(silence){
                var pageReady = true,
                    msg = "";

                $('#editDialog .alert').addClass("hide");
                
                $("#editDialog *[required]").each(function(index,value){
                    if($(this).data("quill")){
                        if(!$(this).data("quill").getText().trim().length){
                            pageReady = false;
                            msg = "Please fill in all required fields.";
                            if(!silence){
                                $(this).parents(".form-group").find(".alert").text(msg).removeClass("hide");
                            }
                        }else{

                        }
                    }else if($(value).val() == ""){
                        pageReady = false;
                        msg = "Please fill in all required fields.";
                        if(!silence){
                            $(this).parents(".form-group").find(".alert").text(msg).removeClass("hide");
                        }
                    }
                });
                if(!$("#edit_closing_date").val()){
                    pageReady = false;
                    msg = "Required field - please set the closing date for photo/video submissions.";
                    if(!silence){
                        $("#edit_closing_date").parents(".form-group").find(".alert").text(msg).removeClass("hide");
                    }
                }
                if(!$("#edit_invitation_closing_date").val()){
                    pageReady = false;
                    msg = "Required field - please set the closing date for invitations to be accepted.";
                    if(!silence){
                        $("#edit_invitation_closing_date").parents(".form-group").find(".alert").text(msg).removeClass("hide");
                    }
                }
                if(!silence && msg){
                    //alert(msg);
                    if($(".alert:visible").length){ //with error message
                        var temp_a = $($(".alert:visible")[0]).parents(".form-group").offset(),
                            temp_b = $(".alert:visible").parents(".modal-body").offset();
                            if( (temp_a.top - temp_b.top ) < 0){
                                $(".alert:visible").parents(".modal-body").stop().animate({scrollTop:0}, 200, 'linear', function() { 
   
                                });
                            }
                    }
                }
                return pageReady;
            }

            function edit_gatherAllData(){
                var a = {
                    detail:{
                        name:$("#edit_project_name").val(),
                        description_brief:$("#edit_project_description").data("quill").container.firstChild.innerHTML,
                        deliverable_brief:$("#edit_deliverable_brief").data("quill").container.firstChild.innerHTML,
                        short_description:$("#edit_project_short_description").val(),
                        closing_date:$("#edit_closing_date").val(),
                        invitation_closing_date:$("#edit_invitation_closing_date").val()
                    },
                    brand:$("#edit_brand").val(),
                    location:$("#edit_location").val(),
                    tag:$("#edit_tag").val(),
                    samples:$("#editDialog .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get()
                };

                return a;
            }

            var _updateProject = false;
            function updateProject(data, project_id){
                if(_updateProject) return;
                _updateProject = true;

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data:{
                        method: "editProject",
                        project_id: project_id,
                        data: data
                    },
                    success:function(rs){
                        _updateProject = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            //no error
                            $("#editDialog").modal("hide");
                            storify.brand.detail.viewDetail(rs.project_id);
                            resetEditProject();
                        }
                    }
                })
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

            function updateEditProject(data){
                $("#edit_project_name").val(data.detail.name);

                //$("#edit_closing_date").val(changeDateTime(data.closing_date));
                //$("#edit_invitation_closing_date").val(changeDateTime(data.invitation_closing_date));

                $("#edit_closing_date").datepicker("update", changeDateTime(data.summary.closing_date));
                $("#edit_invitation_closing_date").datepicker("update", changeDateTime(data.summary.invitation_closing_date));

                if(data.location && data.location.length){
                    $("#edit_location")[0].selectize.addOption(convertToOption(data.location));
                }
                if(data.brand && data.brand.length){
                    $("#edit_brand")[0].selectize.addOption(convertToOption(data.brand));
                }
                if(data.tag && data.tag.length){
                    $("#edit_tag")[0].selectize.addOption(convertToOption(data.tag));
                }
                $("#edit_location")[0].selectize.setValue(convertToArray(data.location));
                $("#edit_brand")[0].selectize.setValue(convertToArray(data.brand));
                $("#edit_tag")[0].selectize.setValue(convertToArray(data.tag));

                if($("#edit_project_description").data("quill")){
                    $("#edit_project_description").data("quill").setContents([]);
                    $("#edit_project_description").data("quill").clipboard.dangerouslyPasteHTML(0, data.detail.description_brief);
                }else{
                    $("#edit_project_description").html(data.detail.description_brief);
                }

                $("#edit_project_short_description").val(data.detail.short_description);

                if($("#edit_deliverable_brief").data("quill")){
                    $("#edit_deliverable_brief").data("quill").setContents([]);
                    $("#edit_deliverable_brief").data("quill").clipboard.dangerouslyPasteHTML(0, data.detail.deliverable_brief);
                }else{
                    $("#edit_deliverable_brief").html(data.detail.deliverable_brief);
                }

                $("#edit_samples").val("");
                $("#editDialog .image-groups").empty();
                if(data.sample.length){
                    $.each(data.sample, function(index,value){
                        addEditSample(value.URL);
                    });
                }

                $("#editDialog .bottom_panel .btn:eq(0)").attr({"data-project_id":data.detail.project_id})

                editProjectCurrentProgress = 0;
            }

            function resetEditProject(){
                
                $("#edit_project_name").val("");
                $("#edit_closing_date").val("");
                $("#edit_invitation_closing_date").val("");
                $("#edit_location")[0].selectize.setValue();
                $("#edit_brand")[0].selectize.setValue();
                $("#edit_tag")[0].selectize.setValue();

                $("#edit_project_description").data("quill").setContents([]);
                $("#edit_project_description").data("quill").clipboard.dangerouslyPasteHTML(0, "");

                $("#edit_project_short_description").val();

                $("#edit_deliverable_brief").data("quill").setContents([]);
                $("#edit_deliverable_brief").data("quill").clipboard.dangerouslyPasteHTML(0, "");

                $("#edit_samples").val("");
                $("#editDialog .image-groups").empty();
            }

            var editProjectCurrentProgress = 0;
            function changeEditProjectProgress(progressindex){
                if(progressindex > editProjectCurrentProgress){
                    $("#editDialog .bs-wizard .col-3").removeClass("complete active disabled");
                    editProjectCurrentProgress = progressindex;
                    switch(progressindex){
                        case 1:
                            $("#editDialog .bs-wizard .col-3:eq(0)").addClass("active");
                            $("#editDialog .bs-wizard .col-3:eq(1)").addClass("disabled");
                            $("#editDialog .bs-wizard .col-3:eq(2)").addClass("disabled");
                        break;
                        case 2:
                            $("#editDialog .bs-wizard .col-3:eq(0)").addClass("complete");
                            $("#editDialog .bs-wizard .col-3:eq(1)").addClass("active");
                            $("#editDialog .bs-wizard .col-3:eq(2)").addClass("disabled");
                        break;
                        case 3:
                            $("#editDialog .bs-wizard .col-3:eq(0)").addClass("complete");
                            $("#editDialog .bs-wizard .col-3:eq(1)").addClass("complete");
                            $("#editDialog .bs-wizard .col-3:eq(2)").addClass("active");
                        break;
                    }
                }
            }

            function editCheckFooterButton(pageindex){
                switch(pageindex){
                    case 1:
                        $("#editDialog .bottom_panel .btn:eq(0)").addClass("disabled");
                        $("#editDialog .bottom_panel .btn:eq(1)").text("Next");
                    break;
                    case 2:
                        $("#editDialog .bottom_panel .btn:eq(0)").removeClass("disabled");
                        $("#editDialog .bottom_panel .btn:eq(1)").text("Next");
                    break;
                    case 3:
                        $("#editDialog .bottom_panel .btn:eq(0)").removeClass("disabled");
                        $("#editDialog .bottom_panel .btn:eq(1)").text("Update");
                    break;
                }
            }

            function edit_formNext(project_id){
                if(isEditPageReady(false)){
                    var a = edit_gatherAllData();
                    updateProject(a, project_id);
                }
            }

            function isPageReady(pageindex, silence){
                var pageReady = true,
                    msg = "";
                if(pageindex == 1){
                    //page 1
                    $('#add_project_page_1 .alert').addClass("hide");
                    $('#add_project_page_1 *[required]').each(function(index,value){
                        if($(this).data("quill")){
                            if(!$(this).data("quill").getText().trim().length){
                                pageReady = false;
                                msg = "Please fill in all required fields.";
                                if(!silence){
                                    $(this).parents(".form-group").find(".alert").text("Please fill in all required fields.").removeClass("hide");
                                }
                            }else{

                            }
                        }else if($(value).val() == ""){
                            pageReady = false;
                            msg = "Please fill in all required fields.";
                            if(!silence){
                                $(this).parents(".form-group").find(".alert").text("Please fill in all required fields.").removeClass("hide");
                            }
                        }
                    });
                }else if(pageindex == 2){
                    //page 2
                    $('#add_project_page_2 .alert').addClass("hide");
                    if(!$("#closing_date").val()){
                        //closing date not set
                        pageReady = false;
                        msg = "Please fill in the closing date";
                        if(!silence){
                            $("#closing_date").parents(".form-group").find(".alert").text("Required field - please set the closing date for photo/video submissions.").removeClass("hide");
                        }
                    }else if(!(parseInt($("#number_of_photo").val()?$("#number_of_photo").val():0, 10) + parseInt($("#number_of_video").val()?$("#number_of_video").val():0, 10))){
                        //number of video or photos is not set.
                        pageReady = false;
                        msg = "Please fill in the number or photo / video required to deliver";
                        if(!silence){
                            $("#number_of_photo").parents(".form-group").find(".alert").text("Required field - please enter the number of photos to be produced by the Creator.").removeClass("hide");
                            $("#number_of_video").parents(".form-group").find(".alert").text("Required field - please enter the number of videos you want the Creator to produce.").removeClass("hide");
                        }
                    }
                }else if(pageindex == 3){
                    $('#add_project_page_3 .alert').addClass("hide");
                    if(!$("#bounty_cash").is(":checked") && !$("#bounty_gift").is(":checked")){
                        //if both not set
                        pageReady = false;
                        msg = "Please specify cash or sponsorship, or both.";
                        if(!silence){
                            $("#bounty_cash").parents(".form-group").find(".alert").text("Please specify cash or sponsorship, or both.").removeClass("hide");
                        }
                    }else if($("#bounty_cash").is(":checked") && (parseInt($("#number_of_photo").val()?$("#number_of_photo").val():0,10)?true:false) && !(parseInt($("#cost_per_photo").val()?$("#cost_per_photo").val():0, 10))){
                        pageReady = false;
                        msg = "Please enter cost per photo";
                        if(!silence){
                            $("#cost_per_photo").parents(".form-group").find(".alert").text("Please enter cost per photo").removeClass("hide");
                        }
                    }else if($("#bounty_cash").is(":checked") && (parseInt($("#number_of_video").val()?$("#number_of_video").val():0,10)?true:false) && !(parseInt($("#cost_per_video").val()?$("#cost_per_video").val():0, 10))){
                        pageReady = false;
                        msg = "Please enter cost per video";
                        if(!silence){
                            $("#cost_per_video").parents(".form-group").find(".alert").text("Please enter cost per video").removeClass("hide");
                        }
                    }else if($("#bounty_gift").is(":checked") && $("#gift_name").val() == ""){
                        //gift, check if gift name is given
                        pageReady = false;
                        msg = "Please fill in the name of the gift";
                        if(!silence){
                            $("#gift_name").parents(".form-group").find(".alert").text("Please fill in the name of the gift").removeClass("hide");
                        }
                    }
                }else if(pageindex == 4){
                    if(!$("#invitation_closing_date").val()){
                        //closing date not set
                        pageReady = false;
                        msg = "Please fill in the invitation closing date";
                        if(!silence){
                            $("#invitation_closing_date").parents(".form-group").find(".alert").text("Required field - please set the closing date for invitations to be accepted.").removeClass("hide");
                        }
                    }else if($(".creator-groups .creator-item").length){
                        pageReady = true;
                    }else{
                        msg = "You may invite creator later";
                    }
                }
                if(!silence && msg){
                    //alert(msg); original alert function
                    if($(".alert:visible").length){ //with error message
                        var temp_a = $($(".alert:visible")[0]).parents(".form-group").offset(),
                            temp_b = $(".alert:visible").parents(".modal-body").offset();
                            if( (temp_a.top - temp_b.top ) < 0){
                                $(".alert:visible").parents(".modal-body").stop().animate({scrollTop:0}, 200, 'linear', function() { 
   
                                });
                            }
                    }
                }
                return pageReady;
            }

            function formBack(){
                var currentPage = 0;
                if($("#add_project_page_1").is(":visible")){
                    currentPage = 1;
                }else if($("#add_project_page_2").is(":visible")){
                    currentPage = 2;
                }else if($("#add_project_page_3").is(":visible")){
                    currentPage = 3;
                }else if($("#add_project_page_4").is(":visible")){
                    currentPage = 4;
                }
                if(currentPage > 1){
                    showAddProjectPage(currentPage-1);
                }
            }

            function gatherAllData(){
                var a = {
                    detail:{
                        name:$("#project_name").val(),
                        description_brief:$("#project_description").data("quill").container.firstChild.innerHTML,
                        deliverable_brief:$("#deliverable_brief").data("quill").container.firstChild.innerHTML,
                        other_brief:"",
                        short_description:$("#project_short_description").val(),
                        no_of_photo:$("#number_of_photo").val(),
                        no_of_video:$("#number_of_video").val(),
                        video_length:$("#video_length").val(),
                        bounty_type:( $("#bounty_cash").is(":checked") && $("#bounty_gift").is(":checked") ) ? 'both': $("#bounty_cash").is(":checked") ? 'cash' : 'gift',
                        cost_per_photo:$("#cost_per_photo").val(),
                        cost_per_video:$("#cost_per_video").val(),
                        reward_name:$("#gift_name").val(),
                        closing_date:$("#closing_date").val(),
                        invitation_closing_date:$("#invitation_closing_date").val()
                    },
                    brand:$("#brand").val(),
                    location:$("#location").val(),
                    tag:$("#tag").val(),
                    deliverable:[],
                    samples:$("#newproject .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                    invitation:$("#newproject .creator-groups .creator-item").map(function(i,v){ return $(v).attr('data-id'); }).get()
                };

                return a;
            }

            function formNext(){
                var currentPage = 0;
                if($("#add_project_page_1").is(":visible")){
                    currentPage = 1;
                }else if($("#add_project_page_2").is(":visible")){
                    currentPage = 2;
                }else if($("#add_project_page_3").is(":visible")){
                    currentPage = 3;
                }else if($("#add_project_page_4").is(":visible")){
                    currentPage = 4;
                }
                if(isPageReady(currentPage, false)){
                    if(currentPage == 4){
                        //get data and create to server and return project id
                        var a = gatherAllData();
                        saveProject(a);
                    }else{
                        //go to next page
                        showAddProjectPage(currentPage+1);
                    }
                }
            }

            var _savingProject = false;
            function saveProject(data){
                if(_savingProject) return;
                _savingProject = true;

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data:{
                        method: "addProject",
                        data:data
                    },
                    success:function(rs){
                        _savingProject = false;
                        console.log(rs);
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            //no error
                            $("#ongoing_grid").attr({"data-sort":"closing_date","data-page":0});
                            $("#ongoing_grid").empty();

                            //also need to clear all data
                            resetAddProject();
                            $("#newproject").modal("hide");

                            //update left menu number
                            if(rs.project_stats){
                                if(rs.project_stats.hasOwnProperty('invite')){
                                    $(".left_menu_invite").text("("+rs.project_stats.invite+")");
                                }
                                if(rs.project_stats.hasOwnProperty('open')){
                                    $(".left_menu_ongoing").text("("+rs.project_stats.open+")");
                                }
                                if(rs.project_stats.hasOwnProperty('close')){
                                    $(".left_menu_closed").text("("+rs.project_stats.close+")");
                                }
                            }

                            storify.core.getProjectListing(
                                "#ongoing_grid", 
                                "#ongoingloadmore", 
                                "You have not created a project yet. Create one now!", 
                                (index,value)=>{
                                    var div = $(storify.template.createListItem(value, value.id, [{classname:"detail", label:"Details"}]));
                                    div.find(".actions .detail").click(function(e){
                                        e.preventDefault();
                                        storify.brand.detail.viewDetail(value.id);
                                    });
                                    return div;
                                },
                                (rs2)=>{
                                    alert(rs2.msg);
                                },
                                ()=>{
                                    storify.brand.detail.viewDetail(rs.project_id);
                                }
                            );
                        }
                    }
                })
            }

            function resetAddProject(){

                //page 1
                $("#project_name").val("");
                $("#project_description").data("quill").setContents([]);
                $("#project_short_description").val();
                if(_default_country_id){
                    $("#location")[0].selectize.setValue(_default_country_id);
                }else{
                    $("#location")[0].selectize.setValue();
                }
                $("#brand")[0].selectize.setValue();
                $("#tag")[0].selectize.setValue();

                //page 2
                $("#closing_date").val("");
                $("#number_of_photo").val("");
                $("#number_of_video").val("");
                $("#video_length").val("");
                $("#samples").val("");
                $("#newproject .image-groups").empty();
                $("#deliverable_brief").data("quill").setContents([]);

                //page 3
                $("#bounty_cash").iCheck("uncheck");
                $("#bounty_gift").iCheck("uncheck");
                $("#cost_per_photo").val("");
                $("#cost_per_video").val("");
                $("#gift_name").val("");

                //page 4
                $("#invitation_closing_date").val("");
                $("#creators")[0].selectize.setValue();
                $(".creator-groups").empty();

                addProjectCurrentProgress = 0;
                showAddProjectPage(1);
            }

            var addProjectCurrentProgress = 0;
            function changeAddProjectProgress(progressindex){
                if(progressindex > addProjectCurrentProgress){
                    $("#newproject .bs-wizard .col-3").removeClass("complete active disabled");
                    addProjectCurrentProgress = progressindex;
                    switch(progressindex){
                        case 1:
                            $("#newproject .bs-wizard .col-3:eq(0)").addClass("active");
                            $("#newproject .bs-wizard .col-3:eq(1)").addClass("disabled");
                            $("#newproject .bs-wizard .col-3:eq(2)").addClass("disabled");
                            $("#newproject .bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 2:
                            $("#newproject .bs-wizard .col-3:eq(0)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(1)").addClass("active");
                            $("#newproject .bs-wizard .col-3:eq(2)").addClass("disabled");
                            $("#newproject .bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 3:
                            $("#newproject .bs-wizard .col-3:eq(0)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(1)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(2)").addClass("active");
                            $("#newproject .bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 4:
                            $("#newproject .bs-wizard .col-3:eq(0)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(1)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(2)").addClass("complete");
                            $("#newproject .bs-wizard .col-3:eq(3)").addClass("active");
                        break;
                    }
                }
            }

            function checkfooterbutton(pageindex){
                switch(pageindex){
                    case 1:
                        $("#newproject .bottom_panel .btn:eq(0)").addClass("disabled");
                        $("#newproject .bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Next");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Next");
                        }
                    break;
                    case 2:
                        $("#newproject .bottom_panel .btn:eq(0)").removeClass("disabled");
                        $("#newproject .bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Next");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Next");
                        }
                    break;
                    case 3:
                        $("#newproject .bottom_panel .btn:eq(0)").removeClass("disabled");
                        $("#newproject .bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Done");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Done");
                        }
                    break;
                    case 4:
                        $("#newproject .bottom_panel .btn:eq(0)").removeClass("disabled");
                        $("#newproject .bottom_panel .btn:eq(1)").text("Submit");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Done");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Done");
                        }
                    break;
                }
            }

            function showAddProjectPage(pageindex){
                $("#add_project_page_1, #add_project_page_2, #add_project_page_3, #add_project_page_4").css({"display":"none"});
                changeAddProjectProgress(pageindex);
                switch(pageindex){
                    case 1:
                        $("#add_project_page_1").removeAttr("style");
                        checkfooterbutton(pageindex);
                    break;
                    case 2:
                        $("#add_project_page_2").removeAttr("style");
                        checkfooterbutton(pageindex);
                    break;
                    case 3:
                        $("#add_project_page_3").removeAttr("style");
                        checkfooterbutton(pageindex);
                    break;
                    case 4:
                        $("#add_project_page_4").removeAttr("style");
                        checkfooterbutton(pageindex);
                    break;
                }
                $(".modal-body:visible").stop().animate({scrollTop:0}, 200, 'linear', function() { 

                });
            }

        //initial function

        $(function(){
            "use strict";

            var _initial_prompt = true;
            $("#addproject").click(function(e){
                e.preventDefault();
                $("#newproject").modal("show");
                
            });

            /*
            sb.connect('n60mnhlb9mn0awh03i7655ajq2owygvw', 'e8b31e1e4fa3eb83b91b4d1ca7b8d3155fe9b419', function(user, error) {
                if (error) {
                    console.log(error);
                    return;
                }else{
                    console.log(user);
                }
            });*/

            $("#newproject .bs-wizard .bs-wizard-dot").each(function(index,value){
                $(this).click(function(e){
                    e.preventDefault();
                    showAddProjectPage(index + 1);
                });
            });

            $(".addSampleButton").click(function(e){
                e.preventDefault();
                if($("#samples").val()){
                    addSample($("#samples").val());
                    $("#samples").val('');
                }
            });

            $(".edit_addSampleButton").click(function(e){
                e.preventDefault();
                if($("#edit_samples").val()){
                    addEditSample($("#edit_samples").val());
                    $("#edit_samples").val('');
                }
            });

            $('.input-group.date input').each(function(index,value){
                $(this).datepicker({
                    format: 'dd/mm/yy',
                    autoclose: true,
                    allowInputToggle: true,
                    startDate: "+0d"
                });
            });

            $("#newproject .bottom_panel button:eq(0)").click(function(e){
                e.preventDefault();
                formBack();
            });

            $("#newproject .bottom_panel button:eq(1)").click(function(e){
                e.preventDefault();
                formNext();
            });

            $("#editDialog .bottom_panel button:eq(0)").click(function(e){
                e.preventDefault();
                edit_formNext($(this).attr("data-project_id"));
            });

            //initial add project prompt
            showAddProjectPage(1);

            $("#ongoingloadmore").click(function(e){
                e.preventDefault();

                storify.core.getProjectListing(
                    "#ongoing_grid", 
                    "#ongoingloadmore", 
                    "You have not created a project yet. Create one now!", 
                    (index,value)=>{
                        var div = $(storify.template.createListItemBrandView(value, value.id, [{classname:"detail", label:"Details"}]));
                        div.find(".actions .detail").click(function(e){
                            e.preventDefault();
                            storify.brand.detail.viewDetail(value.id);
                        });
                        return div;
                    },
                    (rs)=>{
                        alert(rs.msg);
                    },
                    ()=>{
                        if( _initial_prompt && _project_id){
                            _initial_prompt = false;
                            storify.brand.detail.viewDetail(_project_id, function(){
                                <?php
                                    if(sizeof($pathquery) > 4){
                                        switch($pathquery[4]){
                                            case "submit":
                                            ?>$("#submission-tab").tab("show");<?php
                                            break;
                                            case "final":
                                            ?>$("#final-tab").tab("show");<?php
                                            break;
                                            case "creator":
                                            ?>$("#creator-tab").tab("show");<?php
                                            break;
                                            case "bounty":
                                            ?>$("#bounty-tab").tab("show");<?php
                                            break;
                                        }
                                    }
                                ?>
                            });
                        }else if(_creator){
                            if(_creator == "new"){

                            }else{
                                addInvitedCreator(_creator);
                            }                            
                            $("#newproject").modal("show");
                        }
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