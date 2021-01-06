<?php

/**
 * @var $f_value string
 */

$where    = 'where true';
$table    = '`Consignment Dimension`  ';
$wheref   = '';
$group_by = '';


if (isset($parameters['period'])) {
    include_once('utils/date_functions.php');
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, '`Consignment Date`');
} else {
    $where_interval = '';
}




$where = 'where true ';

if ($where_interval != '') {

    $where .= $where_interval['mysql'];
}


if (isset($parameters['elements'])) {

    $elements = $parameters['elements'];

    switch ($parameters['elements_type']) {
        case('dispatch'):
            $_elements            = '';
            $num_elements_checked = 0;


            foreach ($elements['dispatch']['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $num_elements_checked++;

                    $_elements .= ",'".$_key."'";



                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 5) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where     .= ' and `Consignment State` in ('.$_elements.')';
            }



            break;



    }
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Consignment Date`';
} elseif ($order == 'number') {
    $order = '`Consignment Public ID`';
}elseif ($order == 'delivery_notes') {
    $order = '`Consignment Number Delivery Notes`';
} elseif ($order == 'boxes') {
    $order = '`Consignment Number Boxes`';
} elseif ($order == 'tariff_codes') {
    $order = '`Consignment Number Tariff Codes`';
}elseif ($order == 'state') {
    $order = '`Consignment State`';
}elseif ($order == 'amount') {
    $order = '`Consignment Net Amount`';
} else {
    $order = '`Consignment Key`';
}


if ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Consignment Public ID`  like "%%%s%%" ', addslashes($f_value)
    );
}

$fields     = '`Consignment Currency`,`Consignment Number Delivery Notes`,`Consignment Public ID`,`Consignment Date`,`Consignment Key`,`Consignment Number Boxes`,`Consignment State`,`Consignment Net Amount`';
$sql_totals = "select count(Distinct `Consignment Key`) as num from $table $where ";



