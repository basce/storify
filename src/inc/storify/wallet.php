<?php
namespace storify;

class wallet{

	function __construct(){

	}

	public static function withDraw($account_id, $amount, $bank, $bank_account, $bank_transaction_code){
		global $wpdb;

		$current_amount = self::getBalance($account_id);
		if($amount <= $current_amount){

			$wpdb->query("START TRANSACTION");
			$r1 = self::addTransaction($account_id, -1*$amount, "", "withdraw", json_encode(array("bank"=>$bank, "account"=>$bank_account, "transaction_code"=>$bank_transaction_code)));
			$r2 = self::updateBalance($account_id, -1*$amount);
			if( ( $r1 !== false ) && ( $r2 !== false )){
				$wpdb->query("COMMIT");
				return array(
					"error"=>0,
					"msg"=>"transact success"
				);
			}else{
				$wpdb->query("ROLLBACK");
				return array(
					"error"=>1,
					"msg"=>"transact fail",
					"mysql_error"=>$wpdb->last_error
				);
			}

		}else{

			return array(
				"error"=>1,
				"msg"=>"no enough fund"
			);

		}

	}

	public static function topup($account_id, $amount, $method, $transaction_code, $date = null){
		global $wpdb;

		$wpdb->query("START TRANSACTION");
		$r1 = self::addTransaction($account_id, $amount, "", "credit topup", json_encode(array("method"=>$method, "trackcode"=>$transaction_code)), 0, $date);
		$r2 = self::updateBalance($account_id, $amount);
		if( ( $r1 !== false ) && ( $r2 !== false )){
			$wpdb->query("COMMIT");
			return array(
				"error"=>0,
				"msg"=>"transact success"
			);
		}else{
			$wpdb->query("ROLLBACK");
			return array(
				"error"=>1,
				"msg"=>"transact fail",
				"mysql_error"=>$wpdb->last_error
			);
		}
	}

	public static function makepayment($brand_account_id, $payee_account_id, $amount, $extra_data){
		global $wpdb;

		$platform_fee = -9900;

		$wpdb->query("START TRANSACTION");
		$r1 = self::addTransaction($brand_account_id, -1*$amount, "pjt_pay", "deduce fund to make payment", $extra_data);
		$r2 = self::addTransaction($payee_account_id, $amount, "pjt_rec", "receive payment", $extra_data);
		$r3 = self::updateBalance($brand_account_id, -1*$amount);
		$r4 = self::updateBalance($payee_account_id, $amount);

		$r5 = self::addTransaction($brand_account_id, $platform_fee, "plt_fee", "platform fee", $extra_data);
		$r6 = self::updateBalance($brand_account_id, $platform_fee);
		if( ($r1 !== false) && ($r2 !== false) && ($r3 !== false) && ($r4 !== false)){
			$wpdb->query('COMMIT');
			return array(
				"error"=>0,
				"msg"=>"transact success"
			);
		}else{
			$wpdb->query("ROLLBACK");
			return array(
				"error"=>1,
				"msg"=>"transact fail",
				"mysql_error"=>$wpdb->last_error
			);
		}
	}

	public static function addTransaction($account_id, $amount, $transaction_code, $description, $extra_data, $bycash=0, $date = null){
		global $wpdb;

		if(isset($date)){
			$query = "INSERT INTO `".$wpdb->prefix."wallet_transaction` ( account_id, amount, transaction_code, description, extra_data, cash, tt ) VALUES ( %d, %d, %s, %s, %s, %d, %s )";
			return $wpdb->query($wpdb->prepare($query, $account_id, $amount, $transaction_code, $description, json_encode($extra_data), $bycash, $date));
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."wallet_transaction` ( account_id, amount, transaction_code, description, extra_data, cash ) VALUES ( %d, %d, %s, %s, %s, %d )";
			return $wpdb->query($wpdb->prepare($query, $account_id, $amount, $transaction_code, $description, json_encode($extra_data), $bycash));
		}
	}

	public static function createAccount($user_or_business_id, $type="personal"){
		global $wpdb;

		$query = "SELECT account_id FROM `".$wpdb->prefix."wallet` WHERE business_id = %d AND type = %s";
		$account_id = $wpdb->get_var($wpdb->prepare($query, $user_or_business_id, $type));
		if($account_id){
			return array(
				"msg"=>"account already existed",
				"account_id"=>$account_id
			);
		}else{
			$query = "INSERT INTO `".$wpdb->prefix."wallet` ( business_id, type ) VALUES ( %d, %s )";
			$wpdb->query($wpdb->prepare($query, $user_or_business_id, $type));
			return array(
				"msg"=>"",
				"account_id"=>$wpdb->insert_id
			);
		}
	}

	public static function getAccountID($user_or_business_id, $type="personal"){
		global $wpdb;

		$query = "SELECT account_id FROM `".$wpdb->prefix."wallet` WHERE business_id = %d AND type = %s";
		return $wpdb->get_var($wpdb->prepare($query, $user_or_business_id, $type));

	}

	public static function updateBalance($account_id, $amount){
		global $wpdb;

		$query = "UPDATE `".$wpdb->prefix."wallet` SET amount = amount + %d WHERE account_id = %d";
		$wpdb->query($wpdb->prepare($query, $amount, $account_id));
	}

	public static function getBalance($account_id){
		global $wpdb;

		$query = "SELECT amount FROM `".$wpdb->prefix."wallet` WHERE account_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $account_id));
	}

	public static function getTransaction($account_id, $day){ //days
		global $wpdb;

		$query = "SELECT * FROM `".$wpdb->prefix."wallet_transaction` WHERE account_id = %d AND ( UNIX_TIMESTAMP(tt) > ( UNIX_TIMESTAMP() - %d ) ) ORDER BY tt DESC";
		return $wpdb->get_results($wpdb->prepare($query, $account_id, $day * 86400), ARRAY_A);
	}
}