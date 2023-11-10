<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 nov 2023 6;41pm Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if ($parameters['frequency'] == 'annually') {

    $frequency         = 'Yearly';
    $group_by          = ' group by Year(`Date`) ';
    $sql_totals_fields = 'Year(` Date`)';
} elseif ($parameters['frequency'] == 'quarterly') {
    $frequency = 'Quarterly';

    $group_by          = '  group by DATE_FORMAT(`Timeseries Record Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Timeseries Record Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'monthly') {
    $frequency = 'Monthly';

    $group_by          = '  group by DATE_FORMAT(`Timeseries Record Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Timeseries Record Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'weekly') {
    $frequency = 'Weekly';

    $group_by          = ' group by Yearweek(`Date`,3) ';
    $sql_totals_fields = 'Yearweek(`Date`,3)';
} elseif ($parameters['frequency'] == 'daily') {
    $frequency = 'Daily';


    $sql_totals_fields = '`Invoice Date`';
}

$group_by          = ' ';

$timeseries_key = '';
$sql            = sprintf(
    'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Agent" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`=%s AND  `Timeseries Type`="AgentSales" ',
    $parameters['parent_key'], prepare_mysql($frequency)
);

//print $sql;

if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $timeseries_key = $row['Timeseries Key'];
    } else {
        exit('error, no time series');
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit('error b');
}

//print $sql;

$fields
       = '`Timeseries Record Key`,`Timeseries Record Timeseries Key`, `Timeseries Record Date` as `Date`,`Timeseries Record Float A` as sales ,`Timeseries Record Float D` as purchased_amount ,`Timeseries Record Integer A` as dispatched,`Timeseries Record Integer B` as deliveries,`Timeseries Record Integer C` as supplier_deliveries';
$where = sprintf(
    "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
);
$table = "`Timeseries Record Dimension`  ";


$wheref = '';

$_order = $order;
$_dir   = $order_direction;

if ($order == 'date') {
    $order = '`Timeseries Record Date`';
} else {
    $order = '`Timeseries Record Date`';
}


//$sql_totals="select count(Distinct $sql_totals_fields) as num from $table  $where  ";

$fields = "$fields";


?>
