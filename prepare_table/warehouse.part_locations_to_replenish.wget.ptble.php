<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 16:56:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where
    = ' where `Can Pick`="Yes" and `Minimum Quantity`>=0 and   `Minimum Quantity`>=(`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` ) and (P.`Part Current On Hand Stock`-`Quantity On Hand`)>=0  ';


switch ($parameters['parent']) {
    case('warehouse'):
        $where .= sprintf(
            ' and `Part Location Warehouse Key`=%d', $parameters['parent_key']
        );
        break;
    case('warehouse_area'):
        $where .= sprintf(
            ' and `Part Location Warehouse Area Key`=%d', $parameters['parent_key']
        );
        break;
    case('shelf'):
        $where .= sprintf(
            ' and `Part Location Shelf Key`=%d', $parameters['parent_key']
        );
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'location' and $f_value != '') {
    $wheref .= " and  `Location Code` like '".addslashes($f_value)."%'";
}



$_order = $order;
$_dir   = $order_direction;



if ($order == 'part') {
    $order = '`Part Reference`';
}elseif ($order == 'location') {
    $order = '`Location File As`';
}elseif ($order == 'quantity') {
    $order = '`Quantity On Hand`';
} elseif ($order == 'quantity') {
    $order = '`Quantity On Hand`';
} elseif ($order == 'ordered_quantity') {
    $order = 'ordered_quantity';
} elseif ($order == 'effective_stock') {
    $order = 'effective_stock';
} else {

    $order = 'PL.`Part SKU`';
}





$table
    = "
    `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`) left join `Warehouse Flag Dimension` on (`Warehouse Flag Key`=`Location Warehouse Flag Key`)
     ";


$fields
            = " P.`Part Current On Hand Stock`,  `Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as ordered_quantity,`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` as effective_stock,`Location Warehouse Key`,`Quantity On Hand`,`Minimum Quantity`,`Maximum Quantity`,PL.`Location Key`,`Location Code`,P.`Part Reference`,`Warehouse Flag Color`,`Warehouse Flag Key`,`Warehouse Flag Label`,PL.`Part SKU`,
            IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data";
$sql_totals = "select count(*) as num from $table $where ";


?>
