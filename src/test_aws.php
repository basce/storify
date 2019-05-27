<?php  
include("inc/main.php");

$s3 = new Aws\S3\S3Client(array(
	'region'=>'ap-southeast-1',
	'version'=>'latest',
	'credentials'=>array(
		'key'=> "AKIAZFEKE3OJGMG3PUW4",
		"secret"=>"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"
	)
));

$cmd = $s3->getCommand('GetObject', array(
	'Bucket'=>'ncstorifymeprivate',
	'Key'=>"images/favicon.ico"
));

$request = $s3->createPresignedRequest($cmd, '+1minutes');

echo $request->getUri();

/*
//upload example

$s3 = new Aws\S3\S3Client(array(
	'region'=>'ap-southeast-1',
	'version'=>'latest',
	'credentials'=>array(
		'key'=> "AKIAZFEKE3OJGMG3PUW4",
		"secret"=>"FiZ033OKWYtoSb96BrQvWOavS/zof/c2i7Lb4gz5"
	)
));

$result = $s3->putObject(array(
	'Bucket'=>'ncstorifymeprivate',
	'Key'=>"images/favicon.ico", //folder is set here
	'SourceFile'=>__dir__."/favicon.ico"
));

print_r($result);

result :
Aws\Result Object
(
    [data:Aws\Result:private] => Array
        (
            [Expiration] => 
            [ETag] => "c97e0a806d7858a7eaf8c6b5fb1ccd76"
            [ServerSideEncryption] => 
            [VersionId] => 
            [SSECustomerAlgorithm] => 
            [SSECustomerKeyMD5] => 
            [SSEKMSKeyId] => 
            [RequestCharged] => 
            [@metadata] => Array
                (
                    [statusCode] => 200
                    [effectiveUri] => https://ncstorifymeprivate.s3.ap-southeast-1.amazonaws.com/images/favicon.ico
                    [headers] => Array
                        (
                            [x-amz-id-2] => 5EiXFPPo/n3toQlVd6O8o7WTI0aEZ5mDeZ1dF03kdyBa2uYdlmTaAaLHBhZ3aOYm8r+UN74dHqg=
                            [x-amz-request-id] => 90C0AFD40AAE8F7E
                            [date] => Fri, 24 May 2019 09:16:54 GMT
                            [etag] => "c97e0a806d7858a7eaf8c6b5fb1ccd76"
                            [content-length] => 0
                            [server] => AmazonS3
                        )

                    [transferStats] => Array
                        (
                            [http] => Array
                                (
                                    [0] => Array
                                        (
                                        )

                                )

                        )

                )

            [ObjectURL] => https://ncstorifymeprivate.s3.ap-southeast-1.amazonaws.com/images/favicon.ico
        )

    [monitoringEvents:Aws\Result:private] => Array
        (
        )

)
*/