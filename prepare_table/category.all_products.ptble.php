<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 May 2016 at 22:19:15 CEST, Mijas Costa, Spain

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

$group_by       = '';
$table
                = "`Product Dimension` P left join `Store Dimension` S on (`Product Store Key`=`Store Key`)   left join `Category Dimension` F on (`Product Family Category Key`=`Category Key`)   ";
$where_interval = '';
$wheref         = '';


switch ($parameters['parent']) {

    case('category'):
        include_once 'class.Category.php';
        $category = new Category($parameters['parent_key']);

        if (!in_array($category->get('Category Store Key'), $user->stores)) {
            return;
        }
        $associated_field = sprintf(
            "(select `Category Key` from `Category Bridge` C  where C.`Category Key`=%d and `Subject Key`=P.`Product ID` ) as associated, ", $parameters['parent_key']
        );
        $where            = sprintf(
            " where    `Product Store Key`=%d", $category->get('Category Store Key')
        );
        break;
    default:

        exit('unknown parent '.$parameters['parent']);
}


switch ($parameters['elements_type']) {

    case 'status':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 4) {
            $where .= ' and `Product Status` in ('.$_elements.')';
        }
        break;


}


if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Product Name` like '%".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;

if ($order == 'name') {
    $order = '`Product Outer Description`';
} elseif ($order == 'price') {
    $order = '`Product Price`';
} elseif ($order == 'code') {
    $order = '`Product Code File As`';
} else {
    $order = 'P.`Product ID`';
}


$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";

$fields = $associated_field
    ." P.`Product ID`,`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Key`,`Store Code`,F.`Category Label`,`Category Code`,`Product Family Category Key`  ";


?>
