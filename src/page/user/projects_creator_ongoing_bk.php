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
        var _project_id = 0;
        var _project_users = null;

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
                    .append(
                        $("<div>").addClass("description_block")
                            .append($("<h2>").text("Description Brief"))
                            .append($("<pre>").html(data.detail.description_brief))
                    )
                    .append(
                        $("<div>").addClass("description_block")
                            .append($("<h2>").text("Deliverables Brief"))
                            .append($("<pre>").html(data.detail.deliverable_brief))
                    )
                    .append(
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
                var div =  $("<div>").addClass("deliverable_item").attr({"data-id":data.id});
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

            var _gettingDetail = false;
            function viewDetail(project_id){
                if(_gettingDetail) return;
                _gettingDetail = true;

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data: {
                        method: "getDetail",
                        project_id: project_id
                    },
                    success:function(rs){
                        _gettingDetail = false;
                        console.log(rs);
                        if(rs.error){
                            console.log(rs);
                        }else{
                            _project_id = project_id;
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

        $(function(){
            "use strict";

            $("#ongoingloadmore").click(function(e){
                e.preventDefault();
                getProject();
            });

            if($("#ongoingloadmore").length){
                $("#ongoingloadmore").click();
            }
        });
    </script>
</body>
</html>