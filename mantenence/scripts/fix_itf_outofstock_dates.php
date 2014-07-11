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

$sql="select count(*) as num ,`Date`,`Part SKU`,`Location Key`,GROUP_CONCAT(`Inventory Transaction Type`) as types,GROUP_CONCAT(`Inventory Transaction Key`) as types_keys from `Inventory Transaction Fact` where `Part SKU`=24695  group by `Part SKU`,`Location Key`,`Date` ";
$sql="select * from `Inventory Transaction Fact`    where `Out of Stock`>0  and `Picked`=0 order by `Date` desc";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$dn=new DeliveryNote($row['Delivery Note Key']);
	
	if($dn->data['Delivery Note State']='Dispatched' and $dn->data['Delivery Note Date']!=''){
	
	$sql=sprintf("update `Inventory Transaction Fact` set   `Date`=%s where `Inventory Transaction Key`=%d",
	prepare_mysql($dn->data['Delivery Note Date']),
	$row['Inventory Transaction Key']
	);
	print "$sql\n";
	mysql_query($sql);
	}

}






?>


