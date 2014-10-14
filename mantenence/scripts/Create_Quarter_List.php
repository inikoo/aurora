<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');


$start_date='2003-01-01';

$end_date='2012-01-01';

$i=0;
$date=strtotime($start_date);
//print "$date ". strtotime($end_date)."\n";
while($date<strtotime($end_date)){
  $i++;
  $sql=sprintf("insert into `Quarter Dimension` values ('%s','%s')",date('Y1',$date),date('Y-01-01',$date));
  mysql_query($sql);
  print "$sql\n";
  
  $sql=sprintf("insert into `Quarter Dimension` values ('%s','%s')",date('Y2',$date),date('Y-04-01',$date));
  mysql_query($sql);
  print "$sql\n";

  $sql=sprintf("insert into `Quarter Dimension` values ('%s','%s')",date('Y3',$date),date('Y-07-01',$date));
  mysql_query($sql);
  print "$sql\n";
 $sql=sprintf("insert into `Quarter Dimension` values ('%s','%s')",date('Y4',$date),date('Y-10-01',$date));
  mysql_query($sql);
  print "$sql\n";

  
  $date=strtotime(date('Y-m-d',$date).' +1 year');

  

  if($i>100000)
    exit;


 }



?>