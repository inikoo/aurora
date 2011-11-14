<?php
error_reporting(E_ALL);
date_default_timezone_set('UTC');

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


//$stores=array(1);
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

/*
$sql="select * from `Invoice Category Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  print $row['Invoice Category Code']."\n";
  $tm=new TimeSeries(array('m','invoice category:'.$row['Invoice Category Code']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
  $tm=new TimeSeries(array('w','invoice category:'.$row['Invoice Category Code']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
  $tm=new TimeSeries(array('q','invoice category:'.$row['Invoice Category Code']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
   $tm=new TimeSeries(array('y','invoice category:'.$row['Invoice Category Code']));
  $tm->get_values();$tm->save_values();
  $tm->forecast();
};
*/
if(true){
  print "inv\n";

 $tm=new TimeSeries(array('d','invoices'));
  $tm->get_values();
  $tm->save_values();



  $tm=new TimeSeries(array('w','invoices'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
    
  $tm=new TimeSeries(array('q','invoices'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
 
  $tm=new TimeSeries(array('m','invoices'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
  $tm=new TimeSeries(array('y','invoices'));
  $tm->get_values();$tm->save_values();
  if($forecast)
    $tm->forecast();
 
 }


$sql="select * from `Store Dimension`";
$res=mysql_query($sql);

while( $row=mysql_fetch_array($res)){
  $store=new Store($row['Store Key']);
  print 'store ('.$row['Store Key'].') sales'."\n";
  
  $tm=new TimeSeries(array('m','store ('.$row['Store Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
   
  
  
 /*  $tm=new TimeSeries(array('w','store ('.$row['Store Key'].') sales')); */
/*   $tm->get_values(); */
/*   $tm->save_values(); */
/*   if($forecast) */
/*     $tm->forecast(); */

$tm=new TimeSeries(array('d','store ('.$row['Store Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
$tm=new TimeSeries(array('w','store ('.$row['Store Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();



  $tm=new TimeSeries(array('q','store ('.$row['Store Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
$tm=new TimeSeries(array('y','store ('.$row['Store Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();


if($corporate_currency!=$store->data['Store Currency Code']){

  $tm=new TimeSeries(array('m','store ('.$row['Store Key'].') sales (DC)'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
  
 $tm=new TimeSeries(array('w','store ('.$row['Store Key'].') sales (DC)'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
    
     $tm=new TimeSeries(array('d','store ('.$row['Store Key'].') sales (DC)'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();


  $tm=new TimeSeries(array('q','store ('.$row['Store Key'].') sales (DC)'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
  $tm=new TimeSeries(array('y','store ('.$row['Store Key'].') sales (DC)'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
}


 
}
 
$sql="select * from `Product Department Dimension`  where `Product Department Store Key`    ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  print 'product department ('.$row['Product Department Key'].') sales'."\n";
/*   $tm=new TimeSeries(array('w','product department ('.$row['Product Department Key'].') sales')); */
/*   $tm->get_values(); */
/*   $tm->save_values(); */
/*   if($forecast) */
/*     $tm->forecast(); */
$tm=new TimeSeries(array('w','product department ('.$row['Product Department Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
$tm=new TimeSeries(array('m','product department ('.$row['Product Department Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
$tm=new TimeSeries(array('q','product department ('.$row['Product Department Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
$tm=new TimeSeries(array('y','product department ('.$row['Product Department Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
}



$sql="select * from `Product Family Dimension`   where `Product Family Store Key`     ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  print 'product family ('.$row['Product Family Key'].') sales'."\n";
  /* $tm=new TimeSeries(array('w','product family ('.$row['Product Family Key'].') sales')); */
/*   $tm->get_values(); */
/*   $tm->save_values(); */
/*    if($forecast) */
/*      $tm->forecast(); */
 $tm=new TimeSeries(array('m','product family ('.$row['Product Family Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
     $tm->forecast();
 $tm=new TimeSeries(array('q','product family ('.$row['Product Family Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
     $tm->forecast();
 $tm=new TimeSeries(array('y','product family ('.$row['Product Family Key'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
     $tm->forecast();

}


$sql="select * from `Product Dimension`   where `Product Store Key`     ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  //print 'product id ('.$row['Product Code'].') '.$row['Product ID'].' sales'."\n";
  $tm=new TimeSeries(array('w','product id ('.$row['Product ID'].') sales'));
  $tm->get_values(); 
  $tm->save_values(); 
   if($forecast) 
   $tm->forecast(); 
 unset($tm);
 $tm=new TimeSeries(array('m','product id ('.$row['Product ID'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
  $tm->forecast();
 unset($tm);

 $tm=new TimeSeries(array('q','product id ('.$row['Product ID'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
  $tm->forecast();
 unset($tm);
 $tm=new TimeSeries(array('y','product id ('.$row['Product ID'].') sales'));
  $tm->get_values();
  $tm->save_values();
   if($forecast)
  $tm->forecast();
   unset($tm);
}

exit;

$sql="select * from `Product Same Code Dimension` ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  // print 'product code ('.$row['Product Code'].') sales'."\n";
  $tm=new TimeSeries(array('w','product code ('.$row['Product Code'].') sales')); 
   $tm->get_values(); 
   $tm->save_values(); 
   if($forecast) 
     $tm->forecast(); 

 $tm=new TimeSeries(array('m','product code ('.$row['Product Code'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
 unset($tm);
 $tm=new TimeSeries(array('q','product code ('.$row['Product Code'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();
 unset($tm);
 $tm=new TimeSeries(array('y','product code ('.$row['Product Code'].') sales'));
  $tm->get_values();
  $tm->save_values();
  if($forecast)
    $tm->forecast();

  unset($tm);
}

?>