<?php

/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 16 April 2015 09:56:03 BST Sheffield, UK

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


$currencies=array('EUR','PLN','GBP');

foreach ($currencies as $currency1) {
	foreach ($currencies as $currency2) {

		if ($currency1!=$currency2){
			
			$date=gmdate('Y-m-d');
			$exchange=get_currency_other($currency1,$currency2);
			
			$sql=sprintf("insert into `History Currency Exchange Dimension` values (%s,%s%s,%f)  ON DUPLICATE KEY UPDATE `Exchange`=%f ",
			prepare_mysql($date),$currency1,$currency2,$exchange,$exchange);
			//mysql_query($sql);
			print "$sql\n";
		}

	}

}




?>
