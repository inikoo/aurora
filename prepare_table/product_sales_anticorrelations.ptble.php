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
    "where `Product A ID`=%d and `Correlation`<0  ", $parameters['parent_key']
);


$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Product Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}



$_dir   = $order_direction;
$_order = $order;

if ($order == 'code') {
    $order = '`Product Code`';
} elseif ($order == 'label') {
    $order = '`Product Label`';
} elseif ($order == 'correlation') {
    $order = 'Correlation';
} elseif ($order == 'status') {
    $order = 'Product Status';
} elseif ($order == 'customers_AB') {
    $order = 'Customers AB';
}elseif ($order == 'customers_A') {
    $order = 'Customers A';
}elseif ($order == 'customers_B') {
    $order = 'Customers B';
} else {
    $order = '`Correlation`';
}

$fields =

    "`Product Web Configuration`,`Product Number of Parts`,`Product Availability`,`Product Web State`,P.`Product ID`,P.`Product Code`,`Product Name`,`Product Store Key`,`Product Status`,`Correlation`,`Customers A`,`Customers B`,`Customers AB`,`Customers All A`,`Customers All B`,`Product Sales Correlation Last Updated`";
$table
    = ' `Product Sales Correlation`    left join  `Product Dimension` P   on (`Product B ID`=P.`Product ID`)   ';

$sql_totals
    = "select count(distinct P.`Product ID`) as num from $table $where";



