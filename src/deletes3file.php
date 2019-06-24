<?php  
include("inc/main.php");

if(isset($_REQUEST["filename"])){
    $s3 = new Aws\S3\S3Client(array(
        'region'=>'ap-southeast-1',
        'version'=>'latest',
        'credentials'=>array(
            'key'=> AS3CF_AWS_ACCESS_KEY_ID,
            "secret"=> AS3CF_AWS_SECRET_ACCESS_KEY
        )
    ));

    /*
    $postObject = new Aws\S3\PostObjectV4(
        $s3,
        'ncstorifymeprivate',
        array(),
        array(
            'bucket'=>'ncstorifymeprivate',
            'starts-with'=>'projects/1/user_1/'
        ),
        '+30 minutes'
    );
    */
   
    $result = $s3->deleteObject(array(
        "Bucket" => 'ncstorifymeprivate',
        "Key" => 'project/12/1/'.$_REQUEST["filename"]
    ));

    print_r($result);

}else{
    print_r("require filename");
}
