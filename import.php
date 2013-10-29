<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';

if (!isset($_REQUEST['subject']) or !isset($_REQUEST['parent']) or !isset($_REQUEST['parent_key'])) {
	exit("to do a page where the user can choose the correct options");
}

$sql=sprintf("select `Imported Records Key` from `Imported Records Dimension` where `Imported Records Subject`=%s and `Imported Records Parent`=%s and `Imported Records Parent Key`=%d and `Imported Records User Key`=%d  and `Imported Records State` not in  ('Finished','Cancelled')  ",
	prepare_mysql($_REQUEST['subject']),
	prepare_mysql($_REQUEST['parent']),
	$_REQUEST['parent_key'],
	$user->id
);

$result=mysql_query($sql);


if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	header('Location: import_review.php?id='.$row['Imported Records Key'].'&reference=import');
	exit;
}


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/edit.css',
	'css/button.css',
	'css/table.css',
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
	// $yui_path.'uploader/uploader-debug.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/import.js'
);



$subject=$_REQUEST['subject'];
$parent=$_REQUEST['parent'];
$parent_key=$_REQUEST['parent_key'];


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

$smarty->assign('title',$title);
$smarty->assign('subject',$subject);
$smarty->assign('parent',$parent);
$smarty->assign('parent_key',$parent_key);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);


$smarty->assign('block_view',$_SESSION['state']['imported_records']['view']);
$smarty->assign('elements_state',$_SESSION['state']['imported_records']['imported_records']['elements']);

$tipo_filter=$_SESSION['state']['imported_records']['imported_records']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['imported_records']['imported_records']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['imported_records']['imported_records']['f_value']);
$filter_menu=array(
	'filename'=>array('db_key'=>'filename','menu_label'=>_('Filename'),'label'=>_('Filename')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$smarty->assign('gettext_strings',base64_encode(json_encode(array(
'page'=>_('Page'),
'og'=>_('of')
)
)));

$smarty->assign('state_imported_records',base64_encode(json_encode($_SESSION['state']['imported_records'])));


$smarty->display('import.tpl');
?>
