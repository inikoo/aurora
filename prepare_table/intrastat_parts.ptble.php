<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14-08-2019 20:35:53 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


if ($parameters['tariff_code'] == 'missing') {
    $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and (`Part Tariff Code` is null or `Part Tariff Code`="")  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched"  and `Delivery Note Quantity`>0  ', prepare_mysql($parameters['country_code']));

} else {
    $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and `Part Tariff Code` like "%s%%"  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" and `Delivery Note Quantity`>0  ', prepare_mysql($parameters['country_code']), addslashes($parameters['tariff_code']));

}


if (isset($parameters['parent_period'])) {


    include_once 'utils/date_functions.php';


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['parent_period'], $parameters['parent_from'], $parameters['parent_to']
    );


    $where_interval_invoice = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
    $where_interval_dn      = prepare_mysql_dates($from, $to, '`Delivery Note Date`');
    $where .= $where_interval_dn['mysql'];


   // $where .= " and ( (  I.`Invoice Key`>0  ".$where_interval_invoice['mysql']." ) or ( I.`Invoice Key` is NULL  ".$where_interval_dn['mysql']." ))  ";


}



$wheref = '';
if ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Order Public ID` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'supplier' and $f_value != '') {
    $wheref = sprintf(
        ' and `Supplier Code` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'code') {
    $order = '`Part Code File As`';
} elseif ($order == 'name') {
    $order = '`Part Name`';
} elseif ($order == 'units') {
    $order = '`Part Units Per Case`';
} elseif ($order == 'store') {
    $order = '`Store Code`';
} elseif ($order == 'price') {
    $order = '`Part Price`/`Part Units Per Case`';
} elseif ($order == 'weight') {
    $order = '`Part Package Weight`';
} elseif ($order == 'units_send') {
    $order = 'sum(`Delivery Note Quantity`*`Part Units Per Case`) ';
} else {

    $order = 'OTF.`Part ID`';
}


$group_by = ' group by OTF.`	Purchase Order Transaction Part SKU` ';

$table =
    ' `Order Transaction Fact` OTF left join `Part Dimension` P on (P.`Part SKU`=OTF.`Purchase Order Transaction Part SKU`) left join `Supplier Dimension` S on (S.`Supplier Key`=OTF.`Supplier Key`)  ';

$sql_totals = "";


$fields = "OTF.`Part ID`,P.`Part Reference`,`Part Name`,`Part Store Key`,`Part Units Per Case`,`Part Tariff Code`,`Part Price`,`Order Currency Code`,`Part Package Weight`,
sum(`Delivery Note Quantity`*`Part Units Per Case`) as units_send
";


