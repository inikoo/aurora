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
include_once('../../class.Customer.php');

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

 
$sql="delete from `Dashboard User Bridge` ";
mysql_query($sql);

$sql="select * from `User Dimension`  where `User Type`='Staff' or  `User Type`='Administrator'  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


//new dashboard widget showing last updates in twitter available to all users
$sql=sprintf("insert into  `Dashboard User Bridge` (`User key`,`Dashboard Order`,`Dashboard Class`,`Dashboard URL`) values (%d,1,'block_3','dashboard_block.php?tipo=sales_overview')",$row['User Key']);
print "$sql\n";
mysql_query($sql);

$sql=sprintf("insert into  `Dashboard User Bridge` (`User key`,`Dashboard Order`,`Dashboard Class`,`Dashboard URL`) values (%d,2,'block_1','splinter_twitter.php?')",$row['User Key']);
print "$sql\n";
mysql_query($sql);
	
}
?>
