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

$sql="select * from `Product Family Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

$family=new Family($row['Product Family Key']);
	$department=new Department($family->data['Product Family Main Department Key']);



	$sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s where `Product Family Key`=%d",
		$department->id,
		prepare_mysql($department->data['Product Department Code']),
		prepare_mysql($department->data['Product Department Name']),
		$family->id);
		mysql_query($sql);

}

$sql="select * from `Product Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$family=new Family($row['Product Family Key']);
	$department=new Department($family->data['Product Family Main Department Key']);



		
		
		
	$sql=sprintf("update `Product Dimension` set `Product Family Code`=%s,`Product Family Name`=%s,`Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s where `Product ID`=%d",
		prepare_mysql($family->data['Product Family Code']),
		prepare_mysql($family->data['Product Family Name']),
		$department->id,
		prepare_mysql($department->data['Product Department Code']),
		prepare_mysql($department->data['Product Department Name']),
		$row['Product ID']);
	mysql_query($sql);
	
	
	$sql=sprintf("update `Order Transaction Fact` set `Product Family Key`=%d,`Product Department Key`=%d  where `Product ID`=%d",
		$family->data['Product Family Key'],
		$family->data['Product Family Main Department Key'],
		$row['Product ID']

	);
//	print "$sql\n";
	mysql_query($sql);
	
}





?>
