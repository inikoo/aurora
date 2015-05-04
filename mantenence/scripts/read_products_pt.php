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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$set_part_as_available=false;

$csv_file='products_pt.csv';


$handle_csv = fopen($csv_file, "r");
$column=0;

$count=0;



$store_key=2;
$using_commas_as_decimal_point=true;
$store=new Store($store_key);



$department=new Department($store->data['Store Orphan Families Department']);
$family=new Family($store->data['Store Orphan Products Family']);

$__cols=array();
$inicio=false;
$counter=0;
while (($_cols = fgetcsv($handle_csv))!== false) {



	if (count($_cols)<3)continue;
	foreach ($_cols as $key=>$value) {
		$_cols[$key]=trim($value);
	}

	//print_r($_cols);

	if (preg_match('/doned/i',$_cols[0])) {
		$department_data=array(
			'Product Department Code'=>$_cols[1],
			'Product Department Name'=>$_cols[3],
			'Product Department Store Key'=>$store->id
		);
		$department=get_department($department_data);

	}elseif (preg_match('/donef/i',$_cols[0])) {

		if ($_cols[1]=='') {
			print "No code in family\n";
			print_r($_cols);
			
		}else {

			$family_data=array(
				'Product Family Code'=>$_cols[1],
				'Product Family Name'=>$_cols[3],
				'Product Family Description'=>'',
				'Product Family Special Characteristic'=>'',
				'Product Family Main Department Key'=>$department->id,
				'Product Family Store Key'=>$store->id,
			);
			$family=get_family($family_data);
		}
	}elseif (preg_match('/done/i',$_cols[0])) {

		$code=$_cols[1];
		print "$code \n";
		$product_data=array(
			'Product Stage'=>'Normal',
			'Product Sales type'=>'Public Sale',
			'Product Type'=>'Normal',
			'Product Record Type'=>'Normal',
			'Product Web Configuration'=>'Online Auto',
			'Product Store Key'=>$store->id,
			'Product Family Key'=>$family->id,
			'Product Main Department Key'=>$department->id,

			'Product Currency'=>$store->data['Store Currency Code'],
			'Product Locale'=>$store->data['Store Locale'],
			'Product Price'=>floatval(($using_commas_as_decimal_point?preg_replace('/,/','.',$_cols[4]):$_cols[4])),
			'Product RRP'=>floatval(($using_commas_as_decimal_point?preg_replace('/,/','.',$_cols[5]):$_cols[5])),
			'Product Units Per Case'=>floatval($_cols[4]),
			'Product Valid From'=>gmdate('Y-m-d H:i:s'),
			'Product Code'=>$code,
			'Product Name'=>$_cols[3],
			'Product Description'=>'',
			'Product Special Characteristic'=>$_cols[3],
			'Product Part Metadata'=>''
		);


		$product=get_product($product_data);
		if ($product->new or $product->updated) {

			$part=new Part('reference',$code);
			if ($part->sku) {
				$part_list=array(array(
						'Part SKU'=>$part->get('Part SKU'),
						'Parts Per Product'=>1,
						'Product Part Type'=>'Simple'
					));
				$product->new_current_part_list(array('Product Part Metadata'=>'','Product Part List Note'=>''),$part_list)  ;
			}
		}

	}

	$counter++;
	//if ($counter>100)
	//  exit;

}




function get_department($data) {

	$department=new Department('find',$data,'create');
	return $department;

}

function get_family($data) {
	$family=new Family('find',$data,'create');
	return $family;
}

function get_product($data) {
	$product=new Product('find',$data,'create');
	return $product;
}

function create_deal() {

}






?>
