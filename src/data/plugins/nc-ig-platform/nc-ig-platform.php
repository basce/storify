<?php
/*
   Plugin Name: NC IG Platform
   Plugin URI: http://wordpress.org/extend/plugins/nc-ig-platform/
   Version: 0.1
   Author: Yong Chee Wei
   Description: Custom Plugin for IG Platform
   Text Domain: nc-ig-platform
   License: GPLv3
  */

/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

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

/**
 * Add default role meta
 */
/**
 * Add default role meta
 */
add_action('show_user_profile', 'default_role_profile_edit_action');
add_action('edit_user_profile', 'default_role_profile_edit_action');
function default_role_profile_edit_action($user) {
  $default_role = get_user_meta($user->ID, "default_role", true);
?>
  <label for="default_role">
    Default Role
    <select name="default_role" id="default_role">
      <option value="brand" <?=($default_role == "brand")?"selected=\"selected\"":""?>>Brand</option>
      <option value="creator" <?=($default_role == "creator")?"selected=\"selected\"":""?>>Creator</option>
    </select>
  </label>
<?php 
}

add_action('personal_options_update', 'default_role_profile_update_action');
add_action('edit_user_profile_update', 'default_role_profile_update_action');
function default_role_profile_update_action($user_id) {
  update_user_meta($user_id, 'default_role', $_POST['default_role']);
}

/**
 * add brand verified meta
 */
add_action('show_user_profile', 'brand_verified_profile_edit_action');
add_action('edit_user_profile', 'brand_verified_profile_edit_action');
function brand_verified_profile_edit_action($user) {
  $checked = get_user_meta($user->ID, "brand_verified", true) ? ' checked="checked"' : '';
  //$checked = (isset($user->brand_verified) && $user->brand_verified) ? ' checked="checked"' : '';
?>
  <h3>Other</h3>
  <label for="brand_verified">
    <input name="brand_verified" type="checkbox" id="brand_verified" value="1"<?php echo $checked; ?>>
    Brand Verified
  </label>
<?php 
}
add_action('personal_options_update', 'brand_verified_profile_update_action');
add_action('edit_user_profile_update', 'brand_verified_profile_update_action');
function brand_verified_profile_update_action($user_id) {
  update_user_meta($user_id, 'brand_verified', isset($_POST['brand_verified']));
}

/**
 * add Creator Rating
 */
add_action('show_user_profile', 'rating_creator_profile_edit_action');
add_action('edit_user_profile', 'rating_creator_profile_edit_action');
function rating_creator_profile_edit_action($user) {
  $rating_creator = get_user_meta($user->ID, "rating_creator", true)
?>
  <table class="form-table">
      <tbody>
        <tr>
          <th><label for="rating_creator">Rating for Creator</label></th>
          <td>

            <input name="rating_creator" value="<?=$rating_creator?>">
            
          </td>
        </tr>

      </tbody>
    </table>

<?php 
}
add_action('personal_options_update', 'rating_creator_profile_update_action');
add_action('edit_user_profile_update', 'rating_creator_profile_update_action');
function rating_creator_profile_update_action($user_id) {
  update_user_meta($user_id, 'rating_creator', isset($_POST['rating_creator']));
}

/**
 * add Brand Rating
 */
add_action('show_user_profile', 'rating_brand_profile_edit_action');
add_action('edit_user_profile', 'rating_brand_profile_edit_action');
function rating_brand_profile_edit_action($user) {
  $rating_brand = get_user_meta($user->ID, "rating_brand", true)
?>
  <table class="form-table">
      <tbody>
        <tr>
          <th><label for="rating_brand">Rating for Brand ( temporary, will need to move to business account later )</label></th>
          <td>

            <input name="rating_brand" value="<?=$rating_brand?>">
            
          </td>
        </tr>

      </tbody>
    </table>

<?php 

}
add_action('personal_options_update', 'rating_brand_profile_update_action');
add_action('edit_user_profile_update', 'rating_brand_profile_update_action');
function rating_brand_profile_update_action($user_id) {
  update_user_meta($user_id, 'rating_brand', isset($_POST['rating_brand']));
}


/**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
function themeblvd_disable_admin_bar() { 
    if ( ! current_user_can('edit_posts') ) {
        add_filter('show_admin_bar', '__return_false'); 
    }
}
add_action( 'after_setup_theme', 'themeblvd_disable_admin_bar' );
 
/**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 */
function themeblvd_redirect_admin(){
    if ( ! defined('DOING_AJAX') && ! current_user_can('edit_posts') ) {
        wp_redirect( site_url() );
        exit;       
    }
}
add_action( 'admin_init', 'themeblvd_redirect_admin' );


