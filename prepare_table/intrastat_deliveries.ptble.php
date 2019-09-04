<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14-08-2019 20:35:53 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


if ($parameters['tariff_code'] == 'missing') {
    $where = sprintf(' where `Supplier Delivery Parent Country Code`=%s and (`Part Tariff Code` is null or `Part Tariff Code`="")  and D.`Supplier Delivery Key` is not null and `Supplier Delivery State`="InvoiceChecked" and `Supplier Delivery Invoice Public ID` is not null and `Supplier Delivery Invoice Date` is not null  and `Supplier Delivery Placed Units`>0  ', prepare_mysql($parameters['country_code']));

} else {
    $where = sprintf(' where `Supplier Delivery Parent Country Code`=%s and `Part Tariff Code` like "%s%%"  and D.`Supplier Delivery Key` is not null and `Supplier Delivery State`="InvoiceChecked" and `Supplier Delivery Invoice Public ID` is not null and `Supplier Delivery Invoice Date` is not null  and `Supplier Delivery Placed Units`>0   ', prepare_mysql($parameters['country_code']), addslashes($parameters['tariff_code']));

}


if (isset($parameters['parent_period'])) {


    include_once 'utils/date_functions.php';


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['parent_period'], $parameters['parent_from'], $parameters['parent_to']
    );


    $where_interval_dn = prepare_mysql_dates($from, $to, '`Purchase Order Transaction Invoice Date`');
    $where .= $where_interval_dn['mysql'];




}



$wheref = '';
if ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Supplier Delivery Public ID` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'supplier' and $f_value != '') {
    $wheref = sprintf(
        ' and `Supplier Delivery Parent Code` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'amount') {
    $order = 'amount ';
} elseif ($order == 'parts') {
    $order = 'parts ';
} elseif ($order == 'weight') {
    $order = 'weight ';
}elseif ($order == 'supplier') {
    $order = '`Supplier Delivery Parent Name` ';
} elseif ($order == 'number') {
    $order = '`Supplier Delivery Public ID` ';
} elseif ($order == 'date') {
    $order = '`Purchase Order Transaction Invoice Date` ';
} else {

    $order = '`Purchase Order Transaction Part SKU`';
}



$group_by = 'group by OTF.`Supplier Delivery Key` ';

$table =
    ' `Purchase Order Transaction Fact` OTF left join `Supplier Delivery Dimension` D on (OTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`)   left join `Part Dimension` P on (P.`Part SKU`=OTF.`Purchase Order Transaction Part SKU`)   ';

$sql_totals = "";


$fields = "`Supplier Delivery Parent Key`,`Supplier Delivery Parent Code`,`Supplier Delivery Parent Name`,`Purchase Order Transaction Invoice Date`,
sum(`Supplier Delivery Placed Units`) as units_received,`Supplier Delivery Public ID`,`Supplier Delivery Parent`,
sum( `Supplier Delivery Extra Cost Account Currency Amount`+`Supplier Delivery Currency Exchange`*( `Supplier Delivery Net Amount`+`Supplier Delivery Extra Cost Amount` ) ) as amount,


	sum(`Supplier Delivery Placed Units`*`Part Package Weight`/`Part Units Per Package`) as weight ,
	count(distinct OTF.`Purchase Order Transaction Part SKU`) as parts,OTF.`Supplier Delivery Key`



";


