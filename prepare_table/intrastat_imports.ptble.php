<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13-08-2019 16:00:32 MYT Kula Lumpur, Malaysia
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


$where = ' where `Supplier Contact Address Country 2 Alpha Code` in ('.$intrastat_countries.')  and `Supplier Delivery Transaction State`="Placed" and `Supplier Delivery Key`>0 ';


//print_r($parameters);

if (isset($parameters['period'])) {


    include_once 'utils/date_functions.php';


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $where_interval_dn      = prepare_mysql_dates($from, $to, '`Supplier Delivery Last Updated Date`');

    $where .= $where_interval_dn['mysql'];



}

/*
$parameters['invoices_vat'] = (int)$parameters['invoices_vat'];
$parameters['invoices_no_vat'] = (int)$parameters['invoices_no_vat'];
$parameters['invoices_null'] = (int)$parameters['invoices_null'];


if ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  ";

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` not in ('EX','OUT') ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
    $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` in ('EX','OUT') ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
    $where .= " and  I.`Invoice Key` is null  ";

} elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
    $where .= " and   (  I.`Invoice Key` is null  or   ( I.`Invoice Key`>0    and I.`Invoice Tax Code` not in ('EX','OUT') )  ) ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {
    $where .= " and   (  I.`Invoice Key` is null   or  I.`Invoice Tax Code` in ('EX','OUT')    ) ";

} elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
    $where .= " and false ";

}
*/

$wheref = '';
if ($parameters['f_field'] == 'commodity' and $f_value != '') {
    $wheref .= " and  `Part Tariff Code` like '".addslashes($f_value)."%'    ";
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

    $order = '`Supplier Contact Address Country 2 Alpha Code`';
}


$group_by = 'group by LEFT(`Part Tariff Code`,8),`Supplier Contact Address Country 2 Alpha Code`';

$table =
    ' `Purchase Order Transaction Fact` OTF left join `Part Dimension` P on (P.`Part SKU`=OTF.`Purchase Order Transaction Part SKU`) left join `Supplier Dimension` S on (S.`Supplier Key`=OTF.`Supplier Key`)  ';

$sql_totals = "";


$fields = "
sum(`Supplier Delivery Placed Units`) as items,
count(distinct OTF.`Purchase Order Transaction Part SKU`) as parts,
count(distinct OTF.`Supplier Delivery Key`) as orders,
sum( `Supplier Delivery Net Amount`+ `Supplier Delivery Extra Cost Amount` + `Supplier Delivery Extra Cost Account Currency Amount`) as value,
	sum(`Supplier Delivery Placed Units`*`Part Package Weight`*`Part Units Per Package`) as weight ,
	LEFT(`Part Tariff Code`,8) as tariff_code, min(`Supplier Delivery Last Updated Date`) as min_date , `Supplier Delivery Last Updated Date` , `Supplier Contact Address Country 2 Alpha Code`,
	group_concat(`Supplier Delivery Key`),group_concat(distinct date_format(`Supplier Delivery Last Updated Date`,'%y%m')) as monthyear 
";




