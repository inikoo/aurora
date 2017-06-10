<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 2 October 2015 at 09:40:42 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$period_tag = get_interval_db_name($parameters['f_period']);

/*
if (count($user->stores)==0)
	$where="where false";
else
	$where=sprintf("where S.`Store Key` in (%s)", join(',', $user->stores));
*/
$where = 'where true ';

$filter_msg = '';


$group = '';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        '  and `Store Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Store Code` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'families') {
    $order = '`Store Families`';
} elseif ($order == 'departments') {
    $order = '`Store Departments`';
} elseif ($order == 'code') {
    $order = '`Store Code`';
} elseif ($order == 'todo') {
    $order = '`Store In Process Products`';
} elseif ($order == 'discontinued') {
    $order = '`Store In Process Products`';
} elseif ($order == 'profit') {
    $order = '`Store '.$period_tag.' Profit`';

} elseif ($order == 'sales') {
    $order = '`Store '.$period_tag.' Invoiced Amount`';


} elseif ($order == 'name') {
    $order = '`Store Name`';
} elseif ($order == 'active') {
    $order = '`Store For Public Sale Products`';
} elseif ($order == 'outofstock') {
    $order = '`Store Out Of Stock Products`';
} elseif ($order == 'stock_error') {
    $order = '`Store Unknown Stock Products`';
} elseif ($order == 'surplus') {
    $order = '`Store Surplus Availability Products`';
} elseif ($order == 'optimal') {
    $order = '`Store Optimal Availability Products`';
} elseif ($order == 'low') {
    $order = '`Store Low Availability Products`';
} elseif ($order == 'critical') {
    $order = '`Store Critical Availability Products`';
} elseif ($order == 'website') {
    $order = '`Website Code`';
} elseif ($order == 'new') {
    $order = '`Store New Products`';
} else {
    $order = 'S.`Store Key`';
}


$table
    = '`Store Dimension` S left join `Store Data` D on (D.`Store Key`=S.`Store Key`) left join `Store DC Data` DC on DC.`Store Key`=S.`Store Key` left join `Website Dimension` on `Store Website Key`=`Website Key`  ';

$sql_totals
    = "select count(Distinct S.`Store Key`) as num from $table  $where  ";

$fields = "`Store Name`,`Store Code`,S.`Store Key`,`Store New Products`,`Store Active Products`,`Store Suspended Products`,`Store Discontinued Products`,`Store Discontinuing Products`,`Website URL`,`Website Name`,`Website Key`,`Website Code`";

?>
