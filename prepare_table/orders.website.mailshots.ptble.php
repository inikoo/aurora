<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2017 at 18:45:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Email Campaign Store Key`=%d', $parameters['parent_key']
        );
        break;
  
    case('account'):
        $where = sprintf(' where true ');
        break;
    default:
        $where = 'where false';
}

$where.=' and `Email Campaign Type`="AbandonedCart"';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Email Campaign Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Email Campaign Description`';
} elseif ($order == 'customers') {
    $order = '`Email Campaign Total Acc Customers`';
} elseif ($order == 'orders') {
    $order = '`Email Campaign Total Acc Orders`';
}elseif ($order == 'amount') {
    $order = '`Email Campaign Total Acc Amount`';
} elseif ($order == 'from') {
    $order = '`Email Campaign Begin Date`';
} elseif ($order == 'to') {
    $order = '`Email Campaign Expiration Date`';
} elseif ($order == 'active') {
    $order = '`Email Campaign Active`';
} else {
    $order = '`Email Campaign Key`';
}
$table  = '`Email Campaign Dimension` C left join `Store Dimension` S on (S.`Store Key`=C.`Email Campaign Store Key`) ';
$fields = "`Email Campaign Key`,`Email Campaign Name`,`Email Campaign Store Key`,S.`Store Code`,`Store Name`,`Email Campaign Last Updated Date`,`Email Campaign State`,`Email Campaign Number Estimated Emails`,`Email Campaign Store Key`";


$sql_totals = "select count(*) as num from $table $where ";


?>
