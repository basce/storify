<?php
    if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){

        //get ongoing and close number
        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project` WHERE created_by = %d AND status = %s";
        $total_ongoing = $wpdb->get_var($wpdb->prepare($query, $current_user->ID, "open"));
        $total_closed = $wpdb->get_var($wpdb->prepare($query, $current_user->ID, "close"));

        $left_nav_items = array(
            array(
                "label"=>"Boards",
                "link"=>""
            ),
            array(
                "label"=>"Performance",
                "link"=>"/user@".$current_user->ID."/performance",
                "icon"=>"fa-line-chart",
                "query_item"=>"performance",
                "query_index"=>1
            ),
            array(
                "label"=>"Projects",
                "link"=>"",
                "icon"=>"fa-briefcase",
                "group"=>array(
                    array(
                        "label"=>"Ongoing <small class=\"left_menu_ongoing\">(".$total_ongoing.")</small>",
                        "link"=>"/user@".$current_user->ID."/projects/ongoing",
                        "icon"=>"fa-fire",
                        "query_item"=>"ongoing",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Closed <small class=\"left_menu_closed\">(".$total_closed.")</small>",
                        "link"=>"/user@".$current_user->ID."/projects/closed",
                        "icon"=>"fa-archive",
                        "query_item"=>"closed",
                        "query_index"=>2
                    )
                )
            ),
            /*
            array(
                "label"=>"Projects",
                "link"=>"/user@".$current_user->ID."/projects/ongoing",
                "icon"=>"fa-briefcase",
                "query_item"=>"projects",
                "query_index"=>2
            ),
            array(
                "label"=>"Ongoing",
                "link"=>"/user@".$current_user->ID."/projects/ongoing",
                "icon"=>"fa-fire",
                "query_item"=>"ongoing",
                "query_index"=>2
            ),
            array(
                "label"=>"Closed",
                "link"=>"/user@".$current_user->ID."/projects/closed",
                "icon"=>"fa-archive",
                "query_item"=>"closed",
                "query_index"=>2
            ),
            */
            array(
                "label"=>"Collections",
                "link"=>"/user@".$current_user->ID."/collections",
                "icon"=>"fa-book",
                "query_item"=>"collections",
                "query_index"=>1
            ),
            array(
                "label"=>"Settings",
                "link"=>""
            ),
            array(
                "label"=>"Profile",
                "link"=>"/user@".$current_user->ID."/profile",
                "icon"=>"fa-user",
                "query_item"=>"profile",
                "query_index"=>1
            ),
            array(
                "label"=>"Update Password",
                "link"=>"/user@".$current_user->ID."/password",
                "icon"=>"fa-key",
                "query_item"=>"password",
                "query_index"=>1
            ),
            array(
                "label"=>"Sign Out",
                "link"=>"/signout",
                "icon"=>"fa-sign-out",
                "query_item"=>"signout",
                "query_index"=>1
            )
        );

    }else{

        //get ongoing and close number
        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project_invitation` a LEFT JOIN `".$wpdb->prefix."project` b ON a.project_id = b.id WHERE a.user_id = %d AND a.status = %s AND b.status = %s";
        $total_invite = $wpdb->get_var($wpdb->prepare($query, $current_user->ID, "pending", "open"));
        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project_status` WHERE user_id = %d AND status = %s";
        $total_ongoing = $wpdb->get_var($wpdb->prepare($query, $current_user->ID, "open"));
        $total_closed = $wpdb->get_var($wpdb->prepare($query, $current_user->ID, "close"));


        $left_nav_items = array(
            array(
                "label"=>"Boards",
                "link"=>""
            ),
            array(
                "label"=>"Performance",
                "link"=>"/user@".$current_user->ID."/performance",
                "icon"=>"fa-line-chart",
                "query_item"=>"performance",
                "query_index"=>1
            ),
            array(
                "label"=>"Projects",
                "link"=>"",
                "icon"=>"fa-briefcase",
                "group"=>array(
                    array(
                        "label"=>"Invites <small class=\"left_menu_invite\">(".$total_invite.")</small>",
                        "link"=>"/user@".$current_user->ID."/projects/invited",
                        "icon"=>"fa-envelope",
                        "query_item"=>"invited",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Ongoing <small class=\"left_menu_ongoing\">(".$total_ongoing.")</small>",
                        "link"=>"/user@".$current_user->ID."/projects/ongoing",
                        "icon"=>"fa-fire",
                        "query_item"=>"ongoing",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Closed <small class=\"left_menu_closed\">(".$total_closed.")</small>",
                        "link"=>"/user@".$current_user->ID."/projects/closed",
                        "icon"=>"fa-archive",
                        "query_item"=>"closed",
                        "query_index"=>2
                    )
                )
            ),/*
            array(
                "label"=>"Projects",
                "link"=>"/user@".$current_user->ID."/projects/ongoing",
                "icon"=>"fa-briefcase",
                "query_item"=>"projects",
                "query_index"=>2
            ),
            array(
                "label"=>"Invited",
                "link"=>"/user@".$current_user->ID."/projects/invited",
                "icon"=>"fa-envelope",
                "query_item"=>"invited",
                "query_index"=>2
            ),
            array(
                "label"=>"Ongoing",
                "link"=>"/user@".$current_user->ID."/projects/ongoing",
                "icon"=>"fa-fire",
                "query_item"=>"ongoing",
                "query_index"=>2
            ),
            array(
                "label"=>"Closed",
                "link"=>"/user@".$current_user->ID."/projects/closed",
                "icon"=>"fa-archive",
                "query_item"=>"closed",
                "query_index"=>2
            ),*/
            array(
                "label"=>"Collections",
                "link"=>"/user@".$current_user->ID."/collections",
                "icon"=>"fa-book",
                "query_item"=>"collections",
                "query_index"=>1
            ),
            array(
                "label"=>"Settings",
                "link"=>""
            ),
            array(
                "label"=>"Profile",
                "link"=>"/user@".$current_user->ID."/profile",
                "icon"=>"fa-user",
                "query_item"=>"profile",
                "query_index"=>1
            ),
            array(
                "label"=>"Social Showcase",
                "link"=>"/user@".$current_user->ID."/showcase",
                "icon"=>"fa-instagram",
                "query_item"=>"showcase",
                "query_index"=>1
            ),
            array(
                "label"=>"Update Password",
                "link"=>"/user@".$current_user->ID."/password",
                "icon"=>"fa-key",
                "query_item"=>"password",
                "query_index"=>1
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

<nav class="nav flex-column side-nav d-xs-none">
<?php
    foreach($left_nav_items as $key=>$value){
        if(isset($value["group"])){
?>
        <div class="group-nav">
            <span class="nav-link icon" style="position:relative;">
                <i class="fa <?=$value["icon"]?>" style="position:absolute;left:0;top:50%;margin-top:-.55rem;"></i><span style="padding-left:2rem;"><?=$value["label"]?></span>
            </span>
<?php
            foreach($value["group"] as $key2=>$value2){
                if($value2["link"]){
                    if($value2["query_item"] == $pathquery[$value2["query_index"]]){
                        $a_class = "nav-link sub-nav-link active icon";
                    }else{
                        $a_class = "nav-link sub-nav-link icon";
                    }
                }
?>
                <a class="<?=$a_class?>" href="<?=$value2["link"]?>" style="position:relative;">
                    <i class="fa <?=$value2["icon"]?>" style="position:absolute;left:1.5rem;top:50%;margin-top:-.55rem;"></i><span style="padding-left:2rem;"><?=$value2["label"]?></span>
                </a>
<?php                
            }
?>            
        </div>      
<?php            
        }else{
            if($value["link"]){
                if($value["query_item"] == $pathquery[$value["query_index"]]){
                    $a_class = "nav-link active icon";
                }else{
                    $a_class = "nav-link icon";
                }
        ?>
            <a class="<?=$a_class?>" href="<?=$value["link"]?>" style="position:relative;">
                <i class="fa <?=$value["icon"]?>" style="position:absolute;left:0;top:50%;margin-top:-.55rem;"></i><span style="padding-left:2rem;"><?=$value["label"]?></span>
            </a>
        <?php
            }else{
                //not link
                ?>
                <span class="category_label"><?=$value["label"]?></span>
                <?php
            }
        }
    }
?>
</nav>