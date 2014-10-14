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
include_once('../../class.PartLocation.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

global $myconf;
$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate','Disassociate') order by Date ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  //print $row['Date']."\n";
  if($row['Inventory Transaction Type']=='Associate'){
    $pl=new PartLocation('find',
			 array('Location Key'=>$row['Location Key'],'Part SKU'=>$row['Part SKU'])
			 ,'create');
  }



 }
mysql_free_result($result);






?>