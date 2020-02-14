<?php
namespace storify;

include_once(__DIR__."/task.php");
include_once(__DIR__."/user.php");
include_once(__DIR__."/offer.php");
include_once(__DIR__."/status.php");
include_once(__DIR__."/summary.php");
include_once(__DIR__."/submission.php");
include_once(__DIR__."/post_report.php");


class project{

	private $tbl_project;
	private $tbl_project_status;

	private $user_manager = null;
	private $task_manager = null;

	function __construct(){
		global $wpdb;

		$this->tbl_project = $wpdb->prefix."20project";

		$this->user_manager = new \storify\project\user();
	}

	public function getCDNURL($url){
		//update all to https://cdn2.storify.me
		//from
		// https://cdn.storify.me
		// https://staging.storify.me
		// https://storify.me

		$replace = "https://cdn2.storify.me";
		$search = array(
			"https://cdn.storify.me",
			"https://staging.storify.me",
			"https://storify.me"
		);

		foreach($search as $key=>$value){
			$url = str_replace($value, $replace, $url);
		}

		return $url;
	}

	public function getUTCStringFromLocal($datetimeStr, $hours){

		$split = explode("/", $datetimeStr);

		if(sizeof($split) == 3){
			$dateObj = \DateTime::createFromFormat("d/m/y H:i:s", $datetimeStr." 00:00:00");
			return date("Y-m-d H:i:s", $dateObj->getTimestamp() - $hours*3600);
		}else{
			return date("Y-m-d H:i:s", strtotime($datetimeStr) - $hours*3600);
		}
	}

	public function createNewProject($data){
		global $wpdb, $current_user, $default_group_id;

		\storify\vlog::trace("save project", $data);

		$title = isset($data["title"]) ? $data["title"] : "";
		$description = isset($data["description"]) ? $data["description"] : "";
		$summary = isset($data["summary"]) ? $data["summary"] : "";
		$brand = isset($data["brand"]) ? json_encode($data["brand"]) : "[]";
		$location = isset($data["location"]) ? json_encode($data["location"]) : "[]";
		$tag = isset($data["tag"]) ? json_encode($data["tag"]) : "[]";
		$extra = json_encode($data["extra"]);

		$query = "INSERT INTO `".$this->tbl_project."` ( title, description, summary, extra, brand, location, tag, created_by, group_id, status, hide ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %d )";
		$wpdb->query($wpdb->prepare( $query, $title, $description, $summary, $extra, $brand, $location, $tag, $current_user->ID, $default_group_id, "open", 0 ));

		$project_id = $wpdb->insert_id;

		$this->user_manager->addUser($current_user->ID, $project_id, "admin");

		\storify\vlog::trace("save task project to project id :".$project_id, $data["task"]);

		//add task
		foreach( $data["task"] as $key => $value ){

			\storify\project\task::getInstance()->addTask($project_id, $value);

		}

		//add invitation
		if( isset($data["creator"]) && sizeof($data["creator"]) ){
			foreach( $data["creator"] as $key => $value ){
				\storify\project\offer::getInstance()->createOffer($project_id, $value);
			}
		}

		return $project_id;
	}

	public function getProjectDetail($project_id){
		global $wpdb, $default_group_id;

		$query = "SELECT * FROM `".$this->tbl_project."` WHERE id = %d";
		$result = $wpdb->get_row( $wpdb->prepare( $query, $project_id ), ARRAY_A );

		$result["summary"] = json_decode( $result["summary"], true );

		return $result;
	}

	public function updateProjectSummary($project_id, $summaryObj){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_project."` SET summary = %s WHERE id = %d";
		$wpdb->query($wpdb->prepare($query, json_encode($summaryObj), $project_id));
	}

