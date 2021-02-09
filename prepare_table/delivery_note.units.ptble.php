<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18:00 pm Monday, 9 February 2021 (MYT) Time in Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/


$group_by = ' group by PD.`Part SKU` ';
$where    = sprintf(
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

if ($order == 'tariff_code') {
    $order = '`Part Tariff Code`';
} elseif ($order == 'weight') {
    $order = 'weight';
} elseif ($order == 'description') {
    $order = '`Part Recommended Product Unit Name`';
} elseif ($order == 'amount') {
    $order = 'amount';
} elseif ($order == 'units_invoiced') {
    $order = 'units_invoiced';
} elseif ($order == 'countries_of_origin') {
    $order = '`Part Origin Country Code`';
} elseif ($order == 'dangerous_goods') {
    $order = '`Part UN Number`';
} else {
    $order = '`Part Reference`';
}

$table = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  
     ';

$sql_totals = "select count(*) as num from $table $where ";


$fields = "
`Part Tariff Code`,PD.`Part SKU`,`Part Reference`,`Part UN Number`, `Part Origin Country Code`,`Part Recommended Product Unit Name`,
		sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`) as units_invoiced,
		sum(`Amount In`) as amount,		round(sum(`Inventory Transaction Weight`),3) as weight
";

