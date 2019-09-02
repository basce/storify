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
            console.log(currentTime - window._startTime);
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
    <style type="text/css">
        .selectize-control.customselect{
            padding:0;
        }
        .selectize-control.customselect .selectize-input.items{
            box-shadow: none;
            border: none;
        }
        .single-file-input input[type="file"] {
            box-shadow: none;
            border: none;
            color: transparent;
            background-color: transparent;
            padding: 4rem 0 0;
            font-size: inherit;
        }
        .box{
            margin-bottom: 3rem;
        }
        .box .header{
            margin: -3rem -3rem 3rem;
            padding: 1.5rem 3rem;
            background: lightgray;
        }
        .box .header .title{
            font-size: 2rem;
            font-weight: 700;
        }
        .box .header .btn{
            position: absolute;
            top: 1.5rem;
            right: 3rem;
        }
        .member{
            width: 45%;
            margin-right:5%;
            float: left;
            position: relative;
        }
        .member .profile{
            position:absolute;
            top:0;
            left:0;
        }
        .member .profile img{
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .member .right_cnt{
            height: 50px;
            vertical-align: middle;
            display: table-cell;
            padding-left: 55px;
        }
        .member .right_cnt .text{
            padding-right: 10px;
            word-break: break-all;
            display: block;
        }
        .member .close{
            position:absolute;
            top:0;
            right:0;
            display:none;
        }
        .member:hover .close{
            display:block;
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
                        <h1>Business Account Management</h1>
                        <h2>[[description]]</h2>
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
                                
                            <h2>Members</h2>
                            <div class="box">
                                <div class="header">
                                    <div class="title">Owners</div>
                                    <a href="#" class="btn btn-primary small" id="addOwner">Add</a>
                                </div>
                                <div class="body">
                                    <div class="member">
                                        <a href="#" target="_blank" class="profile">
                                            <img class="" src="https://cdn.storify.me/data/uploads/2019/08/winnie-loves.jpg" />
                                        </a>
                                        <div class="right_cnt">
                                            <span class="text">winnie.loves</span>
                                        </div>
                                        <button class="close" type="button" aria-label="Remove"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="member">
                                        <a href="#" target="_blank" class="profile">
                                            <img class="" src="https://via.placeholder.com/50" />
                                        </a>
                                        <div class="right_cnt">
                                            <span class="text">sampletext@email.com (pending)</span>
                                        </div>
                                        <button class="close" type="button" aria-label="Remove"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="box">
                                <div class="header">
                                    <div class="title">Managers</div>
                                    <a href="#" class="btn btn-primary small" id="addManager">Add</a>
                                </div>
                                <div class="body">
                                    <div class="member">
                                        <a href="#" target="_blank" class="profile">
                                            <img class="" src="https://cdn.storify.me/data/uploads/2019/08/winnie-loves.jpg" />
                                        </a>
                                        <div class="right_cnt">
                                            <span class="text">winnie.loves</span>
                                        </div>
                                        <button class="close" type="button" aria-label="Remove"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="member">
                                        <a href="#" target="_blank" class="profile">
                                            <img class="" src="https://via.placeholder.com/50" />
                                        </a>
                                        <div class="right_cnt">
                                            <span class="text">sampletext@email.com (pending)</span>
                                        </div>
                                        <button class="close" type="button" aria-label="Remove"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <!--end page-->
    <script type="text/javascript">

        function sendInvite(email, type, callback){
            $.ajax({
                type:"POST",
                dataType:'json',
                data:{
                    email:email,
                    role:type,
                    method:"addMember"
                },
                error:function(){
                    callback();
                },
                success:function(res){
                    callback(res);
                }
            });
        }

        function removeMember(email, callback){
            $.ajax({
                type:"POST",
                dataType:'json',
                data:{
                    email:email,
                    method:"removeMember"
                },
                error:function(){
                    callback();
                },
                success:function(res){
                    /*
                    [{name:xxx,value:xxxx},{name:xxx,value:xxxx}]
                     */
                    callback(res.data);
                }
            });
        }

        $(function(){

            $("#addOwner").click(function(e){
                e.preventDefault();
            });

            $("#addManager").click(function(e){
                e.preventDefault();
            });
        });
    </script>
</body>
</html>