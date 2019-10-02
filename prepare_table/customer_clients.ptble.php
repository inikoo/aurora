<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-10-2019 15:34:51 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$table    = '`Customer Client Dimension` CC ';
$group_by = '';




$where=sprintf('where `Customer Client Customer Key`=%d',$parameters['parent_key']);

if (count($user->stores) == 0) {
    $where_stores = sprintf(' and  false');
} else {
    $where_stores = sprintf(
        ' and `Customer Client Store Key` in (%s)  ', join(',', $user->stores)
    );
}
$where .= $where_stores;


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'code') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Client Code` like "%s%%" ', addslashes($f_value)
    );
}elseif (($parameters['f_field'] == 'email') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Client Main Plain Email` like "%s%%" ', addslashes($f_value)
    );
}elseif (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Client Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'code') {
    $order = '`Customer Client Code`';
} elseif ($order == 'location') {
    $order = '`Customer Client Location`';
}elseif ($order == 'since') {
    $order = '`Customer Client Creation Date`';
}elseif ($order == 'pending_orders') {
    $order = '`Customer Client Pending Orders`';
}elseif ($order == 'invoices') {
    $order = '`Customer Client Number Invoices`';
}elseif ($order == 'total_invoiced_amount') {
    $order = '`Customer Client Invoiced Amount`';
}elseif ($order == 'last_invoice') {
    $order = '`Customer Client Last Invoice Date`';
} else {
    $order = '`Customer Client File As`';
}


$sql_totals = "select count(Distinct `Customer Client Key`) as num from $table $where";


$fields = '`Customer Client Key`,`Customer Client Code`,`Customer Client Name`,`Customer Client Creation Date`,`Customer Client Location`,
`Customer Client Customer Key`,`Customer Client Store Key`,`Customer Client Number Invoices`,`Customer Client Invoiced Amount`,`Customer Client Currency Code`,
`Customer Client Pending Orders`,`Customer Client Main Plain Email`,`Customer Client Contact Address Formatted`,
`Customer Client Main XHTML Telephone`,`Customer Client Main XHTML Mobile`,`Customer Client Last Invoice Date`     

';