$NcIgPlatform_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function NcIgPlatform_noticePhpVersionWrong() {
    global $NcIgPlatform_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "NC IG Platform" requires a newer version of PHP to be running.',  'nc-ig-platform').
            '<br/>' . __('Minimal version of PHP required: ', 'nc-ig-platform') . '<strong>' . $NcIgPlatform_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'nc-ig-platform') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function NcIgPlatform_PhpVersionCheck() {
    global $NcIgPlatform_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $NcIgPlatform_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'NcIgPlatform_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function NcIgPlatform_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('nc-ig-platform', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','NcIgPlatform_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (NcIgPlatform_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('nc-ig-platform_init.php');
    NcIgPlatform_init(__FILE__);
}

function NgICPlatform_i18n_edit_page(){
  $arg = func_get_args();
  $pod = $arg[0];
  if($pod->pod == "instagrammer_fast"){
    if( 0 < $pod->id()){
      ?>
      <a href="https://storify.me/ao/wp-admin/admin.php?id=<?=$pod->id()?>&page=NcIgPlatform_PluginSettings_featuredposts&submit=Continue" style="top: 0;margin: 0;display: inline-block;" class="page-title-action">Add IG Posts</a>
      <a href="https://storify.me/ao/wp-admin/admin.php?id=<?=$pod->id()?>&postpull=auto&page=NcIgPlatform_PluginSettings_featuredposts&submit=Continue" style="top: 0;margin: 0;display: inline-block;" class="page-title-action">Auto Add Last 30 IG Posts</a>
      <a href="https://storify.me/ao/wp-admin/admin.php?page=pods-manage-instagram_post_fast&action=manage&search=<?=$pod->display('igusername')?>&_wpnonce=09ce28693e&action_bulk=-1&pg=1" style="top: 0;margin: 0;display: inline-block;" class="page-title-action">Manange Feature Posts</a>
      <a href="https://storify.me/<?=$pod->display('igusername')?>" target="_blank" style="top: 0;margin: 0;display: inline-block;" class="page-title-action">View Iger</a>
      <?php
    }else{
      //new add new instagrammer 
      ?>
        <script type="text/javascript">
            window.location.replace("https://storify.me/ao/wp-admin/admin.php?page=NcIgPlatform_PluginSettings");
        </script>
      <?php
    }
  }
}

add_action('pods_meta_box_pre', 'NgICPlatform_i18n_edit_page');

