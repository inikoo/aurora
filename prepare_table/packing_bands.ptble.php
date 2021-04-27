<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2021 at 22:18:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
    case('warehouse'):
        $where = sprintf(
            ' where  `Picking Band Warehouse Key`=%d', $parameters['parent_key']
        );
        break;
  
    case('account'):
        $where = sprintf(' where true ');
        break;
    default:
        $where = 'where false';
}
$where.=' and `Picking Band Type`="Packing"';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Picking Band Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Picking Band Name`';
} elseif ($order == 'parts') {
    $order = '`Picking Band Number Parts`';
} elseif ($order == 'delivery_notes') {
    $order = '`Picking Band Number Delivery Notes`';
}elseif ($order == 'amount') {
    $order = '`Picking Band Amount`';
} else {
    $order = '`Picking Band Key`';
}
$table  = '`Picking Band Dimension`  ';
$fields = "
`Picking Band Amount`,`Picking Band Name`,`Picking Band Number Parts`,`Picking Band Number Delivery Notes`,`Picking Band Key`,`Picking Band Warehouse Key`
";


$sql_totals = "select count(*) as num from $table $where ";


