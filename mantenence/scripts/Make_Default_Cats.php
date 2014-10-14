<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');




//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
  
  $product=new Product($row['Product Key']);
  $sql=sprintf("insert into `Product Category Bridge` values (%d,13)",$product->id);
  //  print $sql;exit;
  mysql_query($sql);
  $sql=sprintf("insert into `Product Category Bridge` values (%d,14)",$product->id);
  mysql_query($sql);
  $sql=sprintf("insert into `Product Category Bridge` values (%d,15)",$product->id);
  mysql_query($sql);
 }

?>