<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 January 2018 at 15:43:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Customer Store Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}





$wheref = '';
if ($parameters['f_field'] == 'country' and $f_value != '') {
    $wheref = sprintf(
        ' and `Country Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'country') {
    $order = '`Country Name`';
}elseif ($order == 'customers' or $order == 'customers_percentage') {
    $order = 'customers';
} elseif ($order == 'sales' or $order == 'sales_percentage') {
    $order = 'sales';
}elseif ($order == 'invoices') {
    $order = 'invoices';
} elseif ($order == 'flag') {
    $order = '`Country 2 Alpha Code`';
}elseif ($order == 'sales_per_customer') {
    $order = 'sales_per_registration';
} else {
    $order = '`Country Key`';
}


$table  = '`Customer Dimension` C left join kbase.`Country Dimension` Co on (C.`Customer Contact Address Country 2 Alpha Code`=Co.`Country 2 Alpha Code`)  ';
$fields = "`Country Key`,`Country Name`,`Country 2 Alpha Code`,count(*) as customers,sum(`Customer Invoiced Net Amount`) as sales,sum(`Customer Number Invoices`) as invoices,  sum(`Customer Invoiced Amount`)/count(*) as sales_per_registration" ;
$group_by=' group by `Customer Contact Address Country 2 Alpha Code` ';

$sql_totals = "select count(Distinct `Customer Contact Address Country 2 Alpha Code`) as num from $table $where ";


?>
