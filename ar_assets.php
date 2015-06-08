<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.1
*/

require_once 'common.php';
//require_once 'stock_functions.php';
require_once 'class.Store.php';

require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';

require_once 'class.Order.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
//require_once 'common_functions.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

if (count($user->stores)==0) return;


$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case 'favorite_products':

list_favorite_products();
break;
case ('get_history_numbers'):
	$data=prepare_values($_REQUEST,array(
			'subject'=>array('type'=>'string'),
			'subject_key'=>array('type'=>'key')

		));
	get_history_numbers($data);
	break;
case ('products_availability_timeline'):

	list_products_availability_timeline();
	break;
case ('get_families_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key')

		));
	get_families_elements_numbers($data);
	break;
case ('get_products_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key')

		));
	get_products_elements_numbers($data);
	break;
case('get_interval_products_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	get_interval_products_elements_numbers($data);
	break;
case('get_interval_families_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	get_interval_families_elements_numbers($data);
	break;
case('get_asset_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	get_asset_sales_data($data);
	break;



case('product_sales_report'):
	list_product_sales_report();
	break;
case('family_sales_report'):
	list_family_sales_report();
	break;
case('department_sales_report'):
	list_department_sales_report();
	break;

case('family_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'family_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	family_sales_data($data);
	break;
case('product_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'product_pid'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	family_sales_data($data);
	break;
case('department_sales_data'):
	$data=prepare_values($_REQUEST,array(
			'deparment_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	family_sales_data($data);
	break;

case('customers_who_order_product'):
	list_customers_who_order_product();
	break;
case('products_lists'):
	list_products_lists();
	break;

case('new_list'):

	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'store_id'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_products_list($data);
	break;

case('is_valid_family_code'):


	$family_code=$_REQUEST['code'];

	if (isset($_REQUEST['code'])!="") {
		$sql=sprintf("select * from `Product Family Dimension` where `Product Family Code`=%s  order by `Product Family Key`  ",prepare_mysql($family_code));
		//print($sql);

		$res=mysql_query($sql);
		$count=mysql_num_rows($res);
		if ($count==0) {
			$response= array('state'=>400,'found'=>'no','msg'=>_("You have entered unexisting family"));
			echo json_encode($response);
			exit;

		} else {
			$response= array('state'=>200,'found'=>'yes','msg'=>_("Family found"));
			echo json_encode($response);
			exit;
		}
	}
	break;


case('is_store_code'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	is_store_code($data);
	break;
case('is_store_vat'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	is_store_vat($data);
	break;
case('is_store_company_number'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	is_store_company_number($data);
	break;
case('is_department_code'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_department_code($data);
	break;
case('is_family_code'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_family_code($data);
	break;
case('is_store_name'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	is_store_name($data);
	break;
case('is_family_name'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_family_name($data);
	break;
case('is_department_name'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_department_name($data);
	break;
case('is_family_special_char'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_family_special_char($data);
	break;

case('is_product_code'):
	if ($_REQUEST['store_key']=='') {
		$response=array('state'=>404,'msg'=>_('Select Store First'));
		echo json_encode($response);
		exit;
	}
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_product_code($data);
	break;
case('is_product_name'):
	$data=prepare_values($_REQUEST,array(
			'store_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_product_name($data);
	break;

case('is_product_special_char'):
	$data=prepare_values($_REQUEST,array(
			'family_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_product_special_char($data);
	break;

case('charges'):
	list_charges();
	break;

case('product_server'):
	list_products_with_same_code();
	break;
case('customers_per_store'):
	list_customers_per_store();
	break;

case('orders_per_store'):
	list_orders_per_store();
	break;
case('invoices_per_store'):
	list_invoices_per_store();
	break;
case('delivery_notes_per_store'):
	list_delivery_notes_per_store();
	break;
case('delivery_notes_per_part'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key')
		));
	list_delivery_notes_per_part($data);
	break;
case('product_code_timeline'):
	product_code_timeline();
	break;


case('families'):
	list_families();
	break;
case('stores'):
	list_stores();
	break;
case('departments'):
	list_departments();
	break;
case('product'):
case('products'):
	list_products();
	break;

default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}



function list_departments() {


	global $user,$corporate_currency;

	if (isset( $_REQUEST['parent_key']) and  is_numeric( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit();

	}


	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';


	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['departments'];

		$conf_table='store';
	}
	elseif ($parent=='none') {

		$conf=$_SESSION['state']['stores']['departments'];

		$conf_table='stores';
	}
	else {

		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];



	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
	} else
		$percentages=$conf['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
	} else
		$period=$conf['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
	} else
		$avg=$conf['avg'];





	$_SESSION['state'][$conf_table]['departments']['order']=$order;
	$_SESSION['state'][$conf_table]['departments']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['departments']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['departments']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['departments']['where']=$where;
	$_SESSION['state'][$conf_table]['departments']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['departments']['f_value']=$f_value;

	$_SESSION['state'][$conf_table]['departments']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['departments']['period']=$period;
	$_SESSION['state'][$conf_table]['departments']['avg']=$avg;


	include_once 'splinters/departments_prepare_list.php';


	$sql="select count(*) as total from $table $where $wheref";
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
			mysql_free_result($result);
		}

	}

	$rtext=number($total_records)." ".ngettext('department','departments',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';





	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with code like")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with this description").": <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('department with code like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('department with this description')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';




	$_dir=$order_direction;
	$_order=$order;


	$period_tag=get_interval_db_name($period);



	//    print $period;

	//$order='`Product Department Code`';
	if ($_order=='families')
		$order='`Product Department Families`';
	elseif ($_order=='todo')
		$order='`Product Department In Process Products`';
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

		$order='`Product Department '.$period_tag.' Acc Invoiced Amount`';

	}


	elseif ($_order=='name')
		$order='`Product Department Name`';
	elseif ($_order=='code')
		$order='`Product Department Code`';
	elseif ($_order=='active')
		$order='`Product Department For Public Sale Products`';
	elseif ($_order=='outofstock')
		$order='`Product Department Out Of Stock Products`';
	elseif ($_order=='stock_error')
		$order='`Product Department Unknown Stock Products`';
	elseif ($_order=='surplus')
		$order='`Product Department Surplus Availability Products`';
	elseif ($_order=='optimal')
		$order='`Product Department Optimal Availability Products`';
	elseif ($_order=='low')
		$order='`Product Department Low Availability Products`';
	elseif ($_order=='critical')
		$order='`Product Department Critical Availability Products`';
	elseif ($_order=='discontinued')
		$order='`Product Department Discontinued Products`';
	elseif ($order=='from') {
		$order='`Product Department Valid From`';
	}elseif ($order=='to') {
		$order='`Product Department Valid To`';
	}elseif ($order=='last_update') {
		$order='`Product Department Last Updated`';
	}




	$sum_families=0;
	$sum_active=0;
	$sum_discontinued=0;

	$sum_todo=0;
	$sum_outofstock=0;
	$sum_stock_error=0;
	$sum_stock_value=0;
	$sum_surplus=0;
	$sum_optimal=0;
	$sum_low=0;
	$sum_critical=0;






	$sql="select sum(`Product Department Out Of Stock Products`) outofstock,sum(`Product Department Unknown Stock Products`)stock_error,
         sum(`Product Department Stock Value`)stock_value,sum(`Product Department Surplus Availability Products`)surplus,sum(`Product Department Optimal Availability Products`) optimal,
         sum(`Product Department Low Availability Products`) low,sum(`Product Department Critical Availability Products`) critical,
         sum(`Product Department In Process Products`) as todo,sum(`Product Department For Public Sale Products`) as sum_active, sum(`Product Department Discontinued Products`) as sum_discontinued,sum(`Product Department Families`) as sum_families
         from $table $where $wheref ";
	$result=mysql_query($sql);
	//print $sql;
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$sum_families=$row['sum_families'];
		$sum_active=$row['sum_active'];
		$sum_discontinued=$row['sum_discontinued'];
		$sum_todo=$row['todo'];
		$sum_outofstock=$row['outofstock'];
		$sum_stock_error=$row['stock_error'];
		$sum_stock_value=$row['stock_value'];
		$sum_surplus=$row['surplus'];
		$sum_optimal=$row['optimal'];
		$sum_low=$row['low'];
		$sum_critical=$row['critical'];
	}



	//$aws_p=money($row['Product Department Total Acc Avg Week Sales Per Product']);
	// $awp_p=money($row['Product Department Total Acc Avg Week Profit Per Product']);

	$sum_total_sales=0;
	$sum_month_sales=0;
	$sql="select  max(`Product Department $period_tag Acc Days Available`) as 'Product Department $period_tag Acc Days Available',max(`Product Department $period_tag Acc Days On Sale`) as 'Product Department $period_tag Acc Days On Sale', sum(if(`Product Department $period_tag Acc Profit`<0,`Product Department $period_tag Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department $period_tag Acc Profit`>=0,`Product Department $period_tag Acc Profit`,0)) as total_profit_plus,sum(`Product Department $period_tag Acc Invoiced Amount`) as sum_total_sales
	from $table $where $wheref  ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$sum_total_profit_plus=$row['total_profit_plus'];
		$sum_total_profit_minus=$row['total_profit_minus'];
		$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];

		if ($avg=='totals')
			$factor=1;
		elseif ($avg=='month') {
			if ($row['Product Department '.$period_tag.' Acc Days On Sale']>0)
				$factor=30.4368499/$row['Product Department '.$period_tag.' Acc Days On Sale'];
			else
				$factor=0;
		}
		elseif ($avg=='week') {
			if ($row['Product Department '.$period_tag.' Acc Days On Sale']>0)
				$factor=7/$row['Product Department '.$period_tag.' Acc Days On Sale'];
			else
				$factor=0;
		}
		elseif ($avg=='month_eff') {
			if ($row['Product Department '.$period_tag.' Acc Days Available']>0)
				$factor=30.4368499/$row['Product Department '.$period_tag.' Acc Days Available'];
			else
				$factor=0;
		}
		elseif ($avg=='week_eff') {
			if ($row['Product Department '.$period_tag.' Acc Days Available']>0)
				$factor=7/$row['Product Department '.$period_tag.' Acc Days Available'];
			else
				$factor=0;
		}
		$sum_total_sales=$row['sum_total_sales']*$factor;
		$sum_total_profit=$sum_total_profit*$factor;
		$_sum_total_sales=$row['sum_total_sales'];

	}
	mysql_free_result($result);




	$sql="select * from $table $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();
	//  print "$sql";
	global $myconf;
	$currency_code=$corporate_currency;
	$sum_active=0;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$currency_code=$row['Product Department Currency Code'];
		$code=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Code']);
		$name=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Name']);
		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Department Store Key'],$row['Product Department Store Code']);

		switch ($row['Product Department Sales Type']) {
		case 'Public Sale':
			$sales_type=_('Public Sale');
			break;
		case 'Private Sale':
			$sales_type=_('Private Sale');
			break;
		case 'Not for Sale':
			$sales_type=_('Not for Sale');
			break;
		}

		$aws_p=money($row['Product Department '.$period_tag.' Acc Avg Week Sales Per Product']);
		$awp_p=money($row['Product Department '.$period_tag.' Acc Avg Week Profit Per Product']);




		if ($percentages) {
			$families=percentage($row['Product Department Families'],$sum_families);

			$todo=percentage($row['Product Department In Process Products'],$sum_todo);
			$active=percentage($row['Product Department For Public Sale Products'],$sum_active);
			$discontinued=percentage($row['Product Department Discontinued Products'],$sum_discontinued);
			$outofstock=percentage($row['Product Department Out Of Stock Products'],$sum_outofstock);
			$stock_error=percentage($row['Product Department Unknown Stock Products'],$sum_stock_error);
			$stock_value=money($row['Product Department Stock Value'],$sum_stock_value);
			$surplus=percentage($row['Product Department Surplus Availability Products'],$sum_surplus);
			$optimal=percentage($row['Product Department Optimal Availability Products'],$sum_optimal);
			$low=percentage($row['Product Department Low Availability Products'],$sum_low);
			$critical=percentage($row['Product Department Critical Availability Products'],$sum_critical);


			$delta_sales='';


			$tsall=percentage($row['Product Department '.$period_tag.' Acc Invoiced Amount'],$_sum_total_sales,2);

			if ($row['Product Department '.$period_tag.' Acc Profit']>=0)
				$tprofit=percentage($row['Product Department '.$period_tag.' Acc Profit'],$sum_total_profit_plus,2);
			else
				$tprofit=percentage($row['Product Department '.$period_tag.' Acc Profit'],$sum_total_profit_minus,2);




		}
		else {
			//

			if ($avg=='totals') {
				$factor=1;
			}
			elseif ($avg=='month') {
				if ($row['Product Department '.$period_tag.' Acc Days On Sale']>0)
					$factor=30.4368499/$row['Product Department '.$period_tag.' Acc Days On Sale'];
				else
					$factor=0;
			}
			elseif ($avg=='week') {
				if ($row['Product Department '.$period_tag.' Acc Days On Sale']>0)
					$factor=7/$row['Product Department '.$period_tag.' Acc Days On Sale'];
				else
					$factor=0;
			}
			elseif ($avg=='month_eff') {
				if ($row['Product Department '.$period_tag.' Acc Days Available']>0)
					$factor=30.4368499/$row['Product Department '.$period_tag.' Acc Days Available'];
				else
					$factor=0;
			}
			elseif ($avg=='week_eff') {
				if ($row['Product Department '.$period_tag.' Acc Days Available']>0)
					$factor=7/$row['Product Department '.$period_tag.' Acc Days Available'];
				else
					$factor=0;
			}

			$tsall=$row['Product Department '.$period_tag.' Acc Invoiced Amount']*$factor;
			$tprofit=$row['Product Department '.$period_tag.' Acc Profit']*$factor;
			$delta_sales='';
			//print ($row['Product Department Total Acc Days On Sale']/30/12)."\n";










		}


		$sum_active+=$row['Product Department For Public Sale Products'];
		if (!$percentages) {
			$tsall=money($tsall,$row['Product Department Currency Code']);
			$tprofit=money($tprofit,$row['Product Department Currency Code']);
			$families=number($row['Product Department Families']);
			$todo=number($row['Product Department In Process Products']);
			$active=number($row['Product Department For Public Sale Products']);
			$discontinued=number($row['Product Department Discontinued Products']);
			$outofstock=number($row['Product Department Out Of Stock Products']);
			$stock_error=number($row['Product Department Unknown Stock Products']);
			$stock_value=money($row['Product Department Stock Value']);
			$surplus=number($row['Product Department Surplus Availability Products']);
			$optimal=number($row['Product Department Optimal Availability Products']);
			$low=number($row['Product Department Low Availability Products']);
			$critical=number($row['Product Department Critical Availability Products']);

		}
		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'store'=>$store,
			'families'=>$families,
			'active'=>$active,
			'todo'=>$todo,
			'discontinued'=>$discontinued,
			'outofstock'=>$outofstock,
			'stock_error'=>$stock_error,
			'stock_value'=>$stock_value,
			'surplus'=>$surplus,
			'optimal'=>$optimal,
			'low'=>$low,
			'critical'=>$critical,
			'sales_type'=>$sales_type,
			'sales'=>$tsall,
			'delta_sales'=>$delta_sales,
			'profit'=>$tprofit,
			'aws_p'=>$aws_p,
			'awp_p'=>$awp_p,
			'item_type'=>'item',
			'from'=>strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Department Valid From']." +00:00")),
			'to'=>(
				($row['Product Department Type']=='Historic')
				?strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Department Valid To']." +00:00")):''),
			'last_update'=>($row['Product Department Last Updated']==''?'':strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Department Last Updated']." +00:00"))),


		);


	}
	mysql_free_result($res);

	if ($total<=$number_results and $total>1) {

		if ($percentages) {

			if ($tsall!=0)$tsall='100.00%';
			else$tsall='';
			if ($tprofit!=0)$tprofit='100.00%';
			else$tprofit='';
			if ($sum_families!=0)$tfamilies='100.00%';
			else$tfamilies='';

			if ($sum_outofstock!=0)$outofstock='100.00%';
			else$outofstock='';
			if ($sum_stock_error!=0)$stock_error='100.00%';
			else$stock_error='';

			if ($sum_surplus!=0)$surplus='100.00%';
			else$surplus='';
			if ($sum_optimal!=0)$optimal='100.00%';
			else$optimal='';
			if ($sum_low!=0)$low='100.00%';
			else$low='';
			if ($sum_critical!=0)$critical='100.00%';
			else$critical='';

			if ($sum_active!=0)$active='100.00%';
			else$active='';
			if ($sum_discontinued!=0)$discontinued='100.00%';
			else$discontinued='';
		} else {
			$tsall=money($sum_total_sales,$currency_code);
			$tprofit=money($sum_total_profit,$currency_code);
			$tfamilies=number($sum_families);
			$outofstock=number($sum_outofstock);
			$stockerror=number($sum_stock_error);

			$surplus=number($sum_surplus);
			$optimal=number($sum_optimal);
			$low=number($sum_low);
			$critical=number($sum_critical);
			$active=number($sum_active);
			$discontinued=number($sum_discontinued);

		}

		$adata[]=array(

			'code'=>_('Total'),
			'families'=>$tfamilies,
			'active'=>number($sum_active),
			'sales'=>$tsall,
			'profit'=>$tprofit,
			'discontinued'=>number($sum_discontinued),
			'sales_type'=>'',
			'outofstock'=>$outofstock,
			'stock_error'=>$stock_error,
			'stock_value'=>$stock_value,
			'surplus'=>$surplus,
			'optimal'=>$optimal,
			'low'=>$low,
			'critical'=>$critical,
			'sales_type'=>$sales_type,
			'active'=>$active,
			'discontinued'=>$discontinued,
			'item_type'=>'total'



		);

	} else {
		$adata[]=array();

	}
	$total_records=ceil($total/$number_results)+$total;
	$number_results++;

	if ($start_from==0)
		$record_offset=0;
	else
		$record_offset=$start_from+1;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$record_offset,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_products() {

	global $user;


	if (count($user->stores)==0) return;

	$display_total=false;

	if (isset( $_REQUEST['list_key']))
		$list_key=$_REQUEST['list_key'];
	else
		$list_key=false;



	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['products'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['products'];
		$conf_table='department';
	}
	elseif ($parent=='family') {
		$conf=$_SESSION['state']['family']['products'];
		$conf_table='family';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['stores']['products'];
		$conf_table='stores';
	}elseif ($parent=='category') {
		$conf=$_SESSION['state']['product_categories']['products'];
		$conf_table='product_categories';
	}
	else {

		exit;
	}

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$conf['view'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['where']))
		$awhere=addslashes($_REQUEST['where']);
	else
		$awhere='';




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
	} else
		$percentages=$conf['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
	} else
		$period=$conf['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
	} else
		$avg=$conf['avg'];


	if (isset( $_REQUEST['avg_reorder'])) {
		$avg_reorder=$_REQUEST['avg_reorder'];
	} else
		$avg_reorder=$conf['avg_reorder'];


	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
	} else {
		$period=$conf['period'];
	}
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		$parent='store';//$conf['parent'];
	}
	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];




	if (isset( $_REQUEST['elements_stock_aux']))
		$elements_stock_aux=$_REQUEST['elements_stock_aux'];
	else
		$elements_stock_aux=$conf['elements_stock_aux'];



	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else
		$elements_type=$conf['elements_type'];

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];




	if (isset( $_REQUEST['elements_type_Historic'])) {
		$elements['type']['Historic']=$_REQUEST['elements_type_Historic'];
	}
	if (isset( $_REQUEST['elements_type_NoSale'])) {
		$elements['type']['NoSale']=$_REQUEST['elements_type_NoSale'];
	}
	if (isset( $_REQUEST['elements_type_Sale'])) {
		$elements['type']['Sale']=$_REQUEST['elements_type_Sale'];
	}
	if (isset( $_REQUEST['elements_type_Private'])) {
		$elements['type']['Private']=$_REQUEST['elements_type_Private'];
	}
	if (isset( $_REQUEST['elements_type_Discontinued'])) {
		$elements['type']['Discontinued']=$_REQUEST['elements_type_Discontinued'];
	}

	if (isset( $_REQUEST['elements_web_Offline'])) {
		$elements['web']['Offline']=$_REQUEST['elements_web_Offline'];
	}
	if (isset( $_REQUEST['elements_web_OutofStock'])) {
		$elements['web']['OutofStock']=$_REQUEST['elements_web_OutofStock'];
	}
	if (isset( $_REQUEST['elements_web_Online'])) {
		$elements['web']['Online']=$_REQUEST['elements_web_Online'];
	}

	if (isset( $_REQUEST['elements_web_Discontinued'])) {
		$elements['web']['Discontinued']=$_REQUEST['elements_web_Discontinued'];
	}


	if (isset( $_REQUEST['elements_stock_Error'])) {
		$elements['stock']['Error']=$_REQUEST['elements_stock_Error'];
	}
	if (isset( $_REQUEST['elements_stock_Excess'])) {
		$elements['stock']['Excess']=$_REQUEST['elements_stock_Excess'];
	}
	if (isset( $_REQUEST['elements_stock_Normal'])) {
		$elements['stock']['Normal']=$_REQUEST['elements_stock_Normal'];
	}
	if (isset( $_REQUEST['elements_stock_Low'])) {
		$elements['stock']['Low']=$_REQUEST['elements_stock_Low'];
	}
	if (isset( $_REQUEST['elements_stock_VeryLow'])) {
		$elements['stock']['VeryLow']=$_REQUEST['elements_stock_VeryLow'];
	}
	if (isset( $_REQUEST['elements_stock_OutofStock'])) {
		$elements['stock']['OutofStock']=$_REQUEST['elements_stock_OutofStock'];
	}




	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['products']['store']=$store;
	} else
		$store=$_SESSION['state']['products']['store'];


	//$_SESSION['state'][$conf_table]['table']['exchange_type']=$exchange_type;
	//$_SESSION['state'][$conf_table]['table']['exchange_value']=$exchange_value;
	//$_SESSION['state'][$conf_table]['table']['show_default_currency']=$show_default_currency;
	$_SESSION['state'][$conf_table]['products']['order']=$order;
	$_SESSION['state'][$conf_table]['products']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['products']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['products']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['products']['where']=$awhere;
	$_SESSION['state'][$conf_table]['products']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['products']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['products']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['products']['avg']=$avg;
	$_SESSION['state'][$conf_table]['products']['avg_reorder']=$avg_reorder;
	$_SESSION['state'][$conf_table]['products']['period']=$period;
	$_SESSION['state'][$conf_table]['products']['elements']=$elements;
	$_SESSION['state'][$conf_table]['products']['elements_type']=$elements_type;
	$_SESSION['state'][$conf_table]['products']['elements_stock_aux']=$elements_stock_aux;
	$_SESSION['state'][$conf_table]['products']['mode']=$mode;



	$filter_msg='';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;




	include 'splinters/products_prepare_list.php';

	$sql="select count(*) as total from $table  $where $wheref";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `Product Dimension`  $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$period_tag=get_interval_db_name($period);


	$_order=$order;
	$_order_dir=$order_dir;

	if ($order=='stock')
		$order='`Product Availability`';
	if ($order=='code' or $order=='codename')
		$order='`Product Code File As`';
	elseif ($order=='name')
		$order='`Product Name`';
	elseif ($order=='available_for')
		$order='`Product Available Days Forecast`';
	elseif ($order=='shortname')
		$order='`Product Available Days Forecast`';

	if ($order=='profit') {

		$order='`Product '.$period_tag.' Acc Profit`';


	}
	elseif ($order=='sales') {
		$order='`Product '.$period_tag.' Acc Invoiced Amount`';
	}elseif ($order=='sales_reorder') {
		$order='`Product '.$period_tag.' Acc Invoiced Amount`';
	}elseif ($_order=='delta_sales') {
		$order='`Product '.$period_tag.' Acc Invoiced Amount`';

	}
	elseif ($order=='margin') {
		$order='`Product '.$period_tag.' Margin`';


	}
	elseif ($order=='sold') {
		$order='`Product '.$period_tag.' Acc Quantity Invoiced`';
	}elseif ($order=='sold_reorder') {
		$order='`Product '.$period_tag.' Acc Quantity Invoiced`';
	}
	elseif ($order=='family') {
		$order='`Product Family`Code';
	}
	elseif ($order=='dept') {
		$order='`Product Main Department Code`';
	}
	elseif ($order=='expcode') {
		$order='`Product Tariff Code`';
	}
	elseif ($order=='parts') {
		$order='`Product XHTML Parts`';
	}
	elseif ($order=='supplied') {
		$order='`Product XHTML Supplied By`';
	}
	elseif ($order=='gmroi') {
		$order='`Product GMROI`';
	}
	elseif ($order=='state') {
		$order='`Product Sales Type`';
	}
	elseif ($order=='web') {
		$order='`Product Web Configuration`';
	}
	elseif ($order=='stock_state') {
		$order='`Product Availability State`';
	}
	elseif ($order=='stock_forecast') {
		$order='`Product Available Days Forecast`';
	}
	elseif ($order=='formated_record_type') {
		$order='`Product Record Type`';
	}
	elseif ($order=='store') {
		$order='`Store Code`';
	}elseif ($order=='price') {
		$order='`Product Price`';
	}elseif ($order=='from') {
		$order='`Product Valid From`';
	}elseif ($order=='to') {
		$order='`Product Valid To`';
	}elseif ($order=='last_update') {
		$order='`Product Last Updated`';
	}elseif ($order=='package_type') {
		$order='`Product Package Type`';
	}elseif ($order=='package_weight') {
		$order='`Product Package Weight`';
	}elseif ($order=='Package') {
		$order='`Product Package Dimensions Volume`';
	}elseif ($order=='package_volume') {
		$order='`Product Package Dimensions Volume`';
	}elseif ($order=='unit_weight') {
		$order='`Product Unit Weight`';
	}elseif ($order=='unit_dimension') {
		$order='`Product Unit Dimensions Volume`';
	}elseif ($order=='1m_avg_sold_over_1y') {
		$order='`Product 1 Year Acc Quantity Invoiced`';
	}elseif ($order=='days_available_over_1y') {
		$order='`Product 1 Year Acc Days On Sale`';
	}elseif ($order=='percentage_available_1y') {
		$order='`Product 1 Year Acc Days Available`/`Product 1 Year Acc Days On Sale`';
	}










	$db_interval=get_interval_db_name($period);



	$sum_total_sales=0;
	$sum_total_profit=0;
	$sum_total_stock_value=0;


	if ($percentages) {

		$sum_total_stock_value=0;
		$sql="select sum(`Product Stock Value`) as sum_stock_value from `Product Dimension` $where $wheref";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sum_total_stock_value=$row['sum_stock_value'];
		}




		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Product $db_interval Acc Profit`<0,`Product $db_interval Acc Profit`,0)) as total_profit_minus,sum(if(`Product $db_interval Acc Profit`>=0,`Product $db_interval Acc Profit`,0)) as total_profit_plus,sum(`Product $db_interval Acc Invoiced Amount`) as sum_total_sales ,sum(`Product Stock Value`) as sum_stock_value  from `Product Dimension` $where $wheref     ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];

		}



	}

	//`Product $db_interval Acc 1YB Invoiced Amount`
	//print $db_interval;

	if (!($db_interval=='Total' or $db_interval=='3 Year')) {
		$fields="`Product $db_interval Acc 1YB Invoiced Amount`,";
	}else {
		$fields='';
	}

	$sql="select P.`Product Valid To`,P.`Product ID`,`Product Code`,`Store Currency Code`,`Product Price`,`Product Units Per Case`,`Product $db_interval Acc Invoiced Amount`,`Product $db_interval Acc Profit`,`Product $db_interval Acc Days On Sale`,`Product $db_interval Acc Days Available` ,
	$fields
	`Product $db_interval Acc Quantity Invoiced`,`Product $db_interval Acc Margin`,`Product Availability`,`Product Sales Type`,`Product Stage`,`Product Main Type`,`Product Package Type`,`Product Web State`,`Product Store Key`,`Store Code`,`Product Web Configuration`,`Product Availability State`,
	`Product Available Days Forecast`,`Product Record Type`,`Product Currency`,`Product XHTML Short Description`,`Product Main Image`,`Product Name`,`Product Valid From`,`Product Last Updated`,
	`Product Family Name`,`Product Main Department Name`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product GMROI`,
	`Product Stock Value`,`Product Package Weight`,`Product Package XHTML Dimensions`,`Product Package Dimensions Volume`,`Product Unit Weight`,`Product Unit XHTML Dimensions`

	from  $table $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	//print $sql;


	$res = mysql_query($sql);
	$adata=array();

	$counter=0;
	$total_units=0;

	$sum_unitary_price=0;
	$counter_unitary_price=0;
	$sum_sold=0;
	$sum_units=0;
	$sum_sales=0;
	$sum_profit=0;
	$count_margin=0;
	$sum_margin=0;



	//  print "P:$period $avg $sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$currency=$row['Store Currency Code'];

		$counter++;




		$counter_unitary_price++;
		$sum_unitary_price+=$row['Product Price']/$row['Product Units Per Case'];




		if ($percentages) {
			$delta_sales='';
			$sold='';
			$margin='';
			$tsall=percentage($row["Product $db_interval Acc Invoiced Amount"],$sum_total_sales,2);
			if ($row["Product $db_interval Acc Profit"]>=0)
				$tprofit=percentage($row["Product $db_interval Acc Profit"],$sum_total_profit_plus,2);
			else
				$tprofit=percentage($row["Product $db_interval Acc Profit"],$sum_total_profit_minus,2);
		}
		else {


			if ($avg=='totals')
				$factor=1;
			elseif ($avg=='month') {
				if ($row["Product $db_interval Acc Days On Sale"]>0)
					$factor=30.4368499/$row["Product $db_interval Acc Days On Sale"];
				else
					$factor='ND';
			}
			elseif ($avg=='week') {
				if ($row["Product $db_interval Acc Days On Sale"]>0)
					$factor=7/$row["Product $db_interval Acc Days On Sale"];
				else
					$factor='ND';
			}
			elseif ($avg=='month_eff') {
				if ($row["Product $db_interval Acc Days Available"]>0)
					$factor=30.4368499/$row["Product $db_interval Acc Days Available"];
				else
					$factor='ND';
			}
			elseif ($avg=='week_eff') {
				if ($row["Product $db_interval Acc Days Available"]>0)
					$factor=7/$row["Product $db_interval Acc Days Available"];
				else
					$factor='ND';
			}
			if ($factor=='ND') {
				$delta_sales='';
				$tsall=_('ND');
				$tprofit=_('ND');
				$sold=_('ND');
			} else {
				$delta_sales='';
				$tsall=($row["Product $db_interval Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Product $db_interval Acc Profit"]*$factor);
				$sold=$row["Product $db_interval Acc Quantity Invoiced"]*$factor;
			}

			if (!($db_interval=='Total' or $db_interval=='3 Year'))
				$delta_sales=delta($row["Product $db_interval Acc Invoiced Amount"],$row["Product $db_interval Acc 1YB Invoiced Amount"]);

			$margin=$row["Product $db_interval Acc Margin"];





		}




		if ($avg_reorder=='totals')
			$factor=1;
		elseif ($avg_reorder=='month') {
			if ($row["Product $db_interval Acc Days On Sale"]>0)
				$factor=30.4368499/$row["Product $db_interval Acc Days On Sale"];
			else
				$factor='ND';
		}
		elseif ($avg_reorder=='week') {
			if ($row["Product $db_interval Acc Days On Sale"]>0)
				$factor=7/$row["Product $db_interval Acc Days On Sale"];
			else
				$factor='ND';
		}
		elseif ($avg_reorder=='month_eff') {
			if ($row["Product $db_interval Acc Days Available"]>0)
				$factor=30.4368499/$row["Product $db_interval Acc Days Available"];
			else
				$factor='ND';
		}
		elseif ($avg_reorder=='week_eff') {
			if ($row["Product $db_interval Acc Days Available"]>0)
				$factor=7/$row["Product $db_interval Acc Days Available"];
			else
				$factor='ND';
		}

		if ($factor=='ND') {
			$sales_reorder=_('ND');
			$sold_reorder=_('ND');
		} else {
			$sales_reorder=($row["Product $db_interval Acc Invoiced Amount"]*$factor);
			$sold_reorder=$row["Product $db_interval Acc Quantity Invoiced"]*$factor;
		}





		if ($db_interval=='Total' or $db_interval=='3 Year') {

			$avg_sold_over='';
			$days_available_over='';
			$percentage_available='';
		}else {
			$avg_sold_over='';
			$days_available_over='';
			$percentage_available='';
		}


		//   '1m_avg_sold_over_1y'=>ceil($row['Product 1 Year Acc Quantity Invoiced']/12),
		// 'days_available_over_1y'=>$row['Product 1 Year Acc Days On Sale'],
		// 'percentage_available_1y'=>percentage($row['Product 1 Year Acc Days Available'],$row['Product 1 Year Acc Days On Sale'])



		if (is_numeric($row['Product Availability']))
			$stock=number($row['Product Availability']);
		else
			$stock='?';

		$sum_sold+=$sold;
		$sum_units+=$sold*$row['Product Units Per Case'];

		$sum_sales+=$tsall;
		$sum_profit+=$tprofit;


		if ($margin=='') {
			if ($sold=='')
				$margin=_('NA');
			else
				$margin=_('ND');

		} else {
			$count_margin++;
			$sum_margin+=$margin;
			$margin=number($margin,1)."%";
		}

		if ($sold=='') {
			$sold=_('NA');
		}
		if ($sold_reorder=='') {
			$sold_reorder=_('NA');
		}

		$type=$row['Product Sales Type'];
		if ($row['Product Stage']=='In Process')
			$type.='<span style="color:red">*</span>';

		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		//$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Store Key'],$row['Store Code']);
		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Store Key'],$row['Store Code']);

		//'Historic','Discontinued','Private','NoSale','Sale'
		switch ($row['Product Main Type']) {
		case('Historic'):
			$main_type=_('Historic');
			break;
		case('Private'):
			$main_type=_('Private');
			break;
		case('NoSale'):
			$main_type=_('Not for Sale');
		case('Discontinued'):
			$main_type=_('Discontinued');
			break;
		case('Sale'):
			$main_type=_('For Sale');
			break;
		default:
			$main_type=$row['Product Main Type'];

		}

		switch ($row['Product Package Type']) {
		case('Bottle'):
			$package_type=_('Bottle');
			break;
		case('Bag'):
			$package_type=_('Bag');
			break;
		case('Box'):
			$package_type=_('Box');
		case('None'):
			$package_type=_('None');
			break;
		case('Other'):
			$package_type=_('Other');
			break;
		default:
			$package_type=$row['Product Package Type'];

		}


		$web_configuration='';
		switch ($row['Product Web State']) {

		case('For Sale'):
			if ($row['Product Web Configuration']=='Online Force For Sale')
				$web_configuration='('._('forced').')';

			$formated_web_configuration='<span class="web_online">'._('Online')." $web_configuration</span>";
			break;
		case('Offline'):
			if ($row['Product Web Configuration']=='Offline')
				$web_configuration='('._('forced').')';
			if ($row['Product Web Configuration']=='Online Auto')
				$web_configuration='('._('auto').')';

			$formated_web_configuration='<span class="web_offline">'._('Offline')." $web_configuration</span>";
			break;
		case('Out of Stock'):
			if ($row['Product Web Configuration']=='Online Force Out of Stock')
				$web_configuration='('._('forced').')';
			$formated_web_configuration='<span class="web_out_of_stock">'._('Out of Stock')." $web_configuration</span>";
			break;
		case('Discontinued'):
			$formated_web_configuration='<span class="web_discontinued">'._('Discontinued')." $web_configuration</span>";
			break;
		default:
			$formated_web_configuration=$row['Product Web State'];

		}

		include_once 'locale.php';
		global $locale_product_record_type;

		$stock_state=$row['Product Availability State'];
		$stock_forecast=interval($row['Product Available Days Forecast']);

		$record_type=$row['Product Record Type'];

		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'price'=>money($row['Product Price'],$row['Product Currency']),
			'name'=>$row['Product XHTML Short Description'],
			'smallname'=>'<span >'.$row['Product XHTML Short Description'].'</span>',
			'formated_record_type'=>$record_type,
			'record_type'=>$row['Product Record Type'],
			'stock_state'=>$stock_state,
			'stock_forecast'=>$stock_forecast,
			'family'=>$row['Product Family Name'],
			'dept'=>$row['Product Main Department Name'],
			//'expcode'=>$row['Product Tariff Code'],
			'parts'=>$row['Product XHTML Parts'],
			'supplied'=>$row['Product XHTML Supplied By'],
			'gmroi'=>$row['Product GMROI'],
			'stock_value'=>money($row['Product Stock Value']),
			'stock'=>$stock,
			'sales'=>(is_numeric($tsall)?money($tsall,$currency):$tsall),
			'sales_reorder'=>(is_numeric($sales_reorder)?money($sales_reorder,$currency):$sales_reorder),
			'delta_sales'=>$delta_sales,
			'profit'=>(is_numeric($tprofit)?money($tprofit,$currency):$tprofit),
			'margin'=>$margin,
			'sold'=>(is_numeric($sold)?number($sold):$sold),
			'sold_reorder'=>(is_numeric($sold_reorder)?number($sold_reorder):$sold_reorder),
			'state'=>$main_type,
			'web'=>$formated_web_configuration,
			'image'=>$row['Product Main Image'],
			'item_type'=>'item',
			'name_only'=>$row['Product Name'],
			'units'=>$row['Product Units Per Case']."x",
			'from'=>strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Valid From']." +00:00")),
			'to'=>(
				($row['Product Main Type']=='Historic' or $row['Product Main Type']=='Discontinued')
				?strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Valid To']." +00:00")):''),
			'last_update'=>($row['Product Last Updated']==''?'':strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Last Updated']." +00:00"))),
			"package_type"=>$package_type,



			"package_weight"=>weight($row['Product Package Weight'],'Kg',3,false,true),
			"package_dimension"=>$row['Product Package XHTML Dimensions'],
			"package_volume"=>volume($row['Product Package Dimensions Volume']),
			"unit_weight"=>weight($row['Product Unit Weight'],'Kg',3,false,true),
			"unit_dimension"=>$row['Product Unit XHTML Dimensions'],
			'days_available_over'=>$days_available_over,
			'percentage_available'=>$percentage_available




		);
	}
	mysql_free_result($res);

	if ($total<=$number_results) {

		if ($percentages) {
			$tsall='100.00%';
			$tprofit='100.00%';
			$tstock_value='100.00%';
		} else {
			$tsall=money($sum_total_sales);
			$tprofit=money($sum_total_profit);
			$tstock_value=money($sum_total_stock_value);

		}


		$total_title='Total';
		if ($view=='sales')
			$total_title=_('Total');

		if ($counter_unitary_price>0)
			$average_unit_price=$sum_unitary_price/$counter_unitary_price;
		else
			$average_unit_price=_('ND');
		if ($count_margin>0)
			$avg_margin='&lang;'.number($sum_margin/$count_margin,1)."%&rang;";
		else
			$avg_margin=_('ND');
		$adata[]=array(

			'code'=>$total_title,
			'name'=>'',
			'shortname'=>number($sum_units).'x',
			'stock_value'=>$tstock_value,
			'sold'=>number($sum_sold),
			'sales'=>money($sum_sales),
			'profit'=>money($sum_profit),
			'margin'=>$avg_margin,
			'item_type'=>'total',
			'type'=>'total'
		);


		// $total_records=ceil($total_records/$number_results)+$total_records;
	} else {
		$adata[]=array();

	}

	$total_records=ceil($total/$number_results)+$total;
	$number_results++;

	if ($start_from==0)
		$record_offset=0;
	else
		$record_offset=$start_from+1;


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);
}








function list_products_with_same_code() {
	$conf=$_SESSION['state']['product']['server'];
	$tableid=0;
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];






	$code=$_SESSION['state']['product']['server']['tag'];
	$where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));
	$wheref='';

	$order_direction=$order_dir;
	$_order=$order;
	$_dir=$order_direction;
	if ($order=='store')
		$order='`Store Name`';

	$sql="select *  from `Product Dimension` left join `Store Dimension` S  on (`Store Key`=`Product Store Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);
	$number_results=mysql_num_rows($res);

	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$id=sprintf("<a href='product.php?pid=%d'>%05d</a>",$row['Product ID'],$row['Product ID']);
		$store=sprintf("<a href='product.php?pid=%d'>%s</a>",$row['Product Store Key'],$row['Store Code']);
		$adata[]=array(
			'id'=>$id,
			'description'=>$row['Product XHTML Short Description'],
			'store'=>$store,
			'parts'=>$row['Product XHTML Parts']
		);

	}
	mysql_free_result($res);
	$rtext=number($number_results).' '._('products with the same code');
	$rtext_rpp='';
	$filter_msg='';
	$total_records=$number_results;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);

	echo json_encode($response);
}



