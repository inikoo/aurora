<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 15:23:14 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table = '`Order Transaction Fact` TR ';

switch ($parameters['parent']) {
    case 'product':
        $where = sprintf(' where `Product ID`=%d', $parameters['parent_key']);

        break;
    default:
        exit('parent not configured');
        break;
}


if ($parameters['frequency'] == 'annually') {
    $group_by          = ' group by Year(`Invoice Date`) ';
    $sql_totals_fields = 'Year(`Invoice Date`)';
} elseif ($parameters['frequency'] == 'monthly') {
    $group_by          = '  group by DATE_FORMAT(`Invoice Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Invoice Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'weekly') {
    $group_by          = ' group by Yearweek(`Invoice Date`) ';
    $sql_totals_fields = 'Yearweek(`Invoice Date`)';
} elseif ($parameters['frequency'] == 'daily') {
    $group_by          = ' group by Date(`Invoice Date`) ';
    $sql_totals_fields = '`Invoice Date`';
}


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'date') {
    $order = '`Invoice Date`';
} else {
    $order = '`Invoice Date`';
}


$sql_totals
    = "select count(Distinct $sql_totals_fields) as num from $table  $where  ";

$fields
    = "`Invoice Date`,
sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as sales,
count(distinct `Invoice Key`) as invoices,
count(distinct `Customer Key`) as customers
";


?>
