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
include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

$sql="select * from `Customer Dimension` order by `Customer Key`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
 
  //print $row['Customer Key']."\n";
   $customer=new Customer($row['Customer Key']);
   //$store=new Store($customer->data['Customer Store Key']);
   // $customer->update_orders();
   //  $customer->update_orders();
   //     $store->update_customer_activity_interval();

   $customer->update_activity();
         $customer->update_is_new();
  



//     $customer->update_temporal_data();
  //$customer->update_activity();
  //$customer->update_full_search();
  print $customer->id."\t\t\r";
 }
 


$sql="select * from `Store Dimension`";
$result=mysql_query($sql);


while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


 $store=new Store($row['Store Key']);

$store->update_interval_sales();
$store->update_customers_data();

 }


?>