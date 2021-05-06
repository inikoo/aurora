<?php

/**
 * @var $f_value string
 */

$where    = 'where true';
$table    = '`Delivery Note Dimension` D ';
$wheref   = '';
$group_by = '';


if (isset($parameters['period'])) {
    include_once('utils/date_functions.php');
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval               = prepare_mysql_dates($from, $to, 'ITF.`Date Packed`');
} else {
    $where_interval = '';
}



$where = sprintf(' where `Inventory Transaction Type` = "Sale" and  ( `Picker Key`=%d or `Packer Key`=%d) ', $parameters['parent_key'],$parameters['parent_key']);

$table          = '`Inventory Transaction Fact` ITF left join  `Delivery Note Dimension` D  on (ITF.`Delivery Note Key`=D.`Delivery Note Key`) ';
$group_by       = ' group by ITF.`Delivery Note Key`';









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
                    if ($_key == 'Ready') {
                        $_elements .= ",'Ready to be Picked'";

                    } elseif ($_key == 'Picking') {
                        $_elements .= ",'Picking','Picked','Picker Assigned'";
                    } elseif ($_key == 'Packing') {
                        $_elements .= ",'Packing','Packed','Packed Done'";
                    } elseif ($_key == 'Done') {
                        $_elements .= ",'Approved'";
                    } elseif ($_key == 'Sent') {
                        $_elements .= ",'Dispatched'";
                    } elseif ($_key == 'Returned') {
                        $_elements .= ",'Cancelled','Cancelled to Restock'";
                    }
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where     .= ' and `Delivery Note State` in ('.$_elements.')';
            }



            break;

        case('type'):


            $_elements            = '';
            $num_elements_checked = 0;
            foreach ($elements['type']['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $num_elements_checked++;

                    if ($_key == 'Replacements') {
                        $_elements .= ", 'Replacement','Replacement & Shortages'  ";

                    } else {

                        $_elements .= ", '$_key'";
                    }
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 5) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where     .= ' and `Delivery Note Type` in ('.$_elements.')';
            }
            break;

    }
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'date_packed') {
    $order = '`Delivery Note Estimated Weight`';
} elseif ($order == 'id') {
    $order = '`Delivery Note File As`';
} elseif ($order == 'customer') {
    $order = '`Delivery Note Customer Name`';
} elseif ($order == 'type') {
    $order = '`Delivery Note Type`';
} elseif ($order == 'weight') {
    $order = '`Delivery Note Weight`';
} elseif ($order == 'parcels') {
    $order = '`Delivery Note Parcel Type`,`Delivery Note Number Parcels`';
} else {
    $order = 'D.`Delivery Note Key`';
}


if ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Delivery Note Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Delivery Note ID` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'country' and $f_value != '') {
    if ($f_value == 'UNK') {
        $wheref    .= " and  `Delivery Note Country Code`='".$f_value."'    ";
        $find_data = ' '._('a unknown country');
    } else {
        $f_value = Address::parse_country($f_value);
        if ($f_value != 'UNK') {
            $wheref    .= " and  `Delivery Note Country Code`='".$f_value."'    ";
            $country   = new Country('code', $f_value);
            $find_data = ' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.png" alt="'.$country->data['Country Code'].'"/>';
        }
    }
}

$fields     = '*';
$sql_totals = "select count(Distinct D.`Delivery Note Key`) as num from $table $where ";
