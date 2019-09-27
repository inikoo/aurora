<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2019 at 16:47:16 GMT+8
 Copyright (c) 2019, Inikoo

 Version 3

*/

//

//$where=" where  `Email Campaign Type Code` in ('GR Reminder','AbandonedCart','OOS Notification','Registration','Password Reminder','Order Confirmation','Delivery Confirmation','Invite','Invite Mailshot') ";



switch ($parameters['parent']){
    case 'store':
        $where=sprintf(' where `Email Campaign Type Scope`="Customer Notification" and `Email Campaign Type Store Key`=%d   and (`Website Registration Type`="ApprovedOnly" or ( `Website Registration Type`="Open" and `Email Campaign Type Code` not in ("Registration Approved","Registration Rejected") ) )   ',$parameters['parent_key']);
        break;
    case 'account':
        $where=sprintf(' where `Email Campaign Type Scope`="Customer Notification" ');
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
}elseif ($order == 'bounces') {
    $order = '`Email Campaign Type Soft Bounces`+`Email Campaign Type Hard Bounces`';
}elseif ($order == 'delivered') {
    $order = '`Email Campaign Type Delivered`';
}elseif ($order == 'open') {
    $order = '`Email Campaign Type Open`/`Email Campaign Type Delivered`';
}elseif ($order == 'mailshots') {
    $order = '`Email Campaign Type Mailshots`';
}elseif ($order == 'clicked') {
    $order = '`Email Campaign Type Clicked`/`Email Campaign Type Delivered`';
} else {
    $order = '`Email Campaign Type Key`';
}
$table  = '`Email Campaign Type Dimension`  left join `Store Dimension` S on (S.`Store Key`=`Email Campaign Type Store Key`)  left join `Website Dimension` W on (S.`Store Website Key`=`Website Key`)  ';
$fields = "`Email Campaign Type Mailshots`,`Email Campaign Type Status`,`Email Campaign Type Hard Bounces`,`Email Campaign Type Soft Bounces`,(`Email Campaign Type Hard Bounces`+`Email Campaign Type Soft Bounces`) as `Email Campaign Type Bounces`,`Email Campaign Type Spams`,`Email Campaign Type Delivered`,`Email Campaign Type Key`,`Email Campaign Type Code`,`Email Campaign Type Store Key`,S.`Store Code`,`Store Name`,`Email Campaign Type Sent`,`Email Campaign Type Open`,`Email Campaign Type Clicked`";


$sql_totals = "select count(*) as num from $table $where ";



