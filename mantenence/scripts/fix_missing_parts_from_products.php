<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 Inikoo , based in 2009 Fix_Missing_Part_List.php
include_once '../../app_files/db/dns.php';
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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');



$sql="select * from `Product Dimension` left join `Store Dimension` on (`Store Key`=`Product Store Key`) where `Product Record Type`='Normal' and `Product Sales Type`='Public Sale' order by `Product Store Key`,`Product Code`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$product=new Product('pid',$row['Product ID']);


	if ($product->get_number_of_parts()==0) {
		//print $row['Store Code'].' '.$product->data['Product Code'].' '.$product->data['Product ID']." ".$product->data['Product Sales Type']."  \n";

				print $row['Store Code'].' '.$product->data['Product Code']." \n";

		continue;
		
		$supplier=new Supplier('code','UNK');
		$supplier_cost=0.4*$product->data['Product Price'];
		$scode='?'.$product->data['Product Code'];

		$sp_data=array(

			'Supplier Key'=>$supplier->id,
			'Supplier Product Code'=>$scode,
			'Supplier Product Units Per Case'=>1,
			'SPH Case Cost'=>sprintf("%.2f",$supplier_cost),
			'Supplier Product Name'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description'))),
			'Supplier Product Description'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description'))),
			'Supplier Product Valid From'=>$product->data['Product Valid From'],
			'Supplier Product Valid To'=>$product->data['Product Valid To'],
		);
		//  print_r($sp_data);
		$supplier_product=new SupplierProduct('find',$sp_data,'create');



		$part_data=array(

			'Part Most Recent'=>'Yes',
			'Part Unit Description'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description'))),

			'part valid from'=>$product->data['Product Valid From'],
			'part valid to'=>$product->data['Product Valid To'],
		);
		$part=new Part('new',$part_data);
		$part_lisarray();
		$part_list[]=array(
			'Part SKU'=>$part->get('Part SKU'),
			'Parts Per Product'=>1,
			'Product Part Type'=>'Simple'
		);
		$spp_header=array(
			'Supplier Product Part Type'=>'Simple',
			'Supplier Product Part Most Recent'=>'Yes',
			'Supplier Product Part Valid From'=>$product->data['Product Valid From'],
			'Supplier Product Part Valid To'=>$product->data['Product Valid From'],
			'Supplier Product Part In Use'=>'Yes'
		);

		$spp_list=array(
			array(
				'Part SKU'=>$part->data['Part SKU'],
				'Supplier Product Units Per Part'=>1,
				'Supplier Product Part Type'=>'Simple'
			)
		);



		$supplier_product->new_current_part_list($spp_header,$spp_list);


		$product->new_current_part_list(array(),$part_list)  ;

		$product->update_parts();
		$part->update_used_in();
		$part->update_supplied_by();
		$product->update_cost();
		$product->update_cost();

	}


}
mysql_free_result($result);


?>
