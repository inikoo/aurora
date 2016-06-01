<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 11:33:21 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);



$table='`Webpage Block Bridge` B left join  `Webpage Dimension` P on (`Webpage Block Webpage Key`=`Webpage Key`)';

switch ($parameters['parent']) {
case('page'):
	$where=sprintf(' where  `Webpage Block Webpage Key`=%d  ' , $parameters['parent_key']);
	break;
case('website'):
	$where=sprintf(' where  `Webpage Website Key`=%d  ' , $parameters['parent_key']);
	break;
case('node'):
	$where=sprintf(' where  `Webpage Website Node Key`=%d  ' , $parameters['parent_key']);
	break;	
default:
    exit('parent not configured '.$parameters['parent']);

}

$group='';



$wheref='';
if ($parameters['f_field']=='template'  and $f_value!='')
	$wheref.=" and `Webpage Block Template` like '".addslashes($f_value)."%'";


$_order=$order;
$_dir=$order_direction;


if ($order=='template'){
	$order='`Webpage Block Template`';
}else {
	$order='`Webpage Block Position`';
}


$sql_totals="select count(Distinct B.`Webpage Block Key`) as num from $table  $where  ";

$fields="
`Webpage Block Key`,`Webpage Block Template`,`Webpage Block Position`
";
?>
