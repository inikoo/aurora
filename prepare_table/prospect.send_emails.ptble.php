<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 12:01:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('prospect'):
        $where = sprintf(
            ' where `Email Tracking Recipient`="Prospect"  and  `Email Tracking Recipient Key`=%d', $parameters['parent_key']
        );
        break;
    case('customer'):
        $where = sprintf(
            ' where `Email Tracking Recipient`="Customer"  and  `Email Tracking Recipient Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'subject' and $f_value != '') {
    $wheref = sprintf(
        ' and `Published Email Template Subject` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'subject') {
    $order = '`Published Email Template Subject`';
} elseif ($order == 'date') {
    $order = '`Email Tracking Created Date`';
} elseif ($order == 'state') {
    $order = '`Email Tracking State`';
} else {
    $order = '`Email Tracking Created Date`';
}
$table  = '`Email Tracking Dimension`  left join `Published Email Template Dimension` S on (`Email Tracking Published Email Template Key`=`Published Email Template Key`) ';
$fields = "`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";


$sql_totals = "select count(*) as num from $table $where ";


?>
