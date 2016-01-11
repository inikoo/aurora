<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 11 January 2016 at 10:13:24 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Image Dimension` I ';
$where=' where true';

$wheref='';


$_order=$order;
$_dir=$order_direction;

if ($order=='size')
	$order='`Image Width`*`Image Height`';
elseif ($order=='filesize')
	$order='`Image File Size`';
elseif ($order=='kind')
	$order='`Image File Format`';
elseif ($order=='filename')
	$order='`Image Filename`';
else
	$order='`Image Key`';


$sql_totals="select count(Distinct I.`Image Key`) as num from $table $where  ";

$fields="`Image Key`,`Image Width`,	`Image Height`,`Image File Size`,`Image File Format`,`Image Filename`";

?>
