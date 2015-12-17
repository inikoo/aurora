<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 17 December 2015 at 08:39:25 CET, Barcelona Airpoty , Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Manufacture Task Dimension` MT ';
$where=' where true';

if (isset($extra_where)) {
	$where.=" $extra_where";
}


$wheref='';
if ($parameters['f_field']=='name' and $f_value!=''  )
	$wheref=sprintf(' and  `Manufacture Task Name` REGEXP "[[:<:]]%s" ',addslashes($f_value));



$_order=$order;
$_dir=$order_direction;

if ($order=='name')
	$order='`Manufacture Task Name`';
elseif ($order=='work_cost')
	$order='`Manufacture Task Work Cost`';
else
	$order='`Manufacture Task Key`';


$sql_totals="select count(Distinct MT.`Manufacture Task Key`) as num from $table  $where  ";

$fields="`Manufacture Task Key`,`Manufacture Task Name`,`Manufacture Task Work Cost`";

?>
