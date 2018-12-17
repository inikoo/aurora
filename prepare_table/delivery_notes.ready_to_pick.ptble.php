<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 15:49:02 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

$where    = 'where `Delivery Note State`="Ready to be Picked"';
$table    = '`Delivery Note Dimension` D left join `Store Dimension` on (`Store Key`=`Delivery Note Store Key`) ';
$wheref   = '';
$group_by = '';




$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Delivery Note Date Created`';
} elseif ($order == 'id') {
    $order = '`Delivery Note File As`';
} elseif ($order == 'customer') {
    $order = '`Delivery Note Customer Name`';
} elseif ($order == 'type') {
    $order = '`Delivery Note Type`';
} elseif ($order == 'weight') {
    $order = '`Delivery Note Estimated Weight`';
}elseif ($order == 'parts') {
    $order = '`Delivery Note Number Ordered Parts`';
}elseif ($order == 'store') {
    $order = '`Store Code`';
}else {
    $order = 'D.`Delivery Note Key`';
}




if ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Delivery Note Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Delivery Note ID` like '".addslashes($f_value)."%'";
}

$fields = '`Delivery Note Key`,`Delivery Note Customer Key`,`Delivery Note Type`,`Delivery Note Date Created`,`Delivery Note Estimated Weight`,`Delivery Note Store Key`,`Delivery Note ID`,`Delivery Note Customer Name`,`Store Code`,`Store Name`,`Delivery Note Number Ordered Parts`';
$sql_totals = "select count(Distinct D.`Delivery Note Key`) as num from $table $where ";



?>
