<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2016 at 22:12:12 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

if ($parameters['frequency'] == 'annually') {

    $frequency         = 'Yearly';
    $group_by          = ' group by Year(`Date`) ';
    $sql_totals_fields = 'Year(` Date`)';
} elseif ($parameters['frequency'] == 'quarterly') {
    $frequency = 'Quarterly';

    $group_by          = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'monthly') {
    $frequency = 'Monthly';

    $group_by          = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'weekly') {
    $frequency = 'Weekly';

    $group_by          = ' group by Yearweek(`Date`) ';
    $sql_totals_fields = 'Yearweek(`Date`)';
} elseif ($parameters['frequency'] == 'daily') {
    $frequency = 'Daily';

    $group_by          = ' ';
    $sql_totals_fields = '`Invoice Date`';
}

$timeseries_key = '';
$sql            = sprintf(
    'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Category" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`=%s AND  `Timeseries Type`="ProductCategorySales" ',
    $parameters['parent_key'], prepare_mysql($frequency)
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $timeseries_key = $row['Timeseries Key'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$fields
       = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as sales ,sum(`Timeseries Record Integer A`) as invoices,sum(`Timeseries Record Integer B`) as customers';
$where = sprintf(
    "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
);
$table = "`Timeseries Record Dimension`  ";


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'date') {
    $order = '`Date`';
} else {
    $order = '`Date`';
}


//$sql_totals="select count(Distinct $sql_totals_fields) as num from $table  $where  ";

$fields = "$fields";


?>
