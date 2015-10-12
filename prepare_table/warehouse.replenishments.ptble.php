<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 16:56:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where=' where `Can Pick`="Yes" and `Minimum Quantity` IS NOT NULL and   `Minimum Quantity`>=(`Quantity On Hand`) and (P.`Part Current On Hand Stock`-`Quantity On Hand`)>=`Minimum Quantity`  ';


switch ($parameters['parent']) {
case('warehouse'):
	$where.=sprintf(' and `Part Location Warehouse Key`=%d',$parameters['parent_key']);
	break;
case('warehouse_area'):
	$where.=sprintf(' and `Part Location Warehouse Area Key`=%d',$parameters['parent_key']);
	break;
case('shelf'):
	$where.=sprintf(' and `Part Location Shelf Key`=%d',$parameters['parent_key']);
	break;
}








$wheref='';
if ($parameters['f_field']=='location' and $f_value!='')
	$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";
if ($parameters['f_field']=='sku' and $f_value!='')
	$wheref.=" and  PL.`Part SKU` like '".addslashes($f_value)."%'";


$_order=$order;
$_dir=$order_direction;



if ($order=='parts')
	$order='`Location Distinct Parts`';
elseif ($order=='max_volumen')
	$order='`Location Max Volume`';
elseif ($order=='max_weight')
	$order='`Location Max Weight`';
elseif ($order=='tipo')
	$order='`Location Mainly Used For`';
elseif ($order=='area')
	$order='`Warehouse Area Code`';
elseif ($order=='warehouse')
	$order='`Warehouse Code`';
else
	$order='`Location File As`';


$table=" `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`) ";


$fields="`Quantity On Hand`,`Minimum Quantity`,`Maximum Quantity`,PL.`Location Key`,`Location Code`,P.`Part Reference`,`Warehouse Flag`,PL.`Part SKU`,IFNULL((select GROUP_CONCAT(L.`Location Key`,':',L.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` L on (L.`Location Key`=PLD.`Location Key`) where PLD.`Part SKU`=P.`Part SKU`),'') as location_data";
$sql_totals="select count(*) as num from $table $where ";



?>
