<?php

	switch ($parent) {
	case('warehouse'):
		$where=sprintf(' where  `Location Warehouse Key`=%d',$parent_key);
		break;
	case('warehouse_area'):
		$where=sprintf(' where `Location Warehouse Area Key`=%d',$parent_key);
		break;
	case('shelf'):
		$where=sprintf(' where `Location Shelf Key`=%d',$parent_key);
		break;
	default:
		$where='where false';
	}



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
	} elseif(	$count_elements<7) {
		$where.=' and `Warehouse Flag` in ('.$_elements.')' ;
	}





	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Location Code` like '".addslashes($f_value)."%'";



?>
