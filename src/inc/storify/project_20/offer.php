<?php
namespace storify\project;

class offer{
	
	private static $instance = null;

	private $tbl_offer;
	private $tbl_offer_response;

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_offer = $wpdb->prefix."20project_offer";
		$this->tbl_offer_response = $wpdb->prefix."20project_offer_response";

	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new offer();
		}

		return self::$instance;
	}

	public function getOfferTable(){
		return $this->tbl_offer;
	}

	public function getOfferResponseTable(){
		return $this->tbl_offer_response;
	}

	public function createOffer($project_id, $offerObj){
		global $wpdb, $current_user, $default_group_id;

		$cash = isset($offerObj["cash"]) ? $offerObj["cash"] : 0;
		$entitlement = isset($offerObj["entitlement"]) ? $offerObj["entitlement"] : "";
		$msg = isset($offerObj["msg"]) ? $offerObj["msg"] : "";
		$user_id = isset($offerObj["userid"]) ? $offerObj["userid"] : 0;

		if(!$user_id){
			//no user found
			return 0;
		}
		// check offer status, if offer status is "open", update rather than insert new
		// offer status: "open", "accepted", "rejected", "withdrawn"

		$query = "SELECT id FROM `".$this->tbl_offer."` WHERE project_id = %d AND user_id = %d AND status = %s";
		$offer_id = $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id, "open"));
		if($offer_id){

			// found status open 
			$query = "UPDATE `".$this->tbl_offer."` SET cash = %s, entitlement = %s, offer_expire_date = NOW() + INTERVAL 1 WEEK WHERE id = %d";
			$wpdb->query( $wpdb->prepare( $query, $cash, $entitlement, $offer_id ) );
			return $offer_id;

		}else{

			$query = "INSERT INTO `".$this->tbl_offer."` ( user_id, by_who, project_id, business_account_id, cash, entitlement, msg, offer_expire_date, status, tt ) VALUES ( %d, %d, %d, %d, %d, %s, %s, NOW() + INTERVAL 1 WEEK, %s, NOW() )";
			$wpdb->query( $wpdb->prepare( $query, $user_id, $current_user->ID, $project_id, $default_group_id, $cash, $entitlement, $msg, "open" ) );
			return $wpdb->insert_id;

		}

	}

	public function responseOffer($offer_id, $response, $msg){
		global $wpdb, $current_user;

		$query = "INSERT INTO `".$this->tbl_offer_response."` ( offer_id, response, msg ) VALUES ( %d, %s, %s )";
		$wpdb->query($wpdb->prepare($query, $offer_id, $response, $msg));

		$query = "UPDATE `".$this->tbl_offer."` SET status = %s WHERE id = %d";
		$wpdb->query( $wpdb->prepare( $query, $response, $offer_id ) );

		return $wpdb->insert_id;
	}

	public function getOfferHistory($project_id, $user_id){
		global $wpdb;

		$query = "SELECT a.*, b.response, b.msg FROM `".$this->tbl_offer."` a LEFT JOIN `".$this->tbl_offer_response."` b ON a.id = b.offer_id WHERE a.project_id = %d AND a.user_id = %d";
		return $wpdb->get_results( $wpdb->prepare( $query, $project_id, $user_id ) );
	}

	public function isUserWithValidOffer($project_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_offer."` WHERE project_id = %d AND user_id = %d AND status = %s";
		return $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id, "open"));
	}

	public function withdrawOffer($offer_id){
		global $wpdb;

		$query = "SELECT user_id, status FROM `".$his->tbl_offer."` WHERE id = %d";
		$offer_result = $wpdb->get_row($wpdb->prepare($query, $offer_id), ARRAY_A );

		if(sizeof($offer_result)){
			if($offer_result["status"] == "open"){
				$query = "UPDATE `".$this->tbl_offer."` SET status = %s WHERE id = %id";
				$wpdb->query($wpdb->prepare($query, "withdrawn", $offer_id));
				return array(
					"error"=>0,
					"success"=>1,
					"user_id"=>$offer_result["user_id"],
					"msg"=>"offer withdrawn"
				);
			}else{
				return array(
					"error"=>0,
					"success"=>0,
					"msg"=>"Only status 'open' offer can be withdrawn"
				);
			}
		}else{
			return array(
				"error"=>1,
				"success"=>0,
				"msg"=>"invalid id"
			);
		}
	}

	public function getOfferStats($project_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_offer."` WHERE project_id = %d AND status != %s";
		$total_offer = $wpdb->get_var($wpdb->prepare($query, $project_id, "withdrawn"));

		$query = "SELECT COUNT(*) FROM `".$this->tbl_offer."` WHERE project_id = %d AND status = %s";
		
		return array(
			"total"=>0,
			"accepted"=>0,
			"rejected"=>0,
			"pending"=>0,
			"removed"=>0
		);
	}

	public function getOfferList($project_id){
		global $wpdb;

		$query = "SELECT a1.* FROM `".$this->tbl_offer."` a1 LEFT OUTER JOIN `".$this->tbl_offer."` a2 ON a1.user_id = a2.user_id AND a1.project_id = a2.project_id AND a1.tt < a2.tt WHERE a2.user_id IS NULL AND a1.project_id = %d";
		return $wpdb->get_results($wpdb->prepare($query, $project_id), ARRAY_A);
	}

	// get creator number of offer by status "open", "accepted", "rejected", "withdrawn"

	public function getCreatorNumberOfOfferByStatus($user_id, $offer_status){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM ( SELECT a1.* FROM `".$this->tbl_offer."` a1 LEFT OUTER JOIN `".$this->tbl_offer."` a2 on a1.user_id = a2.user_id AND a1.project_id = a2.project_id AND a1.tt < a2.tt WHERE a2.user_id IS NULL AND a1.user_id = %d ) b WHERE b.status = %s";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, $offer_status));
	}

	public function getUserLastOfferStatus($project_id, $user_id){
		global $wpdb;

		$query = "SELECT a1.status FROM `".$this->tbl_offer."` a1 LEFT OUTER JOIN `".$this->tbl_offer."` a2 ON a1.user_id = a2.user_id AND a1.project_id = a2.project_id AND a1.tt < a2.tt WHERE a2.user_id IS NULL AND a1.user_id = %d AND a1.project_id = %d";
		return $wpdb->get_var($wpdb->prepare( $query, $user_id, $project_id ));
	}
}