<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 16:57:13 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/






//$where_interval_working_hours='';


$where = " where `Order State`='Dispatched'   ";


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Order Date`');
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

$table = ' `Order Dimension` O  left join `Store Dimension` S on (`Store Key`=`Order Store Key`)    ';

$fields = "
`Store Key`,`Store Code`,`Store Name`,


sum(`Order Items Net Amount` * `Order Currency Exchange`)  items_net,
sum(`Order Shipping Net Amount` * `Order Currency Exchange`)  shipping_net,
sum(`Order Charges Net Amount` * `Order Currency Exchange`)  charges_net,

sum(`Order Items Cost` )  items_cost,
sum(`Order Shipping Cost` )  shipping_cost,
sum(ifnull(`Order Replacement Cost`,0) )  replacement_cost,



sum(`Order Total Net Amount` * `Order Currency Exchange`)  total_net,

sum(`Order Total Tax Amount` * `Order Currency Exchange`)  tax,


sum(`Order Total Refunds` * `Order Currency Exchange`)  refund_amount,
sum(`Order Total Balance` * `Order Currency Exchange`) revenue,
sum(`Order Profit Amount` * `Order Currency Exchange`) profit



";


$sql_totals = "select count(Distinct `Store Key` )  as num from $table  $where  ";

//print $sql_totals;


?>
