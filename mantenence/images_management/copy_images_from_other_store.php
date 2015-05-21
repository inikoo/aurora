<?php
include_once '../../conf/dns.php';
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
include_once '../../class.Image.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$store_to_key=2;
$store_from_key=1;


$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Main Image Key`=0 and `Product Store Key`=%d",$store_to_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$product=new Product('pid',$row['Product ID']);
    
    $product2=new Product('code_store',$product->data['Product Code'],$store_from_key);

    if($product2->data['Product Main Image Key']){
        print "copy image from ".$product->data['Product Code']."\n";
        $product->add_image($product2->data['Product Main Image Key']);
    
    }

}


$sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Main Image Key`=0 and `Product Family Store Key`=%d",
$store_to_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$family=new Family('id',$row['Product Family Key']);
    
    $family2=new Family('code_store',$family->data['Product Family Code'],$store_from_key);

    if($family2->data['Product Family Main Image Key']){
        print "copy image from ".$family->data['Product Family Code']."\n";
        $family->add_image($family2->data['Product Family Main Image Key']);
    
    }

}





?>
