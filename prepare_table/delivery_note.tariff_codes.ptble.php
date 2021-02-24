<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5:11 pm Monday, 8 February 2021 (MYT) Time in Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/


$group_by = ' group by `Part Origin Country Code`,PD.`Part Tariff Code` ';
$where    = sprintf(
    ' where   ITF.`Delivery Note Key`=%d and `Inventory Transaction Type`!="Adjust"', $parameters['parent_key']
);


$where  = sprintf(
    ' where ITF.`Delivery Note Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'tariff_code' and $f_value != '') {
    $wheref .= " and `Part Tariff Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'tariff_code') {
    $order = '`Part Tariff Code`';
} elseif ($order == 'weight') {
    $order = 'weight';
} elseif ($order == 'amount') {
    $order = 'amount';
} elseif ($order == 'units_invoiced') {
    $order = 'units_invoiced';
}elseif ($order == 'origin') {
    $order = '`Part Origin Country Code`';
} else {
    $order = '`Part Tariff Code`';
}

$table = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  
     ';

$sql_totals = "select count(distinct `Part Tariff Code`) as num from $table $where";


$fields = "
`Part Tariff Code`,
	GROUP_CONCAT(DISTINCT  CONCAT_WS('|',PD.`Part SKU`,`Part Reference`)) as `references`,
	GROUP_CONCAT(DISTINCT `Part UN Number`) dangerous_goods,
		sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`) as units_invoiced,
		sum(`Amount In`) as amount,		round(sum(`Inventory Transaction Weight`),3) as weight,
		`Part Origin Country Code`
";

