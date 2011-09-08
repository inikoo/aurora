<?php
include_once('../../app_files/db/dns.php');
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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           




//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$stores=array(1,2,3);




// $sql="select * from `Product History Dimension` PH  left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)   where `Product Store Key` in (".join(',',$stores).")  order by `Product Key`  desc ";
  $sql="select * from `Product Dimension` ";


$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result)   ){
  $product=new Product('pid',$row['Product ID']);
  
  
    
  if($product->data['Product 1 Year Acc Quantity Ordered']==0 and (strtotime($product->data['Product Valid From'])<strtotime('today -1 year')    )){
    $sql=sprintf("select id,code  from aw_old.product  where product.code=%s and (stock=0 or stock<0 or stock is null)   ",prepare_mysql($product->data['Product Code']));
    $result2a=mysql_query($sql);
    if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
      $state='Discontinued';
      $discontinued_state='No Applicable';
      $record_state='Normal';
      $web_state='Offline';
      $discontinued_state='Yes';

     

      $sql=sprintf("update `Product Dimension` set  `Product Sales State`=%s,`Product Record Type`=%s,`Product Web Configuration`=%s ,`Product To Be Discontinued`=%s where `Product ID`=%s"
		   ,prepare_mysql($state)
		   ,prepare_mysql($record_state)
		   ,prepare_mysql($web_state)
		   ,prepare_mysql($discontinued_state)
		  
		   ,$product->pid);
   
      if(!mysql_query($sql))
	exit("$sql can not upodate state of the product");
      

      
    }
    
  }
	
  
  $sql=sprintf("select id,code  from aw_old.product  where product.code=%s and  condicion=2 and stock=0  ",prepare_mysql($product->data['Product Code']));
  $result2a=mysql_query($sql);
  if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
    $state='Discontinued';
    $discontinued_state='Yes';
    $web_state='Offline';
    $record_state='Normal';
     $sql=sprintf("update `Product Dimension` set  `Product Sales State`=%s,`Product Record Type`=%s,`Product Web Configuration`=%s ,`Product To Be Discontinued`=%s where `Product ID`=%s"
		  ,prepare_mysql($state)
		  ,prepare_mysql($record_state)
		  ,prepare_mysql($web_state)
		  ,prepare_mysql($discontinued_state)
		  
		   ,$product->pid);
   
      if(!mysql_query($sql))
	exit("$sql can not upodate state of the product");
    
    
  }
  
}


$sql="select * from `Product Family Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
  $family=new Family($row['Product Family Key']);
  $family->update_sales_state();
}


?>