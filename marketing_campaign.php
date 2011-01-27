<?php
/*
 File: marketing.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');


//$page='marketing';

///$general_options_list=array();
//$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));

//$general_options_list[]=array('tipo'=>'url','url'=>'new_email_campaign.php','label'=>_('Create Email Campaign'));
//$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
//$smarty->assign('general_options_list',$general_options_list);

//$view_orders=$user->can_view('Orders');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/marketing_menu.css',
		 'css/marketing_campaign.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'marketing.js.php',
		'js/menu.js',
		'js/jquery-1.4.4.js',
		'js/marketing_ajax.js'
		);

		$sql = "select * from `Email Campaign Dimension`";
		$res = mysql_query($sql);
		$key = array();
		$name = array();
		$obj = array();
		$status = array();
		$email = array();
		$content = array();

	while($fetchArray = mysql_fetch_assoc($res))
	{
		$key = $fetchArray['Email Campaign Key'];
		$name = $fetchArray['Email Campaign Name'];
		$obj = $fetchArray['Email Campaign Objective'];
		$status = $fetchArray['Email Campaign Status'];
		$email = $fetchArray['Email Campaign Maximum Emails'];
		$content = $fetchArray['Email Campaign Content'];

	
	}
		

	$fields = mysql_num_rows($res);
 
if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}
//$smarty->assign('view',$_SESSION['state'][$page]['view']);



$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('key',$key);
$smarty->assign('name',$name);
$smarty->assign('obj',$obj);
$smarty->assign('status',$status);
$smarty->assign('email',$email);
$smarty->assign('content',$content);
$smarty->assign('fields',$fields);


$smarty->display('marketing_campaign.tpl');

?>
