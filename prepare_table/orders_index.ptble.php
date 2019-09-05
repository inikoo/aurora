<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($user->can_view('stores') or $user->can_view('accounting')){
    $where = "where true";

}else{
    $where = "where false";
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
} elseif ($order == 'orders') {
    $order = 'orders';
} elseif ($order == 'delivery_notes') {
    $order = 'delivery_notes';
} else {
    $order = 'S.`Store Key`';
}


$table = '`Store Dimension` S left join `Store Data` D on (D.`Store Key`=S.`Store Key`)';
$fields
       = "S.`Store Key`,`Store Name`,`Store Code`,`Store Contacts`,
(`Store Orders In Basket Number`+`Store Orders In Process Not Paid Number`+`Store Orders In Process Paid Number`+`Store Orders In Warehouse Number`+`Store Orders Dispatch Approved Number`+`Store Orders Dispatched Number`+`Store Orders Cancelled Number`) as orders,
(`Store Delivery Notes For Orders`+`Store Delivery Notes For Replacements`+`Store Delivery Notes For Shortages`+`Store Delivery Notes For Samples`+`Store Delivery Notes For Donations`) as delivery_notes,
(`Store Invoices`+`Store Refunds`) as invoices,
(`Store Invoices`+`Store Refunds`) as payments

";

$sql_totals = "select count(*) as num from $table $where ";


?>
