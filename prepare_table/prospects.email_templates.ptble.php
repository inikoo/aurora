<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2018 at 14:37:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


//print_r($parameters);

$table
    = '`Email Template Dimension` EB left join `Staff Dimension`  on (`Email Template Last Edited By`=`Staff Key`)';

$fields = "`Email Template Name`,`Email Template State`,`Email Template Created`,`Email Template Key`,`Staff Alias`,`Email Template Subject`";

$where = sprintf(
    " where `Email Template Scope`=%s and `Email Template Scope Key`=%d", prepare_mysql($parameters['parent']),$parameters['parent_key']
);


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`Email Template Name`';
}elseif ($order == 'state') {
    $order = '`Email Template State`';
} elseif ($order == 'author') {
    $order = '`Staff Alias`';
} elseif ($order == 'data') {
    $order = '`Email Template Created`';
}  else {
    $order = '`Email Template Key`';
}




$sql_totals = "select count(Distinct EB.`Email Template Key`) as num from $table $where  ";



?>