	public function generateProjectSummary($project_id){
		global $wpdb;

		// get description text.

		$query = "SELECT * FROM `".$this->tbl_project."` WHERE id = %d";
		$project_result = $wpdb->get_row($wpdb->prepare($query, $project_id), ARRAY_A);

		// get extra field
		$extra = json_decode($project_result, true);

		if($project_result["description"]){
			$summary_text = strip_tags(str_replace('<', ' <', $project_result["description"]));
		}else{
			$summary_text = "";
		}

		// remove extra spaces
		$summary_text = preg_replace( '/\s+/', ' ', $summary_text );

		// get term label

		$query = "SELECT name FROM `".$wpdb->prefix."terms` WHERE term_id = %d";

		$project_location = array();
		$project_result_location = json_decode($project_result["location"], true);
		if( $project_result_location ){
			foreach( $project_result_location as $key=>$value ){
				$label = $wpdb->get_var( $wpdb->prepare( $query, $value ) );

				$project_location[] = array(
					"term_id"=>$value,
					"name"=>$label
				);
			}
		}

		$project_brand = array();
		$project_result_brand = json_decode($project_result["brand"], true);
		if( $project_result_brand ){
			foreach( $project_result_brand as $key=>$value ){
				$label = $wpdb->get_var( $wpdb->prepare( $query, $value ) );

				$project_brand[] = array(
					"term_id"=>$value,
					"name"=>$label
				);
			}
		}

		$project_tag = array();
		$project_result_tag = json_decode($project_result["tag"], true);
		if( $project_result_tag ){
			foreach( $project_result_tag as $key=>$value ){
				$label = $wpdb->get_var( $wpdb->prepare( $query, $value ) );

				$project_tag[] = array(
					"term_id"=>$value,
					"name"=>$label
				);
			}
		}

		// get offers request to get user ( user that accepted and not )

		$offer_result = \storify\project\offer::getInstance()->getOfferList($project_id);

		// generate offer stats

		$offer_stats = array(
			"open"=>0,
			"accept"=>0,
			"reject"=>0
		);

		foreach( $offer_result as $key => $value ){
			switch( $value["status"] ){
				case "open":
					$offer_stats["open"]++;
				break;
				case "accepted":
					$offer_stats["accept"]++;
				break;
				case "rejected":
					$offer_stats["reject"]++;
				break;
			}
		}

		// get offer detail, user name profile

		if(sizeof($offer_result)){

			$user_ids = array();
			foreach( $offer_result as $key=>$value ){
				$user_ids[] = $value["user_id"];
			}

			$query = "SELECT f.*, h.guid as `profile_image` FROM ( SELECT d.* FROM ( SELECT a.display_name, a.ID, b.igusername FROM `".$wpdb->prefix."users` a LEFT JOIN `".$wpdb->prefix."igaccounts` b ON a.ID = b.userid WHERE a.ID IN ( ".implode( ",", $user_ids )." ) ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e on d.igusername = e.igusername ) f LEFT JOIN ( SELECT meta_value, user_id FROM `".$wpdb->prefix."usermeta` WHERE meta_key = %s ) g ON f.ID = g.user_id LEFT JOIN `".$wpdb->prefix."posts` h on g.meta_value = h.ID";

			$users_data = $wpdb->get_results($wpdb->prepare( $query, 'profile_pic' ), ARRAY_A);

			$tempusers_data = array();
			foreach( $users_data as $key=>$value ){
				$tempusers_data["a".$value["ID"]] = $value;
			}

			foreach( $offer_result as $key=>$value ){
				if(isset( $tempusers_data["a".$value["user_id"]] )){
					$offer_result[$key]["display_name"] = $tempusers_data["a".$value["user_id"]]["display_name"];
					$offer_result[$key]["igusername"] = $tempusers_data["a".$value["user_id"]]["igusername"];
					$offer_result[$key]["profile_image"] = $tempusers_data["a".$value["user_id"]]["profile_image"];
				}
			}
		}

		// get tasks
		$tasks = \storify\project\task::getInstance()->getTasks($project_id);

		// get tasks stats
		$number_of_submissions_required = 0;
		$number_of_post_submissions_required = 0;
		if( sizeof($tasks) ){
			foreach( $tasks as $key => $value ){
				$number_of_submissions_required += $offer_stats["accept"];
				if( (int) $value["post"] ){
					$number_of_post_submissions_required += $offer_stats["accept"];
				}
			}
		}

		// get submissions
		$submissions = \storify\project\submission::getInstance()->getSubmissionByProject_id($project_id);

		// get submission stats
		$submission_stats = array(
			"pending"=>0,
			"accept"=>0,
			"reject"=>0,
			"expect"=>$number_of_submissions_required
		);

		if( sizeof($submissions) ){
			foreach( $submissions as $key=>$value ){
				switch($value["status"]){
					case "submitted":
						$submission_stats["pending"]++;
					break;
					case "accepted":
						$submission_stats["accept"]++;
					break;
					case "rejected":
						$submission_stats["reject"]++;
					break;
				}
			}
		}

		// get post_report

		$post_report = \storify\project\post_report::getInstance()->getPostReportByProject_id($project_id);

		$post_report_stats = array(
			"pending"=>0,
			"accept"=>0,
			"reject"=>0,
			"expect"=>$number_of_post_submissions_required
		);

		if(sizeof($post_report)){
			foreach($post_report as $key=>$value){
				switch( $value["status"] ){
					case "submitted":
						$post_report_stats["pending"]++;
					break;
					case "accepted":
						$post_report_stats["accept"]++;
					break;
					case "rejected":
						$post_report_stats["reject"]++;
					break;
				}
			}
		}

		$result = array(
			"id"=>$project_id,
			"description"=>$summary_text,
			"location"=>$project_location,
			"brand"=>$project_brand,
			"tag"=>$project_tag,
			"extra"=>$project_extra,
			"offer"=>array(
				"data"=>$offer_result,
				"stats"=>$offer_stats
			),
			"task"=>$tasks,
			"submission"=>array(
				"data"=>$submissions,
				"stats"=>$submission_stats
			),
			"post_report"=>array(
				"data"=>$post_report,
				"stats"=>$post_report_stats
			)
		);

		// save summary

		$query = "UPDATE `".$this->tbl_project."` SET summary = %s WHERE id = %d";
		$wpdb->query( $wpdb->prepare( $query, json_encode($result), $project_id ) );

	}

