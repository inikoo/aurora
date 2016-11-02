<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 2 October 2015 at 09:40:42 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where  = '';
$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Report Name` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'section' and $f_value != '') {
    $wheref .= " and  `Report Section Name` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Report Name`';
} elseif ($order == 'section') {
    $order = '`Report Section Name``';
}

$order = 'R.`Report Key`';


$table
    = 'kbase.`Report Dimension` R left join kbase.`Report Section Dimension` S on (R.`Report Section Key`=S.`Report Section Key`) ';

$sql_totals
    = "select count(Distinct R.`Report Key`) as num from $table  $where  ";

$fields
    = "`Report Key`,R.`Report Section Key`,`Report Request`,`Report Section Request`,`Report Name`,`Report Section Name`";

?>
