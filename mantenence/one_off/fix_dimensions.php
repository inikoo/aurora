<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$options='no_history';

//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Part Dimension` where `Part SKU`=47561 order by `Part SKU`";
//$sql="select `Part SKU` from `Part Dimension`   order by `Part SKU`";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$part->update_field_switcher('Part Package XHTML Dimensions',$part->get_xhtml_dimensions('Package'),'no_history');
	$part->update_field_switcher('Part Unit XHTML Dimensions',$part->get_xhtml_dimensions('Unit'),'no_history');

	$product_ids=$part->get_product_ids();
	foreach ($product_ids as $product_id) {
		$product=new Product('pid',$product_id);
		if ($product->data['Product Use Part Properties']=='Yes' ) {
			$product->update_field_switcher('Product Package XHTML Dimensions',$part->data['Part Package XHTML Dimensions'],'no_history');
			$product->update_field_switcher('Product Unit XHTML Dimensions',$part->data['Part Unit XHTML Dimensions'],'no_history');
		}

	}



}


?>
