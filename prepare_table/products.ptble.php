<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
include_once('utils/date_functions.php');
$period_tag=get_interval_db_name($parameters['f_period']);

$group_by='';
$table="`Product Dimension` P left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where_interval='';
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


//print_r($parameters);


switch ($parameters['parent']) {
case('list'):

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $_REQUEST['parent_key']);

	$res=mysql_query($sql);
	if ($customer_list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($customer_list_data['List Type']=='Static') {

			$table='`List Product Bridge` PB left join `Product Dimension` P  on (PB.`Product ID`=P.`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
			$where=sprintf(' where `List Key`=%d ', $_REQUEST['parent_key']);

		} else {// Dynamic by DEFAULT



			$tmp=preg_replace('/\\\"/', '"', $customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/', '"', $tmp);
			$tmp=preg_replace('/\'/', "\'", $tmp);

			$raw_data=json_decode($tmp, true);

			$raw_data['store_key']=$store;
			list($where, $table)=product_awhere($raw_data);
			$where='where true'.$where;
		}

	} else {
		exit("error");
	}

	break;
case('stores'):
case('account'):
	$where=sprintf(" where `Product Store Key` in (%s) ", join(',', $user->stores));
	break;
case('store'):
	$where=sprintf(' where `Product Store Key`=%d', $parameters['parent_key']);
	break;
	
case('part'):
		$table='`Product Dimension`  P  left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`) left join `Product Part Bridge` B on (B.`Product Part Product ID`=P.`Product ID`)';

		$where=sprintf(' where `Product Part Part SKU`=%d  ', $parameters['parent_key']);
	break;	

case('customer_favourites'):

	$table="`Product Dimension` P left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`) left join `Customer Favorite Product Bridge` F on (F.`Product ID`=P.`Product ID`)";


	$where.=sprintf(' where F.`Customer Key`=%d', $parameters['parent_key']);
	break;

case('customer'):

	$table=" `Order Transaction Fact` OTF  left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`) ";
	$group_by=' group by OTF.`Product ID`';
	$where=sprintf(' where `Customer Key`=%d', $parameters['parent_key']);
	break;
case('category'):
	include_once 'class.Category.php';
	$category=new Category($parameters['parent_key']);

	if (!in_array($category->data['Category Store Key'], $user->stores)) {
		return;
	}

	$where=sprintf(" where `Subject`='Product' and  `Category Key`=%d", $parameters['parent_key']);
	$table=' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
	break;
default:


}


switch ($parameters['elements_type']) {

case 'status':
	$_elements='';
	$count_elements=0;
	foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
		if ($_value['selected']) {
			$count_elements++;
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<4) {
		$where.=' and `Product Status` in ('.$_elements.')' ;
	}
	break;




}










if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='description' and $f_value!='')
	$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";



$_dir=$order_direction;
$_order=$order;


if ($order=='stock')
	$order='`Product Availability`';
elseif ($order=='code' )
	$order='`Product Code File As`';
elseif ($order=='name')
	$order='`Product Name`';
elseif ($order=='available_for')
	$order='`Product Available Days Forecast`';

