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

$get_value = array();
$rslt = '';

$list_key = 'Email Campaign Mailing List Key';
$list_name = 'Campaign Mailling List Name';
$list_email = 'Campaign Mailling List Email';
$recipient = 'Campaign Mailling List Recipients';

	$query = "select * from `Campaign Mailling List` where 1";
	$res = mysql_query($query);
	if(mysql_num_rows($res)>0)
	{
		while($print = mysql_fetch_assoc($res))
		{
			$get_value[] = $print;
		}
	}
	else
	{	
		$rslt = 'No Result is found';
	}

$smarty->assign('parent','home');
$smarty->assign('title', _('Regular Campaign'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('value',$get_value);
$smarty->assign('rslt',$rslt);


//assigning the database fields
$smarty->assign('list_key',$list_key);
$smarty->assign('list_name',$list_name);
$smarty->assign('list_email',$list_email);
$smarty->assign('recipient',$recipient);



$smarty->display('regular_campaign.tpl');
?>
