<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 October 2016 at 12:13:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$group_by = '';
$table
          = '`Category Dimension` C  left join `Supplier Category Dimension` SC on (`Category key`=SC.`Supplier Category Key`) left join `Supplier Category Data` SCD on (`Category key`=SCD.`Supplier Category Key`) ';

$where = sprintf("where `Category Parent Key`=%d  ", $parameters['parent_key']);


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


$db_period = get_interval_db_name($parameters['f_period']);

if (in_array(
    $db_period, array(
    'Total',
    '3 Year'
)
)) {
} else {
    $fields_1yb
        = "`Supplier Category $db_period Acc 1Yb Invoiced Amount` as sales_1y";

}


$_dir = $order_direction;
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
    = "`Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,
`Supplier Category Number Parts`,`Supplier Category Number Active Parts`,`Supplier Category Number Surplus Parts`,`Supplier Category Number Optimal Parts`,`Supplier Category Number Low Parts`,`Supplier Category Number Critical Parts`,`Supplier Category Number Out Of Stock Parts`,`Supplier Category Number Error Parts`,
`Supplier Category $db_period Acc Invoiced Amount` as sales,$fields_1yb,
`Supplier Category Year To Day Acc Invoiced Amount`,`Supplier Category Year To Day Acc 1Yb Invoiced Amount`,`Supplier Category 1 Year Ago Invoiced Amount`,`Supplier Category 2 Year Ago Invoiced Amount`,`Supplier Category 3 Year Ago Invoiced Amount`,`Supplier Category 4 Year Ago Invoiced Amount`,`Supplier Category 5 Year Ago Invoiced Amount`,
`Supplier Category Quarter To Day Acc Invoiced Amount`,`Supplier Category Quarter To Day Acc 1Yb Invoiced Amount`,`Supplier Category 1 Quarter Ago Invoiced Amount`,`Supplier Category 2 Quarter Ago Invoiced Amount`,`Supplier Category 3 Quarter Ago Invoiced Amount`,`Supplier Category 4 Quarter Ago Invoiced Amount`,
`Supplier Category 1 Quarter Ago 1YB Invoiced Amount`,`Supplier Category 2 Quarter Ago 1YB Invoiced Amount`,`Supplier Category 3 Quarter Ago 1YB Invoiced Amount`,`Supplier Category 4 Quarter Ago 1YB Invoiced Amount`,
`Supplier Category Year To Day Acc 1YB Invoiced Amount`,`Supplier Category Quarter To Day Acc 1YB Invoiced Amount`



 ";

$sql_totals = "select count(distinct `Category Key`) as num from $table $where";

?>
