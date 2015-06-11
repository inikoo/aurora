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


$sql=sprintf("select P.`Product ID`,P.`Product Code` from `Product Dimension` P left join `Product Data Dimension` D on (P.`Product ID`=D.`Product ID`)  where  `Product Main Type`='Sale' and `Product Web State`  in ('For Sale','Out of Stock') and `Product 1 Year Acc Customers`>0  order by `Product 1 Year Acc Customers` desc  "
);
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);
	print $product->data['Product ID'].' '.$product->data['Product Code']."\n";

	$product->update_sales_correlatations('Same Family');


}


?>
