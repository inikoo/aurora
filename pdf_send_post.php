<?php
include_once('common.php');
$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);


	$mailList = array();	
	$mailList = $_REQUEST['check'];
	//print_r($mailList);die();

	//foreach($mailList as $k=>$v){
	//echo "<script language=\"JavaScript\">alert('hiiiiiiii');</script>";
       // header('location:pdf_send_post_customer.php?id='.$v.'');
       //}
	$data = join($mailList, ",");
	header('location:pdf_send_post_customer.php?id='.$data.'');

?>
