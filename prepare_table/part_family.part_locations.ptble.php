<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 00:57:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = sprintf(" where  `Category Key`=%d and `Subject`='Part' and PLD.`Part SKU` is not null  ",$parameters['parent_key']);
$table
            = " `Category Bridge`  left join  `Part Location Dimension` PLD  on (PLD.`Part SKU`=`Subject Key`)     left join `Part Dimension` P on (PLD.`Part SKU`=P.`Part SKU`)  left join `Location Dimension` L on (PLD.`Location Key`=L.`Location Key`) ";
$filter_msg = '';
$wheref     = '';

$fields = '';



if (isset($extra_where)) {
    $where .= $extra_where;
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
       $order = '`Part Reference`';
}elseif ($order == 'sko_description') {
    $order = '`Part Package Description`';
}elseif ($order == 'location') {
    $order = '`Location File As`';
}elseif ($order == 'can_pick') {
    $order = '`Can Pick`';
}elseif ($order == 'sko_cost') {
    $order = '`Part Cost in Warehouse`';
}elseif ($order == 'stock_value') {
    $order = '`Stock Value`';
}elseif ($order == 'quantity') {
    $order = '`Quantity On Hand`';
} else {

    $order = 'P.`Part SKU`';
}


$sql_totals
    = "select count(*) as num from $table  $where  ";

$fields
    .= "
P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,`Part Cost in Warehouse`,
`Location Code`,PLD.`Location Key`,`Part Location Warehouse Key`,
`Quantity On Hand`,`Quantity In Process`,`Stock Value`,`Can Pick`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Last Updated`

";


?>
