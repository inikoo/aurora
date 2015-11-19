<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 November 2015 at 19:12:32 CET, Venice Airport
 Copyright (c) 2015, Inikoo

 Version 3

*/



if ($parameters['parent']=='company') {
	$where=' where true';

}elseif ($parameters['parent']=='employee') {
	$where=sprintf(' where STD.`Staff Key`=%d', $parameters['parent_key']);

}else {

	$where=' where false';
}



$wheref='';

if ($parameters['f_field']=='name' and $f_value!=''  )
	$wheref.=" and  `Staff Name` like '".addslashes($f_value)."%'    ";
elseif ($parameters['f_field']=='id')
	$wheref.=sprintf(" and  `Staff Key`=%d ", $f_value);
if ($parameters['f_field']=='alias' and $f_value!=''  )
	$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";



$_order=$order;
$_dir=$order_direction;

if ($order=='name')
	$order='`Staff Name`';
elseif ($order=='date')
	$order='`Date`';
elseif ($order=='id')
	$order='`Staff Key`';
else
	$order='STD.`Staff Timesheet Key`';

$table=' `Staff Timesheet Dimension` STD left join  `Staff Dimension` SD on (STD.`Staff Key`=SD.`Staff Key`)  ';


$sql_totals="select count(Distinct STD.`Staff Timesheet Key`) as num from $table  $where  ";
$fields=" `Staff Alias`,STD.`Staff Key`,`Staff Name`,`Date`,STD.`Staff Timesheet Key`  ";
?>
