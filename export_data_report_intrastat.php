<?php
/*

Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2012, Inikoo

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once 'common.php';


$line = '';
$data = '';
$header = '';
$my_exported_data=array();
$exported_data = array();

$data='';

$conf=$_SESSION['state']['report_intrastat'];

$conf_table='report_intrastat';



if (isset( $_REQUEST['o']))
	$order=$_REQUEST['o'];
else
	$order=$conf['order'];
if (isset( $_REQUEST['od']))
	$order_dir=$_REQUEST['od'];
else
	$order_dir=$conf['order_dir'];

if (isset( $_REQUEST['f_field']))
	$f_field=$_REQUEST['f_field'];
else
	$f_field=$conf['f_field'];

if (isset( $_REQUEST['f_value']))
	$f_value=$_REQUEST['f_value'];
else
	$f_value=$conf['f_value'];




$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

if (isset( $_REQUEST['y']))
	$y=$_REQUEST['y'];
else
	$y=$conf['y'];


if (isset( $_REQUEST['m']))
	$m=$_REQUEST['m'];
else
	$m=$conf['m'];


$filename = "intrastat_$m$y.csv";

header('Content-Type: application/csv; iso-8859-1');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

$output = fopen('php://output', 'w');




$where=sprintf("where `Current Dispatching State`='Dispatched' and MONTH(`Invoice Date`)=%d  and YEAR(`Invoice Date`)=%d and `Destination Country 2 Alpha Code` in ('AT','BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES') ",$m,$y);


$wheref='';
if ($f_field=='tariff_code'  and $f_value!='')
	$wheref.=" and `Product Tariff Code` like '".addslashes($f_value)."%'";



if ($order=='tariff_code')
	$order='`Product Tariff Code`';

elseif ($order=='value') {
	$order="value";
}elseif ($order=='weight') {
	$order="weight";
}elseif ($order=='country_2alpha_code') {
	$order="`Destination Country 2 Alpha Code` ,`Product Tariff Code`";
}
else {
	$order='`Product Tariff Code`';
}

$sql="select sum(`Shipped Quantity`*`Product Units Per Case`) as items,sum(`Order Bonus Quantity`) as bonus, sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)) as value , sum(`Shipped Quantity`*`Product Net Weight`) as weight , LEFT(`Product Tariff Code`,8) as tariff_code, date_format(`Invoice Date`,'%y%m') as monthyear ,`Destination Country 2 Alpha Code` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code`  order by   $order $order_dir ";
$result=mysql_query($sql);
$data=array();
//print $sql;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {


	$data[]=array(
		'tariff_code'=>$row['tariff_code'],

		'monthyear'=>$row['monthyear'],
		'value'=>round($row['value']),
		'weight'=>ceil($row['weight']),
		'country_2alpha_code'=>$row['Destination Country 2 Alpha Code'],
		'items'=>ceil($row['items']),
		'bonus'=>ceil($row['bonus'])



	);
}

$fields=array(_('Comodity Code'),_('Period'),_('Value'),_('Net Mass'),_('Country'),_('Items'),_('Bonus'));
fputcsv($output, $fields);

foreach ($data as $fields) {
	fputcsv($output, $fields);
}


unset($my_exported_data);
unset($exported_data);




?>
