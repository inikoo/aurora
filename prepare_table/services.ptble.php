<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
include_once('utils/date_functions.php');
$period_tag = get_interval_db_name($parameters['f_period']);

$group_by       = '';
$table
                = "`Product Dimension` P left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where          = " where `Product Type`='Service' ";
$where_interval = '';
$wheref         = '';

if (isset($parameters['awhere']) and $parameters['awhere']) {

    $tmp = preg_replace('/\\\"/', '"', $awhere);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data              = json_decode($tmp, true);
    $raw_data['store_key'] = $store;
    list($where, $table) = product_awhere($raw_data);

    $where_type     = '';
    $where_interval = '';
}


//print_r($parameters);


switch ($parameters['parent']) {

    case('stores'):
    case('account'):
        $where .= sprintf(
            " and `Product Store Key` in (%s) ", join(',', $user->stores)
        );
        break;
    case('store'):
        $where .= sprintf(
            ' and `Product Store Key`=%d', $parameters['parent_key']
        );
        break;

    case('part'):
        $table
            = '`Product Dimension`  P  left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`) left join `Product Part Bridge` B on (B.`Product Part Product ID`=P.`Product ID`)';

        $where .= sprintf(
            ' and `Product Part Part SKU`=%d  ', $parameters['parent_key']
        );
        break;


    case('customer'):

        $table
                  = " `Order Transaction Fact` OTF  left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=S.`Store Key`) ";
        $group_by = ' group by OTF.`Product ID`';
        $where .= sprintf(' and `Customer Key`=%d', $parameters['parent_key']);
        break;
    case('category'):
        include_once 'class.Category.php';
        $category = new Category($parameters['parent_key']);

        if (!in_array($category->data['Category Store Key'], $user->stores)) {
            return;
        }

        $where = sprintf(
            " where `Subject`='Product' and  `Category Key`=%d", $parameters['parent_key']
        );
        $table
               = ' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) left join `Product Data Dimension` PD on (PD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)';
        break;
    default:


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


if ($order == 'stock') {
    $order = '`Product Availability`';
} elseif ($order == 'code') {
    $order = '`Product Code File As`';
} elseif ($order == 'name') {
    $order = '`Product Outer Description`';
} elseif ($order == 'available_for') {
    $order = '`Product Available Days Forecast`';
} elseif ($order == 'shortname') {
    $order = '`Product Available Days Forecast`';
} elseif ($order == 'profit') {

    $order = '`Product '.$period_tag.' Acc Profit`';


} elseif ($order == 'sales') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';
} elseif ($order == 'sales_reorder') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';
} elseif ($_order == 'delta_sales') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';

} elseif ($order == 'margin') {
    $order = '`Product '.$period_tag.' Margin`';


} elseif ($order == 'sold') {
    $order = '`Product '.$period_tag.' Acc Quantity Invoiced`';
} elseif ($order == 'sold_reorder') {
    $order = '`Product '.$period_tag.' Acc Quantity Invoiced`';
} elseif ($order == 'family') {
    $order = '`Product Family`Code';
} elseif ($order == 'expcode') {
    $order = '`Product Tariff Code`';
} elseif ($order == 'parts') {
    $order = '`Product XHTML Parts`';
} elseif ($order == 'gmroi') {
    $order = '`Product GMROI`';
} elseif ($order == 'web') {
    $order = '`Product Web Configuration`';
} elseif ($order == 'stock_state') {
    $order = '`Product Availability State`';
} elseif ($order == 'stock_forecast') {
    $order = '`Product Available Days Forecast`';
}  elseif ($order == 'store') {
    $order = '`Store Code`';
} elseif ($order == 'price') {
    $order = '`Product Price`';
} elseif ($order == 'from') {
    $order = '`Product Valid From`';
} elseif ($order == 'to') {
    $order = '`Product Valid To`';
} elseif ($order == 'last_update') {
    $order = '`Product Last Updated`';
} else {
    $order = 'P.`Product ID`';
}


$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";

$fields
    = "P.`Product ID`,`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Code`,`Store Key`";

//$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
// print $sql;

