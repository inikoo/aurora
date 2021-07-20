<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  13 November 2019  09:35::59  +0100, Malaga, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/

$currency = '';
$where    = 'where true';
$table    = '`Customer Dimension` C ';
$group_by = '';




if ($parameters['parent'] == 'store') {
    include_once('class.Store.php');

    if (in_array($parameters['parent_key'], $user->stores)) {
        $where_stores = sprintf(
            ' and  `Customer Store Key`=%d ', $parameters['parent_key']
        );
    } else {
        $where_stores = ' and false';
    }

    $store    = new Store($parameters['parent_key']);
    $currency = $store->data['Store Currency Code'];
    $where    .= $where_stores;
}


switch ($parameters['elements_type']) {
    case 'orders':
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
        } elseif ($count_elements == 1) {
            $where .= ' and `Customer With Orders`='.$_elements.'';
        }
        break;
    case 'activity':
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
        } elseif ($count_elements < 5) {
            $where .= ' and `Customer Type by Activity` in ('.$_elements.')';
        }
        break;
    case 'type':
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
            $where .= ' and `Customer Level Type` in ('.$_elements.')';
        }
        break;
    case 'location':
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
        } elseif ($count_elements < 2) {
            $where .= ' and `Customer Location Type` in ('.$_elements.')';
        }
        break;


}


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );


} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Plain Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= " and  `Customer Key` like '".addslashes(
            preg_replace('/\s*|\,|\./', '', $f_value)
        )."%' ";
} elseif ($parameters['f_field'] == 'last_more' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'last_less' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'max' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'min' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`>=".$f_value."    ";
}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'formatted_id') {
    $order = 'C.`Customer Key`';
} elseif ($order == 'location') {
    $order = '`Customer Location`';
} elseif ($order == 'orders') {
    $order = '`Customer Orders`';
} elseif ($order == 'email') {
    $order = '`Customer Main Plain Email`';
} elseif ($order == 'telephone') {
    $order = '`Customer Main Plain Telephone`';
} elseif ($order == 'mobile') {
    $order = '`Customer Main Plain Mobile`';
} elseif ($order == 'last_order') {
    $order = '`Customer Last Order Date`';
} elseif ($order == 'last_invoice') {
    $order = '`Customer Last Invoiced Order Date`';
} elseif ($order == 'contact_name') {
    $order = '`Customer Main Contact Name`';
} elseif ($order == 'company_name') {
    $order = '`Customer Company Name`';
}elseif ($order == 'total_payments') {
    $order = '`Customer Payments Amount`';
} elseif ($order == 'total_invoiced_amount') {
    $order = '`Customer Sales Amount`';
} elseif ($order == 'total_invoiced_net_amount') {
    $order = '`Customer Invoiced Net Amount`';

} elseif ($order == 'customer_balance') {
    $order = '`Customer Account Balance`';
} elseif ($order == 'total_payments') {
    $order = '`Customer Net Payments`';
} elseif ($order == 'top_profits') {
    $order = '`Customer Profits Top Percentage`';
} elseif ($order == 'top_balance') {
    $order = '`Customer Balance Top Percentage`';
} elseif ($order == 'top_orders') {
    $order = '``Customer Orders Top Percentage`';
} elseif ($order == 'top_invoices') {
    $order = '``Customer Invoices Top Percentage`';
} elseif ($order == 'total_refunds') {
    $order = '`Customer Total Refunds`';
} elseif ($order == 'contact_since') {
    $order = '`Customer First Contacted Date`';
} elseif ($order == 'activity') {
    $order = '`Customer Type by Activity`';
} elseif ($order == 'logins') {
    $order = '`Customer Number Web Logins`';
} elseif ($order == 'failed_logins') {
    $order = '`Customer Number Web Failed Logins`';
} elseif ($order == 'requests') {
    $order = '`Customer Number Web Requests`';
} elseif ($order == 'invoices') {
    $order = '`Customer Number Invoices`';
}elseif ($order == 'clients') {
    $order = '`Customer Number Clients`';
}elseif ($order == 'portfolio') {
    $order = '`Customer Number Products in Portfolio`';
} else {
    $order = '`Customer Name`';
}


$sql_totals = "select count(Distinct `Customer Key`) as num from $table  $where ";


$fields = ' `Customer Key`,`Customer Name`,`Customer Orders`,`Customer Number Invoices`,`Customer First Contacted Date`,`Customer Billing Address Link`,`Customer Invoice Address Formatted`,
`Customer Type by Activity`,`Customer Store Key`,`Customer Company Name`,`Customer Main Contact Name`,`Customer Location`,`Customer Main Plain Email`,`Customer Main XHTML Telephone`,
`Customer Main XHTML Mobile`,`Customer Payments Amount`,`Customer Sales Amount`,`Customer Last Invoiced Order Date`,`Customer Invoiced Net Amount`,`Customer Number Clients`,
`Customer Contact Address Formatted`,`Customer Number Products in Portfolio`,`Customer Last Order Date`


';

