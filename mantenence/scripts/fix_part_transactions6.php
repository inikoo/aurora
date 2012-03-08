<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.PartLocation.php';

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


	$sql=sprintf("select * from `Inventory Transaction Fact` ITF    where  `Inventory Transaction Type` in ('Associate','Disassociate') and `Relations`=''");
	// print $sql;

	$result2=mysql_query($sql);

	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		print $row2['Inventory Transaction Key']."\n";
	
	$sql=sprintf("select * from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type` in ('Audit') and `Date`=%s  and `Part SKU`=%d and `Location Key`=%d ",prepare_mysql($row2['Date']),$row2['Part SKU'],$row2['Location Key']);
	// print $sql;

	$result3=mysql_query($sql);

	while ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
		
	$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d where `Inventory Transaction Key`=%d",$row3['Inventory Transaction Key'],$row2['Inventory Transaction Key']);
print "$sql\n";
mysql_query($sql);
	
	

}
	
	

}

?>
