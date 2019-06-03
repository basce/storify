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

    $formAttributes = null;//$postObject->getFormAttributes();
    $formInputs = null;//$postObject->getFormInputs();

    $cmd = $s3->getCommand(
            'putObject',
            array(
                'Bucket' => 'ncstorifymeprivate',
                'Key' => 'project/12/1/'.$_REQUEST["filename"],
                "ContentType"=>$_REQUEST["mime"]
            )
        );

    $request = $s3->createPresignedRequest($cmd, '+1minutes');

    $url = $request->getUri();

    //$request = $s3->createPresignedRequest($cmd, '+20 minutes');

    $cmd2 = $s3->getCommand('GetObject', array(
        'Bucket'=>'ncstorifymeprivate',
        'Key'=>"project/12/1/".$_REQUEST["filename"]
    ));

    $request2 = $s3->createPresignedRequest($cmd2, '+20minutes');

    $url2 = $request2->getUri();

    echo json_encode(array(
        "error"=>0,
        "inputs"=>$formInputs,
        "attributes"=>$formAttributes,
        "url"=>(string)$url,
        "url2"=>(string)$url2
    ));

   /*
    $cmd = $s3->getCommand('GetObject', array(
        'Bucket'=>'ncstorifymeprivate',
        'Key'=>"images/favicon.ico"
    ));

    $request = $s3->createPresignedRequest($cmd, '+1minutes');

    echo $request->getUri();
        */
    /*
    try{
        $request = $s3->getCommand(
            'PutObject',
            array(
                'Bucket' => 'ncstorifymeprivate',
                'Key' => 'project/12/1/'.$_REQUEST["filename"],
                'ContentType' => $_REQUEST["mime"],
                'Body' => '',
                'ContentMD5' => false
            )
        )->createPresignedUrl("+30 minutes");

        print_r($request); exit();

    } catch ( S3Exception $e ){
        echo $e->getMessage(). "\n";
    }
    echo json_encode(array(
        "error"=>0,
        "url"=>""
    ));
    */
}else{
    echo json_encode(array(
        "error"=>1,
        "url"=>""
    ));
}
