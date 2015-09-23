<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/




switch ($parameters['parent']) {

case 'store':
	$where=sprintf("where `Category Parent Key`=0 and `Category Store Key`=%d  ",$parameters['parent_key']);
	switch ($parameters['subject']) {

	case('customer'):
		$where.=sprintf(" and `Category Subject`='Customer'");
		break;
	case('product'):
		$where.=sprintf(" and `Category Subject`='Product' ");
		break;
	case('family'):
		$where.=sprintf(" and `Category Subject`='Family'");
		break;
	case('invoice'):
		$where.=sprintf(" and `Category Subject`='Invoice' ");
		break;
	}
	break;
case 'warehouse':
	$where=sprintf("where `Category Parent Key`=0 and `Category Warehouse Key`=%d  ",$parameters['parent_key']);

	switch ($parameters['subject']) {

	case('part'):
		$where.=sprintf(" and `Category Subject`='Part' ");
		break;
	}
	break;
case '':
	switch ($parameters['subject']) {
	case('supplier'):
		$where.=" and `Category Subject`='Supplier'";
		break;
	}
default:
	exit('error: unknown parent category: '.$parameters['parent']);
}





$filter_msg='';
$wheref='';
if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='label' and $f_value!='')
	$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";



$_dir=$order_direction;
$_order=$order;


if ($order=='code')
	$order='`Category Code`';
elseif ($order=='label')
	$order='`Category Label`';
elseif ($order=='subjects')
	$order='`Category Number Subjects`';
elseif ($order=='subcategories')
	$order='`Category Children`';
elseif ($order=='percentage_assigned')
	$order='`Category Number Subjects`/(`Category Number Subjects`+`Category Subjects Not Assigned`)';			
else
	$order='`Category Key`';


$fields='`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned` ';
$table='`Category Dimension` C';

$sql_totals="select count(distinct `Category Key`) as num from $table $where";





?>
