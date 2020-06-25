<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-07-2019 17:16:49 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';





    $table = '`Purchase Order Dimension` O left join `Staff Dimension` on (`Staff Key`=O.`Purchase Order Operator Key`)  ';

    $where = sprintf('where `Purchase Order Type`="Production"');


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Purchase Order Creation Date`');
    $where .= $where_interval['mysql'];
}


if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {
        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;

            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;


                    if ($_key == 'Planning') {
                        $_elements .= ",'InProcess'";
                    }elseif ($_key == 'Queued') {
                        $_elements .= ",'Submitted'";
                    }elseif ($_key == 'Manufacturing') {
                        $_elements .= ",'Confirmed'";
                    }elseif ($_key == 'Manufactured') {
                        $_elements .= ",'Manufactured'";
                    }elseif ($_key == 'QC_Pass') {
                        $_elements .= ",'QC_Pass'";
                    } elseif ($_key == 'Delivered') {
                        $_elements .= ",'Received','Checked','Inputted','Dispatched'";
                    }  elseif ($_key == 'Placed') {
                        $_elements .= ",'Placed','Costing','InvoiceChecked'";
                    } elseif ($_key == 'Cancelled') {
                        $_elements .= ",'Cancelled','NoReceived'";

                    }
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked < 5) {


                $_elements = preg_replace('/^,/', '', $_elements);

                $where .= ' and `Purchase Order State` in ('.$_elements.')';
            }
            break;


    }
}




if (($parameters['f_field'] == 'number') and $f_value != '') {

    $wheref = sprintf(
        '  and  `Purchase Order Public ID`  like "%%%s%%" ', addslashes($f_value)
    );




}




$_order = $order;
$_dir   = $order_direction;


if ($order == 'public_id') {
    $order = '`Purchase Order File As`';
} elseif ($order == 'last_date') {
    $order = 'O.`Purchase Order Last Updated Date`';
} elseif ($order == 'date') {
    $order = 'O.`Purchase Order Creation Date`';
}  elseif ($order == 'state') {
    $order = 'O.`Purchase Order State`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Purchase Order Total Amount`';
}elseif ($order == 'products') {
    $order = 'O.`Purchase Order Ordered Number Items`';
}elseif ($order == 'weight') {
    $order = 'O.`Purchase Order Weight`';
}elseif ($order == 'worker') {
    $order = '`Staff Alias`';
} else {
    $order = 'O.`Purchase Order Key`';
}

$fields
    = '`Purchase Order Parent`,`Purchase Order Parent Key`,O.`Purchase Order Key`,`Purchase Order State`,`Purchase Order Public ID`,O.`Purchase Order Last Updated Date`,`Purchase Order Creation Date`,
`Purchase Order Parent Code`,`Purchase Order Parent Name`,`Purchase Order Total Amount`,`Purchase Order Currency Code`,`Purchase Order Currency Exchange`,
`Purchase Order Weight`,`Purchase Order Ordered Number Items`,`Staff Alias`,`Purchase Order Operator Key`
';

$sql_totals
    = "select count(Distinct O.`Purchase Order Key`) as num from $table $where ";



