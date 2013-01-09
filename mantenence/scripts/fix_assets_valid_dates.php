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

$limit_low='2003-01-01 00:00:00';

$sql="select * from `Store Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	if ($row['Store Valid From']=='') {

		$sql=sprintf("select min(`Order Date`) as date  from `Order Dimension` where `Order Store Key`=%d and `Order Date`>%s",
			$row['Store Key'],
			prepare_mysql($limit_low)

		);

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2)) {
			$sql=sprintf("update `Store Dimension` set `Store Valid From`=%s  where  `Store Key`=%d ",
				prepare_mysql($row2['date']),
				$row['Store Key']);
			mysql_query($sql);
		}
	}
}

$sql="select * from `Product Department Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	if ($row['Product Department Valid From']=='') {

		$sql=sprintf("select min(`Order Date`) as date  from `Order Transaction Fact` where `Product Department Key`=%d and `Order Date`>%s",
			$row['Product Department Key'],
			prepare_mysql($limit_low)

		);

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2)) {

			if ($row2['date']=='') {
				$sql=sprintf("update `Product Department Dimension` set `Product Department Valid From`=NOW()  where  `Product Department Key`=%d ",
					$row['Product Department Key']);
				mysql_query($sql);
			}else {

				$sql=sprintf("update `Product Department Dimension` set `Product Department Valid From`=%s  where  `Product Department Key`=%d ",
					prepare_mysql($row2['date']),
					$row['Product Department Key']);
				mysql_query($sql);
			}
		}
	}
}

$sql="select * from `Product Family Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	//print_r($row);

	if ($row['Product Family Valid From']=='') {

		$sql=sprintf("select min(`Order Date`) as date  from `Order Transaction Fact` where `Product Family Key`=%d and `Order Date`>%s",
			$row['Product Family Key'],
			prepare_mysql($limit_low)

		);
		//print "$sql\n";
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2)) {
			if ($row2['date']=='') {
				$sql=sprintf("update `Product Family Dimension` set `Product Family Valid From`=NOW()  where  `Product Family Key`=%d ",
					$row['Product Family Key']);
				mysql_query($sql);
			}else {

				$sql=sprintf("update `Product Family Dimension` set `Product Family Valid From`=%s  where  `Product Family Key`=%d ",
					prepare_mysql($row2['date']),
					$row['Product Family Key']);
				mysql_query($sql);
			}
		}
		//print "$sql\n";
	}
}


?>
