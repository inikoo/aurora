<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2017 at 01:41:14 GMT+8, Kuala Lumpur, Malaysia
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
} elseif ($order == 'Store Invoices') {
    $order = '`Store Invoices`';
} elseif ($order == 'Store Refunds') {
    $order = '`Store Refunds`';
} elseif ($order == 'in_basket') {
    $order ='in_basket';
} elseif ($order == 'in_process') {
    $order ='in_process';
} elseif ($order == 'sent') {
    $order ='sent';
} elseif ($order == 'cancelled') {
    $order ='cancelled';
}  else {
    $order = 'S.`Store Key`';
}


$table = '`Store Dimension` S left join `Store Data` D on (D.`Store Key`=S.`Store Key`)';
$fields
       = "S.`Store Key`,`Store Name`,`Store Code`,`Store Contacts`,
            (`Store Orders In Basket Number`) in_basket,

     (`Store Orders In Process Not Paid Number`+`Store Orders In Process Paid Number`+`Store Orders In Warehouse Number`+`Store Orders Dispatch Approved Number`) in_process,
            (`Store Orders Dispatched Number`) sent,
     (`Store Orders Cancelled Number`) cancelled,
       
(`Store Orders In Basket Number`+`Store Orders In Process Not Paid Number`+`Store Orders In Process Paid Number`+`Store Orders In Warehouse Number`+`Store Orders Dispatch Approved Number`+`Store Orders Dispatched Number`+`Store Orders Cancelled Number`) as orders,
`Store Invoices`,`Store Refunds`

";

$sql_totals = "select count(*) as num from $table $where ";


?>
