<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 August 2018 at 21:13:20 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

$currency = '';
$where    = 'where true';
$table    = '`Customer Dimension` C ';
$group_by = ' group by OTF.`Customer Key` ';


//print_r($parameters);

if ($parameters['parent'] == 'product') {
    $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) left join `Invoice Dimension` I on (OTF.`Invoice Key`=I.`Invoice Key`) 
    left join `Store Dimension` S on (S.`Store Key`=OTF.`Store Key`)
    ';

    $where = sprintf(' where  `Product ID`=%d ', $parameters['parent_key']);


}


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );


}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'formatted_id') {
    $order = 'C.`Customer Key`';
} elseif ($order == 'location') {
    $order = '`Customer Location`';
}elseif ($order == 'invoices') {
    $order = 'invoices';
}elseif ($order == 'orders') {
    $order = 'orders';
}elseif ($order == 'amount') {
    $order = 'amount';
}elseif ($order == 'qty') {
    $order = 'qty';
} else {
    $order = '`Customer File As`';
}


$sql_totals = "select count(Distinct C.`Customer Key`) as num from $table  $where ";




include_once 'utils/object_functions.php';
$product=get_object('Product',$parameters['parent_key']);
$store=get_object('store',$product->get('Store Key'));



    $fields='C.`Customer Key`,`Customer Name`,`Customer Location`,`Customer Type by Activity`,`Customer Store Key`,count(Distinct `Order Key`) as orders,`Store Currency Code`,
sum(if(`Invoice Type`="Invoice",1,0)) as invoices,sum(if(`Invoice Type`="Refund",1,0)) as refunds,
   sum(if(`Invoice Type`="Invoice",`Order Transaction Amount`,0)) as amount ,
     sum(`Delivery Note Quantity`) as qty 
  
  ';

