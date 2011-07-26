<?php
/*

  About: 
  Autor: Migara Ekanayake
 
  Copyright (c) 2011, Inikoo 
 
  Version 2.0
*/

include_once('common.php');

if(!$user->can_view('contacts'))
  exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if(!$modify or!$create){
  exit();
}




$store_key=$_SESSION['state']['customers']['store'];
$smarty->assign('store_key',$store_key);
$store=new Store($store_key);
$smarty->assign('store',$store);



$smarty->assign('store_key',$store_key);
$smarty->assign('scope','customer');


$general_options_list=array();


$general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Go Back'));

$smarty->assign('general_options_list',$general_options_list);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
		 );
include_once('Theme.php');
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/phpjs.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
	    'js/edit_common.js',
		'customer_store_configuration.js.php?store_key='.$store->id
		);



    $view=$_SESSION['state']['customer_store_configuration']['view'];


//print $view;
$smarty->assign('view',$view);
$_SESSION['state']['customer']['view']=$view;

		
		
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers');

$smarty->assign('title','Creating New Customer');
$smarty->display('customer_store_configuration.tpl');




?>

