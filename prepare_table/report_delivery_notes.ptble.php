<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:12 March 2018 at 11:36:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/






//$where_interval_working_hours='';


$where = " where `Delivery Note State`='Dispatched'   ";


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Delivery Note Date Dispatched`');
    $where .= $where_interval['mysql'];
   // $where_interval_working_hours = prepare_mysql_dates($from, $to, '`Invoice Date`','only dates')['mysql'];

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
}elseif ($order == 'shipments') {
    $order = 'shipments';
}elseif ($order == 'delivery_notes') {
    $order = 'shipments-replacements';
}elseif ($order == 'replacements') {
    $order = 'replacements';
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

$table = ' `Delivery Note Dimension` O  left join `Store Dimension` S on (`Store Key`=`Delivery Note Store Key`)    ';

$fields = "
`Store Key`,`Store Code`,`Store Name`,
count(distinct `Delivery Note Key`) shipments,
sum(if(`Delivery Note Type`='Order' ,0,1)) as replacements,

count(distinct `Delivery Note Customer Key`) customers



";


$sql_totals = "select count(Distinct `Store Key` )  as num from $table  $where  ";

//print $sql_totals;


?>
