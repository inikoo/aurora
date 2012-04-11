<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Invoice.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

$sql="select * from `Delivery Note Dimension` where   `Delivery Note State` not in ('Dispatched','Cancelled') and   `Delivery Note Store Key`=1";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);

$sql=sprintf("update orders_data.orders orders_data.orders set timestamp=0 where id=%d",$id);
print "$sql\n";
mysql_query($sql);


$sql=sprintf("update orders_data.orders set last_transcribed=NULL where id=%d",$id);

mysql_query($sql);



}


$sql="select * from `Delivery Note Dimension` where   `Delivery Note State` not in ('Dispatched','Cancelled')  and `Delivery Note Store Key`=3";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);
$sql=sprintf("update de_orders_data.orders set last_transcribed=NULL where id=%d",$id);
mysql_query($sql);
}

$sql="select * from `Delivery Note Dimension` where   `Delivery Note State` not in ('Dispatched','Cancelled') and  `Delivery Note Store Key`=5";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);
$sql=sprintf("update fr_orders_data.orders set last_transcribed=NULL where id=%d",$id);
mysql_query($sql);
}

$sql="select * from `Delivery Note Dimension` where   `Delivery Note State` not in ('Dispatched','Cancelled') and  `Delivery Note Store Key`=7";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);
$sql=sprintf("update pl_orders_data.orders set last_transcribed=NULL where id=%d",$id);
mysql_query($sql);
}
$sql="select * from `Delivery Note Dimension` where   `Delivery Note State` not in ('Dispatched','Cancelled') and  `Delivery Note Store Key`=9";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$id=preg_replace('/[^\d]/i','',$row['Delivery Note Metadata']);
$sql=sprintf("update it_orders_data.orders set last_transcribed=NULL where id=%d",$id);
mysql_query($sql);

}


?>