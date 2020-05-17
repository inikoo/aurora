<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2017 at 13:27:11 GMT+7 , Bangkok Thailand
 Copyright (c) 2017, Inikoo

 Version 3

*/


$filter_msg = '';
$where      = sprintf("where `Customer Account Balance`!=0  ");


switch ($parameters['parent']) {
    case 'payment_service_provider':

    case 'store':
        $where .= sprintf(
            " and `Customer Store Key`=%d", $parameters['parent_key']
        );
        break;
    case 'account':

        break;

    default:
        $where = "where false";

        break;
}

$group = '';


$wheref = '';
if ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'customer') {
    $order = '`Customer Name`';
} elseif ($order == 'amount') {
    $order = '`Customer Account Balance`';
} elseif ($order == 'customer_id') {
    $order = '`Customer Key`';
} elseif ($order == 'store') {
    $order = '`Store Code`';
} else {
    $order = '`Customer Key`';
}


$table = '`Customer Dimension`  left join `Store Dimension` on (`Customer Store Key`=`Store Key`)';

$sql_totals = "select count(`Customer Key`) as num from $table  $where  ";
$fields     = "`Store Code`,`Store Name`,`Store Key`,`Customer Name`,`Customer Key`,`Customer Account Balance`,`Store Currency Code`";


