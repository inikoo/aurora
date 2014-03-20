<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'common_date_functions.php';

include_once 'class.Supplier.php';

if ($user->data['User Type']!='Supplier' and !$user->can_view('suppliers')) {
	$smarty->display('forbidden.tpl');
	exit;
}


$modify=$user->can_edit('suppliers');

if (isset($_REQUEST['edit']) and $_REQUEST['edit']) {
	header('Location: edit_suplier.php?id='.$_REQUEST['edit']);
	exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
	$supplier_id=$_REQUEST['id'];
else
	$supplier_id=$_SESSION['state']['supplier']['id'];

if ($user->data['User Type']=='Supplier' and !in_array($supplier_id,$user->suppliers)) {

	$smarty->display('forbidden.tpl');
	exit;
}

$_SESSION['state']['supplier']['id']=$supplier_id;
$smarty->assign('supplier_id',$supplier_id);

$smarty->assign('orders_view',$_SESSION['state']['supplier']['orders_view']);

$smarty->assign('block_view',$_SESSION['state']['supplier']['block_view']);


$supplier=new Supplier($supplier_id);
if (!$supplier->id) {
	header('Location: suppliers.php?msg=SNPF');
	exit;
}


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');




$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/quick_edit.css',
	'theme.css.php'

);


$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'connection/connection-debug.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'animation/animation-min.js',

	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
		'external_libs/amstock/amstock/swfobject.js',

	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/suppliers_common.js',
	"edit_address.js.php",
	"js/edit_delivery_address_common.js",
	"js/validate_telecom.js",
	"address_data.js.php?tipo=supplier&id=".$supplier->id,
	"edit_contact_from_parent.js.php",
	"js/edit_contact_telecom.js",
	"edit_contact_name.js.php",
	"edit_contact_email.js.php",
	"edit_subject_quick.js.php?subject=supplier&subject_key=".$supplier->id,
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/notes.js',
	'supplier.js.php'

);


$company=new Company($supplier->data['Supplier Company Key']);
//$supplier->load('contacts');
$smarty->assign('supplier',$supplier);
$smarty->assign('company',$company);

$address=new address($company->data['Company Main Address Key']);
$smarty->assign('address',$address);



$smarty->assign('parent','suppliers');
$smarty->assign('title','Supplier: '.$supplier->get('Supplier Code'));


$tipo_filter=$_SESSION['state']['supplier']['supplier_products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier']['supplier_products']['f_value']);

$filter_menu=array(
	'p.code'=>array('db_key'=>'p.code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
	'sup_code'=>array('db_key'=>'sup_code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->assign('display',$_SESSION['state']['supplier']['display']);

$smarty->assign('supplier_products_view',$_SESSION['state']['supplier']['supplier_products']['view']);
$smarty->assign('supplier_products_period',$_SESSION['state']['supplier']['supplier_products']['period']);
//print_r($_SESSION['state']['supplier']['supplier_products']);

//$smarty->assign('supplier_products_avg',$_SESSION['state']['supplier']['supplier_products']['avg']);



$tipo_filter=$_SESSION['state']['supplier']['porders']['f_field'];
$smarty->assign('filter',$tipo_filter);


$smarty->assign('filter_value1',$_SESSION['state']['supplier']['porders']['f_value']);
$filter_menu=array(
	'public_id'=>array('db_key'=>'p.code','menu_label'=>_('Purchase order'),'label'=>'Id'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Orders with a minimum value of <i>').$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Orders with a maximum value of <i>').$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
	'max'=>array('db_key'=>'max','menu_label'=>_('Orders from the last <i>n</i> days'),'label'=>_('Last (days)'))
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);





$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('default_country_2alpha','GB');

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


//$smarty->assign('show_purchase_history_chart',$_SESSION['state']['supplier']['purchase_history']['show_chart']);
//$smarty->assign('purchase_history_chart_output',$_SESSION['state']['supplier']['purchase_history']['chart_output']);
//$smarty->assign('purchase_history_type',$_SESSION['state']['supplier']['purchase_history']['type']);


$elements_number=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Supplier History Bridge` where `Supplier Key`=%d group by `Type`",$supplier->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['supplier']['history']['elements']);


$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['supplier']['history']['f_field'];
$filter_value=$_SESSION['state']['supplier']['history']['f_value'];

$smarty->assign('filter_value4',$filter_value);
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);

$smarty->assign('supplier_address_fuzzy_type',$supplier->get_main_address_fuzzy_type());
$smarty->assign('sales_sub_block_tipo',$_SESSION['state']['supplier']['sales_sub_block_tipo']);



$tipo_filter=($_SESSION['state']['supplier']['supplier_product_sales']['f_field']);
$smarty->assign('filter5',$tipo_filter);
$smarty->assign('filter_value5',$_SESSION['state']['supplier']['supplier_product_sales']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
);
$smarty->assign('filter_menu5',$filter_menu);
$smarty->assign('filter_name5',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu5',$paginator_menu);


$tipo_filter=($_SESSION['state']['supplier']['supplier_product_sales']['f_field']);
$smarty->assign('filter6',$tipo_filter);
$smarty->assign('filter_value6',$_SESSION['state']['supplier']['supplier_product_sales']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
);
$smarty->assign('filter_menu6',$filter_menu);
$smarty->assign('filter_name6',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);

$smarty->assign('filter_value7','');
$smarty->assign('filter_name7','');
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu7',$paginator_menu);



if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['supplier']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['supplier']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['supplier']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['supplier']['period']=$period;
$_SESSION['state']['supplier']['from']=$from;
$_SESSION['state']['supplier']['to']=$to;
$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$sales_history_timeline_group=$_SESSION['state']['supplier']['sales_history']['timeline_group'];
$smarty->assign('sales_history_timeline_group',$sales_history_timeline_group);
switch ($sales_history_timeline_group) {
case 'day':
	$sales_history_timeline_group_label=_('Daily');
	break;
case 'week':
	$sales_history_timeline_group_label=_('Weekly (end of week)');
	break;
case 'month':
	$sales_history_timeline_group_label=_('Monthy (end of month)');
	break;
case 'year':
	$sales_history_timeline_group_label=_('Yearly');
	break;	
default:
	$sales_history_timeline_group_label=$sales_history_timeline_group;
}
$smarty->assign('sales_history_timeline_group_label',$sales_history_timeline_group_label);

$timeline_group_sales_history_options=array(
	array('mode'=>'day','label'=>_('Daily')),
	array('mode'=>'week','label'=>_('Weekly (end of week)')),
	array('mode'=>'month','label'=>_('Monthy (end of month)')),
	array('mode'=>'year','label'=>_('Yearly'))

);
$smarty->assign('timeline_group_sales_history_options',$timeline_group_sales_history_options);

$smarty->display('supplier.tpl');

?>
