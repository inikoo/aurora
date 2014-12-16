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

$csv_file='gb.csv';


$handle_csv = fopen($csv_file, "r");
$column=0;

$count=0;



$store_key=1;
$using_commas_as_decimal_point=false;
$store=new Store($store_key);
//$department=new Department($store->data['Store Orphan Families Department']);
//$family=new Family($store->data['Store Orphan Products Family']);

$__cols=array();
$inicio=false;
$counter=0;
while (($_cols = fgetcsv($handle_csv))!== false) {



	if (count($_cols)<25)continue;
	foreach ($_cols as $key=>$value) {
		$_cols[$key]=trim($value);
	}


	//print_r($_cols);
	$code=$_cols[3];
	$units=$_cols[5];
	$description=$_cols[6];
	//if ($code!='ESAM-01')continue;
	//print_r($_cols);
	$cost=$_cols[25];
	if (preg_match('/-/',$code) and is_numeric($cost) and is_numeric($units) ) {
		$part=new Part('reference',$code);
		if ($part->sku) {
			$num_sp=count($part->get_supplier_products_new());
			print "$code $cost $num_sp\n";
			//print_r($part->get_supplier_products_new());


			foreach ($part->get_supplier_products_new() as $spp_data) {
				if ($spp_data['Supplier Product Units Per Part']!=$units) {

					$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No',`Supplier Product Part In Use`='No' ,`Supplier Product Part Valid To`=%s  where `Supplier Product Part Key`=%d",

						prepare_mysql(gmdate('Y-m-d H:i:s')),
						$spp_data['Supplier Product Part Key']
					);
					mysql_query($sql);

				}


			}


			$ssp_counter=0;
			foreach ($part->get_supplier_products_new() as $spp_data) {
				if ($ssp_counter>0) {

					$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No',`Supplier Product Part In Use`='No' ,`Supplier Product Part Valid To`=%s  where `Supplier Product Part Key`=%d",

						prepare_mysql(gmdate('Y-m-d H:i:s')),
						$spp_data['Supplier Product Part Key']
					);
					mysql_query($sql);

				}

				$ssp_counter++;
			}

			foreach ($part->get_supplier_products_new() as $spp_data) {
				//print_r($spp_data);

				$sp=new SupplierProduct('pid', $spp_data['Supplier Product ID']);
//print "* $cost*";
				$sp->update_cost($cost);

				break;
			}


			if(count($part->get_supplier_products_new())==0 ){
				$sp_data=array(
		'Supplier Key'=>1,
		'Supplier Product Code'=>$code,
		'Supplier Product Units Per Case'=>1,
		'SPH Case Cost'=>sprintf("%.2f",$cost),
		'Supplier Product Name'=>$description,
		'Supplier Product Description'=>$description,
		'Supplier Product Valid From'=>gmdate("Y-m-d H:i:s"),
		'Supplier Product Valid To'=>gmdate("Y-m-d H:i:s"),
	);
	// print_r($sp_data);
	$supplier_product=new SupplierProduct('find',$sp_data,'create');

$spp_header=array(
		'Supplier Product Part Type'=>'Simple',
		'Supplier Product Part Most Recent'=>'Yes',
		'Supplier Product Part Valid From'=>gmdate("Y-m-d H:i:s"),
		'Supplier Product Part Valid To'=>gmdate("Y-m-d H:i:s"),
		'Supplier Product Part In Use'=>'Yes'
	);

	$spp_list=array(
		array(
			'Part SKU'=>$part->data['Part SKU'],
			'Supplier Product Units Per Part'=>$units,
			'Supplier Product Part Type'=>'Simple'
		)
	);



	$supplier_product->new_current_part_list($spp_header,$spp_list);


			
			}

			$part->update_supplied_by();

		    //foreach ($part->get_product_ids() as $pid) {
			//	$product=new Product('pid',$pid);
			//	$product->update_parts();
			//}


		}
	}

	$counter++;
	//if ($counter>600)
	//	exit;

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
