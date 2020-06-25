<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 16 December 2015 at 23:48:32 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table
    = '`Staff Dimension` SD left join `Staff Operative Data` B on (B.`Staff Operative Key`=SD.`Staff Key`)  ';
$where
    = ' where `Staff Operative Status`="Worker"   ';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Staff Name` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= sprintf(" and  SD.`Staff Key`=%d ", $f_value);
}
if ($parameters['f_field'] == 'alias' and $f_value != '') {
    $wheref .= " and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`Staff Name`';
} elseif ($order == 'code') {
    $order = '`Staff Alias`';
} elseif ($order == 'payroll_id') {
    $order = '`Staff ID`';
}  elseif ($order == 'po_queued') {
    $order = '`Staff Operative Purchase Orders po_manufacturing`';
} elseif ($order == 'po_queued') {
    $order = '`Staff Operative Purchase Orders Manufacturing`';
} else {
    $order = 'SD.`Staff Key`';
}




$sql_totals
    = "select count(Distinct SD.`Staff Key`) as num from $table  $where  ";

$fields
    = "`Staff ID`,
`Staff Alias`,SD.`Staff Key`,`Staff Name`,`Staff Type`,`Staff Operative Purchase Orders Queued`,`Staff Operative Purchase Orders Manufacturing`	
	
";

