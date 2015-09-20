<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/




$table='`Product Department Dimension` D left join `Product Department Data Dimension` DD on (DD.`Product Department Key`=D.`Product Department Key`)';


if (count($user->stores)==0)
	$where="where false";
else {

	switch ($parent) {
	case('store'):
		if (in_array($parent_key,$user->stores))
			$where=sprintf("where  `Product Department Store Key`=%d",$parent_key);
		else
			$where=sprintf("where  false");
		break;
	default:

		$where=sprintf("where `Product Department Store Key` in (%s)",join(',',$user->stores));

	}
}

$filter_msg='';
$wheref=wheref_departments($f_field,$f_value);


	$_dir=$order_direction;
	$_order=$order;


	$period_tag=get_interval_db_name($period);



	//    print $period;

	//$order='`Product Department Code`';
	if ($_order=='families')
		$order='`Product Department Families`';

	elseif ($_order=='aws_p') {
		$order='`Product Department Total Acc Avg Week Sales Per Product`';

	}
	elseif ($_order=='awp_p') {


		$order='`Product Department '.$period_tag.' Acc Avg Week Profit Per Product`';

	}

	elseif ($_order=='profit') {

		$order='`Product Department '.$period_tag.' Acc Profit`';

	}
	elseif ($_order=='sales') {

		$order='`Product Department '.$period_tag.' Acc Invoiced Amount`';

	}
	elseif ($_order=='delta_sales') {


		if ($period_tag=='Total' or $period_tag=='3 Year') {
			$order='`Product Department Code`';
		}else {
			$order='(`Product Department '.$period_tag.' Acc Invoiced Amount`-`Product Department '.$period_tag.' Acc 1YB Invoiced Amount`)/`Product Department '.$period_tag.' Acc 1YB Invoiced Amount`';
		}


	}

elseif ($_order=='id')
		$order='D.`Product Department Key`';
	elseif ($_order=='name')
		$order='`Product Department Name`';
	elseif ($_order=='code')
		$order='`Product Department Code`';
	elseif ($_order=='active')
		$order='`Product Department For Public Sale Products`';
	elseif ($_order=='outofstock') {

		if ($stock_percentages=='horizontal')
			$order='`Product Department Out Of Stock Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Out Of Stock Products`';

	}
	elseif ($_order=='stock_error') {
		if ($stock_percentages=='horizontal')
			$order='`Product Department Unknown Stock Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Unknown Stock Products`';
	}elseif ($_order=='surplus') {
		if ($stock_percentages=='horizontal')
			$order='`Product Department Surplus Availability Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Surplus Availability Products`';
	}elseif ($_order=='optimal') {
		if ($stock_percentages=='horizontal')
			$order='`Product Department Optimal Availability Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Optimal Availability Products`';
	}elseif ($_order=='low') {
		if ($stock_percentages=='horizontal')
			$order='`Product Department Low Availability Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Low Availability Products`';
	}elseif ($_order=='critical') {
		if ($stock_percentages=='horizontal')
			$order='`Product Department Critical Availability Products`/`Product Department For Public Sale Products`';
		else
			$order='`Product Department Critical Availability Products`';
	}elseif ($order=='from') {
		$order='`Product Department Valid From`';
	}elseif ($order=='to') {
		$order='`Product Department Valid To`';
	}elseif ($order=='last_update') {
		$order='`Product Department Last Updated`';
	}elseif ($order=='products_for_sale') {
		$order='`Product Department For Public Sale Products`';
	}elseif ($order=='percentage_out_of_stock') {
		$order='`Product Department Out Of Stock Products`/`Product Department For Public Sale Products`';
	}elseif ($order=='sales_1q') {
		$order='`Product Department 1 Quarter Acc Invoiced Amount`';
	}elseif ($order=='delta_sales_1q') {
		$order='(`Product Department 1 Quarter Acc Invoiced Amount`-`Product Department 1 Quarter Acc 1YB Invoiced Amount`)/`Product Department 1 Quarter Acc 1YB Invoiced Amount`';
	}elseif ($order=='customers_active') {
		$order='`Product Department Active Customers`';
	}elseif ($order=='customers_active_75') {
		$order='`Product Department Active Customers More 0.75 Share`';
	}elseif ($order=='customers_active_50') {
		$order='`Product Department Active Customers More 0.5 Share`';
	}elseif ($order=='customers_active_25') {
		$order='`Product Department Active Customers More 0.25 Share`';
	}

	elseif ($order=='customers_losing') {
		$order='`Product Department Losing Customers`';
	}elseif ($order=='customers_losing_75') {
		$order='`Product Department Losing Customers More 0.75 Share`';
	}elseif ($order=='customers_losing_50') {
		$order='`Product Department Losing Customers More 0.5 Share`';
	}elseif ($order=='customers_losing_25') {
		$order='`Product Department Losing Customers More 0.25 Share`';
	}

	elseif ($order=='customers_lost') {
		$order='`Product Department Lost Customers`';
	}elseif ($order=='customers_lost_75') {
		$order='`Product Department Lost Customers More 0.75 Share`';
	}elseif ($order=='customers_lost_50') {
		$order='`Product Department Lost Customers More 0.5 Share`';
	}elseif ($order=='customers_lost_25') {
		$order='`Product Department Lost Customers More 0.25 Share`';
	}elseif ($_order=='todo') {
		$order='`Product Department In Process Products`';
	}elseif ($_order=='discontinued') {
		$order='`Product Department Discontinued Products`';
	}elseif ($_order=='discontinued') {
		$order='`Product Department Discontinued Products`';
	}elseif ($_order=='not_for_sale') {
		$order='`Product Department Not For Sale Products`';
	}elseif ($_order=='public_sale') {
		$order='`Product Department For Public Sale Products`';
	}elseif ($_order=='private_sale') {
		$order='`Product Department For Private Sale Products`';
	}elseif ($_order=='historic') {
		$order='`Product Department Historic Products`';
	}elseif ($_order=='store') {
		$order='`Product Department Store Code`';
	}



?>
