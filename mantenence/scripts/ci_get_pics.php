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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$sql="select * from `Product Dimension`  group by `Product Code` order by RAND() desc";

$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){


  
  
  

  $product=new Product('pid',$row['Product ID']);
  // print_r($product->data);
  print $product->data['Product Code']."\n";
  $rand=rand().rand();




  $www_address='www.aw-regalos.com/fotos/';


  $exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code'])).".jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){

    //exit;
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");

  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");




$rand=rand().rand();
  $exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");
$rand=rand().rand();
 $exec=sprintf("wget %s%s",$www_address,ucfirst($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtoupper($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_bis_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");
$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_box_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_bis.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");


$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_box.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");


$rand=rand().rand();
$exec=sprintf("wget %s%s",$www_address,strtolower($product->data['Product Code']))."_display.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");


  $product->load('images');
  // print_r($product->images_original);
  foreach($product->images_original as $key=>$_values){

    $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product ID`!=%s",prepare_mysql($product->data['Product Code']),$product->id);
  $resultx=mysql_query($sql);
  while($rowx=mysql_fetch_array($resultx)   ){

    $sql=sprintf("update `Product Image Bridge` set `Is Princial`='No' where `Product ID`=%d",$rowx['Product ID']);
    mysql_query($sql);

    $sql=sprintf("insert into `Product Image Bridge` values (%d,%d,'Yes')",$rowx['Product ID'],$key);
    print "$sql\n";
    mysql_query($sql);

  }

  }
 }


?>