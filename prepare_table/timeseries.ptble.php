<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 11 January 2016 at 09:49:51 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Timeseries Dimension` TS left join `Store Dimension` on (`Timeseries Parent Key`=`Store Key`) ';
$where=' where true';

$wheref='';


$_order=$order;
$_dir=$order_direction;

if ($order=='from')
	$order='`Timeseries From`';
elseif ($order=='to')
	$order='`Timeseries To`';
elseif ($order=='records')
	$order='`Timeseries Number Records`';
elseif ($order=='updated')
	$order='`Timeseries Updated`';
elseif ($order=='type')
	$order='`Timeseries Type`';
else
	$order='`Timeseries Key`';


$sql_totals="select count(Distinct TS.`Timeseries Key`) as num from $table $where  ";

$fields="`Timeseries Key`,`Timeseries Type`,`Store Code`,`Timeseries Parent Key`,`Timeseries Parent`,`Timeseries Number Records`,`Timeseries From`,`Timeseries To`,`Timeseries Updated`";

?>
