<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 January 2018 at 14:49:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Customer Insight Query Store Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}





$wheref = '';
if ($parameters['f_field'] == 'query' and $f_value != '') {
    $wheref = sprintf(
        ' and `	Customer Insight Query Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'query') {
    $order = '`Customer Insight Query Name`';
}elseif ($order == 'label') {
    $order = '`Customer Insight Query Label`';
}elseif ($order == 'customers' or $order == 'customers_percentage') {
    $order = 'Customer Insight Query Customers';
}  else {
    $order = '`Customer Insight Query Position`';
}


$table  = '`Customer Insight Query Dimension`   ';
$fields = "`Customer Insight Query Store Key`,`Customer Insight Query Key`,`Customer Insight Query Type`,`Customer Insight Query Position`,`Customer Insight Query Name`,`Customer Insight Query Label`,`Customer Insight Query In Profile`,`Customer Insight Query In Registration`,`Customer Insight Query Registration Required`,`Customer Insight Query Customers`,`Customer Insight Query Options` ";
$group_by='';
$sql_totals = "select count(*) as num from $table $where ";


?>
