<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Store.php');

error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw2';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  
  $product=new Product($row['Product Key']);

  // print $product->id." ".$product->data['Product Same Code Most Recent']."\n";

  //check iif it is in a department;
  $store_key=$product->data['Product Store Key'];
  $dept_no_dept=new Department('code_store','No department',$store_key);
  if(!$dept_no_dept->id){
    $dept_data=array(
		     'code'=>'No Department',
		     'name'=>'Products Without Department',
		     'store_key'=>$store_key
		     );
    $dept_no_dept=new Department('create',$dept_data);
  }

  $promo=new Department('code_store','Promotional Items',$store_key);
  if(!$promo->id){
    $dept_data=array(
		     'code'=>'Promotional Items',
		     'name'=>'Promotional Items',
		     'store_key'=>$store_key
		     );
    $promo=new Department('create',$dept_data);
  }
  
 if(preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',$product->data['Product Code'])){
    
     $sql=sprintf("update from `Product Dimension` set `Product Main Department Key`=%d,`Product Main Department Code`=%s,`Product Main Department Name`=%s  where `Product Key`=%d "
		 ,$promo->id
		 ,prepare_mysql($promo->data['Product Department Code'])
		 ,prepare_mysql($promo->data['Product Department Name'])
		 ,$product->id);
    mysql_query($sql);

    $sql=sprintf("delete from `Product Department Bridge` where `Product Key`=%d ",$product->id);
    if(!mysql_query($sql))
      exit("errir a");
    $sql=sprintf("insert into  `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$promo->id);
    if(!mysql_query($sql))
      exit("errir b");

  }


  $sql=sprintf("select * from `Product Department Bridge` where `Product Key`=%d",$product->id);
  $result_a=mysql_query($sql);
  $num_deptos=0;
  $no_dep=false;
  if($row_a=mysql_fetch_array($result_a, MYSQL_ASSOC)){
    $num_deptos++;
    if($row_a['Product Department Key']==$dept_no_dept->id)
      $no_dep=true;
  }
  if($num_deptos>1 and $no_dep){
    $sql=sprintf("delete from `Product Department Bridge` where `Product Key`=%d and `Product Department Key`=%d",$product->id,$dept_no_dept->id);
    mysql_query($sql);
  }


  if($num_deptos==0){
    
   $sql=sprintf("update from `Product Dimension` set `Product Main Department Key`=%d,`Product Main Department Code`=%s,`Product Main Department Name`=%s  where `Product Key`=%d "
		 ,$dept_no_dept->id
		 ,prepare_mysql($dept_no_dept->data['Product Department Code'])
		 ,prepare_mysql($dept_no_dept->data['Product Department Name'])
		 ,$product->id);
    mysql_query($sql);
     $sql=sprintf("insert into  `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$dept_no_dept->id);
    mysql_query($sql);

  }



  print $row['Product Key']."\r";

 }