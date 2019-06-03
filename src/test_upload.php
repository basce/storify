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
            <input type="button" value="Upload File">
        </form>
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script type="text/javascript">
            function uploadFile(file, url){
                /*
                var formdata = new FormData();
                formdata.append("file", file);

                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", progressHandler, false);
                ajax.addEventListener("load", completeHandler, false);
                ajax.addEventListener("error", errorHandler, false);
                ajax.addEventListener("abort", abortHandler, false);
                ajax.open('PUT', url, true);
                ajax.setRequestHeader("Content-Type", file.type);
                ajax.send(file);
                */
                
                $.ajax({
                    xhr: function() {
                       var xhr = new window.XMLHttpRequest();
                       xhr.upload.addEventListener("progress", progressHandler, false);
                       xhr.addEventListener("progress", progressHandler, false);
                       return xhr;
                    },
                    url:url,
                    type:"PUT",
                    data:file,
                    processData:false,
                    contentType:false,
                    success:completeHandler,
                    error:errorHandler
                });
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

            function getPresignedURL(filename, type, onComplete){
                $.ajax({
                    url:"get_presigned_url.php",
                    type:"POST",
                    data:{
                        filename:filename,
                        mime:type
                    },
                    success:function(rs){
                        console.log(rs);
                        if(onComplete){
                            onComplete(rs.url)
                        }
                    },
                    error:function(xhr, status, errorThrown){
                        console.log("error");
                    },
                    dataType:"json"
                });
            }

            jQuery(function(){
                $("#file1").change(function(e){
                    if($("#file1")[0].files.length){
                        getPresignedURL($("#file1")[0].files[0].name, $("#file1")[0].files[0].type, function(url){
                            uploadFile($("#file1")[0].files[0], url);
                        });
                    }
                });
            });
        </script>
    </body>
</html>