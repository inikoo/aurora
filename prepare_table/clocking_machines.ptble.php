<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 17:44:32 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$table    = '`Clocking Machine Dimension` CM ';
$group_by = '';


$where='';


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'code') and $f_value != '') {
    $wheref = sprintf(
        ' and `Clocking Machine Code` like "%s%%" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'code') {
    $order = '`Clocking Machine Code`';
} elseif ($order == 'location') {
    $order = '`Clocking Machine Location`';
}elseif ($order == 'since') {
    $order = '`Clocking Machine Creation Date`';
}elseif ($order == 'pending_orders') {
    $order = '`Clocking Machine Pending Orders`';
}elseif ($order == 'invoices') {
    $order = '`Clocking Machine Number Invoices`';
}elseif ($order == 'total_invoiced_amount') {
    $order = '`Clocking Machine Invoiced Amount`';
}elseif ($order == 'last_invoice') {
    $order = '`Clocking Machine Last Invoice Date`';
} else {
    $order = '`Clocking Machine File As`';
}


$sql_totals = "select count(Distinct `Clocking Machine Key`) as num from $table $where";


$fields = '`Clocking Machine Key`,`Clocking Machine Code`,`Clocking Machine Serial Number`';
