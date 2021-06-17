<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 22:24 MYR , Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
    case('warehouse'):
        $where = sprintf(
            ' where  `Location Warehouse Key`=%d and `Location Fulfilment`="Yes"', $parameters['parent_key']
        );
        break;

    default:
        exit ('parent not found '.$parameters['parent']);
}

$where.=' and `Location Type`!="Unknown"';






$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Location Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Location File As`';
} elseif ($order == 'parts') {
    $order = '`Location Distinct Parts`';
}elseif ($order == 'stock_value') {
    $order = '`Location Stock Value`';
} elseif ($order == 'max_volume') {
    $order = '`Location Max Volume`';
} elseif ($order == 'max_weight') {
    $order = '`Location Max Weight`';
}
//elseif ($order == 'tipo') {
//    $order = '`Location Mainly Used For`';
//}

elseif ($order == 'area') {
    $order = '`Warehouse Area Code`';
} elseif ($order == 'flag') {
    $order = '`Warehouse Flag Key`';
} elseif ($order == 'warehouse') {
    $order = '`Warehouse Code`';
} else {
    $order = '`Location Key`';
}


$table
    = '`Location Dimension` L left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Location Warehouse Key`=WD.`Warehouse Key`) left join `Warehouse Flag Dimension`F  on (F.`Warehouse Flag Key`=L.`Location Warehouse Flag Key`)';
$fields
    = "`Location Place`,`Location Key`,`Warehouse Flag Label`,`Warehouse Flag Color`,`Location Warehouse Key`,`Location Warehouse Area Key`,`Location Code`,`Location Distinct Parts`,`Location Max Volume`,`Location Max Weight`, `Location Mainly Used For`,`Warehouse Area Code`,`Warehouse Flag Key`,`Warehouse Code`,`Location Stock Value`";

$sql_totals = "select count(*) as num from $table $where ";


