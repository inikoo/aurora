<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2018 at 11:56:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Shipping Zone Schema Store Key`=%d', $parameters['parent_key']
        );
        break;
  
    case('account'):
        $where = sprintf(' where true ');
        break;
    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Shipping Zone Schema Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'label') {
    $order = '`Shipping Zone Schema Label`';
} elseif ($order == 'customers') {
    $order = '`Shipping Zone Schema Total Acc Submitted Orders Customers`';
} elseif ($order == 'orders') {
    $order = '`Shipping Zone Schema Total Acc Submitted Orders`';
}elseif ($order == 'amount') {
    $order = '`Shipping Zone Schema Total Acc Submitted Orders Amount`';
} elseif ($order == 'from') {
    $order = '`Shipping Zone Schema Creation Date`';
}  elseif ($order == 'type') {
    $order = '`Shipping Zone Schema Type`';
}elseif ($order == 'zones') {
    $order = '`Shipping Zone Schema Number Zones`';
} else {
    $order = 'SZ.`Shipping Zone Schema Key`';
}
$table  = '`Shipping Zone Schema Dimension` SZ left join `Shipping Zone Schema Data` D on (D.`Shipping Zone Schema Key`=SZ.`Shipping Zone Schema Key`) left join `Store Dimension` S on (S.`Store Key`=SZ.`Shipping Zone Schema Store Key`) ';
$fields = " 
`Shipping Zone Schema Number Customers`,`Shipping Zone Schema Number Orders`,`Shipping Zone Schema First Used`,`Shipping Zone Schema Last Used`,
SZ.`Shipping Zone Schema Key`,`Shipping Zone Schema Label`,`Shipping Zone Schema Store Key`,S.`Store Code`,`Store Name`,`Shipping Zone Schema Type`,`Shipping Zone Schema Creation Date`,
`Shipping Zone Schema Total Acc Submitted Orders`,`Shipping Zone Schema Total Acc Submitted Orders Customers`,`Shipping Zone Schema Total Acc Submitted Orders Amount`,`Shipping Zone Schema Number Zones`,`Shipping Zone Schema Amount`,`Store Currency Code`";


$sql_totals = "select count(*) as num from $table $where ";


?>
