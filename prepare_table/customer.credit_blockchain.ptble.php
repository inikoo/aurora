<?php


$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where true" ';
$table = '`Credit Transaction Fact` CTF left join `Payment Dimension` P on (P.`Payment Key`=CTF.`Credit Transaction Payment Key`)  left join  `Credit Transaction History Bridge`  on (`Credit Transaction History Credit Transaction Key`=`Credit Transaction Key`)  left join `History Dimension` on (`History Key`=`Credit Transaction History History Key`) 
left join `Order Dimension` on (`Order Key`=`Payment Order Key`) left join `Invoice Dimension` on (`Invoice Key`=`Payment Invoice Key`)

';


$where = sprintf(
    'where  `Credit Transaction Customer Key`=%d  ', $parameters['parent_key']
);


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        )
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, 'CTF.`Credit Transaction Date`');
    $where          .= $where_interval['mysql'];
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
                $where     .= ' and `Order State` in ('.$_elements.')';
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
                $where     .= ' and `Order Main Source Type` in ('.$_elements.')';
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
                $where     .= ' and `Order Type` in ('.$_elements.')';
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
                $where     .= ' and `Order Payment State` in ('.$_elements.')';
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


if ($order == 'date') {
    $order = '`Credit Transaction Date`';
} elseif ('running_amount') {
    $order = 'CTF.`Credit Transaction Running Amount`';
} elseif ('amount') {
    $order = 'CTF.`Credit Transaction Amount`';
} elseif ('type') {
    $order = '`Credit Transaction Type`';
} else {
    $order = 'CTF.`Credit Transaction Key`';
}


$fields     =
    '`History Abstract`, `Order Key`,`Order Public ID`,`Order Store Key`,  `Invoice Key`,`Invoice Public ID` ,`Invoice Type`,`Payment Related Payment Transaction ID`,`Payment Related Payment Key`, CTF.`Credit Transaction Key`,`Credit Transaction Type`,`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`,`Credit Transaction Running Amount`';
$sql_totals = "select count(Distinct `Credit Transaction Key`) as num from $table $where";


