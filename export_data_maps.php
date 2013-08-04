<?php
/*
File: export_data_maps.php

Data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2009, Inikoo

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
include_once('class.Customer.php');
$css_files=array(
         $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
         $yui_path.'menu/assets/skins/sam/menu.css',
         $yui_path.'calendar/assets/skins/sam/calendar.css',
         $yui_path.'button/assets/skins/sam/button.css',
         $yui_path.'editor/assets/skins/sam/editor.css',
         $yui_path.'assets/skins/sam/autocomplete.css',
         'css/text_editor.css',
         'css/common.css',
         'css/button.css',
         'css/container.css',
         'css/table.css',
	 'css/export_wizard.css',
         'css/customer.css'
         );
$js_files=array(
        $yui_path.'utilities/utilities.js',
        $yui_path.'json/json-min.js',
        $yui_path.'paginator/paginator-min.js',
        $yui_path.'datasource/datasource-min.js',
        $yui_path.'autocomplete/autocomplete-min.js',
        $yui_path.'datatable/datatable-min.js',
        $yui_path.'container/container-min.js',
        $yui_path.'editor/editor-min.js',
        $yui_path.'menu/menu-min.js',
        $yui_path.'calendar/calendar-min.js',
        'external_libs/ampie/ampie/swfobject.js',
        'js/common.js',
        'js/table_common.js',
        'js/search.js',
        'js/edit_common.js',
	'js/export_wizard.js',
        'customer.js.php'
        );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(!$user->can_view('customers')){
  exit();
}

if(!$_REQUEST['subject']){ //To check whether the form has proper parameters in query string //
	header('Location: index.php');
	exit;
}

$map_type = $_REQUEST['subject'];
if($map_type=='customer' || $map_type=='customers' || $map_type=='customers_static_list' || $map_type=='customers_dynamic_list'){
	$map_db_type = 'Customer';

}

$subject_key=$_REQUEST['subject_key'];


$maps = array();
$sql = "SELECT `Map Key`,`Map Name`,`Map Description` from `Export Map` WHERE `Map Type` = '$map_db_type' ORDER BY `Exported Date` ASC";
$query = mysql_query($sql);
if($query){
	$num=mysql_num_rows($query);
	$i=0;

	while($maps_data=mysql_fetch_row($query))
	{
		$maps[$i]=$maps_data;
		$i++;
	}
	//print_r($maps);
}else{

	$num = 0;
}
$smarty->assign('maps',$maps);
$smarty->assign('subject_key',$subject_key);
$smarty->assign('subject',$map_db_type);
$smarty->assign('no_of_maps',$num);
$smarty->display('export_data_maps.tpl');

?>
