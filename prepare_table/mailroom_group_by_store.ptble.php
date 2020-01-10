<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  30 December 2020  12:01::31  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


if ($user->can_view('mailroom')) {
    $where = "where true";

} else {
    $where = "where false";
}

$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Store Name` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Store Code` like '".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Store Code`';
} elseif ($order == 'name') {
    $order = '`Store Name`';
} else {
    $order = 'S.`Store Key`';
}

$table  = '`Store Dimension` S left join `Store Data` D on (D.`Store Key`=S.`Store Key`)';
$fields = " S.`Store Key`,`Store Code`,`Store Name`

";

$sql_totals = "select count(*) as num from $table $where ";


