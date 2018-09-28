<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2018 at 16:33:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Order Basket Purge Store Key`=%d', $parameters['parent_key']
        );
        break;
  
    case('account'):
        $where = sprintf(' where true ');
        break;
    default:
        $where = 'where false';
}



$wheref = '';

$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Order Basket Purge Date`';
} elseif ($order == 'orders') {
    $order = '`Order Basket Purge Purged Orders`';
}elseif ($order == 'transactions') {
    $order = '`Order Basket Purge Purged Transactions`';
} elseif ($order == 'amount') {
    $order = '`Order Basket Purge Purged Amount`';
} elseif ($order == 'state') {
    $order = '`Order Basket Purge State`';
} elseif ($order == 'type') {
    $order = '`Order Basket Purge Type`';
}elseif ($order == 'inactive_days') {
    $order = '`Order Basket Purge Inactive Days`';
}


else {
    $order = '`Order Basket Purge Key`';
}
$table  = '`Order Basket Purge Dimension` C left join `Store Dimension` S on (S.`Store Key`=C.`Order Basket Purge Store Key`) ';
$fields = "`Order Basket Purge Key`,`Order Basket Purge Store Key`,S.`Store Code`,`Store Name`,`Order Basket Purge Date`,`Order Basket Purge State`,`Store Currency Code`,
`Order Basket Purge Estimated Orders`,`Order Basket Purge Estimated Transactions`,`Order Basket Purge Estimated Amount`,
`Order Basket Purge Purged Orders`,`Order Basket Purge Purged Transactions`,`Order Basket Purge Purged Amount`,`Order Basket Purge Type`,`Order Basket Purge Inactive Days`

";


$sql_totals = "select count(*) as num from $table $where ";


?>
