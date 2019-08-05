<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoD.com>
 Created: 18-07-2019 20:27:15 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';





$table
    = ' `Purchase Order Transaction Fact` POTF  left join  `Supplier Delivery Dimension` D on (POTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) 
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

	 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)

	
	';
if ($parameters['parent'] == 'production_part') {

    $where = sprintf(
        'where POTF.`Supplier Part Key`=%d  ', $parameters['parent_key']
    );

} elseif ($parameters['parent'] == 'part') {


    $where = sprintf('where `Part SKU`=%d  ', $parameters['parent_key']);

} else {
    exit("unknown parent: ".$parameters['parent']." \n");
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

//'InProcess','Dispatched','Received','Checked','Placed','Cancelled'
    switch ($parameters['elements_type']) {
        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;



            foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    if ($_key == 'InProcess') {
                        $_elements .= ",'InProcess'";
                    }elseif ($_key == 'Checked') {
                        $_elements .= ",'Dispatched','Received','Checked'";
                    }elseif ($_key == 'Placed') {
                        $_elements .= ",'Placed'";
                    } else {

                        $_elements .= ",'".addslashes($_key)."'";
                    }
                }
            }


            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked < 4) {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Supplier Delivery Transaction State` in ('.$_elements.')';
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
} else {
    $order = 'D.`Supplier Delivery Key`';
}

$fields
    = '`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,D.`Supplier Delivery Key`,`Supplier Delivery State`,`Supplier Delivery Public ID`,D.`Supplier Delivery Last Updated Date`,`Supplier Delivery Creation Date`,
`Supplier Delivery Parent Code`,`Supplier Delivery Parent Name`,`Supplier Delivery Total Amount`,`Supplier Delivery Currency Code`,
`Purchase Order Transaction State`
';

$sql_totals
    = "select count(Distinct D.`Supplier Delivery Key`) as num from $table $where ";


