<?php
$not_found_current_page=$_REQUEST['original_url'];

if(preg_match('/\.(jpg|png|gif|xml|txt|ico|css|js)$/i',$not_found_current_page)){
	header("HTTP/1.0 404 Not Found");
	exit();
}

//print_r($_REQUEST);


include_once('common.php');
$page_key=$site->get_not_found_page_key();


//exit("::: Not found");

include_once('page.php');
?>