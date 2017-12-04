<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2017 at 14:56:10 GMT+7, Bangkok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/


if (count($user->stores) == 0) {
    $where = "where false";
} else {

    $where = sprintf("where `Store Key` in (%s)", join(',', $user->stores));
}

$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Store Name` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Store Code` like '".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Store Code`';
} elseif ($order == 'name') {
    $order = '`Store Name`';
} elseif ($order == 'payments') {
    $order = '`Store Total Acc Payments`';
} elseif ($order == 'credits') {
    $order = '`Store Total Acc Credits`';
} elseif ($order == 'payments_amount') {
    $order = '`Store Total Acc Payments Amount`';
} elseif ($order == 'credits_amount') {
    $order = '`Store Total Acc Credits Amount`';
} else {
    $order = '`Store Key`';
}


$table = '`Store Dimension`';
$fields
       = "`Store Key`,`Store Name`,`Store Code`,
       `Store Total Acc Payments Amount`,`Store Total Acc Payments`,`Store Total Acc Credits Amount`,`Store Total Acc Credits`



";

$sql_totals = "select count(*) as num from $table $where ";


?>
