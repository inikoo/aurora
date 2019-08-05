<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2016 at 12:56:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by = '';


$where = sprintf(
    "where `Category A Key`=%d  ", $parameters['parent_key']
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
} elseif ($order == 'correlation') {
    $order = 'Correlation';
} elseif ($order == 'status') {
    $order = 'Product Category Status';
} elseif ($order == 'customers_AB') {
    $order = 'customers AB';
}elseif ($order == 'customers_A') {
    $order = 'customers A';
}elseif ($order == 'customers_B') {
    $order = 'customers B';
} else {
    $order = '`Correlation`';
}

$fields =

    "P.`Product Category Key`,C.`Category Code`,`Category Label`,C.`Category Key`,`Category Store Key`,`Product Category Status`,`Correlation`,`Customers A`,`Customers B`,`Customers AB`,`Customers All A`,`Customers All B`,`Product Category Sales Correlation Last Updated`";
$table
    = ' `Product Category Sales Correlation`    left join  `Category Dimension` C   on (`Category B Key`=C.`Category Key`)   left join `Product Category Dimension` P on (P.`Product Category Key`=C.`Category Key`) ';

$sql_totals
    = "select count(distinct C.`Category Key`) as num from $table $where";



