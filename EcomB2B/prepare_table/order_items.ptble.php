<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 February 2020  21:21::02  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/


$where  = sprintf(' where OTF.`Order Key`=%d  and `Order Transaction Type`="Order" ', $parameters['parent_key']);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Product History Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code' or $order=='item') {
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
  left join  `Page Store Dimension` W on (PH.`Product ID`=W.`Webpage Scope Key` and `Webpage Scope`='Product')
  left join  `Customer Portfolio Fact` CPF on (PH.`Product ID`=CPF.`Customer Portfolio Product ID` and `Customer Portfolio Customer Key`=OTF.`Customer Key`)

 left join `Order Transaction Out of Stock in Basket Bridge` OO on (OO.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)
";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "`Product Availability State`,`Product Package Weight`,`Product Tariff Code`,`Product UN Number`,`Webpage URL`,`Customer Portfolio Reference`,`Order Quantity`,`Order Currency Code`,`Delivery Note Quantity`,`No Shipped Due Out of Stock`,`Order Bonus Quantity`,
	`Delivery Note Quantity`,OTF.`Product Key`,OTF.`Order Transaction Fact Key`,`Product Units Per Case`,`Product History Name`,`Product History Price`,`Product Currency`,OTF.`Product ID`,`Product History Code`,`Order Quantity`,`Order Bonus Quantity`,`Product Availability`,`Product History XHTML Short Description`,`Order Transaction Amount`,`Transaction Tax Rate`,`Product Tariff Code`,
	`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,`Order Date`,`Order Last Updated Date`,`Current Dispatching State`,OO.`Quantity` as `Out of Stock Quantity`,
		(select GROUP_CONCAT(`Deal Info`,'||',`Order Transaction Deal Pinned`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`

";


