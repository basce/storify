<?php  
include("inc/main.php");
?><!DOCTYPE html>
<html>
    <head>
        <title>Demo with jQuery</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="Demo project with jQuery">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <style type="text/css"></style>
    </head>
    <body>
        <form id="upload_form" enctype="multipart/form-data" method="post">
            <input type="file" name="file1" id="file1"><br>
            <input type="button" value="Upload File" onclick="uploadFile();">
        </form>
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript">
            function uploadFile(){
                var file = $("#file1")[0].files[0];
                var formdata = new FormData();
                formdata.append("file1", file);
                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", progressHandler, false);
                ajax.addEventListener("load", completeHandler, false);
                ajax.addEventListener("error", errorHandler, false);
                ajax.addEventListener("abort", abortHandler, false);
                ajax.open("POST", "test_upload_receiver.php");
                ajax.send(formdata);
            }

            function progressHandler(event){
                console.log(event.loaded + " / "+ event.total);
            }

            function completeHandler(event){
                console.log("upload complete");
            }
            function errorHandler(event){
                console.log("upload error");
            }
            function abortHandler(event){
                console.log("upload abort");
            }
            jQuery(function(){
                
            });
        </script>
    </body>
</html>