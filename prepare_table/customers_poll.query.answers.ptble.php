<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 February 2018 at 11:57:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('Customer_Poll_Query'):
        $where = sprintf(
            ' where  `Customer Poll Query Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}


$wheref = '';
if ($parameters['f_field'] == 'answer' and $f_value != '') {
    $wheref = sprintf(
        ' and `	Customer Poll Replay` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
} elseif ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        ' and `	Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'customer') {
    $order = '`Customer Name`';
} elseif ($order == 'answer') {
    $order = '`Customer Poll Reply`';
} elseif ($order == 'date') {
    $order = '`Customer Poll Date`';
} elseif ($order == 'formatted_id') {
    $order = '`Customer Key`';
} else {
    $order = '`Customer Poll Key`';
}


$table      = '`Customer Poll Fact` CPF left join `Customer Dimension` C on (C.`Customer Key`=`Customer Poll Customer Key`)  ';
$fields     = "`Customer Poll Key`,`Customer Key`,`Customer Name`,`Customer Poll Reply`,`Customer Poll Date` ,`Customer Store Key`";
$group_by   = '';
$sql_totals = "select count(*) as num from $table $where ";


?>
