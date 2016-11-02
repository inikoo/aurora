<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
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
} elseif ($order == 'contacts') {
    $order = '`Store Contacts`';
} elseif ($order == 'active_contacts') {
    $order = 'active';
} elseif ($order == 'new_contacts') {
    $order = '`Store New Contacts`';
} elseif ($order == 'lost_contacts') {
    $order = '`Store Lost Contacts`';
} elseif ($order == 'losing_contacts') {
    $order = '`Store Losing Contacts`';
} elseif ($order == 'contacts_with_orders') {
    $order = '`Store Contacts`';
} elseif ($order == 'active_contacts_with_orders') {
    $order = 'active';
} elseif ($order == 'new_contacts_with_orders') {
    $order = '`Store New Contacts`';
} elseif ($order == 'lost_contacts_with_orders') {
    $order = '`Store Lost Contacts`';
} elseif ($order == 'losing_contacts_with_orders') {
    $order = '`Store Losing Contacts`';
} else {
    $order = '`Store Code`';
}


$table = '`Store Dimension`';
$fields
       = "`Store Key`,`Store Name`,`Store Code`,`Store Contacts`,`Store Total Users`, (`Store Active Contacts`+`Store Losing Contacts`) as active,`Store New Contacts`,`Store Lost Contacts`,`Store Losing Contacts`,`Store Contacts With Orders`,(`Store Active Contacts With Orders`+`Store Losing Contacts With Orders`)as active_with_orders,`Store New Contacts With Orders`,`Store Lost Contacts With Orders`,`Store Losing Contacts With Orders`";

$sql_totals = "select count(*) as num from $table $where ";


?>
