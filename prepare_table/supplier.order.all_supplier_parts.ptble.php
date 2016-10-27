<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 26 June 2016 at 12:08:33 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$where="where `Supplier Part Status`='Available' and `Part Status` not in ('Not In Use','Discontinuing') ";
$table="`Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)  left join `Part Data` PD on (PD.`Part SKU`=SP.`Supplier Part Part SKU`) left join `Supplier Dimension` S on (SP.`Supplier Part Supplier Key`=S.`Supplier Key`)  ";


$fields='`Supplier Part Historic Key`,`Supplier Code`,`Supplier Part Key`,`Supplier Part Part SKU`,`Part Reference`,`Part Unit Description`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Supplier Part Status`,`Supplier Part From`,`Supplier Part To`,`Supplier Part Unit Cost`,`Supplier Part Currency Code`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,`Supplier Part Minimum Carton Order`,
`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Package Weight`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Quarter To Day Acc Dispatched`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Reference`,`Part Total Acc Dispatched`,
`Part Days Available Forecast`,`Part 1 Quarter Acc Dispatched`,`Part Products Web Status`,`Part On Demand`


';

$filter_msg='';
$sql_type='part';
$filter_msg='';
$wheref='';

if ($parameters['parent']=='purchase_order') {
	if ($purchase_order->get('Purchase Order Parent')=='Supplier') {

		$where.=sprintf(" and  `Supplier Part Supplier Key`=%d", $purchase_order->get('Purchase Order Parent Key'));


	}else {
		$where.=sprintf("  and  `Agent Supplier Agent Key`=%d", $purchase_order->get('Purchase Order Parent Key'));
		$table.=' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';


	}



	$fields.=sprintf(',(select `Purchase Order Quantity` from `Purchase Order Transaction Fact` POTF where POTF.`Purchase Order Key`=%d and POTF.`Supplier Part Key`=SP.`Supplier Part Key` ) as `Purchase Order Quantity`', $parameters['parent_key']);

}else {
	exit("parent not found: ".$parameters['parent']);
}



if (isset($parameters['elements_type'])) {

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




		$_elements=preg_replace('/^\,/', '', $_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($count_elements<3) {
			$where.=' and `Supplier Part Status` in ('.$_elements.')' ;

		}
		break;
	case 'part_status':
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
		} elseif ($count_elements==1) {
			if ($_elements=="'InUse'")$_elements="'In Use'";
			elseif ($_elements=="'NotInUse'")$_elements="'Not In Use'";

			$where.=' and `Part Status`='.$_elements.'' ;

		}
		break;


	}
}

if ($parameters['f_field']=='reference' and $f_value!='')
	$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='supplied_by' and $f_value!='')
	$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='sku' and $f_value!='')
	$wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";
elseif ($parameters['f_field']=='description' and $f_value!='')
	$wheref.=" and  `Part Unit Description` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;

if ($order=='description') {
	$order='`Part Unit Description`';
}elseif ($order=='reference') {
	$order='`Supplier Part Reference`';
}elseif ($order=='cost') {
	$order='`Supplier Part Unit Cost`';
}elseif ($order=='supplier_code') {
	$order='`Supplier Code`';
}elseif ($order=='stock') {
	$order='`Part Current Stock`';
}elseif($order=='quantity'){
$order='`Purchase Order Quantity`';
}else {

	$order='`Supplier Part Key`';
}



$sql_totals="select count(Distinct SP.`Supplier Part Key`) as num from $table  $where  ";



//print $sql_totals;

?>
