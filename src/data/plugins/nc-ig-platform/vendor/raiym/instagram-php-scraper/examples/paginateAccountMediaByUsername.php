<?php
require __DIR__ . '/../vendor/autoload.php';


// getPaginateMedias() work with and without authorization
$instagram = \InstagramScraper\Instagram::withCredentials('username', 'password', '/path/to/cache/folder');
$instagram->login();

$result = $instagram->getPaginateMedias('kevin');
$medias = $result['medias'];
if ($result['hasNextPage'] === true) {
    $result = $instagram->getPaginateMedias('kevin', $result['maxId']);
    $medias = array_merge($medias, $result['medias']);
}

echo json_encode($medias);