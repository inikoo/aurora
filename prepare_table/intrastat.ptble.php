<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 December 2017 at 12:29:36 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

/*

$where=sprintf("where `Current Dispatching State`='Dispatched' %s and `Destination Country 2 Alpha Code` in ('AT','BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES') ",
               $date_interval['mysql']
);


$sql="select  sum(`Delivery Note Quantity`*`Product Units Per Case`) as items,sum(`Order Bonus Quantity`) as bonus,GROUP_CONCAT(DISTINCT ' <a href=\"invoice.php?id=',`Invoice Key`,'\">',`Invoice Public ID`,'</a>' ) as invoices ,
	sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`+`Invoice Transaction Insurance Amount`+`Invoice Transaction Net Adjust`)) as value ,
	sum(`Delivery Note Quantity`*`Product Package Weight`) as weight ,
	LEFT(`Product Tariff Code`,8) as tariff_code, date_format(`Invoice Date`,'%y%m') as monthyear ,`Destination Country 2 Alpha Code`
	from
	`Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
	$where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code`  order by   $order $order_direction  limit $start_from,$number_results";

*/

switch($account->get('Account Country 2 Alpha Code')) {
case 'GB':
    $_exclude='"GB","IM"';

    break;
default:
    $_exclude='"'.$account->get('Account Country 2 Alpha Code').'"';
    break;
}

$countries = '';
$sql       = sprintf(
    'SELECT `Country 2 Alpha Code`  FROM kbase.`Country Dimension`  WHERE `EC Fiscal VAT Area`="Yes" AND `Country 2 Alpha Code` NOT IN (%s)  ORDER BY `Country Name`',$_exclude
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $countries .= "'".$row['Country 2 Alpha Code']."',";

    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$countries = preg_replace('/\,$/', '', $countries);


$where = ' where `Delivery Note Address Country 2 Alpha Code` in ('.$countries.')  and DN.`Delivery Note Key` is not null  ';


if (isset($parameters['period']) ) {


    include_once 'utils/date_functions.php';


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $where_interval = prepare_mysql_dates($from, $to, '`Delivery Note Date`');


    $where .= $where_interval['mysql'];


}


$wheref = '';
if ($parameters['f_field'] == 'commodity' and $f_value != '') {
    $wheref .= " and  `Product Tariff Code` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;



if ($order == 'period') {
    $order = 'min_date';
}elseif ($order == 'tariff_code') {
    $order = 'tariff_code';
}elseif ($order == 'tariff_code') {
    $order = 'tariff_code';
}elseif ($order == 'value') {
 $order = 'value';
}elseif ($order == 'items') {
    $order = 'items';
}elseif ($order == 'products') {
    $order = 'products';
}elseif ($order == 'orders') {
    $order = 'orders';
}else{

    $order='`Delivery Note Address Country 2 Alpha Code`';
}


$group_by
    = 'group by `Product Tariff Code`,`Delivery Note Address Country 2 Alpha Code`';

$table = ' `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`) ';

$sql_totals = "";


$fields = "
sum(`Delivery Note Quantity`*`Product Units Per Case`) as items,
count(distinct OTF.`Product ID`) as products,
count(distinct OTF.`Order Key`) as orders,

sum(`Order Transaction Amount`) as value,
	sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`) as weight ,
	LEFT(`Product Tariff Code`,8) as tariff_code, min(`Delivery Note Date`) as min_date , `Delivery Note Date` , `Delivery Note Address Country 2 Alpha Code`,
	group_concat(DN.`Delivery Note Key`),group_concat(distinct date_format(`Delivery Note Date`,'%y%m')) as monthyear 
";


?>
