<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 12:45:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
    case('warehouse'):
        $where = sprintf(
            ' where `Shipper Warehouse Key`=%d', $parameters['parent_key']
        );
        break;
    case('account'):
        $where = sprintf(
            ' where true'
        );
        break;

    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Shipper Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = sprintf(
        ' and `Shipper Code` like "%s%%" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Shipper Code`';
} elseif ($order == 'name') {
    $order = '`Shipper Name`';
} elseif ($order == 'status') {
    $order = '`Shipper Status`';
} elseif ($order == 'consignments') {
    $order = '`Shipper Consignments`';
} elseif ($order == 'last_consignment') {
    $order = '`Shipper Last Consignment`';
} elseif ($order == 'parcels') {
    $order = '`Shipper Number Parcels`';
} elseif ($order == 'weight') {
    $order = '`Shipper Dispatched Weight`';
} else {
    $order = '`Shipper Key`';
}
$table  = '`Shipper Dimension` ';
$fields = "`Shipper Key`,`Shipper Code`,`Shipper Name`,`Shipper Status`,`Shipper Consignments`,`Shipper Last Consignment`,`Shipper Number Parcels`,`Shipper Dispatched Weight`,`Shipper Warehouse Key`";


$sql_totals = "select count(*) as num from $table $where ";
//print $sql_totals;

?>
