<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 15:13:45 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table='`Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)';

	if($parameters['parent']=='company'){
		$where=' where true';
	
	}elseif($parameters['parent']=='department'){
		$where=sprintf(' where `Staff Department Key`=%d',$parameters['parent_key']);
	
	}elseif($parameters['parent']=='area'){

		$where=sprintf(' where `Staff Area Key`=%d',$parameters['parent_key']);
	
	}elseif($parameters['parent']=='position'){
		$table='`Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`) left join `Company Position Staff Bridge` on B (B.`Staff Key`=SD.`Staff Key`)';

		$where=sprintf(' where B.`Position Key`=%d',$parameters['parent_key']);
	
	}



	$wheref='';
	if ($parameters['f_field']=='name' and $f_value!=''  )
		$wheref.=" and  `Staff Name` like '".addslashes($f_value)."%'    ";
	elseif ($parameters['f_field']=='id')
		$wheref.=sprintf(" and  `Staff Key`=%d ",$f_value);
	if ($parameters['f_field']=='alias' and $f_value!=''  )
		$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'    ";

/*

	$_elements='';
	$_number_elements=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
		$_number_elements++;
			if ($_key=='NotWorking') {
				$_elements.=",'No'";
			}
			elseif ($_key=='Working') {
				$_elements.=",'Yes'";
			}

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif($_number_elements<2) {
		$where.=' and `Staff Currently Working` in ('.$_elements.')' ;
	}//
*/


$_order=$order;
$_dir=$order_direction;

if ($order=='name')
		$order='`Staff Name`';
	elseif ($order=='position')
		$order='position';
	elseif ($order=='id')
		$order='`Staff Key`';
	else
		$order='`Staff Key`';



$sql_totals="select count(Distinct SD.`Staff Key`) as num from $table  $where  ";

$fields="
	(select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name`

";
?>
