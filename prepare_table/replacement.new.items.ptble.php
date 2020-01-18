<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2017 at 19:02:30 GMT+8, Kuala Lumpur Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


$where  = sprintf(' where  `Inventory Transaction Type`="Sale" and  OTF.`Order Key`=%d', $parameters['parent_key']);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Product History Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = '`Product History Code`';
} elseif ($order == 'created') {
    $order = '`Order Date`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} else {
    $order = 'OTF.`Order Transaction Fact Key`';
}

$table
    = "
  `Order Transaction Fact` OTF
left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`)
 left join  `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)
 left join `Order Transaction Out of Stock in Basket Bridge` OO on (OO.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)
 left join `Inventory Transaction Fact` ITF on (OTF.`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`)
  left join  `Part Dimension` Pa on (ITF.`Part SKU`=Pa.`Part SKU`)
";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "`Part Package Description`,`Inventory Transaction Quantity`,`Inventory Transaction Key`,ITF.`Part SKU`,`Part Reference`,
	OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Product Units Per Case`,`Product History Name`,`Product History Price`,`Product Currency`,OTF.`Product ID`,`Product History Code` as `Product Code` ,`Order Quantity`,`Order Bonus Quantity`,`Product Availability`,`Product History XHTML Short Description`,`Order Transaction Amount`,`Transaction Tax Rate`,`Product Tariff Code`,
	`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,`Order Date`,`Order Last Updated Date`,`Current Dispatching State`,OO.`Quantity` as `Out of Stock Quantity`,
		(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`

";


