<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2018 at 13:00:34 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$table = '`Order Basket Purge Order Fact` left join   `Order Dimension` O on (`Order Key`=`Order Basket Purge Order Order Key`)  ';


 $where = sprintf(
                ' where `Order Basket Purge Order Basket Purge Key`=%d ', $parameters['parent_key']
            );




if (isset($parameters['elements_type'])) {



    switch ($parameters['elements_type']) {


        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['state']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 4) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Basket Purge Order Status` in ('.$_elements.')';
            }
            break;

    }
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Plain Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'public_id') {
    $order = '`Order File As`';
} elseif ($order == 'last_updated_date') {
    $order = '`Order Basket Purge Order Last Updated Date`';
} elseif ($order == 'customer') {
    $order = 'O.`Order Customer Name`';
}   elseif ($order == 'net_amount') {
    $order = 'O.`Order Total Net Amount`';
} elseif ($order == 'margin') {
    $order = 'O.`Order Margin`';
}elseif ($order == 'purge_status') {
    $order = '`Order Basket Purge Order Status`';
} elseif ($order == 'purged_date') {
    $order = '`Order Basket Purge Purged Date`';
}  else {
    $order = 'O.`Order Key`';
}



$fields
    = '`Order Profit Amount`,`Order Margin`,`Order State`,`Order Number Items`,`Order Store Key`,`Order Payment Method`,`Order Total Net Amount`,`Order Payment State`,`Order State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated by Customer`,O.`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State`,
    `Order Basket Purge Order Status`,`Order Basket Purge Purged Date`,`Order Basket Purge Order Basket Purge Key`
    
    ';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";
//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;


