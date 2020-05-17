<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 12:41:35 CEST, Trnava, Slovakia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$where=sprintf(' where  `Email Campaign Type Store Key`=%d  and  `Email Campaign Type Code` in ("GR Reminder","OOS Notification","AbandonedCart") ',$parameters['parent_key']);


$wheref = '';
/*
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Email Campaign Type Code` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}
*/

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Email Campaign Type Code`';
} elseif ($order == 'mailshots') {
    $order = '`Email Campaign Type Mailshots`';
} elseif ($order == 'send') {
    $order = '`Email Campaign Type Sent`';
}elseif ($order == 'open_rate') {
    $order = '`Email Campaign Type Open`/`Email Campaign Type Sent`';
} elseif ($order == 'clicked_rate') {
    $order = '`Email Campaign Type Clicked`/`Email Campaign Type Sent`';
} else {
    $order = '`Email Campaign Type Key`';
}
$table  = '`Email Campaign Type Dimension` ';
$fields = "`Email Campaign Type Code`,`Email Campaign Type Key`,`Email Campaign Type Store Key`,`Email Campaign Type Mailshots`,`Email Campaign Type Sent`,`Email Campaign Type Open`,`Email Campaign Type Clicked`,`Email Campaign Type Email Template Key`";


$sql_totals = "select count(*) as num from $table $where ";


?>
