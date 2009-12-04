<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Part Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $part=new Part('sku',$row['Part SKU']);

  //Get  status
  if(isset($argv[1]) and $argv[1]=='first'){
  $part_valid_from=$part->data['Part Valid From'];
  $part_valid_to=$part->data['Part Valid To'];
  $in_use='Discontinued';
  $last_stock='Yes';
  $sql=sprintf(" select `Product To Be Discontinued`,`Product Sales State`,`Product Record Type`,`Product Code` from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%d   ",$part->data['Part SKU']);
  // print "$sql\n";
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){

    
    if(preg_match('/^For Sale|Not for sale|Unknown/i',$row2['Product Sales State'])){
      $in_use='In Use';
      $part_valid_to= date("Y-m-d H:i:s");

      if($row2['Product To Be Discontinued']!='Yes')
	$last_stock='No';

    }else{
      
      $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Inventory Transaction Type`='Sale' and `Inventory Transaction Quantity`!=0    order by `Date` desc limit 1 ",$part->data['Part SKU']);
      $resultxx=mysql_query($sql);
      
      //   print "$sql\n";
      if($rowxx=mysql_fetch_array($resultxx, MYSQL_ASSOC)   ){

	if(strtotime($rowxx['Date'])< strtotime( $part->data['Part Valid From'])){
	  $part_valid_from=$rowxx['Date'];
	  //	  	print "$sql\n".$rowxx['Date']." ".$part->data['Part Valid From']." ".$part->data['Part Valid To']."\n";
	}
	$part_valid_to= $rowxx['Date'];
      }else{
	$part_valid_to=$part->data['Part Valid From'];
      }
    
      
    }

  }


    $sql=sprintf("update `Part Dimension` set `Part Last Stock`=%s ,`Part Status`=%s ,`Part Valid From`=%s ,`Part Valid To`=%s where `Part SKU`=%d   "
		 ,prepare_mysql($last_stock)
		 ,prepare_mysql($in_use)
		 ,prepare_mysql($part_valid_from)
		 ,prepare_mysql($part_valid_to),$part->data['Part SKU']);

    //print "$sql\n";
  if(!mysql_query($sql))
    exit("ERROR $sql\n");
  

    $part->load('sales');

  // print "$sql\n";
  //if(!mysql_query($sql))
  //  exit("ERROR $sql\n");
  }
  $part->load('sales');
  $part->load('used in');
  $part->load('supplied by');
  
  if(!isset($argv[1])){
    
    $part->load('stock');
    $part->load('forecast');

  }

  //   $part->load('stock_history');
  //   $part->load('future costs');
  print $row['Part SKU']."\r";
  
 }



?>