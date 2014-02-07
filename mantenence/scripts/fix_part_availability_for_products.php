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
$count=0;




$where=" where `Part SKU`=288";
$where=' ';
$sql="select * from `Part Dimension` $where";

$resultx=mysql_query($sql);
while ($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$rowx['Part SKU']);


	if ($part->data['Part Status']=='Not In Use') {
		$availability_for_products='No';
	}else {

		$products=$part->get_current_products();
		$has_uk=false;
		$uk_online=false;
		$other_online=false;

		foreach ($products as $product) {

			//print_r($products);

			if ($product['StoreCode']=='GB' or $product['StoreCode']=='AWR' ) {
				$has_uk=true;

				if (in_array($product['ProductWebConfiguration'],array('Online Force Out of Stock','Offline'))) {
					$uk_online='No';
				}elseif ($product['ProductWebConfiguration']=='Online Auto') {
					$uk_online='Automatic';
				}else {
					$uk_online='Yes';
				}

			}else {
				if (in_array($product['ProductWebConfiguration'],array('Online Force Out of Stock','Offline'))) {
					$other_online=false;
				}else {
					$other_online=true;
				}

			}




		}


		if ($has_uk) {
			$availability_for_products=$uk_online;

		}else {
			if ($other_online) {
				$availability_for_products='Yes';
			}else {
				$availability_for_products='No';
			}

		}


		//print " Mian: $has_uk -> $uk_online ,  $other_online ; ".$part->data['Part Reference']." $availability_for_products\n";

		$part->update(array('Part Available for Products Configuration'=>$availability_for_products));


	}





}



print "xx\n";


$where='where `Product ID`=47119';
$where='';
$sql=sprintf('select `Product ID` from `Product Dimension`  %s     ',$where);
$res2=mysql_query($sql);

while ($row2=mysql_fetch_array($res2)) {

	$product=new Product('pid',$row2['Product ID']);
	if ($product->data['Product Web Configuration']=='Offline') {
		$product->update_web_state();
	}else {
		$product->update_web_configuration('Online Auto');
	}
}



?>
