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
include_once '../../common_units_functions.php';
include_once '../../common_geometry_functions.php';

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

$sql="select `Part SKU` from `Part Dimension` order by  `Part SKU` desc";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part($row['Part SKU']);
	if ($part->data['Part Unit Weight Display']!='') {
		$value_in_standard_units=convert_units($part->data['Part Unit Weight Display'],$part->data['Part Unit Weight Display Units'],'Kg');

	}else {
		$value_in_standard_units='';
	}
	$sql=sprintf("update `Part Dimension` set `Part Unit Weight`=%s where `Part SKU`=%d",prepare_mysql($value_in_standard_units),$part->sku);
	mysql_query($sql);

	if ($part->data['Part Package Weight Display']!='') {
		$value_in_standard_units=convert_units($part->data['Part Package Weight Display'],$part->data['Part Package Weight Display Units'],'Kg');

	}else {
		$value_in_standard_units='';
	}
	$sql=sprintf("update `Part Dimension` set `Part Package Weight`=%s where `Part SKU`=%d",prepare_mysql($value_in_standard_units),$part->sku);
	mysql_query($sql);







	$tag='Package';
	$value=$part->data["Part $tag Dimensions Display Units"];
	$width_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Width Display'],$value,'m');
	$depth_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Depth Display'],$value,'m');
	$length_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Length Display'],$value,'m');
	$diameter_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Diameter Display'],$value,'m');




	$sql=sprintf("update `Part Dimension` set `Part $tag Dimensions Width`=%s,`Part $tag Dimensions Depth`=%s,`Part $tag Dimensions Length`=%s,`Part $tag Dimensions Diameter`=%s  where `Part SKU`=%d",
		prepare_mysql($width_in_standard_units),
		prepare_mysql($depth_in_standard_units),
		prepare_mysql($length_in_standard_units),
		prepare_mysql($diameter_in_standard_units),

		$part->sku);
	mysql_query($sql);

	$part->get_data('sku',$part->sku);

	//print $sql;


	$volume=get_volume($part->data["Part $tag Dimensions Type"],$part->data["Part $tag Dimensions Width"],$part->data["Part $tag Dimensions Depth"],$part->data["Part $tag Dimensions Length"],$part->data["Part $tag Dimensions Diameter"]);

	if (is_numeric($volume) and $volume>0) {
		$volume_xhtml=$part->get_xhtml_dimensions($tag);
	}else {
		$volume='';
		$volume_xhtml='';
	}
	$sql=sprintf("update `Part Dimension` set `Part $tag Dimensions Volume`=%s , `Part $tag XHTML Dimensions`=%s where `Part SKU`=%d",
		prepare_mysql($volume),
		prepare_mysql($volume_xhtml),

		$part->sku);
	mysql_query($sql);

	$tag='Unit';

	$value=$part->data["Part $tag Dimensions Display Units"];
	$width_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Width Display'],$value,'m');
	$depth_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Depth Display'],$value,'m');
	$length_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Length Display'],$value,'m');
	$diameter_in_standard_units=convert_units($part->data['Part '.$tag.' Dimensions Diameter Display'],$value,'m');



	$sql=sprintf("update `Part Dimension` set `Part $tag Dimensions Width`=%s,`Part $tag Dimensions Depth`=%s,`Part $tag Dimensions Length`=%s,`Part $tag Dimensions Diameter`=%s  where `Part SKU`=%d",
		prepare_mysql($width_in_standard_units),
		prepare_mysql($depth_in_standard_units),
		prepare_mysql($length_in_standard_units),
		prepare_mysql($diameter_in_standard_units),

		$part->sku);
	mysql_query($sql);
	$part->get_data('sku',$part->sku);

	$volume=get_volume($part->data["Part $tag Dimensions Type"],$part->data["Part $tag Dimensions Width"],$part->data["Part $tag Dimensions Depth"],$part->data["Part $tag Dimensions Length"],$part->data["Part $tag Dimensions Diameter"]);

	if (is_numeric($volume) and $volume>0) {
		$volume_xhtml=$part->get_xhtml_dimensions($tag);
	}else {
		$volume='';
		$volume_xhtml='';
	}
	$sql=sprintf("update `Part Dimension` set `Part $tag Dimensions Volume`=%s , `Part $tag XHTML Dimensions`=%s where `Part SKU`=%d",
		prepare_mysql($volume),
		prepare_mysql($volume_xhtml),

		$part->sku);
	mysql_query($sql);



}


$sql="select `Product ID` from `Product Dimension` order by  `Product ID` desc ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);
	$product->update_part_ratio();
	if ($product->data['Product Use Part Properties']=='Yes' ) {
		$product->update_weight_from_parts('Package');
		if ( $product->data['Product Part Units Ratio']==1) {
			$product->update_volume_from_parts('Package');

		}else{
		$sql=sprintf("update `Product Dimension` set `Product Package Dimensions Type`='Rectangular',`Product Package Dimensions Display Units`='cm',`Product Package Dimensions Width`=NULL,`Product Package Dimensions Depth`=NULL,`Product Package Dimensions Length`=NULL,`Product Package Dimensions Diameter`=NULL,`Product Package Dimensions Width Display`=NULL,`Product Package Dimensions Depth Display`=NULL,`Product Package Dimensions Length Display`=NULL,`Product Package Dimensions Diameter Display`=NULL,`Product Package Dimensions Volume`=NULL,`Product Package XHTML Dimensions`=NULL where `Product ID`=%d  ",$product->pid);
		mysql_query($sql);
			//print"X $sql\n";

		}

	}else{
		$sql=sprintf("update `Product Dimension` set `Product Package Type`='Box',`Product Package Weight`=NULL,`Product Package Weight Display`=NULL,`Product Package Weight Display Units`='Kg',`Product Package Dimensions Type`='Rectangular',`Product Package Dimensions Display Units`='cm',`Product Package Dimensions Width`=NULL,`Product Package Dimensions Depth`=NULL,`Product Package Dimensions Length`=NULL,`Product Package Dimensions Diameter`=NULL,`Product Package Dimensions Width Display`=NULL,`Product Package Dimensions Depth Display`=NULL,`Product Package Dimensions Length Display`=NULL,`Product Package Dimensions Diameter Display`=NULL,`Product Package Dimensions Volume`=NULL,`Product Package XHTML Dimensions`=NULL where `Product ID`=%d  ",$product->pid);
		mysql_query($sql);
			//print"* $sql\n";

	}
	
	
	if ($product->data['Product Use Part Units Properties']=='Yes' ) {
		$product->update_volume_from_parts('Unit');
		$product->update_weight_from_parts('Unit');

	}else{
	$sql=sprintf("update `Product Dimension` set `Product Unit Weight`=NULL,`Product Unit Weight Display`=NULL,`Product Unit Weight Display Units`='Kg',`Product Unit Dimensions Type`='Rectangular',`Product Unit Dimensions Display Units`='cm',`Product Unit Dimensions Width`=NULL,`Product Unit Dimensions Depth`=NULL,`Product Unit Dimensions Length`=NULL,`Product Unit Dimensions Diameter`=NULL,`Product Unit Dimensions Width Display`=NULL,`Product Unit Dimensions Depth Display`=NULL,`Product Unit Dimensions Length Display`=NULL,`Product Unit Dimensions Diameter Display`=NULL,`Product Unit Dimensions Volume`=NULL,`Product Unit XHTML Dimensions`=NULL where `Product ID`=%d  ",$product->pid);
		mysql_query($sql);
		print"Y $sql\n";
	}
	


print "Prod ".$product->pid."\r";

}


?>
