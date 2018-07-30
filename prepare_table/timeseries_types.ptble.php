<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2018 at 22:15:22 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$group_by=' group by `Timeseries Type` ';

$table
       = '`Timeseries Dimension` TS  ';
$where = ' where true';

$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'from') {
    $order = '`Timeseries From`';
} elseif ($order == 'to') {
    $order = '`Timeseries To`';
} elseif ($order == 'records') {
    $order = 'records';
} elseif ($order == 'updated') {
    $order = '`Timeseries Updated`';
} elseif ($order == 'type') {
    $order = '`Timeseries Type`';
}elseif ($order == 'timeseries') {
    $order = 'timeseries';
} else {
    $order = '`Timeseries Key`';
}


$sql_totals
    = "select count(Distinct TS.`Timeseries Type`) as num from $table $where  ";

$fields
    = "`Timeseries Key`,`Timeseries Type`,`Timeseries Parent Key`,`Timeseries Parent`,count(*) as timeseries,sum(`Timeseries Number Records`) as records,`Timeseries From`,`Timeseries To`,`Timeseries Updated`";

?>
