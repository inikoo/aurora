<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo Ltd
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');






$sql="select `Invoice Key`,`Invoice Paid`,`Invoice Paid Date`,`Invoice Main Payment Method` from `Invoice Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	if ($row['Invoice Paid']=='yes') {



		$sql = sprintf( "update  `Order No Product Transaction Fact`  set `Payment Method`=%s,`Transaction Outstanding Net Amount Balance`=0,`Transaction Outstanding Tax Amount Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s where `Invoice Key`=%d "
			,prepare_mysql($row['Invoice Main Payment Method'])
			,prepare_mysql($row['Invoice Paid Date'])
			,$row['Invoice Key']);

		mysql_query( $sql );
		

	}







}



?>
