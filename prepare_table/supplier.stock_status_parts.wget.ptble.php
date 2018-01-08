<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 October 2016 at 22:13:18 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$table
    = " `Part Dimension` P left join `Supplier Part Dimension` SP on (SP.`Supplier Part Part SKU`=P.`Part SKU`) left join `Part Data` PD on (PD.`Part SKU`=P.`Part SKU`) ";

$where = sprintf(
    "where `Supplier Part Supplier Key`=%d and `Part Status` in ('In Use','Discontinuing') and `Supplier Part Status`!='Discontinued' ", $parameters['parent_key']
);


//'Surplus', 'Optimal', 'Low', 'Critical', 'Out_Of_Stock', 'Error'

if ($stock_status == 'Todo') {
    $where .= "and `Part Stock Status` in ('Critical','Out_Of_Stock')";
} else {
    $where .= sprintf("and `Part Stock Status`='%s'", $stock_status);
}


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

//print $sql_totals;

$fields
    .= "
`Part Package Description`,`Supplier Part Reference`,`Supplier Part Status`,`Part Stock Status`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Unit Cost`,`Supplier Part Description`,`Supplier Part Currency Code`,
`Supplier Part Minimum Carton Order`,`Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Part SKU`,`Part Reference`,`Part Current Stock`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Quarter To Day Acc Dispatched`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Reference`,`Part Total Acc Dispatched`,
`Part Days Available Forecast`,`Part 1 Quarter Acc Dispatched`,`Part Next Deliveries Data`



";

//( select Group_CONCAT(concat_ws('|',`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,POTF.`Supplier Delivery Key`,`Supplier Delivery Public ID`,`Purchase Order Quantity`)) from `Purchase Order Transaction Fact` POTF LEFT JOIN `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)  where POTF.`Supplier Part Key`=SP.`Supplier Part Key`  and  POTF.`Supplier Delivery Key` is not null and  `Supplier Delivery Transaction Placed`!='Yes'     ) in_deliveries,
//( select Group_CONCAT(concat_ws('|',POTF.`Purchase Order Key` ,`Purchase Order Public ID`,`Purchase Order Quantity`,POTF.`Purchase Order Transaction State`)) from `Purchase Order Transaction Fact` POTF  LEFT JOIN `Purchase Order Dimension` PO  ON (PO.`Purchase Order Key`=POTF.`Purchase Order Key`)  where POTF.`Supplier Part Key`=SP.`Supplier Part Key`  and  POTF.`Supplier Delivery Key` is null and   POTF.`Purchase Order Transaction State` not in ("Placed","Cancelled")    ) in_purchase_orders


?>
