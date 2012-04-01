<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once '../../app_files/db/dns.php';
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
error_reporting(E_ALL);

date_default_timezone_set('UTC');

include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}

$codigos=array();

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$software='Get_Products.php';
$version='V 1.0';



$Data_Audit_ETL_Software="$software $version";

//$file_name='/data/plaza/AWorder2009Poland.xls';

$set_part_as_available=false;
$csv_file='/data/plaza/AWorder2009Poland.csv';
//$csv_file='AWorder2009Poland.csv';
//exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$tcsv_file);
//exec("iconv   -f  ISO8859-1  -t UTF-8  --output  $csv_file $tcsv_file");

//exit;

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store=new Store('code','PL');
$store_key=$store->id;

$gold_camp=new Deal('code','PL.GR');
$vol_camp=new Deal('code','PL.Vol');
$bogof_camp=new Deal('code','PL.BOGOF');
$fam_promo=$fam_promo=new Family('code','Promo_PL',$store_key);
$fam_promo_key=$fam_promo->id;
$fam_products_no_family=new Family('code','PND_PL',$store_key);
$fam_products_no_family_key=$fam_products_no_family->id;


$read=true;	
$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {


	$code=$_cols[3];

//print_r($_cols);
	if ($code=='AWFO-A1' and !$inicio) {
		$inicio=true;
		$x=$__cols[count($__cols)-4];
		$z=$__cols[count($__cols)-3];
		$a=$__cols[count($__cols)-2];
		$b=$__cols[count($__cols)-1];
		$c=$_cols;
		$__cols=array();
		$__cols[]=$x;
		$__cols[]=$z;
		$__cols[]=$a;
		$__cols[]=$b;
		$__cols[]=$c;

	}elseif ($_cols[0]=='Bonus') {
$read=false;		
		
//		break;
	}
	//print "xx $code\n";
//if($read){

	$__cols[]=$_cols;
//}
	
}

$new_family=true;
$department_name='';
$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


