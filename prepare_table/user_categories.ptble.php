<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 October 2015 at 18:14:31 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where  = " where `User Type`!='Customer'  ";
$wheref = '';

$_order = $order;
$_dir   = $order_direction;


if ($order == 'active_users') {
    $order = 'active_users';
}elseif ($order == 'inactive_users') {
    $order = 'inactive_users';
}else {
    $order = 'U.`User Type`';
}

$group_by = ' group by `User Type`';

$table = '`User Dimension` U';

$sql_totals = false;

$fields = "`User Type`,sum(if(`User Active`='Yes',1,0)) as active_users,sum(if(`User Active`='No',1,0)) as inactive_users";

?>
