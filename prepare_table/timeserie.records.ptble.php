<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 11 January 2016 at 13:12:18 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Timeseries Record Dimension` TR ';
$where=sprintf(' where `Timeseries Record Timeseries Key`=%d', $parameters['parent_key']);

if ($parameters['frequency']=='annually') {
	$group_by=' group by Year(`Timeseries Record Date`) ';
	$sql_totals_fields='Year(`Timeseries Record Date`)';
}elseif ($parameters['frequency']=='monthy') {
	$group_by='  group by DATE_FORMAT(`Timeseries Record Date`,"%Y-%m") ';
	$sql_totals_fields='DATE_FORMAT(`Timeseries Record Date`,"%Y-%m")';
}elseif ($parameters['frequency']=='weekly') {
	$group_by=' group by Yearweek(`Timeseries Record Date`) ';
	$sql_totals_fields='Yearweek(`Timeseries Record Date`)';
}elseif ($parameters['frequency']=='daily') {
	$group_by='';
	$sql_totals_fields='TR.`Timeseries Record Key`';
}


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


$sql_totals="select count(Distinct $sql_totals_fields) as num from $table  $where  ";
if ($parameters['frequency']=='daily') {
$fields="`Timeseries Record Type`,`Timeseries Record Date`,`Timeseries Record Float A`,`Timeseries Record Float B`,`Timeseries Record Float C`,`Timeseries Record Float D`,`Timeseries Record Integer A`,`Timeseries Record Integer B`";
}else{
$fields="`Timeseries Record Type`,`Timeseries Record Date`,
sum(`Timeseries Record Float A`) as `Timeseries Record Float A`,
sum(`Timeseries Record Float B`) as `Timeseries Record Float B`,
sum(`Timeseries Record Float C`) as `Timeseries Record Float C`,
sum(`Timeseries Record Float D`) as `Timeseries Record Float D`,
sum(`Timeseries Record Integer A`) as `Timeseries Record Integer A`,
sum(`Timeseries Record Integer B`) as `Timeseries Record Integer B`";

}
?>
