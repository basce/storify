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
    <!-- Main Quill library -->
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
<?=get_option("custom_settings_header_js")?>
    <style type="text/css">
        .add_addSampleButton{
            background-color:black;
        }
    </style>
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
                        <h1>Create Project</h1>
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
                        <div class="col-md-9" id="newproject">
                            <div class="row bs-wizard">
                                
                                <div class="col-3 bs-wizard-step disabled">
                                  <div class="text-center bs-wizard-stepnum"><span class="hidden-md-down">Step 1</span></div>
                                  <div class="progress"><div class="progress-bar"></div></div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center"><span class="hidden-md-down">Brief</span></div>
                                </div>
                                
                                <div class="col-3 bs-wizard-step disabled"><!-- complete -->
                                  <div class="text-center bs-wizard-stepnum"><span class="hidden-md-down">Step 2</span></div>
                                  <div class="progress"><div class="progress-bar"></div></div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center"><span class="hidden-md-down">Deliverable</span></div>
                                </div>
                                
                                <div class="col-3 bs-wizard-step disabled"><!-- complete -->
                                  <div class="text-center bs-wizard-stepnum"><span class="hidden-md-down">Step 3</span></div>
                                  <div class="progress"><div class="progress-bar"></div></div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center"><span class="hidden-md-down">Creators</span></div>
                                </div>

                                <div class="col-3 bs-wizard-step disabled"><!-- complete -->
                                  <div class="text-center bs-wizard-stepnum"><span class="hidden-md-down">Step 4</span></div>
                                  <div class="progress"><div class="progress-bar"></div></div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center"><span class="hidden-md-down">Fees</span></div>
                                </div>

                            </div>
                            <div class="row" style="padding:10px 0;">
                                <div class="col-12" id="add_project_page_1" style="display:none;">
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
                                        <input type="text" class="form-control" id="project_short_description" placeholder="Please include a short line.">
                                        <!-- <textarea class="form-control" id="project_short_description" rows="1" placeholder="Please include a short line."></textarea>-->
                                    </div>
                                    <div class="form-group">
                                        <labal for="project_brand">Brand</labal>
                                        <select name="brand[]" id="brand" data-placeholder="Select Brand." class="customselect" data-enable-input=true nc-method="addBrand" multiple>
                                            <option value="">Select Brand</option>
                                    <?php
                                        $brands = $main->getAllBrands();
                                        foreach($brands as $key=>$value){
                                            if($value["term_id"] == $default_group["default_brand"]){
                                                ?>
                                            <option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option>
                                            <?php
                                            }else{
                                             
                                                if(!$value["hidden"]){
                                            ?>
                                            <option value="<?=$value["term_id"]?>"><?=$value["name"]?></option>
                                            <?php
                                                }
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
                                    <hr>
                                    <label for="">Extra</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="require_address" value="1">
                                        <label class="form-check-label" for="require_address">Require delivery Address</label>
                                    </div>
                                </div>
                                <div class="col-12" id="add_project_page_2" style="display:none;">
                                    <div class="task-items">
                                        
                                    </div>
                                    <div class="section-title clearfix">
                                        <a href="#" class="btn btn-primary text-caps btn-rounded btn-framed width-100" id="addtask">+ NEW TASK</a>
                                    </div>
                                </div>
                                <div class="col-12" id="add_project_page_3" style="display:none">
                                    <div class="form-group">
                                        <label for="creators">Creators To Invite</label>
                                        <div class="input-group-row creator-input">
                                            <select name="creators[]" class="form-control customselect" id="creators" data-placeholder="Select creators for this project." multiple>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="creator-groups row">
                                        
                                    </div>
                                    <div class="form-wdith">
                                        <div class="alert alert-danger hide">Some error message</div>
                                    </div>
                                </div>
                                <div class="col-12" id="add_project_page_4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default fee-container">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><strong>Fee Estimations</strong></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed">
                                                            <thead>
                                                                <tr>
                                                                    <td><strong>Item</strong></td>
                                                                    <td class="text-center"><strong></strong></td>
                                                                    <td class="text-center"><strong></strong></td>
                                                                    <td class="text-right"><strong>Totals</strong></td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="add_project_page_5">
                                    <div class="estimation_box">
                                    </div>
                                </div>
                            </div>     
                            <div class="bottom_panel">
                                <div class="float-right bottom_panel">
                                    <button class="btn btn-primary disabled">Back</button>
                                    <button class="btn btn-primary">Next</button>
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

        var _task_ar = [],
            _creator_ar = [],
            _lastcash, _lastentitlement;

        function createCreator(data){

            var cash_str = "",
                entitlement_str = "";

            if(data.cash){
                cash_str = "SGD$"+data.cash+".00";
            }else if(data.entitlement){
                cash_str = "SGD$0.00";
            }else{
                cash_str = `<span style="color:red">Waiting for offer</span>`;
            }

            if(data.entitlement){
                entitlement_str = '& ' + data.entitlement;
            }else{
                entitlement_str = ` `;
            }

            var creatordiv = `<div class="creator-item col-md-12" data-id="${data.userid}">
                        <div class="item-inner">
                            <div class="creator-image" style="background-image: url(${data.image_url});"></div>
                            
                                <div class="creator-name">
                                    <small>${data.igusername}</small>
                                    <h3>${data.name}</h3>
                                </div>
                                <div class="creator-offer">
                                    <small>offer</small>
                                    <h3>${cash_str}</h3>
                                    <p>${entitlement_str}</p>
                                </div>
                                <div class="creator-actions">
                                    <button class="btn btn-secondary" o="${data.userid}">Remove</button>
                                    <button class="btn btn-primary" o="${data.userid}">${ (()=>{if(!data.cash && !data.entitlement){ return "Make Offer"; }else{ return "Edit Offer";}})() }</button>
                                </div>
                            </div>
                        </div>`;

            /*
            if( !data.cash && !data.entitlement ){
                $(creatordiv).find(".creator-actions .btn-primary").text("Make Offer");
            }else{
                $(creatordiv).find(".creator-actions .btn-primary").text("Edit Offer");
            }*/

            return creatordiv;
        }

        function updateCreator(){
            $(".creator-groups").empty();

            $(_creator_ar).each(function(index, value){
                var creatordiv = $(createCreator(value));
                $(".creator-groups").append(creatordiv);

                creatordiv.find(".creator-actions .btn-secondary").click(function(e){
                    e.preventDefault();

                    _creator_ar = _creator_ar.filter(item=>{
                        return item.userid != $(e.currentTarget).attr("o");
                    });

                    updateCreator();
                });

                creatordiv.find(".creator-actions .btn-primary").click(function(e){
                    e.preventDefault();

                    var selecteditem = _creator_ar.filter(item=>{
                        return item.userid == $(e.currentTarget).attr("o");
                    });

                    if(selecteditem.length){

                        if(!selecteditem[0].cash && !selecteditem[0].entitlement){
                            //populate data
                            $("#editOfferDialog #cash").val(_lastcash);
                            $("#editOfferDialog #entitlement").val(_lastentitlement);
                        }else{
                            //populate data
                            $("#editOfferDialog #cash").val(selecteditem[0].cash ? selecteditem[0].cash : "");
                            $("#editOfferDialog #entitlement").val(selecteditem[0].entitlement ? selecteditem[0].entitlement : "");
                        }

                        $("#editOfferDialog .actions .btn-primary").attr({o:selecteditem[0].userid});

                        $("#editOfferDialog").modal("show");

                    }else{
                        alert("unknown error, no creator match");
                    }

                });
            });

            updateFee();
        }

        function createTaskItem(data){
            var image_div,
                figure_div,
                media_div;
            if(data.image_url && data.image_url.length){
                if(data.image_url.length == 1){
                    image_div = `<div class="image" style="background-image:url(${data.image_url[0]})"></div>`;
                }else{
                    image_div = `<div class="image multiple">`;
                    for(i=0; i < 4; i++){
                        if(i < data.image_url.length){
                            image_div += `<span class="background-image" style="background-image:url(${data.image_url[i]})"></span>`;
                        }
                    }
                    image_div += `</div>`;
                }
            }else{
                image_div = `<div class="image"></div>`;
            }

            //build date
            if(data.post){
                //with post
                figure_div = `
                    <figure>
                        <i class="fa fa-calendar-o"></i> Submit ${data.submission_closing_date}
                    </figure>
                    <figure>
                        <i class="fa fa-calendar-o"></i> Post ${data.posting_date}
                    </figure>
                    `;
            }else{
                figure_div = `
                    <figure>
                        <i class="fa fa-calendar-o"></i> Submit ${data.submission_closing_date}
                    </figure>
                    `;
            }

            //build medias number

            if(data.number_of_video == 0){
                media_div = `${data.number_of_photo} <i class="fa fa-camera" aria-hidden="true"></i>`;
            }else if(data.number_of_photo == 0){
                media_div = `${data.number_of_video} <i class="fa fa-video-camera" aria-hidden="true"></i>`;
            }else{
                media_div = `${data.number_of_photo} <i class="fa fa-camera" aria-hidden="true"></i> | ${data.number_of_video} <i class="fa fa-video-camera" aria-hidden="true"></i>`;
            }

            var div = `
            <div class="task-item" o="${data.id}">
                <div class="wrapper">
                    ${image_div}
                    <div class="content">
                        <h3>
                            <div class="meta">
                                ${figure_div}
                            </div>
                            <span>${data.name}</span>
                        </h3>
                        <div class="description two-button">
                            <p class="linkify">
                                ${media_div} / Creator
                                <br>
                                ${getTextFromHTML(data.instruction)}
                            </p>
                        </div>
                        <div class="actions">
                            <a href="#" class="delete" o="${data.id}">Delete</a>
                            <a href="#" class="edit" o="${data.id}">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            `;

            return div;
        }

        function updateTasks(){
            $(".task-items").empty();

            $(_task_ar).each(function(index,value){
                var taskitemdiv = $(createTaskItem(value));
                $(".task-items").append(taskitemdiv);

                taskitemdiv.find(".actions .delete").click(function(e){
                    e.preventDefault();

                    _task_ar = _task_ar.filter(item=>{
                        return item.id != $(e.currentTarget).attr("o");
                    });
                    
                    updateTasks();
                });

                taskitemdiv.find(".actions .edit").click(function(e){
                    e.preventDefault();

                    var selecteditem = _task_ar.filter(item=>{
                        return item.id == $(e.currentTarget).attr("o");
                    });

                    if(selecteditem.length){
                        $("#addTaskDialog .actions .reset, #addTaskDialog .actions .confirm").addClass("hide");
                        $("#addTaskDialog .actions .edit").attr({"o":selecteditem[0].id}).removeClass("hide");

                        //populate data
                        setupTask(selecteditem[0]);

                        $("#addTaskDialog").modal("show");

                    }else{
                        alert("unknown error, no task match");
                    }
                });
            });
        }

        function updateFee(){
            var container = $(".fee-container .panel-body tbody"),
                item_name = "",
                status = "",
                amount = "",
                count_amount = 0;

            container.empty();
                
            $(_creator_ar).each(function(index, value){
                if(value.cash){
                    item_name = "Payment For "+value.name;
                    status = "";
                    amount = "$"+value.cash+".00";

                    count_amount += parseInt(value.cash,10);

                    container.append(`<tr><td>${item_name}</td><td class="text-center"></td><td class="text-center">${status}</td><td class="text-right">${amount}</td></tr>`);
                }else{

                }
                item_name = "Platform Fee For "+value.name;
                status = "";
                amount = "$99.00";
                count_amount += 99;

                container.append(`<tr><td>${item_name}</td><td class="text-center"></td><td class="text-center">${status}</td><td class="text-right">${amount}</td></tr>`);
            });

            //total item
            var total_amount = "SGD$"+count_amount+".00";
            container.append(`<tr class="top-bold"><td class="no-line"></td><td class="no-line"></td><td class="no-line text-center"><strong>Total</strong></td><td class="no-line text-right">${total_amount}</td></tr>`);
        }

        function getTextFromHTML(htmltext){
            var a = $('<div>').html(htmltext);
            a.find("*").each(function(index){ $( this).append(' '); });
            var str = a.text();
            return str.replace(/\s\s+/g, ' ');
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

        $('.input-group.date input').each(function(index,value){
            $(this).datepicker({
                format: 'dd/mm/yy',
                autoclose: true,
                allowInputToggle: true,
                startDate: "+0d"
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
                var selected = $("#creators")[0].selectize.getValue();
                $.each(selected, function(index,value){
                    var tempitem = $("#creators")[0].selectize.options[value];
                    addInvitedCreator(tempitem);
                });
                $("#creators")[0].selectize.clear(true);
                $("#creators")[0].selectize.close();
            }
        });

        function addInvitedCreator(creator){
            var selected_creator = _creator_ar.filter(item=>{
                return item.userid == creator.userid;
            });

            if(selected_creator.length){
                alert("creator already added");
            }else{
                creator.cash = null;
                creator.entitlement = null;

                _creator_ar.push(creator);

                //sorting, by name
                _creator_ar.sort(function(a,b){
                    let comparison = 0;
                    if( a.igusername > b.igusername ){
                        comparison = 1;
                    }else if( a.igusername < b.igusername ){
                        comparison = -1;
                    }

                    return comparison;
                });

                updateCreator();
            }
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

        $(".addSampleButton").click(function(e){
            e.preventDefault();
            if($("#samples").val()){
                addSample($("#samples").val());
                $("#samples").val('');
            }
        });

        //add task sample
        function addTaskSample(url){
            $("#addTaskDialog .image-groups").prepend(
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

        function gatherAllData(){
            var a = {
                title: $("#project_name").val(),
                description: $("#project_description").data("quill").container.firstChild.innerHTML,
                summary: $("#project_short_description").val(),
                brand: $("#brand").val(),
                location: $("#location").val(),
                tag: $("#tag").val(),
                extra: {
                    require_address:$("#require_address").is(":checked")
                },
                task: _task_ar,
                creator: _creator_ar
            };
            return a;
        }

        var _saving_project = false;
        function saveProject(data){
            if(_saving_project) return;
            _saving_project = true;

            $.ajax({
                type:"POST",
                dataType:'json',
                data:{
                    method:"addProject",
                    data:data
                },
                error:function(){
                    callback();
                },
                success:function(res){
                    _saving_project = false;
                    window.location.href = "/user@<?=$current_user->ID?>/project/"+res.result;
                    console.log(res);
                }
            });
        }

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
                var toolbarOptions = [
                  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                  ['link', 'blockquote'],
                  //['link', 'blockquote', 'code-block'],

                  //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
                  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                  [{ 'direction': 'rtl' }],                         // text direction

                  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                  //[{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                  [{ 'font': [] }],
                  [{ 'align': [] }],

                  [ 'video' ],
                  ['clean']                                         // remove formatting button
                ];

                var temp_quill = new Quill("#"+temp_item.attr("id"), {
                    placeholder: temp_item.attr("placeholder"),
                    modules: {
                    toolbar: toolbarOptions
                  },
                    theme: 'snow'
                });
                temp_item.data("quill", temp_quill);
                /*
                if(inner_content){
                    temp_quill.clipboard.dangerouslyPasteHTML(0, inner_content);
                }*/
            });
        });

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
                if(_task_ar.length == 0){
                    pageReady = false;
                    msg = "Please create at least one task.";
                    if(!silence){
                        alert(msg);
                    }
                }
            }else if(pageindex == 3){
                $('#add_project_page_3 .alert').addClass("hide");
                //check if all creator bounty is set
                var alldone = true,
                    itemnotdone = [];
                $.each(_creator_ar, function(index,item){
                    if(!item.cash && !item.entitlement){
                        alldone = false;
                        itemnotdone.push(item);
                    }
                });

                if(!alldone){
                    pageReady = false;
                    msg = "Bounty of creator(s) is not defined.";
                    if(!silence){
                        $("#add_project_page_3 .alert").text(msg).removeClass("hide");
                    }
                }

            }else if(pageindex == 4){
                
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
                    $("#newproject .bottom_panel .btn:eq(1)").text("Confirm");
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
            $("html").stop().animate({scrollTop:$("#newproject").offset().top}, 200, 'linear', function() { 

            });
        }

        function resetAddTask(){
            $('#addTaskDialog .alert').addClass("hide");

            //2 weeks from today
            var a2w = new Date(Date.now() + 14*24*3600*1000),
                dateStr = a2w.getDate() + "/" + (a2w.getMonth() + 1) + "/" + a2w.getFullYear();

            $("#add_task_name").val("Task #"+(_task_ar.length + 1));
            $("#add_number_of_photo").val("");
            $("#add_number_of_video").val("");
            if($("#add_task_instruction").data("quill")){$("#add_task_instruction").data("quill").setContents([]);}
            $("#add_samples").val("");
            $(".image-groups").empty();
            $("#add_closing_date").datepicker("update", dateStr);
            $("#add_post_on_wall").iCheck("uncheck");
            $("#add_post_date").datepicker("update", dateStr);
            if($("#add_post_instruction").data("quill")){$("#add_post_instruction").data("quill").setContents([]);}
        }

        function setupTask(data){
            $('#addTaskDialog .alert').addClass("hide");

            //2 weeks from today
            var a2w = new Date(Date.now() + 14*24*3600*1000),
                dateStr = a2w.getDate() + "/" + (a2w.getMonth() + 1) + "/" + a2w.getFullYear();

            $("#add_task_name").val(data.name);
            $("#add_number_of_photo").val(data.number_of_photo);
            $("#add_number_of_video").val(data.number_of_video);// temp_quill.clipboard.dangerouslyPasteHTML(0, inner_content);
            if($("#add_task_instruction").data("quill")){$("#add_task_instruction").data("quill").clipboard.dangerouslyPasteHTML(0, data.instruction);}
            $("#add_samples").val("");
            $(".image-groups").empty();

            $.each(data.image_url, function(index2, value2){
                addTaskSample(value2);
            });

            $("#add_closing_date").datepicker("update", data.submission_closing_date);
            if(data.post){
                $("#add_post_on_wall").iCheck("check");
                $("#add_post_date").datepicker("update", data.posting_date);
                if($("#add_post_instruction").data("quill")){$("#add_post_instruction").data("quill").clipboard.dangerouslyPasteHTML(0, data.post_instruction);}
            }else{
                $("#add_post_on_wall").iCheck("uncheck");
                $("#add_post_date").datepicker("update", dateStr);
                if($("#add_post_instruction").data("quill")){$("#add_post_instruction").data("quill").setContents([]);}
            }
        }

        $(function(){
            "use strict";

            $("#newproject .bs-wizard .bs-wizard-dot").each(function(index,value){
                $(this).click(function(e){
                    e.preventDefault();
                    showAddProjectPage(index + 1);
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

            $("#addtask").click(function(e){
                e.preventDefault();
                $("#addTaskDialog .actions .edit").addClass("hide");
                $("#addTaskDialog .actions .reset, #addTaskDialog .actions .confirm").removeClass("hide");
                $("#addTaskDialog").modal("show");
            });

            //initial add project prompt
            showAddProjectPage(1);

            var div = $(storify.template.simpleModal(
                {
                    titlehtml:`Make Offer`,
                    bodyhtml:`
                        <div class="form-group">
                            <label for="cash">Cash</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text">SGD$</span>
                              </div>
                              <input id="cash" name="cash" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                              <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                              </div>
                            </div>
                            <label for="entitlement">Entitlement</label>
                            <div class="input-group">
                              <input id="entitlement" name="entitlement" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Entitlement beside cash">
                            </div>
                            <p></p>
                            <div class="form-wdith">
                                <div class="alert alert-danger hide offer-error">Some error message</div>
                            </div>
                        </div>
                    `
                },
                "editOfferDialog",
                [
                    {
                        label:"Make Offer",
                        attr:{href:"#", class:"btn btn-primary small offer"}
                    }
                ]
            ));

            //get offer
            $(div).find(".actions .offer").click(function(e){
                e.preventDefault();
                //check is offer
                if( ($("#cash").val() == "" || $("#cash").val() == 0) && $("#entitlement").val() == "" ){
                    //error
                    $(".offer-error").removeClass("hide").text("Please enter either cash or entitlement.");
                }else{

                    _lastcash = $("#cash").val();
                    _lastentitlement = $("#entitlement").val();

                    $.each(_creator_ar, function(index,item){
                        if(item.userid == $(e.currentTarget).attr("o")){
                            _creator_ar[index].cash = $("#cash").val();
                            _creator_ar[index].entitlement = $("#entitlement").val();
                        }
                    });

                    updateCreator();

                    $("#editOfferDialog").modal("hide");
                }
            });

            $("body").append(div);            

            var div = $(storify.template.simpleModal(
                {
                    titlehtml:`Add task`,
                    bodyhtml:`
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="form-group">
                                    <label for="add_task_name" class="required">Task Name</label>
                                    <input type="text" class="form-control" id="add_task_name" placeholder="Enter a name for this task." autoComplete="off" required>
                                    <div class="form-wdith">
                                        <div class="alert alert-danger hide">Some error message</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="add_number_of_photo">Min. Photos Per Creator</label>
                                    <input type="number" class="form-control" id="add_number_of_photo" placeholder="Enter number of photos." autoComplete="off" value="">
                                    <div class="form-wdith">
                                        <div class="alert alert-danger hide">Some error message</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="add_number_of_video">Min. Videos Per Creator</label>
                                    <input type="number" class="form-control" id="add_number_of_video" placeholder="Enter number of videos." autoComplete="off" value="">
                                    <div class="form-wdith">
                                        <div class="alert alert-danger hide">Some error message</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="add_task_instruction">Instruction</label>
                                    <div class="form-control quill-textarea" id="add_task_instruction" placeholder="Provide further details about style, creative angle and other expectations."></div>
                                </div>
                                <div class="form-group">
                                    <label for="add_samples">Moodboard</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="add_samples" placeholder="Insert a link to your image." autoComplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary add_addSampleButton" type="button">Add Image</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="image-groups">
                                    
                                </div>
                                <div class="form-group">
                                    <label for="add_closing_date" class="required">Submission Closing Date</label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control" id="add_closing_date" placeholder="dd/mm/yy" autoComplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    <div class="form-width">
                                        <div class="alert alert-danger hide">Some error message</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="add_require_post">Post On Wall Required</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="add_post_on_wall" value="wall">
                                        <label class="form-check-label" for="add_post_on_wall">Post on Instagram</label>
                                    </div>
                                </div>
                                <div class="post_on_wall_content hide">
                                    <div class="form-group">
                                        <label for="add_post_date" class="required">Posting Date</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" id="add_post_date" placeholder="dd/mm/yy" autoComplete="off" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="form-width">
                                            <div class="alert alert-danger hide">Some error message</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_post_instruction">Instruction</label>
                                        <div class="form-control quill-textarea" id="add_post_instruction" placeholder="Provide further details. etc time to post"></div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                },
                "addTaskDialog",
                [
                    {
                        label:"Reset",
                        attr:{href:"#", class:"btn btn-primary small reset"}
                    },
                    {
                        label:"Add",
                        attr:{href:"#", class:"btn btn-primary small confirm"}
                    },
                    {
                        label:"Done",
                        attr:{href:"#", class:"btn btn-primary small edit"}
                    }
                ]
            ));

            //customize item
            div.find(".modal-dialog").addClass("modal-custom-xl");

            //icheck .iCheck();
            div.find("input[type=checkbox]:not(.customcheck), input[type=radio]:not(.customcheck)").each(function(i,e){
                _checkIfObjectVisible($(e), function(){
                    $(e).iCheck();
                    //add listener
                    $(e).on("ifChanged",function(ev){
                        if(ev.currentTarget.checked){
                            $(".post_on_wall_content").removeClass("hide");
                            //scroll
                            setTimeout(function(){
                                $(".post_on_wall_content").parents(".modal-body").stop().animate({scrollTop:$(".post_on_wall_content").parents(".modal-body").innerHeight()+500}, 200, 'linear', function() { 

                                });
                            },300);
                            
                        }else{
                            $(".post_on_wall_content").addClass("hide");
                        }
                    });
                });
            });

            //datepicker
            div.find('.input-group.date input').each(function(index,value){
                $(this).datepicker({
                    format: 'dd/mm/yy',
                    autoclose: true,
                    allowInputToggle: true,
                    startDate: "+0d"
                });
            });

            //quill
            div.find(".quill-textarea").each(function(i,e){
                _checkIfObjectVisible($(e), function(){
                    var temp_item = $(e);
                    var inner_content = temp_item.html();
                    var toolbarOptions = [
                      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                      ['blockquote', 'code-block'],

                      [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                      [{ 'direction': 'rtl' }],                         // text direction

                      [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                      [{ 'font': [] }],
                      [{ 'align': [] }],

                      [ 'video' ],
                      ['clean']                                         // remove formatting button
                    ];

                    var temp_quill = new Quill("#"+temp_item.attr("id"), {
                        placeholder: temp_item.attr("placeholder"),
                        modules: {
                        toolbar: toolbarOptions
                      },
                        theme: 'snow'
                    });
                    temp_item.data("quill", temp_quill);
                    /*
                    if(inner_content){
                        temp_quill.clipboard.dangerouslyPasteHTML(0, inner_content);
                    }*/
                });
            });

            //actions button
            div.find(".actions .reset").click(function(e){
                e.preventDefault();
                //reset
                resetAddTask();
                if($("#addTaskDialog .modal-body").is(":visible")){
                    $("#addTaskDialog .modal-body").stop().animate({scrollTop:0}, 200, 'linear', function() { 
                    });
                }
            });

            div.find(".actions .confirm").click(function(e){
                e.preventDefault();
                //check if 
                var er = 0,
                    pageReady = true,
                    silence = false,
                    checkitems,
                    msg,
                    data;

                $('#addTaskDialog .alert').addClass("hide");

                if($("#add_post_on_wall").is(":checked")){
                    checkitems = "#addTaskDialog *[required]";
                }else{
                    checkitems = "#addTaskDialog *[required][id!='add_post_date']";
                }

                $(checkitems).each(function(index,value){
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

                //check is both photo and video is zero
                if(parseInt($("#add_number_of_photo").val()?$("#add_number_of_photo").val():0,10) == 0 && parseInt($("#add_number_of_video").val()?$("#add_number_of_video").val():0,10) == 0){
                    pageReady = false;
                    msg = "Please fill in the number or photo / video required to deliver.";
                    if(!silence){
                        $("#add_number_of_photo").parents(".form-group").find(".alert").text("Required field - please enter the number of photos to be produced by the Creator.").removeClass("hide");
                        $("#add_number_of_video").parents(".form-group").find(".alert").text("Required field - please enter the number of videos to be produced by the Creator.").removeClass("hide");
                    }
                }

                if($("#add_post_on_wall").is(":checked")){
                    data = {
                        id:Date.now(),
                        image_url:$("#addTaskDialog .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                        submission_closing_date:$("#add_closing_date").val(),
                        name:$("#add_task_name").val(),
                        number_of_video:$("#add_number_of_video").val(),
                        number_of_photo:$("#add_number_of_photo").val(),
                        instruction:$("#add_task_instruction").data("quill").container.firstChild.innerHTML,
                        post:$("#add_post_on_wall").is(":checked"),
                        posting_date:$("#add_post_date").val(),
                        post_instruction:$("#add_post_instruction").data("quill").container.firstChild.innerHTML
                    };
                }else{
                    data ={
                        id:Date.now(),
                        image_url:$("#addTaskDialog .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                        submission_closing_date:$("#add_closing_date").val(),
                        name:$("#add_task_name").val(),
                        number_of_video:$("#add_number_of_video").val(),
                        number_of_photo:$("#add_number_of_photo").val(),
                        instruction:$("#add_task_instruction").data("quill").container.firstChild.innerHTML,
                        post:$("#add_post_on_wall").is(":checked")
                    };
                }

                if(pageReady){
                    _task_ar.push(data);

                    //sort by submission close date
                    _task_ar.sort(function(a,b){
                        var partA = a.submission_closing_date.split("/"),
                            partB = b.submission_closing_date.split("/");

                        const dateA = new Date(partA[2] > 50 ? 2000 + partA[2] : 1900 + partA[2], partA[1] - 1, partA[0] ),
                              dateB = new Date(partB[2] > 50 ? 2000 + partB[2] : 1900 + partB[2], partB[1] - 1, partB[0] );

                        let comparison = 0;
                        if( dateA > dateB ){
                            comparison = 1;
                        }else if( dateA < dateB ){
                            comparison = -1;
                        }

                        return comparison;
                    });

                    updateTasks();
                    resetAddTask();
                    $("#addTaskDialog").modal("hide");
                }else{
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

            });

            div.find(".actions .edit").click(function(e){
                //check if 
                var er = 0,
                    pageReady = true,
                    silence = false,
                    checkitems,
                    msg,
                    data;

                $('#addTaskDialog .alert').addClass("hide");

                if($("#add_post_on_wall").is(":checked")){
                    checkitems = "#addTaskDialog *[required]";
                }else{
                    checkitems = "#addTaskDialog *[required][id!='add_post_date']";
                }

                $(checkitems).each(function(index,value){
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

                //check is both photo and video is zero
                if(parseInt($("#add_number_of_photo").val()?$("#add_number_of_photo").val():0,10) == 0 && parseInt($("#add_number_of_video").val()?$("#add_number_of_video").val():0,10) == 0){
                    pageReady = false;
                    msg = "Please fill in the number or photo / video required to deliver.";
                    if(!silence){
                        $("#add_number_of_photo").parents(".form-group").find(".alert").text("Required field - please enter the number of photos to be produced by the Creator.").removeClass("hide");
                        $("#add_number_of_video").parents(".form-group").find(".alert").text("Required field - please enter the number of videos to be produced by the Creator.").removeClass("hide");
                    }
                }

                if($("#add_post_on_wall").is(":checked")){
                    data = {
                        id:$(e.currentTarget).attr("o"),
                        image_url:$("#addTaskDialog .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                        submission_closing_date:$("#add_closing_date").val(),
                        name:$("#add_task_name").val(),
                        number_of_video:$("#add_number_of_video").val(),
                        number_of_photo:$("#add_number_of_photo").val(),
                        instruction:$("#add_task_instruction").data("quill").container.firstChild.innerHTML,
                        post:$("#add_post_on_wall").is(":checked"),
                        posting_date:$("#add_post_date").val(),
                        post_instruction:$("#add_post_instruction").data("quill").container.firstChild.innerHTML
                    };
                }else{
                    data ={
                        id:$(e.currentTarget).attr("o"),
                        image_url:$("#addTaskDialog .image-groups .image-item a").map(function(i,v){ return $(v).attr('href'); }).get(),
                        submission_closing_date:$("#add_closing_date").val(),
                        name:$("#add_task_name").val(),
                        number_of_video:$("#add_number_of_video").val(),
                        number_of_photo:$("#add_number_of_photo").val(),
                        instruction:$("#add_task_instruction").data("quill").container.firstChild.innerHTML,
                        post:$("#add_post_on_wall").is(":checked")
                    };
                }

                if(pageReady){
                    //_task_ar.push(data);
                    //remove item in array and insert the latest

                    _task_ar = _task_ar.filter(item=>{
                        return item.id != $(e.currentTarget).attr("o");
                    });

                    _task_ar.push(data);

                    //sort by submission close date
                    _task_ar.sort(function(a,b){
                        var partA = a.submission_closing_date.split("/"),
                            partB = b.submission_closing_date.split("/");

                        const dateA = new Date(partA[2] > 50 ? 2000 + partA[2] : 1900 + partA[2], partA[1] - 1, partA[0] ),
                              dateB = new Date(partB[2] > 50 ? 2000 + partB[2] : 1900 + partB[2], partB[1] - 1, partB[0] );

                        let comparison = 0;
                        if( dateA > dateB ){
                            comparison = 1;
                        }else{
                            comparison = -1;
                        }

                        return comparison;
                    });

                    updateTasks();
                    resetAddTask();
                    $("#addTaskDialog .modal-body").scrollTop(0);
                    $("#addTaskDialog").modal("hide");
                }

            });

            $("body").append(div);

            resetAddTask();

            //addSample
            $(".add_addSampleButton").click(function(e){
                e.preventDefault();
                if($("#add_samples").val()){
                    addTaskSample($("#add_samples").val());
                    $("#add_samples").val('');
                }
            });

            showAddProjectPage(1);
        });
    </script>
</body>
</html>