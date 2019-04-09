<?php
namespace storify;

class bookmark{
	private $main_tbl;
	private $group_tbl;
	private $item_tbl;

	private function getCache(){
		if(!$this->cache){
			$this->cache = new memcached;
			$this->cache->addServer('localhost', 11211) or die ("Could not connect");
		}
		return $this->cache;
	}

	function __construct($type){
		global $wpdb;
		switch($type){
			case "story":
				$this->main_tbl = $wpdb->prefix."bookmark_story";
				$this->group_tbl = $wpdb->prefix."bookmark_group_story";
				$this->item_tbl = $wpdb->prefix."bookmark_item_story";
			break;
			case "people":
				$this->main_tbl = $wpdb->prefix."bookmark_people";
				$this->group_tbl = $wpdb->prefix."bookmark_group_people";
				$this->item_tbl = $wpdb->prefix."bookmark_item_people";
			break;
		}
	}

	public function filterBookmarkIDs($userid, $data){
		global $wpdb;
		//gather ids from data
		$ids = array();
		$filter_ids = array();
		if(sizeof($data)){
			foreach($data as $key=>$value){
				$ids[] = $value["id"];
			}
			$table = $this->main_tbl;
			$query = "SELECT item_id FROM `".$table."` WHERE userid = %d AND item_id IN (".implode(",",$ids).")";
			$filter_ids = $wpdb->get_col($wpdb->prepare($query, $userid));
		}
		return $filter_ids;
	}

	public function check($userid, $item_id){
		global $wpdb;
		$table = $this->main_tbl;
		$query = "SELECT COUNT(*) FROM `".$table."` WHERE userid = %d AND item_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $userid, $item_id));
	}

	public function remove($userid, $item_id){
		global $wpdb;
		$table = $this->main_tbl;
		$query = "DELETE FROM `".$table."` WHERE userid = %d AND item_id = %d";
		$wpdb->query($wpdb->prepare($query, $userid, $item_id)); 
	}

	public function add($userid, $item_id){
		global $wpdb;
		$table = $this->main_tbl;
		$query = "SELECT id FROM `".$table."` WHERE userid = %d AND item_id = %d";
		$bookmark_id = $wpdb->get_var($wpdb->prepare($query, $userid, $item_id));
		if($bookmark_id){
			return $bookmarkid;
		}else{
			$wpdb->insert(
				$table,
				array(
					'userid'=>$userid,
					'item_id'=>$item_id
				),
				array(
					'%d',
					'%d'
				)
			);
			return $wpdb->insert_id;
		}
	}

	public function get($userid, $exclude_ids = ""){
		global $wpdb;
		$table = $this->main_tbl;

		if($exclude_ids){
			$query = "SELECT item_id, tt FROM `".$table."` WHERE userid = %d AND item_id NOT IN (".$exclude_ids.")";
			return $wpdb->get_results($wpdb->prepare($query, $userid), ARRAY_A);
		}else{
			$query = "SELECT item_id, tt FROM `".$table."` WHERE userid = %d";
			return $wpdb->get_results($wpdb->prepare($query, $userid), ARRAY_A);
		}
	}

	public function addGroup($name, $userid){
		global $wpdb;
		$table = $this->group_tbl;
		$wpdb->insert(
			$table,
			array(
				'name'=>$name,
				'userid'=>$userid
			),
			array(
				'%s',
				'%d'
			)
		);
		$groupid = $wpdb->insert_id;
		$this->updateGroupTime($groupid);
		return $groupid;
	}

	public function editGroup($name, $groupid){
		global $wpdb;
		$table = $this->group_tbl;
		$wpdb->update(
			$table,
			array(
				'name'=>$name
			),
			array( 'id' => $groupid ),
			array( '%s' ),
			array( '%d' )
		);
		$this->updateGroupTime($groupid);
	}

