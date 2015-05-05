<?php
include_once 'common.php';
include_once 'common_date_functions.php';
include_once 'class.SupplierProduct.php';
include_once 'class.Supplier.php';
include_once 'class.Part.php';

$view_suppliers=$user->can_view('suppliers');


$product_supplier_pid=(isset($_REQUEST['pid'])?$_REQUEST['pid']:$_SESSION['state']['supplier_product']['pid']);

if (!$product_supplier_pid) {
	header('Location: suppliers.php?e');
	exit();
}


$supplier_product= new SupplierProduct('pid',$product_supplier_pid);
if (!$supplier_product->id) {
	header('Location: suppliers.php');
	exit;

}


$supplier_key=$supplier_product->supplier_key;
if ($user->data['User Type']=='Supplier') {

	if (!in_array($supplier_key,$user->suppliers)) {
		header('Location: suppliers.php?e');
		exit();

	}

} else if (!$view_suppliers) {
		header('Location: index.php');
		exit();
	}




$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',

	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/supplier_product.css',
	'theme.css.php'
);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'dragdrop/dragdrop-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/jquery-1.4.4.min.js',
	'js/barcode.js',
	'js/common.js',
	'external_libs/amstock/amstock/swfobject.js',
	'js/edit_common.js',
	'js/table_common.js',
	'js/search.js',
	'edit_stock.js.php',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/notes.js',
	'supplier_product.js.php'
);



$supplier_product_code=$supplier_product->code;
$supplier=new Supplier($supplier_product->data['Supplier Key']);




$modify=$user->can_edit('suppliers');


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');
$smarty->assign('block_view',$_SESSION['state']['supplier_product']['block_view']);

$smarty->assign('corporate_currency',$corporate_currency);



$smarty->assign('stock_history_block',$_SESSION['state']['supplier_product']['stock_history_block']);
$smarty->assign('sales_block',$_SESSION['state']['supplier_product']['sales_block']);


$smarty->assign('supplier_product',$supplier_product);
$smarty->assign('supplier',$supplier);

$smarty->assign('parent','suppliers');
$smarty->assign('title',_('Supplier Product').': '.$supplier_product->get('Supplier Product Code'));




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//$parts=$product_suppliir->get_parts();


$part_skus=$supplier_product->get_part_skus();


$part_sku=false;
if ($supplier_product->data['Supplier Product Part Convertion']=='1:1') {
	$part_sku=array_pop($part_skus);
	$smarty->assign('part_sku',$part_sku);

}








$smarty->assign('supplier_id',$supplier_key);







