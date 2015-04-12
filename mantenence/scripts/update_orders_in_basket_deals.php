<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 12 April 2015 14:00:43 BST, Sheffied, UK

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';
include_once '../../class.Payment_Account.php';
include_once '../../class.Payment.php';



error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;


$sql="select count(*) as total from `Order Dimension` where `Order Current Dispatch State`='In Process by Customer' order by `Order Date` desc";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}

$sql="select `Order Key` from `Order Dimension` where `Order Current Dispatch State`='In Process by Customer' order by `Order Date` desc";
$result=mysql_query($sql);
$counter=0;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$order=new Order($row['Order Key']);

	$order->update_number_items();
	$order->update_number_products();
	$order->update_insurance();

	$order->update_discounts_items();
	$order->update_item_totals_from_order_transactions();



	$order->update_shipping(false,false);
	$order->update_charges(false,false);
	$order->update_discounts_no_items(false);


	$order->update_deal_bridge();

	$order->update_deals_usage();

	$order->update_no_normal_totals();
	//print "xxx\n";
	$order->update_item_totals_from_order_transactions();
	$order->update_totals_from_order_transactions();
	$order->update_number_items();
	$counter++;
	//print $order->id." ".percentage($counter,$total)."  \r";

}




?>
