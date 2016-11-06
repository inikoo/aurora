<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 October 2015 at 18:14:31 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where  = " where `User Type`!='Customer' and `User Active`='Yes'  ";
$wheref = '';

$_order = $order;
$_dir   = $order_direction;


if ($order == 'active_users') {
    $order = 'active_users';
} else {
    $order = 'U.`User Type`';
}

$group_by = ' group by `User Type`';

$table = '`User Dimension` U';

$sql_totals = false;

$fields = "`User Type`,count(*) as active_users";

?>