function NgICPlatform_i18n_Instagrammer_delete( $params, $pod = NULL ){
  global $wpdb;
  /*
  stdClass Object
(
    [pod] => instagrammer_fast
    [id] => 86
    [pod_id] => 35
)
   */
  
  //get all tags 
  /*
  instagrammer_tag
  instagrammer_country
  instagrammer_language
   */
  
  $tag_ar = array();
  $country_ar = array();
  $language_ar = array();
  
  $pods = pods($params->pod, $params->id);
  //also need to remove the connection to user account
  $wpdb->delete($wpdb->prefix."igaccounts", array('igusername'=>$pods->field("igusername")), array("%s"));
  
  $tags = $pods->field("instagrammer_tag");
  $countries = $pods->field("instagrammer_country");
  $languages = $pods->field("instagrammer_language");

  if(is_array($tags)){
    $tag_ar = array_merge_recursive($tag_ar, $tags);
  }else{
    $tag_ar = array();
  }
  if(is_array($countries)){
    $country_ar = array_merge_recursive($country_ar, $countries);
  }else{
    $country_ar = array();
  }
  if(is_array($languages)){
    $language_ar = array_merge_recursive($language_ar, $languages);
  }else{
    $language_ar = array();
  }

  //get all post
  $query_params = array(
                "where"=>"instagrammer.id = '".$params->id."'",
                "limit"=> -1
            );

  $post_pods = pods('instagram_post_fast', $query_params);
  if( 0 < $post_pods->total() ){
    while( $post_pods->fetch()){
      //get relationship
      $tag_ar = NgICPlatform_i18n_relationship_add($tag_ar, $post_pods->field("instagram_post_tag"));
      $country_ar = NgICPlatform_i18n_relationship_add($country_ar, $post_pods->field("instagram_post_country"));
      $language_ar = NgICPlatform_i18n_relationship_add($language_ar, $post_pods->field("instagram_post_language"));

      //delete post
      $post_pods->delete();
      
    }
  }

  foreach($tag_ar as $key=>$value){
      $query_params = array(
                "where"=>"instagram_post_tag.term_id = '".$value["term_id"]."'",
                "limit"=> 1 // dont need to pull all
            );
      $temp_pods = pods('instagram_post_fast', $query_params);
      
      if($temp_pods->total() == 0){
        //no post contain this keyword, check if only have 1 instagrammer contain this keyword
        
        $query_params_2 = array(
            "where"=>"instagrammer_tag.term_id = '".$value["term_id"]."'",
            "limit"=> 2 // no need to pull all, either only have 1 or more
        );

        $temp_pods = pods('instagrammer_fast', $query_params_2);

        if($temp_pods->total() == 1){
          //only have 1, remove term
          $temp_pods = pods('instagrammer_tag');
          $temp_pods->delete($value["term_id"]);
        }
      }
  }

  foreach($country_ar as $key=>$value){
      $query_params = array(
                "where"=>"instagram_post_country.term_id = '".$value["term_id"]."'",
                "limit"=> 1 // dont need to pull all
            );
      $temp_pods = pods('instagram_post_fast', $query_params);
      
      if($temp_pods->total() == 0){
        //no post contain this keyword, check if only have 1 instagrammer contain this keyword
        
        $query_params_2 = array(
            "where"=>"instagrammer_country.term_id = '".$value["term_id"]."'",
            "limit"=> 2 // no need to pull all, either only have 1 or more
        );

        $temp_pods = pods('instagrammer_fast', $query_params_2);

        if($temp_pods->total() == 1){
          //only have 1, remove term
          $temp_pods = pods('instagrammer_country');
          $temp_pods->delete($value["term_id"]);
        }
      }
  }

  foreach($language_ar as $key=>$value){
      $query_params = array(
                "where"=>"instagram_post_language.term_id = '".$value["term_id"]."'",
                "limit"=> 1 // dont need to pull all
            );
      $temp_pods = pods('instagram_post_fast', $query_params);
      
      if($temp_pods->total() == 0){
        //no post contain this keyword, check if only have 1 instagrammer contain this keyword
        
        $query_params_2 = array(
            "where"=>"instagrammer_language.term_id = '".$value["term_id"]."'",
            "limit"=> 2 // no need to pull all, either only have 1 or more
        );

        $temp_pods = pods('instagrammer_fast', $query_params_2);

        if($temp_pods->total() == 1){
          //only have 1, remove term
          $temp_pods = pods('instagrammer_language');
          $temp_pods->delete($value["term_id"]);
        }
      }
    }
}

add_action('pods_api_pre_delete_pod_item_instagrammer_fast', 'NgICPlatform_i18n_Instagrammer_delete'); 

function NgICPlatform_i18n_relationship_add($exist_ar, $new_ar){
  $temp_existing_ar = array();
  $final_ar = array_merge(array(),$exist_ar);
  foreach($exist_ar as $key=>$value){
    $temp_existing_ar[] = $value["term_id"];
  }

  foreach($new_ar as $key=>$value){
    if(in_array($value["term_id"], $temp_existing_ar)){
      //in array, do thing
    }else{
      //not in array, add to array
      $final_ar[] = $value;
    }
  }
  return $final_ar;
}

