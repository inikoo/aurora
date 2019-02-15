<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoD.com>
 Created: 26 November 2018 at 18:28:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';



$where = 'where true ';
$table = '`Supplier Delivery Dimension` D   left join `Order Dimension` O on (`Order Key`=`Supplier Delivery Parent Key`) left join `Store Dimension` on (`Store Key`=`Order Store Key`) '  ;



switch ($parameters['parent']){
    case 'warehouse':

        $where = sprintf(
            'where  `Supplier Delivery Parent`="Order" and `Supplier Delivery Warehouse Key`=%d  ', $parameters['parent_key']
        );
        break;
    default:
        exit ('no parent ->'.$parameters['parent']);
}





if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates(
        $from, $to, 'D.`Supplier Delivery Date`'
    );
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


                    if ($_key == 'InProcess') {
                        $_elements .= ",'InProcess','Dispatched','Consolidated'";
                    }if ($_key == 'Placed') {
                        $_elements .= ",'Placed','Costing'";
                    } else {

                        $_elements .= ",'".addslashes($_key)."'";
                    }

                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked < 6) {




                $_elements = preg_replace('/^,/', '', $_elements);
//'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'
                //'InProcess','Received','Checked','Placed','InvoiceChecked','Cancelled'


                $where .= ' and `Supplier Delivery State` in ('.$_elements.')';
            }
            break;
    }
}


if (($parameters['f_field'] == 'number') and $f_value != '') {

    $wheref = sprintf(
        '  and  `Supplier Delivery Public ID`  like "%%%s%%" ', addslashes($f_value)
    );


}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'public_id') {
    $order = '`Supplier Delivery File As`';
} elseif ($order == 'last_date') {
    $order = 'D.`Supplier Delivery Last Updated Date`';
} elseif ($order == 'date') {
    $order = 'D.`Supplier Delivery Creation Date`';
} elseif ($order == 'supplier') {
    $order = 'D.`Supplier Delivery Supplier Name`';
} elseif ($order == 'state') {
    $order = 'D.`Supplier Delivery State`';
} elseif ($order == 'total_amount') {
    $order = 'D.`Supplier Delivery Total Amount`';
} elseif ($order == 'store') {
    $order = '`Store Code`';
} else {
    $order = 'D.`Supplier Delivery Key`';
}

$fields
    = '`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,D.`Supplier Delivery Key`,`Supplier Delivery State`,`Supplier Delivery Public ID`,D.`Supplier Delivery Last Updated Date`,`Supplier Delivery Creation Date`,
`Supplier Delivery Parent Code`,`Supplier Delivery Parent Name`,`Supplier Delivery Total Amount`,`Supplier Delivery Currency Code`,`Order Store Key`,`Store Name`,`Store Code`,`Supplier Delivery Warehouse Key`
';

$sql_totals
    = "select count(Distinct D.`Supplier Delivery Key`) as num from $table $where ";

?>
