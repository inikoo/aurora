<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 12:14:39 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table = '`Inventory Transaction Fact` TR ';

switch ($parameters['parent']) {
    case 'part':
        $where = sprintf(' where `Part SKU`=%d', $parameters['parent_key']);

        break;
    default:
        exit('parent not configured '.$parameters['parent']);
        break;
}

$where .= " and `Inventory Transaction Type`='Sale' ";


if ($parameters['frequency'] == 'annually') {
    $group_by          = ' group by Year(`Date`) ';
    $sql_totals_fields = 'Year(`Date`)';
} elseif ($parameters['frequency'] == 'monthly') {
    $group_by          = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
    $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
} elseif ($parameters['frequency'] == 'weekly') {
    $group_by          = ' group by Yearweek(`Date`,3) ';
    $sql_totals_fields = 'Yearweek(`Date`,3)';
} elseif ($parameters['frequency'] == 'daily') {
    $group_by          = ' group by Date(`Date`) ';
    $sql_totals_fields = '`Date`';
}elseif ($parameters['frequency'] == 'quarterly') {

    $group_by          = ' group by YEAR(`Date`), QUARTER(`Date`)';
    $sql_totals_fields = 'concat(Year(`Date`),Quarter(`Date`))';


}


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'date') {
    $order = '`Date`';
} else {
    $order = '`Date`';
}


$sql_totals
    = "select count(Distinct $sql_totals_fields) as num from $table  $where  ";





$fields
    = "`Date`,
-1*sum(`Inventory Transaction Quantity`) as skos,
sum(`Amount In`) as sales,
count(distinct `Delivery Note Key`) as deliveries
";


?>
