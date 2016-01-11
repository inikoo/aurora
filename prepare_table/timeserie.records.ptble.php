<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 11 January 2016 at 13:12:18 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Timeseries Record Dimension` TR ';
$where=sprintf(' where `Timeseries Record Timeseries Key`=%d',$parameters['parent_key']);



$wheref='';


$_order=$order;
$_dir=$order_direction;

if ($order=='date')
	$order='`Timeseries Record Date`';
elseif ($order=='type')
	$order='`Timeseries Record Type`';
elseif ($order=='float_a')
	$order='`Timeseries Record Float A`';
elseif ($order=='float_b')
	$order='`Timeseries Record Float B`';
elseif ($order=='float_c')
	$order='`Timeseries Record Float C`';
elseif ($order=='float_d')
	$order='`Timeseries Record Float D`';
elseif ($order=='int_a')
	$order='`Timeseries Record Integer A`';
elseif ($order=='int_b')
	$order='`Timeseries Record Integer B`';		
	
else
	$order='`Timeseries Record Key`';


$sql_totals="select count(Distinct TR.`Timeseries Record Key`) as num from $table  $where  ";

$fields="`Timeseries Record Type`,`Timeseries Record Date`,`Timeseries Record Float A`,`Timeseries Record Float B`,`Timeseries Record Float C`,`Timeseries Record Float D`,`Timeseries Record Integer A`,`Timeseries Record Integer B`";

?>
