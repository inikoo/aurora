<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 20:33:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$filter_msg = '';


switch ($_data['parameters']['parent']) {
    case 'store':
        $where = sprintf("where    `Back in Stock Reminder Store Key`=%d", $_data['parameters']['parent_key']);
        break;
    default:
        exit('no parent back to sock table');

}

$group_by=' group by `Back in Stock Reminder Product ID`';

//$group = 'group by `Back in Stock Reminder Product ID`';

$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  P.`Product Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Product Code`';
} elseif ($order == 'customers') {
    $order = 'count(distinct `Back in Stock Reminder Customer Key`)';
} else {
    $order = '`Back in Stock Reminder Key`';
}


$table = '`Back in Stock Reminder Fact` left join `Product Dimension` on (`Back in Stock Reminder Product ID`=`Product ID`)
    left join `Store Dimension` S on (`Product Store Key`=`Store Key`)
';

$sql_totals = "select count(distinct `Back in Stock Reminder Product ID`) as num from $table  $where  ";

//print $sql_totals;

$fields = "`Back in Stock Reminder Key`,`Product Store Key`,`Product Code`,`Product ID`,`Back in Stock Reminder Product ID`,`Product Status`,`Product Name`, `Product Units Per Case`,`Store Key`,
           count(distinct `Back in Stock Reminder Customer Key`) customers,`Product Web State`,`Product Availability`,`Product Web Configuration`,`Product Number of Parts`
            
            ";


?>
