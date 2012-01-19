<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           




$i=1;






$sql=sprintf("select `Part Unit Description`,`Part SKU` from `Part Dimension` limit 2000000 ");
$res_code=mysql_query($sql);
while($row=mysql_fetch_array($res_code)){
$sql=sprintf("update `Part Dimension` set `Part Unit Description`=%s where `Part SKU`=%d "

,prepare_mysql(strip_tags($row['Part Unit Description']))

,$row['Part SKU']
);
mysql_query($sql);

$i++;

print "$i \r";

}

?>