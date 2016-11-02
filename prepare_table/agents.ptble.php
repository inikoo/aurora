<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2016 at 11:42:43 GMT+8, Ubud, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$currency = '';
$where = 'where true';
$table
    = '`Agent Dimension` A left join `Agent Data` D on (D.`Agent Key`=A.`Agent Key`) ';
$group_by = '';
$where_type = '';


$filter_msg = '';
$wheref     = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Agent Code` like '".addslashes($f_value)."%'";
}
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Agent Name` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'low' and is_numeric($f_value)) {
    $wheref .= " and lowstock>=$f_value  ";
} elseif ($parameters['f_field'] == 'outofstock' and is_numeric($f_value)) {
    $wheref .= " and outofstock>=$f_value  ";
}


$db_period = get_interval_db_name($parameters['f_period']);

if (in_array(
    $db_period, array(
    'Total',
    '3 Year'
)
)) {
} else {
    $fields_1yb = "`Agent $db_period Acc 1YB Invoiced Amount` as sales_1y";

}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Agent Code`';
} elseif ($order == 'name') {
    $order = '`Agent Name`';
} elseif ($order == 'location') {
    $order = '`Agent Location`';
} elseif ($order == 'email') {
    $order = '`Agent Main XHTML Email`';
} elseif ($order == 'telephone') {
    $order = '`Agent Preferred Contact Number Formatted Number`';
} elseif ($order == 'contact') {
    $order = "`Agent Main Contact Name`";
} elseif ($order == 'company') {
    $order = "`Agent Company Name`";
} elseif ($order == 'agent_parts') {
    $order = '`Agent Number Parts`';
} elseif ($order == 'sales') {
    $order = "`Agent $db_period Acc Parts Sold Amount`";
} elseif ($order == 'sales_1y') {

    if (in_array(
        $db_period, array(
        'Total',
        '3 Year'
    )
    )) {

        $order = "`Agent $db_period Acc Parts Sold Amount`";

    } else {


        $order
            = "per $order_direction,`Agent $db_period Acc Parts Sold Amount` $order_direction";


        $order_direction = '';

    }
} elseif ($order == 'pending_pos') {
    $order = '`Agent Open Purchase Orders`';
} elseif ($order == 'margin') {
    $order = "`Agent $db_period Acc Parts Margin`";
} elseif ($order == 'cost') {
    $order = "`Agent $db_period Acc Parts Cost`";
} elseif ($order == 'origin') {
    $order = "`Agent Products Origin Country Code`";
} elseif ($order == 'delivery_time') {
    $order = "`Agent Average Delivery Days`";
} elseif ($order == 'low') {
    $order = "`Agent Number Low Parts`";
} elseif ($order == 'surplus') {
    $order = "`Agent Number Surplus Parts`";
} elseif ($order == 'optimal') {
    $order = "`Agent Number Optimal Parts`";
} elseif ($order == 'low') {
    $order = "`Agent Number Low Parts`";
} elseif ($order == 'critical') {
    $order = "`Agent Number Critical Parts`";
} elseif ($order == 'out_of_stock') {
    $order = "`Agent Number Out Of Stock Parts`";
} elseif ($order == 'delta_sales_year0') {
    $order
        = "(-1*(`Agent Year To Day Acc Invoiced Amount`-`Agent Year To Day Acc 1Yb Invoiced Amount`)/`Agent Year To Day Acc 1Yb Invoiced Amount`)";
} elseif ($order == 'delta_sales_year1') {
    $order
        = "(-1*(`Agent 2 Year Ago Invoiced Amount`-`Agent 1 Year Ago Invoiced Amount`)/`Agent 2 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year2') {
    $order
        = "(-1*(`Agent 3 Year Ago Invoiced Amount`-`Agent 2 Year Ago Invoiced Amount`)/`Agent 3 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year3') {
    $order
        = "(-1*(`Agent 4 Year Ago Invoiced Amount`-`Agent 3 Year Ago Invoiced Amount`)/`Agent 4 Year Ago Invoiced Amount`)";
} elseif ($order == 'sales_year1') {
    $order = "`Agent 1 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year2') {
    $order = "`Agent 2 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year3') {
    $order = "`Agent 3 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year4') {
    $order = "`Agent 4 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year0') {
    $order = "`Agent Year To Day Acc Invoiced Amount`";
} elseif ($order == 'delta_sales_quarter0') {
    $order
        = "(-1*(`Agent Quarter To Day Acc Invoiced Amount`-`Agent Quarter To Day Acc 1Yb Invoiced Amount`)/`Agent Quarter To Day Acc 1Yb Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter1') {
    $order
        = "(-1*(`Agent 2 Quarter Ago Invoiced Amount`-`Agent 1 Quarter Ago Invoiced Amount`)/`Agent 2 Quarter Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter2') {
    $order
        = "(-1*(`Agent 3 Quarter Ago Invoiced Amount`-`Agent 2 Quarter Ago Invoiced Amount`)/`Agent 3 Quarter Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter3') {
    $order
        = "(-1*(`Agent 4 Quarter Ago Invoiced Amount`-`Agent 3 Quarter Ago Invoiced Amount`)/`Agent 4 Quarter Ago Invoiced Amount`)";
} elseif ($order == 'sales_quarter1') {
    $order = "`Agent 1 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter2') {
    $order = "`Agent 2 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter3') {
    $order = "`Agent 3 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter4') {
    $order = "`Agent 4 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter0') {
    $order = "`Agent Quarter To Day Acc Invoiced Amount`";
} else {
    $order = "A.`Agent Key`";
}

$sql_totals
    = "select count(Distinct A.`Agent Key`) as num from $table  $where  $where_type";

$fields
    = "
A.`Agent Key`,`Agent Code`,`Agent Name`,
`Agent Location`,`Agent Main Plain Email`,`Agent Preferred Contact Number`,`Agent Preferred Contact Number Formatted Number`,`Agent Main Contact Name`,`Agent Company Name`,
`Agent Number Suppliers`,`Agent Number Parts`,`Agent Number Surplus Parts`,`Agent Number Optimal Parts`,`Agent Number Low Parts`,`Agent Number Critical Parts`,`Agent Number Critical Parts`,`Agent Number Out Of Stock Parts`,
`Agent $db_period Acc Invoiced Amount` as sales,$fields_1yb,
`Agent Year To Day Acc Invoiced Amount`,`Agent Year To Day Acc 1Yb Invoiced Amount`,`Agent 1 Year Ago Invoiced Amount`,`Agent 2 Year Ago Invoiced Amount`,`Agent 3 Year Ago Invoiced Amount`,`Agent 4 Year Ago Invoiced Amount`,`Agent 5 Year Ago Invoiced Amount`,
`Agent Quarter To Day Acc Invoiced Amount`,`Agent Quarter To Day Acc 1Yb Invoiced Amount`,`Agent 1 Quarter Ago Invoiced Amount`,`Agent 2 Quarter Ago Invoiced Amount`,`Agent 3 Quarter Ago Invoiced Amount`,`Agent 4 Quarter Ago Invoiced Amount`,
`Agent 1 Quarter Ago 1YB Invoiced Amount`,`Agent 2 Quarter Ago 1YB Invoiced Amount`,`Agent 3 Quarter Ago 1YB Invoiced Amount`,`Agent 4 Quarter Ago 1YB Invoiced Amount`,
`Agent Year To Day Acc 1YB Invoiced Amount`,`Agent Quarter To Day Acc 1YB Invoiced Amount`

";


?>
