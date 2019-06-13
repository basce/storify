<?php
namespace storify;

include_once(__DIR__."/deliverable.php");
include_once(__DIR__."/detail.php");
include_once(__DIR__."/draft.php");
include_once(__DIR__."/invitation.php");
include_once(__DIR__."/submission.php");
include_once(__DIR__."/brand.php");
include_once(__DIR__."/location.php");
include_once(__DIR__."/sample.php");
include_once(__DIR__."/summary.php");
include_once(__DIR__."/tag.php");
include_once(__DIR__."/user.php");
include_once(__DIR__."/status.php");

use storify\project\deliverable as deliverable;
use storify\project\detail as detail;
use storify\project\draft as draft;
use storify\project\invitation as invitation;
use storify\project\submission as submission;
use storify\project\brand as brand;
use storify\project\location as location;
use storify\project\sample as sample;
use storify\project\summary as summary;
use storify\project\tag as tag;
use storify\project\user as user;
use storify\project\status as status;

class project{

	private $tbl_project;

	private $deliverable_manager = null;
	private $detail_manager = null;
	private $draft_manager = null;
	private $invitation_manager = null;
	private $submission_manager = null;
	private $location_manager = null;
	private $brand_manager = null;
	private $sample_manager = null;
	private $summary_manager = null;
	private $tag_manager = null;
	private $user_manager = null;
	private $status_manager = null;

	function __construct(){
		global $wpdb;

		$this->tbl_project = $wpdb->prefix."project";

		$this->deliverable_manager = new deliverable();
		$this->detail_manager = new detail();
		$this->draft_manager = new draft();
		$this->invitation_manager = new invitation();
		$this->submission_manager = new submission();
		$this->location_manager = new location();
		$this->brand_manager = new brand();
		$this->sample_manager = new sample();
		$this->summary_manager = new summary();
		$this->tag_manager = new tag();
		$this->user_manager = new user();
		$this->status_manager = new status();
	}  

	public function createNewProject($name, $userid, $groupid = 0){
		global $wpdb;

		$query = "INSERT INTO `".$this->tbl_project."` ( name, created_by, group_id, status ) VALUES ( %s, %d, %d, %s )";
		$wpdb->query($wpdb->prepare($query, $name, $userid, $groupid, "new"));

		$project_id = $wpdb->insert_id;

		$this->user_manager->addUser($userid, $project_id, "admin");

		return $project_id;
	}

	public function getUTCStringFromLocal($datetimeStr, $hours){
		return date("Y-m-d H:i:s", strtotime($datetimeStr) - $hours*3600);
	}

	public function edit_save($project_id, $data){
		global $wpdb;

		//update project name, and closing date
		$query = "UPDATE `".$this->tbl_project."` SET name = %s, closing_date = %s, invitation_closing_date = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $data["detail"]["name"], $this->getUTCStringFromLocal($data["detail"]["closing_date"], 8), $this->getUTCStringFromLocal($data["detail"]["invitation_closing_date"], 8), $project_id));

		//save detail
		$this->detail_manager->save($project_id, $data["detail"]);

		//update brand
		$this->brand_manager->updateBrand($data["brand"], $project_id);

		//update location
		$this->location_manager->updateLocation($data["location"], $project_id);

		//update tags ( categories )
		$this->tag_manager->updateTag($data["tag"], $project_id);

		//generate summary json and save
		$summary_json = $this->generateSummaryJson($project_id);
		$this->summary_manager->updateSummary($summary_json, $project_id);

		//get brandname
		$query = "SELECT b.name FROM `".$this->brand_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
		$brand = $wpdb->get_col($wpdb->prepare($query, $project_id));

		if(sizeof($brand)){
			$brandname = implode(",", $brand);
		
			$query = "UPDATE `".$this->tbl_project."` SET brand_name = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $brandname, $project_id));
		}
	}

	public function updateSummary($project_id){
		//generate summary json and save
		$summary_json = $this->generateSummaryJson($project_id);
		$this->summary_manager->updateSummary($summary_json, $project_id);
	}

	public function save($project_id, $data){
		global $wpdb;
		//update project name, and closing date
		$query = "UPDATE `".$this->tbl_project."` SET name = %s, closing_date = %s, invitation_closing_date = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, $data["detail"]["name"], $this->getUTCStringFromLocal($data["detail"]["closing_date"],8), $this->getUTCStringFromLocal($data["detail"]["invitation_closing_date"],8), $project_id));

		//save detail
		$this->detail_manager->save($project_id, $data["detail"]);

		//update deliverable
		$this->deliverable_manager->setupDeliverable($project_id, $data["detail"]["no_of_photo"], $data["detail"]["no_of_video"], NULL /*$data["deliverable"]*/);

		//update deliverable details, if any
		if(isset($data["deliverable"]) && sizeof($data["deliverable"])){
			//get deliverables list for id
			$current_deliverable = $this->deliverable_manager->getDeliverables($project_id);

			$structure_data = array();
			foreach($data["deliverable"] as $key=>$value){
				$structure_data[] = array(
					"id"=>$current_deliverable[$key],
					"remark"=>$value["remark"]
				);
			}
		}
		//update brand
		if(isset($data["brand"])){
			$this->brand_manager->updateBrand($data["brand"], $project_id);
		}

		//update location
		if(isset($data["location"])){
			$this->location_manager->updateLocation($data["location"], $project_id);
		}

		//update tags ( categories )
		if(isset($data["tag"])){
			$this->tag_manager->updateTag($data["tag"], $project_id);
		}

		//generate summary json and save
		$summary_json = $this->generateSummaryJson($project_id);
		$this->summary_manager->updateSummary($summary_json, $project_id);

		$available_check = 0;
		//check id deliverables is set
		if($data["detail"]["no_of_photo"] || $data["detail"]["no_of_video"]){
			$available_check++;
		}else{
			//no available
		}

		//cost check
		if($data["detail"]["cost_per_photo"] || $data["detail"]["cost_per_video"] || $data["detail"]["reward_name"]){
			$available_check++;
		}else{

		}

		if($available_check == 2){
			//if required data is sufficient, change project status, and calculate require saving data.
			
			//calculate bounty value
			$total_value = $data["detail"]["no_of_photo"] * $data["detail"]["cost_per_photo"] + $data["detail"]["no_of_video"] * $data["detail"]["cost_per_video"];
			
			//calculate item needed
			$total_creatives = $data["detail"]["no_of_photo"] + $data["detail"]["no_of_video"];
			
			//get brandname
			$query = "SELECT b.name FROM `".$this->brand_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
			$brand = $wpdb->get_col($wpdb->prepare($query, $project_id));

			$brandname = implode(",", $brand);
			
			$query = "UPDATE `".$this->tbl_project."` SET bounty = %d, brand_name = %s, number_creatives = %d, hide = %d, status = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, $total_value, $brandname, $total_creatives, 0, "open", $project_id));
		}
	}

