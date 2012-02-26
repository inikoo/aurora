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

$where=' where `Part SKU`=293 ';
$sql="select count(*) as total from `Part Location Dimension`  $where ";
$result=mysql_query($sql);
$total=1;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}


$i=0;
$sql="select * from `Part Location Dimension` $where ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$partlocation=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
	$partlocation->update_stock();
	$i++;
	print sprintf("%.2f",100*($i/$total))."\r";
}


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select `Part SKU` from `Part Dimension`  $where ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);
	$part->update_stock();

	print $row['Part SKU']."\r";


}


?>
