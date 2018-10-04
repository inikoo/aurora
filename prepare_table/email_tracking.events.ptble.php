<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 19:50:18 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

 $where = sprintf(
            ' where `Email Tracking Event Tracking Key`=%d', $parameters['parent_key']
        );


$wheref = '';






$_order = $order;
$_dir   = $order_direction;


if ($order == 'date') {
    $order = '`Email Tracking Event Date`';
} elseif ($order == 'event') {
    $order = '`Email Tracking Event Type`';
} else {
    $order = '`Email Tracking Event Key`';
}
$table  = '`Email Tracking Event Dimension`  ';
$fields = "`Email Tracking Event Key`,`Email Tracking Event Type`,`Email Tracking Event Date`,`Email Tracking Event Data`,`Email Tracking Event Status Code`,`Email Tracking Event Note`";


$sql_totals = "select count(*) as num from $table $where ";


?>
