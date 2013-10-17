<?php
/*

 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2012, Inikoo Ltd

 Version 2.0
*/

include_once 'class.Category.php';
include_once 'class.Store.php';
include_once 'common.php';



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
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/customers_common.js',
	'customer_categories.js.php'
);





$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$smarty->assign('subcategories_view',$_SESSION['state']['customer_categories']['view']);
$smarty->assign('subcategories_period',$_SESSION['state']['customer_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['customer_categories']['avg']);
$smarty->assign('category_period',$_SESSION['state']['customer_categories']['period']);




if (isset($_REQUEST['store_id']) ) {

	$store=new Store($_REQUEST['store_id']);
	if (!$store->id) {

		header('Location: index.php');
		exit;

	}

} else {

	if (count($user->stores)==0) {
		header('Location: index.php');
		exit;
	}else {
		header('Location: customer_categories.php?store_id='.$user->stores[0]);
		exit;


	}

}

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);

$block_view=$_SESSION['state']['customer_categories']['root_block_view'];
$smarty->assign('block_view',$block_view);

$smarty->assign('store_key',$store->id);
$smarty->assign('store_id',$store->id);
$smarty->assign('store',$store);


$tipo_filter=$_SESSION['state']['customer_categories']['main_categories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['customer_categories']['main_categories']['f_value']);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Category Code'),'label'=>_('Code')),
	'label'=>array('db_key'=>'label','menu_label'=>_('Category Label'),'label'=>_('Label')),

);


$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['customer_categories']['no_assigned_customers']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['customer_categories']['no_assigned_customers']['f_value']);
$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
);
$smarty->assign('filter_menu3',$filter_menu);

$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customer Categories'));

$smarty->assign('subject','Customer');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$elements_number=array('Changes'=>0,'Assign'=>0);
$sql=sprintf("select count(*) as num ,`Type` from  `Customer Category History Bridge` where  `Store Key`=%d group by  `Type`",$store->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=number($row['num']);
}
$smarty->assign('history_elements_number',$elements_number);
$smarty->assign('history_elements',$_SESSION['state']['customer_categories']['history']['elements']);

$smarty->display('customer_categories.tpl');

?>

