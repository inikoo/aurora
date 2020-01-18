<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 03-05-2019 14:59:34   Tranava Slovakia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where  = sprintf(' where O.`Invoice Key`=%d   ', $parameters['parent_key']);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Product History Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = '`Product History Code`';
} elseif ($order == 'created') {
    $order = '`Invoice Date`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = 'O.`Order Transaction Fact Key`';
}

$table
    = "
`Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)
";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "`Store Key`,
O.`Order Transaction Fact Key`,`Product Currency`,`Product History Price`,`Product History Code`,`Order Transaction Amount`,`Delivery Note Quantity`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,
`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Currency Code`,`Transaction Tax Rate`,`Order Transaction Metadata`

";


