<?php
namespace storify\project;

class summary{

	private static $instance = null;

	private $tbl_summary;

	// Construct initial value

	private function __construct(){
		global $wpdb;

		if(!$wpdb){
			die("wpdb missing, change to other connection method if you are not using wordpress.");
		}

		$this->tbl_summary = $wpdb->prefix."20project_summary";
	}

	public static function getInstance(){
		if( self::$instance == null ){
			self::$instance = new summary();
		}

		return self::$instance;
	}

	public function getSummaryTable(){
		return $this->tbl_summary;
	}

	/**
	 * 
	 * 	crete summary
	 * 	user_id 0 - brand
	 * 			other - creator_id
	 * 
	 **/

/*
{
	name:"",
	location:[
		{
			term_id:0,
			name:""
		},
		{
			term_id:0,
			name:""
		}
	],
	tag:[
		{
			term_id:0,
			name:""
		},
		{
			term_id:0,
			name:""
		}
	],
	brand:[
		{
			term_id:0,
			name:""
		}
	],
	description:"",
	creators:[
	 // get from getusersByAdmin
	],
	task:{
		data:[
			{
				id:0,
				img_url:"" // require json
				submission_closing_date:"",
				name:"",
				number_of_video:0
				number_of_photo:0,
				instruction:"",
				post:0,
				posting_date:"",
				post_instruction:""
				created_time
			}
		],
		stats:{
			"total":0
		}
	},
	submission:{
		data:[
			{
				id:1,
				type:"igpost",
				task_id:1,
				project_id:1,
				user_id:1,
				data:{
					files:[
						1,2,3,4
					],
					caption:""
				},
				msg:"",
				tt:"2019-01-01 00:00:00"
				status:"pending",
				response_msg:"",
				response_tt:"2019-01-01 00:00:00",
				response_by_who:1
			},
			{
				id:2,
				type:"igpost",
				task_id:1,
				project_id:1,
				user_id:1,
				data:{
					files:[
						2,3,4,5
					],
					caption:""
				},
				msg:"",
				tt:"2019-01-01 00:00:00",
				status:"pending",
				response_msg:"",
				response_tt:"2019-01-01 00:00:00",
				response_by_who:1
			}
		],
		stats:{
			expect:2,
			receive:2,
			accept:2,
			reject:2,
			pending:2
		}
	}
}
*/	

	public function insertSummary($project_id, $summary_obj, $user_id = 0){
		global $wpdb;

		$data = $summary_obj;

		$query = "SELECT COUNT(*) FROM `".$tbl_summary."` WHERE project_id = %d AND user_id = %d";
		if($wpdb->get_var($wpdb->prepare($query, $project_id, $user_id))){
			// summary already exist
			$original = $this->getSummary($project_id, $user_id);

			$data = $this->updateSummaryParam($original, $data);
		}

		$query = "INSERT INTO `".$tbl_submission."` ( project_id, user_id, data ) VALES ( %d, %d, %s )";
		$wpdb->query( $wpdb->prepare( $query, $project_id, $user_id, $data ) );

		return $wpdb->insert_id;
	}

	public function getSummary($project_id, $user_id){
		global $wpdb;

		$query = "
			SELECT a1.data FROM `".$this->tbl_summary."` a1
			LEFT OUTER JOIN `".$this->tbl_summary."` a2
			ON a1.project_id = a2.project_id AND a1.user_id = a2.user_id AND a1.tt < a2.tt
			WHERE a1.project_id IS NULL AND a1.project_id = %d AND a1.user_id = %d
		";

		return json_decode( $wpdb->get_var($wpdb->prepare( $query, $project_id, $user_id )), true );
	}
}