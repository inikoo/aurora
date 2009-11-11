<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

global $myconf;
$sql="select * from `Category Dimension`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sql="select * from `Store Dimension`";
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    $sql=sprintf("insert into `Product Category Dimension` (`Product Category Key`,`Product Category Name`,`Product Category Store Key`) values (%d,%s,%d)"
		 ,$row['Category Key']
		 ,prepare_mysql($row['Category Name'])
		 ,$row2['Store Key']
		 );
    mysql_query($sql);
    $sql=sprintf("insert into `Product Category Default Currency` (`Product Category Key`,`Product Category Store Key`) values (%d,%d)"
		 ,$row['Category Key']
		 ,$row2['Store Key']
		 );
    mysql_query($sql);
  }
}

  //  $category=new Category($row['Category Key']);
  //  $category->load('sales');
 


  //  $category->load('products_info');
  //print $category->id."\r";

mysql_free_result($result);


?>