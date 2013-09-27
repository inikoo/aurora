<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');




$con_drop=@mysql_connect('213.175.222.120','drop_db_user',$dns_pwd );
if (!$con_drop) {
	print "Error can not connect with dropshipping database server\n";
	exit;
}
$db2=@mysql_select_db("ancient_dropshipnew", $con_drop);
if (!$db2) {
	print "Error can not access the database in drop \n";
	exit;
}

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db("dw", $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}



require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

$store=new Store('code','DS');

$department_bridge=array();
$family_bridge=array();

//print_r($store);
//exit;
$sql= "SELECT * FROM ancient_dropshipnew.`catalog_category_entity` where level=2";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {

	$code='';
	$name='';
	$description='';

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=31 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$code=preg_replace('/\s/i','',$row2['value']);
		$code=preg_replace('/\'/i','',$code);
		$code=preg_replace('/\&/i','',$code);
		$code=substr($code,0,5);
		$name=$row2['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_category_entity_text` WHERE  `entity_id` =%d  and attribute_id=38 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {

		$description=$row2['value'];
	}


	if (in_array($code,array('chess'))) {

		continue;
	}




	// print "D: -----\n";
	//print "D: ".$row['entity_id']." code:   $code\n";
	// print "name: $name\n";
	// print "description: $description\n";
	$editor['Date']=$row['created_at'];
	$department=new Department('find',array(
			'Product Department Code'=>$code
			,'Product Department Name'=>$name
			,'Product Department Description'=>$name
			,'Product Department Store Key'=>$store->id
			,'Product Department Valid From'=>$row['created_at']
			,'editor'=>$editor

		),'create');
	if ($department->id) {
		$department_bridge[$row['entity_id']]=$department->id;
	}
}





$sql= "SELECT * FROM ancient_dropshipnew.`catalog_category_entity` where level in (3) and children_count>0";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {

	$department_bridge[$row['entity_id']]=$department_bridge[$row['parent_id']];
}




$sql= "SELECT * FROM ancient_dropshipnew.`catalog_category_entity` where level in (3,4) and children_count=0";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {





	$code='';
	$name='';
	$description='';

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=31 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$code=preg_replace('/\s/i','',$row2['value']);
		$code=preg_replace('/\'/i','',$code);
		$code=preg_replace('/\&/i','',$code);
		$code=substr($code,0,5);
		$name=$row2['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_category_entity_text` WHERE  `entity_id` =%d  and attribute_id=38 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {

		$description=$row2['value'];
	}
	//print "F: parent_id ".$row['parent_id']." ";
	// print "code:   $code\n";
	// print "name: $name\n";
	//print "description: $description\n";

	if (array_key_exists($row['parent_id'],$department_bridge)) {
		$department=new Department($department_bridge[$row['parent_id']]);
	}else {
		$department=new Department('code_store','ND_'.$store->data['Store Code'],$store->id);
	}


	$editor['Date']=$row['created_at'];
	$family_data=array(

		'Product Family Code'=>$code,
		'Product Family Name'=>$name,
		'Product Family Description'=>$description,
		'Product Family Special Characteristic'=>$name,
		'Product Family Main Department Key'=>$department->id,
		'Product Family Store Key'=>$department->data['Product Department Store Key'],
		'Product Family Valid From'=>$row['created_at'],
		'editor'=>$editor

	);

	//print_r($family_data);

	$family=new Family('create',$family_data);
	if ($family->id) {
		$family_bridge[$row['entity_id']]=$family->id;
	}




}

