<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 February 2018 at 13:12:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/






$group_by = '';
$wheref   = '';

$currency = '';
$table = '`Customer Dimension`  ';




if ($parameters['parent'] == 'mailshot') {

    $where = sprintf(' where `Customer Store Key`=%d and `Customer Main Plain Email`!="" and `Customer Send Newsletter`="Yes" ',$parameters['store_key']);



} else{
    exit('error abandoned_cart.mail_list E.l.1a');
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf('  and  `Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value));
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'formatted_id') {
    $order = '`Customer Key`';
} elseif ($order == 'email') {
    $order = '`Customer Main Plain Email`';
} elseif ($order == 'name') {
    $order = '`Customer Name`';
} else {
    $order = '`Customer Main Plain Email`';
}

$fields
    = '`Customer Key`,`Customer Store Key`,`Customer Name`,`Customer Main Contact Name`,`Customer Main Plain Email`,`Customer Company Name`';

$sql_totals = "select count(Distinct `Customer Key`) as num from $table $where";


$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


?>
