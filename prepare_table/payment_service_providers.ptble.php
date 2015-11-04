<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 18:15:06 CET, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


$filter_msg='';

$where="where true";
$group='';



$wheref='';
if ( $parameters['f_field']=='name' and $f_value!='' )
	$wheref=sprintf('  and  PSP.`Payment Service Provider Name` REGEXP "[[:<:]]%s" ',addslashes($f_value));
elseif ( $parameters['f_field']=='code'  and $f_value!='' )
	$wheref.=" and  PSP.`Payment Service Provider Code` like '".addslashes( $f_value )."%'";



$_order=$order;
$_dir=$order_direction;


if ($order=='code')
	$order='PSP.`Payment Service Provider Code`';
elseif ($order=='name')
	$order='PSP.`Payment Service Provider Name`';
elseif ($order=='transactions')
	$order='PSP.`Payment Service Provider Transactions`';
elseif ($order=='accounts')
	$order='PSP.`Payment Service Provider Accounts`';
elseif ($order=='payments')
	$order='PSP.`Payment Service Provider Payments Amount`';
elseif ($order=='refunds')
	$order='PSP.`Payment Service Provider Refunds Amount`';
elseif ($order=='balance')
	$order='PSP.`Payment Service Provider Balance Amount`';

else
	$order='PSP.`Payment Service Provider Key`';



$table='`Payment Service Provider Dimension` PSP ';

$sql_totals="select count(PSP.`Payment Service Provider Key`) as num from $table  $where  ";
$fields="*";

?>