	public function getUsersByAdmin($project_id){
		global $wpdb;

		$query = "SELECT f.*, h.guid as `profile_image` FROM  ( SELECT d.*, e.id FROM ( SELECT a.user_id, a.role, b.display_name, b.user_email, c.igusername FROM ( SELECT user_id, role FROM `".$this->user_manager->getTable()."` WHERE project_id = %d ) a LEFT JOIN `".$wpdb->prefix."users` b ON a.user_id = b.ID LEFT JOIN `".$wpdb->prefix."igaccounts` c ON a.user_id = c.userid ) d LEFT JOIN `".$wpdb->prefix."pods_instagrammer_fast` e ON d.igusername = e.igusername ) f LEFT JOIN ( SELECT meta_value, user_id FROM `".$wpdb->prefix."usermeta` WHERE meta_key = %s ) g ON f.user_id = g.user_id LEFT JOIN `".$wpdb->prefix."posts` h ON g.meta_value = h.ID";
		$data = $wpdb->get_results($wpdb->prepare($query, $project_id, 'profile_pic'), ARRAY_A);

		foreach($data as $key=>$value){
			$data[$key]["profile_image"] = $this->getCDNURL($value["profile_image"]);
		}

		return $data;

	}

	// get project list

	public function getProjectStats($user_id, $group_id=0){
		global $wpdb;

		if(isset($_SESSION["role_view"]) && $_SESSION["role_view"] == "brand"){
			if($group_id){
				$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE hide = %d AND group_id = %d AND status = %s";
				$total_ongoing = $wpdb->get_var($wpdb->prepare($query, 0, $group_id, "open"));
				$total_closed = $wpdb->get_var($wpdb->prepare($query, 0, $group_id, "close"));
			}else{

				// backward compatible, to prevent missing business group id error.

				$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE hide = %d AND created_by = %d AND status = %s";
				$total_ongoing = $wpdb->get_var($wpdb->prepare($query, 0, $user_id, "open"));
				$total_closed = $wpdb->get_var($wpdb->prepare($query, 0, $user_id, "close"));
			}

	        return array(
	        	"open"=>$total_ongoing,
	        	"closed"=>$total_closed
	        );
		}else{
			//get ongoing and close number
			$query = "SELECT COUNT(*) FROM ( SELECT * FROM `".\storify\project\offer::getInstance()->getOfferTable()."` WHERE user_id = %d ) a LEFT JOIN `".$this->tbl_project."` b ON a.project_id = b.id WHERE b.hide = 0 AND a.status = %s AND b.status = %s";
			$total_invite = $wpdb->get_var($wpdb->prepare($query, $user_id, "open", "open"));
			$query = "SELECT COUNT(*) FROM `".

	        $query = "SELECT COUNT(*) FROM ( SELECT * FROM `".$wpdb->prefix."project_invitation` WHERE user_id = %d ) a LEFT JOIN `".$wpdb->prefix."project` b ON a.project_id = b.id WHERE b.hide = 0 AND a.status = %s AND b.status = %s";
	        $total_invite = $wpdb->get_var($wpdb->prepare($query, $user_id, "pending", "open"));
	        $query = "SELECT COUNT(*) FROM `".$wpdb->prefix."project_status` a LEFT JOIN `".$wpdb->prefix."project` b ON a.project_id = b.id WHERE b.hide = 0 AND a.user_id = %d AND a.status = %s";
	        $total_ongoing = $wpdb->get_var($wpdb->prepare($query, $user_id, "open"));
	        $total_closed = $wpdb->get_var($wpdb->prepare($query, $user_id, "close"));

	        return array(
	        	"invite"=>$total_invite,
				"open"=>$total_ongoing,
				"closed"=>$total_closed
			);
		}
	}

