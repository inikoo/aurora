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
include_once '../../class.Order.php';
include_once '../../class.Payment_Account.php';
include_once '../../class.Payment_Service_Provider.php';
include_once '../../class.Payment.php';

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

$inikoo_account=new Account(1);
//'For Sale','Out of Stock','Discontinued','Offline'
$sql="select `Order Key` from `Order Dimension` where  `Order Current Dispatch State` in  ('In Process by Customer')  order by `Order Key` ";
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)   ) {
	$order=new Order($row['Order Key']);

	$sql=sprintf("select OTF.`Product ID` ,OTF.`Product Code`from `Order Transaction Fact` OTF left join `Product Dimension` P  on (P.`Product ID`=OTF.`Product ID`) where `Order Key`=%d and `Product Web State`!='For Sale'  ",$order->id);
	$res2=mysql_query($sql);
//	print $sql;
	while ($row2=mysql_fetch_assoc($res2)   ) {
        $order->remove_out_of_stocks_from_basket($row2['Product ID']);
        print $order->data['Order Public ID'].' '.$row2['Product Code']."\n";
	}
	
/*

	$sql=sprintf("select OTF.`Product ID` ,OTF.`Product Code`from `Order Transaction Fact` OTF left join `Product Dimension` P  on (P.`Product ID`=OTF.`Product ID`) where `Order Key`=%d and `Product Web State`='For Sale'  ",$order->id);
	$res2=mysql_query($sql);
//	print $sql;
	while ($row2=mysql_fetch_assoc($res2)   ) {
        $order->restore_back_to_stock_to_basket($row2['Product ID']);
        print $order->data['Order Public ID'].' '.$row2['Product Code']."\n";
	}
	*/

}


?>
