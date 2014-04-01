<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.PartLocation.php';
include_once '../../class.User.php';
include_once '../../class.InventoryAudit.php';
include_once '../../class.Warehouse.php';

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );


if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');



	

	$sql="select `Store Key`  from `Store Dimension` ";
	$result2=mysql_query($sql);
	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$store=new Store($row2['Store Key']);
		$store->update_sales_averages();
	}



	$sql="select `Product Department Key` from `Product Department Dimension` ";
	$result2=mysql_query($sql);
	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$department=new Department($row2['Product Department Key']);
		$department->update_sales_averages();
	}


	$sql="select `Product Family Key` from `Product Family Dimension` ";
	$result2=mysql_query($sql);
	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$family=new Family($row2['Product Family Key']);
		$family->update_sales_averages();
	}


$sql=sprintf('select `Product ID`  from `Product Dimension`    ');
	$res2=mysql_query($sql);
	//print "$sql\n";
	$count=0;
	while ($row2=mysql_fetch_assoc($res2)) {
		$product=new Product("pid",$row2['Product ID']);
		$department->update_sales_averages();
	}











?>
