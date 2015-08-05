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



$sql=sprintf("select `Product ID`  from `Product History Bridge` group by `Product ID`");
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);



	if (!$product->id) {

		$sql=sprintf("select `History Key` from `Product History Bridge` where `Product ID`=%d ",$row['Product ID']);
		$result2=mysql_query($sql);
		while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
			$sql=sprintf("delete from `History Dimension` where `History Key`=%d",$row2['History Key']);
			mysql_query($sql);
			//print "$sql\n";
		}
		$sql=sprintf("delete from `Product History Bridge` where `Product ID`=%d",$row['Product ID']);
		print "xx $sql\n";
		mysql_query($sql);

	}

}



//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$product=new Product('pid',$row['Product ID']);
	$product->update_links_to_part_data();
	if (count($product->get_current_part_skus())>1   ) {
		$sql=sprintf("select `History Key` from `Product History Bridge` where `Product ID`=%d ",$product->pid);
		$result2=mysql_query($sql);
		while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
			$sql=sprintf("delete from `History Dimension` where `History Key`=%d",$row2['History Key']);
			mysql_query($sql);
			//print "$sql\n";
		}
		$sql=sprintf("delete from `Product History Bridge` where `Product ID`=%d",$product->pid);
		//print "$sql\n";
		mysql_query($sql);

	}



}








?>
