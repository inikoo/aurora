<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 September 2015 20:13:47 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Part Dimension` P  ";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

$fields='';

if (isset($parameters['awhere']) and $parameters['awhere']) {

	$tmp=preg_replace('/\\\"/', '"', $awhere);
	$tmp=preg_replace('/\\\\\"/', '"', $tmp);
	$tmp=preg_replace('/\'/', "\'", $tmp);

	$raw_data=json_decode($tmp, true);
	//$raw_data['store_key']=$store;
	//print_r($raw_data);exit;
	list($where, $table, $sql_type)=parts_awhere($raw_data);

	$where_type='';
	$where_interval='';
}
elseif ($parameters['parent']=='list') {

	$sql=sprintf("select * from `List Dimension` where `List Key`=%d", $parameters['parent_key']);
	//print $sql;exit;
	$res=mysql_query($sql);
	if ($list_data=mysql_fetch_assoc($res)) {
		$awhere=false;
		if ($list_data['List Type']=='Static') {

			$table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
			$where.=sprintf(' and `List Key`=%d ', $parameters['parent_key']);

		} else {
			$tmp=preg_replace('/\\\"/', '"', $list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/', '"', $tmp);
			$tmp=preg_replace('/\'/', "\'", $tmp);

			$raw_data=json_decode($tmp, true);
			//print_r($raw_data);
			//$raw_data['store_key']=$store;
			list($where, $table, $sql_type)=parts_awhere($raw_data);
		}

	} else {

	}
}
elseif ($parameters['parent']=='category') {

	include_once 'class.Category.php';

	$category=new Category($parameters['parent_key']);

	if (!in_array($category->data['Category Warehouse Key'], $user->warehouses)) {
		return;
	}

$fields=' "" as `Warehouse Code`,';

	$where=sprintf(" where `Subject`='Part' and  `Category Key`=%d", $parameters['parent_key']);
	$table=' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) ';
	$where_type='';



}
elseif ($parameters['parent']=='warehouse') {
	$where=sprintf(" where  `Warehouse Key`=%d", $parameters['parent_key']);
$fields=' "" as `Warehouse Code`,';

	$table="`Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)";


}elseif ($parameters['parent']=='account') {




}else{
exit("parent not found ".$parameters['parent']);
}

if(isset($extra_where))
$where.=$extra_where;



if (isset($parameters['elements_type'])) {

	switch ($parameters['elements_type']) {
	case 'stock_status':
		$_elements='';
		$count_elements=0;
		foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
			if ($_value['selected']) {
				$count_elements++;
				$_elements.=','.prepare_mysql($_key);

			}
		}




		$_elements=preg_replace('/^\,/', '', $_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($count_elements<5) {
			$where.=' and `Part Stock Status` in ('.$_elements.')' ;

		}
		break;
	
}
}



if ($parameters['f_field']=='used_in' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='reference' and $f_value!='')
	$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='supplied_by' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='sku' and $f_value!='')
	$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
elseif ($parameters['f_field']=='description' and $f_value!='')
	$wheref.=" and  `Part Unit Description` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;

if ($order=='stock')
	$order='`Part Current Stock`';
elseif ($order=='sku')
	$order='`Part SKU`';
elseif ($order=='id')
	$order='`Part SKU`';
elseif ($order=='formatted_sku')
	$order='`Part SKU`';
elseif ($order=='reference')
	$order='`Part Reference`';
elseif ($order=='description')
	$order='`Part Unit Description`';
elseif ($order=='available_for')
	$order='`Part Available Days Forecast`';
elseif ($order=='supplied_by')
	$order='`Part XHTML Currently Supplied By`';
elseif ($order=='products')
	$order='`Part Currently Used In`';
