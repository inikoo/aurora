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

$sql="delete from `Inventory Transaction Fact` where `Inventory Transaction Type`='Adjust';";
mysql_query($sql);
$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Audit'";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Quantity`=0,`Inventory Transaction Amount`=0,`Inventory Transaction Weight`=0,`Inventory Transaction Storing Charge Amount`=0,`Inventory Transaction Stock`=%f where `Inventory Transaction Key`=%d",

		$row['Inventory Transaction Quantity'],
		$row['Inventory Transaction Key']

	);

	mysql_query($sql);


}

$sql="select * from `Inventory Audit Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



	$part_location=new PartLocation($row['Inventory Audit Part SKU'].'_'.$row['Inventory Audit Location Key']);

	if ($row['Inventory Audit Note']) {
		$details='<b>'.$row['Inventory Audit Note'].'</b>, ';
	}else {
		$details='';
	}
	$details.=_('Audit').', '.'<a href="part.php?sku='.$part_location->part_sku.'">'.$part_location->part->get_sku().'</a>'.' '._('stock in').' <a href="location.php?id='.$part_location->location->id.'">'.$part_location->location->data['Location Code'].'</a> '._('set to').': <b>'.number($row['Inventory Audit Quantity']).'</b>';


	$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Stock`=%d ,`Note`=%s where `Inventory Transaction Type`='Audit' and `Date`=%s and `Part SKU`=%d and `Location Key`=%d"
		,$row['Inventory Audit Quantity']
		,prepare_mysql($details)
		,prepare_mysql($row['Inventory Audit Date'])
		,$row['Inventory Audit Part SKU']
		,$row['Inventory Audit Location Key']

	);
	mysql_query($sql);
	//print "$sql\n";
	//exit;
}

$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type`=''";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



	if (preg_match('/received in/',$row['Note'])){
		$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Type`='In' where `Inventory Transaction Key`=%d",$row['Inventory Transaction Key']);
	mysql_query($sql);
}





}


?>
