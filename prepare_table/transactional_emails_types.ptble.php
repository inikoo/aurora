<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2018 at 21:17:42 CEST, Trnava, Slovakia
 Copyright (c) 2018, Inikoo

 Version 3

*/

//

$where=" where  `Email Campaign Type Code` in ('GR Reminder','AbandonedCart','OOS Notification','Registration','Password Reminder','Order Confirmation','Delivery Confirmation','Invite','Invite Mailshot') ";



switch ($parameters['parent']){
    case 'store':
        $where.=sprintf(' and `Email Campaign Type Store Key`=%d ',$parameters['parent_key']);
        break;
    case 'account':

        break;
    default:
        exit('no parent set up '.$parameters['parent']);

}


$wheref = '';


$_order = $order;
$_dir   = $order_direction;


if ($order == 'type') {
    $order = '`Email Campaign Type Code`';
} elseif ($order == 'sent') {
    $order = '`Email Campaign Type Sent`';
}elseif ($order == 'open') {
    $order = '`Email Campaign Type Open`';
}elseif ($order == 'clicked') {
    $order = '`Email Campaign Type Clicked`';
} else {
    $order = '`Email Campaign Type Key`';
}
$table  = '`Email Campaign Type Dimension`  left join `Store Dimension` S on (S.`Store Key`=`Email Campaign Type Store Key`) ';
$fields = "`Email Campaign Type Key`,`Email Campaign Type Code`,`Email Campaign Type Store Key`,S.`Store Code`,`Store Name`,`Email Campaign Type Sent`,`Email Campaign Type Open`,`Email Campaign Type Clicked`";


$sql_totals = "select count(*) as num from $table $where ";



?>
