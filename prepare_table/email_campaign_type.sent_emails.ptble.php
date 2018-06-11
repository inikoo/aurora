<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2018 at 13:33:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
    case('email_campaign_type'):
        $where = sprintf(
            ' where `Email Tracking Email Template Type Key`=%d', $parameters['parent_key']
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
$table  = '`Email Tracking Dimension`  left join `Published Email Template Dimension` S on (`Email Tracking Published Email Template Key`=`Published Email Template Key`)  left join `Customer Dimension` C on (C.`Customer Key`=`Email Tracking Recipient Key`)';
$fields = "`Customer Key`,`Customer Name`,`Email Tracking Email`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`,`Customer Store Key`";


$sql_totals = "select count(*) as num from $table $where ";
//print $sql_totals;

?>
