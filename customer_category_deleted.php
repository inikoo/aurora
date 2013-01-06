<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Store.php';


if (!$user->can_view('stores')  ) {
	header('Location: index.php');
	exit;
}




if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {

	$category_key=$_REQUEST['id'];
} else {

	header('Location: customer_categories.php?no_category_id');
	exit();
}


$sql=sprintf("select * from `Category Deleted Dimension` where `Category Deleted Key`=%d",$category_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	foreach ($row as $key=>$value) {
		$category_data[preg_replace('/\s/','',$key)]=$value;
	}

}else {
	header('Location: store_customers.php?error=category_not_found');
	exit();
}



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',

	'text_editor.css',
	'common.css',
	'button.css',
	'css/container.css',
	'table.css',
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
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'customer_category_deleted.js.php'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('category_data',$category_data);



$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$message='';




$smarty->assign('deleted_date',strftime("%c", strtotime($category_data['CategoryDeletedDate']." +0:00")));

$smarty->assign('message',$message);

$store=new Store($category_data['CategoryDeletedStoreKey']);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);

$smarty->assign('parent','customers');
$smarty->assign('title',_('Deleted Category'));

$elements_number=array('Change'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Customer Category History Bridge` where  `Category Key`=%d group by  `Type`",$category_key);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['customer_categories']['history']['elements']);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract'),'label'=>_('Abstract'))

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$smarty->display('customer_category_deleted.tpl');

?>
