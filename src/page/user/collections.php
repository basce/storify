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
                        <h1>Collections</h1>
                        <h2>
                            <?php
                                $people_summary_result = $main->getSummary("people", 0); //all
                                $story_summary_result = $main->getSummary("story", 0); //all

                                $total_creators = $people_summary_result["no_items"];
                                $total_stories = $story_summary_result["no_items"];
                                $total_folders = $people_summary_result["no_folder"] + $story_summary_result["no_folder"];

                                echo ($total_creators != 1 ? $total_creators." creators ":"1 creator ")."and ".($total_stories != 1 ? $total_stories." stories":"1 story").".";
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
                            <h2><?php
                            echo $people_summary_result["no_folder"] == 1 ? "1 Creator Board":$people_summary_result["no_folder"]." Creator Boards";
                            ?></h2>
                            <div class="collection-items items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="1" data-sort="">
                                <div class="item scrollreveal">
                                    <div class="wrapper">
                                        <div class="image background-empty">
                                            <h3>
                                                <a class="title" href="/user@<?=$userID?>/collections/people">All</a>
                                            </h3>
                                            <?php
                                                $bookmark_people = $main->getBookmark("people");
                                                if(sizeof($bookmark_people["data"]) == 0){
                                            ?>
                                            <a class="image-wrapper background-image-empty" href="/user@<?=$userID?>/collections/people">
                                                
                                            </a>
                                            <?php
                                                }else if(sizeof($bookmark_people["data"]) <= 1){
                                            ?>
                                            <a class="image-wrapper background-image" href="/user@<?=$userID?>/collections/people">
                                                <img src="<?=$bookmark_people["data"][0]["ig_profile_pic"]?>">
                                            </a>
                                            <?php
                                                }else{
                                            ?>
                                            <a class="image-wrapper multiple" href="/user@<?=$userID?>/collections/people">
                                                <?php
                                                    for($i = 0 ; $i < 4 ; $i++){
                                                        if($i < sizeof($bookmark_people["data"])){
                                                            ?>
                                                <span class="background-image">
                                                    <img src="<?=$bookmark_people["data"][$i]["ig_profile_pic"]?>">
                                                </span>
                                                            <?php
                                                        }else{
                                                            ?><span class="background-image"></span><?php
                                                        }
                                                    }
                                                ?>                                                
                                            </a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    //other bookmark group
                                    $bkgroup4 = $main->getGroup("people", "", 4,1);
                                    if(sizeof($bkgroup4["data"])){
                                        foreach($bkgroup4["data"] as $key=>$value){
                                            $link = "/".$pathquery[0]."/".$pathquery[1]."/people/".$value["id"];
                                            ?> 
                                            <div class="item scrollreveal">
                                                <div class="wrapper">
                                                    <div class="image background-empty">
                                                        <h3>
                                                            <a class="title" href="<?=$link?>"><?=htmlspecialchars($value["name"])?></a>
                                                            <span class="tag"><i class="fa fa-clock-o"></i><?=$value["modified"]?></span>
                                                        </h3>
                                                        <?php
                                                            if(sizeof($value["first_four"]) == 0){
                                                        ?>
                                                        <a class="image-wrapper background-image-empty" href="<?=$link?>">
                                                
                                                        </a>
                                                        <?php
                                                            }else if(sizeof($value["first_four"]) == 1){
                                                        ?>
                                                        <a class="image-wrapper background-image" href="<?=$link?>">
                                                            <img src="<?=$value["first_four"][0]["data"]["ig_profile_pic"]?>">
                                                        </a>
                                                        <?php
                                                            }else{
                                                        ?>
                                                        <a class="image-wrapper multiple" href="<?=$link?>">
                                                            <?php
                                                            for($i = 0; $i < 4; $i++){
                                                                if($i < sizeof($value["first_four"])){
                                                                    ?>
                                                                    <span class="background-image">
                                                                        <img src="<?=$value["first_four"][$i]["data"]["ig_profile_pic"]?>">
                                                                    </span>
                                                                    <?php
                                                                }else{
                                                                    ?><span class="background-image"></span><?php
                                                                }
                                                            }
                                                            ?>
                                                            <?php
                                                            ?>
                                                        </a>
                                                        <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                                <div class="item scrollreveal">
                                    <div class="wrapper">
                                        <div class="image">
                                            <a class="addnew addnewbutton" href="#" o="people" title="Add a Creator Board">
                                                <div style="text-align:center">
                                                    <i class="fa fa-plus-square"></i>
                                                    <?php /* <p style="display:block;height:auto">
                                                        Add a Creator Board
                                                    </p> */ ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <section class="clearfix">
                                <a href="/user@<?=$userID?>/people" class="btn btn-primary float-right">More</a>
                            </section>
                            <hr>
                            <h2><?php
                            echo $story_summary_result["no_folder"] == 1 ? "1 Story Board":$story_summary_result["no_folder"]." Story Boards";
                            ?></h2>
                            <div class="collection-items items grid grid-xl-3-items grid-lg-3-items grid-md-3-items" data-page="1" data-sort="" data-nc_data="post" data-nc_iger="683">
                                <div class="item scrollreveal">
                                    <div class="wrapper">
                                        <div class="image background-empty">
                                            <h3>
                                                <a class="title" href="/user@<?=$userID?>/collections/stories">All</a>
                                            </h3>
                                            <?php
                                                $bookmark_story = $main->getBookmark("story");
                                                if(sizeof($bookmark_story["data"]) == 1){
                                            ?>
                                            <a class="image-wrapper background-image-empty" href="/user@<?=$userID?>/collections/stories">
                                                
                                            </a>
                                            <?php
                                                }else if(sizeof($bookmark_story["data"]) <= 1){
                                            ?>
                                            <a class="image-wrapper background-image" href="/user@<?=$userID?>/collections/stories">
                                                <span class="background-image">
                                                    <img src="<?=$bookmark_story["data"][0]["image_hires"]?>">
                                                </span>
                                            </a>
                                            <?php
                                                }else{
                                            ?>
                                            <a class="image-wrapper multiple" href="/user@<?=$userID?>/collections/stories">
                                                <?php
                                                    for($i = 0 ; $i < 4 ; $i++){
                                                        if($i < sizeof($bookmark_story["data"])){
                                                            ?>
                                                <span class="background-image">
                                                    <img src="<?=$bookmark_story["data"][$i]["image_hires"]?>">
                                                </span>
                                                            <?php
                                                        }else{
                                                            ?><span class="background-image"></span><?php
                                                        }
                                                    }
                                                ?>                                                
                                            </a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    //other bookmark group
                                    $bkgroup4 = $main->getGroup("story", "", 4,1);
                                    if(sizeof($bkgroup4["data"])){
                                        foreach($bkgroup4["data"] as $key=>$value){
                                            $link = "/".$pathquery[0]."/".$pathquery[1]."/stories/".$value["id"];
                                            ?> 
                                            <div class="item scrollreveal">
                                                <div class="wrapper">
                                                    <div class="image background-empty">
                                                        <h3>
                                                            <a class="title" href="<?=$link?>"><?=htmlspecialchars($value["name"])?></a>
                                                            <span class="tag"><i class="fa fa-clock-o"></i><?=$value["modified"]?></span>
                                                        </h3>
                                                        <?php
                                                            if(sizeof($value["first_four"]) == 0){
                                                        ?>
                                                        <a class="image-wrapper background-image-empty" href="<?=$link?>">
                                                
                                                        </a>
                                                        <?php
                                                            }else if(sizeof($value["first_four"]) == 1){
                                                        ?>
                                                        <a class="image-wrapper background-image" href="<?=$link?>">
                                                            <img src="<?=$value["first_four"][0]["data"]["image_hires"]?>">
                                                        </a>
                                                        <?php
                                                            }else{
                                                        ?>
                                                        <a class="image-wrapper multiple" href="<?=$link?>">
                                                            <?php
                                                            for($i = 0; $i < 4; $i++){
                                                                if($i < sizeof($value["first_four"])){
                                                                    ?>
                                                                    <span class="background-image">
                                                                        <img src="<?=$value["first_four"][$i]["data"]["image_hires"]?>">
                                                                    </span>
                                                                    <?php
                                                                }else{
                                                                    ?><span class="background-image"></span><?php
                                                                }
                                                            }
                                                            ?>
                                                            <?php
                                                            ?>
                                                        </a>
                                                        <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                                <div class="item scrollreveal">
                                    <div class="wrapper">
                                        <div class="image">
                                            <a class="addnew addnewbutton" href="#" o="story" title="Add a Story Board">
                                                <div style="text-align:center">
                                                    <i class="fa fa-plus-square"></i>
                                                    <?php /* <p style="display:block;height:auto">
                                                        Add a Story Board
                                                    </p> */ ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <section class="clearfix">
                                <a href="/user@<?=$userID?>/stories" class="btn btn-primary float-right">More</a>
                            </section>
                            <hr>
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
          <div class="modal-body">
            <div class="row">
                <div class="form-group storify-form col-md-10 offset-md-1">
                    <div class="input-group-row">
                        <label for="folder_name" class="col-form-label required">Folder Name</label>
                        <input name="folder_name" type="text" class="form-control" id="folder_name" placeholder="Folder Name" value="" required>
                    </div>
                    <div class="alert alert-danger hide">You have not entered a name. Please try again.</div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="addnew">Add</button>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $(function(){
            function showAddNewDialog(type){
                if(type == "people"){
                    $("#dialogmodal label").text("Enter the name of your Creator Board");
                    $("#dialogmodal .error").text("You have not entered a name. Please try again.")
                    $("#dialogmodal input").attr({"placeholder":"Name of Creator Board"});
                    $("#addnew").attr({o:"people"});
                }else{
                    //story
                    $("#dialogmodal label").text("Enter the name of your Story Board");
                    $("#dialogmodal .error").text("You have not entered a name. Please try again.")
                    $("#dialogmodal input").attr({"placeholder":"Name of Story Board"});
                    $("#addnew").attr({o:"story"});
                }
                $("#folder_name").parents(".form-group").find(".alert").addClass("hide");
                $("#dialogmodal input").val("");
                $("#dialogmodal").modal();
            }

            $(".addnewbutton").click(function(e){
                e.preventDefault();
                showAddNewDialog($(this).attr("o"));
            });

            var _adding = false;
            $("#addnew").click(function(e){
                e.preventDefault();
                if(_adding) return;
                _adding = true;
                var er = 0,
                    type = $(this).attr("o"),
                    linktype;
                if($("#folder_name").val() == ""){
                    er++;
                    $("#folder_name").parents(".form-group").find(".alert").removeClass("hide");
                }else{
                    $("#folder_name").parents(".form-group").find(".alert").addClass("hide");
                }

                if(type == "people"){
                    linktype = "people";
                }else{
                    linktype = "stories";
                }


                if(!er){
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        data:{
                            method:"addfolder",
                            name:$("#folder_name").val(),
                            type:type
                        },                        
                        success:function(rs){
                            _adding = false;
                            if(rs.error){
                                console.log(rs.msg);
                            }else{
                                window.location = "/<?=$pathquery[0]."/".$pathquery[1]."/"?>"+linktype+"/"+rs.result;
                            }
                        }
                    });
                }else{
                    _adding = false;
                }
            });
        });
    </script>
</body>
</html>