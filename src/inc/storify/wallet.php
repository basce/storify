<?php
namespace storify;

class wallet{

	function __construct(){

	}

	public static function addTransaction($payer_id, $payee_id, $amount, $project_id, $transaction_code, $description){
		global $wpdb;

		$query = "INSERT INTO `".$wpdb->prefix."wallet_transaction` ( payer_id, payee_id, amount, project_id, transaction_code, description ) VALUES ( %d, %d, %d, %d, %s, %s )";
		$wpdb->query($wpdb->prepare($query, $payer_id, $payee_id, $amount, $project_id, $transaction_code, $description));
	}

	public static function updateBalance($user_id, $amount){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$wpdb->prefix."wallet` WHERE user_id = %d";
		if(!$wpdb->get_var($wpdb->prepare($query, $user_id))){
			$query = "INSERT INTO `".$wpdb->prefix."wallet` ( user_id , amount ) VALUES ( %d, %d )";
			$wpdb->query($wpdb->prepare($query, $user_id, $amount));
		}

		$query = "UPDATE `".$wpdb->prefix."wallet` SET amount = amount + %d WHERE user_id = %d";
		$wpdb->query($wpdb->prepare($query, $amount, $user_id));
	}

	public static function getTransaction($user_id, $day){ //days
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."wallet_transaction` WHERE user_id = %d AND UNIX_TIMESTAMP(tt) > ( UNIX_TIMESTAMP() + %d ) ";
		return $wpdb->query($wpdb->prepare($query, $user_id, $day * 86400));
	}
}