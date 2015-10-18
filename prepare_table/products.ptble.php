<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

$period_tag=get_interval_db_name($parameters['f_period']);

$group_by='';
$table="`Product Dimension` P left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where_type='';
$where_interval='';
$where='where true';
$wheref='';

if (isset($parameters['awhere']) and $parameters['awhere']) {

	$tmp=preg_replace('/\\\"/', '"', $awhere);
	$tmp=preg_replace('/\\\\\"/', '"', $tmp);
	$tmp=preg_replace('/\'/', "\'", $tmp);

	$raw_data=json_decode($tmp, true);
	$raw_data['store_key']=$store;
	list($where, $table)=product_awhere($raw_data);

	$where_type='';
	$where_interval='';
}





switch ($parameters['parent']) {
case('list'):

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $_REQUEST['parent_key']);

	$res=mysql_query($sql);
	if ($customer_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($customer_list_data['List Type']=='Static') {

			$table='`List Product Bridge` PB left join `Product Dimension` P  on (PB.`Product ID`=P.`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
			$where_type=sprintf(' and `List Key`=%d ', $_REQUEST['parent_key']);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/', '"', $customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/', '"', $tmp);
			$tmp=preg_replace('/\'/', "\'", $tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$store;
			list($where, $table)=product_awhere($raw_data);
		}

	} else {
		exit("error");
	}

	break;
case('stores'):
	$where.=sprintf(" and `Product Store Key` in (%s) ", join(',', $user->stores));
	break;
case('store'):
	$where.=sprintf(' and `Product Store Key`=%d', $parameters['parent_key']);
	break;
case('department'):
	$where.=sprintf('  and `Product Main Department Key`=%d', $parameters['parent_key']);
	break;
case('family'):
	$where.=sprintf(' and `Product Family Key`=%d', $parameters['parent_key']);
	break;
case('customer_favourites'):

$table="`Product Dimension` P left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`) left join `Customer Favorite Product Bridge` F on (F.`Product ID`=P.`Product ID`)";


	$where.=sprintf(' and F.`Customer Key`=%d', $parameters['parent_key']);
	break;	
	
case('customer'):

$table=" `Order Transaction Fact` OTF  left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`) ";
$group_by=' group by OTF.`Product ID`';
	$where.=sprintf(' and `Customer Key`=%d', $parameters['parent_key']);
	break;	
case('category'):
	include_once 'class.Category.php';
	$category=new Category($parameters['parent_key']);

	if (!in_array($category->data['Category Store Key'], $user->stores)) {
		return;
	}
	$where_type='';

	$where=sprintf(" where `Subject`='Product' and  `Category Key`=%d", $parameters['parent_key']);
	$table=' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
	break;
default:


}



$where.=$where_type;


$elements_counter=0;


/*
switch ($elements_type) {
case 'type':
	$_elements='';
	foreach ($elements['type'] as $_key=>$_value) {
		if ($_value) {
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<5) {
		$where.=' and `Product Main Type` in ('.$_elements.')' ;
	}
	break;
case 'web':
	$_elements='';
	foreach ($elements['web'] as $_key=>$_value) {
		if ($_value) {
			if ($_key=='OutofStock')
				$_key='Out of Stock';
			elseif ($_key=='ForSale')
				$_key='For Sale';
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<4) {
		$where.=' and `Product Web State` in ('.$_elements.')' ;
	}
	break;

case 'stock':


	switch ($elements_stock_aux) {
	case 'InWeb':
		$where.=' and `Product Web State`!="Offline" ' ;
		break;
	case 'ForSale':
		$where.=' and `Product Main Type`="Sale" ' ;
		break;
	}


	$_elements='';
	foreach ($elements['stock'] as $_key=>$_value) {
		if ($_value) {
			$_elements.=','.prepare_mysql($_key);
			$elements_counter++;
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($elements_counter<6) {
		$where.=' and `Product Availability State` in ('.$_elements.')' ;
	}
	break;


}

*/

if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='description' and $f_value!='')
	$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";



$_dir=$order_direction;
$_order=$order;


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
}else {
	$order='P.`Product ID`';
}



$sql_totals="select count(distinct  P.`Product ID`) as num from $table $where";

$fields="*";

	//$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	// print $sql;

?>
