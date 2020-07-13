<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17:57:51 MYT Monday, 13 July 2020
 Copyright (c) 2020, Inikoo

 Version 3

*/


$where ='WHERE (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Part Current On Hand Stock External`  AND (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0   and `Part Current On Hand Stock External`>0 ';


$wheref = '';
if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'part') {
    $order = '`Part Reference`';
}  elseif ($order == 'quantity') {
    $order = '`Quantity On Hand`';
} elseif ($order == 'quantity') {
    $order = '`Quantity On Hand`';
} elseif ($order == 'ordered_quantity') {
    $order = 'ordered_quantity';
} elseif ($order == 'effective_stock') {
    $order = 'effective_stock';
}elseif ($order == 'recommended_quantity') {
    $order = '`Minimum Quantity`,`Minimum Quantity`';
} elseif ($order == 'next_deliveries') {
    $order = "(`Part Number Active Deliveries`+`Part Number Draft Deliveries`)";
} else {

    $order = 'P.`Part SKU`';
}




$table = "
    `Part Dimension` P 
     ";


$fields     = " `Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as to_pick,`Part Current On Hand Stock External`, `Part Reference`,   `Part SKU`,`Part Symbol`,`Part Distinct Locations`, P.`Part Current On Hand Stock`,  `Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as ordered_quantity,`Part Current On Hand Stock External`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` as effective_stock,

            IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand`,':',`Location Place` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data,
             `Part Next Deliveries Data`,`Part Units Per Package`,`Part Package Description`
            ";
$sql_totals = "select count(*) as num from $table $where ";