function list_families() {

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit();
	}
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit();
	}

	if ($parent=='department') {

		$conf=$_SESSION['state']['department']['families'];
		$conf_table='department';

	}
	elseif ($parent=='store') {

		$conf=$_SESSION['state']['store']['families'];
		$conf_table='store';

	}
	elseif ($parent=='category') {

		$conf=$_SESSION['state']['family_categories']['families'];
		$conf_table='family_categories';

	}
	elseif ($parent=='none') {

		$conf=$_SESSION['state']['stores']['families'];
		$conf_table='stores';
	}
	else {

		return;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];



	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];

	} else
		$percentages=$conf['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	} else
		$period=$conf['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];

	} else
		$avg=$conf['avg'];




	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];



	if (isset( $_REQUEST['elements_family_discontinued'])) {
		$elements['Discontinued']=$_REQUEST['elements_family_discontinued'];
	}
	if (isset( $_REQUEST['elements_family_discontinuing'])) {
		$elements['Discontinuing']=$_REQUEST['elements_family_discontinuing'];
	}
	if (isset( $_REQUEST['elements_family_normal'])) {
		$elements['Normal']=$_REQUEST['elements_family_normal'];
	}
	if (isset( $_REQUEST['elements_family_inprocess'])) {
		$elements['InProcess']=$_REQUEST['elements_family_inprocess'];
	}

	if (isset( $_REQUEST['elements_family_nosale'])) {
		$elements['NoSale']=$_REQUEST['elements_family_nosale'];
	}



	$filter_msg='';



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	//print_r($_SESSION['state']['department']);

	$_SESSION['state'][$conf_table]['families']['order']=$order;
	$_SESSION['state'][$conf_table]['families']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['families']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['families']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['families']['where']=$where;
	$_SESSION['state'][$conf_table]['families']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['families']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['families']['period']=$period;
	$_SESSION['state'][$conf_table]['families']['avg']=$avg;
	$_SESSION['state'][$conf_table]['families']['percentages']=$percentages;

	$_SESSION['state'][$conf_table]['families']['mode']=$mode;
	$_SESSION['state'][$conf_table]['families']['elements']=$elements;
	$_SESSION['state'][$conf_table]['families']['parent']=$parent;


	include_once 'splinters/families_prepare_list.php';

	$sql="select count(*) as total from $table $where $wheref";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table $where";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	$rtext=number($total_records)." ".ngettext('family','families',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",number($number_results),_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with code like")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with this name").": <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with this name')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';




	$period_tag=get_interval_db_name($period);


	$_order=$order;
	$_dir=$order_direction;
	// $order='`Product Family Code`';
	if ($order=='profit') {
		$order='`Product Family '.$period_tag.' Acc Profit`';

	}
	elseif ($order=='sales') {
		$order='`Product Family '.$period_tag.' Acc Invoiced Amount`';

	} elseif ($_order=='delta_sales') {
		$order='`Product Family '.$period_tag.' Acc Invoiced Amount`';

	}
	elseif ($order=='code')
		$order='`Product Family Code`';
	elseif ($order=='stock_value')
		$order='`Product Family Stock Value`';
	elseif ($order=='name')
		$order='`Product Family Name`';
	elseif ($order=='active')
		$order='`Product Family For Public Sale Products`';
	elseif ($order=='discontinued')
		$order='`Product Family Discontinued Products`';
	elseif ($order=='todo')
		$order='`Product Family In Process Products`';
	elseif ($order=='notforsale')
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
	}
	else
		$order='`Product Family Code`';



	$sum_active=0;
	$sum_discontinued=0;
	$sum_new=0;
	$sum_todo=0;
	$sql="select sum(`Product Family In Process Products`) as sum_todo,sum(`Product Family For Public Sale Products`) as sum_active, sum(`Product Family Discontinued Products`) as sum_discontinued  from $table  $where $wheref ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$sum_discontinued=$row['sum_discontinued'];
		$sum_active=$row['sum_active'];
		$sum_todo=$row['sum_todo'];
	}






	$sum_total_sales=0;
	$sum_month_sales=0;
	$sql="select sum(if(`Product Family $period_tag Acc Profit`<0,`Product Family $period_tag Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family $period_tag Acc Profit`>=0,`Product Family $period_tag Acc Profit`,0)) as total_profit_plus,sum(`Product Family $period_tag Acc Invoiced Amount`) as sum_total_sales   from  $table  $where $wheref   ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$sum_total_sales=$row['sum_total_sales'];

		$sum_total_profit_plus=$row['total_profit_plus'];
		$sum_total_profit_minus=$row['total_profit_minus'];
		$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
	}




	$sql="select *  from $table $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();
	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$code=sprintf('<a href="family.php?id=%d">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
		if ($percentages) {
			$delta_sales='';

			$tsall=percentage($row['Product Family '.$period_tag.' Acc Invoiced Amount'],$sum_total_sales,2);
			if ($row['Product Family '.$period_tag.' Acc Profit']>=0)
				$tprofit=percentage($row['Product Family '.$period_tag.' Acc Profit'],$sum_total_profit_plus,2);
			else
				$tprofit=percentage($row['Product Family '.$period_tag.' Acc Profit'],$sum_total_profit_minus,2);



		}
		else {




			$delta_sales='';




			if ($avg=='totals')
				$factor=1;
			elseif ($avg=='month') {
				if ($row['Product Family '.$period_tag.' Acc Days On Sale']>0)
					$factor=30.4368499/$row['Product Family '.$period_tag.' Acc Days On Sale'];
				else
					$factor=0;
			}
			elseif ($avg=='week') {
				if ($row['Product Family '.$period_tag.' Acc Days On Sale']>0)
					$factor=7/$row['Product Family '.$period_tag.' Acc Days On Sale'];
				else
					$factor=0;
			}
			elseif ($avg=='month_eff') {
				if ($row['Product Family '.$period_tag.' Acc Days Available']>0)
					$factor=30.4368499/$row['Product Family '.$period_tag.' Acc Days Available'];
				else
					$factor=0;
			}
			elseif ($avg=='week_eff') {
				if ($row['Product Family '.$period_tag.' Acc Days Available']>0)
					$factor=7/$row['Product Family '.$period_tag.' Acc Days Available'];
				else
					$factor=0;
			}

			$tsall=money($row['Product Family '.$period_tag.' Acc Invoiced Amount']*$factor);
			$tprofit=money($row['Product Family '.$period_tag.' Acc Profit']*$factor);
			$delta_sales='';



		}






		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Family Store Key'],$row['Product Family Store Code']);
		$department=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Family Main Department Key'],$row['Product Family Main Department Code']);

		$adata[]=array(

			'code'=>$code,
			'name'=>$row['Product Family Name'],
			'active'=>number($row['Product Family For Public Sale Products']),
			'todo'=>number($row['Product Family In Process Products']),
			'discontinued'=>number($row['Product Family Discontinued Products']),
			'notforsale'=>number($row['Product Family Not For Sale Products']),

			'outofstock'=>number($row['Product Family Out Of Stock Products']),
			'stock_error'=>number($row['Product Family Unknown Stock Products']),
			'stock_value'=>money($row['Product Family Stock Value']),
			'store'=>$store,
			'department'=>$department,
			'sales'=>$tsall,
			'delta_sales'=>$delta_sales,
			'profit'=>$tprofit,
			'surplus'=>number($row['Product Family Surplus Availability Products']),
			'optimal'=>number($row['Product Family Optimal Availability Products']),
			'low'=>number($row['Product Family Low Availability Products']),
			'critical'=>number($row['Product Family Critical Availability Products']),
			'image'=>$row['Product Family Main Image'],
			'type'=>'item',
			'item_type'=>'item',
			'from'=>($row['Product Family Valid From']==''?'':strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Family Valid From']." +00:00"))),
			'to'=>(
				($row['Product Family Record Type']=='Discontinued')
				?strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Family Valid To']." +00:00")):''),
			'last_update'=>($row['Product Family Last Updated']==''?'':strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Product Family Last Updated']." +00:00"))),


		);
	}
	mysql_free_result($res);

	if ($total<=$number_results and $total>1) {


		if ($percentages) {
			$tsall='100.00%';
			$tprofit='100.00%';
		} else {
			$tsall=money($sum_total_sales);
			$tprofit=money($sum_total_profit);
		}

		$adata[]=array(

			'code'=>_('Total'),
			'name'=>'',
			'active'=>number($sum_active),
			'discontinued'=>number($sum_discontinued),
			'todo'=>number($sum_todo),

			//    'outofstock'=>number($row['product family out of stock products']),
			//    'stockerror'=>number($row['product family unknown stock products']),
			//    'stock_value'=>money($row['product family stock value']),
			'sales'=>$tsall,
			'profit'=>$tprofit,
			'item_type'=>'total',


		);

	} else {
		$adata[]=array();
	}
	$total_records=ceil($total/$number_results)+$total;
	$number_results++;

	if ($start_from==0)
		$record_offset=0;
	else
		$record_offset=$start_from+1;

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);

	echo json_encode($response);

}

function list_stores() {
	global $user,$corporate_currency;

	$conf=$_SESSION['state']['stores']['stores'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['exchange_type']))
		$exchange_type=addslashes($_REQUEST['exchange_type']);
	else
		$exchange_type=$conf['exchange_type'];

	if (isset( $_REQUEST['exchange_value']))
		$exchange_value=addslashes($_REQUEST['exchange_value']);
	else
		$exchange_value=$conf['exchange_value'];

	if (isset( $_REQUEST['show_default_currency']))
		$show_default_currency=addslashes($_REQUEST['show_default_currency']);
	else
		$show_default_currency=$conf['show_default_currency'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['stores']['stores']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['stores']['stores']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['stores']['stores']['period']=$period;
	} else
		$period=$_SESSION['state']['stores']['stores']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['stores']['stores']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['stores']['stores']['avg'];


	$_SESSION['state']['stores']['stores']['exchange_type']=$exchange_type;
	$_SESSION['state']['stores']['stores']['exchange_value']=$exchange_value;
	$_SESSION['state']['stores']['stores']['show_default_currency']=$show_default_currency;
	$_SESSION['state']['stores']['stores']['order']=$order;
	$_SESSION['state']['stores']['stores']['order_dir']=$order_dir;
	$_SESSION['state']['stores']['stores']['nr']=$number_results;
	$_SESSION['state']['stores']['stores']['sf']=$start_from;
	$_SESSION['state']['stores']['stores']['where']=$where;
	$_SESSION['state']['stores']['stores']['f_field']=$f_field;
	$_SESSION['state']['stores']['stores']['f_value']=$f_value;

	if (count($user->stores)==0)
		$where="where false";
	else
		$where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
	$filter_msg='';
	$wheref=wheref_stores($f_field,$f_value);

	$sql="select count(*) as total from `Store Dimension`  S $where $wheref";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension` S  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';
	$period_tag=get_interval_db_name($period);

	$_dir=$order_direction;
	$_order=$order;
	$order='`Store Code`';
	if ($order=='families')
		$order='`Store Families`';
	elseif ($order=='departments')
		$order='`Store Departments`';
	elseif ($order=='code')
		$order='`Store Code`';
	elseif ($order=='todo')
		$order='`Store In Process Products`';
	elseif ($order=='discontinued')
		$order='`Store In Process Products`';
	elseif ($order=='profit') {
		$order='`Store '.$period_tag.' Profit`';

	}
	elseif ($order=='sales') {
		$order='`Store '.$period_tag.' Invoiced Amount`';



	}
	elseif ($order=='name')
		$order='`Store Name`';
	elseif ($order=='active')
		$order='`Store For Public Sale Products`';
	elseif ($order=='outofstock')
		$order='`Store Out Of Stock Products`';
	elseif ($order=='stock_error')
		$order='`Store Unknown Stock Products`';
	elseif ($order=='surplus')
		$order='`Store Surplus Availability Products`';
	elseif ($order=='optimal')
		$order='`Store Optimal Availability Products`';
	elseif ($order=='low')
		$order='`Store Low Availability Products`';
	elseif ($order=='critical')
		$order='`Store Critical Availability Products`';
	elseif ($order=='new')
		$order='`Store New Products`';


	$sql="select sum(`Store For Public Sale Products`) as sum_active,sum(`Store Families`) as sum_families  from `Store Dimension` S $where $wheref   ";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$sum_families=$row['sum_families'];
		$sum_active=$row['sum_active'];
	}
	mysql_free_result($result);

	global $myconf;




	$sum_total_sales=0;
	$sum_month_sales=0;
	$sum_total_profit_plus=0;
	$sum_total_profit_minus=0;
	$sum_total_profit=0;
	if ($exchange_type=='day2day') {
		$sql=sprintf("select sum(if(`Store DC $period_tag Acc Profit`<0,`Store DC $period_tag Acc Profit`,0)) as total_profit_minus,sum(if(`Store DC $period_tag Acc Profit`>=0,`Store DC $period_tag Acc Profit`,0)) as total_profit_plus,sum(`Store DC $period_tag Acc Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S  left join `Store Dimension` SD on (`SD`.`Store Key`=`S`.`Store Key`)  %s %s",$where,$wheref);
		// print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales+=$row['sum_total_sales'];

			$sum_total_profit_plus=+$row['total_profit_plus'];
			$sum_total_profit_minus=+$row['total_profit_minus'];
			$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);
	} else {
		$sql=sprintf("select sum(if(`Store $period_tag Acc Profit`<0,`Store $period_tag Acc Profit`,0)) as total_profit_minus,sum(if(`Store $period_tag Acc Profit`>=0,`Store $period_tag Acc Profit`,0)) as total_profit_plus,sum(`Store $period_tag Acc Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($corporate_currency));
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales+=$row['sum_total_sales']*$exchange_value;

			$sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
			$sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
			$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);

	}







	$sql="select *  from `Store Dimension` S left join `Store Data Dimension` D on (D.`Store Key`=S.`Store Key`) left join `Store Default Currency` DC on DC.`Store Key`=S.`Store Key`   $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	$sum_sales=0;
	$sum_profit=0;
	$sum_outofstock=0;
	$sum_low=0;
	$sum_optimal=0;
	$sum_critical=0;
	$sum_surplus=0;
	$sum_unknown=0;
	$sum_departments=0;
	$sum_families=0;
	$sum_todo=0;
	$sum_discontinued=0;
	$sum_new=0;
	$DC_tag='';
	if ($exchange_type=='day2day' and $show_default_currency  )
		$DC_tag=' DC';

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

		if ($percentages) {

			$tsall=percentage($row['Store DC '.$period_tag.' Invoiced Amount'],$sum_total_sales,2);
			if ($row['Store DC Total Profit']>=0)
				$tprofit=percentage($row['Store DC '.$period_tag.' Profit'],$sum_total_profit_plus,2);
			else
				$tprofit=percentage($row['Store DC '.$period_tag.' Profit'],$sum_total_profit_minus,2);
		} else {

			if ($avg=="totals")
				$factor=1;
			elseif ($avg=="month") {
				if ($row["Store $period_tag Acc Days On Sale"]>0)
					$factor=30.4368499/$row["Store $period_tag Acc Days On Sale"];
				else
					$factor=0;
			}
			elseif ($avg=="week") {
				if ($row["Store $period_tag Acc Days On Sale"]>0)
					$factor=7/$row["Store $period_tag Acc Days On Sale"];
				else
					$factor=0;
			}
			elseif ($avg=="month_eff") {
				if ($row["Store $period_tag Acc Days Available"]>0)
					$factor=30.4368499/$row["Store $period_tag Acc Days Available"];
				else
					$factor=0;
			}
			elseif ($avg=="week_eff") {
				if ($row["Store $period_tag Acc Days Available"]>0)
					$factor=7/$row["Store $period_tag Acc Days Available"];
				else
					$factor=0;
			}

			$tsall=($row["Store".$DC_tag." Total Acc Invoiced Amount"]*$factor);
			$tprofit=($row["Store".$DC_tag." Total Acc Profit"]*$factor);

		}

		$sum_sales+=$tsall;
		$sum_profit+=$tprofit;
		$sum_new+=$row['Store New Products'];

		$sum_low+=$row['Store Low Availability Products'];
		$sum_optimal+=$row['Store Optimal Availability Products'];
		$sum_low+=$row['Store Low Availability Products'];
		$sum_critical+=$row['Store Critical Availability Products'];
		$sum_surplus+=$row['Store Surplus Availability Products'];
		$sum_outofstock+=$row['Store Out Of Stock Products'];
		$sum_unknown+=$row['Store Unknown Stock Products'];
		$sum_departments+=$row['Store Departments'];
		$sum_families+=$row['Store Families'];
		$sum_todo+=$row['Store In Process Products'];
		$sum_discontinued+=$row['Store Discontinued Products'];


		if (!$percentages) {
			if ($show_default_currency) {
				$class='';
				if ($corporate_currency!=$row['Store Currency Code'])
					$class='currency_exchanged';


				$sales='<span class="'.$class.'">'.money($tsall).'</span>';
				$profit='<span class="'.$class.'">'.money($tprofit).'</span>';
				$margin='<span class="'.$class.'">'.percentage($tprofit,$tsall).'</span>';
			} else {
				$sales=money($tsall,$row['Store Currency Code']);
				$profit=money($tprofit,$row['Store Currency Code']);

				$margin=percentage($tprofit,$tsall);
			}
		} else {
			$sales=$tsall;
			$profit=$tprofit;
			$margin=percentage($profit,$sales);
		}

		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'departments'=>number($row['Store Departments']),
			'families'=>number($row['Store Families']),
			'active'=>number($row['Store For Public Sale Products']),
			'new'=>number($row['Store New Products']),
			'discontinued'=>number($row['Store Discontinued Products']),
			'outofstock'=>number($row['Store Out Of Stock Products']),
			'stock_error'=>number($row['Store Unknown Stock Products']),
			'stock_value'=>money($row['Store Stock Value']),
			'surplus'=>number($row['Store Surplus Availability Products']),
			'optimal'=>number($row['Store Optimal Availability Products']),
			'low'=>number($row['Store Low Availability Products']),
			'critical'=>number($row['Store Critical Availability Products']),
			'sales'=>$sales,
			'profit'=>$profit,
			'margin'=>$margin
		);
	}
	mysql_free_result($res);


	if ($total<=$number_results) {

		if ($percentages) {
			$sum_sales='100.00%';
			$sum_profit='100.00%';
			$margin=percentage($sum_total_profit,$sum_total_sales);

		} else {
			$sum_sales=money($sum_total_sales);
			$sum_profit=money($sum_total_profit);
			$margin=percentage($sum_total_profit,$sum_total_sales);
		}
		$sum_new=number($sum_new);
		$sum_outofstock=number($sum_outofstock);
		$sum_low=number($sum_low);
		$sum_optimal=number($sum_optimal);
		$sum_critical=number($sum_critical);
		$sum_surplus=number($sum_surplus);
		$sum_unknown=number($sum_unknown);
		$sum_departments=number($sum_departments);
		$sum_families=number($sum_families);
		$sum_todo=number($sum_todo);
		$sum_discontinued=number($sum_discontinued);
		$adata[]=array(
			'name'=>'',
			'code'=>_('Total'),
			'active'=>number($sum_active),
			'sales'=>$sum_sales,
			'profit'=>$sum_profit,
			'margin'=>$margin,
			'todo'=>$sum_todo,
			'discontinued'=>$sum_discontinued,
			'low'=>$sum_low,
			'new'=>$sum_new,
			'critical'=>$sum_critical,
			'surplus'=>$sum_surplus,
			'optimal'=>$sum_optimal,
			'outofstock'=>$sum_outofstock,
			'stock_error'=>$sum_unknown,
			'departments'=>$sum_departments,
			'families'=>$sum_families
		);
		$total_records++;
		$number_results++;
	}

	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//$total_records=ceil($total_records/$number_results)+$total_records;
	//$total_records=$total_records;
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_charges() {


	$parent='store';

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];

	if ($parent=='store')
		$parent_id=$_SESSION['state']['store']['id'];
	else
		return;

	$conf=$_SESSION['state'][$parent]['charges'];




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;





	$_SESSION['state'][$parent]['charges']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	// print_r($_SESSION['tables']['families_list']);

	//  print_r($_SESSION['tables']['families_list']);
	if ($parent=='store')
		$where=sprintf("where  `Store Key`=%d ",$parent_id);
	else
		$where=sprintf("where true ");

	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";








	$sql="select count(*) as total from `Charge Dimension`   $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Charge Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('charge','charges',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Charge Name`';
	elseif ($order=='description')
		$order='`Charge Description`,`Charge Terms Description`';
	else
		$order='`Charge Name`';


	$sql="select *  from `Charge Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$adata[]=array(
			'name'=>$row['Charge Name'],
			'description'=>$row['Charge Description'].' '.$row['Charge Terms Description'],


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function list_customers_per_store() {

	global $user;

	$conf=$_SESSION['state']['stores']['customers'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];





	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];

	} else
		$percentages=$_SESSION['state']['stores']['customers']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	} else
		$period=$_SESSION['state']['stores']['customers']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];

	} else
		$avg=$_SESSION['state']['stores']['customers']['avg'];



	$_SESSION['state']['stores']['customers']['percentage']=$percentages;
	$_SESSION['state']['stores']['customers']['period']=$period;
	$_SESSION['state']['stores']['customers']['avg']=$avg;
	$_SESSION['state']['stores']['customers']['order']=$order;
	$_SESSION['state']['stores']['customers']['order_dir']=$order_dir;
	$_SESSION['state']['stores']['customers']['nr']=$number_results;
	$_SESSION['state']['stores']['customers']['sf']=$start_from;
	$_SESSION['state']['stores']['customers']['where']=$where;
	$_SESSION['state']['stores']['customers']['f_field']=$f_field;
	$_SESSION['state']['stores']['customers']['f_value']=$f_value;
	// print_r($_SESSION['tables']['families_list']);

	//  print_r($_SESSION['tables']['families_list']);

	if (count($user->stores)==0)
		$where="where false";
	else {

		$where=sprintf("where `Store Key` in (%s)",join(',',$user->stores));
	}
	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Store Dimension`   $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';





	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';
	elseif ($order=='contacts')
		$order='`Store Contacts`';
	elseif ($order=='active_contacts')
		$order='active';
	elseif ($order=='new_contacts')
		$order='`Store New Contacts`';
	elseif ($order=='lost_contacts')
		$order='`Store Lost Contacts`';
	elseif ($order=='losing_contacts')
		$order='`Store Losing Contacts`';

	elseif ($order=='contacts_with_orders')
		$order='`Store Contacts`';
	elseif ($order=='active_contacts_with_orders')
		$order='active';
	elseif ($order=='new_contacts_with_orders')
		$order='`Store New Contacts`';
	elseif ($order=='lost_contacts_with_orders')
		$order='`Store Lost Contacts`';
	elseif ($order=='losing_contacts_with_orders')
		$order='`Store Losing Contacts`';

	else
		$order='`Store Code`';




	$sql="select `Store Key`,`Store Name`,`Store Code`,`Store Contacts`,`Store Total Users`, (`Store Active Contacts`+`Store Losing Contacts`) as active,`Store New Contacts`,`Store Lost Contacts`,`Store Losing Contacts`,
         `Store Contacts With Orders`,(`Store Active Contacts With Orders`+`Store Losing Contacts With Orders`)as active_with_orders,`Store New Contacts With Orders`,`Store Lost Contacts With Orders`,`Store Losing Contacts With Orders` from  `Store Dimension`    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";




	$res = mysql_query($sql);

	$total=mysql_num_rows($res);


	$total_contacts=0;
	$total_active_contacts=0;
	$total_new_contacts=0;
	$total_lost_contacts=0;
	$total_losing_contacts=0;
	$total_contacts_with_orders=0;
	$total_active_contacts_with_orders=0;
	$total_new_contacts_with_orders=0;
	$total_lost_contacts_with_orders=0;
	$total_losing_contacts_with_orders=0;



	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Code']);

		$total_contacts+=$row['Store Contacts'];

		$total_active_contacts+=$row['active'];
		$total_new_contacts+=$row['Store New Contacts'];
		$total_lost_contacts+=$row['Store Lost Contacts'];
		$total_losing_contacts+=$row['Store Losing Contacts'];
		$total_contacts_with_orders+=$row['Store Contacts With Orders'];
		$total_active_contacts_with_orders+=$row['active_with_orders'];
		$total_new_contacts_with_orders+=$row['Store New Contacts With Orders'];
		$total_lost_contacts_with_orders+=$row['Store Lost Contacts With Orders'];
		$total_losing_contacts_with_orders+=$row['Store Losing Contacts With Orders'];




		$contacts=number($row['Store Contacts']);
		$new_contacts=number($row['Store New Contacts']);
		$active_contacts=number($row['active']);
		$losing_contacts=number($row['Store Losing Contacts']);
		$lost_contacts=number($row['Store Lost Contacts']);
		$contacts_with_orders=number($row['Store Contacts With Orders']);
		$new_contacts_with_orders=number($row['Store New Contacts With Orders']);
		$active_contacts_with_orders=number($row['active_with_orders']);
		$losing_contacts_with_orders=number($row['Store Losing Contacts With Orders']);
		$lost_contacts_with_orders=number($row['Store Lost Contacts With Orders']);
		$total_users=$row['Store Total Users'];

		//  $contacts_with_orders=number($row['contacts_with_orders']);
		// $active_contacts=number($row['active_contacts']);
		// $new_contacts=number($row['new_contacts']);
		// $lost_contacts=number($row['lost_contacts']);
		// $new_contacts_with_orders=number($row['new_contacts']);


		/*
                if ($percentages) {
                    $contacts_with_orders=percentage($row['contacts_with_orders'],$total_contacts_with_orders);
                    $active_contacts=percentage($row['active_contacts'],$total_active);
                    $new_contacts=percentage($row['new_contacts'],$total_new);
                    $lost_contacts=percentage($row['los_contactst'],$total_lost);
                    $contacts=percentage($row['contacts'],$total_contacts);
                    $new_contacts_with_orders=percentage($row['new_contacts'],$total_new_contacts);

                } else {
                    $contacts_with_orders=number($row['contacts_with_orders']);
                    $active_contacts=number($row['active_contacts']);
                    $new_contacts=number($row['new_contacts']);
                    $lost_contacts=number($row['lost_contacts']);
                    $contacts=number($row['contacts']);
                    $new_contacts_with_orders=number($row['new_contacts']);

                }
        */
		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'contacts'=>$contacts,
			'active_contacts'=>$active_contacts,
			'new_contacts'=>$new_contacts,
			'lost_contacts'=>$lost_contacts,
			'losing_contacts'=>$losing_contacts,
			'contacts_with_orders'=>$contacts_with_orders,
			'active_contacts_with_orders'=>$active_contacts_with_orders,
			'new_contacts_with_orders'=>$new_contacts_with_orders,
			'lost_contacts_with_orders'=>$lost_contacts_with_orders,
			'losing_contacts_with_orders'=>$losing_contacts_with_orders,
			'users'=>$total_users


		);

	}
	mysql_free_result($res);




	if ($percentages) {
		$sum_total='100.00%';
		$sum_active='100.00%';
		$sum_new='100.00%';
		$sum_lost='100.00%';
		$sum_contacts='100.00%';
		$sum_new_contacts='100.00%';
	} else {
		$total_contacts=number($total_contacts);
		$total_active_contacts=number($total_active_contacts);
		$total_new_contacts=number($total_new_contacts);
		$total_lost_contacts=number($total_lost_contacts);
		$total_losing_contacts=number($total_losing_contacts);
		$total_contacts_with_orders=number($total_contacts_with_orders);
		$total_active_contacts_with_orders=number($total_active_contacts_with_orders);
		$total_new_contacts_with_orders=number($total_new_contacts_with_orders);
		$total_lost_contacts_with_orders=number($total_lost_contacts_with_orders);
		$total_losing_contacts_with_orders=number($total_losing_contacts_with_orders);

		// $sum_total=number($total_contacts_with_orders);
		// $sum_active=number($total_active_contacts);
		// $sum_new=number($total_new_contacts);
		// $sum_lost=number($total_lost_contacts);
		// $sum_contacts=number($total_contacts);
		// $sum_new_contacts=number($total_new_contacts);
	}


	$adata[]=array(
		'name'=>'',
		'code'=>_('Total'),
		'contacts'=>$total_contacts,
		'active_contacts'=>$total_active_contacts,
		'new_contacts'=>$total_new_contacts,
		'lost_contacts'=>$total_lost_contacts,
		'losing_contacts'=>$total_losing_contacts,
		'contacts_with_orders'=>$total_contacts_with_orders,
		'active_contacts_with_orders'=>$total_active_contacts_with_orders,
		'new_contacts_with_orders'=>$total_new_contacts_with_orders,
		'lost_contacts_with_orders'=>$total_lost_contacts_with_orders,
		'losing_contacts_with_orders'=>$total_losing_contacts_with_orders,
		'users'=>$total_users

		//               'customers'=>$sum_total,
		//             'active'=>$sum_active,
		//           'new'=>$sum_new,
		//         'lost'=>$sum_lost,
		//
		//     'new_contacts'=>$sum_new_contacts
	);


	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	$total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function list_orders_per_store() {
	global $user;
	$conf=$_SESSION['state']['stores']['orders'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else {
		$number_results=$conf['nr'];
	}




	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else {
		$where=$conf['where'];
	}


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];

	} else {
		$percentages=$_SESSION['state']['stores']['orders']['percentages'];
	}


	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	} else {
		$period=$_SESSION['state']['stores']['orders']['period'];
	}
	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];

	} else {
		$avg=$_SESSION['state']['stores']['orders']['avg'];
	}

	$_SESSION['state']['stores']['orders']['percentages']=$percentages;
	$_SESSION['state']['stores']['orders']['period']=$period;
	$_SESSION['state']['stores']['orders']['avg']=$avg;
	$_SESSION['state']['stores']['orders']['order']=$order;
	$_SESSION['state']['stores']['orders']['order_dir']=$order_direction;
	$_SESSION['state']['stores']['orders']['nr']=$number_results;
	$_SESSION['state']['stores']['orders']['sf']=$start_from;
	$_SESSION['state']['stores']['orders']['where']=$where;
	$_SESSION['state']['stores']['orders']['f_field']=$f_field;
	$_SESSION['state']['stores']['orders']['f_value']=$f_value;

	// print_r($_SESSION['tables']['families_list']);

	//  print_r($_SESSION['tables']['families_list']);
	if (count($user->stores)==0)
		$where="where false";
	else {
		$where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
	}
	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Store Dimension`  S $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension` S  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';
	elseif ($order=='orders')
		$order='orders';
	elseif ($order=='cancelled')
		$order='cancelled';
	elseif ($order=='unknown')
		$order='unknown';
	elseif ($order=='paid')
		$order='paid';
	elseif ($order=='pending')
		$order='todo';
	else


		$order='`Store Code`';


	$total_orders=0;
	$total_unknown=0;
	$total_dispatched=0;
	$total_cancelled=0;
	$total_todo=0;
	$total_paid=0;
	$total_suspended=0;
	$sql="select  sum(`Store Total Acc Orders`) as orders,sum(`Store Unknown Orders`) as unknown,sum(`Store Suspended Orders`) as suspended,sum(`Store Dispatched Orders`) as dispatched,sum(`Store Cancelled Orders`) cancelled,sum(`Store Orders In Process`) as todo   from `Store Dimension` S left join `Store Data Dimension` D on (D.`Store Key`=S.`Store Key`) $where     ";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_orders=$row['orders'];
		$total_unknown=$row['unknown'];
		$total_dispatched=$row['dispatched'];
		$total_cancelled=$row['cancelled'];
		$total_todo=$row['todo'];
		$total_suspended=$row['suspended'];


	}





	$sql="select `Store Name`,`Store Code`,S.`Store Key`,`Store Total Acc Orders` as orders,`Store Suspended Orders` as suspended, `Store Unknown Orders` as unknown,`Store Dispatched Orders` as dispatched,`Store Cancelled Orders` cancelled,`Store Orders In Process` as todo from   `Store Dimension` S left join `Store Data Dimension` D on (D.`Store Key`=S.`Store Key`) $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);



	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="orders.php?store=%d&view=orders">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="orders.php?store=%d&view=orders">%s</a>',$row['Store Key'],$row['Store Code']);

		$todo=$row['todo'];
		if ($percentages) {
			$orders=percentage($row['orders'],$total_orders);
			$cancelled=percentage($row['cancelled'],$total_cancelled);
			$unknown=percentage($row['unknown'],$total_unknown);
			$todo=percentage($todo,$total_todo);
			$dispatched=percentage($row['dispatched'],$total_dispatched);
			$suspended=percentage($row['suspended'],$total_suspended);

		} else {
			$orders=number($row['orders']);
			$cancelled=number($row['cancelled']);
			$unknown=number($row['unknown']);
			$todo=number($todo);
			$dispatched=number($row['dispatched']);
			$suspended=number($row['suspended']);
		}
		if ($row['unknown']>0)
			$unknown=sprintf('(<a href="orders.php?store=%d&view=orders&dispatch=unknown">%s</a>) ',$row['Store Key'],$unknown);
		else
			$unknown='';
		$orders=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=all_orders">%s</a>',$row['Store Key'],$orders);
		$cancelled=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=cancelled">%s</a>',$row['Store Key'],$cancelled);
		$dispatched=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=dispatched">%s</a>',$row['Store Key'],$dispatched);
		$todo=$unknown.sprintf('<a href="orders.php?store=%d&view=orders&dispatch=in_process">%s</a>',$row['Store Key'],$todo);
		$suspended=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=suspended">%s</a>',$row['Store Key'],$suspended);



		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'orders'=>$orders,
			'unknown'=>$unknown,
			'cancelled'=>$cancelled,
			'dispatched'=>$dispatched,
			'pending'=>$todo,
			'suspended'=>$suspended

		);
	}
	mysql_free_result($res);

	if ($percentages) {
		$sum_orders='100.00%';
		$sum_cancelled='100.00%';
		$sum_paid='100.00%';
		$sum_unknown='';
		$sum_suspended='100.00%';
	} else {
		$sum_orders=number($total_orders);
		$sum_cancelled=number($total_cancelled);
		$sum_paid=number($total_paid);
		if ($total_unknown>0)
			$sum_unknown="(".number($total_unknown).") ";
		else
			$sum_unknown='';
		$sum_todo=number($total_todo);
		$sum_dispatched=number($total_dispatched);
		$sum_suspended=number($total_suspended);
	}


	$adata[]=array(
		'name'=>'',
		'code'=>_('Total'),
		'orders'=>$sum_orders,
		'unknown'=>$sum_unknown,
		'paid'=>$sum_paid,
		'cancelled'=>$sum_cancelled,
		'dispatched'=>$sum_dispatched,
		'pending'=>$sum_unknown.$sum_todo,
		'suspended'=>$sum_suspended
	);


	$total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_invoices_per_store() {

	global $user;

	$conf=$_SESSION['state']['stores']['invoices'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];

	} else
		$percentages=$_SESSION['state']['stores']['invoices']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	} else
		$period=$_SESSION['state']['stores']['invoices']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];

	} else
		$avg=$_SESSION['state']['stores']['invoices']['avg'];


	$_SESSION['state']['stores']['invoices']['percentages']=$percentages;
	$_SESSION['state']['stores']['invoices']['period']=$period;
	$_SESSION['state']['stores']['invoices']['avg']=$avg;
	$_SESSION['state']['stores']['invoices']['order']=$order;
	$_SESSION['state']['stores']['invoices']['order_dir']=$order_direction;
	$_SESSION['state']['stores']['invoices']['nr']=$number_results;
	$_SESSION['state']['stores']['invoices']['sf']=$start_from;
	$_SESSION['state']['stores']['invoices']['where']=$where;
	$_SESSION['state']['stores']['invoices']['f_field']=$f_field;
	$_SESSION['state']['stores']['invoices']['f_value']=$f_value;


	if (count($user->stores)==0)
		$where="where false";
	else {
		$where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
	}


	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Store Dimension` S $where $wheref";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension` S $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';
	elseif ($order=='invoices')
		$order='invoices';
	elseif ($order=='invoicess_paid')
		$order='invoices_paid';
	elseif ($order=='invoices_to_be_paid')
		$order='invoices_to_be_paid';
	elseif ($order=='refunds')
		$order='refunds';
	elseif ($order=='refundss_paid')
		$order='refunds_paid';
	elseif ($order=='refunds_to_be_paid')
		$order='refunds_to_be_paid';
	else
		$order='`Store Code`';


	$total_invoices=0;
	$total_invoices_paid=0;
	$total_invoices_to_be_paid=0;
	$total_refunds=0;
	$total_refunds_paid=0;
	$total_refunds_to_be_paid=0;

	$sql="select `Store Invoices` as invoices,`Store Refunds` as refunds,`Store Total Acc Invoices` as total_invoices,`Store Paid Invoices` as invoices_paid,`Store Invoices`-`Store Paid Invoices` as invoices_to_be_paid,`Store Paid Refunds` as refunds_paid,`Store Refunds`-`Store Paid Refunds` as refunds_to_be_paid from `Store Dimension` S left join `Store Data Dimension` D on (S.`Store Key`=D.`Store Key`) $where";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_invoices=$row['invoices'];
		$total_invoices_paid=$row['invoices_paid'];
		$total_invoices_to_be_paid=$row['invoices_to_be_paid'];
		$total_refunds=$row['refunds'];
		$total_refunds_paid=$row['refunds_paid'];
		$total_refunds_to_be_paid=$row['refunds_to_be_paid'];
	}

	$sql="select `Store Name`,`Store Code`,S.`Store Key`,`Store Invoices` as invoices,`Store Refunds` as refunds,`Store Total Acc Invoices` as total_invoices,`Store Paid Invoices` as invoices_paid,`Store Invoices`-`Store Paid Invoices` as invoices_to_be_paid,`Store Paid Refunds` as refunds_paid,`Store Refunds`-`Store Paid Refunds` as refunds_to_be_paid from `Store Dimension` S left join `Store Data Dimension` D on (S.`Store Key`=D.`Store Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);



	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="orders.php?store=%d&view=invoices">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="orders.php?store=%d&view=invoices">%s</a>',$row['Store Key'],$row['Store Code']);


		if ($percentages) {
			$invoices=percentage($row['invoices'],$total_invoices);
			$invoices_paid=percentage($row['invoices_paid'],$total_invoices_paid);
			$invoices_to_be_paid=percentage($row['invoices_to_be_paid'],$total_invoices_to_be_paid);
			$refunds=percentage($row['refunds'],$total_refunds);
			$refunds_paid=percentage($row['refunds_paid'],$total_refunds_paid);
			$refunds_to_be_paid=percentage($row['refunds_to_be_paid'],$total_refunds_to_be_paid);


		} else {
			$invoices=number($row['invoices']);
			$invoices_paid=number($row['invoices_paid']);
			$invoices_to_be_paid=number($row['invoices_to_be_paid']);
			$refunds=number($row['refunds']);
			$refunds_paid=number($row['refunds_paid']);
			$refunds_to_be_paid=number($row['refunds_to_be_paid']);

		}

		$invoices=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=invoices">%s</a>',$row['Store Key'],$invoices);
		$invoices_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=paid">%s</a>',$row['Store Key'],$invoices_paid);
		$invoices_to_be_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=to_paid">%s</a>',$row['Store Key'],$invoices_to_be_paid);
		$refunds=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds);
		$refunds_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds_paid);
		$refunds_to_be_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds_to_be_paid);

		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'invoices'=>$invoices,
			'invoices_paid'=>$invoices_paid,
			'invoices_to_be_paid'=>$invoices_to_be_paid,
			'refunds'=>$refunds,
			'refunds_paid'=>$refunds_paid,
			'refunds_to_be_paid'=>$refunds_to_be_paid,
		);
	}
	mysql_free_result($res);

	if ($percentages) {
		$total_invoices='100.00%';
		$total_invoices_paid='100.00%';
		$total_invoices_to_be_paid='100.00%';
		$total_refunds='100.00%';
		$total_refunds_paid='100.00%';
		$total_refunds_to_be_paid='100.00%';


	} else {
		$total_invoices=number($total_invoices);
		$total_invoices_paid=number($total_invoices_paid);
		$total_invoices_to_be_paid=number($total_invoices_to_be_paid);
		$total_refunds=number($total_refunds);
		$total_refunds_paid=number($total_refunds_paid);
		$total_refunds_to_be_paid=number($total_refunds_to_be_paid);

	}


	$adata[]=array(
		'name'=>'',
		'code'=>_('Total'),
		'invoices'=>$total_invoices,
		'invoices_paid'=>$total_invoices_paid,
		'invoices_to_be_paid'=>$total_invoices_to_be_paid,
		'refunds'=>$total_refunds,
		'refunds_paid'=>$total_refunds_paid,
		'refunds_to_be_paid'=>$total_refunds_to_be_paid,

	);


	$total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_delivery_notes_per_store() {
	global $user;
	$conf=$_SESSION['state']['stores']['delivery_notes'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$conf['view'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];

	} else
		$percentages=$_SESSION['state']['stores']['delivery_notes']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];

	} else
		$period=$_SESSION['state']['stores']['delivery_notes']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];

	} else
		$avg=$_SESSION['state']['stores']['delivery_notes']['avg'];



	$_SESSION['state']['stores']['delivery_notes']['percentages']=$percentages;
	$_SESSION['state']['stores']['delivery_notes']['period']=$period;
	$_SESSION['state']['stores']['delivery_notes']['avg']=$avg;
	$_SESSION['state']['stores']['delivery_notes']['order']=$order;
	$_SESSION['state']['stores']['delivery_notes']['order_dir']=$order_direction;
	$_SESSION['state']['stores']['delivery_notes']['nr']=$number_results;
	$_SESSION['state']['stores']['delivery_notes']['sf']=$start_from;
	$_SESSION['state']['stores']['delivery_notes']['where']=$where;
	$_SESSION['state']['stores']['delivery_notes']['view']=$view;

	$_SESSION['state']['stores']['delivery_notes']['f_field']=$f_field;
	$_SESSION['state']['stores']['delivery_notes']['f_value']=$f_value;



	if (count($user->stores)==0)
		$where="where false";
	else {
		$where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
	}


	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




	$sql="select count(*) as total from `Store Dimension` S $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension` S $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';


	$total_dn=0;
	$total_dn_ready_to_pick=0;
	$total_dn_packing=0;
	$total_dn_picking=0;
	$total_dn_ready=0;
	$total_dn_send=0;
	$total_dn_returned=0;
	$total_dn_orders=0;
	$total_dn_shortages=0;
	$total_dn_replacements=0;
	$total_dn_donations=0;
	$total_dn_samples=0;


	$sql="select `Store Delivery Notes For Shortages` as dn_shortages,`Store Delivery Notes For Replacements` as dn_replacements, `Store Delivery Notes For Donations` as dn_donations, `Store Delivery Notes For Samples` as dn_samples, `Store Delivery Notes For Orders` as dn_orders, `Store Total Acc Delivery Notes` as dn,`Store Ready to Pick Delivery Notes` as dn_ready_to_pick,`Store Picking Delivery Notes` as dn_picking,`Store Packing Delivery Notes` as dn_packing,`Store Ready to Dispatch Delivery Notes` as dn_ready,`Store Dispatched Delivery Notes` as dn_send, `Store Returned Delivery Notes`as dn_returned from `Store Dimension` S left join `Store Data Dimension` D on (S.`Store Key`=D.`Store Key`) $where";
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_dn=$row['dn'];
		$total_dn_ready_to_pick=$row['dn_ready_to_pick'];
		$total_dn_picking=$row['dn_picking'];
		$total_dn_packing=$row['dn_packing'];
		$total_dn_ready=$row['dn_ready'];
		$total_dn_send=$row['dn_send'];
		$total_dn_returned=$row['dn_returned'];
		$total_dn_orders=$row['dn_orders'];
		$total_dn_shortages=$row['dn_shortages'];
		$total_dn_replacements=$row['dn_replacements'];
		$total_dn_donations=$row['dn_donations'];
		$total_dn_samples=$row['dn_samples'];

	}





	$sql="select `Store Name`,`Store Code`,S.`Store Key`,`Store Delivery Notes For Shortages` as dn_shortages,`Store Delivery Notes For Replacements` as dn_replacements, `Store Delivery Notes For Donations` as dn_donations, `Store Delivery Notes For Samples` as dn_samples, `Store Delivery Notes For Orders` as dn_orders, `Store Total Acc Delivery Notes` as dn,`Store Ready to Pick Delivery Notes` as dn_ready_to_pick,`Store Picking Delivery Notes` as dn_picking,`Store Packing Delivery Notes` as dn_packing,`Store Ready to Dispatch Delivery Notes` as dn_ready,`Store Dispatched Delivery Notes` as dn_send,`Store Returned Delivery Notes`as dn_returned from `Store Dimension` S left join `Store Data Dimension` D on (S.`Store Key`=D.`Store Key`) $where $wheref   order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);



	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="orders.php?store=%d&view=dn">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="orders.php?store=%d&view=dn">%s</a>',$row['Store Key'],$row['Store Code']);


		if ($percentages) {
			$dn=percentage($row['dn'],$total_dn);
			$dn_ready_to_pick=percentage($row['dn_ready_to_pick'],$total_dn_ready_to_pick);
			$dn_picking=percentage($row['dn_picking'],$total_dn_picking);
			$dn_packing=percentage($row['dn_packing'],$total_dn_packing);
			$dn_ready=percentage($row['dn_ready'],$total_dn_ready);
			$dn_send=percentage($row['dn_send'],$total_dn_send);
			$dn_returned=percentage($row['dn_returned'],$total_dn_returned);
			$dn_orders=percentage($row['dn_orders'],$total_dn_orders);
			$dn_shortages=percentage($row['dn_shortages'],$total_dn_shortages);
			$dn_replacements=percentage($row['dn_replacements'],$total_dn_replacements);
			$dn_donations=percentage($row['dn_donations'],$total_dn_donations);
			$dn_samples=percentage($row['dn_samples'],$total_dn_samples);
		} else {
			$dn=number($row['dn']);
			$dn_ready_to_pick=number($row['dn_ready_to_pick']);
			$dn_picking=number($row['dn_picking']);
			$dn_packing=number($row['dn_packing']);
			$dn_ready=number($row['dn_ready']);
			$dn_send=number($row['dn_send']);
			$dn_returned=number($row['dn_returned']);
			$dn_orders=number($row['dn_orders']);
			$dn_shortages=number($row['dn_shortages']);
			$dn_replacements=number($row['dn_replacements']);
			$dn_donations=number($row['dn_donations']);
			$dn_samples=number($row['dn_samples']);


		}

		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'dn'=>$dn,
			'dn_ready_to_pick'=>$dn_ready_to_pick,
			'dn_picking'=>$dn_picking,
			'dn_packing'=>$dn_packing,
			'dn_ready'=>$dn_ready,
			'dn_send'=>$dn_send,
			'dn_returned'=>$dn_returned,
			'dn_orders'=>$dn_orders,
			'dn_shortages'=>$dn_shortages,
			'dn_replacements'=>$dn_replacements,
			'dn_donations'=>$dn_donations,
			'dn_samples'=>$dn_samples
		);
	}
	mysql_free_result($res);

	if ($percentages) {
		$total_dn='100.00%';
		$total_dn_ready_to_pick='100.00%';
		$total_dn_packing='100.00%';
		$total_dn_picking='100.00%';
		$total_dn_ready='100.00%';
		$total_dn_send='100.00%';
		$total_dn_returned='100.00%';
		$total_dn_orders='100.00%';
		$total_dn_shortages='100.00%';
		$total_dn_replacements='100.00%';
		$total_dn_donations='100.00%';
		$total_dn_samples='100.00%';
	} else {
		$total_dn=number($total_dn);
		$total_dn_ready_to_pick=number($total_dn_ready_to_pick);
		$total_dn_packing=number($total_dn_packing);
		$total_dn_picking=number($total_dn_picking);
		$total_dn_ready=number($total_dn_ready);
		$total_dn_send=number($total_dn_send);
		$total_dn_returned=number($total_dn_returned);
		$total_dn_orders=number($total_dn_orders);
		$total_dn_shortages=number($total_dn_shortages);
		$total_dn_replacements=number($total_dn_replacements);
		$total_dn_donations=number($total_dn_donations);
		$total_dn_samples=number($total_dn_samples);


	}


	$adata[]=array(
		'name'=>'',
		'code'=>_('Total'),
		'dn'=>$total_dn,
		'dn_ready_to_pick'=>$total_dn_ready_to_pick,
		'dn_picking'=>$total_dn_picking,
		'dn_packing'=>$total_dn_packing,
		'dn_ready'=>$total_dn_ready,
		'dn_send'=>$total_dn_send,
		'dn_returned'=>$total_dn_returned,
		'dn_orders'=>$total_dn_orders,
		'dn_shortages'=>$total_dn_shortages,
		'dn_replacements'=>$total_dn_replacements,
		'dn_donations'=>$total_dn_donations,
		'dn_samples'=>$total_dn_samples

	);


	$total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function product_code_timeline() {






	$conf=$_SESSION['state']['product']['code_timeline'];
	//print_r($conf);
	$tableid=0;
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];


	if (isset( $_REQUEST['code']))
		$code=$_REQUEST['code'];
	else
		$code=$conf['code'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];



	$where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));

	$wheref='';

	$order_direction=$order_dir;
	$_order=$order;
	$_dir=$order_direction;
	if ($order=='pid')
		$order='`Product ID`';
	if ($order=='from')
		$order='`Product History Valid From`';
	if ($order=='to')
		$order='`Product History Valid To`';
	else
		$order='`Product History Valid From`';


	$sql="select * from `Product History Dimension` PH left join `Product Dimension`  P on (P.`Product ID`=PH.`Product ID`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);
	$number_results=mysql_num_rows($res);

	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$id=sprintf("<a href='product.php?pid=%d'>%05d</a> <a href='product.php?key=%d'>(%05d)</a>"
			,$row['Product ID'],$row['Product ID']
			,$row['Product Key'] ,$row['Product Key']

		);
		$adata[]=array(
			'pid'=>$id
			,'description'=>$row['Product History XHTML Short Description']

			,'parts'=>$row['Product XHTML Parts']
			,'from'=>strftime("%e %b %Y", strtotime($row['Product History Valid From'].' +0:00'))
			,'to'=>strftime("%e %b %Y", strtotime($row['Product History Valid To'].' +0:00'))
			,'sales'=>money($row['Product History Total Acc Invoiced Amount'],$row['Product Currency'])
		);

	}
	mysql_free_result($res);
	$rtext=number($number_results).' '._('products with the same code');
	$rtext_rpp='';
	$filter_msg='';
	$total_records=$number_results;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);

	echo json_encode($response);
}



