<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:11 June 2016 at 17:23:45 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once('class.Category.php');
$category = new Category($parameters['parent_key']);

$period_tag = get_interval_db_name($parameters['f_period']);


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

if ($order == 'code') {
    $order = '`Store Code`';
} else {
    $order = 'S.`Store Key`';
}


$table = '`Store Dimension` S ';

$sql_totals
    = "select count(Distinct S.`Store Key`) as num from $table  $where  ";

$fields = sprintf(
    "
`Store Key`,`Store Code`,`Store Name`,
(select Concat_ws(',',`Category Key`,`Category Label`,`Category Code`) from `Category Dimension` where `Category Scope`='Product' and `Category Code`=%s and `Category Root Key`=`Store Family Category Key` ) as category_data ,
(select `Category Number Subjects` from `Category Dimension` where `Category Scope`='Product' and `Category Code`=%s and `Category Root Key`=`Store Family Category Key` ) as number_products 

",

    prepare_mysql($category->get('Category Code')), prepare_mysql($category->get('Category Code'))
);

?>
