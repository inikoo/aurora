<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 20:49:12 GMT+8, Sanur , Bli, Indonesia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


$group_by='  ';

$where  = sprintf(
    ' where `Agent Supplier Purchase Order Purchase Order Key`=%d', $parameters['parent_key']
);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'order') {
    $order = '`Agent Supplier Purchase Order File As`';
}elseif ($order == 'supplier') {
    $order = '`Supplier Code`';
}elseif ($order == 'state') {
    $order = '`Agent Supplier Purchase Order State`';
} elseif ($order == 'amount') {
    $order = '`Agent Supplier Purchase Order Amount`';
} elseif ($order == 'products') {
    $order = '`Agent Supplier Purchase Order Products`';
} elseif ($order == 'problems') {
    $order = '`Agent Supplier Purchase Order Products With Problem`';
} else {
    $order = '`Agent Supplier Purchase Order Key`';
}

$table
    = "`Agent Supplier Purchase Order Dimension`  ASPOD left join `Supplier Dimension` SD on (`Agent Supplier Purchase Order Supplier Key`=`Supplier Key`)   ";

$sql_totals
    = "select count(distinct  SD.`Supplier Key`) as num from $table $where";

$fields
    = "SD.`Supplier Key`,`Supplier Code`,`Supplier Name`,ASPOD.`Agent Supplier Purchase Order Key`,`Agent Supplier Purchase Order Purchase Order Key`,`Agent Supplier Purchase Order Public ID`,
    `Agent Supplier Purchase Order Currency Code`,`Agent Supplier Purchase Order Products With Problem`,
    
    `Agent Supplier Purchase Order State`,`Agent Supplier Purchase Order Amount`,`Agent Supplier Purchase Order Products`,`Agent Supplier Purchase Order Weight`,`Agent Supplier Purchase Order Weight`,`Agent Supplier Purchase Order Weight`,`Agent Supplier Purchase Order Missing CBMs`

";