	public function isHaveBusinessAccess($project_id){
		global $wpdb, $default_group_id;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE group_id = %d AND id = %d";

		return $wpdb->get_var( $wpdb->prepare( $query, $default_group_id, $project_id ) );
	}

	public function getBrandProjectList($group_id, $filter, $pagesize = 24, $page = 1){
		global $wpdb;

		switch($filter){
			case "ongoing":

				$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE group_id = %d AND hide = %d AND status = %s";

				$totalsize = $wpdb->get_var($wpdb->prepare($query, $group_id, 0, "open"));
				$totalpage = ceil( $totalsize / $pagesize );

				$query = "SELECT * FROM `".$this->tbl_project."` WHERE group_id = %d AND hide = %d AND status = %s ORDER BY tt DESC LIMIT %d, %d";
				$data = $wpdb->get_results( $wpdb->prepare( $query, $group_id, 0, "open", ( $page - 1 )*$pagesize, $pagesize ), ARRAY_A );

			break;
			case "close":

				$query = "SELECT COUNT(*) FROM `".$this->tbl_project."` WHERE group_id = %d AND hide = %d AND status = %s";

				$totalsize = $wpdb->get_var($wpdb->prepare($query, $group_id, 0, "close"));
				$totalpage = ceil( $totalsize / $pagesize );

				$query = "SELECT * FROM `".$this->tbl_project."` WHERE group_id = %d AND hide = %d AND status = %s ORDER BY tt DESC LIMIT %d, %d";
				$data = $wpdb->get_results( $wpdb->prepare( $query, $group_id, 0, "close", ( $page - 1 )*$pagesize, $pagesize ), ARRAY_A );

			break;
		}

		foreach( $data as $key => $value ){

			$data[$key]["summary"] = json_decode( $data[$key]["summary"], true);

		}
		
		return array(
			"data"=>$data,
			"page"=>$page,
			"totalpage"=>$totalpage,
			"total"=>$totalsize,
			"pagesize"=>(int)$pagesize
		);
	}

	public function getCreatorProjectList($user_id){
		return array();
	}

	// status manager

	public function getNumberOfClosedCreator($user_id){

		return \storify\project\status::getInstance()->getCreatorNumberOfProjectByStatus($user_id, "close");

	}

	public function getNumberOfOnGoingCreator($user_id){

		return \storify\project\status::getInstance()->getCreatorNumberOfProjectByStatus($user_id, "open");
		
	}

	public function getNumberOfInvitationCreator($user_id){

		return \storify\project\offer::getInstance()->getCreatorNumberOfOfferByStatus($user_id, "open");

	}

}