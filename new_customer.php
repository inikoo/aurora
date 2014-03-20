<?php
/*
  File: company.php

  UI company page

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

include_once 'common.php';
include_once 'class.Company.php';
include_once 'class.Category.php';

if (!$user->can_view('contacts'))
	exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if (!$modify or!$create) {
	exit();
}



if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
	$store_id=$_REQUEST['store'];

} else {
	$store_id=$_SESSION['state']['customers']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	header('Location: index.php');
	exit;
}

$store=new Store($store_id);
if ($store->id) {
	$_SESSION['state']['customers']['store']=$store->id;
} else {
	header('Location: index.php?error=store_not_found');
	exit();
}

$store_key=$store->id;





$smarty->assign('store',$store);


$smarty->assign('store_id',$store_key);

$smarty->assign('store_key',$store_key);
$smarty->assign('scope','customer');



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
	'new_customer.js.php?&store_key='.$store_key,
	'edit_address.js.php',
	'edit_contact_from_parent.js.php',
	'js/edit_contact_telecom.js',
	'edit_contact_name.js.php',
	'edit_contact_email.js.php'

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
$editing_block='details';
$smarty->assign('edit',$editing_block);

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$tipo='Company';
if (isset($_REQUEST['tipo']) and $_REQUEST['tipo']=='person') {
	$tipo='Person';
}
$smarty->assign('customer_type',$tipo);




$smarty->assign('hq_country',$corporate_country_code);


$new_subject=array();
$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer' and `Custom Field In New Subject`='Yes'");
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$new_subject[] = array('custom_field_name'=>$row['Custom Field Name']);
}
$smarty->assign('new_subject',$new_subject);

$categories=array();
$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Branch Type`='Root' and `Category Store Key`=%d and `Category Show Subject User Interface`='Yes'",$store_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$tmp=new Category($row['Category Key']);



	$categories[$row['Category Key']]=$tmp;

}
$smarty->assign('categories',$categories);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers');

$smarty->assign('title',_('Creating New Customer'));


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




$smarty->display('new_customer.tpl');




?>
