<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';

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

$set_part_as_available=false;
$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='/data/plaza/AWorder2009Germany.xls';
$csv_file='de.csv';
//exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;


$store=new Store("code","DE");
$store_key=$store->id;

$campaign_data=array('Deal Campaign Code'=>'GR','Deal Campaign Name'=>'Gold Reward','Deal Campaign Store Key'=>$store_key);
$gold_camp=new DealCampaign('find create',$campaign_data);
$campaign_data=array('Deal Campaign Code'=>'Vol','Deal Campaign Name'=>'Volume Discount','Deal Campaign Store Key'=>$store_key);
$vol_camp=new DealCampaign('find create',$campaign_data);
$campaign_data=array('Deal Campaign Code'=>'Bogof','Deal Campaign Name'=>'Bogof','Deal Campaign Store Key'=>$store_key);
$bogof_camp=new DealCampaign('find create',$campaign_data);

$gold_deal_data=array(
	'Deal Code'=>'GR.'.$store->data['Store Code'],
	'Deal Store Key'=>$store_key,
	'Deal Name'=>'Gold Reward',
	'Deal Description'=>'Order within 30 days to receive a discount on selected products, no small order charge and Free Gold Reward Gift or bottle of fine wine (on orders over £100+vat).',
	'Deal Trigger'=>'Order',
	'Deal Trigger Key'=>'0',
	'Deal Trigger XHTML Label'=>'',
	'Deal Terms Type'=>'Order Interval'

);

$deal_gold=$gold_camp->add_deal($gold_deal_data);

$_deal_component_data=array(
	'Deal Component Name'=>'Charge Waiver',
	'Deal Component Terms Description'=>'last order within 30 days',
	'Deal Component Allowance Description'=>'no hanging charges',
	'Deal Component Allowance Type'=>'Get Free',
	'Deal Component Allowance Target'=>'Charge',
	'Deal Component Allowance Target Key'=>''
);

$deal_component=$deal_gold->add_component($_deal_component_data);
$deal_component->update_status('Active');


$fam_promo=$fam_promo=new Family('code','Promo_UK',$store_key);
$fam_promo_key=$fam_promo->id;
$fam_products_no_family=new Family('code','PND_UK',$store_key);
$fam_products_no_family_key=$fam_products_no_family->id;
$current_promotion='';



$fam_promo=$fam_promo=new Family('code','Promo_DE',$store_key);
$fam_promo_key=$fam_promo->id;
$fam_products_no_family=new Family('code','PND_DE',$store_key);
$fam_products_no_family_key=$fam_products_no_family->id;

$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {

	if(count($_cols)<6)
		continue;
	$code=$_cols[3];


	if ($code=='FO-A1' and !$inicio) {
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

	}elseif (preg_match('/First Order Bonus/',$_cols[6])) {
		break;
	}

	$__cols[]=$_cols;
}

$new_family=true;
$department_name='';
$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


