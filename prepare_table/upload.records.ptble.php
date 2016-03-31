<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2016 at 14:59:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
case 'upload':



	$upload =new Upload($parameters['parent_key']);

	switch ($upload->get('Object')) {
	case 'employee':
		$table='  `Upload Record Dimension` as R  left join `Upload File Dimension` F on (F.`Upload File Key`=`Upload Record Upload File Key`)  left join `Staff Dimension` SD on (SD.`Staff Key`=R.`Upload Record Object Key`) ';
		$object_field=' `Staff Alias` as object_name ';
		$where=sprintf(" where  `Upload Record Upload Key`=%d ", $parameters['parent_key']);
		$link='/employee/';
		break;
	default:
		exit('object not suported');
		break;
	}



	break;


default:
	exit('parent not suported');
	break;
}




$wheref='';
if ($parameters['f_field']=='object_name' and $f_value!=''  ) {
	$wheref.=" and  object_name like '".addslashes($f_value)."%'    ";
}


$_order=$order;
$_dir=$order_direction;


if ($order=='row')
	$order='`Upload Record Upload File Key`,`Upload Record Row Index`';
elseif ($order=='status')
	$order='`Upload Record Status`';
elseif ($order=='state')
	$order='`Upload Record State`';
elseif ($order=='date')
	$order='`Upload Record Date`';
elseif ($order=='object_name')
	$order='object_name';
elseif ($order=='msg')
	$order='`Upload Record Message Code`';
else
	$order='`Upload Record Key`';





$sql_totals="select count(*) as num from $table  $where  ";

//print $sql_totals;
$fields=" $object_field,
`Upload Record Key`,
`Upload Record Row Index`,
`Upload File Name`,
`Upload Record Status`,
`Upload Record State`,
`Upload Record Date`,
`Upload Record Message Code`,
`Upload Record Message Metadata`,
`Upload Record Object Key`

";

?>
