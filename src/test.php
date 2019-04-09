<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("inc/class.main.php");

$main = new main();


$tempname = date("j M Y - His");
$project_id = 6; //$main->getProjectManager()->createNewProject("new project ".$tempname, 1, 0);

//save 
/*
$main->getProjectManager()->save(
	$project_id,
	array(
		"detail"=>array(
			"name"=>"new project ".$tempname,
			"description_brief"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultrices imperdiet risus. Pellentesque at lacus vel dolor faucibus varius. Vestibulum quis ultricies leo, quis dictum arcu. Nullam ac felis at ligula pellentesque pretium. Etiam nec suscipit nulla. Mauris venenatis suscipit augue, at sodales turpis pharetra vitae. Pellentesque suscipit orci vel magna tristique maximus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin eu felis vitae risus mattis dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum tincidunt fringilla. Pellentesque in laoreet quam. Maecenas a arcu nunc. Duis suscipit dignissim posuere. Curabitur id nulla tempor, placerat ligula ut, vulputate tellus.

Vivamus nec gravida massa. Nulla non dapibus diam. Aenean et quam eu quam maximus convallis eu a justo. Nulla nulla metus, aliquet sed quam a, ultrices aliquam elit. Nullam in diam congue, lacinia ligula sed, tempus lectus. Phasellus erat augue, scelerisque non feugiat ut, rutrum vitae neque. Donec porta placerat tortor, nec bibendum mi rutrum pharetra.

Quisque id leo justo. Vestibulum dui mauris, venenatis non dolor a, finibus finibus libero. Nulla quis varius elit. Duis sem erat, vehicula gravida ornare quis, posuere eu nibh. Aliquam interdum elit in nibh finibus, quis aliquet velit eleifend. Morbi varius sapien et neque consectetur gravida. Aenean pulvinar mi ipsum, eget malesuada eros auctor ut. Pellentesque vitae porta mauris, a viverra tortor. Vestibulum feugiat lorem molestie facilisis ullamcorper. Donec lorem tortor, ultrices quis lorem vitae, malesuada auctor nisl. Phasellus tempus pharetra dictum.

Maecenas quis augue vitae nisl porttitor feugiat a at massa. Aliquam finibus nisl eget bibendum interdum. Nam posuere mauris enim, at tempus diam ultrices quis. Sed tempor tortor tempus, faucibus nibh non, ullamcorper sapien. Vivamus et ligula rhoncus, euismod eros vel, lacinia lorem. Cras tellus mi, porta aliquet erat vitae, placerat blandit nisi. Nunc egestas condimentum pharetra. Proin eget sapien id lectus tristique ullamcorper. Integer sit amet sem sit amet lorem ultrices ornare. Morbi metus lorem, rhoncus vel lectus a, venenatis imperdiet nibh.

Donec at eros convallis, pulvinar tortor ac, vestibulum orci. Proin sed faucibus dui. Aliquam sed risus ut ipsum dapibus tempus. Maecenas tellus odio, iaculis sed libero id, imperdiet porttitor justo. Ut lacinia ut diam faucibus tincidunt. Ut eu imperdiet arcu. Curabitur auctor augue ut orci luctus varius. Vivamus condimentum lectus at quam malesuada, in vulputate nisi fringilla. Nam molestie mi eget erat semper efficitur. Phasellus ipsum augue, rutrum ac velit quis, bibendum fermentum lacus. Maecenas a dui faucibus, commodo leo quis, ultricies erat. Nunc varius diam sit amet venenatis tempor.",
			"deliverable_brief"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec felis vel metus placerat laoreet. Proin malesuada, magna eget condimentum bibendum, metus enim dictum justo, et malesuada justo quam ullamcorper velit. Donec pharetra lorem turpis, ut ultricies quam convallis eget. Duis accumsan finibus nibh pulvinar mollis. Maecenas luctus dapibus erat, sed lacinia nisi. Nulla euismod tempor pharetra. Nullam nec pellentesque lectus. Morbi massa nulla, pretium eget euismod sit amet, blandit in dui. Nullam pretium ut ligula vitae sollicitudin. Aenean ut dictum risus, in fringilla massa. Vestibulum viverra rhoncus orci sed sollicitudin. Morbi condimentum mattis tempor. Maecenas id dictum metus, a suscipit mi. Nunc laoreet pharetra porttitor. Sed vitae pulvinar mi. Quisque volutpat lacinia vulputate.

Maecenas luctus, nisl ac facilisis tempus, turpis massa vehicula mauris, at tempus sem felis eu massa. Proin gravida pretium nisi, faucibus commodo sapien. Sed in lacus malesuada, fringilla nulla eget, ornare neque. Suspendisse viverra ornare est, a ornare nibh efficitur eleifend. Nullam a aliquet mauris. Suspendisse egestas nisl ac quam euismod, quis ornare metus consectetur. Nam at est lacus. Aenean cursus, turpis nec elementum elementum, lacus ante luctus lectus, eu auctor ex ex ac libero. Fusce felis mi, placerat et euismod ac, consectetur vel massa. Sed eu metus eget metus gravida placerat. Suspendisse eu erat sit amet mi tincidunt pellentesque. Nulla nec convallis turpis. Nam gravida, enim ac placerat lacinia, augue ex imperdiet est, vitae consectetur lorem purus id magna. Ut non lorem a turpis blandit eleifend vel vel magna. Pellentesque odio ipsum, pretium vitae augue ac, feugiat dignissim est. Quisque in lectus blandit, maximus massa id, lacinia tellus.

Duis vel nisi et nisl tempus auctor. Pellentesque vitae nisl sit amet odio maximus mollis. Fusce ut eros sapien. Sed eu odio et orci aliquet sagittis. Suspendisse accumsan ante eu est aliquam, vel gravida nulla dapibus. Nunc suscipit convallis sodales. Vestibulum turpis purus, euismod ut tempor in, efficitur a ante. Praesent quam turpis, rhoncus vel egestas semper, rutrum quis elit. Maecenas sit amet vulputate ex. Nulla ante sapien, vehicula vel sapien pretium, egestas condimentum ex. Sed egestas lacus non odio ullamcorper finibus sed nec sem. Nullam in aliquet augue. Donec fringilla felis sed dapibus imperdiet.",
			"other_brief"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi non risus sed eros laoreet tincidunt ac at urna. Donec ut placerat nisi. Proin ac orci et quam facilisis iaculis. In ultrices nisl et diam pharetra mollis. Maecenas ac enim ut arcu dictum ultricies et eu arcu. Aliquam libero felis, placerat nec massa quis, convallis volutpat lacus. Fusce non enim imperdiet, placerat augue quis, consequat libero. Sed vehicula non risus vitae dignissim. Quisque id imperdiet tortor. Nullam euismod vitae lacus tristique efficitur. Duis tincidunt finibus massa. Quisque at egestas metus, id sollicitudin nisi. Fusce efficitur maximus volutpat. Etiam pretium risus eu tristique rhoncus.",
			"short_description"=>"",
			"no_of_photo"=>mt_rand(2,5),
			"no_of_video"=>mt_rand(0,3),
			"bounty_type"=>"both",
			"cost_per_photo"=>mt_rand(50,100),
			"cost_per_video"=>mt_rand(100,150),
			"video_length"=>30,
			"reward_name"=>"Free Sample",
			"closing_date"=>"2019-04-11 00:00:00",
			"invitation_closing_date"=>"2019-03-01 00:00:00"
		),
		"deliverable"=>array(
			
		),
		"brand"=>array(
			118
		),
		"location"=>array(
			4
		),
		"tag"=>array(
			3,17
		)
	)
);*/

