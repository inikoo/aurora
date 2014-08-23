<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.PartLocation.php');
include_once('../../class.User.php');
include_once('../../class.InventoryAudit.php');

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );


if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$sql=sprintf('select count(*) as num  from `Inventory Transaction Fact` where `Inventory Transaction Type`="Sale"');
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $total=$row['num'];
}

$sql=sprintf('select I.`Note`,`Order Key`,`Order Public ID`,`Metadata`,`Part SKU`,`Location Key` from `Inventory Transaction Fact` I  left join `Order Dimension` O on (I.`Metadata`=O.`Order Original Metadata`) where `Inventory Transaction Type`="Sale"');
print "$sql\n";
$res=mysql_query($sql);
$count=0;
while($row=mysql_fetch_array($res)){
 
 
 $note=$row['Note'];
 $old_note=$note;
 $note=preg_replace('/\(.*\)/','',$note);;
 $note.=sprintf(" (Order:<a href=\"order.php?id=%d\">%s</a>)",$row['Order Key'],$row['Order Public ID']);
 
 $sql=sprintf("update `Inventory Transaction Fact` set `Note`=%s where `Metadata`=%s and `Part SKU`=%d and `Location Key`=%d and `Note`=%s"
 ,prepare_mysql($note)
 ,prepare_mysql($row['Metadata'])
 ,$row['Part SKU']
 ,$row['Location Key']
 ,prepare_mysql($old_note)
 );
 if(!mysql_query($sql)){
 exit("$sql\n");
 }
  $count++;
  print percentage($count,$total)."  \r";
}







?>