foreach ($__cols as $cols) {




	if (count($cols)<7)
		continue;
	$is_product=true;
	$code=_trim($cols[3]);
	$price=$cols[7];
	$supplier_code=_trim($cols[21]);
	$part_code=_trim($cols[22]);
	$supplier_cost=$cols[25];
	$units=$cols[5];
	$rrp=$cols[16];
	$supplier_code=_trim($cols[21]);
	$w=$cols[28];


	

	//  $description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
	$description=_trim($cols[6]);
	$fam_special_char='';
	$special_char='';



//	if (!preg_match('/cotta/i',$code)) {
//		continue;
//	}
	//  exit($code);
	//  print_r($cols);

	if (!preg_match('/^DONE$/i',$cols[0]))
		$is_product=false;
	$code=_trim($code);
	if ($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
		$is_product=false;
	if (preg_match('/^(ob\-108|ob\-156|ish\-94|rds\-47)/i',$code))
		$is_product=false;
	if (preg_match('/^staf-set/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^hook-/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^shop-fit-/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
		$is_product=false;


	if (preg_match('/^credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
		$is_product=false;



	if ($is_product) {


		// if(preg_match('/po/',$code))
		//print "$code\n";
		$part_list=array();
		$rules=array();

		$current_fam_name=$fam_name;
		$current_fam_code=$fam_code;
		if ($new_family) {
			//    print "New family $column $promotion_position \n";
			if ($promotion!='' and  ($column-$promotion_position)<4 ) {
				$current_promotion=$promotion;
			}else
				$current_promotion='';
			$new_family=false;
		}





		$deals=array();
		if (preg_match('/oder mehr/i',_trim($current_promotion))) {
			if (preg_match('/^\d+\%/i',$current_promotion,$match))
				$allowance=$match[0];
			if (preg_match('/\d+ oder mehr/i',$current_promotion,$match))
				$terms=$match[0];

			// print "************".$current_promotion."\n";
			$deals[]=array(
				'Deal Metadata Name'=>'Gold Reward'
				,'Deal Metadata Allowance Description'=>$allowance

			);

			$deals[]=array(
				'Deal Metadata Name'=>'Family Volume Discount'

				,'Deal Metadata Allowance Description'=>$allowance

				,'Deal Metadata Terms Description'=>'beim kauf von '.$terms

			);



		}else
			$deals=array();


		if ($units=='' or $units<=0)
			$units=1;


		// $description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
		$description=_trim($cols[6]);

		//    if(preg_match('/wsl-535/i',$code)){
		//       print_r($cols);
		//       exit;

		//     }

		$rrp=$cols[16];
		$supplier_code=_trim($cols[21]);

		$w=$cols[28];

		if (  preg_match('/\-st\d$/i',$code)  or  preg_match('/\-pack$/i',$code)  or    preg_match('/\-pst$/i',$code)  or    preg_match('/\-kit2$/i',$code)  or  preg_match('/\-kit1$/i',$code)  or preg_match('/\-st$/i',$code)  or   preg_match('/\-minst$/i',$code)  or  preg_match('/\-st$/i',$code)  or   preg_match('/\-minst$/i',$code)  or preg_match('/Bag-02Mx|Bag-04mx|Bag-05mx|Bag-06mix|Bag-07MX|Bag-12MX|Bag-13MX|FishP-Mix|IncIn-ST|IncB-St|LLP-ST|L\&P-ST|EO-XST|AWRP-ST/i',$code) or      $code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St'
			or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
			or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
			or ($description=='' and ( $price=='' or $price==0 ))
		) {
			print "Skipping $code\n";
			continue;
		}


		$price=preg_replace('/[^0-9^\.]/','',$price);


		if (!is_numeric($price) or $price<=0) {
			print "Price Zero  $code \n";
			$price=0;
		}


		if ($code=='Tib-20')
			$supplier_cost=0.2;

		if (!is_numeric($supplier_cost)  or $supplier_cost<=0 ) {
			//   print_r($cols);
			print "$code   assumind supplier cost of 40%  \n";
			$supplier_cost=0.4*$price/$units;

		}

		if (array_key_exists($code,$codigos)) {
			print "Product: $code is duplicated\n";
			continue;
		}

		$codigos[$code]=1;




		$uk_product=new Product('code_store',$code,1);



		if ($units=='')
			$units=1;

		if (is_numeric($rrp))
			$rrp=sprintf("%.2f",$rrp*$units);
		else
			$rrp='';

		if ($fam_special_char=='') {
			$fam_special_char=$current_fam_name;
		}
		if ( $special_char=='') {
			$special_char=$description;
		}



		if (is_numeric($w)) {
			$w=$w*$units;
			if ($w<0.001 and $w>0)
				$_w=0.001;
			else
				$_w=sprintf("%.3f",$w);
		}else
			$_w='';

		if (preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($code))) {


			$family=new Family($fam_promo_key);

		}else {

			if ($department_name=='Gegenstände für Sammler')
				$department_code='Collect';
			if ($department_name=='Ökotaschen')
				$department_code='EcoBag';

			$dep_data=array(
				'Product Department Code'=>$department_code,
				'Product Department Name'=>$department_name,
				'Product Department Store Key'=>$store_key
			);
			$department=new Department('find',$dep_data,'create');

			if ($department->error) {
				print_r($dep_data);
				print_r($department);
				exit;
			}

			$fam_data=array(
				'Product Family Code'=>$current_fam_code,
				'Product Family Name'=>$current_fam_name,
				'Product Family Main Department Key'=>$department->id,
				'Product Family Store Key'=>$store_key,
				'Product Family Special Characteristic'=>$fam_special_char
			);
			$family=new Family('find',$fam_data,'create');

		}


		if (!$family->id) {
			print_r($fam_data);
			print_r($family);
			exit;

		}

		/*
    foreach($deals as $deal_data){
      //         print_r($deal_data);
      //exit;

      $deal_data['Store Key']=$store_key;

      if(preg_match('/Family Volume/i',$deal_data['Deal Metadata Name'])){

	$data=array(
		    'Deal Metadata Allowance Target Key'=>$family->id,
		    'Deal Metadata Trigger Key'=>$family->id,

		    'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description'],
		    'Deal Metadata Terms Description'=>$deal_data['Deal Metadata Terms Description']

		    );

	$vol_camp->create_deal('[Product Family Code] Volume Discount',$data);


      }


      if(preg_match('/Gold/i',$deal_data['Deal Metadata Name'])){

	$data=array(
		    'Deal Metadata Trigger Key'=>$family->id,
		    'Deal Metadata Allowance Target Key'=>$family->id,
		    'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description']
		    );

	$gold_camp->create_deal('[Product Family Code] Goldprämie',$data);

      }

      if(preg_match('/bogof/i',$deal_data['Deal Metadata Name'])){
	$data=array(
		    'Deal Metadata Trigger Key'=>$family->id,
		    'Deal Metadata Allowance Target Key'=>$family->id,
		    'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description']
		    );

	$bogof_camp->create_deal('[Product Family Code] BOGOF',$data);
      }
    }
*/




		$data=array(
			'product code'=>$code,
			'product store key'=>$store_key,
			'product locale'=>'pl_PL',
			'product currency'=>'PLN',

			'product sales type'=>'Public Sale',
			'product type'=>'Normal',
			'product record type'=>'Normal',
			'Product Web Configuration'=>'Online Auto',

			'product stage'=>'Normal',
			'product price'=>sprintf("%.2f",$price),
			'product rrp'=>$rrp,
			'product units per case'=>$units,
			'product name'=>$description,
			'product family key'=>$family->id,
			'product special characteristic'=>$special_char,
			//  'product family special characteristic'=>$fam_special_char,
			'product net weight'=>$_w,
			'product gross weight'=>$_w,
			'product valid from'=>date('Y-m-d H:i:s'),
			'product valid to'=>date('Y-m-d H:i:s'),
			//'deals'=>$deals
		);
		// print_r($cols);
	//	print_r($data);
		if ($uk_product->id)
			$parts=$uk_product->get_current_part_skus();
		else {
			print("product not found in uk: ".$code."\n");
			continue;
		}


		$product=new Product('find',$data,'create');
		if ($product->new) {
			$product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("now +1 seconds")));

		}

		if ($product->new_code) {
			if (count($parts)>0) {
				$part_sku_from_uk=array_pop($parts);
				$part_list[]=array(
					'Product ID'=>$product->get('Product ID'),
					'Part SKU'=>$part_sku_from_uk,
					'Product Part Id'=>1,
					'requiered'=>'Yes',
					'Parts Per Product'=>1,
					'Product Part Type'=>'Simple Pick'
				);

				$product->new_current_part_list(array(),$part_list);
				$product->update_parts();
				$part =new Part('sku',$part_sku_from_uk);
				$part->update_used_in();
			}
		}

		$product->change_current_key($product->id);
		$product->update_rrp('Product RRP',$rrp);
		$product->update_stage('Normal');
		if ($set_part_as_available) {
			set_part_as_available($product);
		}



		if ($product->data['Product Family Key']==$fam_products_no_family_key) {
			$product->update_family_key($family->id);
		}

		if ($product->data['Product Sales Type']!='Private Sale') {
			$product->update_sales_type('Public Sale');
		}

		$sql=sprintf("select `Product ID` from `Product Dimension`  where `Product Code`=%s and `Product Store Key`=%d and `Product ID`!=%d group by `Product ID`",
			prepare_mysql($product->code),
			$product->data['Product Store Key'],
			$product->pid
		);
		$res=mysql_query($sql);
		//print $sql;
		$pids=array();
		while ($row=mysql_fetch_array($res)) {
			$_product=new Product('pid',$row['Product ID']);
			$_product->set_as_historic();
		}
		$product->update_web_state();


	}else {

		$new_family=true;

		// print "Col $column\n";
		//print_r($cols);
		if (  preg_match('/donef/i',$cols[0])       ) {
			$fam_code=$cols[3];
			//      $fam_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
			$fam_name=_trim($cols[6]);

			$fam_position=$column;


		}

		if (preg_match('/oder mehr/i',_trim($cols[6]))) {


			$promotion=$cols[6];

			$promotion=preg_replace('/^\s*order\s*/i','',$promotion);
			$promotion=preg_replace('/discount\s*$/i','',$promotion);
			$promotion=preg_replace('/\s*off\s*$/i','',$promotion);

			$promotion=_trim($promotion);
			$promotion_position=$column;
			// print "*********** Promotion $promotion $promotion_position \n";
		}
		if ($cols[3]=='' and $cols[6]=='') {
			$blank_position=$column;
		}

		if (preg_match('/doned/i',$cols[0])) {
			//      $department_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
			// $department_code=_trim( mb_convert_encoding($cols[3], "UTF-8", "ISO-8859-1,UTF-8"));
			$department_name=_trim($cols[6]);
			$department_code=_trim($cols[3]);


			$department_position=$column;
		}


	}



	$column++;
}



function set_part_as_available($product) {

	$current_part_skus=$product->get_current_part_skus();

	foreach ($current_part_skus as $_part_sku) {
		$part=new Part($_part_sku);
		//$part->update_status('Not In Use');

		//$products_in_part=$part->get_product_ids();
		//print_r($products_in_part);
		//$number_products_in_part=count($products_in_part);
		//print $product->data['Product Code']." $number_products_in_part\n";


		$supplier_products=$part->get_supplier_products();

		foreach ($supplier_products as $supplier_product) {
			$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Status`='In Use' where `Supplier Product Key`=%d",
				$supplier_product['Supplier Product Key']
			);
			mysql_query($sql);
			//print "$sql\n";
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='Yes' where `Supplier Product Part Key`=%d",
				$supplier_product['Supplier Product Part Key']
			);
			mysql_query($sql);
			//  print "$sql\n";

		}

		$part->update_availability();


		$part->update_status('In Use');



	}

}


?>
