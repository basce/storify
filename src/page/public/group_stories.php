<?php
//setup meta and breadcrumb

$user_info = get_userdata($temp_userID);

$temp_result = $main->getGroupItem($pathquery[3], "people", 1, 1); // only interest on the total item
$temp_total = isset($temp_result["total"]) ? (int) $temp_result["total"] : 0;

$temp_description = ( $temp_total != 1 ? $temp_total." stories ":" 1 story ")."on ".$group_detail["name"]." - add stories and creators to your own boards.";

$image_url = $main->getRandomGroupFolderImage($temp_groupID, $temp_type);
if($image_url){
    $post_image_secure = $image_url;
    $post_image = str_replace("https","http",$image_url);
}else{
    $post_image = "http://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg";
    $post_image_secure = "https://cdn.storify.me/data/uploads/2018/12/storify_homepage.jpg";
}

$pageSettings["meta"] = array(
    "name"=>$group_detail["name"]." by ".$user_info->display_name,
    "description"=>$temp_description,
    "canonical"=>get_home_url()."/collections/".$pathquery[1]."/".$pathquery[2]."/".$pathquery[3]
);
$pageSettings["og"] = array(
    "og:type"=>"website",
    "og:title"=>$group_detail["name"]." by ".$user_info->display_name,
    "og:description"=>$temp_description,
    "og:site_name"=>"Storify.Me",
    "og:image"=>$post_image,
    "og:image:secure_url"=>$post_image_secure
);
$pageSettings["breadcrumb"] = array(
    array(
        "label"=>"Home",
        "href"=>"/"
    ),
    array(
        "label"=>"Public Collections",
        "href"=>""
    ),
    array(
        "label"=>$group_detail["name"],
        "href"=>""
    )
);

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
    <link rel="stylesheet" href="/assets/css/animate.css" type="text/css">
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
        case "popular":
            $sortBy = "popular";
        break;
        default:
            $sortBy = "default";
        break;
    }
}else{
    $sortBy = "default";
} ?>
                <!--============ Page Title =========================================================================-->
                <div class="page-title">
                    <div class="container">
                        <h1><?=htmlspecialchars($group_detail["name"])?></h1>
                        <?php
                            $temp_result = $main->getGroupItem($pathquery[3], "story", 1, 1); // only interest on the total item

                            $temp_total = isset($temp_result["total"]) ? (int) $temp_result["total"] : 0;

                            if($temp_total == 1){
                                $temp_subtitle = "1 story on the grid";
                            }else{
                                $temp_subtitle = $temp_total." stories on the grid";
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
                        <div class="col-12">
                            <div class="section-title clearfix hide" id="emptybox">
                                <p>No stories on this board yet.</p>
                            </div>
                            <div class="section-title clearfix">
                                <div class="row">
                                    <div class="col-6">
                                            <select name="order" id="order" class="small width-200px" data-placeholder="Default Sorting" >
                                                <option value="default" <?php if($sortBy == "default"){ echo "selected";}?>>Default</option>
                                                <option value="popular" <?php if($sortBy == "popular"){ echo "selected";}?>>Popular</option>
                                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest</option>
                                                <option value="oldest" <?php if($sortBy == "oldest"){ echo "selected";}?>>Oldest</option>
                                            </select>
                                    </div>
                                    <div class="col-6 edit_bar">
                                        <div class="float-right">
                                            <a href="#" title="Share your board" class="btn btn-primary float-right" data-toggle="modal" data-target="#shareModal" id="share"><i class="fa fa-share-alt-square"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-nc_data="post" data-folder_id="<?=$pathquery[3]?>" data-sort="<?=$sortBy?>">
                                
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-dramed btn-rounded" id="bookmarkloadmore">Load More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--modal-->
    <div class="modal animated fadeIn" tabindex="-1" role="dialog" id="memberonlymodal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Uh-oh, Members Only!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-10 offset-md-1">
                    <a href="/signin">Sign in</a> to add this creator or story to your collection
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <modal class="modal" tabindex="-1" role="dialog" id="shareModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Share this board.</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" readonly="readonly" id="collection_link" value="<?=get_home_url()?>/collections/<?=$pathquery[1]?>/<?=$pathquery[2]?>/<?=$pathquery[3]?>/">
                    <a href="#" class="copybutton">Copy</a>
                    <div class="alert alert-success hide">Link has been copied to clipboard.</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary small" type="button" data-dismiss="modal" aria-label="Close">Ok</button>
                </div>
            </div>
        </div>
    </modal>
    <!--end page-->
    <script type="text/javascript">
        $(document).ready(function($){
            "user strict";

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

            function format1(n) {
              return n.toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
              });
            }

            $("#collection_link").click(function(e){
                e.preventDefault();
                iosCopyToClipboard(this);
            });

            function iosCopyToClipboard(el) {
                var oldContentEditable = el.contentEditable,
                    oldReadOnly = el.readOnly,
                    range = document.createRange();

                el.contentEditable = true;
                el.readOnly = false;
                range.selectNodeContents(el);

                var s = window.getSelection();
                s.removeAllRanges();
                s.addRange(range);

                el.setSelectionRange(0, 999999); // A big number, to cover anything that could be inside the element.

                el.contentEditable = oldContentEditable;
                el.readOnly = oldReadOnly;

                document.execCommand('copy');

                $("#shareModal .alert").removeClass("hide");
                setTimeout(function(){
                    $("#shareModal .alert").addClass("hide");
                },2000);
            }

            function updateMemberOnlyModal(title, content){
                if($("#memberonlymodal").length){
                    $("#memberonlymodal .modal-header .modal-title").text(title);
                    $("#memberonlymodal .modal-body .form-group").html(content);
                    $("#memberonlymodal").modal();
                }else{
                    alert(title);
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
                                if($("#memberonlymodal").length){
                                    $("#memberonlymodal").modal("show");
                                }else{
                                    alert("bookmark function only for logged in user.");
                                }
                            }
                        }else{
                            if(parseInt(rs.bookmark)){
                                obj.addClass("active");
                            }else{
                                obj.removeClass("active");
                                /*
                                obj.parents(".item").addClass("animated zoomOut");
                                setTimeout(function(){
                                    obj.parents(".item").remove();
                                },750);
                                */
                            }
                        }
                    },
                    dataType: "json"

                });
            }

            function createPostGridItem($item_obj){
                var h3 = $("<h3>"),
                    $obj = $item_obj["data"];

                if($obj.post_tag && $obj.post_tag.length){
                    var tag_group = $("<span>").addClass("tag_group");
                    $.each($obj.post_tag, function(index,value){
                        tag_group.append($("<a>").addClass("tag category").attr({href:"/listing?category%5B%5D="+value.term_id,target:"_blank"}).text(value.name));
                    });
                    h3.append(tag_group);
                }

                h3.append($("<a>").addClass("title").attr({href:"/"+$obj.instagrammer.igusername, target:"_blank"}).text($obj.instagrammer.igusername));

                h3.append($("<span>").addClass("tag")
                        .append($("<i>").addClass("fa fa-clock-o"))
                        .append(document.createTextNode($obj.modified)));

                var h4 = $("<h4>").addClass("location");

                if($obj.post_country && $obj.post_country.length){
                    $.each($obj.post_country, function(index, value){
                        h4.append($("<a>").attr({href:"/listing?country%5B%5D="+value.term_id,target:"_blank"}).text(value.name));
                    });
                }


                var item = $("<div>").addClass("item scrollreveal").attr({o:$item_obj.id})
                    .append($("<div>").addClass("wrapper")
                        .append($("<div>").addClass("image")
                                .append(h3)
                                .append($("<a>").addClass("image-wrapper background-image").attr({href:$obj.link,target:"_blank"})
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
                            .append($("<a>")
                                .addClass("fa bookmark " + ($item_obj.bookmark ? "active":""))
                                .attr({href:"#", o:$obj.id, c:'story'})
                                .click(function(e){
                                    e.preventDefault();
                                    bookmarkTrigger($(this).attr("o"), $(this).attr("c"), $(this));
                                })
                            )
                        )
                        .append($("<div>").addClass("description")
                            .append($("<p>").text($obj.caption))
                        )
                        .append(
                            $("<a>").addClass("detail text-caps underline").text("Read").attr({href:$obj.link,target:"_blank"})
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
                            }else{
                                $("#emptybox").addClass("hide");
                            }

                            //add to masonry
                            $.each(rs.result.data, function(index,value){
                                var $temp = createPostGridItem(value);
                              
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

            if($("#bookmarkloadmore").length){
                $("#bookmarkloadmore").click();
            }
        });
    </script>
</body>
</html>