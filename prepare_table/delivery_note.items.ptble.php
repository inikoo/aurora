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
}elseif ($order == 'packer_bonus') {
    $order = 'packer_bonus_amount';
}elseif ($order == 'picker_bonus') {
    $order = 'picker_bonus_amount';
}elseif ($order == 'cartons_bonus') {
    $order = 'cartons_bonus';
}elseif ($order == 'skos_bonus') {
    $order = 'skos_bonus';
} else {
    $order = 'ITF.`Part SKU`';
}

$table
    = ' `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  
 
     ';

$sql_totals = "select count(*) as num from $table $where";

$table
    .= '
    left join `ITF Picking Band Bridge` picker_bonus on (picker_bonus.`ITF Picking Band ITF Key`=`Inventory Transaction Key` and picker_bonus.`ITF Picking Band Type`="Picking") left join `Picking Band Historic Fact` picker_band on (picker_band.`Picking Band Historic Key`=picker_bonus.`ITF Picking Band Picking Band Historic Key`)
        left join `ITF Picking Band Bridge` packer_bonus on (packer_bonus.`ITF Picking Band ITF Key`=`Inventory Transaction Key` and packer_bonus.`ITF Picking Band Type`="Packing") left join `Picking Band Historic Fact` packer_band on (packer_band.`Picking Band Historic Key`=packer_bonus.`ITF Picking Band Picking Band Historic Key`)

     ';


$fields = "
`Part Package Description`,
sum(`Out of Stock`) as `Out of Stock`,
sum(`Required`) as `Required`,
ANY_VALUE(`Part Reference`) as `Part Reference`,
PD.`Part SKU`,
`Part UN Number`,
sum(`Picked`) as `Picked`,
sum(`Packed`) as `Packed`,
`Part SKO Barcode`,
ANY_VALUE(`Picking Note`) as `Picking Note`,
`Part Main Image Key`,
sum(`Given`) as `Given`,
`Part Distinct Locations`,
picker_bonus.`ITF Picking Band Amount` picker_bonus_amount,
picker_bonus.`ITF Picking Band Picking Band Historic Key` picker_bonus_key,

picker_bonus.`ITF Picking Band Cartons` cartons_bonus,
picker_bonus.`ITF Picking Band SKOs` skos_bonus,

packer_bonus.`ITF Picking Band Amount` packer_bonus_amount,
packer_bonus.`ITF Picking Band Picking Band Historic Key` packer_bonus_key,
picker_band.`Picking Band Historic Name` picker_band,
packer_band.`Picking Band Historic Name` packer_band,
picker_bonus.`ITF Picking Band Picking Band Historic Key` caca

";

