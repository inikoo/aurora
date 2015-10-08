<?php
date_default_timezone_set('UTC');

include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Image.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';


$sql=sprintf("select `Product Family Key`,`Product Family Code`,`Product Family Store Key`,`Product Family Main Image Key` from `Product Family Dimension` where `Product Family Main Image Key`=0  and `Product Family Store Key`=1");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	if ( !$row['Product Family Main Image Key']) {
		$family=new Family($row['Product Family Key']);
		$reference=$family->data['Product Family Code'];
		$image_name=strtolower($family->data['Product Family Code']).".jpg";

		$tmp_file='/tmp/'.$image_name;

		$url='http://aw.inikoo.com/public_image.php?store_key=1&family='.$family->data['Product Family Code'];
		//print "$url\n";
		if (@getimagesize($url)) {
			if (file_put_contents($tmp_file, file_get_contents($url))) {
				print "$reference $tmp_file\n";

				$image_data=array(
					'file'=>$tmp_file,
					'source_path'=>'',
					'name'=>$image_name,
					'caption'=>''
				);
				$image=new Image('find',$image_data,'create');
				if (!$image->error) {
					$family->add_image($image->id);
					$family->update_main_image($image->id);
				}
				unlink($tmp_file);
			}

		}
	}


}

$sql=sprintf("select `Product ID`,`Product Code`,`Product Store Key`,`Product Main Image Key` from `Product Dimension` where  `Product Main Image Key`=0  and `Product Store Key`=1");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	if ( !$row['Product Main Image Key']) {
		$product=new Product('pid',$row['Product ID']);
		$reference=$product->data['Product Code'];
		$image_name=strtolower($product->data['Product Code']).".jpg";

		$tmp_file='/tmp/'.$image_name;

		$url='http://aw.inikoo.com/public_image.php?store_key=1&product='.$product->data['Product Code'];
		//print "$url\n";
		if (@getimagesize($url)) {
			if (file_put_contents($tmp_file, file_get_contents($url))) {
				print "$reference $tmp_file\n";

				$image_data=array(
					'file'=>$tmp_file,
					'source_path'=>'',
					'name'=>$image_name,
					'caption'=>''
				);
				$image=new Image('find',$image_data,'create');
				if (!$image->error) {
					$product->add_image($image->id);
					$product->update_main_image($image->id);
				}
				unlink($tmp_file);
			}

		}
	}


}



?>
