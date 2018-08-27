<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 22:05:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$filter_msg = '';



$where = sprintf("where   `Back in Stock Reminder Product ID`=%d",$_data['parameters']['parent_key']);



$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  C.`Customer Name` like '".addslashes($f_value)."%'";
}


$group_by='  ';

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Customer Name`';
} else {
    $order = '`Back in Stock Reminder Key`';
}


$table = '`Back in Stock Reminder Fact`  left join `Customer Dimension` on (`Back in Stock Reminder Customer Key`=`Customer Key`)  left join `Product Dimension` on (`Back in Stock Reminder Product ID`=`Product ID`)';

$sql_totals = "select count(distinct `Back in Stock Reminder Customer Key`) as num from $table  $where  ";

//print $sql_totals;

$fields
            = "`Back in Stock Reminder Key`,`Customer Store Key`,`Customer Name`,`Customer Key`,`Back in Stock Reminder Customer Key`,`Customer Type by Activity`,`Customer Location`,
            `Back in Stock Reminder State`,`Back in Stock Reminder Creation Date`
          
            
            ";





?>
