<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$group_by = '';
switch ($parameters['parent']) {

    case 'account':
        $where = sprintf("where `Category Branch Type`='Head' ");
        switch ($parameters['subject']) {

            case('customer'):
                $where .= sprintf(" and `Category Scope`='Customer'");
                break;
            case('supplier'):
                $where .= sprintf(" and `Category Scope`='Supplier'");
                break;
            case('product'):
                $where .= sprintf(" and `Category Scope`='Product' ");
                break;
            case('family'):
                $where .= sprintf(" and `Category Scope`='Family'");
                break;
            case('invoice'):
                $where .= sprintf(" and `Category Scope`='Invoice' ");
                break;
            case('part'):
                $where .= sprintf(" and `Category Scope`='Part' ");
                break;
            default:
                $where .= sprintf(" and false ");
                break;
        }
        break;


    case 'store':
        $where = sprintf(
            "where `Category Parent Key`=0 and `Category Store Key`=%d  ", $parameters['parent_key']
        );
        switch ($parameters['subject']) {

            case('customer'):
                $where .= sprintf(" and `Category Scope`='Customer'");
                break;
            case('product'):
                $where .= sprintf(" and `Category Scope`='Product' ");
                break;
            case('family'):
                $where .= sprintf(" and `Category Scope`='Family'");
                break;
            case('invoice'):
                $where .= sprintf(" and `Category Scope`='Invoice' ");
                break;
            default:
                $where .= sprintf(" and false ");
                break;

        }
        break;
    case 'warehouse':
        $where = sprintf(
            "where `Category Parent Key`=0 and `Category Warehouse Key`=%d  ", $parameters['parent_key']
        );

        switch ($parameters['subject']) {

            case('part'):
                $where .= sprintf(" and `Category Scope`='Part' ");
                break;
            default:
                $where .= sprintf(" and false ");
                break;
        }
        break;

    case 'category':
        $where = sprintf(
            "where `Category Parent Key`=%d  ", $parameters['parent_key']
        );


        break;


    default:
        exit('error: unknown parent category: '.$parameters['parent']);
}


$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Category Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}




if (isset($parameters['f_period'])) {

    $db_period = get_interval_db_name($parameters['f_period']);
    if (in_array(
        $db_period, array(
                      'Total',
                      '3 Year'
                  )
    )) {
        $yb_fields = " '' as invoices_1yb,'' as refunds_1yb,'' as sales_1yb,";

    } else {
        $yb_fields
            = "`Invoice Category $db_period Acc 1YB Invoices` as invoices_1yb,`Invoice Category $db_period Acc 1YB Invoiced Refunds` as refunds_1yb,`Invoice Category $db_period Acc 1YB Invoiced Amount` as sales_1yb,";
    }

} else {
    $db_period = 'Total';
    $yb_fields = " '' as invoices_1yb,'' as refunds_1yb,'' as sales_1yb,";



}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Category Code`';
} elseif ($order == 'label') {
    $order = '`Category Label`';
} elseif ($order == 'subjects') {
    $order = '`Category Number Subjects`';
} elseif ($order == 'subjects_active') {
    $order = '`Category Number Active Subjects`';
} elseif ($order == 'subjects_no_active') {
    $order = '`Category Number No Active Subjects`';
} elseif ($order == 'subcategories') {
    $order = '`Category Children`';
} elseif ($order == 'percentage_assigned') {
    $order
        = '`Category Number Subjects`/(`Category Number Subjects`+`Category Subjects Not Assigned`)';
} else {
    $order = '`Category Key`';
}


$fields
       = " $yb_fields `Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,
        `Invoice Category $db_period Acc Invoices` as invoices,
         `Invoice Category $db_period Acc Refunds` as refunds,
                 `Invoice Category $db_period Acc Amount` as sales

        ";
$table = '`Category Dimension` C  left join `Invoice Category Data` on (`Category Key`=`Invoice Category Key`) ';

$sql_totals = "select count(distinct `Category Key`) as num from $table $where";



