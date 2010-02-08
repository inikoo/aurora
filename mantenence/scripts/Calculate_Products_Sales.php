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
  $sql="select * from `Product History Dimension` PH  order by `Product Key`   ";


$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result)   ){
  $product=new Product('id',$row['Product Key']);
  
  $product->load('sales');
  //   $product->load('parts');
  // print "caca";
  //$product->load('sales');


  if(isset($argv[1]) and $argv[1]=='first' and isset($argv[2]) and $argv[2]=='aw'){
    
    
    
    if($product->data['Product Same Code Most Recent']=='Yes'){
      $state='For Sale';
	$discontinued_state='No';
	if($product->data['Product 1 Year Acc Quantity Ordered']==0 and (strtotime($product->data['Product Valid From'])<strtotime('today -1 year')    )){
	  //check if has stock
	  $sql=sprintf("select id,code  from aw_old.product  where product.code=%s and (stock=0 or stock<0 or stock is null)   ",prepare_mysql($product->data['Product Code']));
	  $result2a=mysql_query($sql);
	  if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
	    $state='Discontinued';
	    $discontinued_state='No Applicable';
	    
	  }
	  
	}
	
	$sql=sprintf("select id,code  from aw_old.product  where product.code=%s and  condicion=2 and stock=0  ",prepare_mysql($product->data['Product Code']));
	$result2a=mysql_query($sql);
	if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
	  $state='Discontinued';
	  $discontinued_state='Yes';
	  
	}
	
    }else{
      $state='Historic';
      	$discontinued_state='No Applicable';
	
      }
      
  
  
  if($state=='Historic'){
    $record_state='Historic';
    $state='Not for sale';
    $web_state='Offline';
  }elseif($state=='Discontinued'){
    $record_state='Normal';
    $state='Discontinued';
    $web_state='Online';
  }elseif($state=='For Sale'){
    $record_state='Normal';
    $state='For Sale';
    $web_state='Online';
 }
  

   $sql=sprintf("update `Product Dimension` set  `Product Sales State`=%s,`Product Record Type`=%s,`Product Web State`=%s ,`Product To Be Discontinued`=%s where `Product Key`=%s"
		,prepare_mysql($state)
		,prepare_mysql($record_state)
		,prepare_mysql($web_state)
		,prepare_mysql($discontinued_state)

		,$product->id);
    print "$sql\n\n";
  if(!mysql_query($sql))
    exit("can not upodate state of the product");


  }else{


    //$product->load('days');
    // $product->load('stock');
  }
  print $row['Product Key']."\t\t ".$product->data['Product Code']." \r";




 
}


?>