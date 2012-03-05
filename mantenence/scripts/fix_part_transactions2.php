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
$sql="select count(*) as num ,`Date`,`Part SKU`,`Location Key`,GROUP_CONCAT(`Inventory Transaction Type`) as types,GROUP_CONCAT(`Inventory Transaction Key`) as types_keys from `Inventory Transaction Fact`  group by `Part SKU`,`Location Key`,`Date` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	if ($row['num']>1) {
		// print $row['types']."\n";
		if ($row['types']=='Sale,Associate,Audit' or $row['types']=='Audit,Associate,Sale' ) {

			print $row['types']."\n";
			print $row['types_keys']."\n";
			$types=preg_split('/,/',$row['types']);
			$types_keys=preg_split('/,/',$row['types_keys']);
			print_r($types_keys);
			print_r($types);
			foreach ($types as $key=>$value) {
				$itypes[$value]=$key;
			}
			print_r($itypes);
			print "xxxx\n";
			$keys=$types_keys;
			asort($keys);
			print_r($keys);
			//print_r($types_keys);
			$_types=array();
			foreach ($types_keys as $key=>$value) {
				$_types[$value]=$types[$key];
			}
			//ksort($_types);
			print_r($_types);

			$_types_keys=array();
			foreach ($types as $key=>$value) {
				$_types_keys[$value]=$types_keys[$key];
			}
			// print_r($_types_keys);

			$master=array();


			foreach ($types_keys as $key=>$value) {
				$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Key`=$key where `Inventory Transaction Key`=%d",$value);mysql_query($sql);
				//print "$sql\n";
			}


			$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Key`=%d where `Inventory Transaction Key`=%d",array_shift($keys),$itypes['Associate']);mysql_query($sql);
			//print "$sql\n";
			$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Key`=%d where `Inventory Transaction Key`=%d",array_shift($keys),$itypes['Audit']);mysql_query($sql);
			//print "$sql\n";
			$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Key`=%d where `Inventory Transaction Key`=%d",array_shift($keys),$itypes['Sale']);mysql_query($sql);
			//print "$sql\n";


			//exit;
		
		}

		//print $row['types']."\n";
	}
}






?>


