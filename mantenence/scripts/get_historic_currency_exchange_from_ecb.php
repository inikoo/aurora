<?php

/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 19 February 2015 15:26:13 GMT+8, Flores, Indonesia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/



include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.CurrencyExchange.php';

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');
$software='Get_Products.php';
$version='V 1.0';
$Data_Audit_ETL_Software="$software $version";


$csv_file='eurofxref-hist.csv';
$handle_csv = fopen($csv_file, "r");
$__cols=array();
$counter=0;
while (($_cols = fgetcsv($handle_csv))!== false) {
	$counter++;
	if ($counter==1){
	continue;
	}
	$eurgbp=$_cols[8];
	$gbpeur=1/$eurgbp;
	$date=date('Y-m-d',strtotime($_cols[0]));
	//print "$date $eurgbp \n";
	
	$sql=sprintf("select count(*) as num from  `History Currency Exchange Dimension`  where `Currency Pair`='GBPEUR' and `Date`=%s",prepare_mysql($date));
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
		if($row['num']==0){
			$sql=sprintf("insert into `History Currency Exchange Dimension`  values (%s,'GBPEUR',%f)",prepare_mysql($date),$gbpeur);
			mysql_query($sql);
			print "$sql\n";
		}
	}
	
		$sql=sprintf("select count(*) as num from  `History Currency Exchange Dimension`  where `Currency Pair`='EURGBP' and `Date`=%s",prepare_mysql($date));
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
		if($row['num']==0){
			$sql=sprintf("insert into `History Currency Exchange Dimension`  values (%s,'EURGBP',%f)",prepare_mysql($date),$eurgbp);
			mysql_query($sql);
			print "$sql\n";
		}
	}
	
}



?>
