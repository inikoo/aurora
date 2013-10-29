<?php




	switch ($parent) {
	case('warehouse'):
		$where=sprintf(' where `Part Location Warehouse Key`=%d',$parent_key);
		break;
// For this, new fields have to be added to: part Location Dimension 
//	case('warehouse_area'):
//		$where=sprintf(' where `Part Location Warehouse Area Key`=%d',$parent_key);
//		break;
//	case('shelf'):
//		$where=sprintf(' where `Part Location Shelf Key`=%d',$parent_key);
//		break;
	default:
		$where=' where false';
	}



	




	$wheref='';
	if ($f_field=='location' and $f_value!='')
		$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";
if ($f_field=='sku' and $f_value!='')
		$wheref.=" and  PL.`Part SKU` like '".addslashes($f_value)."%'";
if ($f_field=='reference' and $f_value!='')
		$wheref.=" and  `Part Referece` like '".addslashes($f_value)."%'";



?>