	public function generateSummaryJson($project_id){
		global $wpdb;
		$details = $this->detail_manager->getDetail($project_id);

		//get short description
		if(isset($details["short_description"]) && $details["short_description"]){
			$description = $details["short_description"];
		}else{
			$description = wp_trim_words($details["description_brief"]);
		}

		//get location 
		$query = "SELECT b.term_id, b.name FROM `".$this->location_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
		$location = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		//get brand
		$query = "SELECT b.term_id, b.name FROM `".$this->brand_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
		$brand = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		//get tag
		$query = "SELECT b.term_id, b.name FROM `".$this->tag_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
		$tag = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

		//get invitation
		$invitation = $this->invitation_manager->getInvitationStats($project_id);

		//get bounty
		if($details["bounty_type"] == "cash"){
			$photo_cost = $details["no_of_photo"] * $details["cost_per_photo"];
			$video_cost = $details["no_of_video"] * $details["cost_per_video"];
			
			$bounty = array(
				array(
					"type"=>"cash",
					"value"=>$photo_cost + $video_cost
				)
			);
		}else if($details["bounty_type"] == "gift"){
			$bounty = array(
				array(
					"type"=>"gift",
					"value"=>$details["reward_name"]
				)
			);
		}else{
			//both
			$photo_cost = $details["no_of_photo"] * $details["cost_per_photo"];
			$video_cost = $details["no_of_video"] * $details["cost_per_video"];
			
			$bounty = array(
				array(
					"type"=>"cash",
					"value"=>$photo_cost + $video_cost
				),
				array(
					"type"=>"gift",
					"value"=>$details["reward_name"]
				)	
			);
		}

		$deliverables_text = "";
		$deliverables = array();
		if((int)$details["no_of_photo"]){
			$deliverables[] = array(
				"type"=>"photo",
				"amount"=>$details["no_of_photo"]
			);
			$deliverables_text = $details["no_of_photo"] == 1 ? "1 photo":$details["no_of_photo"]." photos";
		}
		if((int)$details["no_of_video"]){
			$deliverables[] = array(
				"type"=>"video",
				"amount"=>$details["no_of_video"]
			);
			if($deliverables_text){
				$deliverables_text .= " and ";
			}
			$deliverables_text .= $details["no_of_video"] == 1 ? "1 video":$details["no_of_video"]." videos";
		}

		//get display_image
		$samples = $this->sample_manager->getSample($project_id);
		if(sizeof($samples)){
			$display_image = $samples[0]["URL"];
		}else{
			$display_image = "";
		}

		$summary = array(
			"name"=>$details["name"],
			"description"=>$description,
			"invitation_closing_date"=>$details["invitation_closing_date"],
			"formatted_invitation_closing_date"=>date('d-m-y',strtotime($details["invitation_closing_date"])),
			"closing_date"=>$details["closing_date"],
			"formatted_closing_date"=>date('d-m-y',strtotime($details["closing_date"])),
			"created_date"=>$details["tt"],
			"deliverables_ar"=>$deliverables,
			"deliverables"=>$deliverables_text,
			"location"=>$location,
			"tag"=>$tag,
			"brand"=>$brand,
			"invitation"=>$invitation,
			"bounty"=>$bounty,
			"display_image"=>$display_image
		);

		//if required data is all set
		
		//calculate bounty value

		
		return json_encode($summary);
	}

	public function updateDeliverables($deliverable_pairs){
		$this->deliverable_manager->updateDeliverables($deliverable_pairs);
	}

