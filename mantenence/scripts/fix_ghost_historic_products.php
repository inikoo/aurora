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



$sql="select * from `Product History Dimension` PH  where `Product ID`=0; ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {


	$sql=sprintf("select count(*) as num from `Order Transaction Fact` where `Product Key`=%d  ",$row['Product Key']);
	$result2=mysql_query($sql);
	while ($row2=mysql_fetch_array($result2)   ) {
		print $row2['num']."\n";
		if ($row2['num']==0) {
			$sql=sprintf("delete  from `Product History Dimension`   where `Product Key`=%d ",$row['Product Key']);
			mysql_query($sql);
			//print "$sql\n";

		}

	}
}




?>
