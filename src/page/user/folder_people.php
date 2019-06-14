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
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<?=get_option("custom_settings_header_js")?>
</head>
<body>
    <div class="page sub-page">
        <header class="hero">
            <div class="hero-wrapper">
<?php 
$header_without_toggle_button = true;
include("page/component/header.php"); 
if(isset($_REQUEST["order"])){
    switch($_REQUEST["order"]){
        case "oldest":
            $sortBy = "oldest";
        break;
        case "latest":
            $sortBy = "latest";
        break;
        default:
            $sortBy = "sort_index";
        break;
    }
}else{
    $sortBy = "sort_index";
}
?>
                <!--============ Page Title =========================================================================-->
                <div class="page-title">
                    <div class="container">
                        <h1><?=htmlspecialchars($group_detail["name"])?></h1>
                        <?php
                            $temp_result = $main->getGroupItem($pathquery[3], "people", 1, 1); // only interest on the total item

                            $temp_total = isset($temp_result["total"]) ? (int) $temp_result["total"] : 0;

                            if($temp_total == 1){
                                $temp_subtitle = "1 creator on this board.";
                            }else{
                                $temp_subtitle = $temp_total." creators on this board.";
                            }
                        ?>
                        <h2><?=$temp_subtitle?></h2>
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
                                <p class="normal">
                                    <span class="float-right edit_bar d-inline-block">
                                        <a href="/user@<?=$current_user->ID?>/collections/<?=$pathquery[2]?>/<?=$pathquery[3]?>/add" title="Add" class="btn btn-primary float-right" id="add_new_item">Add</a>
                                    </span>
                                    <span>No creators on this board yet.</span>
                                </p>
                            </div>
                            <div class="section-title clearfix hide" id="notemptybox">
                                <div class="row">
                                    <div class="col-6">
                                        <!--
                                            <select name="order" id="order" class="small width-200px" data-placeholder="Default Sorting" >
                                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest</option>
                                                <option value="oldest" <?php if($sortBy == "oldest"){ echo "selected";}?>>Oldest</option>
                                            </select>
                                        -->
                                    </div>
                                    <div class="col-6 edit_bar">
                                        <div class="float-right">
                                            <a href="/user@<?=$current_user->ID?>/collections/<?=$pathquery[2]?>/<?=$pathquery[3]?>/add" title="Add" class="btn btn-primary float-right" id="add_new_item2"><i class="fa fa-plus-square"></i></a>
                                            <a href="#" title="Remove" class="btn btn-primary float-right" id="enter_edit_mode"><i class="fa fa-minus-square"></i></a>
                                            <a href="#" title="Update" class="btn btn-primary float-right hide" id="remove"><i class="fa fa-check-square"></i></a>
                                            <a href="#" title="Back" class="btn btn-primary float-right hide" id="back"><i class="fa fa-chevron-left"></i></a>
                                            <a href="#" title="Share" class="btn btn-primary float-right hide" id="share"><i class="fa fa-share-alt-square"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="items grid grid-xl-3-items grid-lg-3-items grid-md-3-items whole-item" data-page=0 data-nc_data="post" data-folder_id="<?=$pathquery[3]?>" data-sort="<?=$sortBy?>">
                                
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
    <modal class="modal" tabindex="-1" role="dialog" id="shareModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary small" type="button" data-dismiss="modal" aria-label="Close">Ok</button>
                </div>
            </div>
        </div>
    </modal>
    </div>
    <!--end page-->
    <script type="text/javascript">
        $(document).ready(function($){
            "user strict";

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function updateButtons(stage){
                if(stage == 1){
                    $("#add_new_item2").removeClass("hide");
                    $("#enter_edit_mode").removeClass("hide");
                    $("#remove").addClass("hide");
                    $("#back").addClass("hide");
                    $(".items.grid").removeClass("editmode");
                    if($(".items.grid").hasClass("ui-sortable")){
                        $(".items.grid").sortable("destroy");
                    }
                }else if(stage == 2){
                    $("#add_new_item2").addClass("hide");
                    $("#enter_edit_mode").addClass("hide");
                    $("#remove").addClass("hide");
                    $("#back").removeClass("hide");
                    $(".items.grid").sortable();
                    $(".items.grid").addClass("editmode");
                }else{
                    $("#add_new_item2").addClass("hide");
                    $("#enter_edit_mode").addClass("hide");
                    $("#remove").removeClass("hide");
                    $("#back").addClass("hide");
                    $(".items.grid").addClass("editmode");
                }
            }

            function format1(n) {
              return n.toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            function createIndexGridItem($item_obj){
                var h3 = $("<h3>"),
                    $obj = $item_obj["data"];

                if($obj.instagrammer_tag && $obj.instagrammer_tag.length){
                    var tag_group = $("<span>").addClass("tag_group");
                    $.each($obj.instagrammer_tag, function(index,value){
                        tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id,target:"_blank"}).text(value.name));
                    });
                    h3.append(tag_group);
                }

                h3.append($("<a>").addClass("title").attr({href:"/"+$obj.igusername+"/",target:"_blank"}).text($obj.igusername));

                /*
                if($obj.instagrammer_language && $obj.instagrammer_language.length){
                    $.each($obj.instagrammer_language, function(index, value){
                        h3.append($("<span>").addClass("tag").text(value.name));
                    });
                }
                
                h3.append($("<span>").addClass("tag")
                                            .append($("<i>").addClass("fa fa-clock-o"))
                                            .append(document.createTextNode($item_obj["modified"])));
                */
                var h4 = $("<h4>").addClass("location");

                if($obj.instagrammer_country && $obj.instagrammer_country.length){
                    $.each($obj.instagrammer_country, function(index, value){
                        h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id,target:"_blank"}).text(value.name));
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
                    $external_url = $("<a>").attr({href:$obj.external_url, target:"_blank",target:"_blank"}).text($obj.external_url);
                }

                var item = $("<div>").addClass("item scrollreveal")
                            .attr({o:$item_obj.id})
                            .append($("<div>").addClass("wrapper")
                                .append($("<div>").addClass("image")
                                        .append(h3)
                                        .append($("<a>").addClass("image-wrapper background-image").attr({href:"/"+$obj.igusername+"/",target:"_blank"})
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
                                .append(
                                    $("<a>").addClass("detail text-caps underline").text("Details").attr({href:"/"+$obj.igusername+"/",target:"_blank"})
                                    )
                            );

                return item;
            }

            function updateItems(){
                var $grid = $(".items.grid"),
                    cur_page = parseInt($grid.attr("data-page"), 10),
                    folder_id = parseInt($grid.attr("data-folder_id"), 10),
                    sort = $grid.attr("data-sort");

                cur_page = cur_page ? cur_page + 1 : 1;

                $("#bookmarkloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data:{
                        method:"getItems",
                        page:cur_page,
                        sort:sort,
                        folder:folder_id
                    },
                    success:function(rs){
                        if(rs.error){
                            console.log(rs.msg);
                        }else{
                            console.log(rs.result);
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
                                var $temp = createIndexGridItem (value);
                                $temp.click(function(e){
                                    //e.preventDefault();
                                    if($(this).parents(".editmode").length){
                                        $(this).toggleClass('selected');
                                        if($(".items .item.selected").length){
                                            updateButtons(3);
                                        }else{
                                            updateButtons(2);
                                        }
                                    }
                                });
                                $grid.append($temp);
                                ScrollReveal().reveal($temp, slideUp);
                            });


                        }
                    }
                })
            }

            $("#bookmarkloadmore").click(function(e){
                e.preventDefault();
                updateItems();
            });

            $("#enter_edit_mode").click(function(e){
                e.preventDefault();
                updateButtons(2);
            });

            var sorted = false;

            $(".items.grid").on("sortstop",function(event,ui){ 
                //update new position
                sorted = true;
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    data:{
                        method:'moveItem',
                        item:$(ui.item).attr("o"),
                        position:ui.item.index()+1 //position start from 1
                    },
                    success:function(rs){
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            //do nothing
                            updateButtons(3);
                        }
                    }
                });
            });

            var removing_items = false;
            function removeItems(items){
                if(removing_items) return;
                removing_items = true;
                var folder_id = parseInt($(".grid.items").attr("data-folder_id"), 10);
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    data:{
                        method:"removeItems",
                        items:items,
                        folder:folder_id
                    },
                    success:function(rs){
                        removing_items = false;
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            window.location.reload();
                        }
                    }
                });
            }

            $("#remove").click(function(e){
                e.preventDefault();
                var selected = [];
                $(".items.grid .item.selected").each(function(index,value){
                    selected.push($(value).attr("o"));
                });
                if(selected.length){
                    removeItems(selected);
                }else{
                    sorted = false;
                    updateButtons(2);
                }
            });

            $("#back").click(function(e){
                updateButtons(1);
            });

            updateButtons(1);

            if($("#bookmarkloadmore").length){
                $("#bookmarkloadmore").click();
            }
        });
    </script>
</body>
</html>
