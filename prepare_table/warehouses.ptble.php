<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 2 October 2015 at 12:42:59 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

/* to delete =====
if (count($user->warehouses)==0)
	$where="where false";
else
	$where=sprintf("where W.`Warehouse Key` in (%s)", join(',', $user->warehouses));
*/

$where='where true';
$filter_msg='';


$group='';



$wheref='';
if ( $parameters['f_field']=='name' and $f_value!='' )
	$wheref.=" and  `Warehouse Name` like '%".addslashes( $f_value )."%'";
elseif ( $parameters['f_field']=='code'  and $f_value!='' )
	$wheref.=" and  `Warehouse Code` like '".addslashes( $f_value )."%'";



$_order=$order;
$_dir=$order_direction;



if ($order=='code')
	$order='`Warehouse Code`';
elseif ($order=='name')
	$order='`Warehouse Name`';
else
    $order='W.`Warehouse Key`';



$table='`Warehouse Dimension` W';

$sql_totals="select count(Distinct W.`Warehouse Key`) as num from $table  $where  ";

$fields="*";

?>
