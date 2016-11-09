<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 November 2016 at 21:30:55 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$group_by ='';

if ($parameters['frequency'] == 'annually') {

    $frequency         = 'Yearly';
} elseif ($parameters['frequency'] == 'quarterly') {
    $frequency = 'Quarterly';

} elseif ($parameters['frequency'] == 'monthly') {
    $frequency = 'Monthly';

} elseif ($parameters['frequency'] == 'weekly') {
    $frequency = 'Weekly';

} elseif ($parameters['frequency'] == 'daily') {
    $frequency = 'Daily';

}

$timeseries_key = '';
$sql            = sprintf(
    'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Account" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`=%s AND  `Timeseries Type`="AccountSales" ',
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

//print $sql;

$fields = ' `Timeseries Record Date` as Date,`Timeseries Record Float A` as sales ,`Timeseries Record Integer A` as invoices,`Timeseries Record Integer B` as refunds,`Timeseries Record Integer C` as customers';
$where = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);
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
