<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 14:15:15 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where  = sprintf(
    ' where POTF.`Purchase Order Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = '`Supplier Part Reference`';
} elseif ($order == 'created') {
    $order = '`Order Date`';
} elseif ($order == 'description') {
    $order = '`Supplier Part Description`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} elseif ($order == 'item_index') {
    $order = '`Purchase Order Item Index`';
} else {
    $order = '`Purchase Order Transaction Fact Key`';
}

$table
    = "
  `Purchase Order Transaction Fact` POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join  `Part Data` PD on (PD.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join `Supplier Dimension` S on (`Supplier Part Supplier Key`=S.`Supplier Key`)

";

$sql_totals
    = "select count(distinct  `Purchase Order Transaction Fact Key`) as num from $table $where";


$fields
    = "
    `Part Main Image Key`,`Part Barcode Number`,`Purchase Order Transaction State`,`Supplier Part Status`,`Purchase Order Ordering Units`,'' as `Supplier Delivery Parent`,'' as `Supplier Delivery Public ID`,
    `Supplier Delivery Units`,`Supplier Delivery Key`,`Purchase Order Item Index`,`Supplier Part Currency Code`,`Supplier Part Historic Unit Cost`,
`Purchase Order Transaction Fact Key`,POTF.`Supplier Part Key`,`Supplier Part Reference`,POTF.`Supplier Part Historic Key`,
`Supplier Part Description`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,POTF.`Purchase Order Key`,
`Supplier Part Unit Cost`,`Part Package Weight`,`Purchase Order CBM`,`Purchase Order Weight`,S.`Supplier Key`,`Supplier Code`,`Supplier Part Minimum Carton Order`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Quarter To Day Acc Dispatched`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Reference`,`Part Total Acc Dispatched`,
`Part Products Web Status`,`Part On Demand`,`Part Days Available Forecast`,`Part Fresh`,P.`Part SKU`,`Part 1 Year Acc Dispatched`,`Part Main Image Key`,`Part Next Deliveries Data`,
`Purchase Order Submitted Units`,
`Part Current Stock In Process`,`Part Current Stock Ordered Paid`


";



