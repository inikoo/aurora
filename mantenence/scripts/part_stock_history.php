<?php
include_once('../../app_files/db/dns.php');
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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$where='and  `Part XHTML Currently Used In` like "%avalon-01%"';
$where='and `Part SKU`=4303';
$where='';
$sql=sprintf('select count(*) as num  from `Part Dimension` where `Part Status`="in Use" %s   order by `Part Valid From` desc ',$where);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $total=$row['num'];
}


//print "Wrap part transactions\n";
$sql=sprintf('select `Part SKU`,`Part XHTML Currently Used In`  from `Part Dimension` where `Part Status`="In Use"   %s  order by `Part Total Acc Sold Amount` ,`Part Valid From`   ',$where);
//print $sql;
$res=mysql_query($sql);
$count=0;
while($row=mysql_fetch_array($res)){
  $count++;

  $part=new Part($row['Part SKU']);

  print percentage($count,$total,5)."  ".$part->data['Part SKU']."\r";
 $part->update_stock_history();


}









?>