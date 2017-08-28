<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2017 at 22:40:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$group_by=' group by ITF.`Part SKU` ';

$where = sprintf(
    ' where   ITF.`Delivery Note Key`=%d and `Inventory Transaction Type` not in  ("Adjust","Restock")', $parameters['parent_key']
);


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and OTF.`Product Code` like '".addslashes($f_value)."%'";
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
    = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  LEFT JOIN  `Location Dimension` L ON  (L.`Location Key`=ITF.`Location Key`) 
     LEFT JOIN  `Part Location Dimension` PL ON  (ITF.`Location Key`=PL.`Location Key` and ITF.`Part SKU`=PL.`Part SKU`) 
     ';

$sql_totals = "select count(*) as num from $table $where";



$fields = "`Part Package Description`,`Inventory Transaction Quantity`,`Out of Stock`,`Required`,`Part Unit Description`,`Part Reference`,PD.`Part SKU`,
`Not Found`,`No Picked Other`,`Part UN Number`,`Picked`,`Packed`,`Location Code`,ITF.`Location Key`,`Inventory Transaction Key`,
`Required`+`Given`-`Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` as to_pick,`Part SKO Barcode`,`Picking Note`,`Part Main Image Key`,
`Warehouse Key`,

`Quantity On Hand`,PD.`Part Current On Hand Stock`,`Date Picked`,`Date Packed`
";

//	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
