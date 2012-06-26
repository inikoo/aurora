<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
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


$sql="select * from `Ship To Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
//	$address=$row['Ship To XHTML Address'];
	$ship_to=new Ship_To($row['Ship To Key']);
	$address=$ship_to->get_xhtml_address();
	$sql=sprintf("update `Ship To Dimension` set `Ship To XHTML Address`=%s where `Ship To Key` =%d",
		prepare_mysql($address),
		$row['Ship To Key']

	);
	mysql_query($sql);

}



exit;

$sql="select * from `Ship To Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$address=$row['Ship To XHTML Address'];
	$address=strip_tags($address,'<br>');
	$sql=sprintf("update `Ship To Dimension` set `Ship To XHTML Address`=%s where `Ship To Key` =%d",
		prepare_mysql($address),
		$row['Ship To Key']

	);
	mysql_query($sql);

}



//Order XHTML Ship Tos

$sql="select * from `Order Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$address=$row['Order XHTML Ship Tos'];
	$address=strip_tags($address,'<br>');
	$sql=sprintf("update `Order Dimension` set `Order XHTML Ship Tos`=%s where `Order Key` =%d",
		prepare_mysql($address),
		$row['Order Key']
	);
	mysql_query($sql);
}

$sql="select * from `Invoice Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$address=$row['Invoice XHTML Address'];
	$address=strip_tags($address,'<br>');
	$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Address`=%s where `Invoice Key` =%d",
		prepare_mysql($address),
		$row['Invoice Key']
	);
	mysql_query($sql);
}

// Delivery Note XHTML Ship To

$sql="select * from `Delivery Note Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$address=$row['Delivery Note XHTML Ship To'];
	$address=strip_tags($address,'<br>');
	$sql=sprintf("update `Delivery Note Dimension` set `Delivery Note XHTML Ship To`=%s where `Delivery Note Key` =%d",
		prepare_mysql($address),
		$row['Delivery Note Key']
	);
	mysql_query($sql);
}
















?>
