<?php
/*
@author Raul Perusquia <rulovico@gmail.com>
Created: 29 April 2015 17:19:18 CEST, Cala de Mijas,  Spain 

Copyright (c) 2015 Inikoo Ltd
*/

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

$sql="select `Supplier Product ID` from `Supplier Product Dimension` ";

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)   ) {
	$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);


	$supplier_product->update_store_as();

	print $row['Supplier Product ID']."\r";

}

?>
