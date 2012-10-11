<?php
include_once('common.php');
//print_r($_REQUEST);
if($_SESSION['logged_in']){
$page_key=$site->get_profile_page_key();
}else{
$page_key=$site->get_login_page_key();
}
//$page_key=$site->get_login_page_key();

include_once('page.php');
?>