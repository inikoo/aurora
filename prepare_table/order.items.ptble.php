<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 13:32:44 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where  = sprintf(' where OTF.`Order Key`=%d', $parameters['parent_key']);
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and OTF.`Product Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = 'OTF.`Product Code`';
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
";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "
	OTF.`Order Transaction Fact Key`,`Product Units Per Case`,`Product History Name`,`Product History Price`,`Product Currency`,OTF.`Product ID`,OTF.`Product Code`,`Order Quantity`,`Order Bonus Quantity`,`Product Availability`,`Product History XHTML Short Description`,`Order Transaction Amount`,`Transaction Tax Rate`,`Product Tariff Code`,
	`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,`Order Date`,`Order Last Updated Date`,`Current Dispatching State`,OO.`Quantity` as `Out of Stock Quantity`,
		(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`

";

?>
