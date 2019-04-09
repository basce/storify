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
    <link rel="stylesheet" href="/assets/css/collection.css">
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
                        <h1>Add Creator to <?=$group_detail["name"]?></h1>
                        <h2></h2>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Page Title =====================================================================-->
                <div class="background"></div>
                <!--end background-->
            </div>
        </header>
        <section class="content">
            <section class="block" id="bookmarkcontainer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <?php include("page/user/leftnav.php"); ?>
                        </div>
                        <div class="col-md-9">
                            <div class="section-title clearfix hide" id="emptybox">
                                <div class="float-xl-left float-md-left story_tell_cont">
                                    <p>Your collection is empty. Add creators by clicking the Save button on their profile.</p>
                                </div>
                                <div class="float-right">
                                    <a href="/" class="btn btn-primary float-right">Add Creators</a>
                                </div>
                            </div>
                            <div class="section-title clearfix hide" id="notemptybox">
                                <p class="normal">
                                    <span class="float-right edit_bar d-inline-block">
                                        <button type="submit" class="btn btn-primary float-right hide" id="add_items"><i class="fa fa-plus-square"></i></button>
                                        <a href="/user@<?=$current_user->ID?>/collections/<?=$query[2]?>/<?=$query[3]?>" class="btn btn-primary float-right" id="add_new_item"><i class="fa fa-chevron-left"></i></a>
                                    </span>
                                    <span class="opacity">Select creators and click <i class="fa fa-plus-square"></i></span>
                                </p>
                            </div>
                            <div class="items grid editmode grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-sort="<?=$sortBy?>" data-nc_data="post"  data-folder_id="<?=$query[3]?>">
                                
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="bookmarkloadmore">Load More</a>
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
        $(document).ready(function($){
            "user strict";

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function updateAddButton(){
                if($(".item.selected").length){
                    $("#add_items").removeClass("hide");
                }else{
                    $("#add_items").addClass("hide");
                }
            }

            function bookmarkTrigger(itemid, type, obj){
                var bookmark;
                if(obj.hasClass("active")){
                    bookmark = 0;
                }else{
                    bookmark = 1;
                }
                $.ajax({
                    url: "/json",
                    method: "POST",
                    data: {
                        method:"triggerbookmark",
                        item_id:itemid,
                        type:type,
                        bookmark:bookmark
                    },
                    success:function(rs){
                        if(rs.error){
                            console.log(rs.msg); //error
                            if(rs.msg == "require login"){
                                alert("bookmark function only for logged in user.");
                            }
                        }else{
                            if(parseInt(rs.bookmark)){
                                obj.addClass("active");
                            }else{
                                obj.removeClass("active");
                            }
                        }
                    },
                    dataType: "json"

                });
            }
            
            function format1(n) {
              return n.toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            function createIndexGridItem($obj){
                var h3 = $("<h3>");

                if($obj.instagrammer_tag && $obj.instagrammer_tag.length){
                    var tag_group = $("<span>").addClass("tag_group");
                    $.each($obj.instagrammer_tag, function(index,value){
                        tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id}).text(value.name));
                    });
                    h3.append(tag_group);
                }

                h3.append($("<a>").addClass("title").attr({href:"/"+$obj.igusername+"/"}).text($obj.igusername));

                /*
                if($obj.instagrammer_language && $obj.instagrammer_language.length){
                    $.each($obj.instagrammer_language, function(index, value){
                        h3.append($("<span>").addClass("tag").text(value.name));
                    });
                }
                
                h3.append($("<span>").addClass("tag")
                                            .append($("<i>").addClass("fa fa-clock-o"))
                                            .append(document.createTextNode($obj.modified)));
                */
                var h4 = $("<h4>").addClass("location");

                if($obj.instagrammer_country && $obj.instagrammer_country.length){
                    $.each($obj.instagrammer_country, function(index, value){
                        h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id}).text(value.name));
                    });
                }

                var aver_likes;
                if(parseInt($obj.average_likes,10) > 0){
                    aver_likes = $("<div>").addClass("price")
                        .append($("<span>").addClass("appendix").text('Average'))
                        .append($("<i>").addClass("fa fa-heart"))
                        .append(document.createTextNode(format1(parseInt($obj.average_likes,10))));
                }

                var $external_url = "";
                if($obj.external_url){
                    $external_url = $("<a>").attr({href:$obj.external_url, target:"_blank"}).text($obj.external_url);
                }

                var item = $("<div>").addClass("item scrollreveal").attr({o:$obj.id})
                            .append($("<div>").addClass("wrapper")
                                .append($("<div>").addClass("image")
                                        .append(h3)
                                        .append($("<a>").addClass("image-wrapper background-image").attr({href:"/"+$obj.igusername+"/"})
                                                        .css({'background-image':'url('+$obj.ig_profile_pic+')'})
                                                        .append($("<img>").attr({src:$obj.ig_profile_pic}))
                                            )
                                    )
                                .append(h4)
                                .append(aver_likes)
                                .append($("<div>").addClass("meta")
                                        .append($("<figure>").text(format1(parseInt($obj.follows_by_count)))
                                                .prepend($("<i>").addClass("fa fa-users"))
                                            )
                                        .append($("<figure>").text(format1(parseInt($obj.media_count)))
                                                .prepend($("<i>").addClass("fa fa-image"))
                                            )
                                    )
                                .append($("<div>").addClass("description")
                                        .append($("<p>")
                                            .text($obj.biography)
                                            )
                                        /*
                                        .append($("<span>")
                                            .append($("<i>").addClass("fa fa-clock-o"))
                                            .append(document.createTextNode($obj.modified))
                                            )*/
                                        /*.append($external_url)*/
                                    )
                                /*
                                .append(
                                    $("<a>").addClass("detail text-caps underline").text("Details").attr({href:"/"+$obj.igusername+"/"})
                                    )*/
                            );

                return item;
            }

            function updateItems(){
                var $grid = $(".items.grid"),
                    cur_page = parseInt($grid.attr("data-page"), 10),
                    sort = $grid.attr("data-sort");

                cur_page = cur_page ? cur_page + 1 : 1;

                $("#bookmarkloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data:{
                        method:"getItems",
                        page:cur_page,
                        sort:sort
                    },
                    success:function(rs){
                        if(rs.error){
                            console.log(rs.msg);
                        }else{
                            console.log(rs);
                            $grid.attr({'data-page':rs.result.page});
                            $("#bookmarkloadmore").text("Load More").blur();
                            if(parseInt(rs.result.page,10) < parseInt(rs.result.totalpage, 10)){
                                $("#bookmarkloadmore").css({display:"inline-block"});
                            }else{
                                $("#bookmarkloadmore").css({display:"none"});
                            }
                            if(rs.result.total == 0){
                                $("#emptybox").removeClass("hide");
                                $("#notemptybox").addClass("hide");
                            }else{
                                $("#emptybox").addClass("hide");
                                $("#notemptybox").removeClass("hide");
                            }

                            //add to masonry
                            $.each(rs.result.data, function(index,value){
                                var $temp = createIndexGridItem(value);
                                $temp.click(function(e){
                                    e.preventDefault();
                                    if($(this).parents(".editmode").length){
                                        $(this).toggleClass('selected');
                                    }
                                    updateAddButton();
                                });
                                $grid.append($temp);
                                ScrollReveal().reveal($temp, slideUp);
                            });


                        }
                    }
                })
            }

            var adding_items = false;
            function addItems(items){
                if(adding_items) return;
                adding_items = true;
                var folder_id = parseInt($(".grid.items").attr("data-folder_id"), 10);
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    data:{
                        method:"addItems",
                        items:items,
                        folder:folder_id
                    },
                    success:function(rs){
                        adding_items = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            window.location = "/user@<?=$current_user->ID?>/collections/<?=$query[2]?>/<?=$query[3]?>";
                        }
                    }
                })
            }

            $("#bookmarkloadmore").click(function(e){
                e.preventDefault();
                updateItems();
            });

            if($("#bookmarkloadmore").length){
                $("#bookmarkloadmore").click();
            }

            $("#add_items").click(function(e){
                e.preventDefault();
                var selected = [];
                $(".items.grid .item.selected").each(function(index,value){
                    selected.push($(value).attr("o"));
                });
                if(selected.length){
                    addItems(selected);
                }else{
                    alert("please select at least 1 item");
                }
            });


            <?php if(isset($_GET["order"])){ ?>
                $('html, body').animate({
                    scrollTop:$("#bookmarkcontainer").offset().top
                },500);
            <?php } ?>
        });
    </script>
</body>
</html>