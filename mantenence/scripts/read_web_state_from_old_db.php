<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
date_default_timezone_set('UTC');

error_reporting(E_ALL);
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



$dns_host='192.168.0.2';
$dns_db='aw_old';
$dns_user='raul';

$con2=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con2) {
            print "Error can not connect with database server\n";
            exit;
}
//$dns_db='dw';
$db2=@mysql_select_db($dns_db, $con2);
if (!$db2) {
            print "Error can not access the database\n";
            exit;
}




require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

/*
$sql=sprintf('select `Product ID` from `Product Dimension` order by `Product ID` ');
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);

	$product->update_web_configuration('Online Auto');
	print $product->pid."\r";
}
*/

$sql=sprintf("select id,code,stock,condicion,web_tipo  from aw_old.product order by code  ");
$result2a=mysql_query($sql,$con2);
while ($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

		print $row2a['code']."\r";

    
	$sql=sprintf('select `Product ID` from `Product Dimension` where `Product Code`=%s',prepare_mysql($row2a['code']));
	$result=mysql_query($sql,$con);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$product=new Product('pid',$row['Product ID']);


		switch ($row2a['web_tipo']) {
		case 'HD':
			$product->update_web_configuration('Offline');
			break;
		case 'O':
			$product->update_web_configuration('Online Force Out of Stock');
			break;
		case 'Y':
			$product->update_web_configuration('Online Force For Sale');
			break;

		default:
			$product->update_web_configuration('Online Force For Sale');
			//$product->update_web_state();
			break;
		}


	}


}



$sql=sprintf('select `Product ID` from `Product Dimension` where `Product Code` like "%%-BN" ');
$result=mysql_query($sql,$con);
//print "$sql\n";
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);

	$product->update_web_configuration('Offline');
}




?>
