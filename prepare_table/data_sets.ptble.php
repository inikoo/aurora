<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 9 January 2016 at 11:57:00 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table = '`Data Sets Dimension` DS ';
$where = ' where true';

if (isset($extra_where)) {
    $where .= " $extra_where";
}


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'sets') {
    $order = '`Data Sets Number Sets`';
} elseif ($order == 'items') {
    $order = '`Data Sets Number Items`';
} elseif ($order == 'size') {
    $order = '`Data Sets Size`';
} else {
    $order = '`Data Sets Key`';
}


$sql_totals
    = "select count(Distinct DS.`Data Sets Key`) as num from $table  $where  ";

$fields
    = "`Data Sets Key`,`Data Sets Code`,`Data Sets Number Sets`,`Data Sets Number Items`,`Data Sets Size`";

?>
