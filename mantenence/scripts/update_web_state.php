<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

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

//  $this->update_historic_sales_data();
 //     $this->update_sales_data();
  //    $this->update_same_code_sales_data();


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
//$stores=array(1,2,3);


 $sql="select `Product ID` from `Product Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
 $product=new Product('pid',$row['Product ID']);

  $product->update_web_state();
    
   // print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";

}


?>
