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




$sql="select * from `Part Dimension` where `Part SKU`=37062 order by `Part SKU`";
$sql="select * from `Part Dimension` order by `Part SKU`  ";


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$locations=$part->get_historic_locations();
	foreach ($locations as $location_key) {

		$part_location=new PartLocation($part->sku.'_'.$location_key);

		$part_location->redo_adjusts();
		$part_location->update_stock();


		$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate','Disassociate')  and `Part SKU`=%d and `Location Key`=%d order by `Date` ",
			$part->sku,
			$location_key
		);
		$result2=mysql_query($sql);
		$last=false;
		$itf_key=false;
		while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
			print $row2['Date'].' '.$row2['Inventory Transaction Type'].' '.$part->sku.'_'.$location_key."\n";
			if (!$last) {

				if ($row2['Inventory Transaction Type']=='Disassociate') {
					exit("shit no associated in the begining\n");
				}

				$last=$row2['Inventory Transaction Type'];
				$itf_key=$row2['Inventory Transaction Key'];
			}else {

				$current=$row2['Inventory Transaction Type'];

				if ($current==$last) {
					// print "shit dupplicated\n";

					if ($current=='Associate') {
						$sql=sprintf("delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d",$row2['Inventory Transaction Key']);
						print "$sql\n";
						mysql_query($sql);
					}

					if ($current=='Disassociate') {
						$sql=sprintf("delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d",$itf_key);
						//print "$sql\n";
						mysql_query($sql);
					}

				}

				$last=$row2['Inventory Transaction Type'];
				$itf_key=$row2['Inventory Transaction Key'];

			}

		}

		$part_location->redo_adjusts();
		$part_location->update_stock();

	}

}



?>