foreach ($__cols as $cols) {
	$is_product=true;
	$code=_trim($cols[3]);
	$price=$cols[9];
	$supplier_code=_trim($cols[23]);
	$part_code=_trim($cols[24]);
	$supplier_cost=$cols[27];
	$units=$cols[5];



	$rrp=$cols[18];
	$supplier_code=_trim($cols[23]);
	if (isset($cols[31])) {
		$w=$cols[31];
	}else {
		$w='';
	}
	$description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
	$fam_special_char=_trim( mb_convert_encoding($cols[7], "UTF-8", "ISO-8859-1,UTF-8"));
	$special_char=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));

	if (!preg_match('/^DONE$/i',$cols[0]))
		$is_product=false;


	//  if(!preg_match('/^avalon-03$/i',$code)){
	//print "xxx ";
	//continue;
	// }


	$code=_trim($code);


	// if (!preg_match('/eid-04c/i',$code)) {
	//  $is_product=false;
	// }

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
		$_deal_type='None';
		
	
		
	if (preg_match('/off\s+\d+\s+or\s+more/i',_trim($current_promotion))) {
			if (preg_match('/^\d+\% off/i',$current_promotion,$match))
				$allowance=$match[0];
			if (preg_match('/off.*more/i',$current_promotion,$match))
				$terms=preg_replace('/^off\s*/i','',$match[0]);

			$_deal_type='GR/Vol';

			$allowance=preg_replace('/ off/i',' off',$allowance);
		}
		elseif (preg_match('/^buy \d+ get \d+ free$/i',_trim($current_promotion))) {
			// print $current_promotion." *********\n";
			preg_match('/buy \d+/i',$current_promotion,$match);
			$buy=_trim(preg_replace('/[^\d]/','',$match[0]));

			preg_match('/get \d+/i',$current_promotion,$match);
			$get=_trim(preg_replace('/[^\d]/','',$match[0]));


			$_deal_type='Bogof';

		}
		else {
			$_deal_type='None';
		}



	if ($_deal_type=='GR/Vol') {

			$deals[]=array(
				'Deal Code'=>'GR.UK',
				'Deal Store Key'=>$store_key,
				'Deal Name'=>'Gold Reward',
				'Deal Description'=>'Order within 30 days to receive a discount on selected products, no small order charge and Free Gold Reward Gift or bottle of fine wine (on orders over £100+vat).',
				'Deal Trigger'=>'Order',
				'Deal Trigger Key'=>'0',
				'Deal Trigger XHTML Label'=>'',
				'Deal Terms Type'=>'Order Interval',
				'component'=>array(
					'Deal Component Name'=>'',
					'Deal Component Terms Description'=>'last order within 30 days',
					'Deal Component Allowance Description'=>$allowance,
					'Deal Component Allowance Type'=>'Percentage Off',
					'Deal Component Allowance Target'=>'Family',
					'Deal Component Allowance Target Key'=>''
				)
			);
			$deals[]=array(
				'Deal Code'=>'Vol',
				'Deal Store Key'=>$store_key,
				'Deal Name'=>'',
				'Deal Description'=>'',
				'Deal Trigger'=>'Family',
				'Deal Terms Type'=>'Family Quantity Ordered',
				'component'=>array(
					'Deal Component Name'=>'',
					'Deal Component Trigger Key'=>'',
					'Deal Component Terms Description'=>'order '.$terms,
					'Deal Component Allowance Description'=>$allowance,
					'Deal Component Allowance Type'=>'Percentage Off',
					'Deal Component Allowance Target'=>'Family',
					'Deal Component Allowance Target Key'=>''
				)




			);
		}
		elseif ($_deal_type=='Bogof') {
			$deals[]=array(
				'Deal Code'=>'Bogof',
				'Deal Description'=>'buy '.$buy.' get '.$get.' free',
				'Deal Store Key'=>$store_key,
				'Deal Trigger'=>'Family',
				'Deal Terms Type'=>'Product Quantity Ordered',
				'component'=>array(

					'Deal Component Name'=>'',
					'Deal Component Terms Description'=>'foreach '.$buy,
					'Deal Component Allowance Description'=>$get.' free',
					'Deal Component Allowance Type'=>'Get Free',
					'Deal Component Allowance Target'=>'Product',
					'Deal Component Allowance Target Key'=>'',

				)
			);
		}
		else {
			$deals=array();
		}





		if ($units=='' or $units<=0)
			$units=1;


		$description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));

		//    if(preg_match('/wsl-535/i',$code)){
		//       print_r($cols);
		//       exit;

		//     }

		$rrp=$cols[18];
		$supplier_code=_trim($cols[21]);

		$w=$cols[28];

		$code=preg_replace('/L\&P\-/','LLP-',$code);

		// if (   !preg_match('/alpha/i',$code) ) {continue;}



		if (  preg_match('/\-st\d$/i',$code)  or  preg_match('/\-pack$/i',$code)  or    preg_match('/\-pst$/i',$code)  or    preg_match('/\-kit2$/i',$code)  or  preg_match('/\-kit1$/i',$code)  or preg_match('/\-st$/i',$code)  or   preg_match('/\-minst$/i',$code)  or  preg_match('/\-st$/i',$code)  or   preg_match('/\-minst$/i',$code)  or preg_match('/Bag-02Mx|Bag-04mx|Bag-05mx|Bag-06mix|Bag-07MX|Bag-12MX|Bag-13MX|sFishP-Mix|IncIn-ST|IncB-St|LLP-ST|L\&P-ST|EO-XST|AWRP-ST/i',$code) or       $code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St'
			or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
			or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
			or ($description=='' and ( $price=='' or $price==0 ))

		) {
			print "Skipping $code\n";
			continue;
		}


		if (!is_numeric($price) or $price<=0) {
			print "Price Zero  $code \n";
			$price=0;
		}


		if ($code=='Tib-20')
			$supplier_cost=0.2;

		if (!is_numeric($supplier_cost)  or $supplier_cost<=0 ) {
			//   print_r($cols);
			//    print "$code   assumind supplier cost of 40%  \n";
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


		if ($fam_special_char=='' or $special_char=='') {

			$_f=preg_replace('/s$/i','',$current_fam_name);
			$special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
			$special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
			$special_char=_trim($special_char);
			if ($special_char==$description) {
				$description=$current_fam_name.' '.$special_char;
				$fam_special_char=$current_fam_name;
			}else
				$fam_special_char=preg_replace('/'.str_replace('/','\/',$special_char).'$/i','',$description);
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
			if ($department_name=='Deko-Artikel')
				$department_code='Deko';

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

			$current_fam_code=preg_replace('/^L\&P$/i','LLP',$current_fam_code);



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
			print_r($family);
			exit;

		}




		$data=array(
			'product code'=>$code,
			'product store key'=>$store_key,
			'product stage'=>'Normal',
			'product locale'=>'de_DE',
			'product currency'=>'EUR',
			'product sales type'=>'Public Sale',
			'product type'=>'Normal',
			'product record type'=>'Normal',
			'Product Web Configuration'=>'Online Auto',


			'product price'=>sprintf("%.2f",$price),
			'product rrp'=>$rrp,
			'product units per case'=>$units,
			'product name'=>$description,
			'product family key'=>$family->id,
			'product special characteristic'=>$special_char,
			//  'product family special characteristic'=>$fam_special_char,

			'product valid from'=>date('Y-m-d H:i:s'),
			'product valid to'=>date('Y-m-d H:i:s'),
			//'deals'=>$deals
		);
		//     print_r($cols);

		if ($uk_product->id) {

			$parts=$uk_product->get_current_part_skus();
		}else {
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



				if ($part->data['Part Tariff Code']!=='')
					$part->update_fields_used_in_products('Part Tariff Code',$part->data['Part Tariff Code']);
				if ($part->data['Part Duty Rate']!=='')
					$part->update_fields_used_in_products('Part Duty Rate',$part->data['Part Duty Rate']);

			}
		}


		//print "rrp: $rrp <-\n";
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
		//print "$sql\n";
		$pids=array();
		while ($row=mysql_fetch_array($res)) {
			$_product=new Product('pid',$row['Product ID']);
			$_product->set_as_historic();
		}
		$product->update_web_state();


		if (count($deals)>0) {

			foreach ($deals as $_deal_key=>$deal_data) {



				if ($deal_data['Deal Code']=='Vol') {
					$deals[$_deal_key]['Deal Code']='Vol.'.$family->data['Product Family Code'];
					$deals[$_deal_key]['Deal Name']=$family->data['Product Family Code'].' Volume Discount';
					$deals[$_deal_key]['Deal Description']=$deals[$_deal_key]['component']['Deal Component Terms Description'].' '. $family->data['Product Family Code'].' family products and get '  .$deals[$_deal_key]['component']['Deal Component Allowance Description'];
					$deals[$_deal_key]['Deal Trigger Key']=$family->id;
					$deals[$_deal_key]['Deal Trigger XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);
					$deals[$_deal_key]['component']['Deal Component Trigger Key']=$family->id;

					$deals[$_deal_key]['component']['Deal Component Allowance Target Key']=$family->id;
					$deals[$_deal_key]['component']['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);

					$deals[$_deal_key]['component']['Deal Component Name']=$family->data['Product Family Code'].' Volume Discount';
					$promotion='';
					$current_promotion='';
					$deal=$vol_camp->add_deal($deals[$_deal_key]);

					$deal_component=$deal->add_component($deals[$_deal_key]['component']);
					$deal_component->update_status('Active');

				}
				elseif ($deal_data['Deal Code']=='GR.'.$store->data['Store Code']) {
					$deals[$_deal_key]['component']['Deal Component Allowance Target Key']=$family->id;
					$deals[$_deal_key]['component']['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);
					$deals[$_deal_key]['component']['Deal Component Name']=$family->data['Product Family Code'].' Gold Reward';
					$promotion='';$current_promotion='';


					$deal_component=$deal_gold->add_component($deals[$_deal_key]['component']);
					$deal_component->update_status('Active');


				}elseif ($deal_data['Deal Code']=='Bogof') {
					$deals[$_deal_key]['Deal Code']='Bogof.'.$family->data['Product Family Code'];
					$deals[$_deal_key]['Deal Name']=$family->data['Product Family Code'].' Bogof';
					$deals[$_deal_key]['Deal Trigger Key']=$family->id;
					$deals[$_deal_key]['Deal Trigger XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);
					$deals[$_deal_key]['component']['Deal Component Trigger Key']=$family->id;

					$deals[$_deal_key]['component']['Deal Component Allowance Target Key']=$product->pid;
					$deals[$_deal_key]['component']['Deal Component Allowance Target XHTML Label']=sprintf('<a href="product.php?pid=%d">%s</a>',$product->pid,$product->code);

					$deals[$_deal_key]['component']['Deal Component Name']=$product->code.' Bogof';


					$deal=$bogof_camp->add_deal($deals[$_deal_key]);
					$deal_component=$deal->add_component($deals[$_deal_key]['component']);
					$deal_component->update_status('Active');


				}





			}


		}

		$deals=array();



	}else {

		$new_family=true;

		// print "Col $column\n";
		//print_r($cols);
		if (  preg_match('/donef/i',$cols[0])       ) {
			$fam_code=$cols[3];
			$fam_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
			$fam_position=$column;


		}



		if (isset($cols[22]) and preg_match('/\d+\:\d+\%$/i',_trim($cols[22]))) {
				$_deal_comps=preg_replace('/\%$/','',$cols[22]);
				$_deal_comps=preg_split('/\:/',$_deal_comps);
				$promotion=sprintf("%d%% off %d or more",$_deal_comps[1],$_deal_comps[0]);
				$promotion=preg_replace('/^\s*order\s*/i','',$promotion);
				$promotion=preg_replace('/discount\s*$/i','',$promotion);
				$promotion=preg_replace('/\s*off\s*$/i','',$promotion);

				$promotion=_trim($promotion);
				$promotion_position=$column;
				print "$promotion\n";
			}elseif (isset($cols[22]) and preg_match('/^B\d+\:\d+$/i',_trim($cols[22]))) {
				$_deal_comps=preg_replace('/^B$/','',$cols[22]);
				$_deal_comps=preg_split('/\:/',$_deal_comps);
				$promotion=sprintf("buy %d get %d free",$_deal_comps[0],$_deal_comps[1]);
				$promotion=_trim($promotion);
				$promotion_position=$column;
				
			}
			
			
		
		
		
		
		if ($cols[3]=='' and $cols[6]=='') {
			$blank_position=$column;
		}


		if (preg_match('/doned/i',$cols[0])) {
			$department_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
			$department_code=_trim( mb_convert_encoding($cols[3], "UTF-8", "ISO-8859-1,UTF-8"));
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
			$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Status`='In Use' where `Supplier Product ID`=%d",
				$supplier_product['Supplier Product ID']
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
