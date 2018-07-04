<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2018 at 10:31:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$filter_msg = '';

$parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);


$where = sprintf("where  `Back in Stock Reminder State`='Ready' and `Back in Stock Reminder Store Key`=%d",$parent->get('Store Key'));

$group = 'group by `Back in Stock Reminder Customer Key`';
$group='';

$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  C.`Customer Name` like '".addslashes($f_value)."%'";
}

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
            = "`Back in Stock Reminder Key`,`Customer Store Key`,`Customer Name`,`Customer Key`,`Back in Stock Reminder Customer Key`,
            group_concat(CONCAT_WS('|',`Product ID`,`Product Code`)) as products
            
            ";





?>
