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





$corporate_currency='GBP';

$sql="select * from `Part Dimension`   order by `Part SKU`  ";

$resultxx=mysql_query($sql);
while ($rowxx=mysql_fetch_array($resultxx, MYSQL_ASSOC)   ) {

	$part=new Part($rowxx['Part SKU']);
	$part->update_stock_in_transactions();

print $part->sku."\r";

}









?>
