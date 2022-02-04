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


$where = ' where `Supplier Delivery Parent Country Code` in ('.$intrastat_countries
    .')  and D.`Supplier Delivery Key` is not null and `Supplier Delivery State`="InvoiceChecked" and `Supplier Delivery Invoice Public ID` is not null and `Supplier Delivery Invoice Date` is not null  and `Supplier Delivery Placed Units`>0 ';



//print_r($parameters);

if (isset($parameters['period'])) {


    include_once 'utils/date_functions.php';


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $where_interval_dn = prepare_mysql_dates($from, $to, '`Purchase Order Transaction Invoice Date`');

    $where .= $where_interval_dn['mysql'];



}



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

    $order = '`Purchase Order Transaction Invoice Date`';
}


$group_by = 'group by LEFT(`Part Tariff Code`,8),`Purchase Order Transaction Invoice Date`';

$table =
    ' `Purchase Order Transaction Fact` OTF left join `Supplier Delivery Dimension` D on (OTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) left join `Part Dimension` P on (P.`Part SKU`=OTF.`Purchase Order Transaction Part SKU`) left join `Supplier Dimension` S on (S.`Supplier Key`=OTF.`Supplier Key`)  ';

$sql_totals = "";


$fields = "
sum(`Supplier Delivery Placed Units`) as items,`Supplier Delivery Parent Country Code`,
count(distinct OTF.`Purchase Order Transaction Part SKU`) as parts,
count(distinct OTF.`Supplier Delivery Key`) as orders,
sum( `Supplier Delivery Extra Cost Account Currency Amount`+`Supplier Delivery Currency Exchange`*( `Supplier Delivery Net Amount`+`Supplier Delivery Extra Cost Amount` ) ) as value,



	sum(`Supplier Delivery Placed Units`*`Part Package Weight`/`Part Units Per Package`) as weight ,
	LEFT(`Part Tariff Code`,8) as tariff_code, min(`Purchase Order Transaction Invoice Date`) as min_date , `Purchase Order Transaction Invoice Date` , `Purchase Order Transaction Invoice Date`,
	group_concat(OTF.`Supplier Delivery Key`),group_concat(distinct date_format(`Purchase Order Transaction Invoice Date`,'%y%m')) as monthyear 
";




