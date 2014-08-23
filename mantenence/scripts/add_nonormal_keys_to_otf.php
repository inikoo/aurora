<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');
include_once('../../class.Node.php');
include_once('../../class.Category.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

$sql="select `Product Key`,`Order Transaction Fact Key` from `Order Transaction Fact` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
$product=new Product($row['Product Key']);

//print_r($product->data);

$sql=sprintf("update `Order Transaction Fact` set `Product ID`=%d,`Product Code`=%s, `Product Family Key`=%d ,`Product Depament Key`=%d  where `Order Transaction Fact Key`=%d ",
$product->data['Product ID'],
prepare_mysql($product->data['Product Code']),
$product->data['Product Family Key'],
$product->data['Product Main Department Key'],
$row['Order Transaction Fact Key']
);
mysql_query($sql);
//print $sql;
//exit;
 }



?>