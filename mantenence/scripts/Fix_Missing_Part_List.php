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

date_default_timezone_set('Europe/London');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;
$sql="select * from `Product Dimension` where `Product Store Key` in (2,3)";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql=sprintf("select `Part SKU` from `Product Part List` where  `Product ID`=%s",$row['Product ID']);
  //print "$sql\n";
  $result2=mysql_query($sql);
  $number_parts=mysql_num_rows($result2);
  if($number_parts==0){
    print "$sql  $number_parts \n";
    print $row['Product Code']."\n";


    $code=$row['Product Code'];
    $code=preg_replace('/L\&P/','LLP',$code);
    
    $uk_product=new Product('code_store',$code,1);
    $parts=$uk_product->get('Parts SKU');
    $part_list=array();


$part_list[]=array(
		   'Product ID'=>$row['Product ID'],
 			   'Part SKU'=>$parts[0],
 			   'Product Part Id'=>1,
 			   'requiered'=>'Yes',
 			   'Parts Per Product'=>1,
 			   'Product Part Type'=>'Simple Pick'
 			   );
  
  $product=new Product('pid',$row['Product ID']);
  $product->new_part_list(array(),$part_list);


  print_r($part_list);
  //  exit;


  }  


 }
mysql_free_result($result);


?>