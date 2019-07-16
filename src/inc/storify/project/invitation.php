<?php
namespace storify\project;

class invitation{
	
	private $tbl_invitation;
	private $tbl_invitation_response;

	public function __construct(){
		global $wpdb;

		$this->tbl_invitation = $wpdb->prefix."project_invitation";
		$this->tbl_invitation_response = $wpdb->prefix."project_invitation_response";
	}  

	public function getInvitationTable(){
		return $this->tbl_invitation;
	}

	public function getInvitationResponseTable(){
		return $this->tbl_invitation_response;
	}

	public function isUserInInvitation($project_id, $user_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_invitation."` WHERE project_id = %d AND user_id = %d";
		$count =  $wpdb->get_var($wpdb->prepare($query, $project_id, $user_id));
		return $count;
	}

	public function removeInvitation($invitation_id){
		global $wpdb;

		$query = "SELECT user_id, status FROM `".$this->tbl_invitation."` WHERE id = %d";
		$invitation_result  = $wpdb->get_row($wpdb->prepare($query, $invitation_id), ARRAY_A);
		if(sizeof($invitation_result )){
			//valid status for remove
			if($invitation_result["status"] == "pending"){
				$query = "UPDATE `".$this->tbl_invitation."` SET status = %s WHERE id = %d";
				$wpdb->query($wpdb->prepare($query, 'removed', $invitation_id));
				return array(
					"error"=>0,
					"success"=>1,
					"userid"=>$invitation_result["user_id"],
					"msg"=>"invitation removed"
				);
			}else{
				return array(
					"error"=>0,
					"success"=>0,
					"msg"=>"only pending invitation can be removed."
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

	public function getInvitationStats($project_id){
		global $wpdb;

		$query = "SELECT COUNT(*) FROM `".$this->tbl_invitation."` WHERE project_id = %d AND status != %s";
		$total_invitation = $wpdb->get_var($wpdb->prepare($query, $project_id, "removed"));

		$query = "SELECT COUNT(*) FROM `".$this->tbl_invitation."` WHERE project_id = %d AND status = %s";
		$total_accepted = $wpdb->get_var($wpdb->prepare($query, $project_id, "accepted"));
		$total_rejected = $wpdb->get_var($wpdb->prepare($query, $project_id, "rejected"));
		$total_pending = $wpdb->get_var($wpdb->prepare($query, $project_id, "pending"));
		$total_removed = $wpdb->get_var($wpdb->prepare($query, $project_id, "removed"));
		
		return array(
			"total"=>$total_invitation,
			"accepted"=>$total_accepted,
			"rejected"=>$total_rejected,
			"pending"=>$total_pending,
			"removed"=>$total_removed
		);
	}

	public function setInvitationBatch($project_id, $userids){
		global $wpdb;

		//unlike setInvitation, when detect the user is in the list, it will not set invite regardless the user status.
		$user_id_added = array();
		$user_id_fail = array();
		if(!is_array($userids)){
			$userids = array($userids);
		}
		foreach($userids as $key=>$value){

			$query = "SELECT status FROM `".$this->tbl_invitation."` WHERE project_id = %d AND user_id = %d";
			$invitation_status = $wpdb->get_var($wpdb->prepare($query, $project_id, $value));
			if($invitation_status){
				if($invitation_status == "removed"){
					$query = "UPDATE `".$this->tbl_invitation."` SET status = %s, sent_date = NOW() WHERE project_id = %d AND user_id = %d";
					$wpdb->query($wpdb->prepare($query, "pending", $project_id, $value));

					$user_id_added[] = $value;	
				}else{
					//it exists and not removed, do nothing, 
					$user_id_fail[] = $value;	
				}
			}else{
				$query = "INSERT INTO `".$this->tbl_invitation."` ( project_id, user_id, status, sent_date ) VALUES ( %d, %d, %s, NOW())";
				$wpdb->query($wpdb->prepare($query, $project_id, $value, "pending"));

				$user_id_added[] = $value;
			}
		}

		return array(
			"added"=>$user_id_added,
			"failed"=>$user_id_fail
		);
	}

	public function setInvitation($project_id, $userid){
		global $wpdb;

		//check if already in table, if yes, return inivitaiton id
		$invitation_id = $this->getInvitationID($project_id, $userid);
		if($invitation_id){
			//invitation exist
			
			$query = "SELECT status FROM `".$this->tbl_invitation."` WHERE id = %d";
			$invitation_status = $wpdb->get_var($wpdb->prepare($query, $invitation_id));

			// pending, rejected, removed, accepted
			if(in_array($invitation_status, array("pending", "rejected", "removed", "expired"))){
				$query = "UPDATE `".$this->tbl_invitation."` SET status = %s, sent_date = NOW() WHERE id = %d";
				$wpdb->query($wpdb->prepare($query, 'pending', $invitation_id));
			}else{
				return array(
					"error"=>0,
					"success"=>0,
					"msg"=>"invitation exists",
					"invitation_id"=>$invitation_id
				);
			}			
		}else{
			$query = "INSERT INTO `".$this->tbl_invitation."` ( project_id, user_id, status, sent_date ) VALUES ( %d, %d, %s, NOW())";
			$wpdb->query($wpdb->prepare($query, $project_id, $userid, "pending"));
			$invitation_id = $wpdb->insert_id;

			return array(
				"error"=>0,
				"success"=>1,
				"msg"=>"invitation successfully set",
				"invitation_id"=>$invitation_id
			);
		}
	}

	public function getInvitationID($project_id, $userid){
		global $wpdb;

		$query = "SELECT id FROM `".$this->tbl_invitation."` WHERE project_id = %d AND user_id = %d";
		return $wpdb->get_var($wpdb->prepare($query, $project_id, $userid));
	}

	public function getInvitation($invitation_id){
		global $wpdb;

		$query = "SELECT project_id, user_id, status FROM `".$this->tbl_invitation."` WHERE id = %d";
		return $wpdb->get_row($wpdb->prepare($query, $invitation_id), ARRAY_A);
	}

	public function reply($invitation_id, $action, $userid, $remark){
		global $wpdb;

		//check userid
		$query = "SELECT status FROM `".$this->tbl_invitation."` WHERE id = %d AND user_id = %d";
		$invitation_status = $wpdb->get_var($wpdb->prepare($query, $invitation_id, $userid));
		if($invitation_status){
			//invitation exist
			if($invitation_status == "pending"){
				if($action == "rejected"){
					$query = "UPDATE `".$this->tbl_invitation."` SET status = %s WHERE id = %d";
					$wpdb->query($wpdb->prepare($query, $action, $invitation_id));

					$remark_id = 0;
					//also save remark if any
					if($remark){
						$query = "INSERT INTO `".$this->tbl_invitation_response."` ( invitation_id, remark, action, user_id ) VALUES ( %d, %s, %s, %d )";
						$wpdb->query($wpdb->prepare($query, $invitation_id, $remark, $action, $userid));
						$remark_id = $wpdb->insert_id;
					}

					return array(
						"error"=>0,
						"success"=>1,
						"msg"=>"update success",
						"remark_id"=>$remark_id,
						"added"=>0
					);


				}else{
					//accept
					$query = "UPDATE `".$this->tbl_invitation."` SET status = %s WHERE id = %d";
					$wpdb->query($wpdb->prepare($query, $action, $invitation_id));

					//get project_id
					$query = "SELECT project_id FROM `".$this->tbl_invitation."` WHERE id = %d";
					$project_id = $wpdb->get_var($wpdb->prepare($query, $invitation_id));
					return array(
						"error"=>0,
						"success"=>1,
						"msg"=>"update success",
						"added"=>$project_id
					);
				}
			}else{
				return array(
					"error"=>1,
					"success"=>0,
					"msg"=>"invitation is ".$invitation_status.", cannot overwrite"
				);
			}
		}else{
			return array(
				"error"=>1,
				"success"=>0,
				"msg"=>"invitation doesn't exist"
			);
		}
	}

}