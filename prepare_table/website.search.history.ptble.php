<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 16 October 2015 at 20:17:22 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/




$where=sprintf(" where  `Site Key`=%d ",$parameters['parent_key']);

$group_by=' ';


$wheref='';
if ($parameters['f_field']=='query' and $f_value!=''  ){
	$wheref.=" and  `Query` like '".addslashes($f_value)."%'    ";
}



$_order=$order;
$_dir=$order_direction;

if ($order=='query')
	$order='`query`';
elseif ($order=='date')
	$order='date';
elseif ($order=='number')
	$order='number';	
elseif ($order=='results')
	$order='results';		
elseif ($order=='users')
	$order='users';			
else
	$order='`User Request Key`';


$table='`Page Store Search Query Dimension` Q  left join `User Dimension` U on (Q.`User Key`=U.`User Key`) ' ;

$sql_totals="select count(*) as num from $table  $where  ";

$fields="`User Alias`,`User Handle`,`Site Key`,Q.`User Key`,`Query`,`Date`,`Number Results`";

$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

//	 print $sql;



?>
