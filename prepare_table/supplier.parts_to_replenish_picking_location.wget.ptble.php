<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 June 2017 at 19:38:51 GMT+8, KLIA2, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/





include_once 'utils/date_functions.php';




$where      = "where true  ";
$table
            = "  `Part Location Dimension` PLD  left join 
            `Part Dimension` P on (PLD.`Part SKU`=P.`Part SKU`) left join `Location Dimension` L on (PLD.`Location Key`=L.`Location Key`) 
            
            LEFT JOIN `Supplier Part Dimension` SP ON (SP.`Supplier Part Part SKU`=P.`Part SKU`)
            
            ";



$filter_msg = '';
$wheref     = '';

$fields = '';


if ($parameters['parent'] == 'supplier') {
    $where = sprintf(
        "where `Supplier Part Supplier Key`=%d   and `Can Pick`='Yes' and (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>`Quantity On Hand`  and (`Part Current Stock In Process`+ `Part Current Stock Ordered Paid`)>0  ", $parameters['parent_key']
    );



} else {
    exit("parent not found ".$parameters['parent']);
}


if (isset($extra_where)) {
    $where .= $extra_where;
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'total_stock') {
    $order = '`Part Current Stock`';
}elseif ($order == 'quantity_in_picking') {
    $order = '`Quantity on hand`';
}elseif ($order == 'to_pick') {
    $order = 'to_pick';
} elseif ($order == 'location') {
    $order = '`Location File As`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} else {

    $order = '`Part SKU`';
}


$sql_totals = "select count(DISTINCT PLD.`Part SKU`) as num from $table  $where  ";

//print $sql_totals;


$fields
    .= "
P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,`Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as to_pick,
`Location Code`,PLD.`Location Key`,`Part Location Warehouse Key`,`Part Package Description`,
`Quantity On Hand`,`Quantity In Process`,`Stock Value`,`Can Pick`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Last Updated`,
(select Group_CONCAT(LL.`Location Code`) from `Part Location Dimension` PL_SL left join `Location Dimension` LL on (LL.`Location Key`=PL_SL.`Location Key`) where PL_SL.`Part SKU`=PLD.`Part SKU` and PL_SL.`Can Pick`='No'  ) as storing_locations

";


?>
