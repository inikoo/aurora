<?php
include_once('../../conf/dns.php');
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






$sql=sprintf("select * from `Order No Product Transaction Fact` limit 2000000 ");
$res_code=mysql_query($sql);
while($row=mysql_fetch_array($res_code)){
$sql=sprintf("update `Order No Product Transaction Fact` set `Order No Product Transaction Fact Key`=%d where `Transaction Net Amount`=%s and `Transaction Type`=%s  and `Transaction Description`=%s and `Invoice Date`=%s  and `Invoice Key`=%d  "
,$i
,prepare_mysql($row['Transaction Net Amount'])

,prepare_mysql($row['Transaction Type'])
,prepare_mysql($row['Transaction Description'])

,prepare_mysql($row['Invoice Date'])
,$row['Invoice Key']
);

mysql_query($sql);

$i++;

print number($i)."\r";

}

?>