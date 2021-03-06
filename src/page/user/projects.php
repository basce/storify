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
<style>
    .color-square{
        margin-top:3px;
    }
    .color-square i{
        opacity:0.5;
        font-size:2em;
    }
    .color-square.active i, .color-square:hover i{
        opacity:1;
    }
    .color-square-1 i{
        color:#2AA9E0;
    }
    .color-square-2 i{
        color:#43AD0F;
    }
    .color-square-3 i{
        color:#F39C12;
    }
    .color-square-4 i{
        color:#AD103C;
    }
    .items:not(.selectize-input).list.compact .item h3 .tag.category{
        left: -20.3rem;
        top: -1rem;
        bottom: auto;
        position: absolute;
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
                        <h1>Projects</h1>
                        <h2>
                            <?php
                                $stats = $main->getProjectManager()->getProjectStats($current_user->ID);
                                if($stats["pending"] == 0){
                                    //no pending
                                    echo "You have ".($stats["open"] == 1 ? "1 Open project":$stats["open"]." Open projects")." to complete.";
                                }else{
                                    //with pending
                                    echo "Say yes to ".($stats["pending"] == 1 ? "1 project ":$stats["pending"]." projects ")."and complete ".($stats["open"] == 1 ? "1 Open project":$stats["open"]." Open projects").".";
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
                            <div class="section-title clearfix">
                                <a href="#" class="btn btn-primary text-caps btn-rounded btn-framed width-100">+ NEW PROJECT</a>
                            </div>
                            <div class="section-title clearfix">
                                <div class="row">
                                    <div class="col-6">
                                        <select name="order" id="project_order" class="small width-200px" data-placeholder="Default Sorting">
                                            <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Latest</option>
                                            <option value="closing" <?php if($sortBy == "closing"){ echo "selected";}?>>Closing</option>
                                            <option value="name" <?php if($sortBy == "name"){ echo "selected";}?>>Name</option>
                                            <option value="brand" <?php if($sortBy == "brand"){ echo "selected";}?>>Brand</option>
                                            <option value="value" <?php if($sortBy == "value"){ echo "selected";}?>>Value</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <a href="#" class="color-square color-square-1" title="Pending"><i class="fa fa-square" aria-hidden="true"></i></a>
                                            <a href="#" class="color-square color-square-2" title="Open"><i class="fa fa-square" aria-hidden="true"></i></a>
                                            <a href="#" class="color-square color-square-3" title="Closing"><i class="fa fa-square" aria-hidden="true"></i></a>
                                            <a href="#" class="color-square color-square-4" title="Closed"><i class="fa fa-square" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="items list compact grid-xl-4-items grid-lg-3-items grid-md-2-items" id="project_grid">
                                <div class="item">
                                    <div class="ribbon-diagonal">
                                        <div class="ribbon-diagonal__inner">
                                            <span>Sold</span>
                                        </div>
                                    </div>
                                    <div class="ribbon-featured"><div class="ribbon-start"></div><div class="ribbon-content">Featured</div><div class="ribbon-end"><figure class="ribbon-shadow"></figure></div></div>
                                    <!--end ribbon-->
                                    <div class="wrapper">
                                        <div class="image">
                                            <h3>
                                                <a href="#" class="tag category">Home &amp; Decor</a>
                                                <a href="single-listing-1.html" class="title">Furniture for sale</a>
                                                <span class="tag">Offer</span>
                                            </h3>
                                            <a href="single-listing-1.html" class="image-wrapper background-image" style="background-image: url(&quot;assets/img/image-01.jpg&quot;);">
                                                <img src="assets/img/image-01.jpg" alt="">
                                            </a>
                                        </div>
                                        <!--end image-->
                                        <h4 class="location">
                                            <a href="#">Manhattan, NY</a>
                                        </h4>
                                        <div class="price">$80</div>
                                        <div class="meta">
                                            <figure>
                                                <i class="fa fa-calendar-o"></i>02.05.2017
                                            </figure>
                                            <figure>
                                                <a href="#">
                                                    <i class="fa fa-user"></i>Jane Doe
                                                </a>
                                            </figure>
                                        </div>
                                        <!--end meta-->
                                        <div class="description">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam venenatis lobortis</p>
                                        </div>
                                        <!--end description-->
                                        <a href="single-listing-1.html" class="detail text-caps underline">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="projectloadmore">Load More</a>
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
            "user strict";

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function createProject(value){
                console.log(value);

                var h3 = $("<h3>");

                if(value.summary.brand.length){
                    var tag_group = $("<span>").addClass("tag_group");
                    $.each(value.summary.brand, function(index,value2){
                        tag_group.append($("<span>").addClass("tag category").text(value2.name).click(function(e){
                            e.preventDefault();
                        }));
                    });

                    h3.append(tag_group);
                }

                h3.append($("<span>").addClass("title").text(value.name).click(function(e){
                    e.preventDefault();
                }));

                h3.append(document.createTextNode(" "));

                h3.append($("<span>").addClass("tag")
                            .append($("<i>").addClass("fa fa-clock-o"))
                            .append(document.createTextNode(value.summary.formatted_created_date))
                    )

                var location = null;

                if(value.summary.location.length){
                    location = $("<h4>").addClass("location");
                    $.each(value.summary.location, function(index,value2){
                        location.append($("<span>").text(value2.name));
                    });
                }

                var prize = null;
                if(value.summary.bounty.type == "cash"){
                    prize = $("<div>").addClass("price")
                                .append($("<i>").addClass("fa fa-money"));
                                //.append(document.createTextNode(value.summary.bounty.value));
                }else{
                    prize = $("<div>").addClass("price")
                                .append($("<i>").addClass("fa fa-gift"));
                                //.append(document.createTextNode(value.summary.bounty.value));
                }


                var div = $("<div>").addClass("item")
                            .append($("<div>").addClass("wrapper")
                                        .append($("<div>").addClass("image")
                                                .append(h3)
                                                .append($("<a>").addClass("image-wrapper background-image")
                                                    .css({"background-image":"url("+value.summary.display_image+")"})
                                                    .append($("<img>").attr({src:value.summary.display_image}))
                                                )
                                            )
                                        .append(location)
                                        .append(prize)
                                        .append($("<div>").addClass("meta")
                                            .append($("<figure>")
                                                    .append($("<i>").addClass("fa fa-calendar-o"))
                                                    .append(document.createTextNode(value.summary.formatted_closing_date))
                                                )
                                            .append($("<figure>")
                                                    .append($("<i>").addClass("fa fa-user"))
                                                    .append(document.createTextNode(value.summary.invitation.accepted+ " / "+value.summary.invitation.total))
                                                )
                                        )
                                        .append($("<div>").addClass("description")
                                            .append($("<p>").text(value.summary.description))
                                        )
                                );

                //add label
                /*
                <div class="ribbon-featured"><div class="ribbon-start"></div><div class="ribbon-content">Featured</div><div class="ribbon-end"><figure class="ribbon-shadow"></figure></div></div>
                 */
                div.append()
                return div;
            }

            function getProjects(){
                var $grid = $("#project_grid"),
                    cur_page = parseInt($grid.attr("data-page"), 10),
                    sort = $grid.attr("data-sort"),
                    filters = $grid.attr("data-filter");

                cur_page = cur_page ? cur_page + 1 : 1;

                $("#projectloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data: {
                        method:"getProject",
                        page:cur_page,
                        sort:sort,
                        filters:filters
                    },
                    success:function(rs){
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            $grid.attr({'data-page':rs.result.page});
                            $("#projectloadmore").text("Load More").blur();
                            if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                                $("#projectloadmore").css({display:"inline-block"});
                            }else{
                                $("#projectloadmore").css({display:"none"});
                            }

                            //add to mansory
                            console.log(rs);
                            
                            $.each(rs.result.data, function(index,value){
                                var $temp = createProject(value);
                                $grid.append($temp);
                                ScrollReveal().reveal($temp, slideUp);
                            });
                            
                        }
                    }
                });
            }

            $("#projectloadmore").click(function(e){
                e.preventDefault();
                getProjects();
            });

            $("#projectloadmore").click();
        });
    </script>
</body>
</html>