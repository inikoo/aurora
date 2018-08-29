<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 August 2018 at 20:20:09 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


include_once 'utils/date_functions.php';
$period_tag = get_interval_db_name($parameters['f_period']);


$where_interval = '';
$wheref         = '';

$group_by = ' group by P.`Product Family Category Key`';
$where    = sprintf(
    ' where P.`Product Type`="Product" and OTF.`Invoice Key`>0 and `Customer Key`=%d', $parameters['parent_key']
);



if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  C.`Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Category Label` like '%".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Category Code`';
} elseif ($order == 'label') {
    $order = '`Category Label`';
} elseif ($order == 'invoices') {
    $order = 'invoices';
} elseif ($order == 'refunds') {
    $order = 'refunds';
} elseif ($order == 'amount') {
    $order = 'amount';
} elseif ($order == 'products') {
    $order = 'count(Distinct P.`Product ID`) ';
}  else {
    $order = 'C.`Category Key`';
}

$table
    = " `Order Transaction Fact` OTF  
    left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) 
    left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`)
     left join `Invoice Dimension` I on (OTF.`Invoice Key`=I.`Invoice Key`)
      left join `Category Dimension` C on (`Product Family Category Key`=`Category Key`)
     ";


$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


//todo remove this after migration
$customer=get_object('Customer',$parameters['parent_key']);
$store=get_object('store',$customer->get('Store Key'));

if($store->get('Store Version')==1){

    $fields
        = "C.`Category Key`,C.`Category Label`,`Store Currency Code`,`Store Code`,S.`Store Key`,`Customer Key`,`Category Code`,
    `Store Name`,
    sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
     sum(if(`Invoice Type`='Refund',1,0)) as refunds,
   sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount,
count(Distinct P.`Product ID`) as products 
";
}else{

    $fields
        = "C.`Category Key`,C.`Category Label`,`Store Currency Code`,`Store Code`,S.`Store Key`,`Customer Key`,`Category Code`,
    `Store Name`,
  
    sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
     sum(if(`Invoice Type`='Refund',1,0)) as refunds,
   sum(`Order Transaction Amount`) as amount ,
     count(Distinct P.`Product ID`) as products 


";
}


$sql
    = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


?>
