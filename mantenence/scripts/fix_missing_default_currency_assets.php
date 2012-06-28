<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;



$sql="select `Product ID` from `Product Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$sql=sprintf("insert into  `Product ID Default Currency`  (`Product ID`) values (%d) ",$row['Product ID']);
	mysql_query($sql);
}
$sql="select `Product Family Key` from `Product Family Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$sql=sprintf("insert into  `Product Family Default Currency`  (`Product Family Key`) values (%d) ",$row['Product Family Key']);
	mysql_query($sql);
}



?>
