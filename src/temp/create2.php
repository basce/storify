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
                    <label for="file" class="col-sm-3 control-label">CSV File</label>
                    <div class="col-sm-9" style="display:table;">
                      <label class="input-group-btn" style="position: relative;font-size: 0;white-space: nowrap;width: 1%;vertical-align: middle;display: table-cell;">
                        <span class="btn btn-primary">
                            Browse… <input type="file" id="file" name="file" style="display: none;" accept=".csv" single="">
                        </span>
                    </label>
                    <input type="text" class="form-control" id="display_label" readonly="">
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
                        $("#display_label").val("");
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

        var _data;
        function completeFn(results)
        {
            _data = results.data;
            console.log(results);
        }


        function errorFn(err, file)
        {
            console.log("ERROR:", err, file);
        }

        function buildConfig()
        {
            return {
                delimiter: '',
                header: true,
                dynamicTyping: false,
                skipEmptyLines: true,
                preview: 0,
                step: undefined,
                encoding: '',
                worker: false,
                comments: '',
                complete: completeFn,
                error: errorFn,
                download: false
            };
        }

        function processing(){

        }

        function ready(){

        }

        function validdatefile(){
            processing();
            var files = $(".input-group-btn input").prop("files");

            if(files.length){
                $.each(files, function(index,f){
                    var reader = new FileReader();
                    var name = f.name;

                    reader.onload = function(e){
                        var data = e.target.result;
                        Papa.parse(data, buildConfig());
                    }
                    reader.readAsText(f);
                });
            }else{
                processing();
            }
        }

        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);

            $("#display_label").val(label);

            validdatefile();
        });

        $(document).ready( function() {
          $(':file').on('fileselect', function(event, numFiles, label) {

              var input = $(this).parents('.input-group').find(':text'),
                  log = numFiles > 1 ? numFiles + ' files selected' : label;

              if( input.length ) {
                  input.val(log);
              } else {
                  if( log ) alert(log);
              }

          });

        $("#form1").submit(function(e){
            e.preventDefault();
            console.log({
                data:_data,
                name:$("#name").val(),
                pw:$("#password").val()
            });
            addReport(_data, $("#name").val(), $("#password").val());
        });

        getAllReport();
      });

    </script>
</body>
</html>