<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 12:00:00 BST (aprox), Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
case('warehouse'):
	$where=sprintf(' where  `Location Warehouse Key`=%d',$parameters['parent_key']);
	break;
case('warehouse_area'):
	$where=sprintf(' where `Location Warehouse Area Key`=%d',$parameters['parent_key']);
	break;
case('shelf'):
	$where=sprintf(' where `Location Shelf Key`=%d',$parameters['parent_key']);
	break;
default:
	$where='where false';
}


/*
$_elements='';
$count_elements=0;
foreach ($elements as $_key=>$_value) {
	if ($_value) {
		$count_elements++;
		if ($_key=='Blue') {
			$_elements.=",'Blue'";
		}
		elseif ($_key=='Green') {
			$_elements.=",'Green'";
		}
		elseif ($_key=='Orange') {
			$_elements.=",'Orange'";
		}
		elseif ($_key=='Pink') {
			$_elements.=",'Pink'";
		}
		elseif ($_key=='Purple') {
			$_elements.=",'Purple'";
		}
		elseif ($_key=='Red') {
			$_elements.=",'Red'";
		}
		elseif ($_key=='Yellow') {
			$_elements.=",'Yellow'";
		}
	}
}
$_elements=preg_replace('/^\,/','',$_elements);
if ($_elements=='') {
	$where.=' and false' ;
} elseif ( $count_elements<7) {
	$where.=' and `Warehouse Flag` in ('.$_elements.')' ;
}

*/



$wheref='';
if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";

$_order=$order;
$_dir=$order_direction;


if ($order=='code')
	$order='`Location File As`';
elseif ($order=='parts')
	$order='`Location Distinct Parts`';
elseif ($order=='max_volume')
	$order='`Location Max Volume`';
elseif ($order=='max_weight')
	$order='`Location Max Weight`';
elseif ($order=='tipo')
	$order='`Location Mainly Used For`';
elseif ($order=='area')
	$order='`Warehouse Area Code`';
elseif ($order=='flag')
	$order='`Warehouse Flag Key`';
elseif ($order=='warehouse')
	$order='`Warehouse Code`';
else
	$order='`Location Key`';

$table='`Location Dimension` left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Location Warehouse Key`=WD.`Warehouse Key`) ';
$fields="`Location Key`,`Warehouse Flag`,`Location Warehouse Key`,`Location Warehouse Area Key`,`Location Code`,`Location Distinct Parts`,`Location Max Volume`,`Location Max Weight`, `Location Mainly Used For`,`Warehouse Area Code`,`Warehouse Flag Key`,`Warehouse Code`";

$sql_totals="select count(*) as num from $table $where ";


?>
