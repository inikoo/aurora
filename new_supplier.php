<?php
/*
  About:
  Autor: Raul Perusquia <rulovico@gmail.com>
  Copyright (c) 2010, Inikoo
  Version 2.0
*/

include_once 'common.php';
include_once 'class.Company.php';

if (!$user->can_view('contacts'))
	exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if (!$modify or!$create) {
	exit();
}

$smarty->assign('scope','supplier');

$smarty->assign('search_label',_('Suppliers'));
$smarty->assign('search_scope','supplier_products');

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/edit.css',
	'css/edit_address.css',
	'theme.css.php'
);



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

	'new_subject.js.php',
	'company.js.php',
	'js/validate_telecom.js',
	'new_supplier.js.php',
	'js/edit_common.js',
	'edit_address.js.php',
	//'edit_contact_from_parent.js.php',
	//'edit_contact_telecom.js.php',
	//'edit_contact_name.js.php',
	//'edit_contact_email.js.php',


);

$sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Code`=L.`Language ISO 639-1 Code` where `Language ISO 639-1 Code`=%s limit 1000",prepare_mysql($myconf['lang']));
//print $sql;

$result=mysql_query($sql);
$salutations=array();
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
}
mysql_free_result($result);
$smarty->assign('prefix',$salutations);




$tipo='supplier';





$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','suppliers');
$smarty->assign('tipo',$tipo);
$categories=array();
$number_categories=count($categories);
$smarty->assign('categories',$categories);
$smarty->assign('number_categories',$number_categories);

$smarty->assign('title','Creating New Supplier');

$tipo_filter100='code';
$filter_menu100=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Country Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Country Name'),'label'=>_('Name')),
	'wregion'=>array('db_key'=>'wregion','menu_label'=>_('World Region Name'),'label'=>_('Region')),
);
$smarty->assign('filter_name100',$filter_menu100[$tipo_filter100]['label']);
$smarty->assign('filter_menu100',$filter_menu100);
$smarty->assign('filter100',$tipo_filter100);
$smarty->assign('filter_value100','');
$smarty->assign('supplier_id','');

$smarty->display('new_supplier.tpl');
?>
