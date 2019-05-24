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
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/nc_custom.js"></script>
    <script src="/assets/js/SendBird.min.js"></script>
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
                            <div class="items list compact" id="ongoing_grid" data-page="1" data-sort="date" data-filter="ongoing">

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

    <div class="modal" tabindex="-1" role="dialog" id="newproject">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                  <div class="bs-wizard-info text-center">Main</div>
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
                  <div class="bs-wizard-info text-center">Creator</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 offset-md-1" id="add_project_page_1" style="display:none;">
                    <div class="form-group">
                        <label for="project_name" class="required">Project Name</label>
                        <input type="text" class="form-control" id="project_name" placeholder="Enter Project Name" required>
                        <!-- <input type="text" class="form-control" id="project_name" aria-describedby="projectNameHelp" placeholder="Enter Project Name">
                        <small id="projectNameHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                    </div>
                    <div class="form-group">
                        <label for="project_description" class="required">Description</label>
                        <textarea class="form-control" id="project_description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="project_short_description">Short Description</label>
                        <textarea class="form-control" id="project_short_description" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <labal for="project_brand">Brand</labal>
                        <select name="brand[]" id="brand" data-placeholder="Select Brand" class="customselect" data-enable-input=true nc-method="addBrand" multiple>
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
                        <select name="location[]" id="location" data-placeholder="Select Location" class="customselect" data-enable-input=true nc-method="addLocation" multiple>
                            <option value="">Select Location</option>
                    <?php
                        $country_tags = $main->getAllCountriesInUsed();
                        foreach($country_tags as $key=>$value){
                            //auto add the user current country
                            //get full name from short name ( since it is storing short name for user data )
                            $temp_val = "";
                            if($current_user_meta && $current_user_meta["city_country"] && sizeof($current_user_meta["city_country"])){
                                //get fullname
                                $temp_val = $user_country_ar[$current_user_meta["city_country"][0]];
                            }
                            if($value["name"] == $temp_val){
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
                        <label for="project_tag">Tags</label>
                        <select name="tag[]" id="tag" data-placeholder="Select Tag" class="customselect" data-enable-input=true nc-method="addTag" multiple>
                            <option value="">Select Tag</option>
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
                        <label for="closing_date">Submission Closing Date</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="closing_date">
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="number_of_photo">Number of Photos</label>
                        <input type="number" class="form-control" id="number_of_photo" placeholder="Enter Number of Photo" value="">
                    </div>
                    <div class="form-group">
                        <label for="number_of_video">Number of Videos</label>
                        <input type="number" class="form-control" id="number_of_video" placeholder="Enter Number of Photo" value="">
                    </div>
                    <div class="form-group">
                        <label for="video_length">Video Length</label>
                        <input type="number" class="form-control" id="video_length" placeholder="Enter Video Length">
                        <small id="videoLengthHelp" class="form-text text-muted">Video length in seconds</small>
                    </div>
                    <div class="form-group">
                        <label for="samples">Samples</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="samples" placeholder="URL Link to Sample Image">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary addSampleButton" type="button">Add Sample</button>
                            </div>
                        </div>
                    </div>
                    <div class="image-groups">
                        
                    </div>
                    <div class="form-group">
                        <label for="deliverable_brief">Deliverable Remark</label>
                        <textarea class="form-control" id="deliverable_brief" row="4"></textarea>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1" id="add_project_page_3" style="display:none">
                    <div class="form-group">
                        <label for="bounty_type">Bounty Type</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="bounty_cash" value="cash">
                            <label class="form-check-label" for="bounty_cash">Cash</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="bounty_gift" value="gift">
                            <label class="form-check-label" for="bounty_gift">Gift</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost_per_photo">Cost per photo</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-dollar"></span>
                            </span>
                            <input type="number" class="form-control" id="cost_per_photo" placeholder="Enter Cost per Photo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost_per_video">Cost per video</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-dollar"></span>
                            </span>
                            <input type="number" class="form-control" id="cost_per_video" placeholder="Enter Cost per Video">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gift_name">Gift</label>
                        <input type="text" class="form-control" id="gift_name" placeholder="Enter Gift" value="">
                    </div>
                </div>
                <div class="col-md-10 offset-md-1" id="add_project_page_4">
                    <div class="form-group">
                        <label for="invitation_closing_date">Invitation closing date</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="invitation_closing_date">
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="creators">Creators</label>
                        <div class="input-group mb-3 creator-input">
                            <select name="creators[]" class="form-control customselect" id="creators" data-placeholder="Select Creator" multiple>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary addCreatorButton" type="button">Add Creator</button>
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
                            <section class="section-container invite-section">
                                <h1>Creator Management</h1>
                                <div class="form-group">
                                    <div class="input-group mb-3 invite-input">
                                        <select name="invite[]" class="form-control customselect" id="invite" data-placeholder="Select Creator" multiple>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary sendInviteButton" type="button">Invite</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="invite-group">
                                    
                                </div>
                                <div class="invite-summary">
                                    
                                </div>
                            </section>
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
              <div class="modal-body deliverable_items">
              </div>
              <div class="modal-footer">

              </div>
        </div>
      </div>
    </div>

    <modal class="modal" tabindex="-1" role="dialog" id="single_invitation_dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Manage Invitation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <div class="profile-image"></div>
                    </div>
                    <div class="col-9">
                        <div><strong></strong></div>
                        <div>Status : <span class="status">Waiting</span></div>
                        <div class="remark"></div>
                    </div>
                </div> 
            </div>
          </div>
          <div class="modal-footer">
            <div class="text-center">
                <button class="btn btn-primary">invite</button>
            </div>
          </div>
        </div>
      </div>
    </modal>

    <div class="modal" tabindex="-1" role="dialog" id="reject_submission">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Reject Submission</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="modal-body deliverable_items">
                <h3>Provide a reason why the submission is rejected so creator can make amendment according to you comments. (optional)</h3>
                <textarea class="form-control" rows="4"></textarea>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary small" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary small confirmreject">Reject</button>
              </div>
        </div>
      </div>
    </div>

    <modal class="modal fullscreen modal-loading" tabindex="-1" role="dialog" id="loading">
        <div class="d-flex justify-content-center">
          <i class="fa fa-spinner fa-spin"></i>
        </div>
    </modal>

    <!--end page-->
    <script type="text/javascript">
            
            var APP_ID = '68CE9863-C07D-4505-A659-F384AB1DE478';
            var sb = new SendBird({appId: APP_ID});
            var _project_id = 0;
            var _project_users = null;
        //loading
        
            function showloading(){
                $("#loading").modal("show");
            }

            function hideloading(){
                $("#loading").modal("hide");
            }

        //user management
        
            var _gettingUsers = false;
            function getAllUsers(onComplete){
                if(_gettingUsers) return;
                _gettingUsers = true;
                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        method:"getUsers",
                        project_id:_project_id
                    },
                    success:function(rs){
                        _gettingUsers = false;
                        _project_users = rs.data;
                        if(onComplete) onComplete();
                    }
                })
            }

            function getUserDetail(user_id){
                var user_obj = null;
                $.each(_project_users, function(index,value){
                    if(value.user_id == user_id){
                        user_obj = value;
                    }
                });
                return user_obj;
            }

        //invitation management
            
            var _updatingInvitation = false;
            $("#single_invitation_dialog .modal-footer button").click(function(e){
                e.preventDefault();
                //check button text
                var command_type = 0,
                    id = $(this).attr("data-id");
                switch($("#single_invitation_dialog .modal-footer button").text()){
                    case "withdraw invitation":
                        command_type = 1;
                    break;
                    case "resend invitation":
                        command_type = 2;
                    break;
                    case "remove creator from project":
                        command_type = 3;
                    break;
                }
                console.log("command", command_type);
                if(command_type && !_updatingInvitation){
                    if(_updatingInvitation) return;
                    _updatingInvitation = true;
                    $.ajax({
                        type:"POST",
                        dataType:"json",
                        data:{
                            project_id:_project_id,
                            command_type:command_type,
                            id:id,
                            method:"editInvitation"
                        },
                        success:function(rs){
                            _updatingInvitation = false;
                            getInvitationList(function(){
                                $("#single_invitation_dialog").modal("hide");
                            });
                        }
                    })
                }
            });

            function resetInvitation(){
                $("#invite")[0].selectize.clear(true);
                $(".invite-group").empty();
                $(".invite-summary").empty();
            }

            function displaySingleInvite(data){
                $("#single_invitation_dialog .modal-body .profile-image").css({"background-image":"url("+data.profile_image+")"});
                $("#single_invitation_dialog .modal-body strong").text(data.display_name);
                var a = $("#single_invitation_dialog .modal-body .status"),
                    b = $("#single_invitation_dialog .modal-footer button");
                switch(data.invitation_status){
                    case "pending":
                        a.addClass("item-pending").text("waiting");
                        b.text("withdraw invitation").attr({"data-id":data.invitation_id});
                    break;
                    case "rejected":
                        a.addClass("item-rejected").text("rejected");
                        b.text("resend invitation").attr({"data-id":data.user_id});
                    break;
                    case "accepted":
                        a.addClass("item-accepted").text("accepted");
                        b.text("remove creator from project").attr({"data-id":data.user_id});
                    break;
                }
                if(data.remark){
                    $("#single_invitation_dialog .remark").css({display:"block"}).text(data.remark);
                }else{
                    $("#single_invitation_dialog .remark").css({display:"none"}).text("");
                }
                $("#single_invitation_dialog").modal();
            }

            function createInviteItem(data){
                /*
                <div class="invite-item item-accepted">
                    <div class="profile-image" style="background-image:url(https://cdn.storify.me/data/uploads/2019/02/willy__liu.jpg)">
                    </div>
                </div>
                 */
                var a = $("<div>").addClass("invite-item")
                            .append($("<div>").addClass("profile-image").css({"background-image":"url("+data.profile_image+")"}))
                            .click(function(e){
                                e.preventDefault();
                                displaySingleInvite(data);
                            });

                switch(data.invitation_status){
                    case "pending":
                        a.addClass("item-pending").attr({title:"waiting"});
                    break;
                    case "accepted":
                        a.addClass("item-accepted").attr({title:"accepted"});
                    break;
                    case "rejected":
                        a.addClass("item-rejected").attr({title:"rejected"});
                    break;
                }

                return a;
            }

            function displayInvitation(data, onComplete){
                $(".invite-group").empty();

                var accepted_count = 0,
                    rejected_count = 0,
                    waiting_count = 0;
                $.each(data, function(index,value){
                    $(".invite-group").append(createInviteItem(value));
                    switch(value.invitation_status){
                        case "pending":
                        case "waiting":
                            waiting_count++;
                        break;
                        case "accepted":
                            accepted_count++;
                        break;
                        case "rejected":
                            rejected_count++;
                        break;
                    }
                });

                $(".invite-summary").empty();
                if(accepted_count){
                    $(".invite-summary").append($("<span>").addClass("item-accepted").text(accepted_count+" accepted"));
                }
                if(waiting_count){
                    $(".invite-summary").append($("<span>").addClass("item-pending").text(waiting_count+" waiting"));
                }
                if(rejected_count){
                    $(".invite-summary").append($("<span>").addClass("item-rejected").text(rejected_count+" rejected"));
                }
                if(onComplete)onComplete();
            }

            var _gettingInvitation = false;
            function getInvitationList(onComplete){
                if(_gettingInvitation) return;
                resetInvitation();
                _gettingInvitation = true;
                $.ajax({
                    type:"POST",
                    dataType:'json',
                    data:{
                        project_id:_project_id,
                        method:"getInvitationList"
                    },
                    success:function(rs){
                        _gettingInvitation = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            //console.log(rs);
                            displayInvitation(rs.data, onComplete);
                        }
                    }
                });
            }
            $("#invite").selectize({
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
                            var tempitem = $("#creators")[0].selectize.options[value]
                            addInvitedCreator(tempitem);
                        });
                    }
                    $("#creators")[0].selectize.clear(true);
                    */
                   //get invitation list
                }
            });

            var _sendingInvitation = false;
            $(".sendInviteButton").click(function(e){
                e.preventDefault();
                //check the selected value
                var a = $("#invite").val();
                if(a && a.length){
                    if(_sendingInvitation) return;
                    _sendingInvitation = true;
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data:{
                            project_id:_project_id,
                            userids:a,
                            method:"sendInvitation"
                        },
                        error:function(request, status, error){
                            _sendingInvitation = false;
                        },
                        success:function(rs){
                            _sendingInvitation = false;
                            getInvitationList();
                        }
                    });
                }
            });

        //deliverable management
            
            var _submission_response = false;
            function responseToSubmission($submission_id, $action, $action_remark){
                if(_submission_response) return;
                _submission_response = true;

                $.ajax({
                    type:"POST",
                    dataType:'json',
                    data:{
                        method:"response_submission",
                        submission_id:$submission_id,
                        status:$action,
                        status_remark:$action_remark
                    },
                    error:function(){
                        callback();
                    },
                    success:function(res){
                        _submission_response = false;
                        if(res.error){
                            alert(res.msg);
                        }else{
                            getDeliverableList(function(){
                                $("#reject_submission").modal("hide");
                            });
                        }
                    }
                });
            }

            $("#reject_submission button.confirmreject").click(function(e){
                e.preventDefault();
                if($("#reject_submission button.confirmreject").attr("data-id")){
                    responseToSubmission($("#reject_submission button.confirmreject").attr("data-id"), "rejected", $("#reject_submission textarea").val());
                }
            });

            function displayDeliverables(data, onComplete){
                $(".deliverable-groups").empty();
                var photo_type = 0,
                    video_type = 0;
                $.each(data, function(index,value){
                    var a = $("<div>").addClass("deliverable-group"),
                        b = $("<div>").addClass("deliverable-creator-group"),
                        c = $("<div>").addClass("deliverable-creator-summary"),
                        creator_not_submit = null,
                        tempname = "",
                        count_done = 0,
                        count_pending = 0,
                        count_reject = 0,
                        tempuser = _project_users.slice(0);

                    if(value.type == "photo"){
                        photo_type++;
                        tempname = "Photo #"+photo_type;
                    }else{
                        video_type++;
                        tempname = "Video #"+video_type;
                    }
                    a.append($("<h3>").text(tempname));
                    if(value.remark){
                        a.append($("<div>").addClass("deliverable-remark").text(value.remark));
                    }


                    $.each(value.data, function(index2,value2){
                        var d = $("<div>").addClass("deliverable-creator-item"),
                            temp_creator = getUserDetail(value2.user_id),
                            temp_remark = null,
                            temp_status = null,
                            temp_action = null;

                        tempuser = tempuser.filter(function(creator){
                            return creator.user_id != value2.user_id;
                        });

                        if(value2.remark){
                            temp_remark = $("<div>").addClass("single_block")
                                                .append($("<label>").text("Remark"))
                                                .append(document.createTextNode(value2.remark));
                        }
                        if(value2.status == "accepted" || value2.status == "rejected"){
                            temp_status = $("<div>").addClass("single_block")
                                                .append($("<label>").text("Status"))
                                                .append($("<span>").addClass("item-status").text(value2.status));
                            if(value2.status == "accepted"){
                                count_done++;
                                d.addClass("item-accepted");
                            }else{
                                count_reject++;
                                d.addClass("item-rejected");
                            }
                        }else{
                            count_pending++;
                            d.addClass("item-pending");
                            temp_action = $("<div>").addClass("bottom_panel")
                                            .append(
                                                $("<button>").addClass("btn btn-success small")
                                                    .text("Accept")
                                                    .click(function(e){
                                                        //accept submission
                                                        responseToSubmission(value2.submission_id, "accepted","");
                                                    })
                                                )
                                            .append(
                                                $("<button>").addClass("btn btn-danger small")
                                                    .text("Reject")
                                                    .click(function(e){
                                                        //reject submission
                                                        $("#reject_submission textarea").val("");
                                                        $("#reject_submission button.confirmreject").attr({"data-id":value2.submission_id});
                                                        $("#reject_submission").modal("show");
                                                    })
                                            );
                        }
                        d.append($("<div>").addClass("top_panel")
                                .append($("<div>").addClass("right-cont")
                                    .append($("<div>").addClass("profile-image")
                                        .attr({"title":temp_creator.display_name})
                                        .css({"background-image":"url("+temp_creator.profile_image+")"})
                                    )
                                )
                                .append($("<div>").addClass("left-cont")
                                    .append($("<small>").text(value2.submit_tt))
                                    .append($("<div>").addClass("single_block")
                                        .append($("<label>").text("Submission"))
                                        .append($("<input>").attr({type:"text",readonly:true})
                                            .val(value2.URL)
                                            .click(function(e){
                                                e.preventDefault();
                                                this.setSelectionRange(0, this.value.length);
                                            })
                                        )
                                    )
                                    .append(temp_remark)
                                    .append(temp_status)
                                )
                            )
                            .append(temp_action);
                        b.append(d);
                    });

                    if(count_done){
                        c.append($("<span>").addClass("item-accepted").text(count_done+" accepted"));
                    }
                    if(count_pending){
                        c.append($("<span>").addClass("item-pending").text(count_pending+" waiting"));
                    }
                    if(count_reject){
                        c.append($("<span>").addClass("item-rejected").text(count_reject+" rejected"));
                    }

                    tempuser = tempuser.filter(function(creator){
                        return creator.role != "admin";
                    });

                    if(tempuser.length){
                        var temp_submit_group = $("<div>").addClass("not-submit-group");
                        $.each(tempuser, function(index3,value3){
                            temp_submit_group.append(
                                $("<div>").addClass("profile-image")
                                    .attr({title:value3.display_name})
                                    .css({"background-image":"url("+value3.profile_image+")"})
                            );
                        });

                        creator_not_submit = $("<div>").addClass("not-submit");
                        creator_not_submit.append($("<h3>").text("Waiting for submit"));
                        creator_not_submit.append(temp_submit_group);
                    }

                    a.append(b);
                    a.append(c);
                    a.append(creator_not_submit);
                    $(".deliverable-groups").append(a);
                });

                if(onComplete)onComplete();
            }

            var _gettingDeliverable = false;
            function getDeliverableList(onComplete){
                if(_gettingDeliverable) return;
                _gettingDeliverable = true;
                $.ajax({
                    type:"POST",
                    dataType:'json',
                    data:{
                        project_id:_project_id,
                        method:"getDeliverable"
                    },
                    error:function(request, status, error){
                        _gettingDeliverable = false;
                    },
                    success:function(rs){
                        _gettingDeliverable = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            displayDeliverables(rs.data, onComplete);
                        }
                    }
                })
            }

        //add project related
        
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
                    })
                },
                onItemAdd: function(value, item){

                    var selected = $("#creators")[0].selectize.getValue();
                    if(selected.length){
                        $.each(selected, function(index,value){
                            //add invited creator
                            var tempitem = $("#creators")[0].selectize.options[value]
                            addInvitedCreator(tempitem);
                        });
                    }
                    $("#creators")[0].selectize.clear(true);
                }
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

            function addSample(url){
                $(".image-groups").append(
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

            function isPageReady(pageindex, silence){
                var pageReady = true,
                    msg = "";
                if(pageindex == 1){
                    //page 1
                    $('#add_project_page_1 *[required]').each(function(index,value){
                        if($(value).val() == ""){
                            pageReady = false;
                            msg = "Please fill in all required fields.";
                        }
                    });
                }else if(pageindex == 2){
                    //page 2
                    if(!$("#closing_date").val()){
                        //closing date not set
                        pageReady = false;
                        msg = "Please fill in the closing date";
                    }else if(!(parseInt($("#number_of_photo").val()?$("#number_of_photo").val():0, 10) + parseInt($("#number_of_video").val()?$("#number_of_video").val():0, 10))){
                        //number of video or photos is not set.
                        pageReady = false;
                        msg = "Please fill in the number or photo / video required to deliver";
                    }
                }else if(pageindex == 3){
                    if(!$("#bounty_cash").is(":checked") && !$("#bounty_gift").is(":checked")){
                        //if both not set
                        pageReady = false;
                        msg = "Please select either cash or gift or both for bounty type.";
                    }else if($("#bounty_cash").is(":checked") && (parseInt($("#number_of_photo").val()?$("#number_of_photo").val():0,10)?true:false) && !(parseInt($("#cost_per_photo").val()?$("#cost_per_photo").val():0, 10))){
                        pageReady = false;
                        msg = "Please enter cost per photo";
                    }else if($("#bounty_cash").is(":checked") && (parseInt($("#number_of_video").val()?$("#number_of_video").val():0,10)?true:false) && !(parseInt($("#cost_per_video").val()?$("#cost_per_video").val():0, 10))){
                        pageReady = false;
                        msg = "Please enter cost per video";
                    }else if($("#bounty_gift").is(":checked") && $("#gift_name").val() == ""){
                        //gift, check if gift name is given
                        pageReady = false;
                        msg = "Please fill in the name of the gift";
                    }
                }else if(pageindex == 4){
                    //check if got at least 1 creator
                    if($(".creator-groups .creator-item").length){
                        pageReady = true;
                    }else{
                        msg = "You may invite creator later";
                    }
                }
                if(!silence && msg){
                    alert(msg);
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
                        description_brief:$("#project_description").val(),
                        deliverable_brief:$("#deliverable_brief").val(),
                        other_brief:"",
                        short_description:$("#project_short_description").val(),
                        no_of_photo:$("#number_of_photo").val(),
                        no_of_video:$("#number_of_video").val(),
                        video_length:$("#video_length").val(),
                        bounty_type:( $("#bounty_cash").is(":checked") && $("#bounty_gift").is(":checked") ) ? 'both': $("#bounty_cash").is(":checked") ? 'cash' : 'product',
                        cost_per_photo:$("#cost_per_photo").val(),
                        cost_per_video:$("#cost_per_video").val(),
                        reward_name:$("#gift_name").val(),
                        closing_date:$("#closing_date").val(),
                        invitation_closing_date:""
                    },
                    brand:$("#brand").val(),
                    location:$("#location").val(),
                    tag:$("#tag").val(),
                    deliverable:[],
                    samples:$(".image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                    invitation:$(".creator-groups .creator-item").map(function(i,v){ return $(v).attr('data-id'); }).get()
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

                        }else{

                        }
                    }
                })
            }

            var addProjectCurrentProgress = 0;
            function changeAddProjectProgress(progressindex){
                if(progressindex > addProjectCurrentProgress){
                    $(".bs-wizard .col-3").removeClass("complete active disabled");
                    addProjectCurrentProgress = progressindex;
                    switch(progressindex){
                        case 1:
                            $(".bs-wizard .col-3:eq(0)").addClass("active");
                            $(".bs-wizard .col-3:eq(1)").addClass("disabled");
                            $(".bs-wizard .col-3:eq(2)").addClass("disabled");
                            $(".bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 2:
                            $(".bs-wizard .col-3:eq(0)").addClass("complete");
                            $(".bs-wizard .col-3:eq(1)").addClass("active");
                            $(".bs-wizard .col-3:eq(2)").addClass("disabled");
                            $(".bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 3:
                            $(".bs-wizard .col-3:eq(0)").addClass("complete");
                            $(".bs-wizard .col-3:eq(1)").addClass("complete");
                            $(".bs-wizard .col-3:eq(2)").addClass("active");
                            $(".bs-wizard .col-3:eq(3)").addClass("disabled");
                        break;
                        case 4:
                            $(".bs-wizard .col-3:eq(0)").addClass("complete");
                            $(".bs-wizard .col-3:eq(1)").addClass("complete");
                            $(".bs-wizard .col-3:eq(2)").addClass("complete");
                            $(".bs-wizard .col-3:eq(3)").addClass("active");
                        break;
                    }
                }
            }

            function checkfooterbutton(pageindex){
                switch(pageindex){
                    case 1:
                        $(".bottom_panel .btn:eq(0)").addClass("disabled");
                        $(".bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Next");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Next");
                        }
                    break;
                    case 2:
                        $(".bottom_panel .btn:eq(0)").removeClass("disabled");
                        $(".bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Next");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Next");
                        }
                    break;
                    case 3:
                        $(".bottom_panel .btn:eq(0)").removeClass("disabled");
                        $(".bottom_panel .btn:eq(1)").text("Next");
                        if(isPageReady(pageindex, true)){
                            //if page ready, can move to next
                            //$(".bottom_panel .btn:eq(1)").removeClass("disabled").text("Done");    
                        }else{
                            //$(".bottom_panel .btn:eq(1)").addClass("disabled").text("Done");
                        }
                    break;
                    case 4:
                        $(".bottom_panel .btn:eq(0)").removeClass("disabled");
                        $(".bottom_panel .btn:eq(1)").text("Done");
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
            }

        // create detail functions
            function createDetail(data){
                //bramd
                var brandtext = [];
                if(data.summary.brand && data.summary.brand.length){
                    $.each(data.summary.brand, function(index, value){
                        brandtext.push(value.name);
                    });
                }

                //locations
                var locationtext = [];
                if(data.summary.location && data.summary.location.length){
                    $.each(data.summary.location, function(index, value){
                        locationtext.push(value.name);
                    });
                }

                //tags
                var tagstext = [];
                if(data.summary.tag && data.summary.tag.length){
                    $.each(data.summary.tag, function(index, value){
                        tagstext.push(value.name);
                    });   
                }

                //deliverable detail
                var deliverable_ar = [], photocount = 1, videocount = 1;
                $.each(data.delivery, function(index,value){
                    if(value.remark){
                        if(value.type == "photo"){
                            deliverable_ar.push([
                                "photo #" + photocount,
                                value.remark
                                ]);
                            photocount++;
                        }
                        if(value.type == "video"){
                            deliverable_ar.push([
                                "video #" + photocount,
                                value.remark
                                ]);
                            photocount++;
                        }
                    }
                });

                var deliverable_block =$("<div>").addClass("description_block")
                            .append($("<h2>").text("Deliverables"))
                            .append($("<p>").text(data.summary.deliverables)
                                            .append(data.detail.no_of_video != "0" && data.detail.video_length ? document.createTextNode(" ("+data.detail.video_length+"s)") : null)
                                );

                if(deliverable_ar.length){
                    deliverable_block.append($("<h3>").text("Details"));

                    $.each(deliverable_ar, function(index, value){
                        deliverable_block.append($("<div>").addClass("single_row")
                                                    .append($("<label>").text(value[0]))
                                                    .append(document.createTextNode(value[1]))
                            );
                    });
                }

                //sample block
                var sample_block = null, sample_inner_block = [];

                if(data.sample.length){
                    sample_block = $("<div>").addClass("description_block")
                                        .append($("<h2>").text("Samples"));;

                    sample_inner_block = $("<div>").addClass("sample_groups");

                    $.each(data.sample, function(index, value){
                        sample_inner_block.append(
                            $("<a>").addClass("sample_clickable")
                                .attr({href:value.URL, target:"_blank"})
                                .append($("<img>").attr({src:value.URL}))
                            );
                    });

                    sample_block.append(sample_inner_block);
                }

                var bounty_block = null, bounty_ul;
                bounty_block = $("<div>").addClass("description_block");
                bounty_block.append($("<h2>").text("Bounty"));

                bounty_ul = $("<ul>");
                if(data.detail.bounty_type == "both"){
                    bounty_ul.append(
                        $("<li>").append($("<label>").text("Cash"))
                                .append(document.createTextNode("$"+data.summary.bounty[0].value+" ( $"+data.detail.cost_per_photo+" for each "))
                                .append($("<i>").addClass("fa fa-picture-o"))
                                .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                                .append($("<i>").addClass("fa fa-video-camera"))
                                .append(document.createTextNode(" )"))
                    );

                    bounty_ul.append(
                        $("<li>").append($("<label>").text("Gift"))
                                .append(document.createTextNode(data.summary.bounty[1].value))
                    );
                }else if(data.detail.bounty_type == "cash"){
                    bounty_ul.append(
                        $("<li>").append($("<label>").text("Cash"))
                                .append(document.createTextNode("$"+data.summary.bounty[0].value+" ( $"+data.detail.cost_per_photo+" for each "))
                                .append($("<i>").addClass("fa fa-picture-o"))
                                .append(document.createTextNode(", $"+data.detail.cost_per_video+" for each "))
                                .append($("<i>").addClass("fa fa-video-camera"))
                                .append(document.createTextNode(" )"))
                    );
                }else{
                    bounty_ul.append(
                        $("<li>").append($("<label>").text("Gift"))
                                .append(document.createTextNode(data.summary.bounty[0].value))
                    );
                }

                bounty_block.append(bounty_ul);


                var cont = $("#detailcontent");
                cont.empty();
                cont.append($("<h1>").text("Detail"));
                cont.append(
                    $("<div>").addClass("row")
                        .append($("<div>").addClass("col-md-7")
                                    .append($("<h2>").text(data.name))
                            )
                        .append($("<div>").addClass("col-md-5 smallertext")
                                    .append($("<div>").addClass("text-right")
                                                .append($("<label>").text("Closing Date"))
                                                .append(document.createTextNode(data.summary.closing_date))
                                        )
                                    .append($("<div>").addClass("text-right")
                                                .append($("<label>").text("Invitation Closing Date"))
                                                .append(document.createTextNode(data.summary.invitation_closing_date))
                                        )
                            )
                    )
                    .append(
                        $("<div>").addClass("description_block")
                            .append($("<div>").addClass("single_row")
                                        .append($("<label>").text("Brand"))
                                        .append(document.createTextNode(brandtext))
                                )
                            .append($("<div>").addClass("single_row")
                                        .append($("<label>").text("Location"))
                                        .append(document.createTextNode(locationtext))
                                )
                            .append($("<div>").addClass("single_row")
                                        .append($("<label>").text("Tag"))
                                        .append(document.createTextNode(tagstext))
                                )
                    )
                    .append($("<hr>"))
                    .append(data.detail.description_brief == "" ? null :
                        $("<div>").addClass("description_block")
                            .append($("<h2>").text("Description Brief"))
                            .append($("<pre>").html(data.detail.description_brief))
                    )
                    .append(data.detail.deliverable_brief == "" ? null :
                        $("<div>").addClass("description_block")
                            .append($("<h2>").text("Deliverables Brief"))
                            .append($("<pre>").html(data.detail.deliverable_brief))
                    )
                    .append(data.detail.other_brief == "" ? null :
                        $("<div>").addClass("description_block")
                            .append($("<h2>").text("Additional Brief"))
                            .append($("<pre>").html(data.detail.other_brief))
                    )
                    .append($("<hr>"))
                    .append(deliverable_block)
                    .append(sample_block)
                    .append($("<hr>"))
                    .append(bounty_block);

                return cont;
            }

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function createHistoryBlock(data){
                return $("<div>").addClass("history_item")
                    .append($("<div>").addClass("submission_block")
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("URL"))
                                            .append($("<div>").addClass("value").text(data.URL))
                                    )
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("Instruction"))
                                            .append($("<div>").addClass("value").text(data.remark))
                                    )
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("Submit Time"))
                                            .append($("<div>").addClass("value").text(data.submit_tt))
                                    )
                        )
                    .append($("<div>").addClass("reply_block")
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("Status"))
                                            .append($("<div>").addClass("value").text(data.response_status))
                                    )
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("Reason"))
                                            .append($("<div>").addClass("value").text(data.response_remark))
                                    )
                                .append($("<div>").addClass("item_row")
                                            .append($("<div>").addClass("label").text("Reply Time"))
                                            .append($("<div>").addClass("value").text(data.response_tt))
                                    )
                        );
            }

            var photo_count = 0;
            var video_count = 0;
            function createDeliverableBlock(data){
                var temptitle = null;
                if(data.type == "photo"){
                    photo_count++;
                    temptitle = "Photo #"+photo_count;
                }else if(data.type == "video"){
                    video_count++;
                    temptitle = "Video #"+video_count;
                }
                //check status
                var statusDisplay = null;
                if(data.response_status == "accepted"){
                    statusDisplay = $("<span>").addClass("value green").text("accepted");
                }else if(data.response_status == "rejected"){
                    statusDisplay = $("<span>").addClass("value red").text("rejected");
                }else if(data.URL == null || data.URL == ""){
                    statusDisplay = $("<span>").addClass("value").text("");
                }else{
                    statusDisplay = $("<span>").addClass("value blue").text("pendding");
                }

                var history_block = null;
                if(data.history_id){
                    history_block = $("<div>").addClass("history_block")
                                        .append($("<a>").attr({href:"#"}).text("history").click(function(e){
                                            e.preventDefault();
                                            var $this = $(this);
                                            getDeliverableHistory(data.deliverable_id, function(data){
                                                var tempparent = $this.parent(".history_block");
                                                $this.remove();
                                                $.each(data, function(index,value){
                                                    var item = createHistoryBlock(value);
                                                    tempparent.append(item);
                                                });
                                            });
                                        }));
                }else{

                }
                var div = $("<div>").addClass("deliverable_item").attr({"data-id":data.id});
                    div.append(
                        $("<div>").addClass("main_block")
                            .append($("<h3>").text(temptitle))
                            .append($("<div>").addClass("remark").text(data.remark))
                            .append($("<div>").addClass("input_group")
                                        .append($("<div>").addClass("label").text("URL"))
                                        .append($("<input>").addClass("form-control").val(data.URL))
                                )
                            .append($("<div>").addClass("input_group")
                                    .append($("<div>").addClass("label").text("Instruction"))
                                    .append($("<textarea>").attr({row:4}).val(data.submission_remark))
                            )
                    )
                    .append(
                        $("<div>").addClass("reply_block")
                            .append($("<div>").addClass("input_group")
                                            .append($("<div>").addClass("label").text("Status"))
                                            .append(statusDisplay)
                                            .append($("<button>").addClass("btn btn-primary save-btn").text("Save"))
                                )
                            .append($("<div>").addClass("input_group")
                                            .append($("<div>").addClass("reason_value").text(data.response_remark))
                                )
                    )
                    .append(history_block);

                return div;
            }

            var _getHistory = false;
            function getDeliverableHistory(deliverable_id, onComplete){
                if(_getHistory) return;

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data: {
                        method: "getDeliverableHistory",
                        deliverable_id : deliverable_id
                    },
                    success:function(rs){
                        _getHistory = false;
                        console.log(rs);
                        if(rs.error){

                        }else{
                            if(onComplete)onComplete(rs.data);
                        }
                    }
                });
            }

            var _gettingDeliverable = false;
            function getDeliverable(project_id){
                if(_gettingDeliverable) return;
                _gettingDeliverable = true;

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data: {
                        method: "getDeliverable",
                        project_id : project_id
                    },
                    success:function(rs){
                        _gettingDeliverable = false;
                        if(rs.error){
                            
                        }else{
                            photo_count = 0;
                            video_count = 0;
                            $("#deliverable_dialog .deliverable_items").empty();
                            $.each(rs.data, function(index,value){
                                var item = createDeliverableBlock(value);
                                $("#deliverable_dialog .deliverable_items").append(item);
                            });
                            $("#deliverable_dialog").modal();
                        }
                    }
                });
            }

            var _gettingDetail = false;
            function viewDetail(project_id){
                if(_gettingDetail) return;
                _gettingDetail = true;
                showloading();
                $(".invite-group").empty();
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data: {
                        method: "getDetail",
                        project_id: project_id
                    },
                    success:function(rs){
                        _gettingDetail = false;
                        hideloading();
                        console.log(rs);
                        if(rs.error){

                        }else{
                            _project_id = project_id;
                            getInvitationList();
                            getAllUsers(function(){
                                getDeliverableList();
                            });
                            createDetail(rs.data);
                            $("#detailModal").modal();
                        }
                    }
                });
            }

            function createPrototypeItem(data_pair, project_id){
                var div = $("<div>").addClass("item prototype_item").css({"background-color":"#FFF","padding":"20px"});

                $.each(data_pair, function(index,value){
                    div.append(
                        $("<div>").addClass("clearfix")
                            .append($("<label>").text(index))
                            .append($("<span>").html(value))
                        )
                });

                //accept and deny button
                div.append(
                    $("<div>").addClass("")
                        .append(
                            $("<a>").addClass("btn btn-primary text-caps btn-rounded btn-framed").attr({href:"#"})
                                .css({"margin-right":"5px"})
                                .text("Detail")
                                .click(function(e){
                                    e.preventDefault();
                                    viewDetail(project_id);
                                })
                            )
                        .append(
                            $("<a>").addClass("btn btn-primary text-caps btn-rounded btn-framed").attr({href:"#"})
                                .css({"margin-right":"5px"})
                                .text("Submission")
                                .click(function(e){
                                    e.preventDefault();
                                    getDeliverable(project_id);
                                    //console.log("open submission", project_id);
                                })
                            )
                );

                return div;
            }

        //get project list

            var _gettingProject = false;
            function getProject(){
                if(_gettingProject) return;
                _gettingProject = true;

                var $grid = $("#ongoing_grid"),
                    cur_page = parseInt($grid.attr("data-page"), 19),
                    sort = $grid.attr("data-sort"),
                    filter = $grid.attr("data-filter");

                $("#ongoingloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        method:"getProject",
                        filter:filter,
                        page:cur_page,
                        sort:sort
                    },
                    success:function(rs){
                        _gettingProject = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            $grid.attr({'data-page':rs.result.page});
                            $("#ongoingloadmore").text("Load More").blur();
                            if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                                $("#ongoingloadmore").css({display:"inline-block"});
                            }else{
                                $("#ongoingloadmore").css({display:"none"});
                            }

                            $.each(rs.result.data, function(index,value){
                                //bounty
                                var bountytext = "";
                                if(value.summary.bounty.length == 2){
                                    bountytext = "$" + value.summary.bounty[0].value + " & " + value.summary.bounty[1].value;
                                }else{
                                    if(value.summary.bounty[0].type == "cash"){
                                        bountytext = "$" + value.summary.bounty[0].value;
                                    }else{
                                        bountytext = value.summary.bounty[0].value;
                                    }
                                }

                                //bramd
                                var brandtext = [];
                                if(value.summary.brand && value.summary.brand.length){
                                    $.each(value.summary.brand, function(index, value){
                                        brandtext.push(value.name);
                                    });
                                }

                                //locations
                                var locationtext = [];
                                if(value.summary.location && value.summary.location.length){
                                    $.each(value.summary.location, function(index, value){
                                        locationtext.push(value.name);
                                    });
                                }

                                //tags, missing now
                                var tagstext = [];
                                if(value.summary.tag && value.summary.tag.length){
                                    $.each(value.summary.tag, function(index, value){
                                        tagstext.push(value.name);
                                    });   
                                }

                                var $temp = createPrototypeItem({
                                    "Name":value.name,
                                    "Description":value.summary.description,
                                    "Project Closing Date":value.summary.closing_date,
                                    "Invitation Closing Date":value.summary.invitation_closing_date,
                                    "Bounty":bountytext,
                                    "Deliverables":value.summary.deliverables,
                                    "Deliverables %":value.deliverables.done,
                                    "Location":locationtext.join(", "),
                                    "Tags":tagstext.join(", "),
                                    "Brand":brandtext.join(", ")
                                }, value.id);
                                $grid.append($temp);
                                ScrollReveal().reveal($temp, slideUp);
                            });
                        }
                    }
                })
            }

        //initial function

        $(function(){
            "use strict";

            $("#addproject").click(function(e){
                e.preventDefault();
                $("#newproject").modal();
                
            });

            $(".bs-wizard .bs-wizard-dot").each(function(index,value){
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


            $('.input-group.date input').each(function(index,value){
                $(this).datepicker();
            });

            $("#ongoingloadmore").click(function(e){
                e.preventDefault();
                getProject();
            });

            $(".bottom_panel button:eq(0)").click(function(e){
                e.preventDefault();
                formBack();
            });

            $(".bottom_panel button:eq(1)").click(function(e){
                e.preventDefault();
                formNext();
            });

            //initial add project prompt
            showAddProjectPage(1);

            if($("#ongoingloadmore").length){
                $("#ongoingloadmore").click();
            }
        });
    </script>
</body>
</html>