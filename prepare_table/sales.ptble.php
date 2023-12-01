<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2018 at 14:32:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/






//$where_interval_working_hours='';


$where = ' where  `Store Status` in ("Normal","ClosingDown")    ';
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
        ' and `Store Code` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'store') {
    $order = '`Store Code`';
}elseif ($order == 'invoices') {
    $order = 'invoices';
}elseif ($order == 'refunds') {
    $order = 'refunds';
}elseif ($order == 'refund_amount') {
    $order = 'refunds_amount_oc';
}elseif ($order == 'revenue') {
    $order = 'revenue_oc ';
}elseif ($order == 'profit') {
    $order = 'profit_oc';
}elseif ($order == 'customers') {
    $order = ' customers ';
}else{

    $order='`Store Code`';
}


$group_by
    = 'group by `Store Key`';

$table = ' `Invoice Dimension` I  left join `Store Dimension` S on (`Store Key`=`Invoice Store Key`)    ';

$fields = "
`Store Key`,`Store Code`,`Store Name`,`Store Currency Code`,sum(if(`Invoice Type`='Invoice',1,0)) as invoices,sum(if(`Invoice Type`='Refund',1,0)) as refunds,
sum(if(`Invoice Type`='Refund',`Invoice Total Net Amount`* `Invoice Currency Exchange`,0)) refunds_amount_oc,
sum(`Invoice Total Net Amount` * `Invoice Currency Exchange`) revenue_oc,
sum(`Invoice Total Profit` * `Invoice Currency Exchange`) profit_oc,

sum(if(`Invoice Type`='Refund',`Invoice Total Net Amount`,0)) refunds_amount,
sum(`Invoice Total Net Amount` ) revenue,
sum(`Invoice Total Profit` ) profit,

count(distinct `Invoice Customer Key`) customers , `Store Status`



";


$sql_totals = "select count(Distinct `Store Key` )  as num from $table  $where  ";




