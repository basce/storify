<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../inc/main.php");
use storify\main as main;
$main = new main();

$current_user = wp_get_current_user();

if(!$current_user->ID || $current_user->ID > 3){
    echo "invalid access";
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
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="container">
		<section>
            <h1>Table Report Generator</h1>
            <form id="form1">
                <div class="form-group">
                    <label for="project_ids" class="col-sm-3 control-label">Project IDs <small>(use comma for multiple IDs )</small></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="project_ids" name="project_ids" placeholder="Project IDs" value="" required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Report Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="" required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="password" name="password" placeholder="Password" value="" required="true">
                    </div>
                </div>
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
        </section>
        <section>
            <div class="reports">
                
            </div>
        </section>
	</div>
    <script type="text/javascript" src="/assets/js/papaparse.min.js"></script>
    <script>

        var _addingReport = false;
        function addReport(data, name, password){
            if(_addingReport) return;
            _addingReport = true;
            $.ajax({
                url: "ajax.php",
                method: "POST",
                dataType: "json",
                data:{
                    method:"addReport",
                    data:data,
                    name:name,
                    password:password
                },
                success:function(rs){
                    _addingReport = false;
                    if(rs.error){
                        alert(rs.msg);
                    }else{
                        getAllReport();
                        $("#name").val("");
                        $("#password").val("");
                        $("#project_ids").val("");
                    }
                }
            });
        }

        var _gettingAllReport = false;
        function getAllReport(){
            if(_gettingAllReport) return;
            _gettingAllReport = true;
            $.ajax({
                url: "ajax.php",
                method: "POST",
                dataType: "json",
                data:{
                    method:"getAllReport"
                },
                success:function(rs){
                    _gettingAllReport = false;
                    if(rs.error){
                        alert(rs.msg);
                    }else{
                        $(".reports").empty();
                        $.each(rs.data, function(index,value){
                            var d = buildRow(value);
                            $(".reports").append(d);
                        });
                    }
                }
            });
        }

        function buildRow(data){
            var div = $("<div>");
            div.append(document.createTextNode(data.name + " ( PW : " + data.password + " ) : "));
            div.append($("<a>").attr({target:"_blank", href:data.url}).text("link"));
            

            return div;
        }

        $(document).ready( function() {
          
            $("#form1").submit(function(e){
                e.preventDefault();
                console.log({
                    project_ids:$("#project_ids").val(),
                    name:$("#name").val(),
                    password:$("#password").val()
                });
                addReport($("#project_ids").val(), $("#name").val(), $("#password").val());
            });

            getAllReport();
      });

    </script>
</body>
</html>