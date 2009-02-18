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
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product($row['Product Key']);
  $product->load('sales');

  
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid From` limit 1",prepare_mysql($row['Product Code']));
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid To` desc",prepare_mysql($row['Product Code']));
  $most_recent='Yes';
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    if($most_recent=='Yes'){
      $most_recent_key=$row2['Product Key'];
      $same_code_to=$row2['Product Valid To'];
    }
    $sql=sprintf("update `Product Dimension` set  `Product Same Code Valid From`=%s ,`Product Same Code Valid To`=%s , `Product Same Code Most Recent Key`=%s,`Product Same Code Most Recent`=%s  where `Product Key`=%s ",prepare_mysql($same_code_from),prepare_mysql($same_code_to),$row['Product Key'],prepare_mysql($most_recent),$most_recent_key,$row2['Product Key']);
    //   print "$sql\n\n";
    mysql_query($sql);
     if($most_recent=='Yes')
       $most_recent=='No';

  }




  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%s order by `Product Valid From` limit 1",prepare_mysql($row['Product ID']));
  
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }else
    exit("caca1");
  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%s order by `Product Valid To` desc limit 1",prepare_mysql($row['Product ID']));
  

  $most_recent='Yes';
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_to=$row2['Product Valid To'];
  }else
    exit("caca2");
  $sql=sprintf("update `Product Dimension` set  `Product Same ID Valid From`=%s ,`Product Same ID Valid To`=%s  where `Product Key`=%s ",prepare_mysql($same_code_from),prepare_mysql($same_code_to),$row['Product Key'],$row2['Product Key']);
  // print "$sql\n\n";
  mysql_query($sql);


 




    


  print $row['Product Key']."\r";




 }



?>