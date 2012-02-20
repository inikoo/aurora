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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');





$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";


$start_date='1980-01-01';

$end_date='2025-01-04';

$i=0;
$date=strtotime($start_date);
//print "$date ". strtotime($end_date)."\n";
while($date<strtotime($end_date)){
  $i++;
  $sql=sprintf("insert into kbase.`Date Dimension` (`Date`) values ('%s')",date('Y-m-d',$date));
  mysql_query($sql);
  print "$sql\n";
  $date=strtotime(date('Y-m-d',$date).' +1 day');
  if($i>100000)
    exit;


 }





?>