	public function deleteGroup($groupid){
		global $wpdb;
		$table = $this->group_tbl;
		$wpdb->delete(
			$table,
			array( 'id' => $groupid ),
			array( '%d' )
		);

		//and also delete all item in group
		$table = $this->item_tbl;
		$wpdb->delete(
			$table,
			array( 'groupid' => $groupid ),
			array( '%d' )
		);
	}

	public function getGroup($userid){
		global $wpdb;
		$table = $this->group_tbl;
		$query = "SELECT name, id, CONVERT_TZ(last_update, \"+00:00\", \"+08:00\") as `last_update` FROM `".$table."` WHERE userid = %d";
		return $wpdb->get_results($wpdb->prepare($query, $userid), ARRAY_A);
	}

	public function getGroupDetail($groupid){
		global $wpdb;
		$table = $this->group_tbl;
		$query = "SELECT name, id, CONVERT_TZ(tt, \"+00:00\", \"+08:00\") as `tt`, CONVERT_TZ(last_update, \"+00:00\", \"+08:00\") as `last_update` FROM `".$table."` WHERE id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $groupid), ARRAY_A);
	}

	public function updateGroupTime($groupid){
		global $wpdb;
		$table = $this->group_tbl;
		$query = "UPDATE `".$table."` SET last_update = NOW() WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $groupid));
	}

	public function checkGroupOwnerShip($groupid, $userid){
		global $wpdb;
		$table = $this->group_tbl;
		$query = "SELECT COUNT(*) FROM `".$table."` WHERE id = %d AND userid = %d";
		return $wpdb->get_var($wpdb->prepare($query, $groupid, $userid));
	}

	public function addItemToGroup($item_id, $groupid){
		global $wpdb;
		$table = $this->item_tbl;
		$query = "SELECT id FROM `".$table."` WHERE item_id = %d AND groupid = %d";
		$group_item_id = $wpdb->get_var($wpdb->prepare($query, $item_id, $groupid));
		if(!$group_item_id){
			//item not exist
			$wpdb->insert(
				$table,
				array(
					"item_id"=>$item_id,
					"groupid"=>$groupid,
					"sort_index"=>0
				),
				array(
					'%d',
					'%d'
				)
			);
			$group_item_id = $wpdb->insert_id;
		}else{
			//item exist, do nothing
		}

		$this->moveItemToLast($group_item_id);
		$this->updateGroupTime($groupid);
		return $group_item_id;
	}

	public function checkGroupItemOwnerShip($item_id, $userid){
		global $wpdb;
		$table = $this->item_tbl;
		$query = "SELECT groupid FROM `".$table."` WHERE id = %d";
		$groupid = $wpdb->get_var($wpdb->prepare($query, $item_id));
		if($groupid){
			return $this->checkGroupOwnerShip($groupid, $userid);
		}
		return false;
	}

	public function removeItemFromGroup($group_item_id){
		global $wpdb;
		$table = $this->item_tbl;
		
		//move item to last before remove
		$this->moveItemToLast($group_item_id);
		//remove item from group
		$wpdb->delete(
			$table,
			array( 'id' => $group_item_id ),
			array( '%d' )
		);
	}

	public function moveItemToLast($group_item_id){
		global $wpdb;
		$table = $this->item_tbl;
		$query = "SELECT MAX(sort_index) FROM `".$table."` WHERE groupid = ( SELECT groupid FROM `".$table."` WHERE id = %d )";
		$max_sort_index = (int) $wpdb->get_var($wpdb->prepare($query, $group_item_id));
		$max_sort_index = $max_sort_index ? $max_sort_index + 1 : 1;
		$this->moveItemTo($group_item_id, $max_sort_index);
	}

