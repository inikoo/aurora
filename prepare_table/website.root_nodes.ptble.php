<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 10:54:48 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);



$table='`Website Node Dimension` N';

switch ($parameters['parent']) {

case('website'):
	$where=sprintf(' where  `Website Node Website Key`=%d and `Website Node Type`="Root" ' , $parameters['parent_key']);
	break;
default:
    exit('parent not configured '.$parameters['parent']);

}

$group='';

/*
switch ($elements_type) {
case 'section':


	$_elements='';
	$count_elements=0;
	foreach ($elements_section as $_key=>$_value) {
		if ($_value) {
			$_elements.=',"'.$_key.'"';
			$count_elements++;
		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($count_elements<7) {
		$where.=' and `Page Store Section Type` in ('.$_elements.')' ;
	}


	break;
case 'state':


	$_elements='';
	$count_elements=0;
	foreach ($elements_state as $_key=>$_value) {
		if ($_value) {
			$_elements.=',"'.$_key.'"';
			$count_elements++;
		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($count_elements<2) {
		$where.=' and `Page State` in ('.$_elements.')' ;
	}
	//print count($count_elements);

	break;
case 'flags':


	$_elements='';
	$count_elements=0;
	foreach ($elements_flags as $_key=>$_value) {
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
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<7) {
		$where.=' and `Site Flag` in ('.$_elements.')' ;
	}


	break;

}

*/

$wheref='';
if ($parameters['f_field']=='code'  and $f_value!='')
	$wheref.=" and `Website Node Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='name' and $f_value!='')
	$wheref.=" and  `Website Node Name` like '".addslashes($f_value)."%'";



$_order=$order;
$_dir=$order_direction;


if ($order=='code'){
	$order='`Website Node Code`';
}if ($order=='name'){
	$order='`Website Node Name`';
}else {
	$order='N.`Website Node Key`';
}


$sql_totals="select count(Distinct N.`Website Node Key`) as num from $table  $where  ";

$fields="
`Website Node Key`,`Website Node Code`,`Website Node Name`
";
?>
