<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 13:25:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Charge Store Key`=%d', $parameters['parent_key']
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
        ' and `Charge Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Charge Name`';
} elseif ($order == 'name') {
    $order = '`Charge Description`';
} elseif ($order == 'customers') {
    $order = '`Charge Total Acc Customers`';
} elseif ($order == 'orders') {
    $order = '`Charge Total Acc Orders`';
}elseif ($order == 'amount') {
    $order = '`Charge Total Acc Amount`';
} elseif ($order == 'from') {
    $order = '`Charge Begin Date`';
} elseif ($order == 'to') {
    $order = '`Charge Expiration Date`';
} elseif ($order == 'active') {
    $order = '`Charge Active`';
} else {
    $order = '`Charge Key`';
}
$table  = '`Charge Dimension` C left join `Store Dimension` S on (S.`Store Key`=C.`Charge Store Key`) ';
$fields = "`Charge Key`,`Charge Name`,`Charge Description`,`Charge Store Key`,S.`Store Code`,`Store Name`,`Charge Active`,`Charge Begin Date`,`Charge Expiration Date`,
`Charge Total Acc Orders`,`Charge Total Acc Customers`,`Charge Total Acc Amount`,`Store Currency Code`";


$sql_totals = "select count(*) as num from $table $where ";




?>
