<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:26:08 CET, Pisa-Milan (train), Italy

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where = sprintf(
    ' where   ITF.`Delivery Note Key`=%d and `Inventory Transaction Type`!="Adjust"', $parameters['parent_key']
);


$where  = sprintf(
    ' where ITF.`Delivery Note Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and OTF.`Product Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = 'OTF.`Product Code`';
} elseif ($order == 'quantity') {
    $order = '`Order Quantity`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = 'ITF.`Inventory Transaction Key`';
}

$table
    = ' `Inventory Transaction Fact` ITF left join   `Order Transaction Fact`OTF  on (ITF.`Map To Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)   ';

$sql_totals = "select count(*) as num from $table $where";


$fields
    = "
`Order Quantity`,`Order Bonus Quantity`,`Inventory Transaction Quantity`,`Out of Stock`,`Required`,`Part Unit Description`,`Part Reference`,PD.`Part SKU`,
`Not Found`,`No Picked Other`,`Product Code`,`Part UN Number`,`Order Transaction Fact Key`,`Product ID`,`Product Code`,`Order Transaction Amount`,`Order Currency Code`,
`Picked`,`Packed`,`Inventory Transaction Key`


";

//	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
