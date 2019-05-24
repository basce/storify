<?php
//meta
?>
<title><?=htmlspecialchars($pageSettings["meta"]["name"])?></title>
<meta name="description" content="<?=htmlspecialchars($pageSettings["meta"]["description"])?>">
<link rel="canonical" href="<?=$pageSettings["meta"]["canonical"]?>" />
<meta property="fb:app_id" content="310258729772529" />
<?php // OG 
	if($pageSettings["og"]){
		foreach($pageSettings["og"] as $key=>$value){?>
<meta property="<?=$key?>" content="<?=htmlspecialchars($value, ENT_QUOTES)?>" />
<?php
		}
	}
	if($additional_og_image && sizeof($additional_og_image)){
		foreach($additional_og_image as $key=>$value){?>
<meta property="og:image" content="<?=str_replace("https://", "http://", $value)?>" />
<meta property="og:image:secure_url" content="<?=$value?>" />
<?php			
		}
	}