<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
error_reporting(E_ALL);
date_default_timezone_set('GMT');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;

$codes=array();
$sql="select `Product ID`,`Product Code` from `Product Dimension` left join `Product Family Dimension` F on (`Product Code`=F.`Product Family Code` ) where F.`Product Family Key` IS NOT NULL ";
$result=mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  print "***********".$row['Product Code']."\n";
  $product=new Product('pid',$row['Product ID']);
  
  if(preg_match('/^fo$/i',$row['Product Code']))
  $regex="and `Product Units Per Case`=10  and  `Product Code` REGEXP '-[A-Z][0-9]+$'";
  elseif(preg_match('/^sg$/i',$row['Product Code']))
  $regex="and `Product Code` REGEXP '-[A-Z][0-9]+$'";
  else
  $regex="and `Product Code` REGEXP '-[0-9]+$'";
  $product->rename_historic(false,$regex);
  $codes[]=  $row['Product Code'];
 }
mysql_free_result ($result);
foreach($codes as $code){
$sql=sprintf("delete from `Product Same Code Dimension` where  `Product Code`='%s' ",$code);
     if(!mysql_query($sql))
       print "error $sql\n";
}



?>