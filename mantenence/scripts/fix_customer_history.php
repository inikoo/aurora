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
include_once('../../class.Order.php');

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

global $myconf;
$sql='select * from `History Dimension` where `Subject`="Customer"   ';
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

$sql=sprintf("insert into `Customer History Bridge` values (%d,%d)",
$row['Subject Key'],
$row['History Key']

);
mysql_query($sql);

 }
mysql_free_result($result);

$sql='select * from `History Dimension` where  `Direct Object`="Customer"   ';
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

$sql=sprintf("insert into `Customer History Bridge` values (%d,%d)",
$row['Direct Object Key'],
$row['History Key']

);
mysql_query($sql);


 }
mysql_free_result($result);

$sql='select * from `History Dimension` where  `Indirect Object`="Customer"  ';
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

$sql=sprintf("insert into `Customer History Bridge` values (%d,%d)",
$row['Indirect Object Key'],
$row['History Key']

);
mysql_query($sql);


 }
mysql_free_result($result);

?>