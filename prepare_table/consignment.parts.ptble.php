<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3:20 pm Sunday, 10 January 2021 (MYT) Time in Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

 Version 2.0
*/


$group_by = ' group by ITF.`Part SKU` ';
$where    = sprintf(
    ' where   `Delivery Note Consignment Key`=%d   AND `Delivery Note State` != "Cancelled" and `Inventory Transaction Quantity`<0 ', $parameters['parent_key']
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
} elseif ($order == 'quantity_units') {
    $order = 'quantity_units';
} elseif ($order == 'weight') {
    $order = 'weight';
} elseif ($order == 'origin') {
    $order = '`Part Origin Country Code`';
} elseif ($order == 'tariff_code') {
    $order = '`Part Tariff Code`';
} elseif ($order == 'description') {
    $order = '`Part Recommended Product Unit Name`';
} elseif ($order == 'invoiced_amount') {
    $order = 'invoiced_amount';
} else {
    $order = 'ITF.`Part SKU`';
}
$table = ' `Inventory Transaction Fact` ITF LEFT JOIN `Delivery Note Dimension` DN ON (DN. `Delivery Note Key` = ITF. `Delivery Note Key`)  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  
     ';

$sql_totals = "select count(*) as num from $table $where";


$fields = "
`Part Tariff Code`,
`Part Recommended Product Unit Name`,
	sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`) as quantity_units,

ANY_VALUE(`Part Reference`) as `Part Reference`,
PD.`Part SKU`,
`Part UN Number`,
round(sum(`Inventory Transaction Weight`),3) as weight,
`Part Origin Country Code`, 
sum(`Amount In`) as invoiced_amount

";

