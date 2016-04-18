<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 April 2016 at 14:45:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Inventory Transaction Fact` ITF left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`)
 left join `Location Dimension` L on (ITF.`Location Key`=L.`Location Key`)
 ";
$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

$fields='';

if ($parameters['parent']=='part') {

	$where=sprintf(" where  `Inventory Transaction Record Type`='Movement' and ITF.`Part SKU`=%d", $parameters['parent_key']);



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

	$order='`Date`';
}



$sql_totals="select count(Distinct `Inventory Transaction Key`) as num from $table  $where  ";

$fields.='`Date`,`Inventory Transaction Section`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,
`Part Reference`,ITF.`Part SKU`,`Delivery Note ID`,ITF.`Delivery Note Key`,ITF.`Location Key`,`Location Code`,
`Note`';



?>
