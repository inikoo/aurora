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






$sql="select * from `Part Dimension` ";
//$sql="select * from `Part Dimension` order by `Part SKU` desc ";


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);
	
	$locations=$part->get_historic_locations();
$part->wrap_transactions();
	//print_r($locations);
	//exit;

	foreach ($locations as $location_key) {

		$part_location=new PartLocation($part->sku.'_'.$location_key);
		$old_stock=$part_location->data['Quantity On Hand'];
		$part_location->redo_adjusts();
		$new_stock=$part_location->data['Quantity On Hand'];

		if ($old_stock!=$new_stock) {
			print "Part: ".$part->sku.'_'.$location_key." $old_stock $new_stock\n";
		}


	}
	

}


?>
