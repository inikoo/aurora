<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2017 at 14:33:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

switch ($parameters['parent']) {
    case('shipping_zone_schema'):
        $where = sprintf(
            ' where  `Shipping Zone Shipping Zone Schema Key`=%d and `Shipping Zone Active`="Yes" ', $parameters['parent_key']
        );
        break;
    case('store'):
        $where = sprintf(
            ' where  `Shipping Zone Store Key`=%d and `Shipping Zone Active`="Yes"', $parameters['parent_key']
        );
        break;
    case('account'):
        $where = sprintf(' where  `Shipping Zone Active`="Yes" ');
        break;
    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = sprintf(
        ' and `Shipping Zone Code` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Shipping Zone Code`';
} elseif ($order == 'name') {
    $order = '`Shipping Zone Name`';
} elseif ($order == 'customers') {
    $order = '`Shipping Zone Total Acc Customers`';
} elseif ($order == 'orders') {
    $order = '`Shipping Zone Total Acc Orders`';
}elseif ($order == 'amount') {
    $order = '`Shipping Zone Total Acc Amount`';
} elseif ($order == 'from') {
    $order = '`Shipping Zone Creation Date`';
}  elseif ($order == 'active') {
    $order = '`Shipping Zone Active`';
} elseif ($order == 'position') {
    $order = '`Shipping Zone Position`';
} else {
    $order = 'SZ.`Shipping Zone Key`';
}
$table  = '`Shipping Zone Dimension` SZ left join `Shipping Zone Data` D on (D.`Shipping Zone Key`=SZ.`Shipping Zone Key`) left join `Store Dimension` S on (S.`Store Key`=SZ.`Shipping Zone Store Key`) ';
$fields = "`Shipping Zone Price`,`Shipping Zone Territories`,SZ.`Shipping Zone Key`,`Shipping Zone Name`,`Shipping Zone Code`,`Shipping Zone Description`,`Shipping Zone Store Key`,S.`Store Code`,`Store Name`,`Shipping Zone Active`,`Shipping Zone Creation Date`,
`Shipping Zone Number Orders`,`Shipping Zone Number Customers`,`Shipping Zone Amount`,`Shipping Zone First Used`,`Shipping Zone Last Used`,`Store Currency Code`";


$sql_totals = "select count(*) as num from $table $where ";


