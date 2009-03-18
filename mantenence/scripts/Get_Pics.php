<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');

$sql="select * from `Product Dimension`  group by `Product Code` order by `Product Code`";

$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){


  
  
  

  $product=new Product($row['Product Key']);
  // print_r($product->data);
  print $product->data['Product Code']."\n";
  $rand=rand().rand();


  $exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code'])).".jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){

    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");

  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");




$rand=rand().rand();
  $exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");
$rand=rand().rand();
 $exec=sprintf("wget www.ancientwisdom.biz/pics/%s",ucfirst($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtoupper($product->data['Product Code']))."_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_bis_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");
$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_box_l.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");

$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_bis.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");


$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_box.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
  exec($exec );
  //print '../../app_files/pics/tmp$rand.jpg'."\n";
  if(file_exists("../../app_files/pics/tmp$rand.jpg") and filesize("../../app_files/pics/tmp$rand.jpg")>0){
    //  print "caca";
    $product->load_original_image("../../app_files/pics/tmp$rand.jpg");
    
  }
 if(file_exists("../../app_files/pics/tmp$rand.jpg") )
    unlink("../../app_files/pics/tmp$rand.jpg");


$rand=rand().rand();
$exec=sprintf("wget www.ancientwisdom.biz/pics/%s",strtolower($product->data['Product Code']))."_display.jpg -q -O ../../app_files/pics/tmp$rand.jpg" ;
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

    $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Key`!=%s",prepare_mysql($product->data['Product Code']),$product->id);
  $resultx=mysql_query($sql);
  while($rowx=mysql_fetch_array($resultx)   ){

    $sql=sprintf("insert into `Product Image Bridge` values (%d,%d)",$rowx['Product Key'],$key);
    //print "$sql\n";
    mysql_query($sql);

  }

  }
 }


?>