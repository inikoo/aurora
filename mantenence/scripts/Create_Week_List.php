<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$start_date='2002-12-30';

$end_date='2020-01-04';

$i=0;
$date=strtotime($start_date);
//print "$date ". strtotime($end_date)."\n";
while($date<strtotime($end_date)){
  $i++;
    if(date('W',$date)==1)
    $__y=date('Y',strtotime(date('Y-m-d',$date).' +6 days'));
  else
    $__y=date('Y',strtotime(date('Y-m-d',$date).' +0 days'));


  $sql=sprintf("insert into kbase.`Week Dimension` values ('%s%s','%s','%s','%s%s','%s','%s','%s','%s','%s')"
	       ,$__y
	       ,date('W',$date)
	       ,date('Y-m-d',$date)
	       ,date('Y-m-d',strtotime(date('Y-m-d',$date).' +6 days'))
	       ,$__y
	       ,date('W',$date)
	       ,date('W',$date)
	       ,date('Y-m-d',strtotime(date('Y-m-d',$date).' +3 days'))
	       ,date('Y-m-d',strtotime(date('Y-m-d',$date).' +6 days'))
	        ,$__y
	       ,date('W',$date)
	       );
  mysql_query($sql);
   // print "$sql\n";
  if(date('W',$date)==53){
    $_year=date('Y',$date);
    if(date('d',$date>=25)){

      $sql=sprintf("select  `Last Day` from kbase.`Week Dimension` where `Year Week`='%s02'",$_year);
      $res=mysql_query($sql);
      $__lastday='';
      while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
	$__lastday=$row['Last Day'];
      }

      $sql=sprintf("update kbase.`Week Dimension` set `Normalized Last Day`='%s' where `Year Week`='%d%02d'",$__lastday,$_year,1);
      print "$sql\n";
      mysql_query($sql);


      for($i=2;$i<=53;$i++){
	$sql=sprintf("update kbase.`Week Dimension` set `Week Normalized`='%02d' where `Year Week`='%d%02d'",$i-1,$_year,$i);
		mysql_query($sql);
		//	print "$sql\n";
	$sql=sprintf("update kbase.`Week Dimension` set `Year Week Normalized`=%s%02d where `Year Week`='%d%02d'",$_year,$i-1,$_year,$i);
	//print "$sql\n";


	mysql_query($sql);
      }
    }else{
      $sql=sprintf("update kbase.`Week Dimension` set `Normalized Last Day`='%s' where `Year Week`='%d%02d'",date('Y-m-d',strtotime(date('Y-m-d',$date).' +6 days')),$_year,52);
      mysql_query($sql);

      $sql=sprintf("update kbase.`Week Dimension` set `Week Normalized`=%d where `Year Week`='%d%02d'",52,$_year,53);
      mysql_query($sql);
      print "$sql\n";
      $sql=sprintf("update kbase.`Week Dimension` set `Year Week Normalized`=%s%02d where `Year Week`='%d%02d'",$_year,52,$_year,53);
      //print "$sql\n";
      mysql_query($sql);
    }

  }

  $date=strtotime(date('Y-m-d',$date).' +7 days');
  //  print date("W r\n",$date);
  if($i>100000)
    exit;


 }



?>