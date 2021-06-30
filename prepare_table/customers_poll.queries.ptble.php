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
            ' where  `Customer Poll Query Store Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}





$wheref = '';
if ($parameters['f_field'] == 'query' and $f_value != '') {
    $wheref = sprintf(
        ' and `	Customer Poll Query Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'query') {
    $order = '`Customer Poll Query Name`';
}elseif ($order == 'label') {
    $order = '`Customer Poll Query Label`';
}elseif ($order == 'customers' or $order == 'customers_percentage') {
    $order = 'Customer Poll Query Customers';
}  else {
    $order = '`Customer Poll Query Position`';
}


$table  = '`Customer Poll Query Dimension`   ';
$fields = "`Customer Poll Query Store Key`,`Customer Poll Query Key`,`Customer Poll Query Type`,`Customer Poll Query Position`,`Customer Poll Query Name`,`Customer Poll Query Label`,`Customer Poll Query In Profile`,`Customer Poll Query In Registration`,`Customer Poll Query Registration Required`,`Customer Poll Query Customers`,`Customer Poll Query Options` ";
$group_by='';
$sql_totals = "select count(*) as num from $table $where ";



