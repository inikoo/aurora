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


$sql="select * from `Part Dimension` order by `Part SKU`";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$supplier_products=$part->get_supplier_products();
	//print_r($supplier_products);

	$sup_code=array();
	foreach ($supplier_products as $supplier_product) {
		if ( $supplier_product['Supplier Product Code']=='' or  preg_match('/\?/',$supplier_product['Supplier Product Code'])  )
			continue;
		$sup_code[strtolower($supplier_product['Supplier Product Code'])]=$supplier_product['Supplier Product Code'];
	}


	$scode='';
	// print_r($sup_code);
	if (count($sup_code)>0) {
		$scode='('.join(',',$sup_code).')';
	}

	if ($scode!='') {

		print $part->sku." $scode\n";
		if (!preg_match('/\)$/',$part->data['Part Unit Description'])) {
			$description= $part->data['Part Unit Description'].' '.$scode;
			$part->update(array('Part Unit Description'=>$description));
			print "Part ".$part->data['Part SKU'].' '.$scode."\n";
		}

	}
	// print $row['Part SKU']."\r";


}


exit;

$sql=sprintf("select code,sup_code  from aw_old.product  left join aw_old.product2supplier on (product_id=aw_old.product.id)   where code='Sel-04'  ");
$result2a=mysql_query($sql);
while ($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ) {

	if ($row2a['sup_code']=='' or preg_match('/\?/',$row2a['sup_code']))
		continue;




	$product=new Product('code_store',$row2a['code'],1);
	print_r($product);
	print $product->code."\n";

	if ($product->id) {
		$current_part_skus=$product->get_all_part_skus();


		foreach ($current_part_skus as $_part_sku) {
			$count++;
			$part=new Part($_part_sku);
			if (!preg_match('/\)$/',$part->data['Part Unit Description'])) {
				$description= $part->data['Part Unit Description'].' ('.$row2a['sup_code'].')';
				$part->update(array('Part Unit Description'=>$description));
				print "Part ".$part->data['Part SKU'].' '.$row2a['sup_code']."\n";
			}





		}
	}









}






?>
