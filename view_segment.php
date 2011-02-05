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

include_once('class.Product.php');
include_once('class.Order.php');

//$page='marketing';

/*$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));

$general_options_list[]=array('tipo'=>'url','url'=>'new_email_campaign.php','label'=>_('Create Email Campaign'));
$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
$smarty->assign('general_options_list',$general_options_list);*/

//$view_orders=$user->can_view('Orders');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'css/marketing_campaign.css',
		 'table.css'
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
		'marketing_create_campaign.js.php',
		'js/add_condition.js'
		);


 
/*if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}*/
//$smarty->assign('view',$_SESSION['state'][$page]['view']);

$getValue = array();


if(isset($_REQUEST['segID']))
{
	
	$sqlQuery = "select `Email People Key`,`People Email`,`People Last Name`,`People First Name`,`People Email Type` from `Email People Dimension` where `People Email` = '".$_REQUEST['segID']."' OR `People First Name` = '".$_REQUEST['segID']."' OR `People Last Name` = '".$_REQUEST['segID']."' ";
	$res = mysql_query($sqlQuery);	
	while($getRow = mysql_fetch_assoc($res))
	{
		$getValue[] = $getRow;
	}
	
}


$people_key = 'Email People Key';
$people_email = 'People Email';
$people_fname = 'People First Name';
$people_lname = 'People Last Name';
$people_type = 'People Email Type';



$smarty->assign('parent','home');
$smarty->assign('title', _('Segment'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('value',$getValue);

//assigning the database fields
$smarty->assign('people_key',$people_key);
$smarty->assign('people_fname',$people_fname);
$smarty->assign('people_email',$people_email);
$smarty->assign('people_lname',$people_lname);
$smarty->assign('people_type',$people_type);




$smarty->display('view_segment.tpl');
?>
