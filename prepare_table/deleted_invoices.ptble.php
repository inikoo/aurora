<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12-06-2019 19:34:16 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$fields     = '';
$filter_msg = '';
$wheref     = '';
$group_by   = '';

$currency = '';



if (isset($parameters['excluded_stores']) and is_array(
        $parameters['excluded_stores']
    ) and count($parameters['excluded_stores']) > 0
) {
    $where = sprintf(
        ' where `Invoice Deleted Store Key` not in (%s)  ', join($parameters['excluded_stores'], ',')
    );
} else {
    $where = ' where true';
}


$table
            = '`Invoice Deleted Dimension` I  left join `User Dimension` on  (`User Key`=`Invoice Deleted User Key`) left join `Order Dimension` on  (`Order Key`=`Invoice Deleted Order Key`)  left join `Store Dimension` on  (`Store Key`=`Invoice Deleted Store Key`)  ';
$where_type = '';


if ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {
        $where = sprintf(
            ' where  `Invoice Deleted Store Key`=%d ', $parameters['parent_key']
        );
        include_once 'class.Store.php';
        $store    = new Store($parameters['parent_key']);
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

                $where = sprintf(
                    'where  `Invoice Deleted Store Key` in (%s)  ', join(',', $user->stores)
                );

            }
        }

}  else {

    exit("unknown parent ".$parameters['parent']." \n");
}


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, 'I.`Invoice Deleted Date`');
    $where .= $where_interval['mysql'];

}


if (isset($parameters['elements'])) {
    $elements = $parameters['elements'];


    switch ($parameters['elements_type']) {

        case('type'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach ($elements['type']['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 2) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Invoice Deleted Type` in ('.$_elements.')';
            }
            break;

            break;
    }

}


if ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  I.`Invoice Deleted Public ID` like '".addslashes(
            preg_replace('/\s*|\,|\./', '', $f_value)
        )."%' ";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Invoice Deleted Date`';
} elseif ($order == 'store') {
    $order = '`Store Code`';
} elseif ($order == 'number') {
    $order = '`Invoice Deleted Public ID`';
} elseif ($order == 'total_amount') {
    $order = '`Invoice Deleted Total Amount`';
} elseif ($order == 'type') {
    $order = '`Invoice Deleted Type`';
}  elseif ($order == 'user') {
    $order = '`User Alias`';
}  else {
    $order = 'I.`Invoice Deleted Key`';
}


$fields
    .= ' `Invoice Deleted Key`,`Invoice Deleted Type`,`Invoice Deleted Store Key`,`Invoice Deleted Order Key`,`Invoice Deleted Public ID`,`Invoice Deleted Total Amount`,`Invoice Deleted Date`,`Invoice Deleted User Key`,`Invoice Deleted Note`,`Invoice Deleted Metadata`,
     `Store Name`,`Store Code`,`Order Public ID`,`User Alias`
     ';
$sql_totals
    = "select count(Distinct I.`Invoice Deleted Key`) as num from $table $where ";


?>
