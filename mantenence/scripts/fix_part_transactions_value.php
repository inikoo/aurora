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




$sql="select `Inventory Transaction Quantity`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,`Part SKU`,`Date` from `Inventory Transaction Fact` order by `Date` desc ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	if($row['Inventory Transaction Quantity']==0){
		$transaction_value=0;
	
	}else{
	
		$part=new Part($row['Part SKU']);
		$cost=$part->get_unit_cost($row['Date']);
		$transaction_value=$row['Inventory Transaction Quantity']*$cost;
		
	
	
	}
//print $row['Date']." $transaction_value\n";

	
		$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%.3f where `Inventory Transaction Key`=%d ",
		$transaction_value,
		$row['Inventory Transaction Key']
		
		);
		//print $sql;
		mysql_query($sql);

	}




?>
