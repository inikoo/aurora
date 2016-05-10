<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 12:00:00 BST (aprox), Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
case('store'):
	$where=sprintf(' where  `Deal Store Key`=%d', $parameters['parent_key']);
	break;
case('campaign'):
	$where=sprintf(' where D.`Deal Campaign Key`=%d', $parameters['parent_key']);
	break;
case('account'):
	$where=sprintf(' where true ');
	break;
default:
	$where='where false';
}


if(isset($parameters['elements_type'])){
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
		$where.=' and `Deal Status` in ('.$_elements.')' ;
				

	}

	break;
case 'trigger':
	$_elements='';
	$count_elements=0;
	foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key=>$_value) {
		if ($_value['selected']) {
			$count_elements++;
			$_elements.=",'".addslashes(preg_replace('/_/',' ',$_key))."'";
			
			
		}
	}

	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ( $count_elements<7) {
		$where.=' and `Deal Trigger` in ('.$_elements.')' ;
				

	}

	break;

}
}







$wheref='';
if ($parameters['f_field']=='name' and $f_value!='')
	$wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;


if ($order=='name'){
	$order='`Deal Name`';
}elseif ($order=='orders'){
	$order='`Deal Total Acc Used Orders`';
}elseif ($order=='customers'){
	$order='`Deal Total Acc Used Customers`';
}elseif ($order=='from'){
	$order='`Deal Begin Date`';
}elseif ($order=='to'){
	$order='`Deal Expiration Date`';
}elseif ($order=='description'){
	$order='`Deal Term Allowances Label`';
}else{
	$order='`Deal Key`';
}
$table='`Deal Dimension` D left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) ';
$fields="`Deal Key`,`Deal Name`,`Deal Term Allowances`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`";


$sql_totals="select count(*) as num from $table $where ";




?>
