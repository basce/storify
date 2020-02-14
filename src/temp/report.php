<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../inc/main.php");
use storify\main as main;
$main = new main();

$query = "SELECT name, password, unique_code, data, tt FROM `".$wpdb->prefix."temp_report` WHERE unique_code = %s";
$data = $wpdb->get_row($wpdb->prepare($query, $_REQUEST["code"]), ARRAY_A);

if(!sizeof($data)){
    echo "no data found.";
    exit();
}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/animate.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/user.css">
    <style>
        body{
            overflow-x: auto;
        }
    </style>
    <script>
        window._startTime = new Date().getTime();

        window.getExecuteTime = function(str){
            var currentTime = new Date().getTime();
            console.log(str);
            console.log(currentTime - window._startTime);
        }
    </script>
    <title>
    	
    </title>
    <style>
        textarea.saved {
          border-radius: 3px;
          animation: highlight 1000ms ease-out;
        }
        @keyframes highlight {
          0% {
            background-color: yellow;
          }
          100 {
            background-color: white;
          }
        }
    </style>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<?php
    if( ( isset($_REQUEST["password"]) && ($_REQUEST["password"] !== $data["password"]) ) || !isset($_REQUEST["password"]) ){
?>
    <div class="container">
        <h2><?php if(isset($_REQUEST["password"]) && ($_REQUEST["password"] !== $data["password"])){ echo "Incorrect Password"; }else{ echo "Require password to access"; } ?></h2>
        <form id="form1" action="report.php" method="GET">
            <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="password" name="password" placeholder="Password" value="" required="true">
                    </div>
                </div>
                <input type="hidden" name="code" value="<?=$_REQUEST["code"]?>">
                <div class="form-group">
                    <div class="col-sm-9">
                      <div class="float-right bottom_panel">
                        <button type="submit" form="form1" class="btn btn-primary" value="Submit">Submit</button>
                       </div>
                    </div>
                </div>
            <div class="clearfix">
            </div>
        </form>
    </div>
<?php        
    }else{
?>    
	<div class="fuild-container" style="padding-left:50px; padding-right:50px;">
        <h2><?=$data["name"]?></h2>
        <table class="table">
<?php
    
                $user_ar = array();
                $items = json_decode($data["data"], true);
                if(isset($items[0]["userid"])){
?>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">userid</th>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col" style="width:30%">Caption</th>
                    <th scope="col">File</th>
                    <th scope="col" style="width:320px">Preview</th>
                    <th style="width:20%">Comments</th>
                </tr>
            </thead>
            <tbody>
<?php                    
                    foreach($items as $key=>$value){
                        if(!isset($user_ar[$value["userid"]])){
                            $query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
                            $igusername = $wpdb->get_var($wpdb->prepare($query, $value["userid"]));

                            $user_ar[$value["userid"]] = $igusername;
                        }

                        //get submission

                        $query = "SELECT c.*, d.msg FROM ( SELECT a.id, a.file_id, a.project_id, a.URL, a.remark, b.file_url FROM `".$wpdb->prefix."project_new_submission` a LEFT JOIN `".$wpdb->prefix."project_new_submission_file` b ON a.file_id = b.id WHERE a.id = %d ) c LEFT JOIN `".$wpdb->prefix."temp_report_remark` d ON c.id = d.submission_id";
                        $submission = $wpdb->get_row($wpdb->prepare($query, $value["submit_id"]), ARRAY_A);

                        if(isset($submission["file_url"]) && $submission["file_url"]){
                            //get file
                            $s3file_result = $main->getS3presignedLink($submission["file_url"], "+7days");
    ?>              <tr>
                        <td><?=$value["submit_id"]?></td>
                        <td><?=$user_ar[$value["userid"]]?></td>
                        <td><?=$value["Date"]?></td>
                        <td><?=$value["Type"]?></td>
                        <td><?=$submission["remark"]?></td>
                        <td><a href="<?=$s3file_result["url"]?>" target="_blank">Download File</a></td>
                        <?php
                        $ext = pathinfo($submission["file_url"], PATHINFO_EXTENSION);
                            $movieType = array("mp4", "m4a", "m4v", "f4v", "f4a", "m4b", "m4r", "f4b", "mov");
                            $imageType = array("jpg", "png", "jpeg");
                            if(in_array(strtolower($ext), $movieType)){
                                ?>
                                <td>
                                <video width="320" height="240" controls>
                                  <source src="<?=$s3file_result["url"]?>" type="video/MP4">
                                  Your browser does not support the video tag.
                                </video>
                                </td>
                                <?php
                            }else if(in_array(strtolower($ext), $imageType)){
                                ?>
                                <td>
                                    <img src="<?=$s3file_result["url"]?>" style="max-width: 320px" />
                                </td><?php
                            }else{
                                ?><td>No preview</td><?php
                            }
                            ?>
                        <td>
                            <form class="remark">
                                <textarea name="remark" rows="4" style="width:100%"><?=$submission["msg"]?$submission["msg"]:""?></textarea>
                                <button class="btn btn-primary small" o="<?=$value["submit_id"]?>">Edit</button>
                            </form>
                        </td>
                    </tr>
    <?php
                        }else{
    ?>              <tr>
                        <td><?=$value["submit_id"]?></td>
                        <td><?=$user_ar[$value["userid"]]?></td>
                        <td><?=$value["Date"]?></td>
                        <td><?=$value["Type"]?></td>
                        <td><?=$submission["remark"]?></td>
                        <td><a href="<?=$submission["URL"]?>" target="_blank">Open in new browser</a></td>
                        <td></td>
                        <td>
                            <form class="remark">
                                <textarea name="remark" rows="4" style="width:100%"><?=$submission["msg"]?$submission["msg"]:""?></textarea>
                                <button class="btn btn-primary small" o="<?=$value["submit_id"]?>">Edit</button>
                            </form>
                        </td>
                    </tr>
    <?php
                        }
                    }
                }else{
?>
            <thead>
                <tr>
                    <th scope="col">Project ID</th>
                    <th scope="col">ID</th>
                    <th scope="col">userid</th>
                    <th scope="col" style="width:30%">Caption</th>
                    <th scope="col">File</th>
                    <th scope="col" style="width:320px">Preview</th>
                    <th style="width:20%">Comments</th>
                </tr>
            </thead>
            <tbody>
<?php    
                    $items = explode(",", $items);
                    foreach($items as $key=>$value){
                        //each project id
                        $query = "SELECT c.*, d.msg FROM ( SELECT a.id, a.file_id, a.creator_id as `userid`, a.project_id, a.URL, a.remark, b.file_url FROM `".$wpdb->prefix."project_new_submission` a LEFT JOIN `".$wpdb->prefix."project_new_submission_file` b ON a.file_id = b.id WHERE a.project_id = %d ) c LEFT JOIN `".$wpdb->prefix."temp_report_remark` d ON c.id = d.submission_id";

                        $submission = $wpdb->get_results($wpdb->prepare($query, $value), ARRAY_A);

                        if(sizeof($submission)){
                            foreach($submission as $key2=>$value2){
                                if(!isset($user_ar[$value2["userid"]])){
                                    $query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d";
                                    $igusername = $wpdb->get_var($wpdb->prepare($query, $value2["userid"]));

                                    $user_ar[$value2["userid"]] = $igusername;
                                }

                                if(isset($value2["file_url"]) && $value2["file_url"]){
                                     $s3file_result = $main->getS3presignedLink($value2["file_url"], "+7days");
?>
                    <tr>
                        <td><?=$value2["project_id"]?></td>
                        <td><?=$value2["id"]?></td>
                        <td><?=$user_ar[$value2["userid"]]?></td>
                        <td><?=$value2["remark"]?></td>
                        <td><a href="<?=$s3file_result["url"]?>" target="_blank">Download File</a></td>
                        <?php
                        $ext = pathinfo($value2["file_url"], PATHINFO_EXTENSION);
                            $movieType = array("mp4", "m4a", "m4v", "f4v", "f4a", "m4b", "m4r", "f4b", "mov");
                            $imageType = array("jpg", "png", "jpeg");
                            if(in_array(strtolower($ext), $movieType)){
                                ?>
                                <td>
                                <video width="320" height="240" controls>
                                  <source src="<?=$s3file_result["url"]?>" type="video/MP4">
                                  Your browser does not support the video tag.
                                </video>
                                </td>
                                <?php
                            }else if(in_array(strtolower($ext), $imageType)){
                                ?>
                                <td>
                                    <img src="<?=$s3file_result["url"]?>" style="max-width: 320px" />
                                </td><?php
                            }else{
                                ?><td>No preview</td><?php
                            }
                            ?>
                        <td>
                            <form class="remark">
                                <textarea name="remark" rows="4" style="width:100%"><?=$value2["msg"]?$value2["msg"]:""?></textarea>
                                <button class="btn btn-primary small" o="<?=$value2["id"]?>">Edit</button>
                            </form>
                        </td>
                    </tr>
<?php
                                }else{
?>
                    <tr>
                        <td><?=$value2["project_id"]?></td>
                        <td><?=$value2["id"]?></td>
                        <td><?=$user_ar[$value2["userid"]]?></td>
                        <td><?=$value2["remark"]?></td>
                        <td><a href="<?=$value2["URL"]?>" target="_blank">Open in new browser</a></td>
                        <td></td>
                        <td>
                            <form class="remark">
                                <textarea name="remark" rows="4" style="width:100%"><?=$value2["msg"]?$value2["msg"]:""?></textarea>
                                <button class="btn btn-primary small" o="<?=$value2["id"]?>">Edit</button>
                            </form>
                        </td>
                    </tr>
<?php
                                }
                            }
                        }

                    }
                }
?>
            </tbody>
        </table>
	</div>
<?php
    }
?>
<script>

    var _addingRemark = false;
    function addRemark(id, msg, onComplete){
        if(_addingRemark){
            alert("another progress is trying to save.");
            return;
        } 
        _addingRemark = true;
        $.ajax({
            url: "report_ajax.php",
            method: "POST",
            dataType: "json",
            data:{
                method:"updateRemark",
                id:id,
                msg:msg
            },
            success:function(rs){
                _addingRemark = false;
                if(rs.error){
                    alert(rs.msg);
                }else{
                    console.log(rs);
                    if(onComplete) onComplete();
                }
            }
        });
    }

    $(function(){
        $("form.remark").submit(function(e){
            e.preventDefault();
            var _this = $(this);
            addRemark(_this.find("button").attr("o"), _this.find("textarea").val(), function(){
                _this.find("textarea").addClass("saved");
                setTimeout(function(){
                    _this.find("textarea").removeClass("saved");
                },2000);
            });
        });
    });
</script>
</body>
</html>