	public function moveItemTo($group_item_id, $target_index){
		global $wpdb;
		$table = $this->item_tbl;
		$query = "SELECT sort_index FROM `".$table."` WHERE id = %d";
		$original_index = $wpdb->get_var($wpdb->prepare($query, $group_item_id));
		$query = "SELECT groupid FROM `".$table."` WHERE id = %d";
		$group_id = $wpdb->get_var($wpdb->prepare($query, $group_item_id));
		if($original_index){
			if($original_index > $target_index){
				$query = "UPDATE `".$table."` SET sort_index = sort_index + 1 WHERE groupid = %d AND sort_index >= %d AND sort_index < %d";
				$wpdb->query($wpdb->prepare($query, $group_id, $target_index, $original_index));
			}else if($original_index < $target_index){
				$query = "UPDATE `".$table."` SET sort_index = sort_index - 1 WHERE groupid = %d AND sort_index <= %d AND sort_index > %d";
				$wpdb->query($wpdb->prepare($query, $group_id, $target_index, $original_index));
			}else{

			}
		}
		$query = "UPDATE `".$table."` SET sort_index = %d WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $target_index, $group_item_id));
		
		$this->updateGroupTime($groupid);
	}

	public function getGroupItem($groupid, $amount=-1){
		global $wpdb;
		$table = $this->item_tbl;
		if($amount == -1){ //all
			$query = "SELECT id, item_id, sort_index, CONVERT_TZ(tt, \"+00:00\", \"+08:00\") as `tt` FROM `".$table."` WHERE groupid = %d ORDER BY sort_index ASC";
			return $wpdb->get_results($wpdb->prepare($query, $groupid), ARRAY_A);
		}else{
			$query = "SELECT id, item_id, sort_index, CONVERT_TZ(tt, \"+00:00\", \"+08:00\") as `tt` FROM `".$table."` WHERE groupid = %d ORDER BY sort_index ASC LIMIT %d";
			return $wpdb->get_results($wpdb->prepare($query, $groupid, $amount), ARRAY_A);
		}
	}

	public function getSummary($userid, $folder_id = 0){
		global $wpdb;
		$bookmark_table = $this->main_tbl;
		$group_table = $this->group_tbl;
		$item_table = $this->item_tbl;
		if($folder_id > 0 ){
			//folder id provided, only interest on the requeted folder
			$total_folder = 1;
			$query = "SELECT item_id FROM `".$item_table."` WHERE groupid = %d";
			$items = $wpdb->get_results($wpdb->prepare($query, $userid), ARRAY_A);
			$total_items = sizeof($items);
		}else{
			//bookmark folder
			if($folder_id == 0){
				//no folder selected, all 
				$query = "SELECT COUNT(*) FROM `".$group_table."` WHERE userid = %d";
				$total_folder = $wpdb->get_var($wpdb->prepare($query, $userid)); // not include 'all' folder

				$query = "SELECT item_id FROM ( SELECT item_id FROM `".$bookmark_table."` WHERE userid = %d UNION SELECT b.item_id FROM `".$group_table."` a LEFT JOIN `".$item_table."` b ON a.id = b.groupid WHERE a.userid = %d AND b.item_id IS NOT NULL ) f"; // but item include all folder
				$group_items = $wpdb->get_results($wpdb->prepare($query, $userid, $userid), ARRAY_A);
				$total_items = sizeof($group_items);

			}else if($folder_id == -1){
				// only bookmark
				$query = "SELECT item_id FROM `".$bookmark_table."` WHERE userid = %d";
				$items = $wpdb->get_results($wpdb->prepare($query,$userid), ARRAY_A);
				$total_items = sizeof($items);
				$total_folder = 1;				
			}else { //$folder_id = -2
				//every boards except 'all' folder
				
				//no folder selected, all 
				$query = "SELECT COUNT(*) FROM `".$group_table."` WHERE userid = %d";
				$total_folder = $wpdb->get_var($wpdb->prepare($query, $userid));

				$query = "SELECT item_id FROM `".$group_table."` a LEFT JOIN `".$item_table."` b ON a.id = b.groupid WHERE a.userid = %d AND b.item_id IS NOT NULL";
				$group_items = $wpdb->get_results($wpdb->prepare($query, $userid), ARRAY_A);
				$total_items = sizeof($group_items);
			}
		}

		return array(
			"no_folder"=>$total_folder,
			"no_items"=>$total_items
		);
	}
}
