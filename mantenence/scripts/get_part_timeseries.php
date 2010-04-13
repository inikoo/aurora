<?php
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once '../../app_files/db/dns.php';
require_once '../../class.TimeSeries.php';
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php'; 
date_default_timezone_set(TIMEZONE) ;

include_once('../../set_locales.php');

require_once '../../conf/conf.php';   


$_SESSION['lang']=1;


$stores=array(1);
$forecast=true;

//$sql="select * from `Part Dimension`  ";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
// $tm=new TimeSeries(array('m','part sku ('.$row['Part SKU'].') required'));
// $tm->get_values();
// $tm->save_values();
// if($forecast)
//    $tm->forecast();
//}
// exit;

$supplier_skus='';
$sql=sprintf("select `Part SKU` as skus from `Part Dimension` where `Part XHTML Currently Supplied By` like '%%AWChina%%' ");
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$supplier_skus.=",".$row['skus']."";
}
$supplier_skus=preg_replace('/^,/','',$supplier_skus);$supplier_skus="($supplier_skus)";


$sql="select * from `Part Dimension` where `Part SKU` in $supplier_skus  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  print $row['Part SKU']."\n";
  $tm=new TimeSeries(array('m','part sku '.$row['Part SKU']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
  
  $tm=new TimeSeries(array('w','part sku '.$row['Part SKU']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
  
  

};




?>