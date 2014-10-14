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

$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$sql="trucate kbase.`Month Dimension`";
 mysql_query($sql);
$start_date='1960-01-01';

$end_date='2020-01-01';

$i=0;
$date=strtotime($start_date);
//print "$date ". strtotime($end_date)."\n";
while($date<strtotime($end_date)){
  $i++;
  $last_day= date('Y-m-d',strtotime(date("Y-m-d",strtotime(date('Y-m-d',$date).' +1 month')).' -1 day ' ));
  $sql=sprintf("insert into kbase.`Month Dimension` values ('%s','%s','%s','%s')",date('Ym',$date),date('Y-m-d',$date),$last_day,date('m',$date));
  mysql_query($sql);
  //print "$sql\n";
  $date=strtotime(date('Y-m-d',$date).' +1 month');
  if($i>100000)
    exit;


 }



?>