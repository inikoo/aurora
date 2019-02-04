<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 27 July 2016 at 11:28:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where = " where  true ";


$wheref = '';
if ($parameters['f_field'] == 'alias' and $f_value != '') {
    $wheref .= " and  `User Deleted Alias` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'handle' and $f_value != '') {
    $wheref .= " and  `User Deleted Handle` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'alias') {
    $order = '`User Deleted Alias`';
} elseif ($order == 'handle') {
    $order = '`User Deleted Handle`';
} elseif ($order == 'date') {
    $order = '`User Deleted Date`';
} elseif ($order == 'type') {
    $order = '`User Deleted Type`';
} else {
    $order = '`User Deleted Key`';
}


$table = '`User Deleted Dimension` U ';

$sql_totals
    = "select count(Distinct U.`User Deleted Key`) as num from $table  $where  ";

$fields
    = "`User Deleted Key`,`User Deleted Alias`,`User Deleted Handle`,`User Deleted Type`,`User Deleted Date`";
?>
