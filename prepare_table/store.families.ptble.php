<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 17:17:36 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

$period_tag=get_interval_db_name($parameters['f_period']);


switch ($parameters['parent']) {
case('store'):

	$where=sprintf(' where `Product Family Store Key`=%d', $parameters['parent_key']);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';
	break;
case('department'):

	$where=sprintf(' where `Product Family Main Department Key`=%d', $parameters['parent_key']);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';

	break;
case('category'):

	$where=sprintf(' where `Category Key`=%d', $parameters['parent_key']);
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`) left join `Category Bridge` on (`Subject`="Family" and `Subject Key`=`Product Family Key`)';

	break;

default:
	if (count($user->stores)==0)
		$where="where false";
	else {

		$where=sprintf("where `Product Family Store Key` in (%s) ", join(',', $user->stores));
	}
	$table='`Product Family Dimension` F left join `Product Family Data Dimension` FD on (FD.`Product Family Key`=F.`Product Family Key`)';


}

/*
$_elements='';
$number_elements=0;
foreach ($elements as $_key=>$_value) {
	if ($_value) {
		$_elements.=','.prepare_mysql($_key);
		$number_elements++;
	}
}
$_elements=preg_replace('/^\,/', '', $_elements);
if ($_elements=='') {
	$where.=' and false' ;
} elseif ($number_elements<4) {
	$where.=' and `Product Family Record Type` in ('.$_elements.')' ;
}
*/



$filter_msg='';
$wheref='';
if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and `Product Family Code`  like '".addslashes($f_value)."%'";
if ($parameters['f_field']=='name' and $f_value!='')
	$wheref.=" and `Product Family Name`  like '%".addslashes($f_value)."%'";



$_order=$order;
$_dir=$order_direction;
// $order='`Product Family Code`';
if ($order=='profit') {
	$order='`Product Family '.$period_tag.' Acc Profit`';

}
elseif ($order=='sales') {
	$order='`Product Family '.$period_tag.' Acc Invoiced Amount`';

} elseif ($_order=='delta_sales') {

	if ($period_tag=='Total' or $period_tag=='3 Year') {
		$order='`Product Family Code`';
	}else {
		$order='(`Product Family '.$period_tag.' Acc Invoiced Amount`-`Product Family '.$period_tag.' Acc 1YB Invoiced Amount`)/`Product Family '.$period_tag.' Acc 1YB Invoiced Amount`';
	}
}
elseif ($order=='code')
	$order='`Product Family Code`';
elseif ($order=='stock_value')
	$order='`Product Family Stock Value`';
elseif ($order=='name')
	$order='`Product Family Name`';
elseif ($order=='public_sale')
	$order='`Product Family For Public Sale Products`';
elseif ($order=='discontinued')
	$order='`Product Family Discontinued Products`';
elseif ($order=='todo')
	$order='`Product Family In Process Products`';
elseif ($order=='notforsale')
	$order='`Product Family Not For Sale Products`';
elseif ($order=='private_sale')
	$order='`Product Family For Private Sale Products`';
elseif ($order=='historic')
	$order='`Product Family Historic Products`';
elseif ($order=='not_for_sale')
	$order='`Product Family Not For Sale Products`';

elseif ($order=='outofstock')
	$order='`Product Family Out Of Stock Products`';
elseif ($order=='stock_error')
	$order='`Product Family Unknown Stock Products`';
elseif ($order=='surplus')
	$order='`Product Family Surplus Availability Products`';
elseif ($order=='optimal')
	$order='`Product Family Optimal Availability Products`';
elseif ($order=='low')
	$order='`Product Family Low Availability Products`';
elseif ($order=='critical')
	$order='`Product Family Critical Availability Products`';
elseif ($order=='from') {
	$order='`Product Family Valid From`';
}elseif ($order=='to') {
	$order='`Product Family Valid To`';
}elseif ($order=='last_update') {
	$order='`Product Family Last Updated`';
}elseif ($order=='department') {
	$order='`Product Family Main Department Code`';
}elseif ($order=='products_for_sale') {
	$order='`Product Family For Public Sale Products`';
}elseif ($order=='percentage_out_of_stock') {
	$order='`Product Family Out Of Stock Products`/`Product Family For Public Sale Products`';
}elseif ($order=='sales_1q') {
	$order='`Product Family 1 Quarter Acc Invoiced Amount`';
}elseif ($order=='delta_sales_1q') {
	$order='(`Product Family 1 Quarter Acc Invoiced Amount`-`Product Family 1 Quarter Acc 1YB Invoiced Amount`)/`Product Family 1 Quarter Acc 1YB Invoiced Amount`';
}elseif ($order=='customers_active') {
	$order='`Product Family Active Customers`';
}elseif ($order=='customers_active_75') {
	$order='`Product Family Active Customers More 0.75 Share`';
}elseif ($order=='customers_active_50') {
	$order='`Product Family Active Customers More 0.5 Share`';
}elseif ($order=='customers_active_25') {
	$order='`Product Family Active Customers More 0.25 Share`';
}

elseif ($order=='customers_losing') {
	$order='`Product Family Losing Customers`';
}elseif ($order=='customers_losing_75') {
	$order='`Product Family Losing Customers More 0.75 Share`';
}elseif ($order=='customers_losing_50') {
	$order='`Product Family Losing Customers More 0.5 Share`';
}elseif ($order=='customers_losing_25') {
	$order='`Product Family Losing Customers More 0.25 Share`';
}

elseif ($order=='customers_lost') {
	$order='`Product Family Lost Customers`';
}elseif ($order=='customers_lost_75') {
	$order='`Product Family Lost Customers More 0.75 Share`';
}elseif ($order=='customers_lost_50') {
	$order='`Product Family Lost Customers More 0.5 Share`';
}elseif ($order=='customers_lost_25') {
	$order='`Product Family Lost Customers More 0.25 Share`';
}else{
$order='F.`Product Family Key`';
}

$sql_totals="select count(distinct  F.`Product Family Key`) as num from $table $where";

$fields="*";

?>