elseif ($order=='margin') {
	$order=' `Part '.$period_tag.' Acc Margin` ';
} elseif ($order=='sold') {
	$order=' `Part '.$period_tag.' Acc Sold` ';
} elseif ($order=='money_in') {
	$order=' `Part '.$period_tag.' Acc Sold Amount` ';
} elseif ($order=='profit_sold') {

	$order=' `Part '.$period_tag.' Acc Profit` ';
} elseif ($order=='avg_stock') {

	$order=' `Part '.$period_tag.' Acc AVG Stock` ';


} elseif ($order=='avg_stockvalue') {

	$order=' `Part '.$period_tag.' Acc AVG Stock Value` ';

} elseif ($order=='keep_days') {

	$order=' `Part '.$period_tag.' Acc Keeping Days` ';
} elseif ($order=='outstock_days') {

	$order=' `Part '.$period_tag.' Acc Out of Stock Days` ';

} elseif ($order=='unknown_days') {

	$order=' `Part '.$period_tag.' Acc Unknown Stock Days` ';

} elseif ($order=='gmroi') {

	$order=' `Part '.$period_tag.' Acc GMROI` ';

}elseif ($order=='stock_value') {

	$order=' `Part Current Value` ';

}elseif ($order=='delta_money_in') {

	$order=' `Part '.$period_tag.' Acc 1YD Sold`';

}elseif ($order=='delta_sold') {

	$order=' `Part '.$period_tag.' Acc 1YD Sold Amount`';

}elseif ($order=='stock_days') {

	$order=' `Part Days Available Forecast`';

}elseif ($order=='next_shipment') {

	$order=' `Part Next Supplier Shipment`';

}elseif ($order=='package_type') {
	$order='`Part Package Type`';
}elseif ($order=='package_weight') {
	$order='`Part Package Weight`';
}elseif ($order=='Package') {
	$order='`Part Package Dimensions Volume`';
}elseif ($order=='package_volume') {
	$order='`Part Package Dimensions Volume`';
}elseif ($order=='unit_weight') {
	$order='`Part Unit Weight`';
}elseif ($order=='unit_dimension') {
	$order='`Part Unit Dimensions Volume`';
}elseif ($order=='from') {
	$order='`Part Valid From`';
}elseif ($order=='to') {
	$order='`Part Valid To`';
}elseif ($order=='last_update') {
	$order='`Part Last Updated`';
}else {

	$order='`Part SKU`';
}

$order='P.'.$order;


$sql_totals="select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields.='P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`';

function parts_awhere($awhere) {

	$sql_type='part';

	$where_data=array(
		//'product_ordered1'=>'∀',
		//'price'=>array(),
		//'invoice'=>array(),
		//'web_state'=>array(),
		//'availability_state'=>array(),
		'tariff_code'=>'',
		'invalid_tariff_code'=>false,
		'geo_constraints'=>'',
		'part_valid_from'=>'',
		'part_valid_to'=>'',
		'part_dispatched_from'=>'',
		'part_dispatched_to'=>'',

		//'product_valid_to'=>'',
		//'price_lower'=>'',
		//'price_upper'=>'',
		//'invoice_lower'=>'',
		// 'invoice_upper'=>''
	);


	//  $awhere=json_decode($awhere,TRUE);


	foreach ($awhere as $key=>$item) {
		$where_data[$key]=$item;
	}


	$date_interval_from=prepare_mysql_dates($where_data['part_valid_from'], $where_data['part_valid_to'], array('`Part Valid From`', '`Part Valid To`'), 'whole_day');
	$date_dispatched=prepare_mysql_dates($where_data['part_dispatched_from'], $where_data['part_dispatched_to'], 'ITF.`Date`', 'whole_day');
	if ($where_data['geo_constraints']!='') {
		$where_geo_constraints=extract_products_geo_groups($where_data['geo_constraints'], '`Dispatch Country Code`', 'CD.`World Region Code`');
	}else {
		$where_geo_constraints='';
	}




	if ($date_dispatched['mysql']!='' or $where_geo_constraints!='' ) {
		$sql_type='itf';
		$where='where  `Inventory Transaction Type` in ("Sale","OIP")  ';
		$table='`Inventory Transaction Fact` ITF  left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) ';
	}else {
		$sql_type='part';
		$where="where true  ";
		$table="`Part Dimension` P";
	}


	if ($where_data['invalid_tariff_code']=='Yes') {
		$where.=" and `Part Tariff Code Valid`='Yes'";

	}elseif ($where_data['invalid_tariff_code']=='No') {
		$where.=" and `Part Tariff Code Valid`='No'";
	}


	$where.=$date_dispatched['mysql'];
	$where.=$date_interval_from['mysql'];
	$where.=$where_geo_constraints;
	//print_r($where_data);
	//print "$table $where  *";exit;
	return array($where, $table, $sql_type);
}


?>
