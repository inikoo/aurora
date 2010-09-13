<?php
/*
 File: orders_server.php 

 Orders server when ther is more that one store

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/


include_once('common.php');
if(!$user->can_view('orders'))
  exit();









$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
		 
		 
	$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );	 
		 
	
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
				'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'orders_server.js.php'
		);


$q='';

$general_options_list=array();



$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


if(isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])){
$_SESSION['state']['stores']['orders_view']=$_REQUEST['view'];
}

$smarty->assign('view',$_SESSION['state']['stores']['orders_view']);
$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo_filter0=($q==''?$_SESSION['state']['stores']['orders']['f_field']:'public_id');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['stores']['orders']['f_value']:addslashes($q)));
$filter_menu0=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Store Code starting with  <i>x</i>','label'=>'Code'),
		   
		   );
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);
$smarty->assign('paginator_menu1',$paginator_menu0);
$smarty->assign('paginator_menu2',$paginator_menu0);



$smarty->display('orders_server.tpl');
?>