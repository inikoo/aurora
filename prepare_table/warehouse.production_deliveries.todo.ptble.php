<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoD.com>
 Created: 11:02 pm Sunday, 21 June 2020 (MYT Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$table = '`Supplier Delivery Dimension` D';


$where = sprintf(
    "where  `Supplier Delivery Warehouse Key`=%d  and `Supplier Delivery Type`='Production' and `Supplier Delivery State` not in ('NoReceived','Cancelled','Placed','InvoiceChecked')  ", $parameters['parent_key']
);






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

$fields = '`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,D.`Supplier Delivery Key`,`Supplier Delivery State`,`Supplier Delivery Public ID`,D.`Supplier Delivery Last Updated Date`,`Supplier Delivery Creation Date`,
`Supplier Delivery Parent Code`,`Supplier Delivery Parent Name`,`Supplier Delivery Total Amount`,`Supplier Delivery Currency Code`,`Supplier Delivery Warehouse Key`
';

$sql_totals = "select count(Distinct D.`Supplier Delivery Key`) as num from $table $where ";
//print $sql_totals;


