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
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        .collection-items:not(.selectize-input).grid .item .image .editbutton{
            display:none;
            position: absolute;
            top: 1rem;
            right: 3.2rem;
            padding: .2rem;
            background: rgba(0,0,0,0.2);
            color: #FFFFFF;   
        }
        .collection-items:not(.selectize-input).grid .item .image .editbutton:hover{
            color: #ff0000;
        }
        .collection-items:not(.selectize-input).grid .item .image .deletebutton{
            display:none;
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: .2rem .3rem;
            background: rgba(0,0,0,0.2);
            color: #FFFFFF;   
        }
        .collection-items:not(.selectize-input).grid .item .image .deletebutton:hover{
            color: #ff0000;
        }
        .collection-items:not(.selectize-input).grid .item:hover .image .editbutton, .collection-items:not(.selectize-input).grid .item:hover .image .deletebutton{
            display:block;
        }
        .change-class-1{
            font-size: 1.5rem;
            position: relative;
            margin-right: .2rem;
            padding: .8rem;
            border-radius: .3rem;
            width: 4rem;
            display: inline-block;
            text-align: center;
        }
        .change-class-1:hover{
            background-color: rgba(0,0,0,.05);
        }
        .change-class-1.active{
            background-color: #000;
            color: #fff;
        }
        @media only screen and (max-width: 786px) {
            .collection-items:not(.selectize-input).grid .item .image .editbutton, .collection-items:not(.selectize-input).grid .item .image .deletebutton{
                display:block;
            }
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
include("page/component/header.php");

switch($_REQUEST["order"]){
    case "oldest":
        $sortBy = "oldest";
    break;
    case "latest":
    default:
        $sortBy = "latest";
    break;
}
 ?>
                <!--============ Page Title =========================================================================-->
                <div class="page-title">
                    <div class="container">
                        <h1>Story Boards</h1>
                        <h2><?php
                                $story_summary_result = $main->getSummary("story", -2); //only folder
                                echo ($story_summary_result["no_items"] == 1 ? "1 story ":$story_summary_result["no_items"]." stories ")."on ".($story_summary_result["no_folder"] == 1 ? "1 board." : $story_summary_result["no_folder"]." boards.");
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
                                <div class="row">
                                    <div class="col-5">
                                        
                                            <select name="order" id="order" class="small width-200px" data-placeholder="Default Sorting" >
                                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest</option>
                                                <option value="oldest" <?php if($sortBy == "oldest"){ echo "selected";}?>>Oldest</option>
                                            </select>
                                        
                                    </div>
                                    <div class="col-2 text-center">
                                        <a class="addnewbutton" href="#" o="story" title="Add a Story Board" style="font-size:3rem;"><i class="fa fa-plus-square"></i></a>
                                    </div>
                                    <div class="col-5">
                                        <div class="float-right">
                                            <a href="/<?=$pathquery[0]?>/people" class="change-class-1" title="Creator Boards">
                                                <i class="fa fa-file-image-o"></i>
                                            </a>
                                            <span class="change-class-1 active">
                                                <i class="fa fa-child"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!--
                                <div class="d-md-none">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="float-xl-left float-md-left float-sm-none">
                                            </div>
                                        </div>
                                        <div class="col-2 text-center">
                                            <a class="addnewbutton" href="#" o="people" title="add a Story Board" style="font-size:3rem;"><i class="fa fa-plus-square"></i></a>
                                        </div>
                                        <div class="col-7">
                                            <div class="float-right">
                                                <a href="/<?=$pathquery[0]?>/stories" class="change-class-1" title="Creator Boards">
                                                    <i class="fa fa-child"></i>
                                                </a>
                                                <span class="change-class-1 active">
                                                    <i class="fa fa-file-image-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5">
                                            <select name="order" id="order" class="small width-200px" data-placeholder="Default Sorting" >
                                                <option value="latest" <?php if($sortBy == "latest"){ echo "selected";}?>>Newest First</option>
                                                <option value="oldest" <?php if($sortBy == "oldest"){ echo "selected";}?>>Oldest First</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        
                            <div class="section-title clearfix hide" id="emptybox">
                                <div class="float-xl-left float-md-left float-sm-none story_tell_cont">
                                    <p>Add creators to your board by clicking the Save button on their profile.</p>
                                </div>
                                <div class="float-xl-right float-md-right float-sm-none">
                                    <a href="/" class="btn btn-primary float-right">Add Creators</a>
                                </div>
                            </div>
                            <div class="collection-items items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="0" data-sort="<?=$sortBy?>">
                                <div class="item scrollreveal lastitem">
                                    <div class="wrapper">
                                        <div class="image">
                                            <a class="addnew addnewbutton" href="#" o="story" title="Add a Story Board">
                                                <i class="fa fa-plus-square"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="center">
                                <a href="#" class="btn btn-primary btn-framed btn-rounded" id="folderloadmore">Load More</a>
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
    <div class="modal" tabindex="-1" role="dialog" id="dialogmodal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="storify-form custommsg" novalidate>
              <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-10 offset-md-1">
                        <div class="input-group-row">
                            <label for="folder_name" class="col-form-label required">Folder Name</label>
                            <input name="folder_name" type="text" class="form-control" placeholder="Folder Name" value="">
                        </div>
                        <div class="alert alert-danger hide">Please give a name</div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="editmodal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="storify-form custommsg" novalidate>
              <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-10 offset-md-1">
                        <div class="input-group-row">
                            <label for="folder_name" class="col-form-label required">Enter a new name for your Story Board</label>
                            <input name="folder_name" type="text" class="form-control" placeholder="Name of Story Board" value="">
                        </div>
                        <div class="alert alert-danger hide">This is a required field. Please enter a new name for your board.</div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="deletemodal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="storify-form custommsg" novalidate>
              <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-10 offset-md-1">
                        <div class="input-group-row">
                            <label for="folder_name" class="col-form-label required">Delete Story Board</label>
                            <input name="folder_name" type="text" class="form-control" placeholder="DELETE" value="" style="text-transform: uppercase">
                        </div>
                        <div class="alert alert-danger hide">This is a required field. To delete this board, enter DELETE.</div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Delete</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(function(){

            function showEditDialog(id, currentValue){
                $("#editmodal input").val(currentValue);
                $("#editmodal form").attr({o:id});
                $("#editmodal .alert").addClass("hide");
                $("#editmodal").modal();
            }

            function showDeleteDialog(id, currentValue){
                $("#deletemodal input").val("");
                $("#deletemodal form").attr({o:id});
                $("#deletemodal .col-form-label").text("Enter DELETE to confirm the removal of "+currentValue);
                $("#deletemodal .alert").addClass("hide");
                $("#deletemodal").modal();
            }

            function showAddNewDialog(type){
                if(type == "people"){
                    $("#dialogmodal label").text("Enter the name of your Creator Board");
                    $("#dialogmodal .alert").text("You have not entered a name. Please try again.")
                    $("#dialogmodal input").attr({"placeholder":"Name of Creator Board"});
                    $("#addnew").attr({o:"people"});
                }else{
                    //story
                    $("#dialogmodal label").text("Enter the name of your Story Board");
                    $("#dialogmodal .alert").text("You have not entered a name. Please try again.")
                    $("#dialogmodal input").attr({"placeholder":"Name of Story Board"});
                    $("#addnew").attr({o:"story"});
                }
                $("#dialogmodal .alert").addClass("hide");
                $("#dialogmodal input").val("");
                $("#dialogmodal").modal();
            }

            $(".addnewbutton").click(function(e){
                e.preventDefault();
                showAddNewDialog($(this).attr("o"));
            });

            var _adding = false;
            $("#dialogmodal form").submit(function(e){
                e.preventDefault();
                if(_adding) return;
                _adding = true;
                var er = 0,
                    type = $(this).attr("o"),
                    input = $(this).find('input[name="folder_name"]');
                if(input.val() == ""){
                    er++;
                    input.parents(".form-group").find(".alert").removeClass("hide");
                }else{
                    input.parents(".form-group").find(".alert").addClass("hide");
                }

                if(!er){
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        data:{
                            method:"addfolder",
                            name:input.val(),
                            type:type
                        },                        
                        success:function(rs){
                            _adding = false;
                            if(rs.error){
                                console.log(rs.msg);
                            }else{
                                window.location = "/<?=$pathquery[0]?>/collections/stories/"+rs.result;
                            }
                        }
                    });
                }else{
                    _adding = false;
                }
            });

            var _editing = false;
            $("#editmodal form").submit(function(e){
                e.preventDefault();
                if(_editing) return;
                _editing =true;
                var er = 0,
                    id = $(this).attr("o"),
                    input = $(this).find('input[name="folder_name"]');
                if(input.val() == ""){
                    er++;
                    input.parents(".form-group").find(".alert").removeClass("hide");
                }else{
                    input.parents(".form-group").find(".alert").addClass("hide");
                }

                if(!er){
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        data:{
                            method:'editfolder',
                            name:input.val(),
                            id:id
                        },
                        success:function(rs){
                            _editing = false;
                            if(rs.error){
                                console.log(rs.msg);
                            }else{
                                $('.item[o="'+id+'"] .title').text(input.val());
                                $("#editmodal").modal('hide');
                            }
                        }
                    });
                }else{
                    _editing = false;
                }
            });

            var _deleting = false;
            $("#deletemodal form").submit(function(e){
                e.preventDefault();
                if(_deleting) return;
                _deleting = true;
                var er = 0,
                    id = $(this).attr("o"),
                    input = $(this).find('input[name="folder_name"]');

                if(input.val()){
                    if(input.val().toLowerCase() == "delete"){
                        $("#deletemodal .alert").addClass("hide");
                    }else{
                        $("#deletemodal .alert").text("Do you want to remove this board? To confirm, enter DELETE.").removeClass("hide");
                        er++;                        
                    }
                }else{
                    $("#deletemodal .alert").text("This is a required field. To delete this board, enter DELETE.").removeClass("hide");
                    er++;
                }

                if(!er){
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        data:{
                            method:'deletefolder',
                            id:id
                        },
                        success:function(rs){
                            _deleting = false;
                            if(rs.error){
                                console.log(rs.msg);
                            }else{
                                $("#deletemodal").modal('hide');
                                var tempitem = $('.item[o="'+id+'"]');
                                tempitem.addClass("animated fadeOut");
                                setTimeout(function(e){
                                    tempitem.remove();
                                },750);
                            }
                        }
                    });
                }else{
                    _deleting = false;
                }

            });

            var slideUp = {
                scale: 0.5,
                opacity: null
            };

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
                */
                h3.append($("<span>").addClass("tag")
                                            .append($("<i>").addClass("fa fa-clock-o"))
                                            .append(document.createTextNode($obj.modified)));

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

                var item = $("<div>").addClass("item scrollreveal")
                            .attr({o:$item_obj.id})
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
                                .append(
                                    $("<a>").addClass("detail text-caps underline").text("Details").attr({href:"/"+$obj.igusername+"/"})
                                    )
                            );

                return item;
            }

            function createFolder($folder_obj){
                var link = '/<?=$pathquery[0]?>/collections/stories/'+$folder_obj.id,
                    bgdiv,i;
                if($folder_obj.first_four.length == 0){
                    bgdiv = $("<a>").addClass("image-wrapper background-image-empty").attr({href:link});
                }else if($folder_obj.first_four.length == 1){
                    bgdiv = $("<a>").addClass("image-wrapper background-image").attr({href:link}).css({"background-image":'url('+$folder_obj.first_four[0].data.image_hires+')'})
                                .append($("<img>").attr({src:$folder_obj.first_four[0].data.image_hires}));
                }else{
                    bgdiv = $("<a>").addClass("image-wrapper multiple").attr({href:link});
                    for(i=0; i < 4; i++){
                        if(i < $folder_obj.first_four.length){
                            bgdiv.append(
                                $("<span>").addClass("background-image").css({"background-image":'url('+$folder_obj.first_four[i].data.image_hires+')'})
                                    .append($("<img>").attr({src:$folder_obj.first_four[i].data.image_hires}))
                                );
                        }else{
                            bgdiv.append(
                                $("<span>").addClass("background-image")
                                );
                        }
                    }
                }

                return $("<div>").addClass("item scrollreveal").attr({o:$folder_obj.id})
                            .append($("<div>").addClass("wrapper")
                                .append($("<div>").addClass("image background-empty")
                                    .append($("<h3>")
                                        .append($("<a>").addClass("title").attr({href:link}).text($folder_obj.name))
                                        .append($("<span>").addClass("tag")
                                            .append($("<i>").addClass("fa fa-clock-o"))
                                            .append(document.createTextNode($folder_obj.modified))
                                            )
                                        )
                                    .append(bgdiv)
                                    .append($("<a>").addClass("fa fa-pencil-square-o editbutton").attr({o:$folder_obj.id,href:"#"})
                                        .click(function(e){
                                            e.preventDefault();
                                            showEditDialog($folder_obj.id, $folder_obj.name);
                                        })
                                    )
                                    .append($("<a>").addClass("fa fa-trash-o deletebutton").attr({o:$folder_obj.id,href:"#"})
                                        .click(function(e){
                                            e.preventDefault();
                                            showDeleteDialog($folder_obj.id, $folder_obj.name);
                                        })
                                    )
                                )
                            );
            }

            function updateFolders(){
                var $grid = $(".items.grid"),
                    cur_page = parseInt($grid.attr("data-page"), 10),
                    sort = $grid.attr("data-sort");

                cur_page = cur_page ? cur_page + 1 : 1;

                $("#folderloadmore").html("Loading <i class=\"fa fa-spinner fa-spin\"></i>");
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    data:{
                        method:"getFolders",
                        page:cur_page,
                        sort:sort
                    },
                    success:function(rs){
                        if(rs.error){
                            alert(rs.msg);
                        }else{
                            $grid.attr({'data-page':rs.result.page});
                            $("#folderloadmore").text("Load More").blur();
                            if(parseInt(rs.result.page, 10) < parseInt(rs.result.totalpage, 10)){
                                $("#folderloadmore").css({display:"inline-block"});
                            }else{
                                $("#folderloadmore").css({display:"none"});
                            }

                            //add to masonry
                            console.log(rs);
                            $.each(rs.result.data, function(index,value){
                                var $temp = createFolder(value);
                                $temp.click(function(e){
                                    if($(this).parents(".editmode").length){
                                        e.preventDefault();
                                        $(this).toggleClass('selected');
                                    }
                                });
                                $grid.find(".lastitem").before($temp);
                                ScrollReveal().reveal($temp, slideUp);
                            });
                        }
                    }
                })
            }

            $("#folderloadmore").click(function(e){
                e.preventDefault();
                updateFolders();
            });

            $("#folderloadmore").click();
        });
    </script>
</body>
</html>