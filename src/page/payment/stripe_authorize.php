<?php  
print_r($_SESSION["state"]);
print_r($_GET["state"]);

$code = $_GET["code"];
$secret = "pk_test_fsS731f6bRJ5kJa5HrWJRsOu00A5NmWP6i";

$ch = curl_init();

$data = array(
	"client_secret"=>$secret,
	"code"=>$code,
	"grant_type"=>"authorization_code"
);

curl_setopt($ch, CURLOPT_URL, 'https://connect.stripe.com/oauth/token');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

curl_close($ch);

print_r($response);

?>