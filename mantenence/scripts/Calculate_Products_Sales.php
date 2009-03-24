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
$dns_db='dw2';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');




//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){

  
  
  

  
  $product=new Product($row['Product Key']);
  
  $product->load('sales');
  $product->load('parts');

  if(isset($argv[1]) and $argv[1]=='first'){


  if($product->data['Product Same Code Most Recent']=='Yes'){
    $state='For sale';
    if($product->data['Product 1 Year Acc Quantity Ordered']==0)
      $state='Discontinued';
    
    $sql=sprintf("select id,code  from aw_old.product  where product.code=%s and  condicion=2 and stock=0  ",prepare_mysql($product->data['Product Code']));
    $result2a=mysql_query($sql);
    if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
      $state='Discontinued';
    }
    
  }else
    $state='History';

   $sql=sprintf("update `Product Dimension` set  `Product Sales State`=%s where `Product Key`=%s",prepare_mysql($state),$product->id);
   // print "$sql\n\n";
  if(!mysql_query($sql))
    exit("can not upodate state of the product");
  }


  print $row['Product Key']."\r";




 }



?>