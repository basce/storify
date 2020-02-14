<?php
namespace storify;

class stripe{

	function __construct(){

	}

	public static function getStripe_id($user_id, $stripe_type){
		global $wpdb;

		$query = "SELECT stripe_id FROM `".$wpdb->prefix."stripe` WHERE user_id = %d AND stripe_type = %s";
		return $wpdb->get_var($wpdb->prepare($query, $user_id, $stripe_type));
	}

	public static function updateStripe_id($user_id, $stripe_type, $stripe_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."stripe` WHERE user_id = %d AND stripe_type = %s";
		if($wpdb->get_var($wpdb->prepare($query, $user_id, $stripe_type))){
			//data exist
			$query = "UPDATE `".$wpdb->prefix."stripe` SET stripe_id = %s WHERE user_id = %d AND stripe_type = %s";
			$wpdb->query($wpdb->prepare($query, $stripe_id, $user_id, $stripe_type));
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."stripe` ( user_id, stripe_type, stripe_id ) VALUES ( %d, %s, %s )";
			$wpdb->query($wpdb->prepare($query, $user_id, $stripe_type, $stripe_id));
		}
	}

	public static function createCustomer(){
		global $current_user, $default_group_id;

		if($default_group_id){
			$response = \Stripe\Customer::create(array(
				"description"=>"for business account :".$default_group_id
			));
			if($response && $response->id){
				return array(
					"type"=>"pay",
					"id"=>$response->id
				);
			}
		}else if($current_user && $current_user->ID){
			$response = \Stripe\Customer::create(array(
				"email"=>$current_user->user_email,
			));
			if($response && $response->id){
				return array(
					"type"=>"personal",
					"id"=>$response->id
				);
			}
		}

		return null;
	}

	public static function setSource($ba_id, $source){
		global $wpdb;

		$stripe_id = self::getStripe_id($ba_id, "pay");
		if($stripe_id){
			return \Stripe\Customer::update(
				$stripe_id,
				[
					"default_source"=>$source
				]
			);
		}else{
			return null;
		}
	}

	public static function removeCard($ba_id, $card_id){

		$stripe_id = self::getStripe_id($ba_id, "pay");
		if($stripe_id){
			return \Stripe\Customer::deleteSource(
				$stripe_id,
				$card_id
			);
		}else{
			return null;
		}
		
	}

	public static function getStripeCustomer($ba_id){
		global $wpdb;

		$stripe_id = self::getStripe_id($ba_id, "pay");
		if($stripe_id){
			return \Stripe\Customer::retrieve($stripe_id);
		}else{
			return null;
		}
	}

	public static function getAllCards($ba_id){ //only the first 20 cards.
		global $wpdb;

		$stripe_id = self::getStripe_id($ba_id, "pay");
		if($stripe_id){
			return \Stripe\Customer::allSources(
			  $stripe_id,
			  [
			    'limit' => 20,
			    'object' => 'card',
			  ]
			);
		}else{
			return null;
		}
	}

	public static function getAllCardsByStripeID($stripe_id){ //only the first 20 cards.

		return \Stripe\Customer::allSources(
		  $stripe_id,
		  [
		    'limit' => 20,
		    'object' => 'card',
		  ]
		);
	}

	public static function addCard($customer_id, $source_token){
		return \Stripe\Customer::createSource(
			$customer_id,
			array(
				"source"=>$source_token
			)
		);
	}

	public static function insertPayment($stripe_payment_id, $amount, $description, $balance_transaction, $uid, $project_id, $receipt_url, $credit_card_fingerprint, $raw){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."payment` ( stripe_payment_id, amount, description, balance_transaction, uid, project_id, receipt_url, credit_card_fingerprint, raw ) VALUES ( %s, %d, %s, %s, %d, %d, %s, %s, %s )";
		$wpdb->query($wpdb->prepare($query, $stripe_payment_id, $amount, $description, $balance_transaction, $uid, $project_id, $receipt_url, $credit_card_fingerprint, $raw));
	}

	public static function checkPayment($project_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."payment` WHERE project_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $project_id));
	}

	public static function recordError($msg, $obj){
		global $wpdb;

		$query = "INSERT `".$wpdb->prefix."stripe_error` ( msg, obj ) VALUES ( %s, %s )";
		$wpdb->query($wpdb->prepare($query, $msg, $obj));
	}
}