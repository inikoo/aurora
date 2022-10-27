<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 October 2016 at 22:13:18 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent_key']) {
    case 'sk':
        $supplier_key = 355;
        $db_name           = 'sk';
        $title        = "SK";
        break;
    case 'es':
        $supplier_key = 209;
        $db_name           = 'es';
        $title        = "ES";
        break;
    case 'aw':
        $supplier_key = 6737;
        $db_name           = 'dw';
        $title        = "AW";
        break;
}


include_once 'utils/date_functions.php';

$table
    = " $db_name.`Part Dimension` P left join $db_name.`Supplier Part Dimension` SP on (SP.`Supplier Part Part SKU`=P.`Part SKU`) left join $db_name.`Part Data` PD on (PD.`Part SKU`=P.`Part SKU`) ";

$where = sprintf(
    "where `Supplier Part Supplier Key`=%d and `Part Status` in ('In Use','Discontinuing')  ",
    $supplier_key
);


$where .= "and `Part Stock Status` in ('Low','Critical','Out_Of_Stock')   ";


$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'stock') {
    $order = '`Part Current On Hand Stock`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Supplier Part Description`';
} elseif ($order == 'available_forecast') {
    $order = '`Part Days Available Forecast`';
} elseif ($order == 'dispatched_per_week') {
    $order = '`Part 1 Quarter Acc Dispatched`';
} else {
    $order = 'P.`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";


$fields
    .= "
`Part Package Description`,`Supplier Part Reference`,`Supplier Part Status`,`Part Stock Status`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Unit Cost`,`Supplier Part Description`,`Supplier Part Currency Code`,
`Supplier Part Minimum Carton Order`,`Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Part SKU`,`Part Reference`,`Part Current Stock`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Quarter To Day Acc Dispatched`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Reference`,`Part Total Acc Dispatched`,
`Part Days Available Forecast`,`Part 1 Quarter Acc Dispatched`,`Part Next Deliveries Data`,`Part Current Stock In Process`,`Part Current Stock Ordered Paid`,
(select CONCAT_WS(',',`Part SKU`,`Part Current On Hand Stock`) from `Part Dimension` PP where PP.`Part Reference`=P.`Part Reference`) as own_data



";

