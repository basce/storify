<?php
namespace storify\project;

class shipping_address{

	private static $instance = null;

	private $tbl_shipping_address;

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_shipping_address = $wpdb->prefix."20project_shipping_address";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new shipping_address();
		}

		return self::$instance;
	}

	public function getTable(){
		return $this->tbl_shipping_address;
	}

	public function addAddress($user_id, $contact_name, $contact_phone, $address_line_1, $address_line_2, $postal_code, $country){
		global $wpdb;

		$query = "INSERT INTO `".$this->tbl_shipping_address."` ( user_id, contact_name, contanct_phone, address_line_1, address_line_2, $postal_code, $country) VALUES ( %d, %s, %s, %s, %s, %s, %s )";
		$wpdb->query($wpdb->prepare( $query, $user_id, $contact_name, $contact_phone, $address_line_1, $address_line_2, $postal_code, $country ));

		$address_id = $wpdb->insert_id;
		//set to default
		$this->setDefault($user_id, $address_id);

		return $address_id;
	}

	public function setDefault($user_id, $address_id){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_shipping_address."` SET default = %d WHERE user_id = %d";
		$wpdb->query($wpdb->prepare( $query, 0, $user_id ));

		$query = "UPDATE `".$this->tbl_shipping_address."` SET default = %d WHERE id = %d";
		$wpdb->query( $wpdb->prepare( $query, 1, $address_id ) );
	}

	public function remove($id, $user_id){
		global $wpdb;

		$query = "UPDATE `".$this->tbl_shipping_address."` SET hide = %d WHERE id = %d AND user_id = %d";
		$wpdb->query($wpdb->prepare( $query, 1, $id, $user_id ));

	}

	public function getAddress($user_id){
		global $wpdb;

		$query = "SELECT * FROM `".$this->tbl_shipping_address."` WHERE user_id = %d";
		return $wpdb->get_results($wpdb->prepare( $query, $user_id ));
	}
}