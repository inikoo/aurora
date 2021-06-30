<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 Jun 2021 18:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/

$group_by = '';
$where = sprintf(
    "where `Category Parent Key`=%d  ", $parameters['parent_key']
);



$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Category Label` REGEXP "\\\\b%s" ', addslashes($f_value)
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
       = "  `Category Parent Key`,`Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`
        ";
$table = '`Category Dimension` C   ';

$sql_totals = "select count(distinct `Category Key`) as num from $table $where";



