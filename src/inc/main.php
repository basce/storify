<?php
namespace storify;

date_default_timezone_set('Asia/Singapore');

define('WP_USE_THEMES', false);
require_once(__DIR__.'/storify/staticparam.php');
require_once(__DIR__.'/../ao/wp-load.php');
include_once(__DIR__.'/../ao/wp-admin/includes/image.php' );
require_once(__DIR__.'/storify/bookmark.php');
require_once(__DIR__.'/storify/pagesettings.php');
require_once(__DIR__.'/storify/notification.php');
require_once(__DIR__.'/storify/project/project.php');
require_once(__DIR__."/noisycrayons/phpemail/mailer.php");
require_once(__DIR__."/vendor/autoload.php");

use storify\project as project;
use storify\bookmark as bookmark;
use storify\notification as notification;
use storify\pagesettings as pagesettings;
use storify\staticparam as staticparam;
use noisycrayons\phpemail\mailer as mailer;

class main{
	private $nc_tags = NULL;
	private $nc_brands = NULL;
	private $nc_inused_tags = NULL;
	private $nc_countries = NULL;
	private $nc_inused_countries = NULL;
	private $nc_languages = NULL;
	private $cache = NULL;
	private $cache_enable = 1;
	private $cache_duration = 3600;

	private $bookmark_story = NULL;
	private $bookmark_people = NULL;

	private $project_manager = NULL;
	private $email_manager = NULL;

	function __construct(){

	}

	public function getEmailer(){
		if(!$this->email_manager){
			mailer::setSenderAndDKIM(
				"no-reply",
				"no-reply@storify.me",
				true,
				AWS_SMTP_ENDPOINT,
				AWS_PORT,
				AWS_SMTP_USERNAME,
				AWS_SMTP_PASSWORD,
				"",
				"",
				"",
				""
			);
			$this->email_manager = new mailer();
		}
		return $this->email_manager;
	}

	public function gen_string($string,$max=20) {
	    $tok = strtok($string,' ');
	    $sub = '';
	    while($tok !== false && mb_strlen($sub) < $max) {
	        if(strlen($sub) + mb_strlen($tok) <= $max) {
	            $sub .= $tok.' ';
	        } else {
	            break;
	        }
	        $tok = strtok(' ');
	    }
	    $sub = trim($sub);
	    if(mb_strlen($sub) == 0){
	    	$sub = mb_substr($string, 0, $max-1);
	    }
	    if(mb_strlen($sub) < mb_strlen($string)) $sub .= '&hellip;';
	    return $sub;
	}

	public function getCountriesList(){
		return staticparam::$user_country_ar;
	}

	public function getProjectManager(){
		if(!$this->project_manager){
			$this->project_manager = new project();
		}
		return $this->project_manager;
	}

	public function setCacheParams($cache_enable, $cache_duration){
		$this->cache_enable = $cache_enable;
		$this->cache_duration = $cache_duration;
	}

	public function getBookmarkStory(){
		if(!$this->bookmark_story){
			$this->bookmark_story = new bookmark("story");
		}
		return $this->bookmark_story;
	}

	public function getBookmarkPeople(){
		if(!$this->bookmark_people){
			$this->bookmark_people = new bookmark("people");
		}
		return $this->bookmark_people;
	}

	public function checkBookmarkItem($itemid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->check($current_user->ID, $itemid);
	}

	//bookmark function
	public function addBookmark($itemid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->add($current_user->ID, $itemid);
	}

	public function removeBookmark($itemid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$bookmark->remove($current_user->ID, $itemid);
	}

	public function getBookmark($type, $orderby="", $pagesize=24, $page=1, $groupid=0){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}

		if($groupid){
			$selected_items = $bookmark->getGroupItem($groupid);
			if(sizeof($selected_items)){
				$selected_ids = array();
				foreach($selected_items as $key=>$value){
					$selected_ids[] = $value["item_id"];
				}
				$bookmark_items = $bookmark->get($current_user->ID, implode(",",$selected_ids));
			}else{
				$bookmark_items = $bookmark->get($current_user->ID);
			}
		}else{
			$bookmark_items = $bookmark->get($current_user->ID);
		}
		switch($orderby){
			case "oldest":
				$bookmark_items = $this->array_orderby($bookmark_items, "tt", SORT_ASC);
			break;
			case "latest":
			default:
				$bookmark_items = $this->array_orderby($bookmark_items, "tt", SORT_DESC);
			break;
		}

		$totalsize = sizeof($bookmark_items);
		$totalpage = ceil(sizeof($bookmark_items) / $pagesize);
		if($page > $totalpage || $page < 1){
			$page = 1;
		}
		$bookmark_items = array_slice($bookmark_items, ($page - 1)*$pagesize, $pagesize);		

		$post_ids = array();
		foreach($bookmark_items as $key=>$value){
			$post_ids[] = $value["item_id"];
		}

		//get item details
		if(sizeof($post_ids)){
			$items = array();
			if($type == "people"){
				$items = $this->getIgerIn($post_ids);
			}else{
				$items = $this->getStoryIn($post_ids);
			}

			$item_objs = array();
			foreach($items as $key=>$value){
				$item_objs[$value["id"]] = $value;
				$item_objs[$value["id"]]["bookmark"] = 1;
			}

			$new_bookmarkitems = array();
			foreach($bookmark_items as $key=>$value){
				$temp_obj = $item_objs[$value["item_id"]];
				$temp_obj["last_update"] = $value["tt"];
				$new_bookmarkitems[] = $temp_obj;
			}
			$bookmark_items = $new_bookmarkitems;
		}

		return array(
			"data"=>$bookmark_items,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}

	public function getBookmarkFirstFour($type){
		$bookmarks = $this->getBookmark($type);
		return array_slice($bookmarks, -4, 4);
	}

	public function getBookmarkList($type){
		$list = $this->getBookmark($type);
		//
		$query = array(
			"limit"=>-1,
			"where"=>"id IN (".implode($list,",").")"
		);
		$igers_pods = pods("instagrammer_fast", $query);
		$igers = array();
		if(0 < $igers_pods->total()){
			while($igers_pods->fetch()){
				$tempobj = array(
	                "id"=>$igers_pods->field('id'),
	                "name"=>$igers_pods->field('name'),
	                "ig_id"=>$igers_pods->field('ig_id'),
	                "igusername"=>$igers_pods->field('igusername'),
	                "ig_profile_pic"=>pods_image_url($igers_pods->field('display_image')["ID"], 'large'),
	                "hr_image"=>pods_image_url($igers_pods->field('display_image')["ID"], NULL),
	                "biography"=>$igers_pods->field('biography'),
	                "media_count"=>$igers_pods->field('media_count'),
	                "follows_by_count"=>$igers_pods->field('follows_by_count'),
	                "external_url"=>$igers_pods->field('external_url'),
	                "instagrammer_tag"=>$igers_pods->field('instagrammer_tag'),
	                "instagrammer_country"=>$igers_pods->field('instagrammer_country'),
	                "instagrammer_language"=>$igers_pods->field('instagrammer_language'),
	                "average_likes"=>$igers_pods->field('average_likes'),
	                "average_comments"=>$igers_pods->field('average_comments'),
                	"modified"=>date('j M y H:i',strtotime($igers_pods->field('modified'))),
                	"unformatted_modified"=>strtotime($igers_pods->field('modified')),
                	"verified"=>$igers_pods->field('verified') 
	            );
	            $igers[] = $tempobj;
			}
		}
		return $igers;
	}

	public function addGroup($name, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->addGroup($name, $current_user->ID);
	}

	public function editGroup($name, $groupid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$bookmark->editGroup($name, $groupid);
	}

