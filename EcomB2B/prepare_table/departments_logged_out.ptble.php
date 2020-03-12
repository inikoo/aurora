<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  07 March 2020  12:16::09  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

$group_by = '';


$where = sprintf(
    "where `Category Parent Key`=%d and `Webpage State`='Online' and `Product Category Status`='Active' and `Product Category Public`='Yes' ", $parameters['parent_key']
);

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
} elseif ($order == 'families') {
    $order = '`Category Number Subjects`';
} elseif ($order == 'products') {
    $order = '(`Product Category Active Products`+`Product Category Discontinuing Products`)';
}  else {
    $order = '`Category Label`';
}

$fields
       = " `Webpage URL`,`Category Number No Active Subjects`,`Category Number Active Subjects`,`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,
       (`Product Category Active Products`+`Product Category Discontinuing Products`) as products
      

        ";
$table = '`Category Dimension` C   left join `Product Category Dimension` PCD on (`Category Key`=PCD.`Product Category Key`)  left join `Page Store Dimension` W on (`Page Key`=`Product Category Webpage Key`) ';

$sql_totals = "select count(distinct `Category Key`) as num from $table $where";



