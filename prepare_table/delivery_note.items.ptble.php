<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:26:08 CET, Pisa-Milan (train), Italy

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$group_by=' group by ITF.`Part SKU` ';
$where = sprintf(
    ' where   ITF.`Delivery Note Key`=%d and `Inventory Transaction Type`!="Adjust"', $parameters['parent_key']
);


$where  = sprintf(
    ' where ITF.`Delivery Note Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'quantity') {
    $order = '`Order Quantity`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} elseif ($order == 'quantity') {
    $order = 'quantity';
} else {
    $order = 'ITF.`Inventory Transaction Key`';
}

$table
    = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  
     ';

$sql_totals = "select count(*) as num from $table $where";


$fields = "`Part Package Description`,`Inventory Transaction Quantity`,`Out of Stock`,
`Required`,`Part Reference`,PD.`Part SKU`,
`Not Found`,`No Picked Other`,`Part UN Number`,`Picked`,`Packed`,`Location Code`,ITF.`Location Key`,`Inventory Transaction Key`,(`Required`+`Given`) as quantity,
`Required`+`Given`+`Inventory Transaction Quantity`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` as pending,
`Required`+`Given`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` as to_pick,`Part SKO Barcode`,`Picking Note`,`Part Main Image Key`,

,PD.`Part Current On Hand Stock`,`Date Picked`,`Date Packed`,`Given`,`Part Distinct Locations`
";




$fields = "`Part Package Description`,sum(`Out of Stock`) as `Out of Stock`,
sum(`Required`) as `Required`,`Part Reference`,PD.`Part SKU`,
`Part UN Number`,
sum(`Picked`) as `Picked`,
sum(`Packed`) as `Packed`,
`Part SKO Barcode`,`Picking Note`,`Part Main Image Key`,

sum(`Given`) as `Given`,`Part Distinct Locations`
";

