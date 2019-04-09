                <!--============ Secondary Navigation ===============================================================-->
                <div class="secondary-navigation">
                    <div class="container">
                        <ul class="left">
                            
                        </ul>
                        <!--end left-->                        
                        <ul class="right">
<?php if($current_user->ID){ ?>
                            <li>
                                <a href="\user@<?=$current_user->ID?>\performance">
                                    <i class="fa fa-user"></i><?=$current_user->display_name?>
                                </a>
                            </li>
                            <li>
                                <a href="\signout">
                                    <i class="fa fa-sign-out"></i>Sign Out
                                </a>
                            </li>
<?php }else{ ?>
                            <li>
                                <a href="\signin">
                                    <i class="fa fa-sign-in"></i>Sign In
                                </a>
                            </li>
                            <li>
                                <a href="\signup">
                                    <i class="fa fa-pencil-square-o"></i>Sign Up
                                </a>
                            </li>
<?php } ?>    
                        </ul>
                        <!--end right-->
                    </div>
                    <!--end container-->
                </div>
                <!--============ End Secondary Navigation ===========================================================-->