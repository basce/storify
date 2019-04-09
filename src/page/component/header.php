                <?php include("second-nav.php"); ?>
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
                                    <li class="nav-item">
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
<?php if($current_user->ID){

    if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){
        $top_nav_1_items = array(
            array(
                "label"=>"Performance",
                "link"=>"/user@".$current_user->ID."/performance",
                "icon"=>"fa-line-chart",
                "query_item"=>"performance"
            ),
            array(
                "label"=>"Projects",
                "link"=>"",
                "icon"=>"fa-briefcase",
                "group"=>array(
                    array(
                        "label"=>"Ongoing",
                        "link"=>"/user@".$current_user->ID."/projects/ongoing",
                        "icon"=>"fa-fire",
                        "query_item"=>"ongoing"
                    ),
                    array(
                        "label"=>"Closed",
                        "link"=>"/user@".$current_user->ID."/projects/closed",
                        "icon"=>"fa-archive",
                        "query_item"=>"closed"
                    )
                )
            ),
            array(
                "label"=>"Collections",
                "link"=>"/user@".$current_user->ID."/collections",
                "icon"=>"fa-book",
                "query_item"=>"collections"
            )
        );
        $top_nav_2_items = array(
            array(
                "label"=>"Profile",
                "link"=>"/user@".$current_user->ID."/profile",
                "icon"=>"fa-user",
                "query_item"=>"profile"
            ),
            array(
                "label"=>"Update Password",
                "link"=>"/user@".$current_user->ID."/password",
                "icon"=>"fa-key",
                "query_item"=>"password"
            ),
            array(
                "label"=>"Use as Creator",
                "link"=>"/user@".$current_user->ID."/viewascreator",
                "icon"=>"fa-people",
                "query_item"=>"viewascreator"
            ),
            array(
                "label"=>"Sign Out",
                "link"=>"/signout",
                "icon"=>"fa-sign-out",
                "query_item"=>"signout"  
            )
        );

    }else{

        $top_nav_1_items = array(
            array(
                "label"=>"Performance",
                "link"=>"/user@".$current_user->ID."/performance",
                "icon"=>"fa-line-chart",
                "query_item"=>"performance"
            ),
            array(
                "label"=>"Projects",
                "link"=>"",
                "icon"=>"fa-briefcase",
                "group"=>array(
                    array(
                        "label"=>"Invited",
                        "link"=>"/user@".$current_user->ID."/projects/invited",
                        "icon"=>"fa-envelope",
                        "query_item"=>"invited"
                    ),
                    array(
                        "label"=>"Ongoing",
                        "link"=>"/user@".$current_user->ID."/projects/ongoing",
                        "icon"=>"fa-fire",
                        "query_item"=>"ongoing"
                    ),
                    array(
                        "label"=>"Closed",
                        "link"=>"/user@".$current_user->ID."/projects/closed",
                        "icon"=>"fa-archive",
                        "query_item"=>"closed"
                    )
                )
            ),
            array(
                "label"=>"Collections",
                "link"=>"/user@".$current_user->ID."/collections",
                "icon"=>"fa-book",
                "query_item"=>"collections"
            )
        );
        $top_nav_2_items = array(
            array(
                "label"=>"Profile",
                "link"=>"/user@".$current_user->ID."/profile",
                "icon"=>"fa-user",
                "query_item"=>"profile"
            ),
            array(
                "label"=>"Social Showcase",
                "link"=>"/user@".$current_user->ID."/showcase",
                "icon"=>"fa-instagram",
                "query_item"=>"showcase"  
            ),
            array(
                "label"=>"Update Password",
                "link"=>"/user@".$current_user->ID."/password",
                "icon"=>"fa-key",
                "query_item"=>"password"
            ),
            array(
                "label"=>"Use as Brand",
                "link"=>"/user@".$current_user->ID."/viewasbrand",
                "icon"=>"fa-people",
                "query_item"=>"viewasbrand"
            ),
            array(
                "label"=>"Sign Out",
                "link"=>"/signout",
                "icon"=>"fa-sign-out",
                "query_item"=>"signout"  
            )
        );
    }