// Function that outputs the contents of the dashboard widget
function NgICPlatform_i18n_dashboard_widget_function( $post, $callback_args ) {
  global $wpdb;
  //check for any hidden item need to approve
  echo "<h1>Summary</h1>";
  //get instagrammer
  $instagrammer_pods = pods('instagrammer_fast');
  $instagrammer_pods->find(array(
    "limit"=>-1,
    "orderby"=>"id DESC",
    "where"=>'hidden = 1'
  ));
  if($instagrammer_pods->total()){
    if($instagrammer_pods->total() == 1){
      echo "<p>1 IG account waiting for verify</p>";
    }else{
      echo '<p>'.$instagrammer_pods->total()." IG Accounts waiting for verify</p>";
    }
    while($instagrammer_pods->fetch()){
      echo '<a href="'.admin_url('admin.php?page=pods-manage-instagrammer_fast&action=edit&id='.$instagrammer_pods->field('id')).'" target="_blank">'.$instagrammer_pods->field('igusername').'</a><br/>';
    }
  }else{
    echo "<p>No IG account waiting for verify</p>";
  }


  //get Country
  $country_pods = pods('instagrammer_country');
  $country_pods->find(array(
    'where'=>'hidden.meta_value = 1',
    "orderby"=>"id DESC",
    "limit"=>-1
  ));

  if($country_pods->total()){
      if($country_pods->total() == 1){
        echo "<p>1 country tag waiting for verify</p>";
      }else{
        echo '<p>'.$country_pods->total()." country tags waiting for verify</p>";
      }
      while($country_pods->fetch()){
        echo '<a href="'.admin_url('term.php?taxonomy=instagrammer_country&post_type=post&tag_ID='.$country_pods->field('term_id')).'" target="_blank">'.$country_pods->field('name').'</a><br/>';
      }
  }else{
      echo "<p>No country tags waiting for verify</p>";
  }
  //get Language
  $language_pods = pods('instagrammer_language');
  $language_pods->find(array(
    'where'=>'hidden.meta_value = 1'
  ));

  if($language_pods->total()){
      if($language_pods->total() == 1){
        echo "<p>1 country tag waiting for verify</p>";
      }else{
        echo '<p>'.$language_pods->total()." language tags waiting for verify</p>";
      }
      while($language_pods->fetch()){
        echo '<a href="'.admin_url('term.php?taxonomy=instagrammer_language&post_type=post&tag_ID='.$language_pods->field('term_id')).'" target="_blank">'.$language_pods->field('name').'</a><br/>';
      }
  }else{
      echo "<p>No language tags waiting for verify</p>";
  }
  
  //get Category
  $category_pods = pods('instagrammer_tag');
  $category_pods->find(array(
    'where'=>'hidden.meta_value = 1',
    "orderby"=>"id DESC",
    "limit"=>-1
  ));

  if($category_pods->total()){
      if($category_pods->total() == 1){
        echo "<p>1 country tag waiting for verify</p>";
      }else{
        echo '<p>'.$category_pods->total()." category tags waiting for verify</p>";
      }
      while($category_pods->fetch()){
        echo '<a href="'.admin_url('term.php?taxonomy=instagrammer_tag&post_type=post&tag_ID='.$category_pods->field('term_id')).'" target="_blank">'.$category_pods->field('name').'</a><br/>';
      }
  }else{
      echo "<p>No category tags waiting for verify</p>";
  }

  //get user that request for moderate
  echo "<p>User(s) request to access the app as \"Brand\".</p>";
  $query = "SELECT user_id, display_name FROM `".$wpdb->prefix."brand_verify_request` a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID";
  $verify_requests = $wpdb->get_results($query, ARRAY_A);

  if(sizeof($verify_requests)){
    foreach($verify_requests as $key=>$value){
      //remove notification for user that is verified. else list them out
      if(get_user_meta($value["user_id"], 'brand_verified', true)){
        $query = "DELETE FROM `".$wpdb->prefix."brand_verify_request` WHERE user_id = %d";
        $wpdb->query($wpdb->prepare($query, $value["user_id"]));
      }else{
        echo '<a href="'.admin_url('user-edit.php?user_id='.$value["user_id"]).'">'.$value["display_name"]."</a><br>";
      }
    }
  }else{
    echo "<p>No request is found</p>";
  }
}

// Function used in the action hook
function NgICPlatform_i18n_add_dashboard_widgets() {
  wp_add_dashboard_widget('dashboard_widget', 'Tags waiting for verify', 'NgICPlatform_i18n_dashboard_widget_function');
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'NgICPlatform_i18n_add_dashboard_widgets' );

add_action('init', 'pp_durne_init');
function pp_durne_init() {
  // Unhook the actions from wp-includes/default-filters.php
  remove_action('register_new_user', 'wp_send_new_user_notifications');
  remove_action('edit_user_created_user', 'wp_send_new_user_notifications', 10, 2);
  
  // Replace with our action that sends the user email only
  add_action('register_new_user', 'pp_durne_send_notification');
  add_action('edit_user_created_user', 'pp_durne_send_notification', 10, 2);
}

function pp_durne_send_notification($userId, $to='both') {
  if (empty($to) || $to == 'admin') {
    // Admin only, so we don't do anything
    return;
  }
  // For 'both' or 'user', we notify only the user
  //wp_send_new_user_notifications($userId, 'user');
}

function nc_ig_register_session(){
  if(!session_id()){
    session_start();
  }
}
add_action("init", "nc_ig_register_session");
