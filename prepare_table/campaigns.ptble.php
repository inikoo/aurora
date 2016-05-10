<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 13:12:39 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
case('store'):
	$where=sprintf(' where  `Deal Campaign Store Key`=%d', $parameters['parent_key']);
	break;
case('account'):
	$where=sprintf(' where true ');
	break;
default:
	$where='where false';
}


if (isset($parameters['elements_type'])) {
	switch ($parameters['elements_type']) {
	case 'status':

		$_elements='';
		$count_elements=0;
		foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
			if ($_value['selected']) {
				$count_elements++;
				$_elements.=",'".addslashes($_key)."'";


			}
		}

		$_elements=preg_replace('/^\,/', '', $_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ( $count_elements<4) {
			$where.=' and `Deal Campaign Status` in ('.$_elements.')' ;


		}

		break;


	}
}







$wheref='';
if ($parameters['f_field']=='name' and $f_value!='')
	$wheref.=" and  `Deal Campaign Name` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;


if ($order=='name') {
	$order='`Deal Campaign Name`';
}elseif ($order=='orders') {
	$order='`Deal Campaign Total Acc Used Orders`';
}elseif ($order=='customers') {
	$order='`Deal Campaign Total Acc Used Customers`';
}elseif ($order=='from') {
	$order='`Deal Campaign Valid From`';
}elseif ($order=='to') {
	$order='`Deal Campaign Valid To`';
}else {
	$order='`Deal Campaign Key`';
}
$table='`Deal Campaign Dimension` C ';
$fields="`Deal Campaign Key`,`Deal Campaign Name`,`Deal Campaign Store Key`,`Deal Campaign Status`,`Deal Campaign Valid From`,`Deal Campaign Valid To`,
`Deal Campaign Total Acc Used Orders`,`Deal Campaign Total Acc Used Customers`,`Deal Campaign Status`,`Deal Campaign Number Current Deals`";


$sql_totals="select count(*) as num from $table $where ";




?>
