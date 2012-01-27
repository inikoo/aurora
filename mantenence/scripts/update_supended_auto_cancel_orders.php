<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo Ltd

date_default_timezone_set('UTC');


include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Deal.php';

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

define("TIMEZONE",'Europe/London');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');
//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'

//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql=sprintf("select * from `Order Dimension` where `Order Current Dispatch State` in ('In Process','Submitted by Customer','Ready to Pick','Unknown','Suspended') and `Order Current Payment State`='Waiting Payment' and `Order Date`<=%s ",
	prepare_mysql(gmdate('Y-m-d H:i:s',strtotime('now -6 month')))
);;
//print "$sql\n";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order=new Order($row['Order Key']);
	print $order->data['Order Public ID'].' '.$order->data['Order Date']."\n";
	$order->cancel(_('Order automatically cancelled'),gmdate("Y-m-d H:i:s",strtotime($order->data['Order Date']." +6 month")));
}


$sql=sprintf("select * from `Order Dimension` where `Order Current Dispatch State` in ('In Process','Submitted by Customer','Ready to Pick','Unknown') and `Order Current Payment State`='Waiting Payment' and `Order Date`<=%s ",
	prepare_mysql(gmdate('Y-m-d H:i:s',strtotime('now -3 month')))
);;
print "$sql\n";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order=new Order($row['Order Key']);
	print $order->data['Order Public ID'].' '.$order->data['Order Date']."\n";
	$order->suspend(_('Order automatically suspended'),gmdate("Y-m-d H:i:s",strtotime($order->data['Order Date']." +3 month")));
}





?>
