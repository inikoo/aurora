<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 23 October 2018 at 17:04:57 GMT+8, Kuala Lumpur Malaysis
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = 'where  `Part Status`="Not In Use"  ';
$table
            = "`Part Dimension` P left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';



if (isset($parameters['f_period'])) {

    $db_period = get_interval_db_name($parameters['f_period']);
    if (in_array(
        $db_period, array(
        'Total',
        '3 Year'
    )
    )) {
        $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";

    } else {
        $yb_fields
            = "`Part $db_period Acc 1YB Dispatched` as dispatched_1yb,`Part $db_period Acc 1YB Invoiced Amount` as sales_1yb,";
    }

} else {
    $db_period = 'Total';
    $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";
}

if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'supplied_by' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Supplied By` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'sku' and $f_value != '') {
    $wheref .= " and  `Part SKU` ='".addslashes($f_value)."'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;



if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'stock') {
    $order = '`Part Current On Hand Stock`';
} elseif ($order == 'dispatched') {
    $order = 'dispatched';
} elseif ($order == 'stock_status' or $order == 'stock_status_label') {
    $order = '`Part Stock Status`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'sko_description') {
    $order = '`Part Package Description`';
} elseif ($order == 'available_for') {
    $order = '`Part Days Available Forecast`';

} elseif ($order == 'sold') {
    $order = ' sold ';
} elseif ($order == 'sales') {
    $order = ' sales ';
} elseif ($order == 'lost') {
    $order = ' lost ';
} elseif ($order == 'bought') {
    $order = ' bought ';
} elseif ($order == 'valid_from') {
    $order = '`Part Valid From`';
} elseif ($order == 'valid_to') {
    $order = '`Part Valid To`';
} elseif ($order == 'active_from') {
    $order = '`Part Active From`';
} elseif ($order == 'last_update') {
    $order = '`Part Last Updated`';
} elseif ($order == 'delta_sales_year0') {
    $order
        = "(-1*(`Part Year To Day Acc Invoiced Amount`-`Part Year To Day Acc 1YB Invoiced Amount`)/`Part Year To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_year1') {
    $order
        = "(-1*(`Part 2 Year Ago Invoiced Amount`-`Part 1 Year Ago Invoiced Amount`)/`Part 2 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year2') {
    $order
        = "(-1*(`Part 3 Year Ago Invoiced Amount`-`Part 2 Year Ago Invoiced Amount`)/`Part 3 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year3') {
    $order
        = "(-1*(`Part 4 Year Ago Invoiced Amount`-`Part 3 Year Ago Invoiced Amount`)/`Part 4 Year Ago Invoiced Amount`)";
} elseif ($order == 'sales_year0') {
    $order = "`Part Year To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_year1') {
    $order = "`Part 1 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year2') {
    $order = "`Part 2 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year3') {
    $order = "`Part 3 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year4') {
    $order = "`Part 4 Year Ago Invoiced Amount`";
} elseif ($order == 'delta_dispatched_year0') {
    $order
        = "(-1*(`Part Year To Day Acc Dispatched`-`Part Year To Day Acc 1YB Dispatched`)/`Part Year To Day Acc 1YB Dispatched`)";
} elseif ($order == 'delta_dispatched_year1') {
    $order
        = "(-1*(`Part 2 Year Ago Dispatched`-`Part 1 Year Ago Dispatched`)/`Part 2 Year Ago Dispatched`)";
} elseif ($order == 'delta_dispatched_year2') {
    $order
        = "(-1*(`Part 3 Year Ago Dispatched`-`Part 2 Year Ago Dispatched`)/`Part 3 Year Ago Dispatched`)";
} elseif ($order == 'delta_dispatched_year3') {
    $order
        = "(-1*(`Part 4 Year Ago Dispatched`-`Part 3 Year Ago Dispatched`)/`Part 4 Year Ago Dispatched`)";
} elseif ($order == 'dispatched_year1') {
    $order = "`Part 1 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year2') {
    $order = "`Part 2 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year3') {
    $order = "`Part 3 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year4') {
    $order = "`Part 4 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year0') {
    $order = "`Part Year To Day Acc Dispatched`";
} elseif ($order == 'has_picture') {
    $order = "`Part Main Image Key`";
} elseif ($order == 'has_stock') {
    $order = "`Part Current On Hand Stock`";
} elseif ($order == 'sales_total') {
    $order = "`Part Total Acc Invoiced Amount`";
} elseif ($order == 'dispatched_total') {
    $order = "`Part Total Acc Dispatched`";
} elseif ($order == 'customer_total') {
    $order = "`Part Total Acc Customers`";
} elseif ($order == 'percentage_repeat_customer_total') {
    $order = "percentage_repeat_customer_total";
} elseif ($order == 'dispatched_per_week') {
    $order = "`Part 1 Quarter Acc Dispatched`";
} elseif ($order == 'weeks_available') {
    $order = "`Part Days Available Forecast`";
} elseif ($order == 'next_deliveries') {
    $order = "(`Part Number Active Deliveries`+`Part Number Draft Deliveries`)";
} else {

    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields
    .= "P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,`Part Current On Hand Stock`,`Part Next Deliveries Data`,`Part Symbol`,
`Part $db_period Acc Dispatched` as dispatched,`Part Number Active Products`,`Part Margin`,
`Part $db_period Acc Invoiced Amount` as sales,
`Part Days Available Forecast`,$yb_fields

`Part 1 Year Ago Dispatched`,`Part 2 Year Ago Dispatched`,`Part 3 Year Ago Dispatched`,`Part 4 Year Ago Dispatched`,`Part 5 Year Ago Dispatched`,
`Part 1 Year Ago Invoiced Amount`,`Part 2 Year Ago Invoiced Amount`,`Part 3 Year Ago Invoiced Amount`,`Part 4 Year Ago Invoiced Amount`,`Part 5 Year Ago Invoiced Amount`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Total Acc Invoiced Amount`,`Part Total Acc Dispatched`,`Part Total Acc Customers`,`Part Total Acc Repeat Customers`,

`Part Year To Day Acc Invoiced Amount`,`Part Year To Day Acc 1YB Profit`,`Part Year To Day Acc Required`,`Part Year To Day Acc Dispatched`,`Part Year To Day Acc 1YB Dispatched`,`Part Year To Day Acc 1YB Invoiced Amount`,
`Part Quarter To Day Acc Invoiced Amount`,`Part Quarter To Day Acc 1YB Profit`,`Part Quarter To Day Acc Required`,`Part Quarter To Day Acc Dispatched`,`Part Quarter To Day Acc 1YB Dispatched`,`Part Quarter To Day Acc 1YB Invoiced Amount`,

`Part 1 Quarter Acc Dispatched`,
`Part Valid From`,`Part Valid From`,`Part Active From`,`Part Main Image Key`,`Part Status`,
if(`Part Total Acc Customers`=0,0,  (`Part Total Acc Repeat Customers`/`Part Total Acc Customers`)) percentage_repeat_customer_total,
`Part Cost`,`Part Cost in Warehouse`,`Part Units Per Package`,`Part Unit Price`,`Part Valid To`

";

?>