?>
                                    <li class="nav-item has-child">
                                        <a class="nav-link" href="#">Account</a>
                                        <!-- 1st level -->
                                        <ul class="child">
                                            <li class="nav-item has-child">
                                                <a href="#" class="nav-link">Boards</a>
                                                <ul class="child">
                                                    <?php
                                                        foreach($top_nav_1_items as $key=>$value){
                                                            if(isset($value["group"])){
                                                                //group items
                                                                ?>
                                                    <li class="nav-item has-child">
                                                        <a href="#" class="nav-link"><?=$value["label"]?></a>
                                                        <ul class="child">
                                                        <?php
                                                                foreach($value["group"] as $key2=>$value2){
                                                            ?>
                                                            <li class="nav-item">
                                                                <a href="<?=$value2["link"]?>" class="nav-link"><?=$value2["label"]?></a>
                                                            </li>            
                                                            <?php
                                                                }
                                                        ?>
                                                        </ul>
                                                    </li>
                                                        <?php
                                                            }else{
                                                                //single items
                                                        ?>
                                                    <li class="nav-item">
                                                        <a href="<?=$value["link"]?>" class="nav-link"><?=$value["label"]?></a>
                                                    </li>
                                                        <?php
                                                            }
                                                        }
                                                    ?>
                                                </ul>
                                            </li>
                                            <li class="nav-item has-child">
                                                <a href="#" class="nav-link">Settings</a>
                                                <ul class="child">
                                                    <?php
                                                        foreach($top_nav_2_items as $key=>$value){
                                                            if(isset($value["group"])){
                                                                //group items
                                                                ?>
                                                    <li class="nav-item has-child">
                                                        <a href="#" class="nav-link"><?=$value["label"]?></a>
                                                        <ul class="child">
                                                        <?php
                                                                foreach($value["group"] as $key2=>$value2){
                                                            ?>
                                                            <li class="nav-item">
                                                                <a href="<?=$value2["link"]?>" class="nav-link"><?=$value2["label"]?></a>
                                                            </li>            
                                                            <?php
                                                                }
                                                        ?>
                                                        </ul>
                                                    </li>
                                                        <?php
                                                            }else{
                                                                //single items
                                                        ?>
                                                    <li class="nav-item">
                                                        <a href="<?=$value["link"]?>" class="nav-link"><?=$value["label"]?></a>
                                                    </li>
                                                        <?php
                                                            }
                                                        }
                                                    ?>
                                                </ul>
                                            </li>
                                        </ul>
                                        <!-- end 1st level -->
                                    </li>
<?php }else{ ?>
                                    <li class="nav-item has-child">
                                        <a class="nav-link" href="#">Account</a>
                                        <!-- 1st level -->
                                        <ul class="child">
                                            <li class="nav-item">
                                                <a href="/signin" class="nav-link">Sign In</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/signup" class="nav-link">Sign Up</a>
                                            </li>
                                        </ul>
                                        <!-- end 1st level -->
                                    </li>
<?php } ?>
                                    <li class="nav-item">
                                        <a href="/submitcreator" class="btn btn-primary text-caps btn-rounded btn-framed">Submit Creator</a>
                                    </li>            
                                </ul>
                                <!--Main navigation list-->
                            </div>
<?php if(!$header_without_toggle_button){ ?>
                            <!--end navbar-collapse-->
                            <a href="#collapseMainSearchForm" class="main-search-form-toggle" data-toggle="collapse"  aria-expanded="false" aria-controls="collapseMainSearchForm">
                                <i class="fa fa-search"></i>
                                <i class="fa fa-close"></i>
                            </a>
                            <!--end main-search-form-toggle-->
<?php } ?>                            
                        </nav>
                        <!--end navbar-->
<?php
    if(!$header_without_breadcrumbs){
        if(isset($pageSettings) && isset($pageSettings["breadcrumb"]) && sizeof($pageSettings["breadcrumb"])){
            echo '<ol class="breadcrumb">';
                foreach($pageSettings["breadcrumb"] as $key=>$value){
                    if($value["href"] !== ""){
                        echo '<li class="breadcrumb-item"><a href="'.$value["href"].'" '.(isset($value["target"])? 'target="'.$value["target"].'"':'').'>'.$value["label"].'</a></li>';
                    }else{
                        echo '<li class="breadcrumb-item active">'.$value["label"].'</a>';
                    }
                }
            echo '</ol>';
        }
    }
?>
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Main Navigation ================================================================-->