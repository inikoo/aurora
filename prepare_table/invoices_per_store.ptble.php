<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 December 2018 at 15:20:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$where = " where true   ";


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');
    $where_dates = $where_interval['mysql'];

    $where_interval_1yb = prepare_mysql_dates($from_date_1yb, $to_1yb, '`Invoice Date`');
    $where_dates_1yb = $where_interval_1yb['mysql'];

}









$wheref = '';
if ($parameters['f_field'] == 'store' and $f_value != '') {
    $wheref = sprintf(
        ' and `Store Code` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'store') {
    $order = '`Store Code`';
}elseif ($order == 'invoices') {
    $order = '`Store Invoices`';
}elseif ($order == 'refunds') {
    $order = '`Store Refunds`';
}elseif ($order == 'refund_percentage') {
    $order = '(`Store Refunds`/(`Store Refunds`+`Store Invoices`))';
}else{

    $order='`Store Code`';
}


$group_by = '';

$table = '`Store Dimension` S   left join  `Store Data` SD  on (S.`Store Key`=SD.`Store Key`)    ';

$fields = "
S.`Store Key`,`Store Code`,`Store Name`,`Store Currency Code`,`Store Invoices`,`Store Refunds`, (`Store Refunds`/(`Store Refunds`+`Store Invoices`)) as refund_percentage



";


$sql_totals = "select count(* )  as num from $table  $where  ";

//print $sql_totals;

