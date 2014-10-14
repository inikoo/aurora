<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           
$stores=array(1,2,3);

$sql="select * from `Product Family Dimension`  where `Product Family Store Key` in (".join(',',$stores).") ";
$sql="select * from `Product Family Dimension` ";

//print $sql;
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $family=new Family($row['Product Family Key']);
  $family->update_sales_default_currency();
  $family->update_product_data();
  $family->update_sales_data();
  print $row['Product Family Code']."        \r";
 }

mysql_free_result($result);

?>