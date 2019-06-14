<?php
//overwrite pagesettings
$pageSettings["meta"]["name"] = $current_user->display_name."'s Showcase - Storify photos and videos";
$pageSettings["meta"]["description"] = "The most beautiful photos and videos from ".$current_user->display_name."(@".$social_account["iger"]["igusername"].")."
?>
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
                        <h1><?=htmlspecialchars($current_user->display_name)?>'s showcase</h1>
                        <h2><?=htmlspecialchars($social_account["iger"]["igusername"])?> - Last modified on <span class="last_update_datetime"><?=date("j M y H:i", $social_account["iger"]["unformatted_modified"])?></span></h2>
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
                            <div class="user_edit">
                                <section class="">
                                    <div class="author big clearfix">
                                        <div class="author-image">
                                            <div class="background-image">
                                                <img src="<?=$social_account["iger"]["ig_profile_pic"]?>" alt="">
                                            </div>
                                        </div>
                                        <!--end author-image-->
                                        <div class="author-description">
                                            <div class="section-title">
                                                <h2><?=$social_account["iger"]["name"]?></h2>
                                                <?php
                                                    if($social_account["iger"]["instagrammer_country"] && sizeof($social_account["iger"]["instagrammer_country"])){
                                                ?><h4 class="location"><?php 
                                                        foreach($social_account["iger"]["instagrammer_country"] as $key=>$value){
                                                            ?><a href="/listing?country%5B%5D=<?=$value["term_id"]?>"><?=$value["name"]?></a><?php
                                                        }
                                                ?></h4><?php       
                                                    }
                                                ?>
                                                <figure>
                                                    <div class="meta float-left">
                                                        <figure>Avg <i class="fa fa-heart"></i><?=number_format($social_account["iger"]["average_likes"])?></figure>
                                                        <figure><i class="fa fa-users"></i><?=number_format($social_account["iger"]["follows_by_count"])?></figure>
                                                        <figure><i class="fa fa-image"></i><?=number_format($social_account["iger"]["media_count"])?></figure>
                                                    </div>
                                                    <div class="text-align-right social">
                                                        <a href="https://instagram.com/<?=$social_account["iger"]["igusername"]?>" target="_blank">
                                                            <i class="fa fa-instagram"></i>
                                                        </a>
                                                    </div>
                                                </figure>
                                            </div>
                                            <p>
                                                <?=$social_account["iger"]["biography"]?>
                                            </p>
                                        </div>
                                        <!--end author-description-->
                                    </div>
                                    <!--end author-->
                                </section>
                                <section>
                                    <p>Help us discover your best stories.</p>
                                    <div class="author-description">
                                        <div class="form-group">
                                            <select name="category[]" id="category" data-placeholder="Please select up to 5 passions." class="customselect" data-maxitems=5 data-enable-input=true nc-method="addCategory" multiple>
                                                <option value="">Select passion</option>
                                                <?php
                                                    $language_tags = $main->getAllTags();
                                                    foreach($language_tags as $key=>$value){
                                                        $temp_selected = false;
                                                        foreach($social_account["iger"]["instagrammer_tag"] as $key2=>$value2){
                                                            if($value2["term_id"] == $value["term_id"]){
                                                                $temp_selected = true;
                                                            }
                                                        }
                                                        if(isset($value["hidden"]) && $value["hidden"]){
                                                            if($temp_selected){
                                                                ?><option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option><?php
                                                            }
                                                        }else{
                                                            if($temp_selected){
                                                                ?><option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option><?php
                                                            }else{
                                                                ?><option value="<?=$value["term_id"]?>"><?=$value["name"]?></option><?php
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="alert alert-danger hide">Please select at least 1 option.</div>
                                        </div>
                                    </div>
                                    <div class="author-description">
                                        <div class="form-group">
                                            <select name="country[]" id="country" data-placeholder="Please select up to 5 countries." class="customselect" data-maxitems=5 data-enable-input=true multiple nc-method="addCountry">
                                                <option value="">Select country/city</option>
                                                <?php
                                                    $category_tags = $main->getAllCountries();
                                                    foreach($category_tags as $key=>$value){
                                                        $temp_selected = false;
                                                        foreach($social_account["iger"]["instagrammer_country"] as $key2=>$value2){
                                                            if($value2["term_id"] == $value["term_id"]){
                                                                $temp_selected = true;
                                                            }
                                                        }
                                                        if(isset($value["hidden"]) && $value["hidden"]){
                                                            if($temp_selected){
                                                                ?><option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option><?php
                                                            }
                                                        }else{
                                                            if($temp_selected){
                                                                ?><option value="<?=$value["term_id"]?>" selected><?=$value["name"]?></option><?php
                                                            }else{
                                                                ?><option value="<?=$value["term_id"]?>"><?=$value["name"]?></option><?php
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div class="alert alert-danger hide">Please select at least 1 option.</div>
                                        </div>
                                    </div>
                                </section>
                                <section class="clearfix">
                                    <button type="submit" class="btn btn-primary float-right width-180" id="tags_update">Update</button>
                                </section>
                            </div>
                            <hr>
                            <section class="clearfix">
                                <button type="submit" class="btn btn-primary float-right width-180" id="post_pull" style="z-index:1; margin-left:5px;"><i class="fa fa-refresh"></i> Get Up To Date</button>
                                <h2 style="margin-bottom:0">Featured Stories</h2>
                                <p class="post_idle" style="display:none">Last pulled on <span class="last_update_datetime"></span></p>
                                <p class="post_empty" style="display:none">No stories yet, pull stories from your Instagram account now.</p>
                                <p class="post_waiting" style="display:none">No stories yet, update passions and country before you can pull stories from your Instagram account.</p>
                                <p class="post_pulling" style="display:none">Working hard to uncover your stories. This will take a few minutes, so you can go explore other pages and come back later...</p>
                            </section>
                            <div class="items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-nc_data="post">
                            </div>
                        </div>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->
    <script type="text/javascript">
        $(function(){
            var _social_data = <?=json_encode($social_account)?>;
            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function formReady(){
                var er = 0;
                if($("#country").val().length){
                    $("#country").parent().find(".alert").addClass("hide");
                }else{
                    $("#country").parent().find(".alert").removeClass("hide");
                    er++;
                }

                if($("#category").val().length){
                    $("#category").parent().find(".alert").addClass("hide");
                }else{
                    $("#category").parent().find(".alert").removeClass("hide");
                    er++;
                }

                return !er;
            }

            function format1(n) {
              return n.toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            function createPostGridItem($obj){
                var h3 = $("<h3>");

                if($obj.post_tag && $obj.post_tag.length){
                    var tag_group = $("<span>").addClass("tag_group");
                    $.each($obj.post_tag, function(index,value){
                        tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id}).text(value.name));
                    });
                    h3.append(tag_group);
                }

                 h3.append($("<span>").addClass("tag")
                        .append($("<i>").addClass("fa fa-clock-o"))
                        .append(document.createTextNode($obj.modified)));

                var h4 = $("<h4>").addClass("location");

                if($obj.post_country && $obj.post_country.length){
                    $.each($obj.post_country, function(index, value){
                        h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id}).text(value.name));
                    });
                }

                var item = $("<div>").addClass("item")
                    .append($("<div>").addClass("wrapper")
                        .append($("<div>").addClass("image")
                                .append(h3)
                                .append($("<a>").addClass("image-wrapper background-image").attr({href:$obj.link, target:"_blank"})
                                    .css({'background-image':'url('+$obj.image_hires+')'})
                                    .append($("<img>").attr({src:$obj.image_hires}))
                            )
                        )
                        .append(h4)
                        .append($("<div>").addClass("meta")
                            .append($("<figure>")
                                .append($("<i>").addClass("fa fa-heart"))
                                .append(document.createTextNode(format1(parseInt($obj.likes, 10))))
                            )
                            .append($("<figure>")
                                .append($("<i>").addClass("fa fa-comments"))
                                .append(document.createTextNode(format1(parseInt($obj.comments, 10))))
                            )
                        )
                        .append($("<div>").addClass("description")
                            .append($("<p>").text($obj.caption))
                        )
                        .append(
                            $("<a>").addClass("detail text-caps underline").text("Read").attr({href:$obj.link, target:"_blank"})
                        )
                    );
                return item;
            }

            var _updatingPostsAsync = false;
            function updatePostsAsync(){
                if(_updatingPostsAsync) return;
                _updatingPostsAsync = true;

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:'<?=$pathquery[2]?>',
                    data:{
                        method:"updatePostsAsync",
                        iger:_social_data.iger.id
                    },
                    success:function(rs){
                        _updatingPostsAsync = false;

                        checkUpdate();
                    }
                });
            }

            var _updatingPosts = false;
            function upatePostItems(){
                if(_updatingPosts) return;
                _updatingPosts = true;
                var $grid = $(".items.grid");

                changeMsg("processing");

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:'<?=$pathquery[2]?>',
                    data:{
                        method:"updatePosts",
                        iger:_social_data.iger.id
                    },
                    success:function(rs){
                        _updatingPosts = false;
                        $(".post_pulling").attr({style:"display:none"});
                        $(".post_idle").removeAttr("style");
                        if(rs.error){
                            console.log(rs.msg);
                        }else{
                            $grid.empty();
                            $grid.attr({'data-page':rs.result.page});
                            $("#post_pull").html('<i class="fa fa-refresh"></i> Get Up To Date').blur();
                            if(rs.social_data && rs.social_data.iger && rs.social_data.iger.modified){
                                $(".last_update_datetime").text(rs.social_data.iger.modified);
                            }
                            if(rs.result.data && rs.result.data.length){
                                //got result
                                changeMsg("pull_done");
                            }else{
                                //empty
                                changeMsg("empty");
                            }
                            $.each(rs.result.data, function(index,value){
                                var $temp = createPostGridItem(value);
                                $grid.append( $temp );
                                ScrollReveal().reveal($temp, slideUp);
                            })
                        }
                    }
                });
            }

            var _checkUpdating = false;
            function checkUpdate(){
                if(_checkUpdating) return;
                _checkUpdating = true;

                changeMsg("processing");

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:'<?=$pathquery[2]?>',
                    data:{
                        method:"checkPostsStatus",
                        iger:_social_data.iger.id
                    },
                    success:function(rs){
                        _checkUpdating = false;
                        if(rs.status == 1){

                            changeMsg("processing");

                            setTimeout(function(){
                                checkUpdate();
                            },5000);
                        }else{
                            var $grid = $(".items.grid");
                            $grid.empty();
                            $grid.attr({'data-page':0});

                            pullPosts();
                        }
                    },
                    error:function(xhr, status, thrownError){
                        _checkUpdating = false;
                        console.log("error");
                        console.log(thrownError);
                        setTimeout(function(){
                            checkUpdate();
                        },5000);
                    }
                });
            }

            var _pullingPosts = false;
            function pullPosts(){
                if(_pullingPosts) return;
                _pullingPosts = true;
                var $grid = $(".items.grid"),
                    cur_page = $grid.attr("data-page");

                cur_page = cur_page ? cur_page + 1: 1;

                changeMsg("processing");

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:'<?=$pathquery[2]?>',
                    data:{
                        method:"getPosts",
                        iger:_social_data.iger.id,
                        page:cur_page
                    },
                    success:function(rs){
                        _pullingPosts = false;
                        if(rs.error){
                            console.log(rs.msg);
                        }else{
                            $grid.attr({'data-page':rs.result.page});
                            if(rs.social_data && rs.social_data.iger && rs.social_data.iger.modified){
                                $(".last_update_datetime").text(rs.social_data.iger.modified);
                            }
                            if(rs.result.data && rs.result.data.length){
                                //got result
                                changeMsg("pull_done");
                            }else{
                                //empty
                                changeMsg("empty");
                            }

                            $grid.empty();
                            $.each(rs.result.data, function(index,value){
                                var $temp = createPostGridItem(value);
                                $grid.append( $temp );
                                ScrollReveal().reveal($temp, slideUp);
                            })
                        }
                    }
                })
            }

            $("#post_pull").click(function(e){
                if(!$(this).hasClass("disabled")){
                    updatePostsAsync();
                }
            });

            function changeMsg(type){
                $("#post_pull").addClass("hide");
                $(".post_idle").attr({style:"display:none"});
                $(".post_empty").attr({style:"display:none"});
                $(".post_pulling").attr({style:"display:none"});
                $(".post_waiting").attr({style:"display:none"});
                $("#post_pull").html('<i class="fa fa-refresh"></i> Get Up To Date').removeClass("disabled").blur();
                switch(type){
                    case "pull_done":
                        $("#post_pull").removeClass("hide");
                        $(".post_idle").removeAttr("style");
                    break;
                    case "empty":
                        $("#post_pull").removeClass("hide");
                        $(".post_empty").removeAttr("style");
                    break;
                    case "need_tag":
                        $(".post_waiting").removeAttr("style");
                    break;
                    case "processing":
                        $("#post_pull").removeClass("hide");
                        $("#post_pull").html('<i class="fa fa-refresh fa-spin"></i> Get Up To Date').addClass("disabled");
                        $(".post_pulling").removeAttr("style");
                    break;
                }
            }

            function availableForPostPulling(){
                if(_social_data && 
                   _social_data.iger && 
                   _social_data.iger.instagrammer_country && 
                   _social_data.iger.instagrammer_country.length &&
                   _social_data.iger.instagrammer_tag && 
                   _social_data.iger.instagrammer_tag.length ){

                    return true;
                }else{

                    return false;
                }
            }

            $("#tags_update").click(function(e){
                if(formReady()){
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data:{
                            countries:$("#country").val(),
                            language:[],
                            category:$("#category").val(),
                            method:"social_update"
                        },
                        success:function(data){
                            if(data.error){
                                alert("data.msg");
                            }else{
                                _social_data = data.social_data;

                                if(data.category_changed){
                                    var a = $("#category").parents(".form-group").find(".alert");
                                    a.text("Passion tags updated successfully.").removeClass("hide alert-danger").addClass("alert-success");
                                    setTimeout(function() {
                                        a.text("Please select at least 1 option.").removeClass("alert-success").addClass("alert-danger hide");
                                    },1000);
                                }
                                if(data.country_changed){
                                    var b = $("#country").parents(".form-group").find(".alert");
                                    b.text("Country tags updated successfully.").removeClass("hide alert-danger").addClass("alert-success");
                                    setTimeout(function() {
                                        b.text("Please select at least 1 option.").removeClass("alert-success").addClass("alert-danger hide");
                                    },1000);
                                }
                                if(data.social_data && data.social_data.iger && data.social_data.iger.modified){
                                    $(".last_update_datetime").text(data.social_data.iger.modified);
                                }
                                /*
                                if(availableForPostPulling()){
                                    changeMsg("empty");
                                }else{
                                    changeMsg("need_tag");
                                }*/
                                if($(".post_waiting:visible").length){
                                    pullPosts();
                                }
                                if(data.category_changed || data.country_changed){
                                    setTimeout(function(){
                                        $('html, body').animate({
                                            scrollTop: $("#post_pull").offset().top
                                        }, 500);
                                    },1000);
                                }else{
                                    $('html, body').animate({
                                        scrollTop: $("#post_pull").offset().top
                                    }, 500);
                                }
                            }
                        }
                    })
                }else{
                    //do nothing, wait user input
                }
            });

            if(_social_data && _social_data.iger && _social_data.iger.id){
                if(availableForPostPulling()){
                    checkUpdate();
                }else{
                    changeMsg("need_tag");
                }
            }
        });
    </script>
</body>
</html>