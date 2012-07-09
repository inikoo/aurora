<?php
include_once 'class.Category.php';
include_once 'class.Store.php';

include_once 'common.php';
//include_once 'assets_header_functions.php';



if (!$user->can_view('orders')  ) {
	header('Location: index.php');
	exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('stores');




$smarty->assign('view',$_SESSION['state']['invoice_categories']['view']);

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
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
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'external_libs/ammap/ammap/swfobject.js',
	'js/parts_common.js',
	'js/edit_category_common.js'

);





$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');

$smarty->assign('subcategories_view',$_SESSION['state']['invoice_categories']['view']);

$smarty->assign('subcategories_period',$_SESSION['state']['invoice_categories']['period']);
$smarty->assign('subcategories_avg',$_SESSION['state']['invoice_categories']['avg']);

$smarty->assign('category_period',$_SESSION['state']['invoice_categories']['period']);




if (isset($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];
} else {
	$category_key=$_SESSION['state']['invoice_categories']['category_key'];
}

if (!$category_key) {


	if (isset($_REQUEST['store'])  and is_numeric($_REQUEST['store']) ) {

		$store=new Store($_REQUEST['store']);
		if (!$store->id) {

			header('Location: index.php');
			exit;

		}

	} else {
		header('Location: index.php');
		exit;
	}



	$block_view=$_SESSION['state']['invoice_categories']['base_block_view'];
	$smarty->assign('block_view',$block_view);
	$js_files[]='invoice_categories_base.js.php';
	$tpl_file='invoice_categories_base.tpl';

} else {



	$category=new Category($category_key);
	if (!$category->id) {
		header('Location: invoice_categories.php?id=0&error=cat_not_found');
		exit;
	}

	$category_key=  $category->id;
	$store=new Store($category->data['Category Store Key']);


	if (isset($_REQUEST['from'])) {
		$from=$_REQUEST['from'];
	}else {
		$from='';
	}

	if (isset($_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['orders']['to']=$to;
	}else {
		$to='';
	}
	$_SESSION['state']['orders']['to']=$to;
	$_SESSION['state']['orders']['from']=$from;

	$smarty->assign('from',$from);
	$smarty->assign('to',$to);





	$total_invoices_and_refunds=0;
	$total_invoices=0;
	$total_refunds=0;
	$total_paid=0;
	$total_to_pay=0;


	$sql=sprintf("select sum(if(`Invoice Paid`='Yes',1,0)) as paid  ,sum(if(`Invoice Paid`='No',1,0)) as to_pay  , sum(if(`Invoice Title`='Invoice',1,0)) as invoices  ,sum(if(`Invoice Title`='Refund',1,0)) as refunds  from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d  %s %s" ,
		$category->id,
		($from?sprintf('and `Invoice Date`>%s',prepare_mysql($from)):''),

		($to?sprintf('and `Invoice Date`<%s',prepare_mysql($to)):'')

	);
	$result=mysql_query($sql);

//	print "$sql\n";
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total_invoices_and_refunds=$row['invoices']+$row['refunds'];
		$total_invoices=$row['invoices'];
		$total_refunds=$row['refunds'];
		$total_paid=$row['paid'];
		$total_to_pay=$row['to_pay'];

	}
	
		$smarty->assign('total_invoices_and_refunds',$total_invoices_and_refunds);
		$smarty->assign('total_invoices',$total_invoices);
		$smarty->assign('total_refunds',$total_refunds);
		$smarty->assign('total_paid',$total_paid);
		$smarty->assign('total_to_pay',$total_to_pay);


	$smarty->assign('category',$category);

	if (isset($_REQUEST['block_view']) and in_array($_REQUEST['block_view'],array('subcategories','subjects','subcategories_charts','history'))) {
		$_SESSION['state']['invoice_categories']['block_view']=$_REQUEST['block_view'];
	}

	$block_view=$_SESSION['state']['invoice_categories']['block_view'];

	if ($block_view=='subcategories' and $category->get('Category Children')==0) {
		$block_view='subjects';
	}

	if ($block_view=='subjects' and $category->get('Category Number Subjects')==0  ) {
		$block_view='subcategories';
	}


	$smarty->assign('block_view',$block_view);



	$tipo_filter1=$_SESSION['state']['orders']['invoices']['f_field'];
	$smarty->assign('filter0',$tipo_filter1);
	$smarty->assign('filter_value0',($_SESSION['state']['orders']['invoices']['f_value']));
	$filter_menu1=array(
		'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Invoice Number'),
		'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
		'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
		'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
		'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
	);
	$smarty->assign('filter_menu0',$filter_menu1);
	$smarty->assign('filter_name0',$filter_menu1[$tipo_filter1]['label']);
	$paginator_menu1=array(10,25,50,100,500);
	$smarty->assign('paginator_menu0',$paginator_menu1);

	$smarty->assign('invoice_type',$_SESSION['state']['orders']['invoices']['invoice_type']);

	// $smarty->assign('view',$_SESSION['state']['warehouse']['parts_view']);
	// $smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
	// $smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
	// $smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);




	$js_files[]='invoice_categories.js.php';
	$tpl_file='invoice_category.tpl';





}
$smarty->assign('store_id',$store->id);
$smarty->assign('store',$store);


$_SESSION['state']['invoice_categories']['category_key']=$category_key;


$tipo_filter=$_SESSION['state']['invoice_categories']['subcategories']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['invoice_categories']['subcategories']['f_value']);

$filter_menu=array(
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Category Name'),'label'=>_('Name')),
);


$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['store']['history']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
	'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);



$smarty->assign('parent','orders');
$smarty->assign('title', _('Invoice Categories'));

$smarty->assign('subject','Part');
$smarty->assign('category_key',$category_key);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

include_once 'conf/period_tags.php';
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$plot_data=array('pie'=>array('forecast'=>3,'interval'=>''));
$smarty->assign('plot_tipo','store');
$smarty->assign('plot_data',$plot_data);

$smarty->display($tpl_file);
?>
