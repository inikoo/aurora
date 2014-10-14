<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$options='no_history';

$sql="select `Supplier Product ID` from `Supplier Product Dimension`  ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);

	$supplier_product->update_stock();
$supplier_product->update_days_available();
$supplier_product->update_up_today_sales();
$supplier_product->update_last_period_sales();
$supplier_product->update_interval_sales();

	print $row['Supplier Product ID']."\r";

}

?>
