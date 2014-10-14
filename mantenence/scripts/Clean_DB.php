
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



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$sql=' delete  from `Product Department Dimension` where `Product Department Store Key`=2;delete  from `Product Department Dimension` where `Product Department Store Key`=3;delete  from `Product Family Dimension` where `Product Family Store Key`=2;delete  from `Product Family Dimension` where `Product Family Store Key`=3;delete   from `Product Dimension` where `Product Store Key`=3;delete from `Product Dimension` where `Product Store Key`=2;delete  from `Product Department Bridge` where `Product Department Key`>21;';
 mysql_query($sql);

$sql='select `Product Part Key` as todelete  from `Product Part List` PPL left join `Product Dimension` PD on (PD.`Product ID`=PPL.`Product ID`)  where PD.`Product Key` is null ';
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql="delete from `Product Part List` where `Product Part Key`=".$row['todelete'];
  print "$sql\n";
  mysql_query($sql);
 }