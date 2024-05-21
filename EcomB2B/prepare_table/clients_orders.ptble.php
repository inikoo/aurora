<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 February 2020  23:18::00  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$table = '`Order Dimension` O  left join `Customer Client Dimension` CC on (CC.`Customer Client Key`=O.`Order Customer Client Key`)';

if($parameters['parent']=='client'){
    $where = sprintf(
        'where  `Order Customer Client Key`=%d  ', $parameters['parent_key']
    );

}elseif($parameters['parent']=='customer'){
    $where = sprintf(
        'where  `Order Customer Key`=%d  ', $parameters['parent_key']
    );

}else{
    exit ('forbidden x43429');
}




if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Order Date`');
    $where .= $where_interval['mysql'];
}

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
            } elseif ($num_elements_checked == 7) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order State` in ('.$_elements.')';
            }
            break;
        case('source'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['source']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Main Source Type` in ('.$_elements.')';
            }
            break;
        case('type'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['type']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Type` in ('.$_elements.')';
            }
            break;
        case('payment'):
            $_elements            = '';
            $num_elements_checked = 0;

            //'Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'

            foreach (
                $parameters['elements']['payment']['items'] as $_key => $_value
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
                $where .= ' and `Order Payment State` in ('.$_elements.')';
            }
            break;
    }
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Order Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value)
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
} elseif ($order == 'last_date' or $order == 'date') {
    $order = 'O.`Order Date`';
} elseif ($order == 'customer') {
    $order = 'O.`Order Customer Name`';
} elseif ($order == 'dispatch_state') {
    $order = 'O.`Order State`';
} elseif ($order == 'payment_state') {
    $order = 'O.`Order Payment State`';
} elseif ($order == 'state') {
    $order = 'O.`Order State`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Order Total Amount`';
} elseif ($order == 'margin') {
    $order = 'O.`Order Margin`';
} else {
    $order = 'O.`Order Key`';
}



$fields
    = '`Order Profit Amount`,`Order Margin`,`Order State`,`Order Number Items`,`Order Store Key`,`Order Payment Method`,`Order Balance Total Amount`,`Order Payment State`,`Order State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State`,
    `Customer Client Name`,`Order Customer Client Key`,`Order Delivery Note Key`
    ';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";






