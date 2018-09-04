<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2018 at 23:17:19 GMT+9, Tokyo, Japan

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


$order_object=get_object('Order',$parameters['parent_key']);


$where  = sprintf(' where `Product Store Key`=%d and `Product Status`!="Discontinued"', $order_object->get('Order Store Key'));
$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and P.`Product Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = '`Product Code`';
} if ($order == 'description') {
    $order = '`Product Name`';
}  else {
    $order =' `Product Code`';

}

$table
    = "  `Product Dimension` P ";

$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = "
	`Product Current Key` ,`Product Units Per Case`,`Product Name`,`Product Price`,`Product Currency`,`Product ID`,`Product Code`,`Product Currency`,`Product Tariff Code`,`Product Availability`,
	(select Concat_WS('|',OTF.`Order Transaction Fact Key`,`Order Quantity`,`Order Transaction Amount`,`Current Dispatching State`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`) from  `Order Transaction Fact` OTF left join `Order Transaction Out of Stock in Basket Bridge` OO on (OO.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)
where OTF.`Order Key`=".$parameters['parent_key']."  and OTF.`Product ID`=P.`Product ID` limit 1) as otf_data,
(select group_concat(`Deal Info`) from  `Order Transaction Deal Bridge` OTDB 
where OTDB.`Order Key`=".$parameters['parent_key']."  and OTDB.`Product ID`=P.`Product ID` ) as `Deal Info`

";

?>
