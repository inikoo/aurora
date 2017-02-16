<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2017 at 12:48:42 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where `Order Class`="Archived" ';
$table = '`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

if ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {
        $where .= sprintf(' and  `Order Store Key`=%d ', $parameters['parent_key']);
        if (!isset($store)) {
            include_once 'class.Store.php';
            $store = new Store($parameters['parent_key']);
        }
        $currency = $store->data['Store Currency Code'];
    } else {
        $where .= sprintf(' and  false');
    }


} elseif ($parameters['parent'] == 'account') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {

        if (count($user->stores) == 0) {
            $where = ' where false';
        } else {

            $where .= sprintf('and  `Order Store Key` in (%s)  ', join(',', $user->stores));
        }
    }

} else {
    exit("unknown parent\n");
}

if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $parameters['period'], $parameters['from'], $parameters['to']);

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Order Date`');
    $where .= $where_interval['mysql'];
}

if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {

        case('dispatch'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['dispatch']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 2) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Current Dispatch State` in ('.$_elements.')';
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
    }
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'country' and $f_value != '') {
    if ($f_value == 'UNK') {
        $wheref    = " and  `Order Main Country Code`='".$f_value."'    ";
        $find_data = ' '._('a unknown country');
    } else {
        include once('class.Address.php');
        $f_value = Address::parse_country($f_value);
        if ($f_value != 'UNK') {
            $wheref    = " and  `Order Main Country Code`='".$f_value."'    ";
            $country   = new Country('code', $f_value);
            $find_data = ' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code'])
                .'.gif" alt="'.$country->data['Country Code'].'"/>';
        }
    }
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
    $order = 'O.`Order Current Dispatch State`';
} elseif ($order == 'payment_state') {
    $order = 'O.`Order Current Payment State`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Order Total Amount`';
} else {
    $order = 'O.`Order Key`';
}

$fields
    = '`Order Number Items`,`Order Store Key`,`Payment Account Name`,`Order Payment Method`,`Order Current XHTML Dispatch State`,`Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State`';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";
//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;




?>
