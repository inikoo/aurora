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


$sql="select * from aw_old.in_out left join product on (product.id=product_id) order by product,date limit 100";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $date=$row['date'];
  $code=$row['code'];
  

  $sql=sprintf("select `Product Key` from `Product Dimension` where `Product Code`=%s and `Product Valid From`<=%s and `Product Valid To`=%s order by `Product Valid To` desc ",prepare_mysql($code),prepare_mysql($date),prepare_mysql($date));
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $product_ID=$row2['Product ID'];
  }else
    exit("error 1");

  
   $sql=sprintf("select `Part SKU` from `Product Part List` where `Product ID`=%s  ",prepare_mysql($product_ID));
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $part_sku=$row2['Part SKU'];
  }else
    exit("error 1");
  

 }



?>

// select SPD.`Supplier Product ID` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) where `Product ID`=1;