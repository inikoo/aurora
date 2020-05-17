<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:26 September 2018 at 15:16:47 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$table  = '`Email Tracking Dimension`  left join `Email Campaign Type Dimension`  on (`Email Tracking Email Template Type Key`=`Email Campaign Type Key`) 
  left join `Order Sent Email Bridge` on (`Order Sent Email Email Tracking Key`=`Email Tracking Key`) 
    left join `Published Email Template Dimension` on (`Email Tracking Published Email Template Key`=`Published Email Template Key`)
    left join `Customer Dimension` on (`Email Tracking Recipient Key`=`Customer Key`) ';
$fields =
    "`Order Sent Email Type`,`Email Tracking Email`,`Customer Store Key` as store_key,`Customer Key` as recipient_key,`Customer Name` as recipient_name,`Email Campaign Type Code`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";


$where = sprintf(
    ' where `Order Sent Email Order Key`=%d', $parameters['parent_key']
);


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Email Tracking Created Date`');
    $where          .= $where_interval['mysql'];

}


$wheref = '';
if(!empty($parameters['f_field'])){
    if ($parameters['f_field'] == 'subject' and $f_value != '') {
        $wheref = sprintf(
            ' and `Published Email Template Subject` REGEXP "\\\\b%s" ', addslashes($f_value)
        );
    }
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


$sql_totals = "select count(*) as num from $table $where ";


?>
