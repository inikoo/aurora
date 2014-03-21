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


$sql="select `Product Key`,UNIX_TIMESTAMP(`Product History Valid From`) as unix_date ,`Product History Valid From` as date  from `Product History Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("select count(*) as num, UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date from `Order Transaction Fact` where `Product Key`=%d and `Order Date`>%s",
		$row['Product Key'],
		prepare_mysql($limit_low)

	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
	
			//print $row2['unix_date'].' '.$row['unix_date']."\n";
	
		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Product History Dimension` set `Product History Valid From`=%s  where  `Product Key`=%d ",
				prepare_mysql($row2['date']),
				$row['Product Key']);
			mysql_query($sql);
			//print $row['date']." $sql\n";
		}
		//print "$sql\n";
	}
}


$sql="select `Product ID`,UNIX_TIMESTAMP(`Product Valid From`) as unix_date ,`Product Valid From` as date  from `Product Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("select count(*) as num, UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date from `Order Transaction Fact` where `Product ID`=%d and `Order Date`>%s",
		$row['Product ID'],
		prepare_mysql($limit_low)

	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
	
			//print $row2['unix_date'].' '.$row['unix_date']."\n";
	
		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Product Dimension` set `Product Valid From`=%s  where  `Product ID`=%d ",
				prepare_mysql($row2['date']),
				$row['Product ID']);
			mysql_query($sql);
			//print $row['date']." $sql\n";
		}
		//print "$sql\n";
	}
}


$sql="select `Product Code`,UNIX_TIMESTAMP(`Product Same Code Valid From`) as unix_date ,`Product Same Code Valid From` as date  from `Product Same Code Dimension  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("select count(*) as num, UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date from `Order Transaction Fact` where `Product Code`=%s and `Order Date`>%s",
		prepare_mysql($row['Product Code']),
		prepare_mysql($limit_low)

	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
	
			//print $row2['unix_date'].' '.$row['unix_date']."\n";
	
		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Product Same Code Dimension` set `Product Same Code Valid From`=%s  where  `Product Code`=%s ",
				prepare_mysql($row2['date']),
				prepare_mysql($row['Product Code'])
				);
			mysql_query($sql);
			//print $row['date']." $sql\n";
		}
		//print "$sql\n";
	}
}





$sql="select `Store Key`,UNIX_TIMESTAMP(`Store Valid From`) as unix_date ,`Store Valid From` as date  from `Store Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



	$sql=sprintf("select count(*) as num,UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date  from `Order Dimension` where `Order Store Key`=%d and `Order Date`>%s",
		$row['Store Key'],
		prepare_mysql($limit_low)

	);
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {

		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Store Dimension` set `Store Valid From`=%s  where  `Store Key`=%d ",
				prepare_mysql($row2['date']),
				$row['Store Key']);

			mysql_query($sql);

		}
	}

}



$sql="select `Product Department Key`,UNIX_TIMESTAMP(`Product Department Valid From`) as unix_date ,`Product Department Valid From` as date from `Product Department Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$sql=sprintf("select  count(*) as num, UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date  from `Order Transaction Fact` where `Product Department Key`=%d and `Order Date`>%s",
		$row['Product Department Key'],
		prepare_mysql($limit_low)

	);

	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {

		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Product Department Dimension` set `Product Department Valid From`=%s  where  `Product Department Key`=%d ",
				prepare_mysql($row2['date']),
				$row['Product Department Key']);
			mysql_query($sql);
			//print "$sql\n";
		}
	}

}


$sql="select `Product Family Key`,UNIX_TIMESTAMP(`Product Family Valid From`) as unix_date ,`Product Family Valid From` as date  from `Product Family Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	//print_r($row);



	$sql=sprintf("select count(*) as num, UNIX_TIMESTAMP(min(`Order Date`)) as unix_date,min(`Order Date`) as date from `Order Transaction Fact` where `Product Family Key`=%d and `Order Date`>%s",
		$row['Product Family Key'],
		prepare_mysql($limit_low)

	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
		if ((($row2['unix_date']<$row['unix_date']) or ($row['unix_date']==0 and $row2['unix_date']>0))and $row2['num']>0) {

			$sql=sprintf("update `Product Family Dimension` set `Product Family Valid From`=%s  where  `Product Family Key`=%d ",
				prepare_mysql($row2['date']),
				$row['Product Family Key']);
			mysql_query($sql);
			//print "$sql\n";
		}
		//print "$sql\n";
	}
}







?>
