<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;



$sql="select `Product ID` from `Product Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {

	$sql=sprintf("select count(*) as num from `Product ID Default Currency` where `Product ID`=%d  ",
		$row['Product ID']
	);
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
		if ($row2['num']==0) {
			$sql=sprintf("insert into  `Product ID Default Currency`  (`Product ID`) values (%d) ",$row['Product ID']);
			mysql_query($sql);
			print "$sql\n";
		}
	}
}
$sql="select `Product Family Key` from `Product Family Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {


	$sql=sprintf("select count(*) as num from `Product Family Default Currency` where `Product Family Key`=%d  ",
		$row['Product Family Key']
	);
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
		if ($row2['num']==0) {
			$sql=sprintf("insert into  `Product Family Default Currency`  (`Product Family Key`) values (%d) ",$row['Product Family Key']);
			mysql_query($sql);
			print "$sql\n";
		}
	}
}



?>
