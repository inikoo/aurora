<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.PartLocation.php');

error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$not_found=00;

$force_first_day=true;
$first_day_with_data=strtotime("2007-03-24");


$where='';

$sql="select * from `Location Dimension`  ";

$resultx=mysql_query($sql);
$counter=1;
while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
  $location= new Location($rowx['Location Key']);
  $location->load('parts_data');
 }


?>