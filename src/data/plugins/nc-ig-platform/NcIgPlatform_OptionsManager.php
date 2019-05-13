<?php
require_once 'vendor/autoload.php';
/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

class NcIgPlatform_OptionsManager {

    public function getOptionNamePrefix() {
        return get_class($this) . '_';
    }

    /**
     * Define your options meta data here as an array, where each element in the array
     * @return array of key=>display-name and/or key=>array(display-name, choice1, choice2, ...)
     * key: an option name for the key (this name will be given a prefix when stored in
     * the database to ensure it does not conflict with other plugin options)
     * value: can be one of two things:
     *   (1) string display name for displaying the name of the option to the user on a web page
     *   (2) array where the first element is a display name (as above) and the rest of
     *       the elements are choices of values that the user can select
     * e.g.
     * array(
     *   'item' => 'Item:',             // key => display-name
     *   'rating' => array(             // key => array ( display-name, choice1, choice2, ...)
     *       'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber'),
     *       'Rating:', 'Excellent', 'Good', 'Fair', 'Poor')
     */
    public function getOptionMetaData() {
        return array();
    }

    /**
     * @return array of string name of options
     */
    public function getOptionNames() {
        return array_keys($this->getOptionMetaData());
    }

    /**
     * Override this method to initialize options to default values and save to the database with add_option
     * @return void
     */
    protected function initOptions() {
    }

