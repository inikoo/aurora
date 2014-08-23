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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$sql=sprintf("select * from `Product Dimension` where `Product Store Key`=1 ");
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $pid=$row['Product ID'];
  $code=$row['Product Code'];
  $keys=get_keys($pid);
  $state=$row['Product Record Type'];
  $old_last_date=$row['Product Valid To'];
  
  
  //  $sql="select count(*) as num from `Order Transaction Fact` where `Product Key` in ($keys) ";
  // $res2=mysql_query($sql);
  //if($row2=mysql_fetch_array($res2)){
  //if($row2['num']==0)
  //  $skus=get_product_all_skus($pid);
  //   print "$code $pid SKUS: $skus ; $state $keys $old_last_date \n";

    


  // }
  if($state=='Discontinued'  or $state=='Historic'  ){
  $sql="select `Order Date` from `Order Transaction Fact` where `Product Key` in ($keys) order by `Order Date` desc limit 1";
    $res2=mysql_query($sql);
     if($row2=mysql_fetch_array($res2)){
       $last_date=$row2['Order Date'];
       if(strtotime($old_last_date)>strtotime($last_date)){
	 print "$code $pid $state $keys $last_date $old_last_date \n";
	 $sql=sprintf("update `Product Dimension` set `Product Valid To`=%s where `Product ID`=%d ",prepare_mysql($last_date),$pid);
	 if(!mysql_query($sql))
	   exit($sql);
       }
       
     }
  }
  
  
  
  
  
  
  
  
      // }else{
      //print("no transactions\n");
      // $last_date='no data';
      //  print "$code $pid $state $keys $last_date $old_last_date \n";
      
      //   }
    
    //  print "$code $pid $state $keys $last_date $old_last_date \n";
  //  }
  

}


function get_keys($pid){
    $sql=sprintf("select GROUP_CONCAT(distinct `Product Key`) as ids from `Product History Dimension` where `Product ID`=$pid "
	
		 );
    //print "$sql\n";
    $res=mysql_query($sql);
    $ids='';
    if($row=mysql_fetch_array($res)){
      $ids= $row['ids'];
    }
    return $ids;
}

function get_product_all_skus($ids){

   
  
    
    $sql="select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part List` where `Product ID` in ($ids)  ";
    $res=mysql_query($sql);
    $skus='';
    if($row=mysql_fetch_array($res)){
      $skus= $row['skus'];
    }

    return $skus;
    
  }


?>