<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2017 at 20:29:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$filter_msg = '';

$where = "where true";
switch ($parameters['parent']) {
    case 'payment_service_provider':
        $where = sprintf(
            "where `Payment Service Provider Key`=%d", $parameters['parent_key']
        );
        break;
    case 'payment_account':
        $where = sprintf(
            "where `Payment Account Key`=%d", $parameters['parent_key']
        );
        break;
    case 'store':
        $where = sprintf(
            "where `Payment Store Key`=%d", $parameters['parent_key']
        );
        break;
    case 'account':
        $where = sprintf("where true");
        break;
    case 'order':
        $where = sprintf(
            "where `Payment Order Key`=%d", $parameters['parent_key']
        );
        break;
    case 'invoice':
        $where = sprintf(
            "where `Payment Invoice Key`=%d", $parameters['parent_key']
        );
        break;
    default:
        $where = "where false";

        break;
}

$group = '';


$wheref = '';
if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  P.`Payment Transaction ID` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'reference') {
    $order = 'P.`Payment Transaction ID`';
} elseif ($order == 'amount') {
    $order = 'P.`Payment Transaction Amount`';
} elseif ($order == 'date') {
    $order = 'P.`Payment Last Updated Date`';
} elseif ($order == 'type') {
    $order = 'P.`Payment Type`';
} elseif ($order == 'status') {
    $order = 'P.`Payment Transaction Status`';
} else {
    $order = 'P.`Payment Key`';
}


$table = '`Payment Dimension` P left join `Payment Account Dimension` PA on (PA.`Payment Account Key`=P.`Payment Account Key`)  left join `Store Dimension` on (`Store Key`=`Payment Store Key`)';

$sql_totals = "select count(P.`Payment Key`) as num from $table  $where  ";
$fields
            = "`Payment Method`,`Payment Order Key`,`Store Key`,`Store Name`,`Store Code`,PA.`Payment Account Code`,PA.`Payment Account Name`,`Payment Account Block`,`Payment Transaction Amount Refunded`,`Payment Transaction Amount Credited`,`Payment Submit Type`,`Payment Key`,`Payment Transaction ID`,`Payment Currency Code`,`Payment Transaction Amount`,P.`Payment Type`,`Payment Last Updated Date`,`Payment Transaction Status`,`Payment Transaction Status Info`";



