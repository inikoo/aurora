<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoD.com>
 Created: 7 July 2021 at 20:38:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where true ';
$table = '`Fulfilment Delivery Dimension` D  left join `Store Dimension` on (`Fulfilment Delivery Store Key`=`Store Key`)  ';

if ($parameters['parent'] == 'warehouse') {

    $where = sprintf(
        'where   `Fulfilment Delivery Warehouse Key`=%d', $parameters['parent_key']
    );


} elseif ($parameters['parent'] == 'customer') {
    $where = sprintf(
        'where   `Fulfilment Delivery Customer Key`=%d  ', $parameters['parent_key']
    );
}

if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates(
        $from, $to, 'D.`Fulfilment Delivery Date`'
    );
    $where          .= $where_interval['mysql'];
}

if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {
        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;

            //enum('InProcess','Received','Checked','ReadyToPlace','Placed','Cancelled')

            foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;
                    $_elements .= ",'".addslashes($_key)."'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked < 6) {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where     .= ' and `Fulfilment Delivery State` in ('.$_elements.')';
            }
            break;
    }
}


if (($parameters['f_field'] == 'number') and $f_value != '') {

    $wheref = sprintf(
        '  and  `Fulfilment Delivery Public ID`  like "%%%s%%" ', addslashes($f_value)
    );


}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'customer_delivery_reference') {
    $order = '`Fulfilment Delivery File As`';
} elseif ($order == 'last_date') {
    $order = 'D.`Fulfilment Delivery Last Updated Date`';
} elseif ($order == 'date') {
    $order = 'D.`Fulfilment Delivery Creation Date`';
} elseif ($order == 'customer') {
    $order = 'D.`Fulfilment Delivery Customer Name`';
} elseif ($order == 'state') {
    $order = 'D.`Fulfilment Delivery State`';
} else {
    $order = 'D.`Fulfilment Delivery Key`';
}

$fields = '`Fulfilment Delivery Customer Key`,D.`Fulfilment Delivery Key`,`Fulfilment Delivery State`,`Fulfilment Delivery Public ID`,D.`Fulfilment Delivery Last Updated Date`,`Fulfilment Delivery Creation Date`,
`Fulfilment Delivery Customer Name`,`Fulfilment Delivery Received Date`,`Fulfilment Delivery Estimated Receiving Date`,`Store Type`,`Fulfilment Delivery Warehouse Key`
';

$sql_totals = "select count(Distinct D.`Fulfilment Delivery Key`) as num from $table $where ";