$sql= "SELECT * FROM ancient_dropshipnew.`catalog_product_entity` where sku is not NULL and sku not in ('EO-')  ";
$res=mysql_query($sql,$con_drop);
while ($row=mysql_fetch_assoc($res)) {

	$store_code=$store->data['Store Code'];
	$order_data_id=$row['entity_id'];

	$sql=sprintf("select * from `Product Import Metadata` where `Metadata`=%s and `Import Date`>=%s",
		prepare_mysql($store_code.$order_data_id),
		prepare_mysql($row['updated_at'])

	);
	$resxx=mysql_query($sql);
	if ($rowxx=mysql_fetch_assoc($resxx)) {

		continue;
	}


	$code=$row['sku'];
	print $row['entity_id']." $code \n";

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=56 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$name=$row2['value'];
	}else {
		exit("error no name associated\n");
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=524 ",$row['entity_id']);
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {
		$sku=$row2['value'];
	}else {
		exit("error no sku associated\n");
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  and attribute_id=526 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$parts_per_product=$row2['value'];
	}else {
		exit("error no part_relation associated\n");
	}


	if (!is_numeric($parts_per_product) or $parts_per_product<=0) {
		print_r($row);
		exit("wrong parts per product\n");
	}


	if ($parts_per_product=='') {
		print "$sku $parts_per_product\n";
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_text` WHERE  `entity_id` =%d  and attribute_id=57 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$description=$row2['value'];
	}else {
		exit("error no description associated\n");
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  and attribute_id=60 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$price=$row2['value'];
	}else {
		exit("error no description associated\n");
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  and attribute_id=65 ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {
		$weight=$row2['value'];
	}else {
		exit("error no description associated\n");
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`catalog_category_product` WHERE  `product_id` =%d   ",$row['entity_id']);
	$res2=mysql_query($sql,$con_drop);
	if ($row2=mysql_fetch_assoc($res2)) {

		if (array_key_exists($row2['category_id'],$family_bridge)) {
			$family=new Family($family_bridge[$row2['category_id']]);
			//print "xxxx0\n";
		}else {
			//print "xxxx\n";
			$family=new Family('code_store','PND_'.$store->data['Store Code'],$store->id);


		}

	}else {
		$family=new Family('code_store','PND_'.$store->data['Store Code'],$store->id);
		//print "xxxx2\n";
	}



	//print_r($family_bridge);
	$weight=$weight/1000;
	//$weight=500;
	//print_r($family);
	//exit;
	//print $family->data['Product Family Code']."\n";
	$editor['Date']=$row['created_at'];
	$product_data=array(
		'product stage'=>'Normal',
		'product sales type'=>'Public Sale',
		'product type'=>'Normal',
		'Product stage'=>'Normal',
		'product record type'=>'Normal',
		'Product Web Configuration'=>'Online Auto',
		'product store key'=>$store->id,
		'product currency'=>$store->data['Store Currency Code'],
		'product locale'=>$store->data['Store Locale'],
		'product price'=>$price,
		//  'product rrp'=>$price,
		'product units per case'=>1,
		'product family key'=>$family->id,

		'product valid from'=>$editor['Date'],
		//  'product valid to'=>$editor['Date'],
		'Product Code'=>$code,
		'Product Name'=>$name,
		'Product Description'=>$description,
		'Product Special Characteristic'=>$name,
		'Product Main Department Key'=>$family->data['Product Family Main Department Key'],
		'editor'=>$editor,
		'Product Net Weight'=>$weight,
		'Product Parts Weight'=>$weight,
		//  'Product Part Metadata'=>$data['values']['Product Part Metadata']
	);
	//print_r($product_data);
	$product=new Product('find',$product_data,'create');
	//print "$sku $parts_per_product\n";

	//print_r($product);
	if ($product->new_id) {
		$part=new Part('sku',$sku);

		if ($part->sku) {
			$part_list=array();
			$part_list[]=array(

				'Part SKU'=>$part->get('Part SKU'),

				'Parts Per Product'=>$parts_per_product,
				'Product Part Type'=>'Simple'

			);
			$product_part_header=array(
				'Product Part Valid From'=>$editor['Date'],
				//'Product Part Valid To'=>$date2,
				'Product Part Most Recent'=>'Yes',
				'Product Part Type'=>'Simple'

			);

			//print_r($product_part_header);

			$product->new_current_part_list($product_part_header,$part_list);
			$part->update_used_in();

		}else {
			print "error no sku found";
			print_r($product_data);

		}

	}

	if ($product->found_in_id) {
		$update_data=array(
			'Product Net Weight'=>$weight,
			'Product Parts Weight'=>$weight
		);
		//print_r($update_data);
		$product->update($update_data);
	}
	$product->update_web_configuration('Online Auto');


	$sql=sprintf("INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s",
				prepare_mysql($store_code.$order_data_id),
				prepare_mysql($row['updated_at']),
				prepare_mysql($row['updated_at'])
			);

		mysql_query($sql);

	//print_r($product);
	//print " $name\n $description";

}


?>
