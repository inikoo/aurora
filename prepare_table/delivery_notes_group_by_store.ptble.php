<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 July 2018 at 23:35:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


if (count($user->stores) == 0) {
    $where = "where false";
} else {

    $where = sprintf("where `Store Key` in (%s)", join(',', $user->stores));
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
} elseif ($order == 'deliveries') {
    $order = 'deliveries';
} elseif ($order == 'replacements') {
    $order = 'replacements';
}elseif ($order == 'in_warehouse') {
    $order = 'in_warehouse';
}elseif ($order == 'sent') {
    $order = 'sent';
}elseif ($order == 'returned') {
    $order = 'returned';
} else {
    $order = 'S.`Store Key`';
}


$table = '`Store Dimension` S';
$fields
       = "S.`Store Key`,`Store Name`,`Store Code`,
   `Store Delivery Notes For Orders` as deliveries,
         ( `Store Delivery Notes For Replacements`+ `Store Delivery Notes For Shortages`) as replacements,
     (`Store Ready to Pick Delivery Notes`+`Store Picking Delivery Notes`+`Store Packing Delivery Notes`+`Store Ready to Dispatch Delivery Notes`) in_warehouse,     
  `Store Dispatched Delivery Notes` as sent,
    `Store Returned Delivery Notes` as returned,
(`Store Delivery Notes For Orders`+`Store Delivery Notes For Replacements`+`Store Delivery Notes For Shortages`+`Store Delivery Notes For Samples`+`Store Delivery Notes For Donations`) as delivery_notes


";

$sql_totals = "select count(*) as num from $table $where ";


?>