    /**
     * Cleanup: remove all options from the DB
     * @return void
     */
    protected function deleteSavedOptions() {
        $optionMetaData = $this->getOptionMetaData();
        if (is_array($optionMetaData)) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $prefixedOptionName = $this->prefix($aOptionKey); // how it is stored in DB
                delete_option($prefixedOptionName);
            }
        }
    }

    /**
     * @return string display name of the plugin to show as a name/title in HTML.
     * Just returns the class name. Override this method to return something more readable
     */
    public function getPluginDisplayName() {
        return get_class($this);
    }

    /**
     * Get the prefixed version input $name suitable for storing in WP options
     * Idempotent: if $optionName is already prefixed, it is not prefixed again, it is returned without change
     * @param  $name string option name to prefix. Defined in settings.php and set as keys of $this->optionMetaData
     * @return string
     */
    public function prefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) { // 0 but not false
            return $name; // already prefixed
        }
        return $optionNamePrefix . $name;
    }

    /**
     * Remove the prefix from the input $name.
     * Idempotent: If no prefix found, just returns what was input.
     * @param  $name string
     * @return string $optionName without the prefix.
     */
    public function &unPrefix($name) {
        $optionNamePrefix = $this->getOptionNamePrefix();
        if (strpos($name, $optionNamePrefix) === 0) {
            return substr($name, strlen($optionNamePrefix));
        }
        return $name;
    }

    /**
     * A wrapper function delegating to WP get_option() but it prefixes the input $optionName
     * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
     * @param $optionName string defined in settings.php and set as keys of $this->optionMetaData
     * @param $default string default value to return if the option is not set
     * @return string the value from delegated call to get_option(), or optional default value
     * if option is not set.
     */
    public function getOption($optionName, $default = null) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        $retVal = get_option($prefixedOptionName);
        if (!$retVal && $default) {
            $retVal = $default;
        }
        return $retVal;
    }

    /**
     * A wrapper function delegating to WP delete_option() but it prefixes the input $optionName
     * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
     * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
     * @return bool from delegated call to delete_option()
     */
    public function deleteOption($optionName) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return delete_option($prefixedOptionName);
    }

    /**
     * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
     * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
     * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
     * @param  $value mixed the new value
     * @return null from delegated call to delete_option()
     */
    public function addOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return add_option($prefixedOptionName, $value);
    }

    /**
     * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
     * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
     * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
     * @param  $value mixed the new value
     * @return null from delegated call to delete_option()
     */
    public function updateOption($optionName, $value) {
        $prefixedOptionName = $this->prefix($optionName); // how it is stored in DB
        return update_option($prefixedOptionName, $value);
    }

    /**
     * A Role Option is an option defined in getOptionMetaData() as a choice of WP standard roles, e.g.
     * 'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber')
     * The idea is use an option to indicate what role level a user must minimally have in order to do some operation.
     * So if a Role Option 'CanDoOperationX' is set to 'Editor' then users which role 'Editor' or above should be
     * able to do Operation X.
     * Also see: canUserDoRoleOption()
     * @param  $optionName
     * @return string role name
     */
    public function getRoleOption($optionName) {
        $roleAllowed = $this->getOption($optionName);
        if (!$roleAllowed || $roleAllowed == '') {
            $roleAllowed = 'Administrator';
        }
        return $roleAllowed;
    }

    /**
     * Given a WP role name, return a WP capability which only that role and roles above it have
     * http://codex.wordpress.org/Roles_and_Capabilities
     * @param  $roleName
     * @return string a WP capability or '' if unknown input role
     */
    protected function roleToCapability($roleName) {
        switch ($roleName) {
            case 'Super Admin':
                return 'manage_options';
            case 'Administrator':
                return 'manage_options';
            case 'Editor':
                return 'publish_pages';
            case 'Author':
                return 'publish_posts';
            case 'Contributor':
                return 'edit_posts';
            case 'Subscriber':
                return 'read';
            case 'Anyone':
                return 'read';
        }
        return '';
    }

    /**
     * @param $roleName string a standard WP role name like 'Administrator'
     * @return bool
     */
    public function isUserRoleEqualOrBetterThan($roleName) {
        if ('Anyone' == $roleName) {
            return true;
        }
        $capability = $this->roleToCapability($roleName);
        return current_user_can($capability);
    }

    /**
     * @param  $optionName string name of a Role option (see comments in getRoleOption())
     * @return bool indicates if the user has adequate permissions
     */
    public function canUserDoRoleOption($optionName) {
        $roleAllowed = $this->getRoleOption($optionName);
        if ('Anyone' == $roleAllowed) {
            return true;
        }
        return $this->isUserRoleEqualOrBetterThan($roleAllowed);
    }

    /**
     * see: http://codex.wordpress.org/Creating_Options_Pages
     * @return void
     */
    public function createSettingsMenu() {
        $pluginName = $this->getPluginDisplayName();
        //create new top-level menu
        add_menu_page($pluginName . ' Plugin Settings',
                      $pluginName,
                      'administrator',
                      get_class($this),
                      array(&$this, 'settingsPage')
        /*,plugins_url('/images/icon.png', __FILE__)*/); // if you call 'plugins_url; be sure to "require_once" it

        //call register settings function
        add_action('admin_init', array(&$this, 'registerSettings'));
    }

    public function registerSettings() {
        $settingsGroup = get_class($this) . '-settings-group';
        $optionMetaData = $this->getOptionMetaData();
        foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
            register_setting($settingsGroup, $aOptionMeta);
        }
    }

    public function custom_print_r($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function autoPoll($igname, $userid, $number_post){
        global $wpdb;

        $memcached = new \memcached; //user memcached to prevent pullingsame profile multiple times in the same time
        $memcached->addServer('localhost', 11211) or die ("Could not connect");

        if($memcached->get("nc_igplatform_pulling".$igname)){
            return array(
                "error"=>1,
                "msg"=>"The pulling of account ".$igname." is processing. Please wait until it complete before you pull again."
            );
        }

        // the lock will auto release after 30 mins, regardless it pull success or not.
        $memcached->set("nc_igplatform_pulling".$igname, true, 1800);

        $print_log = php_sapi_name() == "cli";
        if($print_log){ 
            print_r("auto pull start >>\n");
        }
        $beginningTime = time();

        $return_obj = array(
            "error"=>0,
            "msg"=>"unknown"
        );
        $instagram = new \InstagramScraper\Instagram();

        $pod = pods('instagrammer_fast', $userid);
        
        if($print_log){ 
            print_r( (time() - $beginningTime )." starting pulling Igmedia\n");
        }

        try{
            $media = $instagram->getMedias($igname, 30, '');
        }catch(Exception $e){
            $return_obj["error"] = 1;
            $return_obj["msg"] = "Instagram Error, pulling posts fail, refresh and try again";
            return $return_obj;
        }

        if($print_log){
            print_r( (time() - $beginningTime )." complete pulling Igmedia\n");
        }

        $formatted_media = array();

        foreach($media as $key=>$value){

            $tempObj = array(
                "id"=>$value["id"],
                "user"=>$userid,
                "image"=>array(
                    "thumbnail"=>$value["imageThumbnailUrl"],
                    "standard_resolution"=>$value["imageHighResolutionUrl"]
                ),
                "created_time"=>$value["createdTime"],
                "caption"=>$value["caption"],
                "tags"=>"",
                "likes"=>$value["likesCount"],
                "comments"=>$value["commentsCount"],
                "type"=>$value["type"] == "video" ? "video":"image",
                "link"=>$value["link"],
                "location"=>array(
                    "id"=>$value["locationId"],
                    "name"=>$value["locationName"]
                )
            );

            $next_max_id = $value["id"];

            $formatted_media[] = $tempObj;
        }

        $return_obj["results"] = array();

        $attachment_IDs = array();

        //echo "<p>The posts below had been created.</p>";
        $tag = $pod->field('instagrammer_tag');
        $country = $pod->field('instagrammer_country');
        $language = $pod->field('instagrammer_language');

        $post_pod = pods('instagram_post_fast');

        $newimageid = 0;
        $newimagetitle = $pod->display('igusername');

        foreach($formatted_media as $key=>$value){
            if($print_log){
                print_r( (time() - $beginningTime )." check post exist \n");
            }
            //add to db if not exist in db.
            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagram_post_fast` WHERE ig_id = %s";
            $prepare = $wpdb->prepare($query, $value["id"]);
            $instagram_post_id = $wpdb->get_var($prepare);

            if($instagram_post_id){
                if($print_log){
                    print_r( (time() - $beginningTime )." post exist, update \n");
                }
                //id exists, update data rather that insert
                //echo "<p>existing</p>";
                $data = array(
                    "name"=>$pod->display('igusername')." - IG Post #".$value["id"],
                    "caption"=>$value["caption"],
                    "post_created_time"=>date("Y-m-d H:i:s", $value["created_time"]),
                    "likes"=>$value["likes"],
                    "comments"=>$value["comments"],
                    "ig_link"=>$value["link"],
                    "ig_type"=>$value["type"],
                    "ig_id"=>$value["id"]
                );

                $temp_post_pod = pods("instagram_post_fast", $instagram_post_id);
                $temp_image_hires = $temp_post_pod->field('image_hires');
                $post_pod->save($data, null, $instagram_post_id);
                
                if($print_log){
                    print_r( (time() - $beginningTime )." post update done \n");
                }
                if($temp_image_hires && $temp_image_hires["ID"]){
                    $attachment_IDs[] = $temp_image_hires["ID"];
                }

            }else{
                if($print_log){
                    print_r( (time() - $beginningTime )." post not exist \n");
                }
                //echo "<p>new</p>";
                $thumbnail_image = isset($value["image"]) && isset($value["image"]["thumbnail"]) ? $value["image"]["thumbnail"] : "";
                $standard_image = isset($value["image"]) && isset($value["image"]["standard_resolution"]) ? $value["image"]["standard_resolution"] : $thumbnail_image;

                if($print_log){
                    print_r( (time() - $beginningTime )." starting copy image file to AWS S3 \n");
                }
                if($standard_image){
                    $result = $this->copyFileToWP($standard_image, $pod->display('igusername')." - IG Post #".$value["id"], 'ig'.$value["id"]);
                }else if($thumbnail_image){
                    $result = $this->copyFileToWP($thumbnail_image, $pod->display('igusername')." - IG Post #".$value["id"]." ( thumbnail )", 'ig'.$value["id"]);
                }
                if($print_log){
                    print_r( (time() - $beginningTime )." complete copy image file to AWS S3 \n");
                }
                
                if($newimageid == 0){
                    $newimageid = $result["media_id"];
                }

                $data = array(
                    "name"=>$pod->display('igusername')." - IG Post #".$value["id"],
                    "image_thumbnail"=>array(
                        "id"=>$result["media_id"],
                        "title"=>$pod->display('igusername')." - IG Post #".$value["id"]
                    ),
                    "image_hires"=>array(
                        "id"=>$result["media_id"],
                        "title"=>$pod->display('igusername')." - IG Post #".$value["id"]
                    ),
                    "caption"=>$value["caption"],
                    "post_created_time"=>date("Y-m-d H:i:s", $value["created_time"]),
                    "likes"=>$value["likes"],
                    "comments"=>$value["comments"],
                    "ig_link"=>$value["link"],
                    "ig_type"=>$value["type"],
                    "ig_id"=>$value["id"]
                );

                $instagram_post_id = $post_pod->add($data);
                if($print_log){
                    print_r( (time() - $beginningTime )." post add done \n");
                }
                if(!$instagram_post_id){
                    $this->custom_print_r($data);
                    if($print_log){
                        print_r( (time() - $beginningTime )." post id not exist after add \n");
                    }
                }

                $temp_pod = pods("instagram_post_fast", $instagram_post_id);

                $temp_pod->add_to("instagrammer", $userid);

                if($tag && sizeof($tag)){
                    foreach($tag as $key=>$value){
                        $temp_pod->add_to("instagram_post_tag", $value["term_id"]);
                    }
                }

                if($country && sizeof($country)){
                    foreach($country as $key=>$value){
                        $temp_pod->add_to("instagram_post_country", $value["term_id"]);   
                    }
                }

                if($language && sizeof($language)){
                    foreach($language as $key=>$value){
                        $temp_pod->add_to("instagram_post_language", $value["term_id"]);      
                    }
                }
            }             

            $templink = 'https://storify.me/ao/wp-admin/admin.php?page=pods-manage-instagram_post_fast&action=edit&id='.$instagram_post_id;
            $return_obj["results"][] = array(
                "link"=>$templink
            );

            if(sizeof($attachment_IDs)){
                foreach($attachment_IDs as $key=>$value){
                    //remove item
                    /*
                    $result = wp_delete_attachment($value, true);
                    if($result === false){
                        print_r( $value." >> delete fail " );
                    }else{
                        print_r( " ".$value." >> delete success");
                    }*/
                    //dont need to check account display image, since it will be replaced by the last step in this function
                }
            }
        }

        if($newimageid != 0){
            //cannot remove the previous set image, since it is used for the stored post
            //save the first post image as display image for user
            $data = $pod->save('display_image', array(
                "id"=>$newimageid,
                "title"=>$newimagetitle
            ));
        }

        if($print_log){
            print_r( (time() - $beginningTime )." all file inserted complete. \n");
        }
        $this->updateAverageIG($userid);
        if($print_log){
            print_r( (time() - $beginningTime )." all average data updated. \n");
        }

        $memcached->set("nc_igplatform_pulling".$igname, false, 1);

        return $return_obj;
    }

    public function settingsPage_featuredposts(){
        global $wpdb;
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'nc-ig-platform'));
        }

        $optionMetaData = $this->getOptionMetaData();

        // Save Posted Options
        if ($optionMetaData != null) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                if (isset($_POST[$aOptionKey])) {
                    $this->updateOption($aOptionKey, $_POST[$aOptionKey]);
                }
            }
        }

        // HTML for the page
        $settingsGroup = get_class($this) . '-settings-group';

        $withInstagrammerID = false;
        if(isset($_GET["id"])){
            $pod = pods('instagrammer_fast', $_GET["id"]);
            if($pod->display('id')){
                $withInstagrammerID = true;

                if($_GET["postpull"] == "auto"){
                    echo "<h2>Auto Pulling Start, Be patient</h2>";
                    sleep(1);

                    $igname = $pod->display('igusername');
                    $result = $this->autoPoll($igname, $_GET["id"], 30);

                    if($result["error"]){
                        die($result["msg"]);
                    }else{
                        foreach($result["result"] as $key=>$value){
                            echo "<p><a href=\"".$value["link"]."\" target=\"_blank\">".$value["link"]."</a></p>";
                        }
                    }

                    echo "<p>All done</p>";

                }else if($_POST["submit"] == "Select Posts"){
                    echo "<h2>Be patient, it take longer time if there are a lot of posts</h2>";
                    sleep(1);
                    $selectedPosts = isset($_POST["selectedPosts"]) ? json_decode(stripcslashes($_POST["selectedPosts"]), true): array();

                    if(sizeof($selectedPosts)){
                        foreach($selectedPosts as $key=>$value){
                            //add to db if not exist in db.
                            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagram_post_fast` WHERE ig_id = %s";
                            $prepare = $wpdb->prepare($query, $value["id"]);
                            $instagram_post_id = $wpdb->get_var($prepare);

                            $thumbnail_image = isset($value["image"]) && isset($value["image"]["thumbnail"]) ? $value["image"]["thumbnail"] : "";
                            $standard_image = isset($value["image"]) && isset($value["image"]["standard_resolution"]) ? $value["image"]["standard_resolution"] : $thumbnail_image;

                            if($standard_image){
                                $result = $this->copyFileToWP($standard_image, $pod->display('igusername')." - IG Post #".$value["id"], 'ig'.$value["id"]);
                            }else if($thumbnail_image){
                                $result = $this->copyFileToWP($thumbnail_image, $pod->display('igusername')." - IG Post #".$value["id"]." ( thumbnail )", 'ig'.$value["id"]);
                            }

                            
                            echo "<p>The posts below had been created.</p>";
                            $tag = $pod->field('instagrammer_tag');
                            $country = $pod->field('instagrammer_country');
                            $language = $pod->field('instagrammer_language');

                            $post_pod = pods('instagram_post_fast');
                            $data = array(
                                "name"=>$pod->display('igusername')." - IG Post #".$value["id"],
                                "image_thumbnail"=>array(
                                    "id"=>$result["media_id"],
                                    "title"=>$pod->display('igusername')." - IG Post #".$value["id"]." ( thumbnail )"
                                ),
                                "image_hires"=>array(
                                    "id"=>$result["media_id"],
                                    "title"=>$pod->display('igusername')." - IG Post #".$value["id"]
                                ),
                                "caption"=>$value["caption"],
                                "post_created_time"=>date("Y-m-d H:i:s", $value["created_time"]),
                                "likes"=>$value["likes"],
                                "comments"=>$value["comments"],
                                "ig_link"=>$value["link"],
                                "ig_type"=>$value["type"],
                                "ig_id"=>$value["id"]
                            );


                            if($instagram_post_id){
                                //id exists, update data rather that insert
                                echo "<p>existing</p>";
                                $post_pod->save($data, null, $instagram_post_id);

                            }else{
                                echo "<p>new</p>";
                                $instagram_post_id = $post_pod->add($data);
                                if(!$instagram_post_id){
                                    $this->custom_print_r($data);
                                }

                                $temp_pod = pods("instagram_post_fast", $instagram_post_id);

                                $temp_pod->add_to("instagrammer", $_GET["id"]);

                                foreach($tag as $key=>$value){
                                    $temp_pod->add_to("instagram_post_tag", $value["term_id"]);
                                }

                                foreach($country as $key=>$value){
                                    $temp_pod->add_to("instagram_post_country", $value["term_id"]);   
                                }

                                foreach($language as $key=>$value){
                                    $temp_pod->add_to("instagram_post_language", $value["term_id"]);      
                                }
                            }

                            $templink = 'https://storify.me/ao/wp-admin/admin.php?page=pods-manage-instagram_post_fast&action=edit&id='.$instagram_post_id;
                            echo "<p><a href=\"".$templink."\" target=\"_blank\">".$templink."</a></p>";

                            if($key == 0){
                                //save the first post image as display image for user
                                $data = $pod->save('display_image', array(
                                    "id"=>$result["media_id"],
                                    "title"=>$pod->display('igusername')
                                ));
                            }
                        }
                        echo "<p>All done</p>";

                        $this->updateAverageIG($_GET["id"]);

                    }else{
                        ?>
                        <div class="warp">
                            <h1>No Posts is selected</h1>
                        </div>
                        <?php
                    }
                }else{
            ?>
            <div class="wrap">
                <h1><?=$pod->display('name')?> ( <?=$pod->display('igusername')?> )</h1>
                <img src="<?=$pod->display('ig_profile_pic')?>" />
                <h2>Select Posts Below</h2>
                <?php
                    $instagram = new \InstagramScraper\Instagram();
                    $igname = $pod->display('igusername');

                    $selectedPosts = isset($_POST["selectedPosts"]) ? json_decode(stripcslashes($_POST["selectedPosts"]), true): array();
                    $previous_max_id = isset($_POST["previous_max_id"]) ? $_POST["previous_max_id"] : "";
                    $next_max_id = isset($_POST["next_max_id"]) ? $_POST["next_max_id"] : "";
                    if($_POST["submit"] == "Newer Posts"){
                        $maxId = $previous_max_id;
                    }else{
                        $maxId = $next_max_id;
                    }

                    try{
                        $media = $instagram->getMedias($igname, 30, $maxId);
                    }catch(Exception $e){
                        $media = array();
                        $this->custom_print_r($e);
                        die("Instagram Error, refresh and try again");
                    }

                    $formatted_media = array();
                    $next_max_id = "";

                    foreach($media as $key=>$value){

                        $tempObj = array(
                            "id"=>$value["id"],
                            "user"=>$_GET["id"],
                            "image"=>array(
                                "thumbnail"=>$value["imageThumbnailUrl"],
                                "standard_resolution"=>$value["imageHighResolutionUrl"]
                            ),
                            "created_time"=>$value["createdTime"],
                            "caption"=>$value["caption"],
                            "tags"=>"",
                            "likes"=>$value["likesCount"],
                            "comments"=>$value["commentsCount"],
                            "type"=>$value["type"] == "video" ? "video":"image",
                            "link"=>$value["link"],
                            "location"=>array(
                                "id"=>$value["locationId"],
                                "name"=>$value["locationName"]
                            )
                        );

                        $next_max_id = $value["id"];

                        $formatted_media[] = $tempObj;
                    }
                ?>
                <style type="text/css">
                    .floating_panel{
                        position:fixed;
                        bottom:0;
                        right:10px;
                        background-color: #f1f1f1;
                        border:solid 1px #333;
                        padding:10px;
                    }
                    .post_cards::after{
                        display:block;
                        clear:both;
                        content:" ";
                    }
                    .post_card{
                        padding:10px;
                        border:1px solid #333;
                        width:calc(70% - 15px);
                        margin:5px;
                        float:left;
                    }
                    .post_card::after{
                        clear:both;
                        display:block;
                        content:" ";
                    }
                    .post_card .col-1,.post_card .col-2,.post_card .col-3{
                        float:left;
                    }
                    .post_card .col-1{
                        width:20px;
                        padding:10px;
                    }
                    .post_card .col-2{
                        width:calc(20% - 20px);
                        padding:10px;
                    }
                    .post_card .col-2 img{
                        width:100%;
                    }
                    .post_card .col-3{
                        width:60%;
                        padding:10px;
                    }
                    .post_card .col-3 pre{
                        white-space: pre-wrap;
                        margin:0 0 1em;
                    }
                </style>
                <form method="post" action="" name="igpostselector">
                <?php
                    if(sizeof($formatted_media)){
                        $pageMaxId[] = $formatted_media[sizeof($formatted_media) - 1]["id"];

                        $sorted_formatted_media = $formatted_media;  //$this->array_orderby($formatted_media, 'likes', SORT_DESC, 'comments', SORT_DESC);
                ?>  
                    <div class="post_cards">
                    <?php
                        foreach($sorted_formatted_media as $key=>$value){
                    ?>  
                        <label for="post_<?=$value["id"]?>">
                            <div class="post_card">
                                <div class="col-1">
                                    <input type="checkbox" id="post_<?=$value["id"]?>" name="igposts" value="<?=$value["id"]?>" />
                                </div>
                                <div class="col-2">
                                    <img src="<?=$value["image"]["thumbnail"]?>" />
                                </div>
                                <div class="col-3">
                                    <pre><?=$value["caption"]?></pre>
                                    <div class="row-1">
                                        Posted on : <?=date("Y-m-d H:i:s", $value["created_time"])?>, # of Likes : <?=$value["likes"]?>, # of Comments : <?=$value["comments"]?>
                                    </div>
                                </div>
                            </div>
                        </label>
                    <?php                
                        }
                    }else{  ?>
                    <p>No Post is found</p>
                        <?php
                    }?>
                    </div>
                    <input type="hidden" name="selectedPosts" value="">
                    <input type="hidden" name="iguser" value="<?=$_GET["id"]?>">
                    <div class="floating_panel">
                        <div class="numofpost">Selected Posts : <span>0</span></div>
                        <p class="submit">
                            <?php wp_nonce_field( 'IG Posts', 'nonce_igposts'); ?>
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Select Posts">
                            <input type="hidden" name="previous_max_id" value="<?=$previous_max_id?>">
                            <?php if($maxId){ ?>
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Newer Posts">
                            <?php }
                            if($formatted_media){ ?>      
                            <input type="hidden" name="next_max_id" value="<?=$next_max_id?>">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Older Posts"><?php
                        } ?>
                        </p>
                    </div>
                </form>
                <script>
                    jQuery(function(){
                        var $ = jQuery;
                        var selectedPost = <?=json_encode($selectedPosts)?>;
                        var currentPost = <?=json_encode($formatted_media)?>;

                        $('form[name="igpostselector"]').submit(function(e){
                            

                           $('input[name="selectedPosts"]').val(JSON.stringify(selectedPost));
                        });

                        $('input[name="igposts"]').change(function(){
                            var inIndex = -1, elementValue,
                            vthis = $(this),
                            selected_value = vthis.val();

                            $.each(selectedPost, function(index, value){
                                if(value.id == selected_value){
                                    inIndex = index;
                                }
                            });
                            if(vthis.is(":checked") == false){
                                //uncheck, check if in selectedPosts
                                if(inIndex > -1){
                                    selectedPost.splice(inIndex, 1);
                                }
                            }else{
                                //check
                                if(inIndex > -1){
                                    // do nothing
                                }else{
                                    // no in list
                                    $.each(currentPost, function(index, value){
                                        if(value.id == selected_value){
                                            elementValue = value;
                                        }
                                    });

                                    selectedPost.push(elementValue);
                                }
                            }
                            updateNumberOfPost();
                        });

                        updateNumberOfPost();

                        $.each(selectedPost, function(index,value){
                            $('input[name="igposts"][value="'+value.id+'"]').prop("checked",true);
                        });

                        function updateNumberOfPost(){
                            var a = selectedPost.length;

                            console.log(selectedPost);

                            $(".numofpost span").text(selectedPost.length);
                        }
                    });
                </script>
            </div>
            <?php
                }
            }
        }

        if(!$withInstagrammerID){
            //get list of 
            $params = array(
                "limit"=> -1
            );
            $pods = pods('instagrammer_fast', $params);

            $instagrammers = array();
            if( 0 < $pods->total() ){
                while( $pods->fetch()){
                    $instagrammers[] = array(
                        "id"=>$pods->display("id"),
                        "name"=>$pods->display("igusername") . " ( ".$pods->display("name")." )"
                    );
                }
            }
            ?>
        <div class="wrap">
            <h1>Select Instagrammer</h1>
        </div>
        <form method="get" action="" name="igselector">
            <select name="id">
            <?php
                foreach($instagrammers as $key=>$value){
                    ?><option value="<?=$value["id"]?>"><?=$value["name"]?></option><?php
                }
            ?>
            </select>
            <input type="hidden" name="page" value="<?=$_GET["page"]?>">
            <?php
                submit_button('Continue');
            ?>
        </form>
            <?php
        }
    }

    public function updateAverageIG($id){
        //usually it takes 36 hours for a post to mature, and we also only interest on the recent posts ( limited to 50 )
        $query = array(
                "limit"=>31,
                "offset"=>1,
                "where"=>"instagrammer.id = ".$id,
                "orderby"=>"post_created_time DESC"
            );

        $post_pods = pods("instagram_post_fast", $query);
        $temp_posts = array();

        if(0 < $post_pods->total()){
            while($post_pods->fetch()){
                $temp_posts[] = array(
                    "likes"=>$post_pods->field("likes"),
                    "comments"=>$post_pods->field("comments")
                );
            }
        }

        $this->_updateAverageIG($temp_posts, $id);

    }

    public function _updateAverageIG($posts, $id){
        global $wpdb;

        $totalposts = sizeof($posts);

        if($totalposts > 0){
            $total_likes = 0;
            $total_comments = 0;

            foreach($posts as $key=>$value){
                $total_likes += (int)$value["likes"];
                $total_comments += (int)$value["comments"];
            }

            //update number
            $pod = pods('instagrammer_fast');

            $pod->save(array(
                "average_likes"=>$total_likes / $totalposts,
                "average_comments"=>$total_comments / $totalposts
            ), null, $id);

            $query = "INSERT INTO `".$wpdb->prefix."stats_avg_likes` ( instagrammer_id, amount ) VALUES ( %d, %f )";
            $wpdb->query($wpdb->prepare($query, $id, $total_likes / $totalposts));

            $query = "INSERT INTO `".$wpdb->prefix."stats_avg_comments` ( instagrammer_id, amount ) VALUES ( %d, %f )";
            $wpdb->query($wpdb->prepare($query, $id, $total_comments / $totalposts));

        }else{
            // do nothing
        }

    }

    public function getIGUsername($igusername){
        global $wpdb;
        
        return ;
        //not in used 
        //
        $instagram = new \InstagramScrapper\Instagram();
        try{
            $account = $instagram->getAccount($igusername);
            
            $fullName = $account["fullName"] ? $account["fullName"] : $_POST["ig_username"];
            $profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];


            $tempobj = array(
                "name"=>$fullName,
                "ig_id"=>$account["id"],
                "igusername"=>$account["username"],
                "ig_profile_pic"=>$profileImage,
                "biography"=>$account["biography"],
                "media_count"=>$account["mediaCount"],
                "follows_by_count"=>$account["followsCount"],
                "external_url"=>$account["externalUrl"]
            );

            $pod = pods('instagrammer_fast');

            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
            $prepare = $wpdb->prepare($query, $account["id"]);
            $instagrammer_id = $wpdb->get_var($prepare);

            //update IG Data
            $pod->save($data, null, $instagrammer_id);

            $media = $instagram->getMedias( $igusername, 6,'');

        }catch(Exception $e){
            return null;
        }
    }

    /**
     * Creates HTML for the Administration page to set options for this plugin.
     * Override this method to create a customized page.
     * @return void
     */
    public function settingsPage() {
        global $wpdb;
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'nc-ig-platform'));
        }

        $optionMetaData = $this->getOptionMetaData();

        // Save Posted Options
        if ($optionMetaData != null) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                if (isset($_POST[$aOptionKey])) {
                    $this->updateOption($aOptionKey, $_POST[$aOptionKey]);
                }
            }
        }

        // HTML for the page
        $settingsGroup = get_class($this) . '-settings-group';

        $instagram = new \InstagramScraper\Instagram();

        if(isset($_POST) && sizeof($_POST)){
            if(isset($_POST["st_nonce_username"])){
                if(wp_verify_nonce( $_POST['st_nonce_username'], 'IG Username' ) ){
                    //username enter
                    try{
                        $account = $instagram->getAccount($_POST["ig_username"]);
                        //account found
                        $fullName = $account["fullName"] ? $account["fullName"] : $_POST["ig_username"];
                        $profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];

                        $result = $this->copyFileToWP($profileImage, $fullName, $_POST["ig_username"]);

                        //add image to wordpress library

                        //add to db if not exist in db.
                        $query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
                        $prepare = $wpdb->prepare($query, $account["id"]);
                        $instagrammer_id = $wpdb->get_var($prepare);

                        $pod = pods('instagrammer_fast');

                        if($instagrammer_id){
                            //id exists, update data rather that insert

                            $data = array(
                                "name"=>$fullName,
                                "ig_id"=>$account["id"],
                                "igusername"=>$account["username"],
                                "biography"=>$account["biography"],
                                "media_count"=>$account["mediaCount"],
                                "follows_count"=>$account["followsCount"],
                                "follows_by_count"=>$account["followedByCount"],
                                "external_url"=>$account["externalUrl"],
                                "ig_profile_pic"=>array(
                                    "id"=>$result["media_id"],
                                    "title"=>$fullName
                                )
                            );

                            $pod->save($data, null, $instagrammer_id);
                            /*
                            $query = "UPDATE `".$wpdb->prefix."pods_instagrammer_fast` SET name = %s, biography = %s, media_count = %s, follows_count = %s, follows_by_count = %s, external_url = %s, modified = NOW() WHERE id = %s";
                            $prepare = $wpdb->prepare($query, $account["fullName"], $account["biography"], $account["mediaCount"], $account["followsCount"], $account["followedByCount"], $account["externalUrl"]);
                            $wpdb->query($prepare);
                            */
                           
                           //
                            
                            echo "<p>Existing IGer found, Key index updated.</p>";

                        }else{

                            $data = array(
                                "name"=>$fullName,
                                "ig_id"=>$account["id"],
                                "igusername"=>$account["username"],
                                "biography"=>$account["biography"],
                                "media_count"=>$account["mediaCount"],
                                "follows_count"=>$account["followsCount"],
                                "follows_by_count"=>$account["followedByCount"],
                                "external_url"=>$account["externalUrl"],
                                "ig_profile_pic"=>array(
                                    "id"=>$result["media_id"],
                                    "title"=>$fullName
                                ),
                                "display_image"=>array(
                                    "id"=>$result["media_id"],
                                    "title"=>$fullName  
                                )
                            );

                            $instagrammer_id = $pod->add($data);
                            /*
                            $query = "INSERT INTO `".$wpdb->prefix."pods_instagrammer_fast` ( name, created, ig_id, igusername, biography, media_count, follows_count, follows_by_count, external_url ) VALUES ( %s, NOW(), %s, %s, %s, %s, %s, %s, %s, %s )";
                            $prepare = $wpdb->prepare($query, $account["fullName"], $account["id"], $account["username"], $account["biography"], $account["mediaCount"], $account["followsCount"], $account["followedByCount"], $account["externalUrl"]);
                            
                            $wpdb->query($prepare);
                            */
                           
                            echo "<p>New user Inserted</p>";
                        }

                        $query = "INSERT INTO `".$wpdb->prefix."stats_no_followers` ( instagrammer_id, amount ) VALUES ( %d, %d )";
                        $wpdb->query($wpdb->prepare($query, $instagrammer_id, $account["followedByCount"]));

                        $query = "INSERT INTO `".$wpdb->prefix."stats_media_count` ( instagrammer_id, amount ) VALUES ( %d, %d )";
                        $wpdb->query($wpdb->prepare($query, $instagrammer_id, $account["mediaCount"]));

                        echo "<p>Will be redirected to edit page in 3 seconds.</p>";

                        ?>
                        <script>
                            setTimeout(function(){
                                window.location.replace("<?=admin_url('admin.php?page=pods-manage-instagrammer_fast&action=edit&id='.$instagrammer_id)?>");
                            }, 3000);
                        </script>
                        <?php
                        exit();

                    }catch(Exception $e){
                        if($e instanceof InstagramScraper\Exception\InstagramNotFoundException){
                            $error_msg = $e->getMessage();
                        }else{
                            $this->custom_print_r($e);
                        }
                    }
                }else{
                    $error_msg = "Invalid nonce token. Please refresh the page and try again.";
                }
            }else{
                $this->custom_print_r($_POST);
            }
        }else{

        }

        if(1){
        ?>
        <div class="wrap">
            <h1>Enter Instagram Username to add instagrammer</h1>
            <?php
                if(isset($error_msg)){
                    ?>
                    <p><?=$error_msg?></p>
                    <?php
                }
            ?>
            <form method="post" action="">
                <input class="igusername" type="text" name="ig_username" value="" required>
                <?php
                    wp_nonce_field( 'IG Username', 'st_nonce_username');
                    submit_button('Add Instagrammer');
                ?>
            </form>
        </div>
        <?php
        }
    }

    /**
     * Helper-function outputs the correct form element (input tag, select tag) for the given item
     * @param  $aOptionKey string name of the option (un-prefixed)
     * @param  $aOptionMeta mixed meta-data for $aOptionKey (either a string display-name or an array(display-name, option1, option2, ...)
     * @param  $savedOptionValue string current value for $aOptionKey
     * @return void
     */
    protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue) {
        if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
            $choices = array_slice($aOptionMeta, 1);
            ?>
            <p><select name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>">
            <?php
                            foreach ($choices as $aChoice) {
                $selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
                ?>
                    <option value="<?php echo $aChoice ?>" <?php echo $selected ?>><?php echo $this->getOptionValueI18nString($aChoice) ?></option>
                <?php
            }
            ?>
            </select></p>
            <?php

        }
        else { // Simple input field
            ?>
            <p><input type="text" name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>"
                      value="<?php echo esc_attr($savedOptionValue) ?>" size="50"/></p>
            <?php

        }
    }

    /**
     * Override this method and follow its format.
     * The purpose of this method is to provide i18n display strings for the values of options.
     * For example, you may create a options with values 'true' or 'false'.
     * In the options page, this will show as a drop down list with these choices.
     * But when the the language is not English, you would like to display different strings
     * for 'true' and 'false' while still keeping the value of that option that is actually saved in
     * the DB as 'true' or 'false'.
     * To do this, follow the convention of defining option values in getOptionMetaData() as canonical names
     * (what you want them to literally be, like 'true') and then add each one to the switch statement in this
     * function, returning the "__()" i18n name of that string.
     * @param  $optionValue string
     * @return string __($optionValue) if it is listed in this method, otherwise just returns $optionValue
     */
    protected function getOptionValueI18nString($optionValue) {
        switch ($optionValue) {
            case 'true':
                return __('true', 'nc-ig-platform');
            case 'false':
                return __('false', 'nc-ig-platform');

            case 'Administrator':
                return __('Administrator', 'nc-ig-platform');
            case 'Editor':
                return __('Editor', 'nc-ig-platform');
            case 'Author':
                return __('Author', 'nc-ig-platform');
            case 'Contributor':
                return __('Contributor', 'nc-ig-platform');
            case 'Subscriber':
                return __('Subscriber', 'nc-ig-platform');
            case 'Anyone':
                return __('Anyone', 'nc-ig-platform');
        }
        return $optionValue;
    }

    /**
     * Query MySQL DB for its version
     * @return string|false
     */
    protected function getMySqlVersion() {
        global $wpdb;
        $rows = $wpdb->get_results('select version() as mysqlversion');
        if (!empty($rows)) {
             return $rows[0]->mysqlversion;
        }
        return false;
    }

    /**
     * If you want to generate an email address like "no-reply@your-site.com" then
     * you can use this to get the domain name part.
     * E.g.  'no-reply@' . $this->getEmailDomain();
     * This code was stolen from the wp_mail function, where it generates a default
     * from "wordpress@your-site.com"
     * @return string domain name
     */
    public function getEmailDomain() {
        // Get the site domain and get rid of www.
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
            $sitename = substr($sitename, 4);
        }
        return $sitename;
    }

    public function copyFileToWP($url, $description, $filename){
        global $wpdb;

        $upload = wp_upload_dir();

        $fileheader = get_headers($url, 1);
        if(isset($fileheader) && isset($fileheader["Content-Type"]) && strpos($fileheader["Content-Type"], 'image') !== false){
            //is image, get mime
            $type = substr(strrchr($fileheader["Content-Type"], '/'), 1);
            switch ($type) {
                case 'gif':
                    $extension = "gif";
                    break;
                case 'x-icon':
                    $extension = "ico";
                    break;
                case 'png':
                    $extension = "png";
                    break;
                case 'svg+xml':
                    $extension = "svg";
                    break;
                case 'tiff':
                    $extension = "tiff";
                    break;
                case 'webp':
                    $extension = "webp";
                    break;
                default:
                    $extension = "jpg";
                    break;
            }

            $filename = sanitize_title($filename).".".$extension;
            copy($url, $upload['path'].'/'.$filename);
            $filetype = wp_check_filetype($filename, null);

            $attachment = array(
                'guid'              =>  $upload['url'].'/'.$filename,
                'post_mime_type'    =>  $filetype['type'],
                'post_title'        =>  $description,
                'post_content'      =>  '',
                'post_status'       =>  'inherit'
            );

            //check if image exist, overwrite if yes
            
            $attach_id = wp_insert_attachment( $attachment, $upload['path'].'/'.$filename, 0);
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['path'].'/'.$filename);
            $response = wp_update_attachment_metadata( $attach_id, $attach_data );

            return array(
                "error"=>0,
                "msg"=>"attached",
                "media_id"=>$attach_id,
                "attach_data"=>$attach_data
            );
        }else{
            return array(
                "error"=>1,
                "msg"=>"not an image"
            );
        }

    }

    public function getCacheImage($url){
        global $wpdb;
        $filename = $this->isImageInDB($url);

        $upload = wp_upload_dir();
        $upload_dir = $upload['baseurl'];
        $upload_path = $upload['basedir']. '/imagecache/';
        $upload_dir = $upload_dir . '/imagecache/';
        if($filename){
            return $upload_dir.$filename;
        }

        $fileheader = get_headers($url, 1);
        if(isset($fileheader) && isset($fileheader["Content-Type"]) && strpos($fileheader["Content-Type"], 'image') !== false){
            //is image, get mime
            $type = substr(strrchr($fileheader["Content-Type"], '/'), 1);
            switch ($type) {
                case 'gif':
                    $extension = "gif";
                    break;
                case 'x-icon':
                    $extension = "ico";
                    break;
                case 'png':
                    $extension = "png";
                    break;
                case 'svg+xml':
                    $extension = "svg";
                    break;
                case 'tiff':
                    $extension = "tiff";
                    break;
                case 'webp':
                    $extension = "webp";
                    break;
                default:
                    $extension = "jpg";
                    break;
            }

            $uniqueName = $this->getUniqueCode($url, $extension);

            if(copy($url, $upload_path."/".$uniqueName)){
                return $upload_dir.$uniqueName;
            }else{
                return $upload_dir.'not_available.jpg#copy_error';
            }
        }else{
            return $upload_dir.'not_available.jpg';
        }
    }

    private function isImageInDB($url){
        global $wpdb;

        $query = "SELECT cachename FROM `". $wpdb->prefix ."imagecache` WHERE url = %s";
        $cachename = $wpdb->get_var($query, $url);
        return $cachename;
    }

    private function getUniqueCode($url, $fileextension){
        global $wpdb;

        $notInserted = true;
        $randname = "";
        while($notInserted){
            $randname = $this->getRandomString(20,'abcdefghijklmnopqrstuvwxyz0123456789_').".".$fileextension;
            $query = "SELECT COUNT(*) FROM `". $wpdb->prefix ."imagecache` WHERE cachename = %s";
            if(!$wpdb->get_var($query, $randname)){
                $query = "INSERT INTO `". $wpdb->prefix ."imagecache` ( url, cachename ) VALUES ( %s , %s )";
                $prepare = $wpdb->prepare($query, $url, $randname);
                $result = $wpdb->query($prepare);
                $notInserted = false;
            }
        }

        return $randname;
    }

    private function getRandomString($len, $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
        $str = "";
        while(strlen($str) < $len){
            $str .= substr($chars, mt_rand(0, strlen($chars)), 1);
        }
        return $str;
    }

    private function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

}

