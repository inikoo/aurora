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
$sql="select * from `Product Part List`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql=sprintf("insert into `Product Part Dimension`  (`Product Part Note`,`Product Part Type`,`Product Part Metadata`,`Product Part Valid From`,`Product Part Valid To`,`Product Part Most Recent`) values (%s,%s,%s,%s,%s,%s) "
	       ,prepare_mysql($row['Product Part Note'],false)
	       ,prepare_mysql($row['Product Part Type'])
	       ,prepare_mysql($row['Product Part Metadata'],false)
	       ,prepare_mysql($row['Product Part Valid From'])
	       ,prepare_mysql($row['Product Part Valid To'])
	       ,prepare_mysql($row['Product Part Most Recent'])
	       );
  mysql_query($sql);
 
  $new_key=mysql_insert_id();
  $sql=sprintf("update `Product Part List` set `Product Part Key`=%d where `Product Part Key`=%d  "
	       ,$new_key
	       ,$row['Product Part Key']
	       );
   mysql_query($sql);
   $sql=sprintf("update `Product Part Dimension` set `Product Part Most recent Key`=%d where `Product Part Key`=%d  "
	       ,$new_key
	       ,$new_key
	       );
   mysql_query($sql);

 }
mysql_free_result($result);


?>