	public function deleteGroup($groupid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$bookmark->deleteGroup($groupid);
	}

	public function checkGroupOwnerShip($groupid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->checkGroupOwnerShip($groupid, $current_user->ID);
	}

	public function checkGroupOwnerShipByID($groupid, $userid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark = $bookmark->checkGroupOwnerShip($groupid, $userid);
	}

	public function getGroupDetail($groupid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$groupDetails = $bookmark->getGroupDetail($groupid);
		return sizeof($groupDetails) ? $groupDetails[0] : null;
	}

	public function getRandomGroupFolderImage($groupid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}

		$result = $bookmark->getGroupItem($groupid, 1);

		if($result && sizeof($result)){
			if($type == "people"){
				$items = $this->getIgerIn(array($result[0]["item_id"]));
				if(sizeof($items) && isset($items[0]["hr_image"])){
					return $items[0]["hr_image"];
				}
			}else{
				$items = $this->getStoryIn(array($result[0]["item_id"]));
				if(sizeof($items) && isset($items[0]["hr_image"])){
					return $items[0]["hr_image"];
				}
			}
		}
		
		return NULL;
	}

	public function getGroup($type, $orderby="", $pagesize=24, $page=1){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$group_list = $bookmark->getGroup($current_user->ID);

		switch($orderby){
			case "name":
				$group_list = $this->array_orderby($group_list, "name", SORT_ASC);
			break;
			case "oldest":
				$group_list = $this->array_orderby($group_list, "last_update", SORT_ASC);
			break;
			case "latest":
			default:
				$group_list = $this->array_orderby($group_list, "last_update", SORT_DESC);
			break;
		}

		$totalsize = sizeof($group_list);
		$totalpage = ceil(sizeof($group_list) / $pagesize);
		if($page > $totalpage || $page < 1){
			$page = 1;
		}
		$group_list = array_slice($group_list, ($page - 1)*$pagesize, $pagesize);

		$post_ids = array();
		foreach($group_list as $key=>$value){
			//get first 4 post,
			$tempitems = $bookmark->getGroupItem($value["id"], 4);
			$group_list[$key]["first_four"] = $tempitems;
			$group_list[$key]["modified"] = date('j M y H:i',strtotime($value["last_update"]));

			foreach($tempitems as $key2=>$value2){
				if(!in_array($value2["item_id"], $post_ids)){
					$post_ids[] = $value2["item_id"];
				}
			}
		}

		//get item details
		if(sizeof($post_ids)){
			$items = array();
			if($type == "people"){
				$items = $this->getIgerIn($post_ids);
			}else{
				$items = $this->getStoryIn($post_ids);
			}

			$item_objs = array();
			foreach($items as $key=>$value){
				$item_objs[$value["id"]] = $value;
			}

			foreach($group_list as $key=>$value){
				foreach($value["first_four"] as $key2=>$value2){
					$group_list[$key]["first_four"][$key2]["data"] = $item_objs[$value2["item_id"]];
				}
			}
		}

		return array(
			"data"=>$group_list,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>$pagesize
		);
	}

	public function addToGroup($itemid, $type, $groupid){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->addItemToGroup($itemid, $groupid);
	}

	public function removeFromGroup($groupitemid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$bookmark->removeItemFromGroup($groupitemid);
	}

	public function checkGroupItemOwnerShip($groupitemid, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		return $bookmark->checkGroupItemOwnerShip($groupitemid, $current_user->ID);	
	}

	public function moveGroupItemTo($groupitemid, $position, $type){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$bookmark->moveItemTo($groupitemid, $position);
	}

	public function getGroupItem($groupid, $type, $orderby, $pagesize=24, $page=1){
		global $current_user;
		if(!$current_user) die("require login");
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		$groupitem = $bookmark->getGroupItem($groupid);

		$totalsize = sizeof($groupitem);
		$totalpage = ceil(sizeof($groupitem) / $pagesize);
		if($page > $totalpage || $page < 1){
			$page = 1;
		}
		$groupitem = array_slice($groupitem, ($page - 1)*$pagesize, $pagesize);	

		$post_ids = array();
		foreach($groupitem as $key=>$value){
			$post_ids[] = $value["item_id"];
		}
		//get item details
		if(sizeof($post_ids)){
			if($type == "people"){
				$items = $this->getIgerIn($post_ids);
			}else{
				$items = $this->getStoryIn($post_ids);
			}
			$item_objs = array();
			foreach($items as $key=>$value){
				$item_objs[$value["id"]] = $value;
			}

			foreach($groupitem as $key=>$value){
				$groupitem[$key]["data"] = $item_objs[$value["item_id"]];
				$groupitem[$key]["modified"] = date('j M y H:i',strtotime($groupitem[$key]["tt"]));
			}
		}
		
		switch($orderby){
			case "oldest":
				$groupitem = $this->array_orderby($groupitem, "tt", SORT_ASC);
			break;
			case "latest":
				$groupitem = $this->array_orderby($groupitem, "tt", SORT_DESC);
			break;
			default:
				$groupitem = $this->array_orderby($groupitem, "sort_index", SORT_ASC);
			break;
		}

		return array(
			"data"=>$groupitem,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>$pagesize
		);
	}

	public function getSummaryByUserID($type, $folder_id = 0, $user_id){
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		if($folder_id > 0){
			//get info without userid
			return $bookmark->getSummary(0, $folder_id);
		}else{
			//either get all bookmark or 'all' folder
			return $bookmark->getSummary($user_id, $folder_id);
		}
	}

	public function getSummary($type, $folder_id = 0){
		if($type == "people"){
			$bookmark = $this->getBookmarkPeople();
		}else{
			$bookmark = $this->getBookmarkStory();
		}
		if($folder_id > 0){
			//get info without userid
			return $bookmark->getSummary(0, $folder_id);
		}else{
			//either get all bookmark or 'all' folder
			global $current_user;
			if(!$current_user) die("require login");
			return $bookmark->getSummary($current_user->ID, $folder_id);
		}
	}

	//============================================================================================================
	public function getCache(){
		if(!$this->cache){
			$this->cache = new \memcached;
			$this->cache->addServer('localhost', 11211) or die ("Could not connect");
		}
		return $this->cache;
	}

	public function copyFileToWP($url, $description, $filename){
		$temp_optionManager = new \NcIgPlatform_OptionsManager();
		return $temp_optionManager->copyFileToWP($url, $description, $filename);
	}

	public function updateLatest30Posts($igusername, $userid){
		$temp_optionManager = new \NcIgPlatform_OptionsManager();
		$result = $temp_optionManager->autoPoll($igusername, $userid, 30);
		if($result["error"]){
			return $result;
		}else{
			//no error, get last 30 posts
			return array(
				"error"=>0,
				"posts"=>$this->getPosts($userid, 30, 1, "latest", true)
			);
		}
	}

	public function checkUpdatingRecord($igusername){

		return $this->getCache()->get("nc_igplatform_post_updating_".$igusername);
		/*
		global $wpdb;

		//update data that is exceed 30 mins
		$query = "UPDATE `".$wpdb->prefix."_postupdating` SET done = %d, msg = %s WHERE done = %d AND tt < ( NOW() - INTERVAL 30 MINUTE )";
		$wpdb->query($wpdb->prepare($query, 1, "exceed 30 mins, terminate process" , 0));

		//check if the data we want is pulling
		$query = "SELECT done, msg FROM `".$wpdb->prefix."_postupdating` WHERE igusername = %s ORDER BY id DESC";
		$result = $wpdb->get_row($wpdb->prepare($query, $igusername), ARRAY_A);

		return $result;
		*/
	}

	public function updateLatest30PostsAsync($igusername, $userid){
		if(!$this->checkUpdatingRecord($igusername)){
			//is not pulling
			
			$obj = array(
				"error"=>0,
				"msg"=>$this->getCache()->get("nc_igplatform_post_updating_error")
			);

			try{
				exec('php '.dirname(__DIR__).'/async_pulling.php '.$igusername.' '.$userid.' >/dev/null &');
			}catch(Exception $e){
				$obj = array(
					"error"=>1,
					"msg"=>$e->getMessage()
				);
			}

			return $obj;
		}else{
			return array(
				"error"=>1,
				"msg"=>"Account is under pulling, please try again later"
			);
		}
	}

	public function insertNewIGAccount($igusername){
		global $wpdb;

		print_r(class_exists('\InstagramScrapper\Instagram')?"class exist":"class not exist");
		/*
		try{
			$instagram = new \InstagramScrapper\Instagram();
			$account = $instagram->getAccount($igusername);

			$fullName = $account["fullName"] ? $account["fullName"] : $igusername;
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

			$result = $this->copyFileToWP($profileImage, $fullName, $igusername);

			$query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
			$prepare = $wpdb->prepare($query, $account["id"]);
			$instagrammer_id = $wpdb->get_var($prepare);

			$pod = pods("instagrammer_fast");
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
			if($instagrammer_id){
				$pod->save($data, null, $instagrammer_id);
			}else{
				$instagrammer_id = $pod->add($data);
			}

		}catch(Exception $e){
            if($e instanceof \InstagramScraper\Exception\InstagramNotFoundException){
                $error_msg = $e->getMessage();
            }else{
                $error_msg = $e->getMessage();
            }
            print_r($error_msg);exit();
        }
        */
	}

	public function setUserIGAccount($userID, $igusername){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."igaccounts` WHERE userid = %d AND LOWER( igusername ) = LOWER( %s )";
		$prepare = $wpdb->prepare($query, $userID, $igusername);
		if($wpdb->get_var($prepare)){
			//already connect, nothing happen
			return $igusername;
		}else{
			//remove all other connection
			$query = "DELETE FROM `".$wpdb->prefix."igaccounts` WHERE LOWER(igusername) = LOWER(%s)";
			$wpdb->query($wpdb->prepare($query, $igusername));

			$query = "INSERT INTO `".$wpdb->prefix."igaccounts` ( userid, igusername ) VALUES ( %d, LOWER( %s ) )";
			$wpdb->query($wpdb->prepare($query, $userID, $igusername));
			return $igusername;
		}
	}

	public function updateUserTags($countries, $languages, $categories, $igusernmae){
		global $wpdb;

		$query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE LOWER( igusername ) = LOWER( %s )";
		$prepare = $wpdb->prepare($query, $igusernmae);
		$instagrammer_id = $wpdb->get_var($prepare);

		$pod = pods("instagrammer_fast", $instagrammer_id);
		$original_countries = $pod->field('instagrammer_country');
		$original_languages = $pod->field('instagrammer_language');
		$original_categories = $pod->field('instagrammer_tag');

		//remove if got any
		if($original_countries && sizeof($original_countries)){
			foreach($original_countries as $key=>$value){
				$pod->remove_from('instagrammer_country', $value["term_id"]);
			}
		}
		/*
		if(sizeof($original_languages)){
			foreach($original_languages as $key=>$value){
				$pod->remove_from('instagrammer_language', $value["term_id"]);
			}
		}
		*/
	
		//check if any tag change
		$category_changed = false;
		if($original_categories && sizeof($original_categories) && (sizeof($original_categories) == sizeof($categories))){
			foreach($original_categories as $key=>$value){
				$exist = false;
				foreach($categories as $key2=>$value2){
					if($value["term_id"] == $value2){
						$exist = true;
					}
				}
				if(!$exist){
					$category_changed = true;
					break;
				}
			}
		}else{
			$category_changed = true;
		}

		//check if any tag change
		$country_changed = false;
		if($original_countries && sizeof($original_countries) && (sizeof($original_countries) == sizeof($countries))){
			foreach($original_countries as $key=>$value){
				$exist = false;
				foreach($countries as $key2=>$value2){
					if($value["term_id"] == $value2){
						$exist = true;
					}
				}
				if(!$exist){
					$country_changed = true;
					break;
				}
			}
		}else{
			$country_changed = true;
		}
		
		if($original_categories && sizeof($original_categories)){
			foreach($original_categories as $key=>$value){
				$pod->remove_from('instagrammer_tag', $value["term_id"]);
			}
		}

		if($countries && sizeof($countries)){
			foreach($countries as $key=>$value){
				$pod->add_to('instagrammer_country', $value);
			}
		}
		if($languages && sizeof($languages)){
			foreach($languages as $key=>$value){
				$pod->add_to('instagrammer_language', $value);
			}
		}
		if($categories && sizeof($categories)){
			foreach($categories as $key=>$value){
				$pod->add_to('instagrammer_tag', $value);
			}
		}

		return array(
			"country_changed"=>$country_changed,
			"category_changed"=>$category_changed
		);
	}

	public function IGAccountBelongToUser($igname, $userID){
		$IGUsernames = $this->getUserIGAccounts($userID);
		$in_array = false;
		if(sizeof($IGUsernames)){
			foreach($IGUsernames as $key=>$value){
				if($value[0] == $igname){
					$in_array = true;
				}
			}
		}
		return $in_array;
	}

	public function getUserIGAccounts($userID){
		global $wpdb;

		$query = "SELECT igusername FROM `".$wpdb->prefix."igaccounts` WHERE userid = %s";
		$prepare = $wpdb->prepare($query, $userID);
		$igaccounts = $wpdb->get_results($prepare, ARRAY_N);
		return $igaccounts;
	}

	public function parseUserTerm($str){
		preg_match('/user@([\d]+)/',$str,$matches);
		if(sizeof($matches)){
			return $matches[1];
		}else{
			return null;
		}
	}

	public function split_name($name) {
	    $name = trim($name);
	    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
	    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
	    return array($first_name, $last_name);
	}

	public function random_username($string) {
	    $pattern = " ";
		$firstPart = strstr(strtolower($string), $pattern, true);
		$secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);

		$username = trim($firstPart).trim($secondPart);
	    return $username;
	}

	public function createUser($email, $password, $name, $gender, $city_country, $newsletter){
		//check if email is already in used.
		
		$exists = email_exists ( $email );
		if( $exists ){
			return array(
				"error"=>1,
				"msg"=>"email is registered"
			);
		}else{
			//new email
			$result = register_new_user($email, $email); //register username as email
			if( is_wp_error ($result) ){
				//register error
				return array(
					"error"=>1,
					"msg"=>json_encode($result)
				);
			}else{
				//register success
				$user_id = $result;
				//set password
				wp_set_password($password, $user_id);

				$result2 = wp_update_user(array(
					"ID"=>$user_id,
					"display_name"=>$name,
					"user_nicename"=>$this->random_username($name)
				));

				$full_name = $this->split_name($name);

				update_user_meta($user_id, 'first_name', $full_name[0]);
				update_user_meta($user_id, 'last_name', $full_name[1]);

				//update user meta ,gender and country / city
				update_user_meta($user_id, 'gender', $gender);
				update_user_meta($user_id, 'city_country', $city_country);
				update_user_meta($user_id, 'newsletter', $newsletter);

				return array(
					"error"=>0,
					"id"=>$user_id
				);
			}
		}
	}

	public function getIgerIn($list){
		$query = array(
			"limit"=>-1,
			"where"=>"id IN (".implode($list,",").")"
		);
		$igers_pods = pods("instagrammer_fast", $query);
		$igers = array();
		if(0 < $igers_pods->total()){
			while($igers_pods->fetch()){
				$tempobj = array(
	                "id"=>$igers_pods->field('id'),
	                "name"=>$igers_pods->field('name'),
	                "ig_id"=>$igers_pods->field('ig_id'),
	                "igusername"=>$igers_pods->field('igusername'),
	                "ig_profile_pic"=>pods_image_url($igers_pods->field('display_image')["ID"], 'large'),
	                "hr_image"=>pods_image_url($igers_pods->field('display_image')["ID"], NULL),
	                "biography"=>$igers_pods->field('biography'),
	                "media_count"=>$igers_pods->field('media_count'),
	                "follows_by_count"=>$igers_pods->field('follows_by_count'),
	                "external_url"=>$igers_pods->field('external_url'),
	                "instagrammer_tag"=>$igers_pods->field('instagrammer_tag'),
	                "instagrammer_country"=>$igers_pods->field('instagrammer_country'),
	                "instagrammer_language"=>$igers_pods->field('instagrammer_language'),
	                "average_likes"=>$igers_pods->field('average_likes'),
	                "average_comments"=>$igers_pods->field('average_comments'),
	                "created"=>strtotime($igers_pods->field('created')),
                	"modified"=>date('j M y H:i',strtotime($igers_pods->field('modified'))),
                	"unformatted_modified"=>strtotime($igers_pods->field('modified')),
                	"verified"=>$igers_pods->field("verified")
	            );
	            $igers[] = $tempobj;
			}
		}
		return $igers;
	}

	public function getStoryIn($list){
		$query = array(
			"limit"=>-1,
			"where"=>"id IN (".implode($list,",").")"
		);

		$post_pods = pods("instagram_post_fast", $query);
		$posts = array();
		if(0 < $post_pods->total()){
			while($post_pods->fetch()){
				$posts[] = array(
					"id"=>$post_pods->field('id'),
					"name"=>$post_pods->field('name'),
					"image_hires"=>pods_image_url($post_pods->field('image_hires')["ID"], 'large'),
					"hr_image"=>pods_image_url($post_pods->field('image_hires')["ID"], NULL),
					"instagrammer"=>$post_pods->field('instagrammer'),
					"caption"=>$post_pods->field('caption'),
					"likes"=>$post_pods->field('likes'),
					"comments"=>$post_pods->field('comments'),
					"link"=>$post_pods->field('ig_link'),
					"post_tag"=>$post_pods->field('instagram_post_tag'),
					"post_country"=>$post_pods->field('instagram_post_country'),
					"post_language"=>$post_pods->field('instagram_post_language'),
					"modified"=>date('j M y H:i', strtotime($post_pods->field('post_created_time'))),
                	"unformatted_modified"=>strtotime($post_pods->field('post_created_time'))
				);
			}
		}

		return $posts;
	}

	public function createTag($tagType, $input){
		switch ($tagType) {
			case 'country':
				$pod = pods('instagrammer_country');
				break;
			case 'language':
				$pod = pods('instagrammer_language');
				break;
			case "brand":
				$pod = pods('brand');
			break;
			case 'category':
			default:
				$pod = pods('instagrammer_tag');
				break;
		}
		//check if pod exist with slug or name
		$params = array(
			'where'=>'UPPER(t.name) = UPPER("'.$input.'")'
		);
		$pod->find($params);
		$tempobj = NULL;
		if($pod->total()){
			while($pod->fetch()){
				$tempobj = array(
	                "id"=>$pod->field('term_id'),
	                "name"=>$pod->field('name'),
	                "hidden"=>sizeof($pod->field('hidden'))?$pod->field('hidden'):0
	            );
			}
		}

		if($tempobj){
			return array(
				"text"=>$tempobj["name"],
				"term_id"=>$tempobj["id"]
			);
		}else{
			//new item
			$temp_id = $pod->add(array(
				"name"=>$input,
				"hidden"=>1
			));
			return array(
				"text"=>$input,
				"term_id"=>$temp_id
			);
		}
	}

	public function getQueryPath(){
		$url_parts = parse_url($_SERVER['REQUEST_URI']);
	    $request = $url_parts["path"];
	    return explode("/", trim($request, "/"));
	}

	public function getSingleIger($igerID){
		global $current_user;
		$tempobj = null;
		if(is_int($igerID)){
			$igers_pods = pods("instagrammer_fast", $igerID);

			if($igers_pods->field("id")){
				$tempobj = array(
	                "id"=>$igers_pods->field('id'),
	                "name"=>$igers_pods->field('name'),
	                "ig_id"=>$igers_pods->field('ig_id'),
	                "igusername"=>$igers_pods->field('igusername'),
	                "ig_profile_pic"=>pods_image_url($igers_pods->field('ig_profile_pic')["ID"], 'large'),
	                "hr_image"=>pods_image_url($igers_pods->field('ig_profile_pic')["ID"], NULL),
	                "display_image"=>pods_image_url($igers_pods->field('display_image')["ID"], 'large'),
	                "biography"=>$igers_pods->field('biography'),
	                "media_count"=>$igers_pods->field('media_count'),
	                "follows_by_count"=>$igers_pods->field('follows_by_count'),
	                "external_url"=>$igers_pods->field('external_url'),
	                "instagrammer_tag"=>$igers_pods->field('instagrammer_tag'),
	                "instagrammer_country"=>$igers_pods->field('instagrammer_country'),
	                "instagrammer_language"=>$igers_pods->field('instagrammer_language'),
	                "average_likes"=>$igers_pods->field('average_likes'),
	                "average_comments"=>$igers_pods->field('average_comments'),
	                "created"=>strtotime($igers_pods->field('created')),
	                "modified"=>date('j M y H:i', strtotime($igers_pods->field('modified'))),
	                "unformatted_modified"=>strtotime($igers_pods->field('modified')),
	                "verified"=>$igers_pods->field("verified")
	            );
			}
		}else{
			//check is igusername
			$query = array(
				"limit"=>1,
				"where"=>"t.igusername = '".$igerID."'"
			);

			$igers_pods = pods("instagrammer_fast", $query);

			if(0 < $igers_pods->total()){
				while($igers_pods->fetch()){
					$tempobj = array(
		                "id"=>$igers_pods->field('id'),
		                "name"=>$igers_pods->field('name'),
		                "ig_id"=>$igers_pods->field('ig_id'),
		                "igusername"=>$igers_pods->field('igusername'),
		                "ig_profile_pic"=>pods_image_url($igers_pods->field('ig_profile_pic')["ID"], 'large'),
		                "hr_image"=>pods_image_url($igers_pods->field('ig_profile_pic')["ID"], NULL),
		                "display_image"=>pods_image_url($igers_pods->field('display_image')["ID"], 'large'),
		                "biography"=>$igers_pods->field('biography'),
		                "media_count"=>$igers_pods->field('media_count'),
		                "follows_by_count"=>$igers_pods->field('follows_by_count'),
		                "external_url"=>$igers_pods->field('external_url'),
		                "instagrammer_tag"=>$igers_pods->field('instagrammer_tag'),
		                "instagrammer_country"=>$igers_pods->field('instagrammer_country'),
		                "instagrammer_language"=>$igers_pods->field('instagrammer_language'),
		                "average_likes"=>$igers_pods->field('average_likes'),
		                "average_comments"=>$igers_pods->field('average_comments'),
		                "created"=>strtotime($igers_pods->field('created')),
	                	"modified"=>date('j M y H:i',strtotime($igers_pods->field('modified'))),
	                	"unformatted_modified"=>strtotime($igers_pods->field('modified')),
	                	"verified"=>$igers_pods->field("verified")
		            );
				}
			}
		}
		
		if($tempobj){

			//get post of the user
			if(sizeof($current_user) && $current_user->ID){
				//check bookmark
				$bookmark = $this->getBookmarkPeople();
				$tempobj["bookmark"] = $bookmark->check($current_user->ID, $tempobj["id"]);
			}else{
				$tempobj["bookmark"] = 0;
			}

			return array(
				'iger'=>$tempobj,
				'posts'=>null
			);
		}else{
			return null;
		}
	}

	function search_name_exact($str){
		global $wpdb;

		//check if exact name exist
		
	}

	function search($input){
		$input = trim($input);
		$searhterms = array();
		if($input){ //no empty
			if(str_pos($input," ")){
				//with empty space

			}else{
				
			}
		}
	}

	function getAllTagsInUsed($forceRefresh = false){
		global $wpdb;
		
		if($this->nc_inused_tags && !$forceRefresh){
			return $this->nc_inused_tags;
		}
		$tags = $this->getCache()->get("nc_igplatform_inused_tags");
		if($tags && !$forceRefresh && $this->cache_enable){
			$this->nc_inused_tags = $tags;
			return $this->nc_inused_tags;
		}

		/*
		SELECT a.name, a.term_id, b.meta_value as `hidden` FROM ( SELECT name, term_id FROM `wp_terms` WHERE term_id IN ( SELECT related_item_id FROM `wp_podsrel` WHERE field_id = 48 AND item_id IN ( SELECT id FROM `wp_pods_instagrammer_fast` WHERE hidden = 0 ) GROUP BY related_item_id ) ) a LEFT JOIN ( SELECT term_id, meta_value FROM `wp_termmeta` WHERE meta_key = 'hidden' ) b ON a.term_id = b.term_id WHERE b.meta_value is NULL OR b.meta_value = 0
		 */
		
		$query = "SELECT a.name, a.term_id, b.meta_value as `hidden` FROM ( SELECT name, term_id FROM `wp_terms` WHERE term_id IN ( SELECT related_item_id FROM `wp_podsrel` WHERE field_id = %d AND item_id IN ( SELECT id FROM `wp_pods_instagrammer_fast` WHERE hidden = %d ) GROUP BY related_item_id ) ) a LEFT JOIN ( SELECT term_id, meta_value FROM `wp_termmeta` WHERE meta_key = %s ) b ON a.term_id = b.term_id WHERE b.meta_value is NULL OR b.meta_value = %d ORDER BY a.name ASC";

		$result = $wpdb->get_results($wpdb->prepare($query, 48, 0, 'hidden', 0), ARRAY_A);

		$ar = array();
		foreach($result as $key=>$value){
			$ar[] = array(
				"term_id"=>$value["term_id"],
				"name"=>$value["name"],
				"hidden"=>isset($value["hidden"]) ? $value["hidden"] : 0
			);
		}
		$this->nc_inused_tags = $ar;
		$this->getCache()->set("nc_igplatform_inused_tags", $ar, $this->cache_duration);
		return $this->nc_inused_tags;
	}

	function getAllTags($forceRefresh = false){
		if($this->nc_tags && !$forceRefresh){
			return $this->nc_tags;
		}
		$tags = $this->getCache()->get("nc_igplatform_tags");
		if($tags && !$forceRefresh && $this->cache_enable){
			$this->nc_tags = $tags;
			return $this->nc_tags;	
		}

		$tag_pods = pods("instagrammer_tag");
		$tag_pods->find(array(
			"limit"=>-1,
			"orderby"=>"name ASC"
		));

		$ar = array();

		if(0 < $tag_pods->total()){
			while($tag_pods->fetch()){
				$ar[] = array(
					"term_id"=>$tag_pods->field("term_id"),
					"name"=>$tag_pods->field("name"),
					"hidden"=>sizeof($tag_pods->field("hidden"))?$tag_pods->field("hidden"):0
				);
			}
		}
		$this->nc_tags = $ar;
		$this->getCache()->set("nc_igplatform_tags", $ar, $this->cache_duration); // 1 day
		return $this->nc_tags;
	}

	function getAllCountriesInUsed($forceRefresh = false){
		global $wpdb;
		
		if($this->nc_inused_countries && !$forceRefresh){
			return $this->nc_inused_countries;
		}
		$countries = $this->getCache()->get("nc_igplatform_inused_countries");
		if($countries && !$forceRefresh && $this->cache_enable){
			$this->nc_inused_countries = $countries;
			return $this->nc_inused_countries;
		}
		
		$query = "SELECT a.name, a.term_id, b.meta_value as `hidden` FROM ( SELECT name, term_id FROM `wp_terms` WHERE term_id IN ( SELECT related_item_id FROM `wp_podsrel` WHERE field_id = %d AND item_id IN ( SELECT id FROM `wp_pods_instagrammer_fast` WHERE hidden = %d ) GROUP BY related_item_id ) ) a LEFT JOIN ( SELECT term_id, meta_value FROM `wp_termmeta` WHERE meta_key = %s ) b ON a.term_id = b.term_id WHERE b.meta_value is NULL OR b.meta_value = %d ORDER BY a.name ASC";

		$result = $wpdb->get_results($wpdb->prepare($query, 49, 0, 'hidden', 0), ARRAY_A);

		$ar = array();
		foreach($result as $key=>$value){
			$ar[] = array(
				"term_id"=>$value["term_id"],
				"name"=>$value["name"],
				"hidden"=>isset($value["hidden"]) ? $value["hidden"] : 0
			);
		}
		$this->nc_inused_countries = $ar;
		$this->getCache()->set("nc_igplatform_inused_countries", $ar, $this->cache_duration);
		return $this->nc_inused_countries;
	}

	function getAllCountries($forceRefresh = false){
		if($this->nc_countries && !$forceRefresh){
			return $this->nc_countries;
		}
		$countries = $this->getCache()->get("nc_igplatform_countries");
		if($countries && !$forceRefresh && $this->cache_enable){
			$this->nc_countries = $countries;
			return $this->nc_countries;
		}

		$country_pods = pods("instagrammer_country");
		$country_pods->find(array(
			"limit"=>-1,
			"orderby"=>"name ASC"
		));

		$ar = array();
		if(0 < $country_pods->total()){
			while($country_pods->fetch()){
				$ar[] = array(
					"term_id"=>$country_pods->field("term_id"),
					"name"=>$country_pods->field("name"),
					"hidden"=>sizeof($country_pods->field("hidden"))?$country_pods->field("hidden"):0
				);
			}
		}
		$this->nc_countries = $ar;
		$this->getCache()->set("nc_igplatform_countries", $ar, $this->cache_duration); // 1 day
		return $this->nc_countries;
	}

	function getAllBrands($forceRefresh = false){
		if($this->nc_brands && !$forceRefresh){
			return $this->nc_brands;
		}

		$brands = $this->getCache()->get("nc_igplatform_brands");
		if($brands && !$forceRefresh && $this->cache_enable){
			$this->nc_brands = $brands;
			return $this->nc_brands;
		}

		$brand_pods = pods("brand");
		$brand_pods->find(array(
			"limit"=>-1,
			"orderby"=>"name ASC"
		));

		$ar = array();
		if(0 < $brand_pods->total()){
			while($brand_pods->fetch()){
				$ar[] = array(
					"term_id"=>$brand_pods->field("term_id"),
					"name"=>$brand_pods->field("name"),
					"hidden"=>0
				);
			}
		}
		$this->nc_brands = $ar;
		$this->getCache()->set("nc_igplatform_brands", $ar, $this->cache_duration);
		return $this->nc_brands;
	}

	function getAllLanguages($forceRefresh = false){
		if($this->nc_languages && !$forceRefresh){
			return $this->nc_languages;
		}
		$languages = $this->getCache()->get("nc_igplatform_languages");
		if($languages && !$forceRefresh && $this->cache_enable){
			$this->nc_languages = $languages;
			return $this->nc_languages;
		}

		$language_pods = pods("instagrammer_language");
		$language_pods->find(array(
			"limit"=>-1,
			"orderby"=>"name ASC"
		));

		$ar = array();
		if(0 < $language_pods->total()){
			while($language_pods->fetch()){
				$ar[] = array(
					"term_id"=>$language_pods->field("term_id"),
					"name"=>$language_pods->field("name"),
					"hidden"=>sizeof($language_pods->field("hidden"))?$language_pods->field("hidden"):0
				);
			}
		}
		$this->nc_languages = $ar;
		$this->getCache()->set("nc_igplatform_languages", $ar, $this->cache_duration); // 1 day
		return $this->nc_languages;
	}

	function convertCauseToText($cause){
		$category = isset($cause["category"]) ? $cause["category"] : array();
		$country = isset($cause["country"]) ? $cause["country"] : array();
		$language = isset($cause["language"]) ? $cause["language"] : array();

		$category_name = array();
		$country_name = array();
		$language_name = array();
		if(sizeof($category)){
			foreach($category as $key=>$value){
				$pod = pods("instagrammer_tag", $value);
				$category_name[] = $pod->field("name");
			}
		}
		if(sizeof($country)){
			foreach($country as $key=>$value){
				$pod = pods("instagrammer_country", $value);
				$country_name[] = $pod->field("name");
			}
		}
		if(sizeof($language)){
			foreach($language as $key=>$value){
				$pod = pods("instagrammer_language", $value);
				$language_name[] = $pod->field("name");
			}
		}
		return array(
			"category"=>$category_name,
			"country"=>$country_name,
			"language"=>$language_name
		);
	}

	function getPosts($id, $pagesize=24, $page=1, $orderby="", $forceRefresh=false){
		global $current_user;
		$posts = $this->getCache()->get("nc_user_".$id);

		if($forceRefresh || !$posts || !$this->cache_enable){

			$query = array(
				"limit"=>-1,
				"where"=>"instagrammer.id = ".$id
			);

			$post_pods = pods("instagram_post_fast", $query);
			$posts = array();
			if(0 < $post_pods->total()){
				while($post_pods->fetch()){
					$posts[] = array(
						"id"=>$post_pods->field('id'),
						"name"=>$post_pods->field('name'),
						"image_hires"=>str_replace('https://storify.me/', 'https://cdn.storify.me/', pods_image_url($post_pods->field('image_hires')["ID"], 'large')),
						"hr_image"=>str_replace('https://storify.me/', 'https://cdn.storify.me/', pods_image_url($post_pods->field('image_hires')["ID"], NULL)),
						"caption"=>$post_pods->field('caption'),
						"likes"=>$post_pods->field('likes'),
						"comments"=>$post_pods->field('comments'),
						"link"=>$post_pods->field('ig_link'),
						"post_tag"=>$post_pods->field('instagram_post_tag'),
						"post_country"=>$post_pods->field('instagram_post_country'),
						"post_language"=>$post_pods->field('instagram_post_language'),
						"created"=>strtotime($post_pods->field('post_created_time')),
						"modified"=>date('j M y H:i', strtotime($post_pods->field('post_created_time'))+8*3600),
	                	"unformatted_modified"=>strtotime($post_pods->field('post_created_time'))+8*3600,
	                	"last_updated_time"=>strtotime($post_pods->field('modified'))
					);
				}
			}

			$this->getCache()->set("nc_user_".$id, $posts, $this->cache_duration); // 1 day
		}

		switch($orderby){
			case "score":
				$posts = $this->array_orderby($posts, "score", SORT_DESC, "follows_by_count", SORT_DESC);
			break;
			case "likes":
				$posts = $this->array_orderby($posts, "likes", SORT_DESC);
			break;
			case "oldest":
				$posts = $this->array_orderby($posts, "created", SORT_ASC);
			break;
			case "latest":
			default:
				$posts = $this->array_orderby($posts, "created", SORT_DESC);
			break;
		}

		//pagination
		$totalsize = sizeof($posts);
		$totalpage = ceil(sizeof($posts) / $pagesize);
		if($page > $totalpage || $page < 1){
			$page = 1;
		}
		$data = array_slice($posts, ($page - 1)*$pagesize, $pagesize);

		if(sizeof($current_user) && $current_user->ID){
			//check bookmark
			$bookmark = $this->getBookmarkStory();
			$filterIDs = $bookmark->filterBookmarkIDs($current_user->ID, $data);

			foreach($data as $key=>$value){
				if(in_array($value["id"], $filterIDs)){
					$data[$key]["bookmark"] = 1;
				}else{
					$data[$key]["bookmark"] = 0;
				}
			}
		}else{
			foreach($data as $key=>$value){
				$data[$key]["bookmark"] = 0;
			}
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>$pagesize
		);
	}

	public function getCreatorSingle($userid){
		global $wpdb;

		$query = "SELECT b.ID as `userid`, c.igusername, c.verified as `verified`, c.name, REPLACE(e.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `image_url` FROM `wp_igaccounts` a LEFT JOIN `wp_users` b ON a.userid = b.ID LEFT JOIN `wp_pods_instagrammer_fast` c ON a.igusername = c.igusername LEFT JOIN ( SELECT meta_value, user_id FROM `wp_usermeta` WHERE meta_key = %s) d ON b.ID = d.user_id LEFT JOIN `wp_posts` e ON d.meta_value = e.ID WHERE c.id = %d";

		return $wpdb->get_row($wpdb->prepare($query, 'profile_pic', $userid), ARRAY_A);
	}

	public function getCreator($searchname){
		global $wpdb, $current_user;

		/*
		$query = "SELECT * FROM ( SELECT a.userid as `userid`, b.name as `name`, b.igusername as `igusername`, b.verified as `verified`,REPLACE(d.guid, 'https://storify.me','https://cdn.storify.me') as `image_url`, ( CASE WHEN g.item_id IS NULL THEN 0 ELSE 1 END ) as `bookmark_item_id` FROM `wp_igaccounts` a LEFT JOIN `wp_pods_instagrammer_fast` b ON a.igusername = b.igusername LEFT JOIN `wp_podsrel` c ON ( b.id = c.item_id AND c.field_id = %d ) LEFT JOIN `wp_posts` d ON c.related_item_id = d.ID LEFT JOIN ( SELECT item_id FROM `wp_bookmark_people` WHERE userid = %d ) g ON b.id = g.item_id ) h WHERE LOWER(name) LIKE '%s' OR LOWER(igusername) LIKE '%s' ORDER BY bookmark_item_id DESC, name ASC";

		return $wpdb->get_results($wpdb->prepare($query, 43, $current_user->ID, "%".strtolower($searchname)."%","%".strtolower($searchname)."%"), ARRAY_A);
		*/
		$query = "SELECT * FROM ( SELECT b.ID as `userid`, c.igusername, c.verified as `verified`, c.name, REPLACE(e.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `image_url`, ( CASE WHEN g.item_id IS NULL THEN 0 ELSE 1 END ) as `bookmark` FROM `wp_igaccounts` a LEFT JOIN `wp_users` b ON a.userid = b.ID LEFT JOIN `wp_pods_instagrammer_fast` c ON a.igusername = c.igusername LEFT JOIN ( SELECT meta_value, user_id FROM `wp_usermeta` WHERE meta_key = %s) d ON b.ID = d.user_id LEFT JOIN `wp_posts` e ON d.meta_value = e.ID LEFT JOIN ( SELECT item_id FROM `wp_bookmark_people` WHERE userid = %d ) g ON b.ID = g.item_id ) h WHERE LOWER(name) LIKE %s OR LOWER(igusername) LIKE %s ORDER BY bookmark DESC, name ASC";

		return $wpdb->get_results($wpdb->prepare($query, 'profile_pic', $current_user->ID, "%".strtolower($searchname)."%","%".strtolower($searchname)."%"), ARRAY_A);
	}

	function getIger($categories, $countries, $languages, $pagesize=24, $page=1, $orderby="", $forceRefresh=false){
		global $current_user;
		if(sizeof($categories) || sizeof($countries) || sizeof($languages)){
			//change all the int
			if(sizeof($categories)){
				foreach($categories as $key=>$value){
					$categories[$key] = (int)$value;
				}
			}else{
				$categories = null;				
			}
			if(sizeof($countries)){
				foreach($countries as $key=>$value){
					$countries[$key] = (int)$value;
				}
			}else{
				$countries = null;				
			}
			if(sizeof($languages)){
				foreach($languages as $key=>$value){
					$languages[$key] = (int)$value;
				}
			}else{
				$languages = null;				
			}
			$searchMD5 = md5(json_encode(array($categories, $countries, $languages)));
		}else{
			$searchMD5 = "null";
		}
		$igers = $this->getCache()->get("nc_".$searchMD5);
		if($forceRefresh || !$igers || !$this->cache_enable){
			//no record, get Data
			
			$categoriesCause = array();
			if(isset($categories)){
			    if(sizeof($categories)){
			        foreach($categories as $key=>$value){
			            $categoriesCause[] = "instagrammer_tag.term_id = '".$value."'";
			        }
			    }
			}

			$countriesCause = array();
			if(isset($countries)){
			    if(sizeof($countries)){
			        foreach($countries as $key=>$value){
			            $countriesCause[] = "instagrammer_country.term_id = '".$value."'";
			        }
			    }
			}

			$languagesCause = array();
			if(isset($languages)){
			    if(sizeof($languages)){
			        foreach($languages as $key=>$value){
			            $languagesCause[] = "instagrammer_language.term_id = '".$value."'";
			        }
			    }
			}

			$query = array(
				"limit"=>-1
			);

			$wherecauseAr = array("t.hidden <> 1");

			if(sizeof($categoriesCause)){
				$wherecauseAr[] = "(".implode(" OR ", $categoriesCause).")";
			}
			if(sizeof($countriesCause)){
				$wherecauseAr[] = "(".implode(" OR ", $countriesCause).")";
			}
			if(sizeof($languagesCause)){
				$wherecauseAr[] = "(".implode(" OR ", $languagesCause).")";
			}
			
			if($wherecauseAr){
				$query["where"] = implode(" AND ", $wherecauseAr);
			}

			$igers_pods = pods("instagrammer_fast", $query);
			$igers = array();
			if(0 < $igers_pods->total()){
				while($igers_pods->fetch()){
					$tempobj = array(
		                "id"=>$igers_pods->field('id'),
		                "name"=>$igers_pods->field('name'),
		                "ig_id"=>$igers_pods->field('ig_id'),
		                "igusername"=>$igers_pods->field('igusername'),
		                "org_profile_pic"=>pods_image_url($igers_pods->field('ig_profile_pic')["ID"], 'large'),
		                "hr_image"=>pods_image_url($igers_pods->field('display_image')["ID"], NULL),
		                "ig_profile_pic"=>pods_image_url($igers_pods->field('display_image')["ID"], 'large'),
		                "biography"=>$igers_pods->field('biography'),
		                "media_count"=>$igers_pods->field('media_count'),
		                "follows_by_count"=>$igers_pods->field('follows_by_count'),
		                "external_url"=>$igers_pods->field('external_url'),
		                "instagrammer_tag"=>$igers_pods->field('instagrammer_tag'),
		                "instagrammer_country"=>$igers_pods->field('instagrammer_country'),
		                "instagrammer_language"=>$igers_pods->field('instagrammer_language'),
		                "average_likes"=>$igers_pods->field('average_likes'),
		                "average_comments"=>$igers_pods->field('average_comments'),
		                "created"=>strtotime($igers_pods->field('created')),
	                	"modified"=>date('j M y H:i',strtotime($igers_pods->field('modified'))),
	                	"unformatted_modified"=>strtotime($igers_pods->field('modified')),
	                	"verified"=>$igers_pods->field('verified')
		            );
		            $igers[] = $tempobj;
				}
			}

			//do calculation 
			foreach($igers as $key=>$value){
				$languages_count = 0.7;
				$countries_count = 0.5;
				$tag_count = 0.6;

				$score = 0;

				if(sizeof($value["instagrammer_tag"]) && sizeof($categories)){
					foreach($value["instagrammer_tag"] as $key3=>$value3){
						if(in_array($value3["term_id"], $categories)){
							$score += $tag_count;
						}
					}
				}

				if(sizeof($value["instagrammer_country"]) && sizeof($countries)){
					foreach($value["instagrammer_country"] as $key3=>$value3){
						if(in_array($value3["term_id"], $countries)){
							$score += $countries_count;
						}
					}
				}

				if(sizeof($value["instagrammer_language"]) && sizeof($languages)){
					foreach($value["instagrammer_language"] as $key3=>$value3){
						if(in_array($value3["term_id"], $languages)){
							$score += $languages_count;
						}
					}
				}

				$igers[$key]["score"] = $score;
			}

			$this->getCache()->set("nc_".$searchMD5, $igers, $this->cache_duration); //1 day
		}

		switch($orderby){
			case "score":
				$igers = $this->array_orderby($igers, "score", SORT_DESC, "follows_by_count", SORT_DESC);
			break;
			case "likes":
				$igers = $this->array_orderby($igers, "follows_by_count", SORT_DESC);
			break;
			case "verified":
				$igers = $this->array_orderby($igers, "verified", SORT_DESC);
			break;
			case "latest":
			default:
				$igers = $this->array_orderby($igers, "unformatted_modified", SORT_DESC);
			break;
		}

		//pagination
		$totalsize = sizeof($igers);
		$totalpage = ceil(sizeof($igers) / $pagesize);
		if($page > $totalpage || $page < 1){
			$page = 1;
		}
		$data = array_slice($igers, ($page - 1)*$pagesize, $pagesize);

		if($current_user && sizeof($current_user) && $current_user->ID){
			//check bookmark
			$bookmark = $this->getBookmarkPeople();
			$filterIDs = $bookmark->filterBookmarkIDs($current_user->ID, $data);

			foreach($data as $key=>$value){
				if(in_array($value["id"], $filterIDs)){
					$data[$key]["bookmark"] = 1;
				}else{
					$data[$key]["bookmark"] = 0;
				}
			}
		}else{
			foreach($data as $key=>$value){
				$data[$key]["bookmark"] = 0;
			}
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>$pagesize,
			"cause"=>array(
				"category"=>$categories,
				"country"=>$countries,
				"language"=>$languages,
				"order"=>$orderby
			)
		);
	}

	function isBrandVerified($user_id){
		global $current_user;
		return get_user_meta($current_user->ID, "brand_verified", true);
	}

	/**
	 * Changing default role
	 */
	function changeDefaultRole($default_role, $user_id){
		global $wpdb;
		if($default_role == "brand"){
			//if requesting to change as brand, check if user already brand verified
			
			if(!get_user_meta($user_id, "brand_verified", true)){
				$this->requestBrandVerified($user_id);
			}
			//change default role
			update_user_meta($user_id, "default_role", "brand");
			$_SESSION["role_view"] = "brand";
		}else{
			update_user_meta($user_id, "default_role", "creator");
			$_SESSION["role_view"] = "creator";
		}
	}

	function requestBrandVerified($user_id){
		global $wpdb;

		//check is user_id in table
		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."brand_verify_request` WHERE user_id = %d";
		if(!$wpdb->get_var($wpdb->prepare($query, $user_id))){
			//not exist, add
			$query = "INSERT INTO `".$wpdb->prefix."brand_verify_request` ( user_id ) VALUES ( %d )";
			$wpdb->query($wpdb->prepare($query, $user_id));
		}
	}

	function removeBrandVerifiedRequset($user_id){
		global $wpdb;

		$query = "DELECT FROM `".$wpdb->prefix."brand_verify_request` WHERE user_id = %d";
		$wpdb->query($wpdb->prepare($query, $user_id));
	}

	function array_orderby()
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

	function createGridItem($item){

		if(sizeof($item["instagrammer_tag"])){
			$ig_tags_html = '<span class="tag_group">';
			foreach($item["instagrammer_tag"] as $key=>$value){
				$ig_tags_html .= '<a href="listing?category%5B%5D='.$value["term_id"].'" class="tag category">'.$value["name"].'</a>';
			}
			$ig_tags_html .= "</span>";
		}else{
			$ig_tags_html = "";
		}

		$iger_html = '<a href="/'.$item["igusername"].'/" class="title">'.$item["igusername"].'</a>';

		/*
		if(sizeof($item["instagrammer_language"])){
			$ig_language_html = "";
			foreach($item["instagrammer_language"] as $key=>$value){
				$ig_language_html .= '<span class="tag">'.$value["name"].'</span>';
			}
		}else{
			$ig_language_html = "";
		}
		*/
		$ig_language_html = '<span class="tag"><i class="fa fa-clock-o"></i>'.$item["modified"].'</span>';

		if(sizeof($item["instagrammer_country"])){
			$ig_country_html = '<h4 class="location">';
			foreach($item["instagrammer_country"] as $key=>$value){
				$ig_country_html .= '<a href="listing?country%5B%5D='.$value["term_id"].'">'.$value["name"].'</a>';
			}
			$ig_country_html .= '</h4>';
		}else{
			$ig_country_html = '';
		}

		if($item["average_likes"] > 0){
			$ig_country_html .= '<div class="price"><span class="appendix">Average</span><i class="fa fa-heart"></i>'.number_format($item["average_likes"], 0).'</div>';
		}
		?>
		<div class="item scrollreveal">
            <div class="wrapper">
                <div class="image">
                    <h3>
                        <?=$ig_tags_html?>
                        <?=$iger_html?>
                        <?=$ig_language_html?>
                    </h3>
                    <a href="/<?=$item["igusername"]?>/" class="image-wrapper background-image">
                        <img src="<?=$item["ig_profile_pic"]?>" alt="">
                    </a>
                </div>
                <!--end image-->
                <?=$ig_country_html?>
                <div class="meta">
                    <figure>
                        <i class="fa fa-users"></i><?=number_format($item["follows_by_count"])?>
                    </figure>
                    <figure>
                        <i class="fa fa-image"></i><?=number_format($item["media_count"])?>
                    </figure>
                </div>
                <!--end meta-->
                <div class="description">
                    <p><?=$item["biography"]?>
                    </p>
                    <?php
                        if(isset($item["external_url"]) && $item["external_url"]){
                        	/*
                    ?>
                    <a href="<?=$item["external_url"]?>"><?=$item["external_url"]?></a>
                    <?php     */   
                        }
                    ?>
                </div>
                <!--end description-->
                <a href="/<?=$item["igusername"]?>/" class="detail text-caps underline">Details</a>
            </div>
        </div>
		<?php
	}

	function createPageItem($pagenumber, $cause, $isCurrentPage, $special=NULL){
		//create url first
		$causeItems = array();
		foreach($cause as $key=>$value){
			if($key !== "order"){
				if(sizeof($value)){
					//got items
					foreach($value as $key2=>$value2){
						$causeItems[] = $key.'%5B%5D='.$value2;
					}
				}
			}else{
				$causeItems[] = "order=".$value;
			}
		}

		if(sizeof($causeItems)){
			$url = "listing?".implode("&", $causeItems)."&page=";
		}else{
			$url = "listing?page=";
		}

		if($special){
			//so either is previous or next
			if($special == "previous"){
				?>
				<li class="page-item">
					<a class="page-link" href="<?=$url.$pagenumber?>" aria-label="Previous">
                        <span aria-hidden="true">
                            <i class="fa fa-chevron-left"></i>
                        </span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
				<?php
			}else{
				?>
				<li class="page-item">
                    <a class="page-link" href="<?=$url.$pagenumber?>" aria-label="Next">
                        <span aria-hidden="true">
                            <i class="fa fa-chevron-right"></i>
                        </span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
				<?php
			}
		}else{
			//is number
			?>
			<li class="page-item<?=$isCurrentPage?" active":""?>">
                <a class="page-link" href="<?=$url.$pagenumber?>"><?=$pagenumber?></a>
            </li>
			<?php
		}
	}

	function createPagination($current_page, $max_page, $cause){
		if($max_page > 1){
			echo '<div class="page-pagination">';
			echo '<nav aria-label="Pagination">';
			echo '<ul class="pagination">';

			if($current_page != 1){
				$this->createPageItem($current_page-1, $cause, false, 'previous');
			}

			$span = 2;

			$start_page_number = ( $current_page <= $span ) ? 1 : ( ( $max_page - $current_page ) > $span ? $current_page - $span : $max_page - 2*$span);
			$start_page_number = $start_page_number < 1 ? 1 : $start_page_number;
			$end_page_number = min($start_page_number+ 2*$span, $max_page);
			for( $i = $start_page_number; $i < $end_page_number+1; $i++){
				$this->createPageItem($i, $cause, $i == $current_page, NULL);
			}

			if($current_page != $max_page){
				$this->createPageItem($current_page+1, $cause, false, 'next');
			}

			echo '</ul></nav></div>';
		}else{

		}
	}

	function getS3UploadPresignedLink($filekey, $filemime){
		$s3 = new \Aws\S3\S3Client(array(
			"region"=>"ap-southeast-1",
			"version"=>"latest",
			"credentials"=>array(
				"key"=>AS3CF_AWS_ACCESS_KEY_ID,
				"secret"=>AS3CF_AWS_SECRET_ACCESS_KEY
			)
		));

		try{
			$cmd = $s3->getCommand(
				'putObject',
				array(
					'Bucket' => 'ncstorifymeprivate',
					"Key" => $filekey,
					"ContentType" => $filemime
				)
			);

			$request = $s3->createPresignedRequest($cmd, "+10minutes");

			$url = (string)$request->getUri();

			return array(
				"error"=>0,
				"url"=>$url,
				"key"=>$filekey
			);
		}catch( Exception $e ){

			return array(
				"error"=>1,
				"msg"=>$e->getMessage()
			);
		}
	}

	function getS3presignedLink($filekey){
		$s3 = new \Aws\S3\S3Client(array(
			"region"=>"ap-southeast-1",
			"version"=>"latest",
			"credentials"=>array(
				"key"=>AS3CF_AWS_ACCESS_KEY_ID,
				"secret"=>AS3CF_AWS_SECRET_ACCESS_KEY
			)
		));

		try{
			$cmd = $s3->getCommand(
				'GetObject',
				array(
					"Bucket" => 'ncstorifymeprivate',
					"Key" => $filekey
				)
			);

			$request = $s3->createPresignedRequest($cmd, '+20minutes');

			$url = (string)$request->getUri();

			return array(
				"error"=>0,
				"url"=>$url
			);
		}catch( Exception $e ){

			return array(
				"error"=>1,
				"msg"=>$e->getMessage()
			);
		}
	}
}