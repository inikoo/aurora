<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 6 October 2015 at 09:53:04 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



if ($parameters['parent']=='customer' or $parameters['parent']=='order_customer') {
	$where=sprintf(' where   B.`Customer Key`=%d   ', $parameters['parent_key']);
	$subject='Customer';
}elseif ($parameters['parent']=='store') {
	$where=sprintf(' where   B.`Store Key`=%d   ', $parameters['parent_key']);
	$subject='Store';
}elseif ($parameters['parent']=='department') {
	$where=sprintf(' where   B.`Department Key`=%d   ', $parameters['parent_key']);
	$subject='Product Department';
}elseif ($parameters['parent']=='family') {
	$where=sprintf(' where   B.`Family Key`=%d   ', $parameters['parent_key']);
	$subject='Product Family';
}elseif ($parameters['parent']=='product') {
	$where=sprintf(' where   B.`Product ID`=%d   ', $parameters['parent_key']);
	$subject='Product';
}elseif ($parameters['parent']=='part') {
	$where=sprintf(' where   B.`Part SKU`=%d   ', $parameters['parent_key']);
	$subject='Part';
}elseif ($parameters['parent']=='employee') {
	$where=sprintf(' where   B.`Staff Key`=%d   ', $parameters['parent_key']);
	$subject='Staff';
}elseif ($parameters['parent']=='supplier_product') {
	$where=sprintf(' where   B.`Supplier Product ID`=%d   ', $parameters['parent_key']);
	$subject='Supplier Product';
}elseif ($parameters['parent']=='account') {
	$where=sprintf(' where  true  ');
	$subject='Account';
}elseif ($parameters['parent']=='site') {
	$where=sprintf(' where   B.`Site Key`=%d   ', $parameters['parent_key']);
	$subject='Site';
}elseif ($parameters['parent']=='porder') {
	$where=sprintf(' where   B.`Purchase Order Key`=%d   ', $parameters['parent_key']);
	$subject='Purchase Order';
}elseif ($parameters['parent']=='supplier_dn') {
	$where=sprintf(' where   B.`Supplier Delivery Note Key`=%d   ', $parameters['parent_key']);
	$subject='Supplier Delivery Note';
}elseif ($parameters['parent']=='supplier_invoice') {
	$where=sprintf(' where   B.`Supplier Invoice Key`=%d   ', $parameters['parent_key']);
	$subject='Supplier Invoice';
}elseif ($parameters['parent']=='order') {
	$where=sprintf(' where   B.`Order Key`=%d   ', $parameters['parent_key']);
	$subject='Order';
}elseif ($parameters['parent']=='dn') {
	$where=sprintf(' where   B.`Delivery Note Key`=%d   ', $parameters['parent_key']);
	$subject='Delivery Note';
}elseif ($parameters['parent']=='invoice') {
	$where=sprintf(' where   B.`Invoice Key`=%d   ', $parameters['parent_key']);
	$subject='Invoice';
}

elseif ($parameters['parent']=='supplier') {
	$where=sprintf(' where   B.`Supplier Key`=%d   ', $parameters['parent_key']);
	$subject='Supplier';
}


/*

$_elements='';

if (is_array($elements)) {
	foreach ($elements as $_key=>$_value) {
		if ($_value)
			$_elements.=','.prepare_mysql($_key);
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} else {
		$where.=' and Type in ('.$_elements.')' ;
	}
}
*/

$wheref='';



if ( $parameters['f_field']=='notes' and $f_value!='' )
	$wheref.=" and   `History Abstract` like '%".addslashes($f_value)."%'   ";
elseif ($parameters['f_field']=='upto' and is_numeric($f_value) )
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`History Date`))<=".$f_value."    ";
elseif ($parameters['f_field']=='older' and is_numeric($f_value))
	$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`History Date`))>=".$f_value."    ";
elseif ($parameters['f_field']=='author' and $f_value!='') {
	$wheref.=" and   `Author Name` like '".addslashes($f_value)."%'   ";
}

$_order=$order;
$_dir=$order_direction;
if ($order=='date') {
	$order="`History Date` $order_direction , `History Key` $order_direction ";
}
if ($order=='note')
	$order="`History Abstract` $order_direction";
if ($order=='objeto')
	$order="`Direct Object` $order_direction";
if ($order=='author')
	$order="`Author Name` $order_direction";
$order_direction='';

$table="  `$subject History Bridge` B left join `History Dimension` H  on (B.`History Key`=H.`History Key`) ";

$sql_totals="select count(Distinct B.`History Key`) as num from $table  $where  ";
$fields="`Type`,`Strikethrough`,`Deletable`,`Subject`,`Author Name`,`History Details`,`History Abstract`,H.`History Key`,`History Date`";
?>
