<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 February 2016 at 11:45:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$where=sprintf(" where  B.`Category Key`=%d", $parameters['parent_key']);
$table=' `Category Bridge` B left join  `Category Dimension` C on (`Subject Key`=C.`Category Key`) ';





if ($parameters['f_field']=='code' and $f_value!='')
	$wheref.=" and  `Category Code` like '".addslashes($f_value)."%'";
elseif ($parameters['f_field']=='label' and $f_value!='')
	$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";
else
$wheref='';


$_dir=$order_direction;
$_order=$order;


if ($order=='code') {
	$order='`Category Code`';

}elseif ($order=='label') {
	$order='`Category Labels`';
}else {
	$order='C.`Category Key`';
}



$sql_totals="select count(distinct  C.`Category Key`) as num from $table $where";

$fields="*";

$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


//print $sql;

?>
