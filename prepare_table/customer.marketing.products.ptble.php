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

$group_by       = '';
$table
                = "`Product Dimension` P left join `Product Data` PD on (PD.`Product ID`=P.`Product ID`) left join `Product DC Data` PDCD on (PDCD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where_interval = '';
$wheref         = '';

$group_by = ' group by OTF.`Product ID`';
$where    = sprintf(
    ' where P.`Product Type`="Product" and OTF.`Invoice Key`>0 and `Customer Key`=%d', $parameters['parent_key']
);



if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  P.`Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Product Name` like '%".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Product Code File As`';
} elseif ($order == 'name') {
    $order = '`Product Name`';
} elseif ($order == 'status') {
    $order = '`Product Status`';
}elseif ($order == 'invoices') {
    $order = 'invoices';
} elseif ($order == 'refunds') {
    $order = 'refunds';
} elseif ($order == 'amount') {
    $order = 'amount';
} elseif ($order == 'qty') {
    $order = 'qty';
}  else {
    $order = 'P.`Product ID`';
}

$table
    = " `Order Transaction Fact` OTF  
    left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) 
    left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`)
     left join `Invoice Dimension` I on (OTF.`Invoice Key`=I.`Invoice Key`)
     ";


$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


//todo remove this after migration
$customer=get_object('Customer',$parameters['parent_key']);
$store=get_object('store',$customer->get('Store Key'));


    $fields
        = "P.`Product ID`,P.`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Code`,S.`Store Key`,`Product RRP`,`Product Unit Label`,`Customer Key`,
    `Store Name`,`Product Web Configuration`,`Product Availability`,`Product Web State`,`Product Cost`,`Product Number of Parts`,P.`Product Status`,`Product Units Per Case`,
  
    sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
     sum(if(`Invoice Type`='Refund',1,0)) as refunds,
   sum(`Order Transaction Amount`) as amount ,
     sum(`Order Quantity`) as qty 

";



$sql
    = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


?>
