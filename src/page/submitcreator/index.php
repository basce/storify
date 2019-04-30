<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php include("page/component/meta.php"); ?>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/selectize.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/user.css">
    <link rel="stylesheet" href="/assets/css/submitcreator.css">
    <link rel="stylesheet" href="/assets/css/loading.css">
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
    <script src="/assets/js/storify.loading.js"></script>
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
                <div class="page-title">
                    <div class="container">
                        <h1>Psst, know a great storyteller? tip us off.</h1>
                        <h2>Provide his or her details below</h2>
                    </div>
                    <!--end container-->
                </div>
            </div>
        </header>
        <section class="content">
            <section class="block">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <form class="form clearfix" novalidate="novalidate" method="POST" id="submitcreatorform">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">@</span>
                                        </div>
                                        <input name="igusername" type="text" class="form-control" id="igusername" placeholder="IG Username">
                                    </div>
                                    <div class="alert alert-danger hide">Please enter Creator's username</div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label required">Country</label>
                                    <select name="country[]" id="country" data-placeholder="Select country/city" class="customselect" data-enable-input=true multiple nc-method="addCountry">
                                        <option value="">Select country/city</option>
                                        <?php
                                            $category_tags = $main->getAllCountries();
                                            foreach($category_tags as $key=>$value){
                                                if(isset($value["hidden"]) && $value["hidden"]){
                                                    //hidden value, not need to show
                                                }else{
                                                    ?><option value="<?=$value["term_id"]?>"><?=$value["name"]?></option><?php
                                                }
                                            }
                                        ?>
                                    </select>
                                    <div class="alert alert-danger hide">Please enter Creator's country</div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label required">Passions</label>
                                    <select name="category[]" id="category" data-placeholder="Select passion" class="customselect" data-enable-input=true nc-method="addCategory" multiple>
                                        <option value="">Select passion</option>
                                        <?php
                                            $language_tags = $main->getAllTags();
                                            foreach($language_tags as $key=>$value){
                                                if(isset($value["hidden"]) && $value["hidden"]){
                                                }else{
                                                    ?><option value="<?=$value["term_id"]?>"><?=$value["name"]?></option><?php
                                                }
                                            }
                                        ?>
                                    </select>
                                    <div class="alert alert-danger hide">Please enter Creator's passions</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p>
                                        
                                    </p> 
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!--end col-md-6-->
                    </div>
                    <!--end row-->
                </div>
                <!--end container-->
            </section>
            <!--end block-->
        </section>
    <?php include("page/component/footer.php"); ?>
    </div>
    <modal class="modal" tabindex="-1" role="dialog" id="dialogmodal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Thank you for your tip off. We will invite @<span class="igname"></span> to our great community and you might just see great stories from him or her soon!</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </modal>
    <!--end page-->
    <script type="text/javascript">
        function formReady(){
            var er = 0;
            if($("#igusername").val()){
                $("#igusername").parent().parent().find(".alert").addClass("hide");
            }else{
                $("#igusername").parent().parent().find(".alert").removeClass("hide");
                er++;
            }
            if($("#country").val().length){
                $("#country").parent().find(".alert").addClass("hide");
            }else{
                $("#country").parent().find(".alert").removeClass("hide");
                er++;
            }

            if($("#category").val().length){
                $("#category").parent().find(".alert").addClass("hide");
            }else{
                $("#category").parent().find(".alert").removeClass("hide");
                er++;
            }

            return !er;
        }

        $("#submitcreatorform").submit(function(e){
            e.preventDefault();
            if(formReady()){
                storify.loading.show();
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data:{
                        igusername:$("#igusername").val(),
                        countries:$("#country").val(),
                        language:[],
                        category:$("#category").val(),
                        method:"submitcreator"
                    },
                    success:function(data){
                        storify.loading.hide();
                        if(data.error){
                            alert(data.msg);
                        }else{
                            //show modal
                        }
                        $("span.igname").text($("#igusername").val());
                        $("#igusername").val("");
                        $("#country")[0].selectize.setValue();
                        $("#category")[0].selectize.setValue();
                        $("#dialogmodal").modal("show");
                    }
                })
            }else{
                //do nothing, wait user input
            }
        });
    </script>
</body>
</html>