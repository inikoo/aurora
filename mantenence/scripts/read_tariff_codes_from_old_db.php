<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.PartLocation.php';

include_once '../../class.SupplierProduct.php';
date_default_timezone_set('UTC');

error_reporting(E_ALL);
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


$csv_file='tariff_codes.csv';
$handle_csv = fopen($csv_file, "r");



while (($data = fgetcsv($handle_csv, 1000, ",",'"',"\n")) !== FALSE) {

	if ($data[1]=='' and $data[2]=='') {
		continue;
	}

	if ( $data[2]=='')
		$tariff_code= $data[1];
	else
		$tariff_code= $data[2];

	$product=new Product('code_store',$data[0],1);
	print $product->data['Product Code']."\r";
	if ($product->id) {
		$current_part_skus=$product->get_current_part_skus();


		foreach ($current_part_skus as $_part_sku) {
			$part=new Part($_part_sku);


			$part->update_tariff_code(substr($tariff_code,0,8));



		}
	}



};

exit;

$sql=sprintf("select id,code,export_code  from aw_old.product    ");
$result2a=mysql_query($sql);
while ($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {




	$product=new Product('code_store',$row2a['code'],1);
	print $product->data['Product Code']."\r";
	if ($product->id) {
		$current_part_skus=$product->get_current_part_skus();


		foreach ($current_part_skus as $_part_sku) {
			$part=new Part($_part_sku);


			$part->update_tariff_code(substr($row2a['export_code'],0,8));



		}
	}









}






?>
