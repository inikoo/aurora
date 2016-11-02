<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 27 July 2016 at 15:32:24 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where = " where  `Staff Deleted Type`='Contractor' ";


$wheref = '';
if ($parameters['f_field'] == 'alias' and $f_value != '') {
    $wheref .= " and  `Staff Deleted Alias` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Staff Deleted Name` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'employee_id' and $f_value != '') {
    $wheref .= " and  `Staff Deleted ID` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'alias') {
    $order = '`Staff Deleted Alias`';
} elseif ($order == 'name') {
    $order = '`Staff Deleted Name`';
} elseif ($order == 'employee_id') {
    $order = '`Staff Deleted ID`';
} elseif ($order == 'date') {
    $order = '`Staff Deleted Date`';
} else {
    $order = '`Staff Deleted Key`';
}


$table = '`Staff Deleted Dimension` SD ';

$sql_totals
    = "select count(Distinct SD.`Staff Deleted Key`) as num from $table  $where  ";

$fields
    = "`Staff Deleted Key`,`Staff Deleted Alias`,`Staff Deleted Name`,`Staff Deleted ID`,`Staff Deleted Date`";
?>
