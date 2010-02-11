<?php
/*
 File: customers.php 

 UI customers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');

if(!$user->can_view('customers')){
  exit();
}


$smarty->assign('box_layout','yui-t0');









$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'search.js',
		'customers_server.js.php'
		);






$smarty->assign('details',$_SESSION['state']['customers']['details']);
$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Customers List'));


$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['customers']['table']['f_value']);

$filter_menu=array(
		   'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>'Customer Name','label'=>'Name'),
		   'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>'Customer Postcode','label'=>'Postcode'),
		   'min'=>array('db_key'=>_('min'),'menu_label'=>'Mininum Number of Orders','label'=>'Min No Orders'),
		   'max'=>array('db_key'=>_('min'),'menu_label'=>'Maximum Number of Orders','label'=>'Max No Orders'),

		   );
$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);




$smarty->assign('plot_tipo',$_SESSION['state']['customers']['plot']);
$smarty->assign('view',$_SESSION['state']['customers']['view']);
$smarty->display('customers_server.tpl');

?>