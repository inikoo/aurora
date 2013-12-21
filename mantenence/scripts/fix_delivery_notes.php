<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo Ltd
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
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



$sql="select * from `Order Dimension` where `Order Current Dispatch State`!='Dispatched' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order_data_id=preg_replace('/E/','',$row['Order Original Metadata']);
	$sql=sprintf("update ci_orders_data.orders set last_transcribed=NULL where id=%d",$order_data_id);
	print "$sql\n";
	mysql_query($sql);
}


exit;

$count_changed=0;

$sql="select * from `Delivery Note Dimension` where `Delivery Note Key`=894389";
$sql="select * from `Delivery Note Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$dn=new DeliveryNote($row['Delivery Note Key']);
	
	if($dn->data['Delivery Note State']=='Dispatched'){
	
	$sql=sprintf("select `Order Public ID`,`Delivery Note Quantity`,`Order Transaction Fact Key` from `Order Transaction Fact` where `Delivery Note Key`=%s  and `Current Dispatching State` in ('Packed')  ",
			$dn->id);
//print "$sql\n";
		$result2=mysql_query($sql);
		
		while ($row2=mysql_fetch_array($result2,MYSQL_ASSOC)  ) {

			//print $row['Order Public ID']."\n";

			$sql = sprintf("update  `Order Transaction Fact` set `Actual Shipping Date`=%s,`Shipped Quantity`=%f, `Current Dispatching State`=%s where   `Order Transaction Fact Key`=%d",
				prepare_mysql($dn->data['Delivery Note Date']),
				$row2['Delivery Note Quantity'],
				prepare_mysql('Dispatched'),
				$row2['Order Transaction Fact Key']
			);
			mysql_query($sql);
			
			$count_changed+=mysql_affected_rows();
			
			//print "$sql\n";
		}
	

	
	}


}
print $count_changed;
exit;



///////////////////////////////
// ES VERSION

$sql="select * from `Delivery Note Dimension` where `Delivery Note State`='' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order_data_id=preg_replace('/U/','',$row['Delivery Note Metadata']);
	$sql=sprintf("update ci_orders_data.orders set last_transcribed=NULL where id=%d",$order_data_id);
	print "$sql\n";
	mysql_query($sql);
}


/*
///////////////////////////////
// UK VERSION

$sql="select * from `Delivery Note Dimension` where `Delivery Note State`='' and `Delivery Note Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order_data_id=preg_replace('/U/','',$row['Delivery Note Metadata']);
	$sql=sprintf("update orders_data.orders set last_transcribed=NULL where id=%d",$order_data_id);
	print "$sql\n";
	mysql_query($sql);
}
*/


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";



?>