function is_store_company_number($data) {
	if (!isset($data['query'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}



	$sql=sprintf("select `Store Key`,`Store Name`,`Store Company Number` from `Store Dimension` where  `Store Company Number`=%s  "
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Store <a href="store.php?id=%d">%s</a> already has this Company Number (%s)'
			,$data['Store Key']
			,$data['Store Name']
			,$data['Store Company Number']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}
}

function is_store_vat($data) {
	if (!isset($data['query'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}



	$sql=sprintf("select `Store Key`,`Store Name`,`Store VAT Number` from `Store Dimension` where  `Store VAT Number`=%s  "
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Store <a href="store.php?id=%d">%s</a> already has this VAT (%s)'
			,$data['Store Key']
			,$data['Store Name']
			,$data['Store VAT Number']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}
}

function is_store_code($data) {

	if (!isset($data['query'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}



	$sql=sprintf("select `Store Key`,`Store Name`,`Store Code` from `Store Dimension` where  `Store Code`=%s  "
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Store <a href="store.php?id=%d">%s</a> already has this code (%s)'
			,$data['Store Key']
			,$data['Store Name']
			,$data['Store Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_store_name($data) {
	if (!isset($data['query'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}



	$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where  `Store Name`=%s  "
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another store (<a href="store.php?id=%d">%s</a>) already has this name'
			,$data['Store Key']
			,$data['Store Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_department_name($data) {
	if (!isset($data['query']) or !isset($data['store_key'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product Department Key`,`Product Department Code` from `Product Department Dimension` where  `Product Department Store Key`=%d and  `Product Department Name`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another department (<a href="department.php?id=%d">%s</a>) already has this name'
			,$data['Product Department Key']
			,$data['Product Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_department_code($data) {
	if (!isset($data['query']) or !isset($data['store_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product Department Key`,`Product Department Name`,`Product Department Code` from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Department <a href="department.php?id=%d">%s</a> already has this code (%s)'
			,$data['Product Department Key']
			,$data['Product Department Name']
			,$data['Product Department Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_family_name($data) {
	if (!isset($data['query']) or !isset($data['store_key'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where  `Product Family Store Key`=%d and  `Product Family Name`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another family (<a href="family.php?id=%d">%s</a>) already has this name'
			,$data['Product Family Key']
			,$data['Product Family Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_family_code($data) {


	if (!isset($data['query']) or !isset($data['store_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product Family Key`,`Product Family Name`,`Product Family Code` from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Family <a href="family.php?id=%d">%s</a> already has this code (%s)'
			,$data['Product Family Key']
			,$data['Product Family Name']
			,$data['Product Family Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_family_special_char($data) {
	if (!isset($data['query']) or !isset($data['store_key'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where  `Product Family Store Key`=%d and  `Product Family Special Characteristic`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another family (<a href="family.php?id=%d">%s</a>) has the same special characteristic'
			,$data['Product Family Key']
			,$data['Product Family Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}





function new_products_list($data) {
	$list_name=$data['list_name'];
	$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d ",
		prepare_mysql($list_name),
		$store_id
	);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];
	$table='`Product Dimension` P ';


	list($where,$table)=product_awhere($awhere);

	$where.=sprintf(' and `Product Store Key`=%d ',$store_id);


	$sql="select count(Distinct P.`Product ID`) as total from $table  $where";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No products match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}


	}
	mysql_free_result($res);

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Product',%d,%s,%s,%s,NOW())",
		$store_id,
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere']))

	);
	mysql_query($list_sql);
	$customer_list_key=mysql_insert_id();

	if ($list_type=='Static') {


		$sql="select P.`Product ID` from $table  $where group by P.`Product ID`";
		//   print $sql;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$customer_key=$data['Product ID'];
			$sql=sprintf("insert into `List Product Bridge` (`List Key`,`Product ID`) values (%d,%d)",
				$customer_list_key,
				$customer_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$customer_list_key

	);
	echo json_encode($response);

}



function list_products_lists() {

	global $user;

	$conf=$_SESSION['state']['products']['list'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];



	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))



		$awhere=$_REQUEST['where'];
	else
		$awhere=$conf['where'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['products']['store']=$store;
	} else
		$store=$_SESSION['state']['products']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['products']['list']['order']=$order;
	$_SESSION['state']['products']['list']['order_dir']=$order_direction;
	$_SESSION['state']['products']['list']['nr']=$number_results;
	$_SESSION['state']['products']['list']['sf']=$start_from;
	$_SESSION['state']['products']['list']['where']=$awhere;
	$_SESSION['state']['products']['list']['f_field']=$f_field;
	$_SESSION['state']['products']['list']['f_value']=$f_value;



	$where=' where `List Scope`="Product"';
	if (in_array($store,$user->stores)) {
		$where.=sprintf(' and   `List Parent Key`=%d  ',$store);

	}

	$wheref='';

	$sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('List','Lists',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all Lists");




	$filter_msg='';





	$_order=$order;
	$_dir=$order_direction;


	if ($order=='name')
		$order='`List Name`';
	elseif ($order=='creation_date')
		$order='`List Creation Date`';
	elseif ($order=='product_list_type')
		$order='`List Type`';

	else
		$order='`List Key`';


	$sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Parent Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";

	$adata=array();



	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





		$cusomer_list_name=" <a href='products_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
		switch ($data['List Type']) {
		case 'Static':
			$product_list_type=_('Static');
			break;
		default:
			$product_list_type=_('Dynamic');
			break;

		}

		$adata[]=array(


			'product_list_type'=>$product_list_type,
			'name'=>$cusomer_list_name,
			'key'=>$data['List key'],
			'creation_date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['List Creation Date']." +00:00")),
			'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
			'delete'=>'<img src="art/icons/cross.png"/>'


		);

	}


	mysql_free_result($result);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}








function is_product_name($data) {
	if (!isset($data['query']) or !isset($data['store_key'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where  `Product Store Key`=%d and  `Product Name`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another product (<a href="product.php?pid=%d">%s</a>) already has this name'
			,$data['Product ID']
			,$data['Product Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_product_code($data) {


	if (!isset($data['query']) or !isset($data['store_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$store_key=$data['store_key'];

	$sql=sprintf("select `Product ID`,`Product Name`,`Product Code` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  "
		,$store_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Product <a href="product.php?pid=%d">%s</a> already has this code (%s)'
			,$data['Product ID']
			,$data['Product Name']
			,$data['Product Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_product_special_char($data) {
	if (!isset($data['query']) or !isset($data['family_key'])) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$family_key=$data['family_key'];

	$sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where  `Product Family Key`=%d and  `Product Special Characteristic`=%s  "
		,$family_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another product (<a href="product.php?pid=%d">%s</a>) has the same special characteristic'
			,$data['Product ID']
			,$data['Product Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}


function list_customers_who_order_product() {
	$conf=$_SESSION['state']['product']['customers'];

	if (isset( $_REQUEST['code'])) {
		$tag=$_REQUEST['code'];
		$mode='code';
	} elseif (isset( $_REQUEST['id'])) {
		$tag=$_REQUEST['id'];
		$mode='id';
	} elseif (isset( $_REQUEST['key'])) {
		$tag=$_REQUEST['key'];
		$mode='key';
	} else {
		$tag=$_SESSION['state']['product']['tag'];
		$mode=$_SESSION['state']['product']['mode'];
	}

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['product']['customers']['order']=$order;
	$_SESSION['state']['product']['customers']['order_dir']=$order_direction;
	$_SESSION['state']['product']['customers']['nr']=$number_results;
	$_SESSION['state']['product']['customers']['sf']=$start_from;
	$_SESSION['state']['product']['customers']['f_field']=$f_field;
	$_SESSION['state']['product']['customers']['f_value']=$f_value;
	$_SESSION['state']['product']['customers']['tag']=$tag;
	$_SESSION['state']['product']['customers']['mode']=$mode;

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$table=' `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)          ';

	if ($mode=='code') {
		$where=sprintf(" where OTF.`Product Code`=%s ",prepare_mysql($tag));

	}
	elseif ($mode=='pid')
		$where=printf(" where OTF.`Product ID`=%d ",$tag);
	elseif ($mode=='key')
		$where=sprintf(" where  OTF.`Product Key`=%d ",$tag);


	$wheref="";

	//    if ($f_field=='max' and is_numeric($f_value) )
	//       $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
	//  elseif ($f_field=='min' and is_numeric($f_value) )
	//     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Customer Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {

			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
			}

		}
	}

	$sql="select count(distinct OTF.`Customer Key`) as total from  $table  $where $wheref";
	//   print $mode.' '.$sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct OTF.`Customer Key`) as total from  $table  $where      ";

		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}


	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>10)
		$rtext_rpp=' ('._("Showing all").')';
	else {
		$rtext_rpp='';
	}




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
			break;

		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
			break;

		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
			break;
		case('postcode'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
			break;
		case('last_more'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('last_less'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		}
	}
	else
		$filter_msg='';


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='dispatched')
		$order='dispatched';
	elseif ($order=='orders')
		$order='orders';
	elseif ($order=='charged')
		$order='charged';
	elseif ($order=='to_dispatch')
		$order='to_dispatch';
	elseif ($order=='dispatched')
		$order='dispatched';
	elseif ($order=='nodispatched')
		$order='nodispatched';
	else
		$order='`Customer Name`';


	$sql="select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`) as charged ,count(distinct `Order Key`) as orders ,sum(`Shipped Quantity`) as dispatched,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`) as nodispatched from    $table   $where $wheref  group by CD.`Customer Key`    order by $order $order_direction  limit $start_from,$number_results ";


	$data=array();
	//print $sql;
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$data[]=array(
			'customer'=>sprintf('<a href="customer.php?id=%d"><b>%s</b></a>, %s',$row['customer_id'],$row['Customer Name'],$row['Customer Main Location']),
			'charged'=>money($row['charged']),
			'orders'=>number($row['orders']),
			'dispatched'=>number($row['dispatched']),
			'nodispatched'=>number($row['nodispatched'])

		);
	}
	mysql_free_result($res);

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}




function list_delivery_notes_per_part($data) {

	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	$conf=$_SESSION['state']['part']['delivery_notes'];

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view='';//$conf['view'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	list($date_interval,$error)=prepare_mysql_dates($from,$to);
	if ($error) {
		list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {
		$_SESSION['state']['part']['delivery_notes']['from']=$from;
		$_SESSION['state']['part']['delivery_notes']['to']=$to;
	}


	$_SESSION['state']['part']['delivery_notes']=
		array(
		'view'=>$view,
		'order'=>$order,
		'order_dir'=>$order_direction,
		'nr'=>$number_results,
		'sf'=>$start_from,
		'where'=>$where,
		'f_field'=>$f_field,
		'f_value'=>$f_value,
		'from'=>$from,
		'to'=>$to,
		'elements'=>$elements,
		'f_show'=>$_SESSION['state']['part']['delivery_notes']['f_show']
	);


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$wheref='';

	if ($f_field=='note' and $f_value!='') {
		// $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
		$wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";
	}


	$where=$where.sprintf(" and `Part SKU`=%d ",$parent_key);







	$sql="select count(*) as total from `Inventory Transaction Fact`     $where $wheref group by `Delivery Note Key`";
	//print $sql;exit;

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Transaction Fact`   $where group by `Delivery Note Key` ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}



	$rtext=$total.' '.ngettext('delivery note','delivery notes',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total_records==0) {
		$rtext=_('No delivery notes');
		$rtext_rpp='';
	}




	$rtext=number($total_records)." ".ngettext('delivery note','delivery notes',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any delivery note like")." <b>".$f_value."*</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('delivery notes with')." <b>".$f_value."*</b>";
			break;

		}
	}
	else
		$filter_msg='';



	$order=' `Date` desc , `Inventory Transaction Key` desc ';
	$order_direction=' ';


	$sql="select * from `Inventory Transaction Fact` ITF left join `Delivery Note Dimension` DN on (ITF.`Delivery Note Key`=DN.`Delivery Note Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";




	//print $sql;exit;
	$result=mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
		$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


		$type=$row['Delivery Note Type'];

		switch ($row['Delivery Note Parcel Type']) {
		case('Pallet'):
			$parcel_type='P';
			break;
		case('Envelope'):
			$parcel_type='e';
			break;
		default:
			$parcel_type='b';

		}

		if ($row['Delivery Note Number Parcels']=='') {
			$parcels='?';
		}
		elseif ($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']) {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
		}
		else {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
		}
		if ($row['Delivery Note State']=='Dispatched')
			$date=strftime("%e %b %y", strtotime($row['Delivery Note Date'].' +0:00'));
		else
			$date=strftime("%e %b %y", strtotime($row['Delivery Note Date Created'].' +0:00'));
		$adata[]=array(
			'id'=>$order_id
			,'customer'=>$customer
			,'date'=>$date
			,'type'=>$type.($row['Delivery Note XHTML Orders']?' ('.$row['Delivery Note XHTML Orders'].')':'')
			,'orders'=>$row['Delivery Note XHTML Orders']
			,'invoices'=>$row['Delivery Note XHTML Invoices']
			,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
			,'parcels'=>$parcels


		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function family_sales_data($data) {



	$customers=0;
	$invoices=0;
	$profit=money(0);
	$outers=0;
	$sales=money(0);

	$from_date=$data['from'].' 00:00:00';
	$to_date=$data['to'].' 23.59:59';


	$sql=sprintf("select count(distinct `Customer Key`) as customers, count(distinct `Invoice Key`) as invoices,sum(`Shipped Quantity`) as qty_delivered,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`) as total_cost from `Order Transaction Fact` where `Product Family Key`=%d %s %s",
		$data['family_key'],
		($from_date?sprintf('and `Invoice Date`>%s',prepare_mysql($from_date)):''),
		($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')
	);
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$customers=number($row['customers']);
		$invoices=number($row['invoices']);
		$profit=money($row['net']-$row['total_cost']);
		$outers=number($row['qty_delivered']);
		$sales=money($row['net']);
	}

	$response=
		array('state'=>200,
		'customers'=>$customers,
		'invoices'=>$invoices,
		'profits'=>$profit,
		'customers'=>$customers,
		'outers'=>$outers,
		'sales'=>$sales,
		'formated_period'=>($from_date?strftime("%a %e %b %Y %H:%M %Z", strtotime($from_date.' +0:00')):"").'-'.($to_date?strftime("%a %e %b %Y %H:%M %Z", strtotime($to_date.' +0:00')):"")

	);
	echo json_encode($response);

}







function list_department_sales_report() {

	global $user;
	$display_total=false;

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['department_sales'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['department_sales'];
		$conf_table='department';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['stores']['department_sales'];
		$conf_table='stores';
	}
	else {
		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];

	}else {
		$from=$_SESSION['state'][$parent]['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];

	}else {
		$from=$_SESSION['state'][$parent]['to'];

	}



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$_SESSION['state'][$conf_table]['family_sales']['order']=$order;
	$_SESSION['state'][$conf_table]['family_sales']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['family_sales']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['family_sales']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['family_sales']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['family_sales']['f_value']=$f_value;

	$table='`Product Family Dimension`';
	$where_type='';
	$where_interval='';
	$where='where true';



	switch ($parent) {
	case('store'):

		$where.=sprintf(' and OTF.`Store Key`=%d',$parent_key);
		break;

	default:
		if (count($user->stores)==0)
			$where="where false";
		else {

			$where=sprintf("where OTF.`Store Key` in (%s) ",join(',',$user->stores));
		}

	}


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];
	$where.=$where_interval;
	$where.=$where_type;

	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Product Department Name` like '".addslashes($f_value)."%'";





	$total_records=0;
	$total=0;
	$filtered=0;
	$sql="select count(distinct OTF.`Product Department Key`)  as total  from `Order Transaction Fact` OTF  $where    ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_records=$row['total'];
		$total=$total_records;
	}
	if ($wheref!='') {
		$sql="select count(distinct OTF.`Product Department Key`) as total from `Order Transaction Fact`  OTF  left join  `Product Department Dimension` P  o (OTF.`Product Department Key`=P.`Product Department Key`)  $where $wheref ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total=$total_records-$row['total'];
			$filtered=$row['total'];
		}
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('department','departments',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('departments with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('departments with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='store')
		$order='`Store Code`';

	elseif ($order=='name')
		$order='`Product Department Name`';
	elseif ($order=='sales')
		$order='net';
	elseif ($order=='sold')
		$order='qty_delivered';
	elseif ($order=='profit')
		$order='profit';

	else
		$order='`Product Department Code`';



	$sql="select OTF.`Store Key`,`Store Code`,OTF.`Product Department Key`,`Product Department Code`,`Invoice Currency Code`,P.`Product Department Name`,count(distinct `Customer Key`) as customers, count(distinct `Invoice Key`) as invoices,sum(`Shipped Quantity`) as qty_delivered,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit from  `Product Department Dimension` P  left join  `Order Transaction Fact`  OTF  on (OTF.`Product Department Key`=P.`Product Department Key`) left join `Store Dimension` S on (OTF.`Store Key`=S.`Store Key`) $where $wheref group by OTF.`Product Department Key` order by $order $order_direction limit $start_from,$number_results    ";


	$adata=array();

	$res = mysql_query($sql);


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$code=sprintf('<a href="department.php?id=%s">%s</a>',$row['Product Department Key'],$row['Product Department Code']);
		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

		$currency=$row['Invoice Currency Code'];
		$sold=$row['qty_delivered'];
		$tsall=$row['net'];
		$tprofit=$row['profit'];


		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'name'=>$row['Product Department Name'],
			'sales'=>(is_numeric($tsall)?money($tsall,$currency):$tsall),
			'sold'=>(is_numeric($sold)?number($sold):$sold),
		);



	}
	mysql_free_result($res);







	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);
}
function list_family_sales_report() {

	global $user;
	$display_total=false;

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['family_sales'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['family_sales'];
		$conf_table='department';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['stores']['family_sales'];
		$conf_table='stores';
	}
	else {

		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];

	}else {
		$from=$_SESSION['state'][$parent]['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];

	}else {
		$from=$_SESSION['state'][$parent]['to'];

	}



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	/*
	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
	} else
		$percentages=$conf['percentages'];




	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
	} else
		$avg=$conf['avg'];
*/

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		$parent='store';//$conf['parent'];
	}





	$_SESSION['state'][$conf_table]['family_sales']['order']=$order;
	$_SESSION['state'][$conf_table]['family_sales']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['family_sales']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['family_sales']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['family_sales']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['family_sales']['f_value']=$f_value;

	$table='`Product Family Dimension`';
	$where_type='';
	$where_interval='';
	$where='where true';



	switch ($parent) {
	case('store'):

		$where.=sprintf(' and OTF.`Store Key`=%d',$parent_key);
		break;
	case('department'):

		$where.=sprintf(' and OTF.`Product Department Key`=%d',$parent_key);
		break;
	default:
		if (count($user->stores)==0)
			$where="where false";
		else {

			$where=sprintf("where `Product Family Store Key` in (%s) ",join(',',$user->stores));
		}

	}


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];
	$where.=$where_interval;
	$where.=$where_type;

	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Product Family Name` like '".addslashes($f_value)."%'";



	$total_records=0;
	$total=0;
	$filtered=0;
	$sql="select count(distinct OTF.`Product Family Key`)  as total  from `Order Transaction Fact` OTF  $where    ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_records=$row['total'];
		$total=$total_records;
	}
	if ($wheref!='') {
		$sql="select count(distinct OTF.`Product Family Key`)  as total from `Order Transaction Fact`  OTF  left join  `Product Family Dimension` P on (OTF.`Product Family Key`=P.`Product Family Key`)  $where $wheref       ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total=$total_records-$row['total'];
			$filtered=$row['total'];
		}
	}
	mysql_free_result($res);



	$rtext=number($total_records)." ".ngettext('family','families',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='store')
		$order='`Store Code`';
	elseif ($order=='stock_value')
		$order='`Product Family Stock Value`';
	elseif ($order=='name')
		$order='`Product Family Name`';
	elseif ($order=='sales')
		$order='net';
	elseif ($order=='sold')
		$order='qty_delivered';
	elseif ($order=='profit')
		$order='profit';
	elseif ($order=='main_type')
		$order='`Product Family Record Type`';

	else
		$order='`Product Family Code`';



	$sql="select `Product Family Store Key`,`Store Code`,OTF.`Product Family Key`,`Product Family Code`,`Product Family Record Type`,`Invoice Currency Code`,P.`Product Family Name`,OTF.`Product ID`,OTF.`Product Code`,count(distinct `Customer Key`) as customers, count(distinct `Invoice Key`) as invoices,sum(`Shipped Quantity`) as qty_delivered,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit from     `Order Transaction Fact`  OTF    left join `Product Family Dimension` P on (OTF.`Product Family Key`=P.`Product Family Key`) left join `Store Dimension` S on (P.`Product Family Store Key`=S.`Store Key`) $where $wheref group by OTF.`Product Family Key` order by $order $order_direction limit $start_from,$number_results    ";

	$adata=array();
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$code=sprintf('<a href="family.php?id=%s">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Family Store Key'],$row['Store Code']);

		switch ($row['Product Family Record Type']) {
		case('Historic'):
			$main_type=_('Historic');
			break;
		case('Private'):
			$main_type=_('Private');
			break;
		case('NoSale'):
			$main_type=_('Not for Sale');
		case('Discontinued'):
			$main_type=_('Discontinued');
			break;
		case('Sale'):
			$main_type=_('For Sale');
			break;
		default:
			$main_type=$row['Product Family Record Type'];

		}

		$currency=$row['Invoice Currency Code'];

		$sold=$row['qty_delivered'];
		$tsall=$row['net'];
		$tprofit=$row['profit'];

		include_once 'locale.php';

		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'name'=>$row['Product Family Name'],
			'sales'=>(is_numeric($tsall)?money($tsall,$currency):$tsall),
			//'profit'=>(is_numeric($tprofit)?money($tprofit,$currency):$tprofit),
			'sold'=>(is_numeric($sold)?number($sold,0):$sold),
			'state'=>$main_type
		);



	}
	mysql_free_result($res);







	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);
}
function list_product_sales_report() {

	global $user;
	$display_total=false;



	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['product_sales'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['product_sales'];
		$conf_table='department';
	}
	elseif ($parent=='family') {
		$conf=$_SESSION['state']['family']['product_sales'];
		$conf_table='family';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['stores']['product_sales'];
		$conf_table='stores';
	}
	else {

		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];

	}else {
		$from=$_SESSION['state'][$parent]['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];

	}else {
		$from=$_SESSION['state'][$parent]['to'];

	}



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		$parent='store';//$conf['parent'];
	}





	$_SESSION['state'][$conf_table]['product_sales']['order']=$order;
	$_SESSION['state'][$conf_table]['product_sales']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['product_sales']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['product_sales']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['product_sales']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['product_sales']['f_value']=$f_value;

	$table='`Product Dimension`';
	$where_type='';
	$where_interval='';
	$where='where true';



	switch ($parent) {
	case('store'):
		$where.=sprintf(' and OTF.`Store Key`=%d',$parent_key);
		break;
	case('department'):
		$where.=sprintf('  and OTF.`Product Department Key`=%d',$parent_key);
		break;
	case('family'):
		$where.=sprintf(' and OTF.`Product Family Key`=%d',$parent_key);
		break;
	default:
	}


	$group='';
	$where_type='';
	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];
	$where.=$where_interval;
	$where.=$where_type;

	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  OTF.`Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  P.`Product Name` like '".addslashes($f_value)."%'";


	$total_records=0;
	$total=0;
	$filtered=0;
	$sql="select count(distinct OTF.`Product ID`)  as total  from `Order Transaction Fact` OTF  $where    ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total_records=$row['total'];
		$total=$total_records;
	}
	if ($wheref!='') {
		$sql="select count(distinct OTF.`Product ID`) as total from `Order Transaction Fact`  OTF  left join  `Product Dimension` P   on (OTF.`Product ID`=P.`Product ID`)  $where $wheref       ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total=$total_records-$row['total'];
			$filtered=$row['total'];
		}
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;


	if ($order=='code')
		$order='`Product Code File As`';
	elseif ($order=='name')
		$order='`Product Name`';
	elseif ($order=='state') {
		$order='`Product Sales Type`';
	}
	elseif ($order=='family') {
		$order='`Product Family`Code';
	}
	elseif ($order=='dept') {
		$order='`Product Main Department Code`';
	}
	elseif ($order=='store') {
		$order='`Store Code`';
	}elseif ($order=='profit') {
		$order='profit';

	}
	elseif ($order=='sales') {
		$order='net';

	}elseif ($_order=='delta_sales') {
		$order='delta_sales';

	}
	elseif ($order=='margin') {
		$order='margin';

	}
	elseif ($order=='sold') {
		$order='qty_delivered';

	}else {
		$tipo_order='asset';
		$order='';
		$$order_direction='';
	}
	if ($order!='')$order=" order by $order $order_direction";






	$sql="select `Product Store Key`,`Store Code`,`Product XHTML Short Description`,`Product Record Type`,`Product Main Type`,`Invoice Currency Code`,P.`Product Name`,OTF.`Product ID`,OTF.`Product Code`,count(distinct `Customer Key`) as customers, count(distinct `Invoice Key`) as invoices,sum(`Shipped Quantity`) as qty_delivered,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit from  `Order Transaction Fact` OTF left join     `Product Dimension` P    on (OTF.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (OTF.`Store Key`=S.`Store Key`) $where $wheref group by OTF.`Product ID` $order limit $start_from,$number_results    ";


	$adata=array();

	$res = mysql_query($sql);


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




		$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Store Key'],$row['Store Code']);

		//'Historic','Discontinued','Private','NoSale','Sale'
		switch ($row['Product Main Type']) {
		case('Historic'):
			$main_type=_('Historic');
			break;
		case('Private'):
			$main_type=_('Private');
			break;
		case('NoSale'):
			$main_type=_('Not for Sale');
		case('Discontinued'):
			$main_type=_('Discontinued');
			break;
		case('Sale'):
			$main_type=_('For Sale');
			break;
		default:
			$main_type=$row['Product Main Type'];

		}

		$currency=$row['Invoice Currency Code'];

		$sold=$row['qty_delivered'];
		$tsall=$row['net'];
		$tprofit=$row['profit'];




		include_once 'locale.php';



		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'name'=>$row['Product XHTML Short Description'],
			'sales'=>(is_numeric($tsall)?money($tsall,$currency):$tsall),
			//'profit'=>(is_numeric($tprofit)?money($tprofit,$currency):$tprofit),
			'sold'=>(is_numeric($sold)?number($sold):$sold),
			'state'=>$main_type
		);



	}
	mysql_free_result($res);







	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function get_interval_products_elements_numbers($data) {

	$parent=$data['parent'];
	$parent_key=$data['parent_key'];
	$from=$data['from'];
	$to=$data['to'];


	switch ($parent) {
	case 'store':
		$db_field='Store Key';
		$db_field2='Store Key';

		break;
	case 'department':
		$db_field='Product Main Department Key';
		$db_field2='Product Department Key';

		break;
	case 'family':
		$db_field='Product Family Key';
		$db_field2='Product Family Key';

		break;
	}

	$elements_number=array('Historic'=>0,'Discontinued'=>0,'NoSale'=>0,'Sale'=>0,'Private'=>0);

	if ($from=='' and $to=='') {
		$sql=sprintf("select count(distinct `Product ID`)  as num  ,`Product Main Type`   from  `Product Dimension` P    where `%s`=%d   group by `Product Main Type`   ",
			$db_field,$parent_key);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_number[$row['Product Main Type']]=$row['num'];
		}

	}else {

		if ($from)$from=$from.' 00:00:00';
		if ($to)$to=$to.' 23:59:59';
		$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
		$where_interval=$where_interval['mysql'];

		$sql=sprintf("select count(distinct OTF.`Product ID`)  as num  ,`Product Main Type`   from  `Product Dimension` P  left join `Order Transaction Fact`  OTF  on (OTF.`Product ID`=P.`Product ID`)  where OTF.`%s`=%d  $where_interval   group by `Product Main Type`   ",
			$db_field2,
			$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_number[$row['Product Main Type']]=$row['num'];
		}

	}


	echo json_encode(array('elements_numbers'=>$elements_number));

}

function get_interval_families_elements_numbers($data) {

	$parent=$data['parent'];
	$parent_key=$data['parent_key'];
	$from=$data['from'];
	$to=$data['to'];


	switch ($parent) {
	case 'store':
		$db_field='Store Key';
		$db_field2='Store Key';

		break;
	case 'department':
		$db_field='Product Family Main Department Key';
		$db_field2='Product Department Key';

		break;
	default:
		exit();
	}

	$elements_number=array('InProcess'=>0,'Discontinued'=>0,'Normal'=>0,'Discontinuing'=>0,'NoSale'=>0);

	if ($from=='' and $to=='') {
		$sql=sprintf("select count(*)  as num  ,`Product Family Record Type`   from  `Product Family Dimension` P    where `%s`=%d   group by `Product Family Record Type`   ",
			$db_field,$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ($row['Product Family Record Type']!='')
				$elements_number[$row['Product Family Record Type']]=$row['num'];
		}

	}else {

		if ($from)$from=$from.' 00:00:00';
		if ($to)$to=$to.' 23:59:59';
		$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
		$where_interval=$where_interval['mysql'];

		$sql=sprintf("select count(distinct OTF.`Product Family Key`)  as num  ,`Product Family Record Type`    from  `Product Family Dimension` P  left join `Order Transaction Fact`  OTF  on (OTF.`Product Family Key`=P.`Product Family Key`)  where OTF.`%s`=%d  $where_interval   group by `Product Family Record Type`    ",

			$db_field2,
			$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ($row['Product Family Record Type']!='')
				$elements_number[$row['Product Family Record Type']]=$row['num'];
		}

	}


	echo json_encode(array('elements_numbers'=>$elements_number));

}


function get_asset_sales_data($data) {

	$parent=$data['parent'];

	$parent_key=$data['parent_key'];
	$from_date=$data['from'];
	$to_date=$data['to'];

	if ($from_date)$from_date=$from_date.' 00:00:00';
	if ($to_date)$to_date=$to_date.' 23:59:59';
	$where_interval=prepare_mysql_dates($from_date,$to_date,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];




	$sales=0;
	$outers=0;
	$profits=0;
	$customers=0;
	$invoices=0;


	switch ($parent) {
	case('store'):

		$sql=sprintf("select `Store Currency Code` from `Store Dimension`where  `Store Key`=%d  ",$parent_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$currency=$row['Store Currency Code'];

		}

		$sql=sprintf("select 0 as outers, count(Distinct `Invoice Customer Key`) as customers,count(*) as invoices,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit from `Invoice Dimension`  where `Invoice Store Key`=%d  $where_interval   ",
			$parent_key);
		break;
	case('department'):

		$sql=sprintf("select `Product Department Currency Code` from `Product Department Dimension`where  `Product Department Key`=%d  ",
			$parent_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$currency=$row['Product Department Currency Code'];
		}

		$sql=sprintf("select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit,sum(`Shipped Quantity`) outers,count(DISTINCT `Customer Key`) as customers,count(DISTINCT `Invoice Key`) as invoices from `Order Transaction Fact`  OTF    where OTF.`Product Department Key`=%d and `Current Dispatching State`='Dispatched' $where_interval   ",$parent_key);
		break;
	case('family'):

		$sql=sprintf("select `Product Family Currency Code` from `Product Family Dimension`where  `Product Family Key`=%d  ",$parent_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$currency=$row['Product Family Currency Code'];
		}


		$sql=sprintf("select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit,sum(`Shipped Quantity`) outers,count(DISTINCT `Customer Key`) as customers,count(DISTINCT `Invoice Key`) as invoices from `Order Transaction Fact`  OTF    where OTF.`Product Family Key`=%d and `Current Dispatching State`='Dispatched' $where_interval   ",$parent_key);



		break;



	case('product'):
		$sql=sprintf("select `Product Currency` from `Product Dimension` where  `Product ID`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$currency=$row['Product Currency'];
		}

		$sql=sprintf("select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit,sum(`Shipped Quantity`) outers,count(DISTINCT `Customer Key`) as customers,count(DISTINCT `Invoice Key`) as invoices from `Order Transaction Fact`  OTF    where OTF.`Product ID`=%d and `Current Dispatching State`='Dispatched' $where_interval   ",$parent_key);
		break;
	}




	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$customers=$row['customers'];
		$invoices=$row['invoices'];
		$outers=$row['outers'];
		$sales=$row['net'];
		$profits=$row['profit'];


	}








	$response= array('state'=>200,

		'sales'=>money($sales,$currency),
		'profits'=>money($profits,$currency),
		'invoices'=>number($invoices),
		'customers'=>number($customers),
		'outers'=>number($outers),

	);

	echo json_encode($response);




}


function get_families_elements_numbers($data) {

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	$elements_numbers=array('InProcess'=>0,'Discontinued'=>0,'Normal'=>0,'Discontinuing'=>0,'NoSale'=>0);


	switch ($parent) {
	case'store':
		$where=sprintf("where `Product Family Store Key`=%d",$parent_key);
		$table='`Product Family Dimension`';
		break;
	case'category':
		$where=sprintf("where `Category Key`=%d",$parent_key);
		$table='`Product Family Dimension` F left join `Category Bridge` on (`Subject`="Family" and `Subject Key`=`Product Family Key`)';

		break;
	case'department':
		$where=sprintf("where `Product Family Main Department Key`=%d",$parent_key);
		$table='`Product Family Dimension`';

		break;
	case'none':
		$where=sprintf("where true");
		$table='`Product Family Dimension`';

		break;
	default:
		return;
	}


	$sql=sprintf("select count(*) as num ,`Product Family Record Type` from  $table %s group by  `Product Family Record Type`   ",
		$where);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Product Family Record Type']]=$row['num'];
	}
	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);
}



function get_products_elements_numbers($data) {
	global $user;

	$parent_key=$data['parent_key'];
	$parent=$data['parent'];

	$elements_numbers=array(
		'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>0),
		'web'=>array('ForSale'=>0,'OutofStock'=>0,'Discontinued'=>0,'Offline'=>0),
		'stock'=>array('Excess'=>0,'Normal'=>0,'Low'=>0,'VeryLow'=>0,'OutofStock'=>0,'Error'=>0)
	);


	switch ($parent) {

	case 'none':
		$where=sprintf(" where `Product Store Key` in (%s) ",join(',',$user->stores));
		$table='`Product Dimension`';
		$elements_stock_aux=$_SESSION['state']['stores']['products']['elements_stock_aux'];
		break;

	case 'store':
		$where=sprintf(' where  `Product Store Key`=%d',$parent_key);
		$table='`Product Dimension`';
		$elements_stock_aux=$_SESSION['state']['store']['products']['elements_stock_aux'];
		break;

	case 'department':
		$where=sprintf(' where  `Product Main Department Key`=%d',$parent_key);
		$table='`Product Dimension`';
		$elements_stock_aux=$_SESSION['state']['department']['products']['elements_stock_aux'];
		break;

	case 'family':
		$where=sprintf(' where  `Product Family Key`=%d',$parent_key);
		$table='`Product Dimension`';
		$elements_stock_aux=$_SESSION['state']['family']['products']['elements_stock_aux'];
		break;
	case 'category':
		$where=sprintf(" where `Subject`='Product' and  `Category Key`=%d",$parent_key);
		$table=' `Category Bridge` left join  `Product Dimension` C on (`Subject Key`=`Product ID`) ';
		$elements_stock_aux='';
		break;

	}

	$sql=sprintf("select count(*) as num,`Product Main Type` from  %s %s group by `Product Main Type`",$table,$where);
	$res=mysql_query($sql);



	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers['type'][$row['Product Main Type']]=number($row['num']);
	}

	$sql=sprintf("select count(*) as num,`Product Web State` from  %s %s group by `Product Web State`",$table,$where);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers['web'][preg_replace('/\s/','',$row['Product Web State'])]=number($row['num']);
	}


	switch ($elements_stock_aux) {
	case 'InWeb':
		$where.=' and `Product Web State`!="Offline" ' ;
		break;
	case 'ForSale':
		$where.=' and `Product Main Type`="Sale" ' ;
		break;
	}



	$sql=sprintf("select count(*) as num,`Product Availability State` from  %s %s group by `Product Availability State`",$table,$where);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers['stock'][$row['Product Availability State']]=number($row['num']);
	}





	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);

}

function list_products_availability_timeline() {

	include_once 'common_date_functions.php';

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	$conf_var='page_changelog';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['product_changelog'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['product_changelog'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['page']['product_changelog'];
		$conf_table='page';

	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['product_changelog'];
		$conf_table='site';

	}elseif ($parent=='product') {
		$conf=$_SESSION['state']['product']['availability'];
		$conf_table='product';

	}
	else {

		exit;
	}

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;


	$_order=$order;
	$_dir=$order_direction;

	if (count($user->stores)==0) {
		$where='where false ';
	}else {
		$where='where true ';
	}

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and PAT.`Store Key`=%d',$parent_key);
		break;

	case('department'):
		$where.=sprintf(' and PAT.`Department Key`=%d',$parent_key);
		break;
	case('family'):
		$where.=sprintf(' and PAT.`Family Key`=%d',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and PAT.`Store Key`=%d',$parent_key);
		break;
	case('product'):
		$where.=sprintf(' and PAT.`Product ID`=%d',$parent_key);
		break;
	default:
		exit();
		break;

	}



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";



	$sql="select  count(*) as total from `Product Availability Timeline` PAT  $where   ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count(*) as total_without_filters from `Product Availability Timeline` PAT  left join `Product Dimension` PD on (PAT.`Product ID` = PD.`Product ID`)   $where  $wheref ";


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);




	$rtext=number($total_records)." ".ngettext('change','changes',$total_records);





	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with code')." <b>$f_value</b>*)";
		break;
	case('title_label'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with label")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with label')." <b>$f_value</b>*)";
		break;

	}



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='code') {
		$order='`Page Code`';
	}if ($order=='title_label') {
		$order='`Page Short Title`';
	}if ($order=='operation') {
		$order='`Operation`';
	}if ($order=='state') {
		$order='`State`';
	}if ($order=='duration') {
		$order='`Duration`';
	}else {



		$order='`Date`';
	}

	$sql=sprintf("select  *  from `Product Availability Timeline` PAT  left join `Product Dimension` PD on (PAT.`Product ID` = PD.`Product ID`) $where $wheref order by $order $order_direction limit $start_from,$number_results ");



	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		switch ($row['Web State']) {
		case('Out of Stock'):
			$web_state='<span class=="out_of_stock">'._('Out of Stock').'</span>';
			break;
		case('For Sale'):
			$web_state=_('Online');
			break;
		case('Discontinued'):
			$web_state=_('Discontinued');
		case('Offline'):
			$web_state=_('Offline');
		default:
			$web_state=$row['Product Web State'];


			break;


		}
		switch ($row['Availability']) {
		case('Yes'):
			$availability=_('Yes');
			break;
		case('No'):
			$availability=_('No');
			break;
		}

		$duration=gettext_relative_time($row['Duration']);

		$data[]=array(
			'code'=>sprintf("<a href='product.php?pid=%d'>%s</a>",$row['Product ID'],$row['Product Code']),
			'description'=>$row['Product Name'],
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($row['Date']." +00:00")),
			'availability'=>$availability,
			'web_state'=>$web_state,
			'duration'=>$duration,


		);


	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function get_history_numbers($data) {

	$subject_key=$data['subject_key'];
	$subject=$data['subject'];

	$elements_numbers=array('WebLog'=>0,'Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0);

	if ($subject=='family') {
		$sql=sprintf("select count(*) as num , `Type` from  `Product Family History Bridge` where `Family Key`=%d group by `Type`",$subject_key);
	}elseif ($subject=='department') {
		$sql=sprintf("select count(*) as num , `Type` from  `Product Department History Bridge` where `Department Key`=%d group by `Type`",$subject_key);
	}elseif ($subject=='product') {
		$sql=sprintf("select count(*) as num , `Type` from  `Product History Bridge` where `Product ID`=%d group by `Type`",$subject_key);
	}

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Type']]=$row['num'];
	}
	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);
}


function list_favorite_products() {

	if (isset($_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit();
	}
	
	if (isset($_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit();
	}
	
	switch ($parent) {
	    case 'site':
	        $_conf='site';
	        break;
	        case 'store':
	        $_conf='store';
	        break; 
	    default:
	        exit();
	        break;
	}
	
		$conf=$_SESSION['state'][$_conf]['favorites_products'];


	

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state'][$_conf]['favorites_products']['order']=$order;
	$_SESSION['state'][$_conf]['favorites_products']['order_dir']=$order_direction;
	$_SESSION['state'][$_conf]['favorites_products']['nr']=$number_results;
	$_SESSION['state'][$_conf]['favorites_products']['sf']=$start_from;
	$_SESSION['state'][$_conf]['favorites_products']['f_field']=$f_field;
	$_SESSION['state'][$_conf]['favorites_products']['f_value']=$f_value;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$table=' `Customer Favorite Product Bridge` F left join `Product Dimension` P on (F.`Product ID`=P.`Product ID`)';


    switch ($parent) {
        case 'site':
            $where=sprintf(' where `Site Key`=%d',$parent_key);
            break;
           case 'store':
            $where=sprintf(' where `Store Key`=%d',$parent_key);
            break; 
        default:
            exit();
            break;
    }


	$wheref="";


	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Product Code` like '".addslashes($f_value)."%'";
	
	$sql="select count(distinct F.`Product ID`) as total from  $table  $where $wheref";
	//   print $mode.' '.$sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct F.`Product ID`) as total from  $table  $where      ";

		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>10)
		$rtext_rpp=' ('._("Showing all").')';
	else {
		$rtext_rpp='';
	}

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product code like")." <b>$f_value</b> ";
			break;
	

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('product','products',$total)." "._('with code like')." <b>*".$f_value."*</b>";
			break;
		
		}
	}
	else
		$filter_msg='';


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='code')
		$order='`Product Code File As`';
	elseif ($order=='name')
		$order='`Product Name`';	
	elseif ($order=='customers')
		$order='count(distinct F.`Customer Key`)';
	elseif ($order=='last_favorited')
		$order='last_favorited';
	else
		$order='`Product Code File As`';


	$sql="select   F.`Product ID` ,`Product Code`,`Product Name`, count(distinct F.`Customer Key`) as customers ,max(F.`Date Created`) as last_favorited from    $table   $where $wheref  group by F.`Product ID`    order by $order $order_direction  limit $start_from,$number_results ";


	$data=array();
	$res = mysql_query($sql);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$data[]=array(
			'code'=>sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']),
			'name'=>$row['Product Name'],

			'customers'=>number($row['customers']),
			'last_favorited'=>strftime("%a %e %b %Y %H:%M %Z",strtotime($row['last_favorited'].' +0:00')),
			

		);
	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}


?>
