<?php

if(isset($_POST["default_source"])){
	\storify\stripe::setSource($default_group_id, $_POST["default_source"]);
}

if(isset($_POST["delete_card_id"])){
	\storify\stripe::removeCard($default_group_id, $_POST["delete_card_id"]);
}

if(isset($_POST["request_invoice"])){
	//if request invoice
}