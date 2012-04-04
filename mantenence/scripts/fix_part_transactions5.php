<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.PartLocation.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Part Dimension` where `Part SKU`=749 order by `Part SKU`";
$sql="select * from `Part Dimension`  where `Part SKU`=30301 order by `Part SKU` desc ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);
	
	
	
	//$part->wrap_transactions();
	$locations=array();
	$was_associated=array();
	$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where `Inventory Transaction Type`='Associate' and  `Part SKU`=%d group by `Location Key` ",$row['Part SKU']);
	// print $sql;

	$result2=mysql_query($sql);

	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$_part_loc=new PartLocation($row['Part SKU'].'_'.$row2['Location Key']) ;

		$sql=sprintf("select `Inventory Transaction Type`,`Date` from `Inventory Transaction Fact`  where `Inventory Transaction Type` in ('Associate','Disassociate') and  `Part SKU`=%d and `Location Key`=%d order by `Date` desc ",$row['Part SKU'],$row2['Location Key']);
		//print "$sql\n";
		$result3=mysql_query($sql);

		if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
			//print_r($row3);
			if ($row3['Inventory Transaction Type']=='Disassociate') {
				$sql=sprintf("delete from `Part Location Dimension`  where   `Part SKU`=%d and `Location Key`=%d  ",$row['Part SKU'],$row2['Location Key']);
				print "$sql\n";
				mysql_query($sql);
			}else {
				$pl_data=array(
					'Part SKU'=>$row['Part SKU'],
					'Location Key'=>$row2['Location Key'],
					'Date'=>$row3['Date']);
				print_r($pl_data);
				$part_location=new PartLocation('find',$pl_data,'create');


				$part_location->redo_adjusts();
				continue;
			}



		}else {
			$sql=sprintf("delete from `Part Location Dimension`  where   `Part SKU`=%d and `Location Key`=%d  ",$row['Part SKU'],$row2['Location Key']);
			print "$sql\n";
			//print "$sql\n";
			mysql_query($sql);

		}
		$_part_loc->redo_adjusts();

		//print $row['Part SKU']."\r";


	}
	
	
		$sql=sprintf("select ITF.`Location Key`  from `Inventory Transaction Fact` ITF    where  `Part SKU`=%d group by `Location Key` ",$row['Part SKU']);
	print $sql;

	$result2=mysql_query($sql);

	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$part_location=new PartLocation($row['Part SKU'].'_'.$row2['Location Key']);
		$part_location->redo_adjusts();

	}
	

}

?>
