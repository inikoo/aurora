<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 August 2016 at 18:12:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
case 'account':

	$where=sprintf(" where true ");
	$link='/employee/';

	break;


default:
	exit('parent not suported x '.$parameters['parent']);
	break;
}



$wheref='';
if ($parameters['f_field']=='name' and $f_value!=''  ) {
	$wheref.=" and  `Material Name` like '".addslashes($f_value)."%'    ";
}


$_order=$order;
$_dir=$order_direction;


if ($order=='name')
	$order='`Material Name`';
elseif ($order=='parts')
	$order='`Material Parts Number`';
elseif ($order=='type')
	$order='`Material Type`';
else
	$order='`Material Key`';



$table='  `Material Dimension` M ';


$sql_totals="select count(*) as num from $table  $where  ";

//print $sql_totals;
$fields="
`Material Key`,
`Material Name`,`Material Parts Number`,`Material Type`


";

?>
