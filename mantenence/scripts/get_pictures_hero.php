<?php

date_default_timezone_set('UTC');

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Image.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

$to_stop=0;

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='kaw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$store=new Store('code','Hero');

$pics=array();

$path="pictures_hero/";
$img_array_full_path = glob($path."*.jpg");
//print_r($img_array_full_path);
foreach ($img_array_full_path as $pic_path) {
	$_pic_path=preg_replace('/.*\//','',$pic_path);
	if (preg_match('/.jpg/',$_pic_path)) {
		// print "$pic_path\n";
		$pics[]=$pic_path;





	}
}


foreach ($pics as $pic) {

	$image_name=preg_replace('/.*\//','',$pic);

	$product_code=preg_replace('/\.jpg$/','',$image_name);

	$product_code=preg_replace('/\-[12345]$/','',$product_code);

//	print "img: $pic $product_code\n";

	$product=new Product('code_store',$product_code,$store->id);
	if ($product->id) {
		$image_data=array(
			'file'=>$pic,
			'source_path'=>'',
			'name'=>$image_name,
			'caption'=>''
		);



		$image=new Image('find',$image_data,'create');
		$product->add_image($image->id);


	}
	$family=new Family('code_store',$product_code,$store->id);
	if ($product->id) {
		$image_data=array(
			'file'=>$pic,
			'source_path'=>'',
			'name'=>$image_name,
			'caption'=>''
		);


		//print_r($image_data);
		$image=new Image('find',$image_data,'create');
		$family->add_image($image->id);


	}


}

$sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Main Image Key`=0");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$family=new Family($row['Product Family Key']);

	$sql2=sprintf("select `Product Main Image Key` from `Product Dimension` where `Product Family Key`=%d and `Product Main Image Key`>0",$family->id);
	$res2=mysql_query($sql2);
	if ($row2=mysql_fetch_assoc($res2)) {
		$family->add_image($row2['Product Main Image Key']);

	}

}




?>
