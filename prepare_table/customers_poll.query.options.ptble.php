<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2018 at 15:39:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('Customer_Poll_Query'):
        $where = sprintf(
            ' where  `Customer Poll Query Option Query Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}





$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = sprintf(
        ' and `	Customer Poll Query Option Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Customer Poll Query Option Name`';
}elseif ($order == 'label') {
    $order = '`Customer Poll Query Option Label`';
}elseif ($order == 'customers' or $order == 'customers_percentage') {
    $order = '`Customer Poll Query Option Customers`';
}elseif ($order == 'last_chose') {
    $order = '`Customer Poll Query Option Last Answered`';
}  else {
    $order = '`Customer Poll Query Option Name`';
}


$table  = '`Customer Poll Query Option Dimension`   ';
$fields = "`Customer Poll Query Option Last Answered`,`Customer Poll Query Option Store Key`,`Customer Poll Query Option Key`,`Customer Poll Query Option Query Key`,`Customer Poll Query Option Name`,`Customer Poll Query Option Label`,`Customer Poll Query Option Customers` ";
$group_by='';
$sql_totals = "select count(*) as num from $table $where ";


?>
