<?php
/*

  About: 
  Autor: Migara Ekanayake
 
  Copyright (c) 2011, Inikoo 
 
  Version 2.0
*/

include_once('common.php');
$create=$user->can_create('warehouses');
$modify=$user->can_edit('warehouses');
$smarty->assign('view_parts',$user->can_view('parts'));


if(!$modify or!$create){
  exit();
}

$general_options_list=array();

$general_options_list[]=array('tipo'=>'url','url'=>'warehouses.php','label'=>_('Go Back'));

$smarty->assign('general_options_list',$general_options_list);
$view=$_SESSION['state']['warehouses']['view'];
$smarty->assign('view',$view);

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
$css_files[]='theme.css.php';
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
		'part_configuration.js.php'
		);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');

$smarty->assign('title','Part Configuration Customer');
$smarty->display('part_configuration.tpl');




?>

