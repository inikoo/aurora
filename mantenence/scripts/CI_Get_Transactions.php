<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Customer.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='costa';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$sql="select * from ci.orden ";
$result=mysql_query($sql);
while($_order=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  print $_order['public_id']."\n";
  $order_key=0;
  $invoice_key=0;
  $consolidated='No';
  $customer=new customer('id',$_order['customer_id']);
  $customer_name=$customer->data['Customer Name'];
  if($customer_name=='')
    $customer_name='?';
  $sql=sprintf("insert into `Order Dimension` (`Order Date`,`Order Public ID`,`Order File As`,`Order Customer Key`,`Order Customer Name`,`Order Store Key`,`Order Store Code`,`Order Items Net Amount`,`Order Shipping Net Amount`,`Order Charges Net Amount`,`Order Total Net Amount`,`Order Total Tax Amount`,`Order Total Amount`) values (%s,%s,%s,%d,%s,1,'AWR',%.2f,%.2f,%.2f,%.2f,%.2f,%.2f) "
	       ,prepare_mysql($_order['date_creation'])
	       ,prepare_mysql($_order['public_id'])
	       ,prepare_mysql($_order['public_id'])
	       ,$customer->id
	       ,prepare_mysql($customer_name)
	       ,$_order['items_charge']
	       ,$_order['shipping']
	       ,$_order['charges']
	       ,$_order['net']
	       ,$_order['vat']+$_order['vat2']
	       ,$_order['total']
	       );
  $index_date=$_order['date_creation'];
 if(!mysql_query($sql)){
   print "$sql\n";
   exit;
 }
 $date_invoiced='';
  if($_order['tipo']==2){
$consolidated='Yes';

$date_invoiced=$_order['date_invoiced'];

 $sql=sprintf("insert into `Invoice Dimension` (`Invoice Type`,`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Store Key`,`Invoice Store Code`,`Invoice Items Net Amount`,`Invoice Shipping Net Amount`,`Invoice Charges Net Amount`,`Invoice Total Net Amount`,`Invoice Total Tax Amount`,`Invoice Total Amount`) values ('Invoice',%s,%s,%s,%d,%s,1,'AWR',%.2f,%.2f,%.2f,%.2f,%.2f,%.2f) "
	       ,prepare_mysql($_order['date_invoiced'])
	       ,prepare_mysql($_order['public_id'])
	      ,prepare_mysql($_order['public_id'])
	      ,$customer->id
	      ,prepare_mysql($customer_name)
	       ,$_order['items_charge']
	       ,$_order['shipping']
	       ,$_order['charges']
	       ,$_order['net']
	       ,$_order['vat']+$_order['vat2']
	       ,$_order['total']
	       );
   $index_date=$_order['date_invoiced'];
 if(!mysql_query($sql)){
   print "$sql\n";
   exit;
 }
 
 $sql="select ordered,product_id,p.code,dispatched,discount,charge from ci.transaction left join ci.product p on (p.id=product_id) where order_id=".$_order['id'];


 $res2=mysql_query($sql);
  while($row2=mysql_fetch_array($res2, MYSQL_ASSOC)   ){
    // print " ".$row2['code']."\n";
    
    $product=new Product('code',$row2['code']);
    if(!$product->id){
      exit("product not found");
    }
    
    // print "x \n";
    $net=$row2['charge'];
    $f_disc=1-$row2['discount'];
    if($f_disc==0){
      $gross=$product->data['Product Price']*$row2['dispatched'];
    }else{
      $gross=$net/$f_disc;
    }
    $discount=$gross-$net;
    
   
     $sql="select code,name,supplier_id,price,sup_code from ci.product2supplier left join ci.supplier on (ci.supplier.id=supplier_id) where product_id=".$row2['product_id'];
     // print "$sql\n";
  $res22=mysql_query($sql);
  $supplier_cost='';
  if($row22=mysql_fetch_array($res22, MYSQL_ASSOC)   ){
    $supplier_cost=$row22['price'];

    
  }
  if(!is_numeric($supplier_cost) or $supplier_cost<=0)
    $cost=0.6*$product->data['Product Price'];
  else
    $cost=$row2['dispatched']*$product->data['Product Units Per Case']*$supplier_cost;
  
  $sql=sprintf("insert into `Order Transaction Fact` (`Order Key`,`Invoice Key`,`Order Public ID`,`Invoice Public ID`,`Product Key`,`Customer Key`,`Store Key`,`Order Quantity`,`Invoice Quantity`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Cost Supplier`,`Consolidated`,`Invoice Date`,`Order Date`,`Shipped Quantity`,`Order Last Updated Date`) values (%d,%d,%s,%s,%d,%d,1,%f,%f,%.2f,%.2f,%.2f,'%s',%s,%s,%s,%s)"
	       ,$order_key
	       ,$invoice_key
	       ,$_order['public_id']
	       ,$_order['public_id']
	       ,$product->id
	       ,$customer->id
	       ,$row2['ordered']
	       ,$row2['dispatched']
	       ,$gross
	       ,$discount
	       ,$cost
	       ,$consolidated
	       ,prepare_mysql($date_invoiced)
	       ,prepare_mysql($_order['date_creation'])
	       ,$row2['dispatched']
	       
	       ,prepare_mysql($index_date)
	       );
  if(!mysql_query($sql)){
   print "$sql\n";
   exit;
 }
  }
  }
}
