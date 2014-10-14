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

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           


print "Consignee Code,Consignee Name,Address Line 1,Address Line 2,Address Line 3,Town,County,Postcode,Contact Name,Contact Tel No,Contact Fax No,Contact Email,Tags,Description,Service Level,Weight,Special Instructions,LastAccessed\n";

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
  
  $tel=($customer->data['Customer Main XHTML Telephone']==''?$customer->data['Customer Main XHTML Mobile']:$customer->data['Customer Main XHTML Telephone']);
  
  $delivery_address=new Address($customer->data['Customer Main Delivery Address Key']);
  
  if($delivery_address->data['Address Country Code']!='GBR'){
    continue;
    
  }
    
  
  $delivery_address_lines=$delivery_address->display('3lines');
  
  
  $delivery_tel=$delivery_address->get_formated_principal_telephone();
  if($delivery_tel=='')
    $delivery_tel=$tel;
  
   $data=array();

   $data[]=$customer->id;
   $data[]=preg_replace('/\,/',' ',$customer->data['Customer Name']);
   $data[]=preg_replace('/\,/',' ',$delivery_address_lines[1]);
   $data[]=preg_replace('/\,/',' ',$delivery_address_lines[2]);
   $data[]=preg_replace('/\,/',' ',$delivery_address_lines[3]);
   $data[]=preg_replace('/\,/',' ',$delivery_address->display('Town with Divisions'));
   $data[]=preg_replace('/\,/',' ',$delivery_address->display('Country Divisions'));
   $data[]=preg_replace('/\,/',' ',$delivery_address->data['Address Postal Code']);
   $data[]=preg_replace('/\,/',' ',$customer->data['Customer Main Contact Name']);
   $data[]=preg_replace('/\,/',' ',$delivery_tel);
   $data[]=preg_replace('/\,/',' ',$customer->data['Customer Main XHTML FAX']);
   $data[]=preg_replace('/\,/',' ',$customer->data['Customer Main Plain Email']);
   $data[]='';
   $data[]='';
   $data[]='';
   $data[]='';
   $data[]='';
   $data[]='01/01/2010 00:00:00';

   print join(',',$data)."\n";
   

//$store=new Store($customer->data['Customer Store Key']);
   // $customer->update_orders();
   //  $customer->update_orders();
   //     $store->update_customer_activity_interval();

   // $customer->update_activity();
   //     $customer->update_is_new();
  



//     $customer->update_temporal_data();
  //$customer->update_activity();
  //$customer->update_full_search();
   // print $customer->id."\t\t\r";
 }
 


$sql="select * from `Store Dimension`";
$result=mysql_query($sql);


while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


 $store=new Store($row['Store Key']);

$store->update_interval_sales();
$store->update_customers_data();

 }


?>