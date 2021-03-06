<?php
    if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){

        //get ongoing and close number
        $project_stats = $main->getProjectManager()->getProjectStats($current_user->ID, $default_group_id);
        $total_ongoing = $project_stats["open"];
        $total_closed = $project_stats["closed"];

        if($default_group_id){
            $business_link = array(
                "label"=>"Businesss Account",
                "link"=>"",
                "icon"=>"fa-users",
                "group"=>array(
                    array(
                        "label"=>$default_group["name"],
                        "link"=>"/user@".$current_user->ID."/business_profile",
                        "icon"=>"fa-users",
                        "query_item"=>"business_profile",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Members",
                        "link"=>"/user@".$current_user->ID."/business_member",
                        "icon"=>"fa-users",
                        "query_item"=>"business_member",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Change Group",
                        "link"=>"/user@".$current_user->ID."/business_group",
                        "icon"=>"fa-arrows-h",
                        "query_item"=>"business_group",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Invitations",
                        "link"=>"/user@".$current_user->ID."/business_invite",
                        "icon"=>"fa-envelope-square",
                        "query_item"=>"business_invite",
                        "query_index"=>2
                    ),
                    array(
                        "label"=>"Payment Method",
                        "link"=>"/user@".$current_user->ID."/business_payment",
                        "icon"=>"fa-credit-card",
                        "query_item"=>"business_payment",
                        "query_index"=>2
                    )
                )
            );
        }else{
            $business_link = array(
                "label"=>"Businesss Account",
                "link"=>"",
                "icon"=>"fa-users",
                "group"=>array(
                    array(
                        "label"=>"Setup",
                        "link"=>"/user@".$current_user->ID."/business_welcome",
                        "icon"=>"fa-flag-checkered",
                        "query_item"=>"business_welcome",
                        "query_index"=>2
                    )
                )
            );
        }

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
            $business_link,
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
        $project_stats = $main->getProjectManager()->getProjectStats($current_user->ID);
        $total_invite = $project_stats["invite"];
        $total_ongoing = $project_stats["open"];
        $total_closed = $project_stats["closed"];


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
                "query_item"=>"signout",
                "query_index"=>1
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
                    if((sizeof($pathquery) > $value2["query_index"]) && $value2["query_item"] == $pathquery[$value2["query_index"]]){
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
                if((sizeof($pathquery) > $value["query_index"]) && $value["query_item"] == $pathquery[$value["query_index"]]){
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