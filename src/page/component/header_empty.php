                <?php include("second-nav.php"); ?>
                <!--============ End Secondary Navigation ===========================================================-->
                <!--============ Main Navigation ====================================================================-->
                <div class="main-navigation">
                    <div class="container">
                        <nav class="navbar navbar-expand-lg navbar-light justify-content-between">
                            <a class="navbar-brand" href="<?=get_home_url()?>"  target="_self">
                                <img src="<?=pods_image_url(get_option('custom_settings_logo_image'), null)?>" width="129" alt="">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbar">
                                <!--Main navigation list-->
                                <ul class="navbar-nav">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="<?=get_home_url()?>">Home</a>
                                    </li>
                                    <li class="nav-item has-child">
                                        <a class="nav-link" href="#">Followed</a>
                                        <!-- 1st level -->
                                        <ul class="child">
                                            <li class="nav-item">
                                                <a href="/listing?country%5B%5D=4" class="nav-link">Singapore</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/listing?country%5B%5D=14" class="nav-link">Malaysia</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/listing?country%5B%5D=60" class="nav-link">Indonesia</a>
                                            </li>
                                        </ul>
                                        <!-- end 1st level -->
                                    </li>
                                    <li class="nav-item">
                                        <a href="/submitcreator" class="btn btn-primary text-caps btn-rounded btn-framed">Submit Creator</a>
                                    </li>
                                </ul>
                                <!--Main navigation list-->
                            </div>
                        </nav>
                        <!--end navbar-->
<?php
    $temp_url=strtok($_SERVER["REQUEST_URI"],'?');
    $temp_paths = trim($temp_url,"/");
    $temp_paths_ar = explode("/", $temp_paths);
    $breadcrumbs = array();
    if(sizeof($temp_paths_ar)){
        if(sizeof($temp_paths_ar) == 1){
            $breadcrumbs[] = array(
                "label"=>"home",
                "target"=>"_self",
                "url"=>"/"
            );
            switch($temp_paths_ar[0]){
                case "listing":
                    $breadcrumbs[] = array(
                        "label"=>"search",
                        "target"=>"",
                        "url"=>""
                    );
                break;
                default:
                    $breadcrumbs[] = array(
                        "label"=>$temp_paths_ar[0],
                        "target"=>"",
                        "url"=>""
                    );
                break;
            }
        }
    }

    if(sizeof($breadcrumbs)){
        echo '<ol class="breadcrumb">';
            foreach($breadcrumbs as $key=>$value){
                if($value["url"] !== ""){
                    echo '<li class="breadcrumb-item"><a href="'.$value["url"].'" target="'.$value["target"].'">'.$value["label"].'</a></li>';
                }else{
                    echo '<li class="breadcrumb-item active">'.$value["label"].'</a>';
                }
            }
        echo '</ol>';
    }
?>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Main Navigation ================================================================-->