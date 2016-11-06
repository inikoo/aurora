<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2016 at 12:56:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by = '';


$where = sprintf("where B.`Category Key`=%d  ", $parameters['parent_key']);


$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Category Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Category Code`';
} elseif ($order == 'label') {
    $order = '`Category Label`';
} elseif ($order == 'subjects') {
    $order = '`Category Number Subjects`';
} elseif ($order == 'subjects_active') {
    $order = '`Category Number Active Subjects`';
} elseif ($order == 'subjects_no_active') {
    $order = '`Category Number No Active Subjects`';
} elseif ($order == 'subcategories') {
    $order = '`Category Children`';
} elseif ($order == 'percentage_assigned') {
    $order
        = '`Category Number Subjects`/(`Category Number Subjects`+`Category Subjects Not Assigned`)';
} else {
    $order = '`Category Key`';
}


$fields
    = 'C.`Category Code`,`Category Label`,C.`Category Key`,`Category Store Key` ';
$table
    = ' `Category Bridge` B left join    `Category Dimension` C   on (B.`Subject Key`=C.`Category Key` and `Subject`="Category")  left join `Product Category Dimension` P on (P.`Product Category Key`=C.`Category Key`)';

$sql_totals
    = "select count(distinct C.`Category Key`) as num from $table $where";


?>
