<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.Location.php';
include_once '../../class.PartLocation.php';


error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');
//update `Part Location Dimension` set `Can Pick`='Yes';

$date='2012-05-06 14:00:00';
$date='2012-05-22 14:00:00';
$origin='147583';

$csv_file='delivery.csv';

$handle_csv = fopen($csv_file, "r");
while (($_cols = fgetcsv($handle_csv))!== false) {

	if (  $_cols[1]!='' and $_cols[2]!='' ) {

		print_r($_cols);
		

		$product=new Product('code_store',$_cols[1],1);		
		if(!$product->id){
		print "mmm  ".$_cols[1]." \n";
			continue;
		}
		
		
			$parts=$product->get_part_list();
			//print_r($parts);

			$parts_data=array_pop($parts);
			$part_sku=$parts_data['Part SKU'];
		
		
		if($_cols[0]!=''){
		
		$used_for='Picking';
		$location_data=array(
			'Location Warehouse Key'=>1,
			'Location Warehouse Area Key'=>1,
			'Location Code'=>$_cols[0],
			'Location Mainly Used For'=>$used_for
		);

		//$location=new Location('find',$location_data,'create');
		}else{
		
		$part=new Part($part_sku);
		$locations=$part->get_picking_location_key($date);
		//print_r($locations);
		//exit;
		$location_data=array_pop($locations);
		
		
		$location=new Location($location_data['location_key']);
		
		}




		if ($location->id) {




			

			



			$location_key=$location->id;



			$part_location_data=array(
				'Location Key'=>$location_key
				,'Part SKU'=>$part_sku

			);

			if($part_sku){


			$part_location=new PartLocation('find',$part_location_data,'create');
			$note='';
			$qty= (float) $_cols[2];
			//print "$qty\n";
			
			
			$add_data=array('Quantity'=>$qty,'Origin'=>$origin);
			
			
			
			
			$part_location->add_stock($add_data,$date);
			}
			else{
				print 'Error adding: '.$_cols[0].','.$_cols[1].','.$_cols[2]."\n";
			
			}
			
			
		}



	}


}




?>
