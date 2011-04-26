<?php
/*
 File: marketing.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');

include_once('class.Product.php');
include_once('class.Order.php');

$page='marketing';

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));

$general_options_list[]=array('tipo'=>'url','url'=>'new_email_campaign.php','label'=>_('Create Email Campaign'));
$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
$smarty->assign('general_options_list',$general_options_list);

$view_orders=$user->can_view('Orders');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'css/marketing_campaigns.css',
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
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'marketing_create_campaign.js.php'
		);

	echo $_POST['op'];
	echo $click;
	echo $plain;
	


if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}
$smarty->assign('view',$_SESSION['state'][$page]['view']);

//get the list
$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

	$query = "select `Campaign Mailling List Name`,`Campaign Mailling List Id`,`Campaign Mailling List Default Name`,`Campaign Mailling List Email`,`Campaign Mailling List Recipients`,`Campaign Mailling Track Click`,`Campaign Mailling Track Open` from `Campaign Mailling List` where `Campaign Mailling List Id` = '".$id."'";
	$res = mysql_query($query);
	if(mysql_num_rows($res) > 0)
	{		
		$row = mysql_fetch_array($res);
	}

$list_id = $row['Campaign Mailling List Id'];
$plain = $row['Campaign Mailling Plain Text Click'];
$click = $row['Campaign Mailling Track Click'];
$open = $row['Campaign Mailling Track Open'];
$track = '';
	
        if($plain == 0)
	{
		$track = $track.'Plain Text Click is disabled<br>';
	}
	else
	{
		$track = $track.'Plain Text Click is enabled<br>';
	}

	if($click == 0)
	{
		$track = $track.'Mailing track click is disabled<br>';
	}
	else
	{
		$track = $track.'Mailing track click is enabled<br>';
	}
		
	if($open == 0)
	{
		$track = $track.'Mailing track open is disabled<br>';
	}
	else
	{
		$track = $track.'Mailing track open is enabled<br>';
	}
	
	

$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('list_id',$list_id);

$smarty->assign('list_name',$row['Campaign Mailling List Name']);

$smarty->assign('default_name',$row['Campaign Mailling List Default Name']);

$smarty->assign('email',$row['Campaign Mailling List Email']);

$smarty->assign('recipients',$row['Campaign Mailling List Recipients']);

$smarty->assign('track',$track);



$q='';
$tipo_filter=($q==''?$_SESSION['state'][$page]['email_campaigns']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state'][$page]['email_campaigns']['f_value']:addslashes($q)));
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>'Campaign with name like <i>x</i>','label'=>'Name')
             );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('confirm_mail_send.tpl');

?>
