<?php
//iglogin
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use \InstagramScraper\Instagram as Instagram;
use \InstagramScraper\Exception\InstagramNotFoundException as InstagramNotFoundException;
use storify\job as job;

if(isset($_GET["error"])){
    header("Location: /user@".$current_user->ID."/social/?error=".$_GET["error"]);
    exit();
}else if(isset($_GET["code"])){
    //check
    try{
        $ch = curl_init();

        $data = array(
            "client_id"=>'cb5c39433c444e3fb8161f72e632ea19',
            "client_secret"=>'e3f9cc618e6d41998e253ddd9efef96b',
            "grant_type"=>"authorization_code",
            "redirect_uri"=>get_home_url()."/iglogin/",
            "code"=>$_GET["code"]
        );
        curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        curl_close($ch);

        $igData = json_decode($response, true);

        if(isset($igData["error_message"])){
            header("Location: /user@".$current_user->ID."/showcase/?error=".$igData["error_message"]);
            exit();
        }else if(isset($igData["user"]) && isset($igData["user"]["username"]) ){
            $igname = $main->setUserIGAccount($current_user->ID, $igData["user"]["username"]);

            $instagram = new Instagram();
            $account = $instagram->getAccount($igname);

            $fullName = $account["fullName"] ? $account["fullName"] : $current_user->display_name;
            $profileImage = isset($account["profilePicUrlHd"]) && $account["profilePicUrlHd"] ? $account["profilePicUrlHd"] : $account["profilePicUrl"];

            $tempobj = array(
                "name"=>$fullName,
                "ig_id"=>$account["id"],
                "igusername"=>$account["username"],
                "ig_profile_pic"=>$profileImage,
                "biography"=>$account["biography"],
                "media_count"=>$account["mediaCount"],
                "follows_by_count"=>$account["followsCount"],
                "external_url"=>$account["externalUrl"]
            );
            
            $result = $main->copyFileToWP($profileImage, $fullName, $igname);

            $query = "SELECT id FROM `".$wpdb->prefix."pods_instagrammer_fast` WHERE ig_id = %s";
            $prepare = $wpdb->prepare($query, $account["id"]);
            $instagrammer_id = $wpdb->get_var($prepare);

            $pod = pods("instagrammer_fast");
            $data = array(
                "name"=>$fullName,
                "ig_id"=>$account["id"],
                "igusername"=>$account["username"],
                "biography"=>$account["biography"],
                "media_count"=>$account["mediaCount"],
                "follows_count"=>$account["followsCount"],
                "follows_by_count"=>$account["followedByCount"],
                "external_url"=>$account["externalUrl"],
                "ig_profile_pic"=>array(
                    "id"=>$result["media_id"],
                    "title"=>$fullName
                ),
                "hidden"=>1,
                "verified"=>1
            );

            update_user_meta($current_user->ID, 'profile_pic', $result["media_id"]);

            $user_country_ar = array(
                "AF" => "Afghanistan",
                "AL" => "Albania",
                "DZ" => "Algeria",
                "AS" => "American Samoa",
                "AD" => "Andorra",
                "AO" => "Angola",
                "AI" => "Anguilla",
                "AQ" => "Antarctica",
                "AG" => "Antigua and Barbuda",
                "AR" => "Argentina",
                "AM" => "Armenia",
                "AW" => "Aruba",
                "AU" => "Australia",
                "AT" => "Austria",
                "AZ" => "Azerbaijan",
                "BS" => "Bahamas",
                "BH" => "Bahrain",
                "BD" => "Bangladesh",
                "BB" => "Barbados",
                "BY" => "Belarus",
                "BE" => "Belgium",
                "BZ" => "Belize",
                "BJ" => "Benin",
                "BM" => "Bermuda",
                "BT" => "Bhutan",
                "BO" => "Bolivia",
                "BA" => "Bosnia and Herzegovina",
                "BW" => "Botswana",
                "BV" => "Bouvet Island",
                "BR" => "Brazil",
                "IO" => "British Indian Ocean Territory",
                "BN" => "Brunei Darussalam",
                "BG" => "Bulgaria",
                "BF" => "Burkina Faso",
                "BI" => "Burundi",
                "KH" => "Cambodia",
                "CM" => "Cameroon",
                "CA" => "Canada",
                "CV" => "Cape Verde",
                "KY" => "Cayman Islands",
                "CF" => "Central African Republic",
                "TD" => "Chad",
                "CL" => "Chile",
                "CN" => "China",
                "CX" => "Christmas Island",
                "CC" => "Cocos (Keeling) Islands",
                "CO" => "Colombia",
                "KM" => "Comoros",
                "CG" => "Congo",
                "CD" => "Congo, the Democratic Republic of the",
                "CK" => "Cook Islands",
                "CR" => "Costa Rica",
                "CI" => "Cote D'Ivoire",
                "HR" => "Croatia",
                "CU" => "Cuba",
                "CY" => "Cyprus",
                "CZ" => "Czech Republic",
                "DK" => "Denmark",
                "DJ" => "Djibouti",
                "DM" => "Dominica",
                "DO" => "Dominican Republic",
                "EC" => "Ecuador",
                "EG" => "Egypt",
                "SV" => "El Salvador",
                "GQ" => "Equatorial Guinea",
                "ER" => "Eritrea",
                "EE" => "Estonia",
                "ET" => "Ethiopia",
                "FK" => "Falkland Islands (Malvinas)",
                "FO" => "Faroe Islands",
                "FJ" => "Fiji",
                "FI" => "Finland",
                "FR" => "France",
                "GF" => "French Guiana",
                "PF" => "French Polynesia",
                "TF" => "French Southern Territories",
                "GA" => "Gabon",
                "GM" => "Gambia",
                "GE" => "Georgia",
                "DE" => "Germany",
                "GH" => "Ghana",
                "GI" => "Gibraltar",
                "GR" => "Greece",
                "GL" => "Greenland",
                "GD" => "Grenada",
                "GP" => "Guadeloupe",
                "GU" => "Guam",
                "GT" => "Guatemala",
                "GN" => "Guinea",
                "GW" => "Guinea-Bissau",
                "GY" => "Guyana",
                "HT" => "Haiti",
                "HM" => "Heard Island and Mcdonald Islands",
                "VA" => "Holy See (Vatican City State)",
                "HN" => "Honduras",
                "HK" => "Hong Kong",
                "HU" => "Hungary",
                "IS" => "Iceland",
                "IN" => "India",
                "ID" => "Indonesia",
                "IR" => "Iran, Islamic Republic of",
                "IQ" => "Iraq",
                "IE" => "Ireland",
                "IL" => "Israel",
                "IT" => "Italy",
                "JM" => "Jamaica",
                "JP" => "Japan",
                "JO" => "Jordan",
                "KZ" => "Kazakhstan",
                "KE" => "Kenya",
                "KI" => "Kiribati",
                "KP" => "Korea, Democratic People's Republic of",
                "KR" => "Korea, Republic of",
                "KW" => "Kuwait",
                "KG" => "Kyrgyzstan",
                "LA" => "Lao People's Democratic Republic",
                "LV" => "Latvia",
                "LB" => "Lebanon",
                "LS" => "Lesotho",
                "LR" => "Liberia",
                "LY" => "Libyan Arab Jamahiriya",
                "LI" => "Liechtenstein",
                "LT" => "Lithuania",
                "LU" => "Luxembourg",
                "MO" => "Macao",
                "MK" => "Macedonia, the Former Yugoslav Republic of",
                "MG" => "Madagascar",
                "MW" => "Malawi",
                "MY" => "Malaysia",
                "MV" => "Maldives",
                "ML" => "Mali",
                "MT" => "Malta",
                "MH" => "Marshall Islands",
                "MQ" => "Martinique",
                "MR" => "Mauritania",
                "MU" => "Mauritius",
                "YT" => "Mayotte",
                "MX" => "Mexico",
                "FM" => "Micronesia, Federated States of",
                "MD" => "Moldova, Republic of",
                "MC" => "Monaco",
                "MN" => "Mongolia",
                "MS" => "Montserrat",
                "MA" => "Morocco",
                "MZ" => "Mozambique",
                "MM" => "Myanmar",
                "NA" => "Namibia",
                "NR" => "Nauru",
                "NP" => "Nepal",
                "NL" => "Netherlands",
                "AN" => "Netherlands Antilles",
                "NC" => "New Caledonia",
                "NZ" => "New Zealand",
                "NI" => "Nicaragua",
                "NE" => "Niger",
                "NG" => "Nigeria",
                "NU" => "Niue",
                "NF" => "Norfolk Island",
                "MP" => "Northern Mariana Islands",
                "NO" => "Norway",
                "OM" => "Oman",
                "PK" => "Pakistan",
                "PW" => "Palau",
                "PS" => "Palestinian Territory, Occupied",
                "PA" => "Panama",
                "PG" => "Papua New Guinea",
                "PY" => "Paraguay",
                "PE" => "Peru",
                "PH" => "Philippines",
                "PN" => "Pitcairn",
                "PL" => "Poland",
                "PT" => "Portugal",
                "PR" => "Puerto Rico",
                "QA" => "Qatar",
                "RE" => "Reunion",
                "RO" => "Romania",
                "RU" => "Russian Federation",
                "RW" => "Rwanda",
                "SH" => "Saint Helena",
                "KN" => "Saint Kitts and Nevis",
                "LC" => "Saint Lucia",
                "PM" => "Saint Pierre and Miquelon",
                "VC" => "Saint Vincent and the Grenadines",
                "WS" => "Samoa",
                "SM" => "San Marino",
                "ST" => "Sao Tome and Principe",
                "SA" => "Saudi Arabia",
                "SN" => "Senegal",
                "CS" => "Serbia and Montenegro",
                "SC" => "Seychelles",
                "SL" => "Sierra Leone",
                "SG" => "Singapore",
                "SK" => "Slovakia",
                "SI" => "Slovenia",
                "SB" => "Solomon Islands",
                "SO" => "Somalia",
                "ZA" => "South Africa",
                "GS" => "South Georgia and the South Sandwich Islands",
                "ES" => "Spain",
                "LK" => "Sri Lanka",
                "SD" => "Sudan",
                "SR" => "Suriname",
                "SJ" => "Svalbard and Jan Mayen",
                "SZ" => "Swaziland",
                "SE" => "Sweden",
                "CH" => "Switzerland",
                "SY" => "Syrian Arab Republic",
                "TW" => "Taiwan, Province of China",
                "TJ" => "Tajikistan",
                "TZ" => "Tanzania, United Republic of",
                "TH" => "Thailand",
                "TL" => "Timor-Leste",
                "TG" => "Togo",
                "TK" => "Tokelau",
                "TO" => "Tonga",
                "TT" => "Trinidad and Tobago",
                "TN" => "Tunisia",
                "TR" => "Turkey",
                "TM" => "Turkmenistan",
                "TC" => "Turks and Caicos Islands",
                "TV" => "Tuvalu",
                "UG" => "Uganda",
                "UA" => "Ukraine",
                "AE" => "United Arab Emirates",
                "GB" => "United Kingdom",
                "US" => "United States",
                "UM" => "United States Minor Outlying Islands",
                "UY" => "Uruguay",
                "UZ" => "Uzbekistan",
                "VU" => "Vanuatu",
                "VE" => "Venezuela",
                "VN" => "Viet Nam",
                "VG" => "Virgin Islands, British",
                "VI" => "Virgin Islands, U.s.",
                "WF" => "Wallis and Futuna",
                "EH" => "Western Sahara",
                "YE" => "Yemen",
                "ZM" => "Zambia",
                "ZW" => "Zimbabwe"
            );

            $current_user_meta = get_user_meta($current_user->ID);
    
            // get country name
            $country_label = "";
            foreach($user_country_ar as $key=>$value){
                if(sizeof( $current_user_meta["city_country"]) && ($key == $current_user_meta["city_country"][0])){
                    $country_label = $value;
                }
            }

            $country_pod = pods('instagrammer_country');
            $params = array(
                'where'=>'UPPER(t.name) = UPPER("'.$country_label.'")'
            );
            $country_pod->find($params);
            $tempobj = NULL;
            if($country_pod->total()){
                while($country_pod->fetch()){
                    $tempobj = array(
                        "id"=>$country_pod->field("term_id"),
                        "name"=>$country_pod->field("name"),
                        "hidden"=>sizeof($country_pod->field("hidden"))?$country_pod->field("hidden"):0
                    );
                }
            }
            if($tempobj){
                //item exist
                $temp_id = $tempobj["id"];
            }else{
                //item not exist
                $temp_id = $pod->add(array(
                    "name"=>$country_label,
                    "hidden"=>0
                ));
            }

            // check if country category exist, else create
            // connect with the account
            
            if($instagrammer_id){
                $pod->save($data, null, $instagrammer_id);
            }else{
                $data["display_image"] = array(
                    "id"=>$result["media_id"],
                    "title"=>$fullName
                );
                $instagrammer_id = $pod->add($data);
            }

            //add country tag
            $pod2 = pods("instagrammer_fast", $instagrammer_id);
            $pod2->add_to("instagrammer_country", $temp_id);

            $main->updateSingleIgerOnElasticSearch($account["username"]);

            //check if passive job exist, send email if yes.
            $result = job::getPassiveJob($current_user->ID, "waiting_for_ig");
            if(sizeof($result)){

                /*
                $email_result = $main->sendLambdaBatchEmail(
                    array(
                        array(
                            "to"=>array(
                                "name"=>$current_user->display_name,    
                                "email"=>$current_user->user_email
                            ),
                            "data"=>array(
                                "first_name"=>$current_user->first_name ? $current_user->first_name : $current_user->display_name,
                                "igusername"=>$igname,
                                "social_showcase_page_link"=>get_home_url()."/user@".$current_user->ID."/showcase",
                                "invited_project_page_link"=>get_home_url()."/user@".$current_user->ID."/projects/invited",
                                "text_preview"=>"Hey ".( $current_user->first_name ? $current_user->first_name : $current_user->display_name )." (@".$igname."), Your Instagram account has been linked successfully."
                            )
                        )
                    ), 
                    array(
                        "name"=>"Storify",
                        "email"=>"hello@storify.me"
                    ),
                    "storify_connected_with_ig"
                );
                */

                job::add($current_user->ID, "ig_connect", array(  // add job 
                    "userid"=>$current_user->ID
                ), 0); // execute as soon as possible

                job::updatePassiveJob($result["id"], "complete"); //
            }
            
            header("Location: /user@".$current_user->ID."/showcase/".$igname);
            exit();
        }else{
            header("Location: /user@".$current_user->ID."/showcase/?error=IG%20Error,username%20is%20empty");
            exit();
        }
    }catch(Exception $e){
        if($e instanceof InstagramNotFoundException){
            $error_msg = $e->getMessage();
        }else{
            $error_msg = $e->getMessage();
        }
        header("Location: /user@".$current_user->ID."/showcase/?error=".$error_msg);
        exit();
    }
}

?>