<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2018 at 15:53:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$period_tag = get_interval_db_name($parameters['f_period']);

/*
if (count($user->stores)==0)
	$where="where false";
else
	$where=sprintf("where S.`Store Key` in (%s)", join(',', $user->stores));
*/
$where = 'where `Category Scope`="Invoice" and `Category Branch Type`="Head" ';

$filter_msg = '';


$group = '';


if (isset($parameters['f_period'])) {

    $db_period = get_interval_db_name($parameters['f_period']);
    if (in_array(
        $db_period, array(
                      'Total',
                      '3 Year'
                  )
    )) {
        $yb_fields = " '' as refunds_1yb,'' as invoices_1yb,'' as invoices_amount_1yb,'' as sales_1yb,'' as refunds_amount_1yb,";

    } else {
        $yb_fields
            = "
            `Invoice Category $db_period Acc 1YB Refunds` as refunds_1yb,
            `Invoice Category DC $db_period Acc 1YB Refunded Amount` as refunds_amount_1yb,
            `Invoice Category $db_period Acc 1YB Invoices` as invoices_1yb,
             `Invoice Category DC $db_period Acc 1YB Amount` as invoices_amount_1yb,
            (`Invoice Category DC $db_period Acc 1YB Amount`-`Invoice Category DC $db_period Acc 1YB Refunded Amount` ) as sales_1yb,";
    }

} else {
    $db_period = 'Total';
    $yb_fields = " '' as refunds_1yb,'' as invoices_1yb,'' as invoices_amount_1yb,'' as sales_1yb,'' as refunds_amount_1yb,";
}

$wheref = '';
if ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        '  and `Category Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Category Code` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Category Code`';
}elseif ($order == 'label') {
    $order = '`Category Label`';
}elseif ($order == 'refunds') {
    $order = 'refunds';
}elseif ($order == 'refunds_1yb') {
    $order = 'refunds_1yb';
}elseif ($order == 'invoices') {
    $order = 'invoices';
}elseif ($order == 'invoices_1yb') {
    $order = 'invoices_1yb';
}elseif ($order == 'sales') {
    $order = 'sales';
}elseif ($order == 'sales_1yb') {
    $order = 'sales_1yb';
}else {
    $order = 'ICS.`Invoice Category Key`';
}


$table
    = '`Invoice Category Dimension` ICS left join `Invoice Category Data` D on (D.`Invoice Category Key`=ICS.`Invoice Category Key`) 
        left join `Invoice Category DC Data` DC on (DC.`Invoice Category Key`=ICS.`Invoice Category Key`) 
        left join `Category Dimension` C on (ICS.`Invoice Category Key`=`Category Key`)  ';

$sql_totals
    = "select count(Distinct ICS.`Invoice Category Key`) as num from $table  $where  ";

$fields = "`Category Key`,`Category Code`,`Category Label`,$yb_fields
`Invoice Category $db_period Acc Refunds` as refunds,
 `Invoice Category DC $db_period Acc Refunded Amount` as refunds_amount,
`Invoice Category $db_period Acc Invoices` as invoices,
 `Invoice Category DC $db_period Acc Amount` as invoices_amount,

(`Invoice Category DC $db_period Acc Amount`-`Invoice Category DC $db_period Acc Refunded Amount` ) as sales 
  ";

?>
