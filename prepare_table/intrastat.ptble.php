<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 December 2017 at 12:29:36 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once('class.Country.php');

$account_country = new Country('code', $account->get('Account Country Code'));


$intrastat_countries = array(
    'NL',
    'BE',
    'GB',
    'BG',
    'ES',
    'IE',
    'IT',
    'AT',
    'GR',
    'CY',
    'LV',
    'LT',
    'LU',
    'MT',
    'PT',
    'PL',
    'FR',
    'RO',
    'SE',
    'DE',
    'SK',
    'SI',
    'FI',
    'DK',
    'CZ',
    'HU',
    'EE'
);


$intrastat_countries = "'".implode("','", $intrastat_countries)."'";


$intrastat_countries = preg_replace('/,?\''.$account_country->get('Country 2 Alpha Code').'\'/', '', $intrastat_countries);

$intrastat_countries = preg_replace('/^,/', '', $intrastat_countries);


$where = ' where `Delivery Note Address Country 2 Alpha Code` in ('.$intrastat_countries.')  and DN.`Delivery Note Key` is not null  and `Delivery Note State`="Dispatched"  and `Delivery Note Quantity`>0  ';


//print_r($parameters);

if (isset($parameters['period'])) {


    include_once 'utils/date_functions.php';


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $where_interval_invoice = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
    $where_interval_dn      = prepare_mysql_dates($from, $to, '`Delivery Note Date`');

    $where .= $where_interval_dn['mysql'];

    //    $where .= " and ( (  I.`Invoice Key`>0  ".$where_interval_invoice['mysql']." ) or ( I.`Invoice Key` is NULL  ".$where_interval_dn['mysql']." ))  ";


}

$parameters['invoices_vat'] = (int)$parameters['invoices_vat'];
$parameters['invoices_no_vat'] = (int)$parameters['invoices_no_vat'];
$parameters['invoices_null'] = (int)$parameters['invoices_null'];


if ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  ";

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` not in ('EX','OUT','EU') ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` in ('EX','OUT','EU') ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
    $where .= " and  I.`Invoice Key` is null  ";

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
    $where .= " and   (  I.`Invoice Key` is null  or   ( I.`Invoice Key`>0    and I.`Invoice Tax Code` not in ('EX','OUT','EU') )  ) ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {
    $where .= " and   (  I.`Invoice Key` is null   or  I.`Invoice Tax Code` in ('EX','OUT','EU')    ) ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
    $where .= " and false ";

}


$wheref = '';
if ($parameters['f_field'] == 'commodity' and $f_value != '') {
    $wheref .= " and  `Product Tariff Code` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'period') {
    $order = 'min_date';
} elseif ($order == 'tariff_code') {
    $order = 'tariff_code';
} elseif ($order == 'tariff_code') {
    $order = 'tariff_code';
} elseif ($order == 'value') {
    $order = 'value';
} elseif ($order == 'items') {
    $order = 'items';
} elseif ($order == 'products') {
    $order = 'products';
} elseif ($order == 'weight') {
    $order = 'sum(`Delivery Note Quantity`*`Product Package Weight`)';
} elseif ($order == 'orders') {
    $order = 'orders';
} else {

    $order = '`Delivery Note Address Country 2 Alpha Code`';
}


$group_by = 'group by LEFT(`Product Tariff Code`,8),`Delivery Note Address Country 2 Alpha Code`';

$table =
    ' `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`) left join `Invoice Dimension` I  on (OTF.`Invoice Key`=I.`Invoice Key`) ';

$sql_totals = "";


$fields = "
sum(`Delivery Note Quantity`*`Product Units Per Case`) as items,
count(distinct OTF.`Product ID`) as products,
count(distinct OTF.`Order Key`) as orders,

sum(`Invoice Currency Exchange Rate`*`Order Transaction Amount`) as value,
	sum(`Delivery Note Quantity`*`Product Package Weight`) as weight ,
	LEFT(`Product Tariff Code`,8) as tariff_code, min(`Delivery Note Date`) as min_date , `Delivery Note Date` , `Delivery Note Address Country 2 Alpha Code`,
	group_concat(DN.`Delivery Note Key`),group_concat(distinct date_format(`Delivery Note Date`,'%y%m')) as monthyear 
";




