<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 October 2015 at 16:52:46 CET, Train (Napoli-Florence), Italy

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where  = sprintf(' where O.`Invoice Key`=%d', $parameters['parent_key']);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and O.`Product Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = 'O.`Product Code`';
} elseif ($order == 'created') {
    $order = '`Invoice Date`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = 'O.`Order Transaction Fact Key`';
}

$table
    = "
`Order Transaction Fact` O  left join `Product History Dimension` PH on (O.`Product Key`=PH.`Product Key`) left join
  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "
O.`Order Transaction Fact Key`,`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product History XHTML Short Description`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code`

";

// $sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
