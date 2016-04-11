<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 7 April 2016 at 00:38:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$where="where true  ";
$table="`Barcode Dimension` B  ";
$filter_msg='';
$filter_msg='';
$wheref='';

$fields='';

if ($parameters['parent']=='account') {




}else {
	exit("parent not found ".$parameters['parent']);
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
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<3) {
		$where.=' and `Barcode Status` in ('.$_elements.')' ;
	}
	break;


}




if ($parameters['f_field']=='number' and $f_value!='')
	$wheref.=" and  `Barcode Number` like '%".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;

if ($order=='number') {
	$order='`Barcode Number`';
}elseif ($order=='status') {
	$order='`Barcode Status`';
}else {

	$order='`Barcode Key`';
}

$order='B.'.$order;


$sql_totals="select count(Distinct `Barcode Key`) as num from $table  $where  ";

$fields.='`Barcode Key`,`Barcode Number`,`Barcode Status`,`Barcode Sticky Note`,(select CONCAT_WS(",",`Part SKU`,`Part Reference`) from `Barcode Asset Bridge` left join `Part Dimension` on (`Barcode Asset Type`="Part" and `Barcode Asset Key`=`Part SKU`) where `Barcode Asset Status`="Assigned" and `Barcode Asset Barcode Key`=B.`Barcode Key` limit 1 ) as parts';



?>
