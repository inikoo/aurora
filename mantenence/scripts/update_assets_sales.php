<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
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
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;





$sql="select * from `Product Family Dimension` ";

//print $sql;
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $family=new Family($row['Product Family Key']);
//  $family->update_sales_default_currency();
  $family->update_product_data();
  //$family->update_sales_data();
 // print $row['Product Family Code']."        \r";
 }

mysql_free_result($result);





/*
$sql="select * from `Store Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


 $store=new Store($row['Store Key']);
  $store->update_up_today_sales();
  $store->update_customer_activity_interval();
$store->update_interval_sales();
$store->update_last_period_sales();

 }
$sql="select * from `Product Department Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
$department=new Department($row['Product Department Key']);
 // $department=new Department(95);
    $department->update_sales_default_currency();

  $department->update_customers();
  $department->load('sales');
  $department->load('products_info');
  $department->update_families();
 

//  print $department->data['Product Department Code']."\n";
 }
mysql_free_result($result);

 $sql="select `Product ID` from `Product Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
 $product=new Product('pid',$row['Product ID']);
//$product=new Product('pid',37949);
 $product->update_sales_data();
 // $product->update_full_search();
//  print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";

}


 $sql="select * from `Product History Dimension` PH  order by `Product Key` desc  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
  $product=new Product('id',$row['Product Key']);
 $product->update_historic_sales_data();
  //print "PH ".$row['Product Key']."\t\t ".$product->data['Product Code']." \r";

}


$sql="select * from `Part Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $part=new Part('sku',$row['Part SKU']);
$part->update_estimated_future_cost();
  $part->load('used in');
  $part->load('supplied by');
  $part->update_up_today_sales();
$part->update_interval_sales();
$part->update_last_period_sales();

}
*/




?>
