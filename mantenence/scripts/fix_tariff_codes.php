<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.Charge.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';
include_once 'local_map.php';

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
$codigos=array();


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$set_part_as_available=false;

$csv_file='tariff_codes.csv';



$sql2=sprintf("select * from `Part Dimension`");

$res2=mysql_query($sql2);
while ($row2=mysql_fetch_assoc($res2)) {
	$part=new Part($row2['Part SKU']);
	if ($part->data['Part Tariff Code']!=='') {
		$product_ids=$part->get_product_ids();

		foreach ($product_ids as $product_id) {

			$product=new Product('pid',$product_id);
			
			if(!$product->id){
				print $part->sku."  $product_id \n";
			}else{
			//print $product->data['Product Use Part Tariff Data']."\n";
			if ($product->data['Product Use Part Tariff Data']=='Yes' and $product->data['Product Tariff Code']=='') {
				
				$product->update_field('Product Tariff Code',$part->data['Part Tariff Code']);
				if($product->updated){
					printf("%s %s %s %s\n",$product->data['Product Code'],$product->data['Product Store Key'],$product->data['Product ID'],$product->data['Product Main Type']);
				}
				
				}
		}
		}

	
	}
	
	if ($part->data['Part Duty Rate']!=='') {
		$product_ids=$part->get_product_ids();

		foreach ($product_ids as $product_id) {

			$product=new Product('pid',$product_id);
			if(!$product->id){
				//print $part->sku."\n";
			}else{
			if ($product->data['Product Use Part Tariff Data']=='Yes' and $product->data['Product Duty Rate']=='') {
				$product->update_field('Product Duty Rate',$part->data['Part Duty Rate']);
			}
			}
		}


	}
	
}
exit;

/*
$column=0;

$count=0;

$sql2=sprintf("select `Product Tariff Code`,`Product ID`,`Product Duty Rate`,`Product Code` from `Product Dimension`  where    `Product Tariff Code` is  null  ");
$res2=mysql_query($sql2);
while ($row2=mysql_fetch_assoc($res2)) {

	$product=new Product('pid',$row['Product ID']);
	$list_parts=$product->get_all_part_skus();


	$sql=sprintf("update `Product Dimension` set `Product Tariff Code`=%s ,`Product Duty Rate`=%s where `Product Code`=%s",
		prepare_mysql($tariff_code),
		prepare_mysql($duty_rate),
		prepare_mysql($code)
	);

}
exit;
//  $tariff_code=sprintf("%010d",_trim($row2['Product Tariff Code']));
//  $duty_rate=_trim($row2['Product Duty Rate']);
//  $code=_trim($row2['Product Tariff Code']);

$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Code`=%s",
	prepare_mysql($code)
);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$product=new Product('pid',$row['Product ID']);
	$list_parts=$product->get_all_part_skus();

	foreach ($list_parts as $sku) {
		$part=new Part($sku);
		$part->update_duty_rate($duty_rate);
		$part->update_tariff_code($tariff_code);

	}
	$sql=sprintf("update `Product Dimension` set `Product Tariff Code`=%s ,`Product Duty Rate`=%s where `Product Code`=%s",
		prepare_mysql($tariff_code),
		prepare_mysql($duty_rate),
		prepare_mysql($code)
	);
	mysql_query($sql);
}
}



*/

?>