//print_r($main->getProjectManager()->getCreatorInvitationList(20));

//print_r($main->getProjectManager()->getProjectDetail(4,20));

//get users in project
//print_r($main->getProjectManager()->getUsers(1));

//get invitation list
//print_r($main->getProjectManager()->getInvitationList(1));

//respond invitation
//print_r($main->getProjectManager()->invitation_response(4, "accepted", 20, ""));
//print_r($main->getProjectManager()->invitation_response(3, "rejected", 34, "not free lor"));

//set invitation, batch
//$result = $main->getProjectManager()->setInvitationBatch($project_id, array(20,34));

//resend invitation, with only one user ID
//print_r($main->getProjectManager()->setInvitation($project_id, 34));

//get project list, pagination ?
//$result = $main->getProjectManager()->getProjectList(20,"",24,1);

//get single project detail
//$result = $main->getProjectManager()->getProjectDetail(1, 20);

//get deliverables
//print_r($main->getProjectManager()->getDeliverables(1, 1));

//get deliverable history
//print_r($main->getProjectManager()->getDeliverablesHistory(5, 20));

//make submission
//print_r($main->getProjectManager()->submission_submit(5, 20, "https://sample_link2", "instruction on how to download 2"));

//print_r($main->getProjectManager()->submission_admin_response(2, 1, "accepted", "no remark"));

//set sample
//print_r($main->getProjectManager()->addSample("https://cdn.storify.me/data/uploads/2019/01/ig1960910849516109141.jpg",$project_id));

//get sample
//print_r($main->getProjectManager()->getSample(1));

//remove sample
//print_r($main->getProjectManager()->removeSample(1));

//get all instagrammer

//print_r("testing");
/*
$instagram = new \InstagramScraper\Instagram();
try{
    $media = $instagram->getMedias('basce', 30, '');
    print_r($media);
}catch(Exception $e){
    $media = array();
    print_r($e);
    die("Instagram Error, pulling posts fail, refresh and try again");
}

try{
$result = wp_delete_attachment(32996, true);
print_r($result);
}catch(Exception $e){
	}
*/

//get the latest 30 offset the last post
/*
$current_user = wp_get_current_user();

$query = array(
	"limit"=>31,
	"offset"=>1,
	"where"=>"instagrammer.id = 754",
	"orderby"=>"post_created_time DESC"
);

$post_pods = pods("instagram_post_fast", $query);
print_r("total : ".$post_pods->total()."\n");
if(0 < $post_pods->total()){
	while($post_pods->fetch()){
		print_r($post_pods->field("post_created_time")."\n");
	}
}
*/

//add items to book mark
//print_r($main->getAllTagsInUsed(true));
/*
print_r($main->addToGroup(673, 'people', 1));
print_r($main->addToGroup(668, 'people', 1));
print_r($main->addToGroup(674, 'people', 1));
print_r($main->addToGroup(675, 'people', 1));
print_r($main->addToGroup(676, 'people', 1));
*/
?>