<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2018 at 10:49:55 GMT+8 Malaysia Kuala Lumpur
 Copyright (c) 2016, Inikoo

 Version 3

*/

$filter_msg = '';
$wheref     = '';
$group_by   = ' group by `Invoice Customer Key`';

$currency = '';




$where = ' where true';


$table
            = '`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`) left join `Customer Dimension` C on (C.`Customer Key`=I.`Invoice Customer Key`)  ';
$where_type = '';

if ($parameters['parent'] == 'sales_representative') {

    $where = sprintf(
        'where `Invoice Sales Representative Key`=%d  ', $parameters['parent_key']
    );

} else {

    exit("unknown parent ".$parameters['parent']." \n");
}


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
    $where .= $where_interval['mysql'];

}



if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'status') {
    $order = '`Customer Type by Activity`';
} elseif ($order == 'number') {
    $order = '`Invoice File As`';
} elseif ($order == 'formatted_id') {
    $order = '`Customer Key`';
} elseif ($order == 'invoices') {
    $order = 'invoices';
} elseif ($order == 'refunds') {
    $order = 'refunds';
} elseif ($order == 'total_amount') {
    $order = 'total_amount';
} else {
    $order = 'C.`Customer Key`';
}


$fields ='
    `Customer Name`,`Customer Key`,`Customer Store Key`,`Customer Type by Activity`,`Store Currency Code`,
      sum(`Invoice Total Net Amount`) total_amount,
    sum(if(`Invoice Type`="Invoice",1,0)) invoices,sum(if(`Invoice Type`="Refund",1,0)) refunds,
    sum(if(`Invoice Type`="Invoice",`Invoice Total Net Amount`,0)) invoiced_amount,sum(if(`Invoice Type`="Refund",`Invoice Total Net Amount`,0)) refunded_amount
';

$sql_totals
    = "select count(Distinct I.`Invoice Customer Key`) as num from $table $where ";


?>
