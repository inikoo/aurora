<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.ImportedRecords.php';

//include_once 'common_import.php';

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'




);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'uploader/uploader-debug.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'import_review.js.php',

);




if (!isset($_REQUEST['id'])) {
	exit("no id");
}
if (!isset($_REQUEST['reference'])) {
	$reference='import';
}else{
$reference=$_REQUEST['reference'];
}

$imported_records=new ImportedRecords('id',$_REQUEST['id']);

if(!$imported_records->id){
	exit("imported_records id not found");
}


$index=1;

$subject=$imported_records->data['Imported Records Subject'];
$parent=$imported_records->data['Imported Records Parent'];
$parent_key=$imported_records->data['Imported Records Parent Key'];


switch ($subject) {
case('customers'):
	include_once 'class.Store.php';
	$store=new Store($parent_key);
	$smarty->assign('store',$store);
	$smarty->assign('store_id',$store->id);
	$smarty->assign('search_label',_('Customers'));
	$smarty->assign('search_scope','customers');
	$smarty->assign('search_type','customers_store');
	$smarty->assign('parent','customers');
	$title=_('Import customers');

	break;


case('supplier_products'):

	break;

case('staff'):
	break;

case('positions'):
	break;

case('areas'):
	break;

case('departments'):
	break;

case('family'):
case('department'):
case('store'):
case('locations'):
	break;

default:

}

$smarty->assign('filter_name5','name');
$smarty->assign('filter_value5','');



$smarty->assign('index',$index);

$smarty->assign('title',$title);
$smarty->assign('subject',$subject);
$smarty->assign('parent',$parent);
$smarty->assign('parent_key',$parent_key);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->assign('reference',$reference);
$smarty->assign('imported_records',$imported_records);

$smarty->display('import_review.tpl');
?>
