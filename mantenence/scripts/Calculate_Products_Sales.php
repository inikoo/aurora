<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('Europe/London');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           




//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$stores=array(1,2,3);




// $sql="select * from `Product History Dimension` PH  left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)   where `Product Store Key` in (".join(',',$stores).")  order by `Product Key`  desc ";
  $sql="select * from `Product History Dimension` PH  order by `Product Key` desc  ";


$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result)   ){
  $product=new Product('id',$row['Product Key']);
 // if($product->data['Product Code']=='JuteS-01'){
  $product->load('sales');
$product->update_parts();
  print $row['Product Key']."\t\t ".$product->data['Product Code']." \r";

//}


 
}


?>