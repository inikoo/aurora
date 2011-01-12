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
if(count($user->stores)==0){
      header('Location: marketing.php');
}

include_once('class.Product.php');
include_once('class.Order.php');







$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));
$general_options_list[]=array('tipo'=>'url','url'=>'campaign.php?new','label'=>_('Create Campaign'));
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
		 'table.css','css/users.css'
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
		'new_email_campaign.js.php'
		);




$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$store_key=0;
$stores_data=array();
$sql=sprintf("select `Store Key`,`Store Code`,`Store Name` from   `Store Dimension` where `Store Key` in (%s)  ",$store_keys=join(',',$user->stores));
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $stores_data[$row['Store Key']]=array(
    'key'=>$row['Store Key'],
    'name'=>$row['Store Name'],
    'code'=>$row['Store Code']

);
$store_key=$row['Store Key'];
}
$number_stores=count($stores_data);
$smarty->assign('number_stores',$number_stores);
if($number_stores!=1){

$store_key=0;
}
$smarty->assign('store_key',$store_key);
$smarty->assign('stores_data',$stores_data);


$smarty->display('new_email_campaign.tpl');




?>

