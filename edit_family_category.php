<?php
/*

  About:
  Autor: Raul Perusquia <raul@inikoo.com>
  Created: 2 December 2013 17:55:11 CET, Málaga ESP	
	
  Copyright (c) 2013, Inikoo

  Version 2.0

*/



include_once 'class.Category.php';
include_once 'class.Store.php';

include_once 'common.php';




if (!$user->can_view('stores')  ) {
	header('Location: index.php');
	exit;
}

if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];


} else {
	header('Location: index.php?error_no_wrong_category_id');
	exit;
}
$category=new Category($category_key);
if (!$category->id) {
	header('Location: family_categories.php?id=0&error=cat_not_found');
	exit;

}
if ($category->data['Category Subject']!='Family') {
	header('Location: index.php?error_no_wrong_category_id');
	exit;
}


$modify=$user->can_edit('Products');
if (!$modify) {
	header('Location: family_categories.php');
}




$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$css_files=array(

	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
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
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	$yui_path.'animation/animation-min.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'js/edit_category_common.js'
);
$smarty->assign('css_files',$css_files);





$_SESSION['state']['family_categories']['no_assigned_families']['checked_all']=0;







$category_key=$category->id;

$view=$_SESSION['state']['family_categories']['edit'];


if ($category->data['Category Max Deep']<=$category->data['Category Deep'] ) {
	$create_subcategory=false;
	if ( $_SESSION['state']['family_categories']['edit']=='subcategory') {
		$view='parts';
		$_SESSION['state']['family_categories']['edit']=$view;
	}

}else {
	$create_subcategory=true;


}



$smarty->assign('category',$category);
$smarty->assign('category_key',$category->id);

// $tpl_file='part_category.tpl';
$store_id=$category->data['Category Store Key'];




$store=new Store($store_id);

if (!$store->id) {

	//print_r($category);

	header('Location: index.php?error=store_not_found');
	exit;

}


$order=$_SESSION['state']['family_categories']['subcategories']['order'];
if ($order=='code') {
	$order='`Category Code`';
	$order_label=_('Code');
} else {
	$order='`Category Label`';
	$order_label=_('Label');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d and `Category Root Key`=%d  and %s < %s  order by %s desc  limit 1",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='edit_family_category.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select`Category Key` as id , `Category Code` as name from `Category Dimension`  where  `Category Parent Key`=%d  and `Category Root Key`=%d    and  %s>%s  order by %s   ",
	$category->data['Category Parent Key'],
	$category->data['Category Root Key'],
	$order,
	prepare_mysql($category->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='edit_family_category.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);



$smarty->assign('show_history',$_SESSION['state']['family_categories']['show_history']);


$smarty->assign('store_id',$store_id);
$js_files[]='edit_family_category.js.php?key='.$category_key;
$smarty->assign('js_files',$js_files);
$smarty->assign('category_key',$category_key);
$smarty->assign('create_subcategory',$create_subcategory);



$smarty->assign('edit',$view);
$smarty->assign('store',$store);
$smarty->assign('subject','Family');

$smarty->assign('parent','products');
$smarty->assign('title', _('Family Category').' '.$category->data['Category Code'].' ('._('Editing').')');






$tipo_filter=$_SESSION['state']['family_categories']['subcategories']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['family_categories']['subcategories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Name')),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['family_categories']['no_assigned_families']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['family_categories']['no_assigned_families']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$tipo_filter=$_SESSION['state']['family_categories']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['family_categories']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

);

$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['family_categories']['edit_families']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['family_categories']['edit_families']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Family code starting with <i>x</i>'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Family name containing <i>x</i>'),'label'=>_('Name'))
);
$smarty->assign('filter_menu2',$filter_menu);

$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$smarty->assign('filter4','used_in');
$smarty->assign('filter_value4','');
$filter_menu=array(
	'sku'=>array('db_key'=>'sku','menu_label'=>_('Family SKU'),'label'=>_('SKU')),
	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in'),'label'=>_('Used in')),
);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu['used_in']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);

$smarty->assign('filter5','code');
$smarty->assign('filter_value5','');
$filter_menu=array(
	'code'=>array('db_key'=>'sku','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'used_in','menu_label'=>_('Category Label'),'label'=>_('Label')),
);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu['code']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Product Family Category History Bridge` where  `Category Key`=%d group by  `Type`",$category->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}


$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['family_categories']['history']['elements']);

$smarty->assign('assigned_subjects_view',$_SESSION['state']['family_categories']['edit_categories']['view']);


$smarty->display('edit_family_category.tpl');
?>
