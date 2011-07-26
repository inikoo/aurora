<?php
session_start();
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
require('common_functions.php');
$smarty = new Smarty;
$smarty->assign('current_page_url',curPageURL());
$smarty->assign('current_page',curPage());

$valid_languages=array('en_GB','es_ES');

if(isset($_REQUEST['lang']) and in_array($_REQUEST['lang'],$valid_languages)){
	$language=$_REQUEST['lang'];
	$_SESSION['language']=$language;
}else{
	if(isset($_SESSION['language'])){
	}
	else{
		$language='en_GB';
		$_SESSION['language']=$language;
	}
}

$smarty->assign('language',$_SESSION['language']);


?>