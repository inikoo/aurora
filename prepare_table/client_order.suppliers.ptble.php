<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 20:49:12 GMT+8, Sanur , Bli, Indonesia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


$group_by=' group by `Supplier Part Supplier Key` ';

$where  = sprintf(
    ' where POTF.`Purchase Order Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Code` like '".addslashes($f_value)."%'";
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
left join `Purchase Order Dimension` PO on (POTF.`Purchase Order Key`=PO.`Purchase Order Key`)
";

$sql_totals
    = "select count(distinct  S.`Supplier Key`) as num from $table $where";

$fields
    = "S.`Supplier Key`,`Supplier Code`,`Supplier Name`,POTF.`Purchase Order Key`,`Purchase Order Currency Code`,
    sum(`Supplier Part Unit Cost`*`Purchase Order Quantity`*`Part Units Per Package`*`Supplier Part Packages Per Carton`) as amount,
count(distinct P.`Part SKU`) as products

";


?>


