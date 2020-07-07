<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 29 August 2016 at 14:30:37 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';


$sql = sprintf(
    'SELECT `Product Part Part SKU` AS `Part SKU` FROM `Product Part Bridge` WHERE `Product Part Product ID`=%d ', $parameters['parent_key']
);


$where = sprintf(
    ' where `Product Part Product ID`=%d ', $parameters['parent_key']
);


$table
            = "  `Product Part Bridge` B left join  `Part Dimension` P  on (`Product Part Part SKU`=P.`Part SKU`)  left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";
$filter_msg = '';

$filter_msg = '';
$wheref     = '';


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'stock') {
    $order = '`Part Current Stock`';
} elseif ($order == 'stock_status') {
    $order = '`Part Stock Status`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'package_description') {
    $order = '`Part Package Description`';
} elseif ($order == 'available_for') {
    $order = '`Part Days Available Forecast`';

} else {

    $order = 'P.`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields
    = "P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,`Part 1 Quarter Acc Dispatched`,`Product Part Ratio`,`Product Part Note`,`Part On Demand`,`Part Current On Hand Stock`


";



