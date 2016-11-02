<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 September 2016 at 12:19:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('warehouse'):
        $where = sprintf(
            ' where  `Warehouse Area Warehouse Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        exit ('parent not found '.$parameters['parent']);
}


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Warehouse Area Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = sprintf(
        ' and `Warehouse Area Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Warehouse Area Code`';
} elseif ($order == 'parts') {
    $order = '`Warehouse Area Distinct Parts`';
} elseif ($order == 'locations') {
    $order = '`Warehouse Area Number Locations`';
} elseif ($order == 'name') {
    $order = '`Warehouse Area Name`';
} else {
    $order = '`Warehouse Area Key`';
}


$table
    = '`Warehouse Area Dimension` WA  left join `Warehouse Dimension` WD on (`Warehouse Area Warehouse Key`=WD.`Warehouse Key`)';
$fields
    = "`Warehouse Area Key`,`Warehouse Area Warehouse Key`,`Warehouse Area Code`,`Warehouse Area Distinct Parts`,`Warehouse Area Code`,`Warehouse Area Name`,`Warehouse Code`,`Warehouse Area Number Locations`";

$sql_totals = "select count(*) as num from $table $where ";


?>
