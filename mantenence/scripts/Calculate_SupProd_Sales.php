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

//select `Product Key`,`Product Code` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where `Supplier Product Key`=133;



$sql="select * from `Supplier Product Dimension` where `Supplier Product Key`=13258 ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sp=new SupplierProduct($row['Supplier Product Key']);
  $sp->load('used in');
  $sp->load('sales');
  print $row['Supplier Product Key']."\r";
 }



?>