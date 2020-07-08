<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 12:00:00 BST (aprox), Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('warehouse'):
        $where = sprintf(
            ' where  `Location Deleted Warehouse Key`=%d', $parameters['parent_key']
        );
        break;
    case('warehouse_area'):
        $where = sprintf(
            ' where `Location Deleted Warehouse Area Key`=%d', $parameters['parent_key']
        );
        break;
    default:
        exit ('parent not found '.$parameters['parent']);
}

$where .= ' ';


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Location Deleted Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Location Deleted File As`';
} elseif ($order == 'area') {
    $order = '`Warehouse Area Code`';
} elseif ($order == 'date') {
    $order = '`Location Deleted Date`';
} else {
    $order = '`Location Deleted Key`';
}


$table  = '`Location Deleted Dimension` L left join `Warehouse Area Dimension` WAD on (`Location Deleted Warehouse Area Key`=WAD.`Warehouse Area Key`) 
    ';
$fields = "`Location Deleted Key`,`Location Deleted Warehouse Key`,`Location Deleted Warehouse Area Key`,`Location Deleted Code`,`Location Deleted Warehouse Area Code` ,`Warehouse Area Code`,
    `Location Deleted Date`,`Location Deleted Note`,`Warehouse Area Key`
    ";

$sql_totals = "select count(*) as num from $table $where ";

