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
include_once '../../class.PartLocation.php';

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


$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Associate'  and `Part SKU`=39059 ";


$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Associate' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("select count(*) num from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Audit' and  `Part SKU`=%d and `Location Key`=%d and `Date`=%s   ",
		$row['Part SKU'],$row['Location Key'],prepare_mysql($row['Date'])
	);
	$result1=mysql_query($sql);
	if ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
		if ($row1['num']==0) {
			$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
			$audit_key=$part_location->audit(0,_('Part associated with location'),$row['Date']);
			$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d where `Inventory Transaction Key`=%d",$row['Inventory Transaction Key'],$audit_key);
			mysql_query($sql);
		}
	}
}

//print "===\n";

$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Disassociate'  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("select count(*) num from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Audit' and  `Part SKU`=%d and `Location Key`=%d and `Date`=%s   ",
		$row['Part SKU'],$row['Location Key'],prepare_mysql($row['Date'])
	);
	//print $sql;
	$result1=mysql_query($sql);
	if ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
		if ($row1['num']==0) {
			$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
			$audit_key=$part_location->audit(0,_('Part disassociate with location'),$row['Date'],$include_current=true);
			$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d where `Inventory Transaction Key`=%d",$row['Inventory Transaction Key'],$audit_key);
			mysql_query($sql);
		}
	}
}





?>
