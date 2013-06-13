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


$sql=sprintf("update `Product Dimension` set `Product Barcode Type`='none' , `Product Barcode Data Source`='ID' ,`Product Barcode Data`='' "
	
			);
			mysql_query($sql);

$csv_file='barcodes.csv';


$handle_csv = fopen($csv_file, "r");
$column=0;

$count=0;

$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {




	if (_trim($_cols[1])!='' and _trim($_cols[1])!='') {
		$barcode=_trim($_cols[1]);
		$code=_trim($_cols[2]);

		if($code=='')
			continue;

		$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Code`=%s",
			prepare_mysql($code)
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$product=new Product('pid',$row['Product ID']);
			/*
			$list_parts=$product->get_all_part_skus();

			foreach ($list_parts as $sku) {
				$part=new Part($sku);
				$part->update_duty_rate($duty_rate);
				$part->update_tariff_code($tariff_code);

			}
			*/
			$sql=sprintf("update `Product Dimension` set `Product Barcode Type`='ean13' , `Product Barcode Data Source`='Other' ,`Product Barcode Data`=%s where `Product Code`=%s",
				prepare_mysql($barcode),
				prepare_mysql($code)
			);
			//print "$sql\n";
			mysql_query($sql);
		}
	}

}


?>