	public function isProjectOwner($project_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE id = %d AND created_by = %d";
		return $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id));
	}

	public function emptySample($project_id){
		return $this->sample_manager->emptySample($project_id);
	}

	public function addSample($url, $project_id){
		return $this->sample_manager->addSample($url, $project_id);
	}

	public function removeSample($sample_id){
		return $this->sample_manager->removeSample($sample_id);
	}

	public function getSample($project_id){
		return $this->sample_manager->getSample($project_id);
	}

	public function getInvitationList($project_id){
		global $wpdb;

		/*
		SELECT a.id as `invitation_id`, a.status as `invitation_status`, a.sent_date, a.user_id, a.status, b.action, b.remark FROM `wp_project_invitation` a LEFT JOIN ( SELECT m1.* FROM `wp_project_invitation_response` m1 LEFT JOIN `wp_project_invitation_response` m2 ON (m1.invitation_id = m2.invitation_id AND m1.id < m2.id) WHERE m2.id IS NULL) b ON a.id = b.invitation_id WHERE project_id = 1
		 */
		
		$query = "SELECT f.invitation_id, f.invitation_status, f.user_id, f.sent_date, display_name, igusername, user_email, action, remark, REPLACE(REPLACE(h.guid, 'https://storify.me/', 'https://cdn.storify.me/'), 'https://staging.storify.me/', 'https://cdn.storify.me/') as `profile_image` FROM ( SELECT d.*, e.id FROM ( SELECT a.*, b.display_name, b.user_email, c.igusername FROM ( SELECT a.id as `invitation_id`, a.status as `invitation_status`, a.sent_date, a.user_id, a.status, b.action, b.remark FROM `".$wpdb->prefix."project_invitation` a LEFT JOIN ( SELECT m1.* FROM `".$wpdb->prefix."project_invitation_response` m1 LEFT JOIN `".$wpdb->prefix."project_invitation_response` m2 ON (m1.invitation_id = m2.invitation_id AND m1.id < m2.id) WHERE m2.id IS NULL ) b ON a.id = b.invitation_id WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b on a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT item_id, related_item_id FROM `".$wpdb->prefix."podsrel` WHERE field_id = %d ) g ON f.ID = g.item_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.related_item_id = h.ID WHERE f.invitation_status != %s AND f.invitation_status != %s ORDER BY f.sent_date DESC, display_name ASC";

		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 43, "removed", "closed"), ARRAY_A); // field_id 43 is the profile image for instagrammer_fast

		foreach($data as $key=>$value){
			if($value["invitation_status"] == "pending"){
				$data[$key]["action"] = null;
				$data[$key]["remark"] = null;
			}
		}

		return $data;
	}

	public function invitation_response($invitation_id, $action,$userid, $remark){
		global $wpdb;
		//check if project not yet close
		$query = "SELECT b.status FROM `".$this->invitation_manager->getInvitationTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id WHERE a.id = %d";
		$project_status = $wpdb->get_var($wpdb->prepare($query, $invitation_id));

		if($project_status == "open"){
			//check if user is the one to response
			$result = $this->invitation_manager->reply($invitation_id, $action, $userid, $remark);
			if(isset($result["added"]) && $result["added"]){
				//user added
				$invitation_detail = $this->invitation_manager->getInvitation($invitation_id);
				$this->user_manager->addUser($userid, $invitation_detail["project_id"], "creator");

				//add creator - project status
				$this->status_manager->updateStatus($userid, $invitation_detail["project_id"], "open");

			}
		}else{
			return array(
				"error"=>1,
				"success"=>0,
				"msg"=>"Project no longer active, current status : ".( $project_status?$project_status:"NULL")
			);
		}

		return $result;
	}

	public function setInvitationBatch($project_id, $user_lists){ //send invitation in batch, will only send if invitation is not created before
		return $this->invitation_manager->setInvitationBatch($project_id, $user_lists);
	}

	public function setInvitation($project_id, $user_id){ // resend invitation, for rejected, pending, removed
		return $this->invitation_manager->setInvitation($project_id, $user_id);
	}

	public function removeInvitation($invitation_id){
		return $this->invitation_manager->removeInvitation($invitation_id);
	}

	public function getUsersByAdmin($project_id){
		global $wpdb;

		/*
		$query = "SELECT f.*, REPLACE(h.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `profile_image` FROM  ( SELECT d.*, e.id FROM ( SELECT a.user_id, a.role, b.display_name, b.user_email, c.igusername FROM ( SELECT user_id, role FROM `".$this->user_manager->getTable()."` WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT item_id, related_item_id FROM `".$wpdb->prefix."podsrel` WHERE field_id = %d ) g on f.ID = g.item_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.related_item_id = h.ID";

		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 43), ARRAY_A);
		*/
	
		$query = "SELECT f.*, REPLACE(h.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `profile_image` FROM  ( SELECT d.*, e.id FROM ( SELECT a.user_id, a.role, b.display_name, b.user_email, c.igusername FROM ( SELECT user_id, role FROM `".$this->user_manager->getTable()."` WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT meta_value, user_id FROM `".$wpdb->prefix."usermeta` WHERE meta_key = %s ) g ON f.user_id = g.user_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.meta_value = h.ID";
		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 'profile_pic'), ARRAY_A);
		return $data;
	}

	public function getUsers($project_id){
		global $wpdb;

		/*
		$query = "SELECT f.*, REPLACE(h.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `profile_image` FROM  ( SELECT d.*, e.id FROM ( SELECT a.user_id, a.role, b.display_name, c.igusername FROM ( SELECT user_id, role FROM `".$this->user_manager->getTable()."` WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT item_id, related_item_id FROM `".$wpdb->prefix."podsrel` WHERE field_id = %d ) g on f.ID = g.item_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.related_item_id = h.ID";

		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 43), ARRAY_A);
		*/
	
		$query = "SELECT f.*, REPLACE(h.guid, 'https://storify.me/', 'https://cdn.storify.me/') as `profile_image` FROM ( SELECT d.* FROM ( SELECT a.user_id, a.role, b.ID, b.display_name, c.igusername FROM ( SELECT user_id, role FROM `".$wpdb->prefix."project_user` WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT meta_value, user_id FROM `".$wpdb->prefix."usermeta` WHERE meta_key = %s ) g ON f.user_id = g.user_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.meta_value = h.ID";

		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 'profile_pic'), ARRAY_A);
		return $data;
	}

	public function getProjectStats($user_id){
		global $wpdb;

		if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){
			$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project` WHERE created_by = %d AND status = %s";
	        $total_ongoing = $wpdb->get_var($wpdb->prepare($query, $user_id, "open"));
	        $total_closed = $wpdb->get_var($wpdb->prepare($query, $user_id, "close"));

	        return array(
	        	"open"=>$total_ongoing,
	        	"closed"=>$total_closed
	        );
		}else{
			//get ongoing and close number
	        $query = "SELECT COUNT(*) FROM ( SELECT * FROM `".$wpdb->prefix."project_invitation` WHERE user_id = %d ) a LEFT JOIN `".$wpdb->prefix."project` b ON a.project_id = b.id WHERE a.status = %s AND b.status = %s";
	        $total_invite = $wpdb->get_var($wpdb->prepare($query, $user_id, "pending", "open"));
	        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project_status` WHERE user_id = %d AND status = %s";
	        $total_ongoing = $wpdb->get_var($wpdb->prepare($query, $user_id, "open"));
	        $total_closed = $wpdb->get_var($wpdb->prepare($query, $user_id, "close"));

	        return array(
	        	"invite"=>$total_invite,
				"open"=>$total_ongoing,
				"closed"=>$total_closed
			);
		}
	}

	public function getNumberOfClosedCreator($user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->status_manager->getTable()."` WHERE user_id = %d AND status = %s";
		$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "close"));

		return $totalsize;
	}

	public function getNumberOfOnGoingCreator($user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->status_manager->getTable()."` WHERE user_id = %d AND status = %s";
		$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "open"));

		return $totalsize;
	}

	public function getNumberOfInvitationCreator($user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status = %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d";
			
		return $wpdb->get_var($wpdb->prepare($query, $user_id, "pending", 0));
	}

	public function getCreatorInvitationList($user_id, $orderBy, $filter="pending", $pagesize=24, $page=1){
		global $wpdb;

		$orderByCond = "";
		switch($orderBy){
			case "bounty":
				$orderByCond = " ORDER BY b.bounty DESC";
			break;
			case "brand_name":
				$orderByCond = " ORDER BY b.brand_name ASC";
			break;
			case "number_creatives":
				$orderByCond = " ORDER BY b.number_creatives ASC";
			break;
			case "closing_date":
				$orderByCond = " ORDER BY b.closing_date ASC";
			break;
			case "invitation_closing_date":
			default:
				$orderByCond = " ORDER BY b.invitation_closing_date ASC";
			break;

		}

		if($filter == "all"){

			$query = "SELECT COUNT(*) FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status != %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s";

			$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "removed", 0, "open"));
			$totalpage = ceil($totalsize / $pagesize);

			$query = "SELECT a.invitation_id, a.status, b.*, UNIX_TIMESTAMP( b.invitation_closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status != %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s".$orderByCond." LIMIT %d, %d";

			$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "removed", 0, "open", ($page - 1)*$pagesize, $pagesize), ARRAY_A);
		}else if($filter == "other"){

			$query = "SELECT COUNT(*) FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status != %s AND status != %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s";

			$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "pending", "removed", 0, "open"));
			$totalpage = ceil($totalsize / $pagesize);

			$query = "SELECT a.invitation_id, a.status, b.*, UNIX_TIMESTAMP( b.invitation_closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status != %s AND status != %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status =%s".$orderByCond." LIMIT %d, %d";

			$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "pending", "removed", 0, "open", ($page-1)*$pagesize, $pagesize), ARRAY_A);
		}else if($filter == ""){

			$query = "SELECT COUNT(*) FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status = %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s";
			
			$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "pending", 0, "open"));
			$totalpage = ceil($totalsize / $pagesize);

			$query = "SELECT a.invitation_id, a.status, b.*, UNIX_TIMESTAMP( b.invitation_closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status = %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s".$orderByCond." LIMIT %d, %d";

			$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "pending", 0, "open", ($page-1)*$pagesize, $pagesize), ARRAY_A);
		}else{

			$query = "SELECT COUNT(*) FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status = %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s";

			$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, $orderBy, 0, "open"));
			$totalpage = ceil($totalsize / $pagesize);

			$query = "SELECT a.invitation_id, a.status, b.*, UNIX_TIMESTAMP( b.invitation_closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM ( SELECT id as `invitation_id`, project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND status = %s ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE b.hide = %d AND b.status = %s".$orderByCond." LIMIT %d, %d";

			$data = $wpdb->get_results($wpdb->prepare($query, $user_id, $filter, 0, "open", ($page-1)*$pagesize, $pagesize), ARRAY_A);
		}

		foreach($data as $key=>$value){
			$data[$key]["summary"] = json_decode($data[$key]["summary"], true);
			//get formatted date
			$data[$key]["summary"]["formatted_invitation_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["formatted_invitation_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["invitation_closing_timestamp"] = strtotime($data[$key]["summary"]["invitation_closing_date"]);
			$data[$key]["summary"]["formatted_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["formatted_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["closing_timestamp"] = strtotime($data[$key]["summary"]["closing_date"]);
			$data[$key]["summary"]["formatted_created_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["formatted_created_date2"] = date('d-m-y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["created_timestamp"] = strtotime($data[$key]["summary"]["created_date"]);
			$data[$key]["duesoon"] = ( strtotime($data[$key]["summary"]["invitation_closing_date"]) - time() ) < 172800 ? true : false;
			$data[$key]["deliverables"] = $this->getDeliverablesStats($value["id"], $user_id);
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}

	public function getCreatorProjectList($user_id, $orderBy="", $filter="", $pagesize=24, $page=1){
		global $wpdb;

		$orderByCond = "";
		switch($orderBy){
			case "bounty":
				$orderByCond = " ORDER BY b.bounty DESC";
			break;
			case "brand_name":
				$orderByCond = " ORDER BY b.brand_name ASC";
			break;
			case "number_creatives":
				$orderByCond = " ORDER BY b.number_creatives ASC";
			break;
			case "closing_date":
				$orderByCond = " ORDER BY b.closing_date ASC";
			break;
			case "rev_closing_date":
				$orderByCond = " ORDER BY b.closing_date DESC";
			break;
			case "invitation_closing_date":
			default:
				$orderByCond = " ORDER BY b.invitation_closing_date ASC";
			break;
		}

		switch($filter){
			case "ongoing": //open
				//total size
				$query = "SELECT COUNT(*) FROM `".$this->status_manager->getTable()."` WHERE user_id = %d AND status = %s";
				$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "open"));
				$totalpage = ceil($totalsize / $pagesize);

				$query = "SELECT b.*, UNIX_TIMESTAMP( b.closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM `".$this->status_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE a.user_id = %d AND a.status = %s".$orderByCond." LIMIT %d, %d";
				$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "open", ($page-1)*$pagesize, $pagesize), ARRAY_A);
			break;
			case "close":
				$query = "SELECT COUNT(*) FROM `".$this->status_manager->getTable()."` WHERE user_id = %d AND status = %s";
				$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "close"));
				$totalpage = ceil($totalsize / $pagesize);

				$query = "SELECT b.*, UNIX_TIMESTAMP( b.closing_date ) - UNIX_TIMESTAMP() as `before_time_left`, c.summary FROM `".$this->status_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE a.user_id = %d AND a.status = %s".$orderByCond." LIMIT %d, %d";
				$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "close", ($page-1)*$pagesize, $pagesize), ARRAY_A);
			break;
		}
		
		foreach($data as $key=>$value){
			$data[$key]["summary"] = json_decode($data[$key]["summary"], true);
			//get formatted date
			$data[$key]["summary"]["formatted_invitation_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["formatted_invitation_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["invitation_closing_timestamp"] = strtotime($data[$key]["summary"]["invitation_closing_date"]);
			$data[$key]["summary"]["formatted_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["formatted_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["closing_timestamp"] = strtotime($data[$key]["summary"]["closing_date"]);
			$data[$key]["summary"]["formatted_created_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["formatted_created_date2"] = date('d-m-y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["created_timestamp"] = strtotime($data[$key]["summary"]["created_date"]);
			$data[$key]["duesoon"] = ( strtotime($data[$key]["summary"]["closing_date"]) - time() ) < 172800 ? true : false;
			$data[$key]["deliverables"] = $this->getDeliverablesStats($value["id"], $user_id);
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}

	public function getBrandProjectList($user_id, $orderby = "", $filter="", $pagesize=24, $page=1){
		global $wpdb;

		$orderByCond = "";
		switch($orderby){
			case "id":
				$orderByCond = " ORDER BY b.id DESC";
			break;
			case "bounty":
				$orderByCond = " ORDER BY b.bounty DESC";
			break;
			case "brand_name":
				$orderByCond = " ORDER BY b.brand_name ASC";
			break;
			case "number_creatives":
				$orderByCond = " ORDER BY b.number_creatives ASC";
			break;
			case "closing_date":
				$orderByCond = " ORDER BY b.closing_date ASC";
			break;
			case "invitation_closing_date":
			default:
				$orderByCond = " ORDER BY b.invitation_closing_date ASC";
			break;
		}

		switch($filter){
			case "ongoing":
				$query = "SELECT COUNT(*) FROM `".$this->user_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id WHERE a.user_id = %d AND a.role = %s AND b.status = %s";
				$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "admin", "open"));
				$totalpage = ceil($totalsize / $pagesize);

				$query = "SELECT a.role, b.*, c.summary FROM `".$this->user_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE a.user_id = %d AND a.role = %s AND b.status = %s".$orderByCond." LIMIT %d, %d";
				$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "admin", "open", ($page - 1)*$pagesize, $pagesize), ARRAY_A);
			break;
			case "close":
				$query = "SELECT COUNT(*) FROM `".$this->user_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id WHERE a.user_id = %d AND a.role = %s AND ( b.status = %s OR b.status = %s )";
				$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, "admin", "complete", "close"));
				$totalpage = ceil($totalsize / $pagesize);

				$query = "SELECT a.role, b.*, c.summary FROM `".$this->user_manager->getTable()."` a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id LEFT JOIN `".$this->summary_manager->getSummaryTable()."` c ON a.project_id = c.project_id WHERE a.user_id = %d AND a.role = %s AND ( b.status = %s OR b.status = %s )".$orderByCond." LIMIT %d, %d";
				$data = $wpdb->get_results($wpdb->prepare($query, $user_id, "admin", "complete", "close", ($page - 1)*$pagesize, $pagesize), ARRAY_A);
			break;
		}

		foreach($data as $key=>$value){
			$data[$key]["summary"] = json_decode($data[$key]["summary"], true);
			//get formatted date
			$data[$key]["summary"]["formatted_invitation_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["formatted_invitation_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["invitation_closing_date"]));
			$data[$key]["summary"]["invitation_closing_timestamp"] = strtotime($data[$key]["summary"]["invitation_closing_date"]);
			$data[$key]["summary"]["formatted_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["formatted_closing_date2"] = date('d-m-y',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["closing_timestamp"] = strtotime($data[$key]["summary"]["closing_date"]);
			$data[$key]["summary"]["formatted_created_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["formatted_created_date2"] = date('d-m-y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["created_timestamp"] = strtotime($data[$key]["summary"]["created_date"]);
			$data[$key]["duesoon"] = ( strtotime($data[$key]["summary"]["closing_date"]) - time() ) < 172800 ? true : false;
			$data[$key]["deliverables"] = $this->getDeliverablesStats($value["id"], $user_id);
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}

	public function getProjectList($user_id, $orderby="", $filters="", $pagesize=24, $page=1){
		global $wpdb;

		//filter ongoing , complete
		$query = "SELECT COUNT(*) FROM ( SELECT c.*, d.role FROM ( SELECT a.*, b.summary FROM ( SELECT * FROM `".$this->tbl_project."` WHERE id IN ( SELECT project_id FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d ) OR id IN ( SELECT project_id FROM `".$this->user_manager->getTable()."` WHERE user_id = %d ) ) a LEFT JOIN `".$this->summary_manager->getSummaryTable()."` b ON a.id = b.project_id ) c LEFT JOIN ( SELECT project_id, role, user_id FROM `".$this->user_manager->getTable()."` WHERE user_id = %d ) d ON c.id = d.project_id ) e LEFT JOIN ( SELECT project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND ( status != %s OR status != %s ) ) f ON e.id = f.project_id";
		$totalsize = $wpdb->get_var($wpdb->prepare($query, $user_id, $user_id, $user_id, $user_id, "removed", "rejected"));
		$totalpage = ceil($totalsize / $pagesize);

		//get projectid
		$query = "SELECT e.*, f.status FROM ( SELECT c.*, d.role FROM ( SELECT a.*, b.summary FROM ( SELECT * FROM `".$this->tbl_project."` WHERE id IN ( SELECT project_id FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d ) OR id IN ( SELECT project_id FROM `".$this->user_manager->getTable()."` WHERE user_id = %d ) ) a LEFT JOIN `".$this->summary_manager->getSummaryTable()."` b ON a.id = b.project_id ) c LEFT JOIN ( SELECT project_id, role, user_id FROM `".$this->user_manager->getTable()."` WHERE user_id = %d ) d ON c.id = d.project_id ) e LEFT JOIN ( SELECT project_id, status FROM `".$this->invitation_manager->getInvitationTable()."` WHERE user_id = %d AND ( status != %s OR status != %s )) f ON e.id = f.project_id LIMIT %d, %d";

		$data = $wpdb->get_results($wpdb->prepare($query, $user_id, $user_id, $user_id, $user_id, "removed", "rejected", ($page - 1)*$pagesize, $pagesize), ARRAY_A);

		foreach($data as $key=>$value){
			$data[$key]["summary"] = json_decode($data[$key]["summary"], true);
			//get formatted date
			$data[$key]["summary"]["formatted_closing_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["closing_date"]));
			$data[$key]["summary"]["closing_timestamp"] = strtotime($data[$key]["summary"]["closing_date"]);
			$data[$key]["summary"]["formatted_created_date"] = date('j M y H:i',strtotime($data[$key]["summary"]["created_date"]));
			$data[$key]["summary"]["created_timestamp"] = strtotime($data[$key]["summary"]["created_date"]);
			$data[$key]["deliverables"] = $this->getDeliverablesStats($value["id"], $user_id);
		}

		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
		//check owner
	}

	public function getProjectDetail($project_id, $user_id){
		global $wpdb;

		if($this->user_manager->isUserInProject($project_id, $user_id) || $this->invitation_manager->isUserInInvitation($project_id, $user_id)){
			$query = "SELECT name, closing_date, invitation_closing_date, created_by FROM `".$this->tbl_project."` WHERE id = %d";
			$project = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);			

			$query = "SELECT * FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
			$project["detail"] = $wpdb->get_row($wpdb->prepare($query, $project_id));

			//get summary
			$project["summary"] = json_decode($this->summary_manager->getSummary($project_id), true);

			foreach($project["summary"] as $key=>$value){
				$project["summary"]["formatted_invitation_closing_date"] = date("d-m-y", strtotime($project["summary"]["invitation_closing_date"]));
				$project["summary"]["formatted_closing_date"] = date("d-m-y", strtotime($project["summary"]["closing_date"]));
				$project["summary"]["formatted_invitation_closing_date2"] = date('d-m-y',strtotime($project["summary"]["invitation_closing_date"]));
				$project["summary"]["formatted_created_date2"] = date('d-m-y',strtotime($project["summary"]["created_date"]));
				$project["summary"]["formatted_closing_date2"] = date('d-m-y',strtotime($project["summary"]["closing_date"]));
			}

			//get delviery
			$project["delivery"]  = $this->deliverable_manager->getDeliverables($project_id);

			//get location 
			$query = "SELECT b.term_id, b.name FROM `".$this->location_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
			$project["location"] = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			//get brand
			$query = "SELECT b.term_id, b.name FROM `".$this->brand_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
			$project["brand"] = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			//get tag
			$query = "SELECT b.term_id, b.name FROM `".$this->tag_manager->getTable()."` a LEFT JOIN `".$wpdb->prefix."terms` b ON a.term_id = b.term_id WHERE a.project_id = %d ORDER BY name ASC";
			$project["tag"] = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			//get Sample
			$query = "SELECT id, URL FROM `".$this->sample_manager->getSampleTable()."` WHERE project_id = %d";
			$project["sample"] = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			//get User
			$project["users"] = $this->user_manager->getUsers($project_id);

			//get duesoon
			$project["duesoon"] = ( strtotime($project["summary"]["closing_date"]) - time() ) < 172800 ? true : false;

			//get bounty
			$details = $this->detail_manager->getDetail($project_id);
			if($details["bounty_type"] == "cash"){
				$photo_cost = $details["no_of_photo"] * $details["cost_per_photo"];
				$video_cost = $details["no_of_video"] * $details["cost_per_video"];
				
				$bounty = array(
					array(
						"type"=>"cash",
						"value"=>$photo_cost + $video_cost
					)
				);
			}else if($details["bounty_type"] == "gift"){
				$bounty = array(
					array(
						"type"=>"gift",
						"value"=>$details["reward_name"]
					)
				);
			}else{
				//both
				$photo_cost = $details["no_of_photo"] * $details["cost_per_photo"];
				$video_cost = $details["no_of_video"] * $details["cost_per_video"];
				
				$bounty = array(
					array(
						"type"=>"cash",
						"value"=>$photo_cost + $video_cost
					),
					array(
						"type"=>"gift",
						"value"=>$details["reward_name"]
					)	
				);
			}
			$project["summary"]["bounty"] = $bounty;

			return array(
				"data"=>$project,
				"error"=>0
			);
		}else{
			return array(
				"data"=>null,
				"error"=>1,
				"msg"=>"access denied"
			);
		}
	}

	public function getDeliverablesHistory($deliverable_id, $user_id){ //the deliverable history for certain creator
		global $wpdb;

		$query = "SELECT a.submission_id, a.deliverable_id, a.URL, a.remark, a.user_id, a.status, a.submission_tt as `submit_tt`, b.user_id as `admin`, b.status as `response_status`, b.remark as `response_remark`, b.tt as `response_tt` FROM `".$this->submission_manager->getSubmissionHistoryTable()."` a LEFT JOIN `".$this->submission_manager->getSubmissionResponseTable()."` b ON a.submission_id = b.submission_id WHERE a.deliverable_id = %d AND a.user_id = %d";
		$data = $wpdb->get_results($wpdb->prepare($query, $deliverable_id, $user_id), ARRAY_A);
		return $data;
	}

	public function getDeliverablesStats($project_id, $user_id){
		global $wpdb;
		$user_role = $this->user_manager->getUserRoleInProject($project_id, $user_id);

		$query = "SELECT no_of_photo, no_of_video FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$task = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);
		$total_deliverables = $task["no_of_photo"] + $task["no_of_video"];
		if($user_role == "admin"){
			//get total user being invite to the app
			$query = "SELECT COUNT(*) FROM `wp_project_invitation` WHERE project_id = %d";
			$total_invitation = $wpdb->get_var($wpdb->prepare($query, $project_id));
			//get total creator in the project
			$query = "SELECT COUNT(*) FROM `wp_project_user` WHERE project_id = %d AND role = %s";
			$total_creator = $wpdb->get_var($wpdb->prepare($query, $project_id, 'creator'));
			
			$query = "SELECT status FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d";

			//expecting deliverables
			$total_deliverables = $total_deliverables * $total_creator;

			$total_accept = 0;
			$total_rejected = 0;
			$total_pending = 0;

			$submissions = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
			
			foreach($submissions as $key=>$value){
				if($value["status"] == "accepted"){
					$total_accept++;
				}else if($value["status"] == "rejected"){
					$total_rejected++;
				}else{
					$total_pending++;
				}
			}

			return array(
				"done"=>$total_invitation && $total_deliverables ? $total_accept / ( $total_invitation * $total_deliverables) : 0,
				"deliverable"=>$total_deliverables,
				"invitation"=>$total_invitation,
				"creator"=>$total_creator,
				"accepted"=>$total_accept,
				"pending"=>$total_pending,
				"rejected"=>$total_rejected
			);
		}else{

			//creator
			$query = "SELECT status FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d";

			$total_accept = 0;
			$total_rejected = 0;
			$total_pending = 0;

			$submissions = $wpdb->get_results($wpdb->prepare($query, $project_id, $user_id), ARRAY_A);

			foreach($submissions as $key=>$value){
				if($value["status"] == "accepted"){
					$total_accept++;
				}else if($value["status"] == "rejected"){
					$total_rejected++;
				}else{
					$total_pending++;
				}
			}

			return array(
				"done"=>$total_deliverables ? $total_accept / $total_deliverables :0,
				"deliverable"=>$total_deliverables,
				"accepted"=>$total_accept,
				"pending"=>$total_pending,
				"rejected"=>$total_rejected
			);
		}
	}

	public function getDeliverables($project_id, $user_id){
		global $wpdb;
		$user_role = $this->user_manager->getUserRoleInProject($project_id, $user_id);

		$query = "SELECT no_of_photo, no_of_video FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$task = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);
		$total_deliverables = $task["no_of_photo"] + $task["no_of_video"];

		if($user_role == "admin"){
			$query = "SELECT a.id, a.creator_id, a.type, a.URL, a.remark, a.status, a.file_id, a.admin_remark, a.admin_response_tt, a.tt, b.filename, b.size, b.mime FROM `".$this->submission_manager->getSubmissionTable()."` a LEFT JOIN `".$this->submission_manager->getSubmissionFileTable()."` b ON a.file_id = b.id WHERE a.project_id = %d ORDER BY a.creator_id, a.type";
			$submissions = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
			//group all together

			$query = "SELECT COUNT(*) FROM `wp_project_user` WHERE project_id = %d AND role = %s";
			$total_creator = $wpdb->get_var($wpdb->prepare($query, $project_id, 'creator'));

			$total_photo = $task["no_of_photo"] * $total_creator;
			$total_video = $task["no_of_video"] * $total_creator;

			usort($submissions, function($a, $b){
				if($a["status"] == "" || $a["status"] == "pending"){
					$a_sort_weight = 2;
				}else if($a["status"] == "rejected"){
					$a_sort_weight = 1;
				}else{
					$a_sort_weight = 0;
				}

				if($b["status"] == "" || $b["status"] == "pending"){
					$b_sort_weight = 2;
				}else if($b["status"] == "rejected"){
					$b_sort_weight = 1;
				}else{
					$b_sort_weight = 0;
				}

				if($a_sort_weight == $b_sort_weight){
					return 0;
				}else if($a_sort_weight > $b_sort_weight){
					return -1;
				}else{
					return 1;
				}
			});

			if(sizeof($submissions)){
				$newSubmission = array();
				foreach($submissions as $key=>$value){
					if(!isset($newSubmission[$value["creator_id"]])){
						$newSubmission[$value["creator_id"]] = array();
					}
					$newSubmission[$value["creator_id"]][] = $value;
				}
				$submissions = $newSubmission;
			}

			return array(
				"error"=>0,
				"data"=>$submissions,
				"no_of_photo"=>$total_photo,
				"no_of_video"=>$total_video,
				"msg"=>""
			);
		}else{

			$query = "SELECT a.id, a.creator_id, a.type, a.URL, a.remark, a.status, a.file_id, a.admin_remark, a.admin_response_tt, a.tt, b.filename, b.size, b.mime FROM `".$this->submission_manager->getSubmissionTable()."` a LEFT JOIN `".$this->submission_manager->getSubmissionFileTable()."` b ON a.file_id = b.id WHERE a.project_id = %d AND a.creator_id = %d ORDER BY a.tt DESC";
			$submissions = $wpdb->get_results($wpdb->prepare($query, $project_id, $user_id), ARRAY_A);

			usort($submissions, function($a, $b){
				if($a["status"] == "" || $a["status"] == "pending"){
					$a_sort_weight = 2;
				}else if($a["status"] == "rejected"){
					$a_sort_weight = 1;
				}else{
					$a_sort_weight = 0;
				}

				if($b["status"] == "" || $b["status"] == "pending"){
					$b_sort_weight = 2;
				}else if($b["status"] == "rejected"){
					$b_sort_weight = 1;
				}else{
					$b_sort_weight = 0;
				}

				if($a_sort_weight == $b_sort_weight){
					return 0;
				}else if($a_sort_weight > $b_sort_weight){
					return -1;
				}else{
					return 1;
				}
			});

			return array(
				"error"=>0,
				"data"=>$submissions,
				"no_of_photo"=>$task["no_of_photo"],
				"no_of_video"=>$task["no_of_video"],
				"msg"=>""
			);
		}
	}
/*
	public function getDeliverables($project_id, $user_id){
		global $wpdb;
		$user_role = $this->user_manager->getUserRoleInProject($project_id, $user_id);

		if($user_role == "admin"){
			//get whole list
			$deliverables = $this->deliverable_manager->getDeliverables($project_id);
			foreach($deliverables as $key=>$value){
				//get submission and response
				
				$query = "SELECT c.*, d.id as `history_id` FROM ( SELECT a.URL, a.remark, a.user_id, a.status, a.tt as `submit_tt`, a.id as `submission_id`, a.deliverable_id, b.user_id as `admin`, b.status as `response_status`, b.remark as `response_remark`, b.tt as `response_tt` FROM ( SELECT m1.* FROM `".$this->submission_manager->getSubmissionTable()."` m1 LEFT JOIN `".$this->submission_manager->getSubmissionTable()."` m2 ON (m1.deliverable_id = m2.deliverable_id AND m1.user_id = m2.user_id AND m1.id < m2.id ) WHERE m2.id IS NULL ) a LEFT JOIN `".$this->submission_manager->getSubmissionResponseTable()."` b ON a.id = b.submission_id WHERE a.deliverable_id = %d ) c LEFT JOIN ( SELECT m3.* FROM `".$this->submission_manager->getSubmissionHistoryTable()."` m3 LEFT JOIN `".$this->submission_manager->getSubmissionHistoryTable()."` m4 ON ( m3.deliverable_id = m4.deliverable_id AND m3.user_id = m4.user_id AND m3.id < m4.id ) WHERE m4.id IS NULL ) d ON ( c.deliverable_id = d.deliverable_id AND c.user_id = d.user_id )";
				$data = $wpdb->get_results($wpdb->prepare($query, $value["id"]), ARRAY_A);

				$deliverables[$key]["data"] = $data;

				//check if got history
			}
			return array(
				"error"=>0,
				"data"=>$deliverables,
				"msg"=>""
			);
			
		}else if($user_role == "creator"){
			//only get data about the user
			
			$query = <<<EOD
				SELECT d.*, e.id as `history_id` FROM 
				( 
				SELECT a.id as `deliverable_id`, a.project_id, a.type, a.remark as `deliverable_remark`, b.submission_id, b.user_id, b.URL, b.submission_remark, c.user_id as `admin_id`, c.status as `response_status`, c.remark as `response_remark`, c.tt as `response_date`
				FROM `{$this->deliverable_manager->getTable()}` a 
				LEFT JOIN 
				( SELECT m1.id as `submission_id`, m1.deliverable_id, m1.user_id, m1.URL, m1.remark as `submission_remark`, m1.status FROM `{$this->submission_manager->getSubmissionTable()}` m1 
				LEFT JOIN `{$this->submission_manager->getSubmissionTable()}` m2 ON ( m1.deliverable_id = m2.deliverable_id AND m1.user_id = m2.user_id AND m1.id < m2.id ) WHERE m1.user_id = %d AND m2.id is NULL 
				) b ON a.id = b.deliverable_id 
				LEFT JOIN `{$this->submission_manager->getSubmissionResponseTable()}` c ON b.submission_id = c.submission_id WHERE a.project_id = %d
				) d 
				LEFT JOIN `{$this->submission_manager->getSubmissionHistoryTable()}` e ON ( d.deliverable_id = e.deliverable_id AND d.user_id = e.user_id ) GROUP BY deliverable_id
					ORDER BY d.type, d.deliverable_id
				EOD;

			$data = $wpdb->get_results($wpdb->prepare($query, $user_id, $project_id), ARRAY_A);
			
			return array(
				"error"=>0,
				"data"=>$data,
				"msg"=>$wpdb->last_query
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"user not in any role"
			);
		}
	}
*/

	public function checkUserRoleInProject($user_id, $project_id){
		
		return $this->user_manager->getUserRoleInProject($project_id, $user_id);
	}

	public function submission_remove($submission_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->submission_manager->getSubmissionTable()."` WHERE id = %d AND creator_id = %d";
		if($wpdb->get_var($wpdb->prepare($query, $submission_id, $user_id))){
			$this->submission_manager->removeSubmission($submission_id);
			return array(
				"error"=>0,
				"msg"=>""
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"You are not the owner of this submission"
			);
		}
	}

	public function checkFileOwnerShip($key_id, $user_id){
		return $this->submission_manager->checkFileOwnerShip($key_id, $user_id);
	}

	public function checkFileAdminAccess($key_id, $admin_id){
		global $wpdb;

		$result = $this->submission_manager->getFileKey($key_id);
		$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE created_by = %d AND id = %d";
		if($wpdb->get_var($wpdb->prepare($query, $admin_id, $result["project_id"]))){
			return true;
		}else{
			return false;
		}
	}

	public function changeKeyStatus($key_id, $status){
		$this->submission_manager->changeKeyStatus($key_id, $status);
	}

	public function getFile($key_id){
		return $this->submission_manager->getFileKey($key_id);
	}

	public function uploadComplete($key_id, $caption, $type){
		global $wpdb;
		//insert into submission
		$result = $this->submission_manager->changeKeyStatus($key_id, "uploaded");

		if($type != "photo"){
			$type = "video";
		}

		$query = "SELECT no_of_photo, no_of_video FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$data = $wpdb->get_row($wpdb->prepare($query, $result["project_id"]), ARRAY_A);

		//get number of submitted photo, video
		$query = "SELECT COUNT(*) FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND type = %s";

		$num_submitted_type = $wpdb->get_var($wpdb->prepare($query, $result["project_id"], $result["user_id"], $type));

		if($type == "photo"){
			$cap_submitted_type = $data["no_of_photo"];
		}else{
			$cap_submitted_type	= $data["no_of_video"];
		}

		if($num_submitted_type < $cap_submitted_type){
			return $this->submission_manager->submitFile($result["project_id"], $type, $result["user_id"], $key_id, $caption);
		}else{
			return array(
				"error"=>1,
				"msg"=>"cap reached",
				"success"=>0
			);
		}				
	}

	public function submission_file_submit($project_id, $type, $user_id, $filename, $filesize, $filemime){
		global $wpdb;

		if($type != "photo"){
			$type = "video";
		}

		$query = "SELECT no_of_photo, no_of_video FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$data = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);

		//get number of submitted photo, video
		$query = "SELECT COUNT(*) FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND type = %s";

		$num_submitted_type = $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id, $type));

		if($type == "photo"){
			$cap_submitted_type = $data["no_of_photo"];
		}else{
			$cap_submitted_type	= $data["no_of_video"];
		}

		if($num_submitted_type < $cap_submitted_type){
			return $this->submission_manager->addFileKey($project_id, $user_id, $filename, $filesize, $filemime);
		}else{
			return array(
				"error"=>1,
				"msg"=>"cap reached",
				"success"=>0
			);
		}

	}

	public function submission_submit($project_id, $type, $user_id, $url, $remark){
		global $wpdb;

		if($type != "photo"){
			$type = "video";
		}

		$query = "SELECT no_of_photo, no_of_video FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$data = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);

		//get number of submitted photo, video
		$query = "SELECT COUNT(*) FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND type = %s";

		$num_submitted_type = $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id, $type));

		if($type == "photo"){
			$cap_submitted_type = $data["no_of_photo"];
		}else{
			$cap_submitted_type	= $data["no_of_video"];
		}

		if($num_submitted_type < $cap_submitted_type){
			return $this->submission_manager->submitText($project_id, $type, $user_id, $url, $remark);
		}else{
			return array(
				"error"=>1,
				"msg"=>"cap reached",
				"success"=>0
			);
		}
	}

	public function submission_admin_response($submission_id, $admin_user_id, $status, $status_remark){
		$result = $this->submission_manager->responseSubmission($submission_id, $status, $status_remark);

		//check user completion
		$submission_detail = $this->submission_manager->getSubmissionDetail($submission_id);

		$completion = $this->get_completion_bounty($submission_detail["project_id"], $submission_detail["creator_id"]);

		if($completion["complete"] == 1){
			//finish all task
			$this->changeUserStatus($submission_detail["project_id"], $submission_detail["creator_id"], "close", $admin_user_id);
		}

		
		return $result;
	}

	//check user completion, and return the bounty 
	public function get_completion_bounty($project_id, $user_id){
		global $wpdb;

		//get bounty
		$query = "SELECT bounty_type, no_of_photo, no_of_video, cost_per_photo, cost_per_video, reward_name FROM `".$this->detail_manager->getProjectDetailTable()."` WHERE project_id = %d";
		$result = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);

		//$query = "SELECT type, status, a.remark FROM `".$this->deliverable_manager->getTable()."` a LEFT JOIN ( SELECT m1.* FROM `".$this->submission_manager->getSubmissionTable()."` m1 LEFT JOIN `".$this->submission_manager->getSubmissionTable()."` m2 ON (m1.deliverable_id = m2.deliverable_id AND m1.id < m2.id ) WHERE m1.user_id = %d AND m2.id is NULL ) b on a.id = b.deliverable_id WHERE a.project_id = %d";
		$query = "SELECT type, status, remark FROM `".$this->submission_manager->getSubmissionTable()."` WHERE creator_id = %d AND project_id = %d";

		$deliverables = $wpdb->get_results($wpdb->prepare($query, $user_id, $project_id), ARRAY_A);

		usort($deliverables, function($a, $b){
			if($a["status"] == "" || $a["status"] == "pending"){
				$a_sort_weight = 0;
			}else if($a["status"] == "rejected"){
				$a_sort_weight = 1;
			}else{
				$a_sort_weight = 2;
			}

			if($b["status"] == "" || $b["status"] == "pending"){
				$b_sort_weight = 0;
			}else if($b["status"] == "rejected"){
				$b_sort_weight = 1;
			}else{
				$b_sort_weight = 2;
			}

			if($a_sort_weight == $b_sort_weight){
				return 0;
			}else if($a_sort_weight > $b_sort_weight){
				return -1;
			}else{
				return 1;
			}
		});

		//calculate 
		if($result["bounty_type"] == "cash" || $result["bounty_type"] == "both"){
			$cost_per_photo = (int)$result["cost_per_photo"];
			$cost_per_video = (int)$result["cost_per_video"];

		}else{
			$cost_per_photo = 0;
			$cost_per_video = 0;

		}

		if($result["bounty_type"] == "both" || $result["bounty_type"] == "gift"){
			$gift = $result["reward_name"];
		}else{
			$gift = "";
		}

		$cash = 0;
		$at_least_one = false;
		$complete = 0;

		$complete_count = 0;

		if(sizeof($deliverables)){
			foreach($deliverables as $key=>$value){
				if($value["status"] == "accepted"){
					$complete_count++;
					if($value["type"] == "photo"){
						$cash += $cost_per_photo;
						$at_least_one = true;
					}
					if($value["type"] == "video"){
						$cash += $cost_per_video;
						$at_least_one = true;
					}
				}
			}
			$complete = $complete_count / ($result["no_of_photo"] + $result["no_of_video"]);
		}

		return array(
			"cash"=>$cash,
			"complete"=>$complete,
			"total_item"=>$result["no_of_photo"] + $result["no_of_video"],
			"total_photo"=>$result["no_of_photo"],
			"total_video"=>$result["no_of_video"],
			"complete_item"=>$complete_count,
			"gift"=>$at_least_one ? $gift : "",
			"cost_per_photo"=>$cost_per_photo,
			"cost_per_video"=>$cost_per_video,
			"bounty_type"=>$result["bounty_type"],
			"deliverables"=>$deliverables
		);
	}

	//get project completion, list out user, and completion
	public function get_project_completion($project_id, $user_id){
		global $wpdb;

		//check if user_id is admin
		$user_role = $this->checkUserRoleInProject($user_id, $project_id);

		if($user_role == "admin"){

			$query = "SELECT user_id, status FROM `".$this->status_manager->getTable()."` WHERE project_id = %d";
			$users = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			if(sizeof($users)){
				foreach($users as $key=>$value){
					//get delivery
					$users[$key]["completion"] = $this->get_completion_bounty($project_id, $value["user_id"]);
				}
			}

			return array(
				"error"=>0,
				"msg"=>"",
				"data"=>$users
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"require admin access"
			);
		}
	}

	public function close_project($project_id, $admin_id){
		global $wpdb;

		//check if user_id is admin
		$user_role = $this->checkUserRoleInProject($admin_id, $project_id);

		if($user_role == "admin"){

			$query = "UPDATE `".$this->tbl_project."` SET status = %s WHERE id = %d";
			$wpdb->query($wpdb->prepare($query, "close", $project_id));
			
			return array(
				"error"=>0,
				"msg"=>"silent operation"
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"require admin access"
			);
		}
	}

	public function changeAllUserStatus($project_id, $status, $admin_id){
		global $wpdb;

		//check if user_id is admin
		$user_role = $this->checkUserRoleInProject($admin_id, $project_id);

		if($user_role == "admin"){
			if($status == "close"){
				$users = $this->user_manager->getUsers($project_id);
				foreach($users as $key=>$value){
					if($value["role"] == "creator"){
						$this->status_manager->updateStatus($value["user_id"], $project_id, "close");
						$query = "SELECT id FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND status = %s";
						$opening_items = $wpdb->get_col($wpdb->prepare($query, $project_id, $value["user_id"], ""));

						if(sizeof($opening_items)){
							foreach($opening_items as $key=>$value){
								//change each item to reject
								$this->submission_manager->responseSubmission($value, "rejected", "Project close");
								//move the item to history
								//$this->submission_manager->removeSubmission($value);
							}
						}
					}
				}
			}
			return array(
				"error"=>0,
				"msg"=>"silent operation"
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"require admin access"
			);
		}		
	}

	public function changeUserStatus($project_id, $user_id, $status, $admin_id){
		global $wpdb;

		//check if user_id is admin
		$user_role = $this->checkUserRoleInProject($admin_id, $project_id);

		if($user_role == "admin"){
			//get bounty
			$this->status_manager->updateStatus($user_id, $project_id, $status);
			if($status == "close"){
				$query = "SELECT id FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND status = %s";
				$opening_items = $wpdb->get_col($wpdb->prepare($query, $project_id, $user_id, ""));

				if(sizeof($opening_items)){
					foreach($opening_items as $key=>$value){
						//change each item to reject
						$this->submission_manager->responseSubmission($value, "rejected", "Project close");
						//move the item to history
						//$this->submission_manager->removeSubmission($value);
					}
				}
			}
			return array(
				"error"=>0,
				"msg"=>"silent operation"
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"require admin access"
			);
		}
	}

	//get summary
	public function getCreatorCompletionSummary($project_id, $user_id){
		global $wpdb;

		$bounty = $this->get_completion_bounty($project_id, $user_id);
		$breakdown = array();

		$breakdown_photo = array(
			"type"=>"photo",
			"amount"=>0,
			"expected"=>0,
			"cost_per_item"=>$bounty["cost_per_photo"]
		);

		$breakdown_video = array(
			"type"=>"video",
			"amount"=>0,
			"expected"=>0,
			"cost_per_item"=>$bounty["cost_per_video"]
		);

		$total_cash = 0;

		foreach($bounty["deliverables"] as $key=>$value){
			if($value["type"] == "photo"){
           		$breakdown_photo["expected"]++;
           		if($value["status"] == "accepted"){
           			$total_cash += $bounty["cost_per_photo"];
           			$breakdown_photo["amount"]++;
           		}
			}else if($value["type"] == "video"){
				$breakdown_video["expected"]++;
				if($value["status"] == "accepted"){
					$breakdown_video["amount"]++;
					$total_cash += $bounty["cost_per_video"];
				}
			}
		}

		if($breakdown_photo["expected"] > 0){
			$breakdown[] = $breakdown_photo;
		}

		if($breakdown_video["expected"] > 0){
			$breakdown[] = $breakdown_video;
		}

		return array(
			"deliverables"=>$bounty["deliverables"],
			"completion"=>$bounty["complete"],
			"breakdown"=>$breakdown,
			"total_cash"=>$total_cash,
			"gift"=>( (double)$bounty["complete"] && $bounty["bounty_type"] != "cash" ) ? $bounty["gift"] : ""
		);
	}

	public function getBrandCompletionSummary($project_id, $admin_id){
		global $wpdb;

		//check if user_id is admin
		$user_role = $this->checkUserRoleInProject($admin_id, $project_id);

		if($user_role == "admin"){

			$query = "SELECT user_id, status FROM `".$this->status_manager->getTable()."` WHERE project_id = %d";
			$users = $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);

			$gift_amount = 0;
			$gift_name = "";

			$total_cash = 0;
			$creators = array();

			if(sizeof($users)){
				foreach($users as $key=>$value){
					//get delivery
					$creator_completion = $this->getCreatorCompletionSummary($project_id, $value["user_id"]);
					$creator_completion["user_id"] = $value["user_id"];
					$creator_completion["status"] = $value["status"];
					$creators[] = $creator_completion;
					$total_cash += $creator_completion["total_cash"];
					if($creator_completion["gift"] != ""){
						$gift_name = $creator_completion["gift"];
						$gift_amount++;
					}
				}
			}

			return array(
				"error"=>0,
				"msg"=>"",
				"data"=>array(
					"creators"=>$creators,
					"total_cash"=>$total_cash,
					"total_gift"=>array(
						"name"=>$gift_name,
						"amount"=>$gift_amount
					)
				)
			);
		}else{
			return array(
				"error"=>1,
				"msg"=>"require admin access",
				"data"=>NULL
			);
		}
	}

	public function cronJob(){
		global $wpdb;

		//expired invitation
		//SELECT a . * , b.invitation_closing_date FROM ( SELECT * FROM  `wp_project_invitation` WHERE STATUS =  "pending" )a LEFT JOIN wp_project b ON a.project_id = b.id WHERE b.invitation_closing_date < NOW() && b.invitation_closing_date != "0000-00-00 00:00:00"
		$query = "UPDATE `wp_project_invitation` SET status = %s WHERE id IN ( SELECT a.id FROM ( SELECT * FROM  `".$this->invitation_manager->getInvitationTable()."` WHERE STATUS = %s )a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id WHERE ( UNIX_TIMESTAMP( b.invitation_closing_date ) ) < UNIX_TIMESTAMP() && b.invitation_closing_date != %s )";
		$wpdb->query($wpdb->prepare($query, "expired", "pending", "0000-00-00 00:00:00"));

		//expired project
		$query = "SELECT id FROM `".$this->tbl_project."` WHERE ( UNIX_TIMESTAMP( closing_date ) + 604800 ) < UNIX_TIMESTAMP() AND status = %s";
		$ids = $wpdb->get_col($wpdb->prepare($query, "open"));

		if(sizeof($ids)){
			foreach($ids as $key=>$project_id){
				//closing each user
				$users = $this->user_manager->getUsers($project_id);
				foreach($users as $key=>$value){
					if($value["role"] == "creator"){
						$this->status_manager->updateStatus($value["user_id"], $project_id, "close");
						$query = "SELECT id FROM `".$this->submission_manager->getSubmissionTable()."` WHERE project_id = %d AND creator_id = %d AND status = %s";
						$opening_items = $wpdb->get_col($wpdb->prepare($query, $project_id, $value["user_id"], ""));

						if(sizeof($opening_items)){
							foreach($opening_items as $key=>$value){
								//change each item to reject
								$this->submission_manager->responseSubmission($value, "rejected", "Project force close");
								//move the item to history
								$this->submission_manager->removeSubmission($value);
							}
						}
					}
				}

				//close project
				$query = "UPDATE `".$this->tbl_project."` SET status = %s WHERE id = %d";
				$wpdb->query($wpdb->prepare($query, "close", $project_id));
			}
		}
	}
}