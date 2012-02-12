<?php
include_once('common.php');
$page_key=$site->get_home_page_key();

if(!$page_key){
	print "Error";
}else{
include_once('page.php');
}
?>