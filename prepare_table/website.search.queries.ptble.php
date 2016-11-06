<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 16 October 2015 at 15:45:50 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where = sprintf(" where  `Site Key`=%d ", $parameters['parent_key']);

$group_by = ' group by `Query`';


$wheref = '';
if ($parameters['f_field'] == 'query' and $f_value != '') {
    $wheref .= " and  `Query` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'query') {
    $order = '`query`';
} elseif ($order == 'date') {
    $order = 'date';
} elseif ($order == 'number') {
    $order = 'number';
} elseif ($order == 'results') {
    $order = 'results';
} elseif ($order == 'users') {
    $order = 'users';
} else {
    $order = '`User Request Key`';
}


$table = '`Page Store Search Query Dimension` Q';

$sql_totals = "select count(Distinct Q.`Query`) as num from $table  $where  ";

$fields
    = "`Site Key`,`User Request Key`,`Query`,max(`Date`) as date,count(*) as number,count(distinct `User Key`) as users,avg( `Number Results`)  results";

$sql
    = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

//	 print $sql;


?>