elseif ($order=='profit') {

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
elseif ($order=='formatted_record_type') {
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

$fields="P.`Product ID`,`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Code`,`Store Key`";

//$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
// print $sql;


function product_awhere($awhere) {


	$where_data=array(
		//'product_ordered1'=>'∀',
		'price'=>array(),
		'invoice'=>array(),
		'web_state'=>array(),
		'availability_state'=>array(),
		'geo_constraints'=>'',
		'created_date_to'=>'',
		'product_valid_from'=>'',
		'product_valid_to'=>'',
		'price_lower'=>'',
		'price_upper'=>'',
		'invoice_lower'=>'',
		'invoice_upper'=>''
	);


	//  $awhere=json_decode($awhere,TRUE);


	foreach ($awhere as $key=>$item) {
		$where_data[$key]=$item;
	}


	$where='where true';
	$table='`Product Dimension` P ';

	$use_product=false;
	//$use_categories =false;
	$use_otf =false;



	$price_where='';
	foreach ($where_data['price'] as $price) {
		switch ($price) {
		case 'less':
			$price_where.=sprintf(" and `Product Price`<%s ", prepare_mysql($where_data['price_lower']));
			break;
		case 'equal':
			$price_where.=sprintf(" and `Product Price`=%s  ", prepare_mysql($where_data['price_lower']));
			break;
		case 'more':
			$price_where.=sprintf(" and `Product Price`>%s  ", prepare_mysql($where_data['price_upper']));
			break;
		case 'between':
			$price_where.=sprintf(" and  `Product Price`>%s  and `Product Price`<%s", prepare_mysql($where_data['price_lower']), prepare_mysql($where_data['price_upper']));
			break;
		}
	}
	$price_where=preg_replace('/^\s*and/', '', $price_where);

	if ($price_where!='') {
		$where.=" and ($price_where)";
	}

	$invoice_where='';
	foreach ($where_data['invoice'] as $invoice) {
		switch ($invoice) {
		case 'less':
			$invoice_where.=sprintf(" and `Product Total Invoiced Amount`<%s ", prepare_mysql($where_data['invoice_lower']));
			break;
		case 'equal':
			$invoice_where.=sprintf(" and `Product Total Invoiced Amount`=%s  ", prepare_mysql($where_data['invoice_lower']));
			break;
		case 'more':
			$invoice_where.=sprintf(" and `Product Total Invoiced Amount`>%s  ", prepare_mysql($where_data['invoice_upper']));
			break;
		case 'between':
			$invoice_where.=sprintf(" and `Product Total Invoiced Amount`>%s  and `Product Total Invoiced Amount`<%s", prepare_mysql($where_data['invoice_lower']), prepare_mysql($where_data['invoice_upper']));
			break;
		}
	}
	$invoice_where=preg_replace('/^\s*and/', '', $invoice_where);

	if ($invoice_where!='') {
		$where.=" and ($invoice_where)";
	}



	$web_state_where='';
	foreach ($where_data['web_state'] as $web_state) {
		switch ($web_state) {
		case 'online_force_out_of_stock':
			$web_state_where.=sprintf(" or `Product Web Configuration`='Online Force Out of Stock' ");
			break;
		case 'online_auto':
			$web_state_where.=sprintf(" or `Product Web Configuration`='Online Auto'  ");
			break;
		case 'offline':
			$web_state_where.=sprintf(" or  `Product Web Configuration`='Offline'  ");
			break;
		case 'unknown':
			$web_state_where.=sprintf(" or  `Product Web Configuration`='Unknown'  ");
			break;
		case 'online_force_for_sale':
			$web_state_where.=sprintf(" or  `Product Web Configuration`='Online Force For Sale'  ");
			break;
		}
	}
	$web_state_where=preg_replace('/^\s*or/', '', $web_state_where);
	if ($web_state_where!='') {
		$where.=" and ($web_state_where)";
	}

	$availability_state_where='';
	foreach ($where_data['availability_state'] as $availability_state) {
		switch ($availability_state) {
		case 'optimal':
			$availability_state_where.=sprintf(" or `Product Availability State`='Optimal' ");
			break;
		case 'low':
			$availability_state_where.=sprintf(" or `Product Availability State`='Low'  ");
			break;
		case 'critical':
			$availability_state_where.=sprintf(" or  `Product Availability State`='Critical'  ");
			break;
		case 'surplus':
			$availability_state_where.=sprintf(" or  `Product Availability State`='Surplus'  ");
			break;
		case 'out_of_stock':
			$availability_state_where.=sprintf(" or  `Product Availability State`='Out of Stock'  ");
			break;

		case 'unknown':
			$availability_state_where.=sprintf(" or  `Product Availability State`='Unknown'  ");
			break;

		case 'no_applicable':
			$availability_state_where.=sprintf(" or  `Product Availability State`='No applicable'  ");
			break;
		}
	}
	$availability_state_where=preg_replace('/^\s*or/', '', $availability_state_where);
	if ($availability_state_where!='') {
		$where.=" and ($availability_state_where)";
	}



	$date_interval_from=prepare_mysql_dates($where_data['product_valid_from'], '', '`Product Valid From`', 'only_dates');
	$date_interval_to=prepare_mysql_dates('', $where_data['product_valid_to'], '`Product Valid To`', 'only_dates');



	$where.=$date_interval_from['mysql'].$date_interval_to['mysql'];


	/*

    $where_billing_geo_constraints='';
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Billing To Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }


    */

	//print $table. $where; exit;

	return array($where, $table);
}


?>
