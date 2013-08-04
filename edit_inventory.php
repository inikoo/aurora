<?php
/*

  About: 
 
  Copyright (c) 2011, Inikoo 
 
  Version 2.0
*/

include_once('common.php');
include_once('class.Warehouse.php');



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$warehouse_id=$_REQUEST['id'];

}else {
		
		if (count($user->warehouses)==0) {
		header('Location: index.php?error_no_warehouse_key');
		exit;
	}else {
		header('Location: inventory.php?id='.$user->warehouses[0]);
		exit;


	}
	
	
}


$warehouse=new warehouse($warehouse_id);
if (!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ) {
	header('Location: index.php');
	exit;
}


$create=$user->can_create('warehouses');
$modify=$user->can_edit('warehouses');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('warehouse',$warehouse);


if(!$modify or!$create){
  exit();
}

$general_options_list=array();


$view=$_SESSION['state']['warehouses']['view'];
$smarty->assign('view',$view);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'css/text_editor.css',
		 'css/common.css',
		 'css/button.css',
		 'css/container.css',
		 'css/table.css',
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
		'edit_inventory.js.php'
		);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','parts');

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('edit',$_SESSION['state']['warehouse']['edit']);


$smarty->assign('title','Edit Inventory');
$smarty->display('edit_inventory.tpl');




?>

