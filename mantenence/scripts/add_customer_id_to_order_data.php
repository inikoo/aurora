<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');
include_once('../../class.Node.php');
include_once('../../class.Category.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

$stores=array('u'=>'','d'=>'de_','f'=>'fr_','p'=>'pl_');
$stores=array('d'=>'de_','f'=>'fr_','p'=>'pl_');

foreach($stores as $metadata_key=>$table_prefix){
$sql="select `Delivery Note Customer Key` as customer_id,`Delivery Note Metadata` as od_id from `Delivery Note Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   if(preg_match('/^'.$metadata_key.'\d+/i',$row['od_id'])){
        $sql=sprintf("update %sorders_data.orders set customer_id=%d where id=%d",
            $table_prefix,
            $row['customer_id'],
            preg_replace('/^'.$metadata_key.'/i','',$row['od_id'])
            );
        mysql_query($sql);
   }
 }

$sql="select `Invoice Customer Key` as customer_id,`Invoice Metadata` as od_id from `Invoice Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   if(preg_match('/^'.$metadata_key.'\d+/i',$row['od_id'])){
        $sql=sprintf("update %sorders_data.orders set customer_id=%d where id=%d",
             $table_prefix,
            $row['customer_id'],
            preg_replace('/^'.$metadata_key.'/i','',$row['od_id'])
            );
        mysql_query($sql);
   }
 }

$sql="select `Order Customer Key` as customer_id,`Order Original Metadata` as od_id from `Order Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   if(preg_match('/^'.$metadata_key.'\d+/i',$row['od_id'])){
        $sql=sprintf("update %sorders_data.orders set customer_id=%d where id=%d",
             $table_prefix,
            $row['customer_id'],
            preg_replace('/^'.$metadata_key.'/i','',$row['od_id'])
            );
        mysql_query($sql);
   }
 }
}


?>