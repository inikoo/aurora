<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2017 at 14:23:12 CEST, Tranava, Slovakia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$group_by='  ';

$where  = sprintf(
    ' where ITF.`Delivery Note Key`=%d', $parameters['parent_key']
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
    = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  LEFT JOIN  `Part Location Dimension` PL ON  (ITF.`Location Key`=PL.`Location Key` and ITF.`Part SKU`=PL.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)
    left join `Order Transaction Fact` on (`Order Transaction Fact Key`= `Map To Order Transaction Fact Key`)   ';

$sql_totals = "select count(*) as num from $table $where";


$fields = " `Part Symbol`, PL.`Location Key` as pl_ok  ,`Part UN Number`,`Part Package Description`,`Part Reference`,PD.`Part SKU`,`Part Distinct Locations`,`Required`+`Given` as required,L.`Location Key`,`Location Code`,`Quantity on Hand`,`Quantity On Hand`,`Part Current On Hand Stock`,`Date Picked`,`Picked`,
 `Out of Stock`-`No Authorized`-`Not Found`-`No Picked Other` as cant_pick ,`Part SKO Barcode`,`Part Distinct Locations` ,`Inventory Transaction Key`,
 `Part Main Image Key`,`Picking Note`,`Order Transaction Amount`,`Order Currency Code`
 ";

//	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