$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Supplier Product History Bridge` where `Supplier Product ID`=%d group by `Type`",$supplier_product->pid);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_part_history_number',$elements_number);
$smarty->assign('elements_part_history',$_SESSION['state']['supplier_product']['history']['elements']);


//$smarty->assign('transaction_type',$_SESSION['state']['supplier_product']['transactions']['view']);
$tipo_filter=$_SESSION['state']['supplier_product']['stock_history']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['supplier_product']['stock_history']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier_product']['stock_history']['f_value']);
$filter_menu=array(
	'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['supplier_product']['transactions']['f_field'];
$smarty->assign('filter_show1',$_SESSION['state']['supplier_product']['transactions']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier_product']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
	'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$tipo_filter2=$_SESSION['state']['supplier_product']['porders']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['supplier_product']['porders']['f_value']));
$filter_menu2=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Id'),'label'=>_('Id')),
);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);

$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['supplier_product']['history']['f_field'];
$filter_value=$_SESSION['state']['supplier_product']['history']['f_value'];

$smarty->assign('filter_value3',$filter_value);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);
$smarty->assign('sticky_note',$supplier_product->data['Supplier Product Sticky Note']);

$smarty->assign('filter_name4','');
$smarty->assign('filter_value4','');



if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['supplier_product']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['supplier_product']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['supplier_product']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['supplier_product']['period']=$period;
$_SESSION['state']['supplier_product']['from']=$from;
$_SESSION['state']['supplier_product']['to']=$to;

$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');

$stock_history_timeline_group=$_SESSION['state']['supplier_product']['stock_history']['timeline_group'];
$smarty->assign('stock_history_timeline_group',$stock_history_timeline_group);
switch ($stock_history_timeline_group) {
case 'day':
	$stock_history_timeline_group_label=_('Daily');
	break;
case 'week':
	$stock_history_timeline_group_label=_('Weekly (end of week)');
	break;
case 'month':
	$stock_history_timeline_group_label=_('Monthy (end of Month)');
	break;
default:
	$stock_history_timeline_group_label=$stock_history_timeline_group;
}
$smarty->assign('stock_history_timeline_group_label',$stock_history_timeline_group_label);

$timeline_group_stock_history_options=array(
	array('mode'=>'day','label'=>_('Daily')),
	array('mode'=>'week','label'=>_('Weekly (end of week)')),
	array('mode'=>'month','label'=>_('Monthy (end of Month)'))

);
$smarty->assign('timeline_group_stock_history_options',$timeline_group_stock_history_options);


$smarty->assign('show_stock_history_chart',$_SESSION['state']['supplier_product']['stock_history']['show_chart']);
$smarty->assign('stock_history_chart_output',$_SESSION['state']['supplier_product']['stock_history']['chart_output']);




$sales_history_timeline_group=$_SESSION['state']['supplier_product']['sales_history']['timeline_group'];
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


$order=$_SESSION['state']['supplier']['supplier_products']['order'];



	$db_period=get_interval_db_name($_SESSION['state']['supplier']['supplier_products']['period']);

	if ($order=='id'){
		$order='`Supplier Product ID`';
		$_order='Supplier Product ID';
		$order_label=_('Supplier Product ID');
	}elseif ($order=='supplier'){
		$order='`Supplier Code`';
		$_order='Supplier Code';
		$order_label=_('Supplier Code');
	}elseif ($order=='code'){
		$order='`Supplier Product Code`';
		$_order='Supplier Product Code';
		$order_label=_('Code');
	}elseif ($order=='used_in'){
		$order='`Supplier Product XHTML Sold As`';
			$_order='Supplier Product XHTML Sold As';
		$order_label=_('Sold As');
	}elseif ($order=='tuos'){
		$order='`Supplier Product Days Available`';
			$_order='Supplier Product Days Available';
		$order_label=_('Days Available');
	}elseif ($order=='stock'){
		$order='`Supplier Product Stock`';
			$_order='Supplier Product Stock`';
		$order_label=_('Stock`');
	}elseif ($order=='name'){
		$order='`Supplier Product Name`';
			$_order='Supplier Product Name';
		$order_label=_('Name');
	}elseif ($order=='profit') {
		$order="`Supplier Product $db_period Acc Parts Profit`";
			$_order="Supplier Product $db_period Acc Parts Profit";
		$order_label=_('Profit');
	}
	elseif ($order=='required') {
		$order="`Supplier Product $db_period Acc Parts Required`";
			$_order="Supplier Product $db_period Acc Parts Required";
		$order_label=_('Required');
	}elseif ($order=='state') {
		$order="`Supplier Product State`";
			$_order='Supplier Product State';
		$order_label=_('State');
	}
	elseif ($order=='sold') {
		$order="`Supplier Product $db_period Acc Parts Sold`";
	$_order="Supplier Product $db_period Acc Parts Sold";
		$order_label=_('Parts Sold');

	}
	elseif ($order=='sales') {
		$order="`Supplier Product $db_period Acc Parts Sold Amount`";
$_order="Supplier Product $db_period Acc Parts Sold Amount";
		$order_label=_('Sales');
	}
	elseif ($order=='margin') {
		$order="`Supplier Product $db_period Acc Parts Margin`";
$_order="Supplier Product $db_period Acc Parts Margin";
		$order_label=_('Margin');
	}
	elseif ($order=='dispatched') {
		$order="`Supplier Product $db_period Acc Parts Dispatched`";
$_order="Supplier Product $db_period Acc Parts Dispatched";
		$order_label=_('Dispatched');
	}else{
		$order='`Supplier Product Code`';
	$_order='Supplier Product Code';
		$order_label=_('Code');
}

//$_order=preg_replace('/`/','',$order);
$sql=sprintf("select  P.`Supplier Product ID` as id , `Supplier Product Name` as name from `Supplier Product Dimension` P  where  `Supplier Key`=%d  and %s < %s  order by %s desc  limit 1",
	$supplier->id,
	$order,
	prepare_mysql($supplier_product->get($_order)),
	$order
);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='supplier_product.php?pid='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev_pid',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select P.`Supplier Product ID` as id , `Supplier Product Name` as name from `Supplier Product Dimension` P  where  `Supplier Key`=%d   and  %s>%s  order by %s   ",
	$supplier->id,
	$order,
	prepare_mysql($supplier_product->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='supplier_product.php?pid='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next_pid',$next);
}
mysql_free_result($result);



$smarty->display('supplier_product.tpl');




?>
