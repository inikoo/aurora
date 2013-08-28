<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Order.php';

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

/*
$sql="select `Order Key`from `Order Dimension`  ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$order=new Order($row['Order Key']);
		$order->update_xhtml_delivery_notes();//exit;
	$order->update_no_normal_totals();
}

exit;
*/

//$sql="select `Metadata` from `Order No Product Transaction Fact` where `Order Key`=0  or `Order Key` Is NULL ";

$sql="select `Order Post Transaction Metadata` as Metadata from `Order Post Transaction Dimension`  ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	if (preg_match('/U/',$row['Metadata'])) {
		$order_data_id=preg_replace('/U/','',$row['Metadata']);
		$sql=sprintf("update orders_data.orders set last_transcribed=null where orders_data.orders.id =%d",$order_data_id);
		print "$sql\n";
		mysql_query($sql);
	}
	if (preg_match('/F/',$row['Metadata'])) {
		$order_data_id=preg_replace('/F/','',$row['Metadata']);
		$sql=sprintf("update fr_orders_data.orders set last_transcribed=null where orders_data.orders.id =%d",$order_data_id);
		print "$sql\n";
		mysql_query($sql);
	}
	if (preg_match('/D/',$row['Metadata'])) {
		$order_data_id=preg_replace('/D/','',$row['Metadata']);
		$sql=sprintf("update de_orders_data.orders set last_transcribed=null where orders_data.orders.id =%d",$order_data_id);
		print "$sql\n";
		mysql_query($sql);
	}
	if (preg_match('/I/',$row['Metadata'])) {
		$order_data_id=preg_replace('/I/','',$row['Metadata']);
		$sql=sprintf("update it_orders_data.orders set last_transcribed=null where orders_data.orders.id =%d",$order_data_id);
		print "$sql\n";
		mysql_query($sql);
	}	
}




?>
