<?php
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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


$codigos=array();
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2002-spain.xls';
$csv_file='es_tmp.csv';
//exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;
$set_part_as_available=true;

$store_key=1;
$create_cat=false;
//----------------------------------OK


$last_department_name='';
$date=date("Y-m-d H:i:s");
$editor=array(
	'Date'=>$date,
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>0,
	'User Key'=>0,
);


$store=new Store(1);


$gold_camp=new Deal('code','Oro');
$vol_camp=new Deal('code','Mayo');
$bogof_camp=new Deal('code','Bogof');
$fam_promo=$fam_promo=new Family('code','Promo_ES',$store_key);
$fam_promo_key=$fam_promo->id;
$fam_products_no_family=new Family('code','PND_ES',$store_key);
$fam_products_no_family_key=$fam_products_no_family->id;





$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {
	if (count($_cols)<=5)
		continue;

	//print_r($_cols);


	$code=trim( mb_convert_encoding($_cols[5], "UTF-8", "ISO-8859-1,UTF-8"));




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


	}elseif (isset($_cols[8]) and preg_match('/Regalo de bienvenida/i',$_cols[8])) {

		break;
	}

	$__cols[]=$_cols;
}


//print_r($__cols);
//exit;

$fam_name='Productos sin Familia';
$fam_code='PND_ES';

$new_family=true;


$department_name='ND';
$department_code='Productos sin Departamento';


$department_name='';
$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


foreach ($__cols as $cols) {




	// print_r($cols);
	//exit;

	$is_product=true;



	$code=_trim($cols[3+2]);


	$price=$cols[7+2];
	$supplier_code=trim( mb_convert_encoding($cols[23], "UTF-8", "ISO-8859-1,UTF-8"));
	$part_code=_trim($cols[22]);
	$supplier_cost=$cols[26];

	if (preg_match('/moonbr-\d+/i',$code,$match)) {
		$code=$match[0];
		$cols[5]=$match[0];

	}
	if (preg_match('/aquabr-\d+/i',$code,$match)) {
		$code=$match[0];
		$cols[5]=$match[0];
	}
	if (preg_match('/ametbr-\d+/i',$code,$match)) {
		$code=$match[0];
		$cols[5]=$match[0];
	}

	if (preg_match('/miscbr-\d+/i',$code,$match)) {
		$code=$match[0];
		$cols[5]=$match[0];

	}

	if($code=='?')
		continue;


	// print_r($cols);
	// exit;
	$code=preg_replace('/\s.*$/','',$code);

	$code=_trim($code);
	
	
	
	
	if ( !preg_match('/\-/',$code) and !is_numeric($price))
		$is_product=false;
	
	if ($code==''  or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
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

	if(in_array($code,array('FL05/B','T10C','T54C')))
		$is_product=true;
if (preg_match('/^b\d{4}$/i',$code) )
		$is_product=true;

if (preg_match('/^t\d{4}c$/i',$code) )
		$is_product=true;



	if (preg_match('/^y\d{2}$/i',$code) )
		$is_product=true;
	if (preg_match('/^credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
		$is_product=false;



//print "$code -> $is_product .\n";

//continue;

	if ($is_product) {

		

		if ($cols[8]=='' and $price=='')
			continue;

 if(!preg_match('/thss-10/i',$code)){

//	  continue;
	}




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



		// print $current_promotion;

		$deals=array();
		if (preg_match('/\%/i',_trim($current_promotion))) {
			if (preg_match('/\d+\%/i',$current_promotion,$match))
				$allowance=$match[0];
			preg_replace('/\d+\%/','',$current_promotion);

			if (preg_match('/\d+/i',$current_promotion,$match))
				$terms=$match[0];



			$deals[]=array(
				'Deal Metadata Name'=>'Club Oro'
				,'Deal Metadata Trigger'=>'Order'
				,'Deal Description'=>$allowance.' if last order within 1 calendar month'
				,'Deal Metadata Terms Type'=>'Order Interval'
				,'Deal Metadata Terms Description'=>'last order within 1 calendar month'
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Product'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);
			$deals[]=array(
				'Deal Metadata Name'=>'Mayoreo en Familia'
				,'Deal Metadata Trigger'=>'Family'

				,'Deal Metadata Terms Type'=>'Family Quantity Ordered'
				,'Deal Metadata Terms Description'=>'order '.$terms
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Product'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);



		}elseif (preg_match('/^Ofertas? \d+\s?x\s?\d+$/i',_trim($current_promotion))) {
			// print $current_promotion." *********\n";
			preg_match('/Ofertas? \d+/i',$current_promotion,$match);
			$buy=_trim(preg_replace('/[^\d]/','',$match[0]));

			preg_match('/x\s?\d+/i',$current_promotion,$match);
			$get=_trim(preg_replace('/[^\d]/','',$match[0]));

			$deals[]=array(
				'Deal Metadata Name'=>'Oferta n x m'
				,'Deal Metadata Trigger'=>'Product'
				,'Deal Description'=>'buy '.$buy.' get '.$get.' free'
				,'Deal Metadata Terms Type'=>'Product Quantity Ordered'
				,'Deal Metadata Terms Description'=>'foreach '.$buy
				,'Deal Metadata Allowance Description'=>$get.' free'
				,'Deal Metadata Allowance Type'=>'Get Free'
				,'Deal Metadata Allowance Target'=>'Product'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);


		}else
			$deals=array();


$deals=array();


		$units=$cols[7];
		if ($units=='' or $units<=0)
			$units=1;

		$cols[8]=preg_replace('/†/','',$cols[8]);


		$cols[8]=preg_replace('/Lavanda .{2}(Semillas de Lavanda)/','Lavanda (Semillas de Lavanda)',$cols[8]);
		$cols[8]=preg_replace('/^Rosa .{1,2}\(P/','^Rosa (P',$cols[8]);
		$cols[8]=preg_replace('/P.*talos de jazm.*n .{2}\(con brillo/i','PÈtalos de jazmÌn (con brillo',$cols[8]);
		$cols[8]=preg_replace('/Glitter Musk.*\(con brillo\)/','Glitter Musk (con brillo)',$cols[8]);
		$cols[8]=preg_replace('/Vaso Ba.*Modelo Rajasthan/','Vaso BaÒo Modelo Rajasthan',$cols[8]);

		$cols[8]=preg_replace('/Jabonero Modelo.{1,3}Rajasthan/i','Jabonero Modelo Rajasthan',$cols[8]);
		$cols[8]=preg_replace('/Vaso Ba.*o.{1,4}Modelo Marakesh/i','Vaso BaÒo Modelo Marakesh',$cols[8]);

		$cols[8]=preg_replace('/^.{1,2}Rose Garden/','Rose Garden',$cols[8]);
		$cols[8]=preg_replace('/Atrapasue.{1,3}o 3D .{1,2}-/','AtrapasueÒo 3D -',$cols[8]);
		$cols[8]=preg_replace('/Small Rough Turquoise Bracelet.{1,4}30g/','Small Rough Turquoise Bracelet 30g',$cols[8]);


		$description=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));

		$description=preg_replace('/Vaso Ba.*Modelo Rajasthan/','Vaso Baño Modelo Rajasthan',$description);

		$description=preg_replace('/Vaso Ba.*o.{1,4}Modelo Marakesh/i','Vaso Baño Modelo Marakesh',$description);
		$description=preg_replace('/Atrapasue.{1,3}o 3D .{1,2}-/','Atrapasueño 3D -',$description);


		if ($code=='MFH-06')
			$description='Pétalos de jazmín (con brillo) rosa';
		if ($code=='BLN-03')
			$description='Rose Garden (Red)';

		//    if(preg_match('/wsl-535/i',$code)){
		//       print_r($cols);
		//       exit;

		//     }

		$rrp=$cols[18];
		$supplier_code=_trim($cols[23]);

		$w=$cols[29];





		if ($code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St'
			or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
			or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
		) {
			print "Skipping $code\n";

		}else {


			if (!is_numeric($price) or $price<=0) {


				continue;
				print "Price Zero  $code \n";
				$price=0;
			}


			if ($code=='Tib-20')
				$supplier_cost=0.2;

			if ($code=='L&P-ST') {
				$supplier_cost=36.30;
				$price=86.40;
			}

			if (!is_numeric($supplier_cost)  or $supplier_cost<=0 ) {
				//   print_r($cols);
				print "$code   guessing supplier cost of 40%  \n";
				$supplier_cost=0.4*$price/$units;

			}




			if ($units=='')
				$units=1;

			if (is_numeric($rrp))
				$rrp=sprintf("%.2f",$rrp*$units);
			else
				$rrp='';


			//   $_f=preg_replace('/s$/i','',$current_fam_name);
			//       //print "$_f\n";
			//       $special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
			//       $special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
			$fam_special_char=$current_fam_name;
			$special_char=$description;

			if (is_numeric($w)) {
				$w=$w*$units;
				if ($w<0.001 and $w>0)
					$_w=0.001;
				else
					$_w=sprintf("%.3f",$w);
			}else
				$_w='';

			$department_code='';

			// print "$department_name\n ";
			if ($department_name=='Ancient Wisdom Home Fragrance' )
				$department_code='Home';
			if ($department_name=='Bathroom Heaven' )
				$department_code='Bath';
			if ($department_name=='Departamento de Velas' )
				$department_code='Velas';
			if ($department_name=='Exotic Incense Dept Order' )
				$department_code='Inc';
			if (preg_match('/Departamento Mundo Asi/i',$department_name) )
				$department_code='Asia';
			if (preg_match('/Crystal Department/i',$department_name) )
				$department_code='Crys';
			if (preg_match('/Retail Display Stands/i',$department_name) )
				$department_code='RDS';
			if (preg_match('/Departamento de Oportunidades/i',$department_name) )
				$department_code='Dop';
			if (preg_match('/Departamento de Perfume/i',$department_name) )
				$department_code='Perf';
			if (preg_match('/Stoneware/i',$department_name) )
				$department_code='Stone';
			if (preg_match('/Relaxing Music Collection/i',$department_name) )
				$department_code='Relax';
			if (preg_match('/Jewellery Quarter/i',$department_name) )
				$department_code='Joyas';
			if (preg_match('/Paradise Accesories/i',$department_name) )
				$department_code='PA';
			if (preg_match('/Departamento de Bolsas/i',$department_name) )
				$department_code='BET';
			if (preg_match('/Ancient Wisdom Aromatherapy Dept/i',$department_name) )
				$department_code='Aterp';
			if (preg_match('/Woodware Dept|Departamento de Madera/i',$department_name) )
				$department_code='Wood';
			if (preg_match('/Fragrancias de casa - AW -Regalos/i',$department_name) )
				$department_code='Casa';
			if (preg_match('/Departamento de Aromaterapia/i',$department_name) )
				$department_code='Aroma';
			if (preg_match('/Paraiso del Ba/i',$department_name) )
				$department_code='Bath';
			if (preg_match('/Rincón Bisuteía/i',$department_name) )
				$department_code='Bisu';

			if (preg_match('/Cristales Nuevos/i',$department_name) )
				$department_code='Crist';
if (preg_match('/Departamento De Fiesta/i',$department_name) )
				$department_code='Fiesta';
				
				
				if (preg_match('/Departamento De Navidad/i',$department_name) )
				$department_code='Navi';
				
				
				
				
				
			if ($department_code=='') {

				exit("Error unknown department (get_product_es.php) name: $department_name\n");

			}


			$dep_data=array(
				'Product Department Code'=>$department_code,
				'Product Department Name'=>$department_name,
				'Product Department Store Key'=>$store_key
			);
			$department=new Department('find',$dep_data,'create');



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
			exit("Error en familia");

		}

/*
		foreach ($deals as $deal_data) {
			//   print_r($deal_data);


			$deal_data['Store Key']=$store_key;

			if (preg_match('/Mayoreo en Familia/i',$deal_data['Deal Metadata Name'])) {
				//$deal_data['Deal Deal Key']=$volume_cam_id;
				//$deal_data['Deal Metadata Name']=preg_replace('/Family/',$family->data['Product Family Code'],$deal_data['Deal Metadata Name']);
				//$deal_data['Deal Description']=preg_replace('/same family/',$family->data['Product Family Name'].' outers',$deal_data['Deal Description']);

				$data=array(
					'Deal Metadata Allowance Target Key'=>$family->id,
					'Deal Metadata Trigger Key'=>$family->id,

					'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description'],
					'Deal Metadata Terms Description'=>$deal_data['Deal Metadata Terms Description']

				);
				//print_r($data);
				$vol_camp->create_deal('[Product Family Code] Volume Discount',$data);


			}


			if (preg_match('/Oro/i',$deal_data['Deal Metadata Name'])) {
				//$deal_data['Deal Deal Key']=$gold_reward_cam_id;
				//$deal_data['Deal Metadata Name']=$family->data['Product Family Code'].' '.$deal_data['Deal Metadata Name'];
				$data=array(
					'Deal Metadata Trigger Key'=>$family->id,
					'Deal Metadata Allowance Target Key'=>$family->id,
					'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description']
				);

				// print_r($gold_camp);exit;
				$gold_camp->create_deal('[Product Family Code] Club Oro',$data);

			}

			if (preg_match('/bogof/i',$deal_data['Deal Metadata Name'])) {
				$data=array(
					'Deal Metadata Trigger Key'=>$family->id,
					'Deal Metadata Allowance Target Key'=>$family->id,
					'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description']
				);

				$bogof_camp->create_deal('[Product Family Code] BOGOF',$data);


			}



		}
*/

		if ($family->id) {
			$_special_char=$special_char;
			$fam_sp=$family->data['Product Family Special Characteristic'];
			$fam_sp=preg_replace('/[^a-z^0-9^\.^\-^"^\s]/i','',$fam_sp);


			//print "->$fam_sp ,  $special_char  ";
			$special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
			$fam_sp=preg_replace('/s$/i','',$fam_sp);
			$special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
			if ($special_char=='')
				$special_char=$_special_char;
			//print " ==> $special_char  \n";
		}




		$data=array(
			'product store key'=>1,
			'product currency'=>'EUR',
			'product locale'=>'es_ES',
			'product stage'=>'Normal',
			'product sales type'=>'Public Sale',
			'product type'=>'Normal',
			'product record type'=>'Normal',
			'Product Web Configuration'=>'Online Auto',

			'product code'=>$code,
			'product price'=>sprintf("%.2f",$price),
			'product rrp'=>$rrp,
			'product units per case'=>$units,
			'product name'=>$description,

			'product family key'=>$family->id,
			'product special characteristic'=>$special_char,
			'product family special characteristic'=>$fam_special_char,
			'product net weight'=>$_w,
			'product gross weight'=>$_w,
			'product valid from'=>date('Y-m-d H:i:s'),
			'product valid to'=>date('Y-m-d H:i:s'),
			'deals'=>$deals

		);
		// print_r($cols);
		//print_r($data);
		//exit;

		if (array_key_exists($code,$codigos)) {
			print "Product: $code is duplicated\n";
			continue;
		}


		$codigos[$code]=1;
		$product=new Product('find',$data,'create');


        $__parts=$product->get_part_list();


		if ($product->new_id or count($__parts)==0) {



			// $product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("now +1 seconds")));



			$scode=_trim($cols[22]);
			$supplier_code=$cols[23];
			update_supplier_part($code,$scode,$supplier_code,$units,$w,$product,$description,$supplier_cost);

			
		}
		
		
		$product->set_duplicates_as_historic();
		

		$product->change_current_key($product->id);
		//print_r($cols);
		//print $product->data['Product Code'].": ".$product->data['Product RRP']." -> $rrp\n";

		$product->update_rrp('Product RRP',$rrp);


	



		$product->update_stage('Normal');
		if ($set_part_as_available) {
			set_part_as_available($product);
		}



		//if ($product->data['Product Family Key']==$fam_products_no_family_key) {
			$product->update_family_key($family->id);
		//}

		if ($product->data['Product Sales Type']!='Private Sale') {
			$product->update_sales_type('Public Sale');
		}else{
		
			$product->update_web_configuration('Online Force For Sale');
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



	}
	else {




		$new_family=true;

		//   print "Col $column\n";
		//  print_r($cols);

		if ($department_name=='Paradise Accesories') {
			if (preg_match('/Bolsos con Parejo/',$cols[8])) {
				$fam_code='PBP';
				$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			}
			if (preg_match('/Bolsos/',$cols[8])) {
				$fam_code='PB';
				$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			}
			if (preg_match('/Pulseras hechas a mano Paradise/i',$cols[8])) {
				$fam_code='Ppul';
				$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			}
			if (preg_match('/Originales Collares hechos a mano Paradise/i',$cols[8])) {
				$fam_code='Pcol';
				$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			}
			if (preg_match('/Pendientes Paradise/i',$cols[8])) {
				$fam_code='Ppen';
				$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			}


		}


		if ($cols[5]!='' and $cols[8]!='') {
			$fam_code=$cols[5];
			$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			$fam_position=$column;


		}

		if (
			preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free|\d+ o m.as y obtendr.s \s+\% descuanto/i',_trim($cols[8]))
			or
			preg_match('/\d+\% desc|Descuento del \d+\%|Oferta \d+\s*x\s*\d+|Oferta \d+\% Descuento|Pide .*\d+\% descuento|\d+% Descuento desde \d+|Rebajas al \d+%|Descuento \d+%|Rebajas \d+%|\d+% Descuento comprando \d+|\d% Descuento al comprar \d|Comprando \d+ .* \d+% Desc|\d+% Desc.*\d+/i',_trim($cols[8]))
		) {


			$promotion=$cols[8];

			$promotion=preg_replace('/^\s*order\s*/i','',$promotion);
			$promotion=preg_replace('/discount\s*$/i','',$promotion);
			$promotion=preg_replace('/\s*off\s*$/i','',$promotion);

			$promotion=_trim($promotion);
			$promotion_position=$column;
			// print "*********** Promotion $promotion $promotion_position \n";
		}
		if ($cols[5]=='' and $cols[8]=='') {
			$blank_position=$column;
		}




		if ( ($cols[8]!='' and preg_match('/Sub Total/i',$cols[13])) or preg_match('/Bathroom Heaven/',$cols[8]) or $cols[8]=='Paradise Accesories' or preg_match('/Departamento de Bolsas/',$cols[8]) ) {


			$department_name=trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
			$department_position=$column;

			//  print_r($cols);
			// if($department_name!='Ancient Wisdom Home Fragrance')


		}

		$posible_fam_code=$cols[5];
		$posible_fam_name=$cols[8];
	}



	$column++;
}


function update_supplier_part($code,$scode,$supplier_code,$units,$w,$product,$description,$supplier_cost) {
	global $myconf,$editor;
	$product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("now +1 seconds")));
	if (preg_match('/^SG\-|^info\-/i',$code))
		$supplier_code='AW';
	if ($supplier_code=='AW')
		$scode=$code;



	if ($scode=='SSK-452A' and $supplier_code=='Smen')
		$scode='SSK-452A bis';


	if (preg_match('/^(StoneM|Smen)$/i',$supplier_code)) {
		$supplier_code='StoneM';
	}


	$the_supplier_data=array(
		'name'=>$supplier_code,
		'code'=>$supplier_code,
	);

	// Suppplier data
	if (preg_match('/Ackerman|Ackerrman|Akerman/i',$supplier_code)) {
		$supplier_code='Ackerman';
		$the_supplier_data=array(
			'Supplier Name'=>'Ackerman Group',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'Unit 15/16'
			,'Supplier Address Line 2'=>'Hickman Avenue'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'London'
			,'town_d1'=>''
			,'town_d2'=>'Chingford'
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'Supplier Address Postal Code'=>'E4 9JG'

			,'Supplier Main Plain Email'=>'office@ackerman.co.uk'
			,'Supplier Main Plain Telephone'=>'020 8527 6439'
		);
	}
	if (preg_match('/^puck$/i',$supplier_code)) {
		$supplier_code='Puck';
		$the_supplier_data=array(
			'Supplier Name'=>'Puckator',
			'Supplier Code'=>$supplier_code
			,'Supplier Address Line 1'=>'Lowman Works'
			,'Supplier Address Line 2'=>''
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'East Taphouse'
			,'town_d1'=>''
			,'town_d2'=>'Near Liskeard'
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'PL14 4NQ'

			,'Supplier Main Plain Email'=>'accounts@puckator.co.uk'
			,'Supplier Main Plain Telephone'=>'1579321550'
			,'Supplier Main Plain FAX'=>'1579321520'
		);
	}

	if (preg_match('/^decent gem$/i',$supplier_code)) {
		$supplier_code='DecGem';
		$the_supplier_data=array(
			'Supplier Name'=>'Decent Gemstone Exports',
			'Supplier Code'=>$supplier_code


			,'Supplier Address Line 1'=>"Besides Balaji's Mandir"
			,'Supplier Address Line 2'=>'Near Rajputwad'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Khambhat'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'India'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'388620'

			,'Supplier Main Plain Email'=>'decentstone@sancharnet.in'
			,'Supplier Main Plain Telephone'=>'00917926578604'
			,'Supplier Main Plain FAX'=>'00917926584997'
		);
	}
	if (preg_match('/^kiran$/i',$supplier_code)) {

		$the_supplier_data=array(
			'Supplier Name'=>'Kiran Agencies',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"4D Garstin Place"
			,'Supplier Address Line 2'=>''
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Kolkata'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'India'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'700001'

			,'Supplier Main Plain Telephone'=>'919830020595'

		);
	}


	if (preg_match('/^watkins$/i',$supplier_code)) {

		$the_supplier_data=array(
			'Supplier Name'=>'Watkins Soap Co Ltd',
			'Supplier Code'=>$supplier_code


			,'Supplier Address Line 1'=>"Reed Willos Trading Est"
			,'Supplier Address Line 2'=>'Finborough Rd'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Stowmarket'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'IP14 3BU'


			,'Supplier Main Plain Telephone'=>'01142501012'
			,'Supplier Main Plain FAX'=>'01142501006'
		);
	}



	if (preg_match('/^decree$/i',$supplier_code)) {

		$the_supplier_data=array(
			'Supplier Name'=>'Decree Thermo Limited',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"300 Shalemoor"
			,'Supplier Address Line 2'=>'Finborough Rd'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Sheffield'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'S3 8AL'

			,'Supplier Main Contact Name'=>'Zoie'
			,'Supplier Main Plain Email'=>'Watkins@soapfactory.fsnet.co.uk'
			,'Supplier Main Plain Telephone'=>'01449614445'
			,'Supplier Main Plain FAX'=>'014497111643'
		);
	}

	if (preg_match('/^cbs$/i',$supplier_code)) {

		$the_supplier_data=array(
			'Supplier Name'=>'Carrierbagshop',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Unit C18/21"
			,'Supplier Address Line 2'=>'Hastingwood trading Estate'
			,'Supplier Address Line 3'=>'35 Harbet Road'
			,'Supplier Address Town'=>'London'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'N18 3HU'

			,'Supplier Main Contact Name'=>'Neil'
			,'Supplier Main Plain Email'=>'info@carrierbagshop.co.uk'
			,'Supplier Main Plain Telephone'=>'08712300980'
			,'Supplier Main Plain FAX'=>'08712300981'
		);
	}


	if (preg_match('/^giftw$/i',$supplier_code)) {

		$the_supplier_data=array(
			'Supplier Name'=>'Giftworks Ltd',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Unit 14"
			,'Supplier Address Line 2'=>'Cheddar Bussiness Park'
			,'Supplier Address Line 3'=>'Wedmore Road'
			,'Supplier Address Town'=>'Cheddar'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'BS27 3EB'

			,'Supplier Main Plain Email'=>'info@giftworks.tv'
			,'Supplier Main Plain Telephone'=>'441934742777'
			,'Supplier Main Plain FAX'=>'441934740033'
			,'www.giftworks.tv'
		);
	}


	if (preg_match('/^Sheikh$/i',$supplier_code)) {
		$supplier_code='Sheikh';
		$the_supplier_data=array(
			'Supplier Name'=>'Sheikh Enterprises',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Eidgah Road"
			,'Supplier Address Line 2'=>'Opp. Islamia Inter College'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Saharanpur'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'India'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'247001'


		);
	}
	if (preg_match('/^Gopal$/i',$supplier_code)) {
		$supplier_code='Gopal';
		$the_supplier_data=array(
			'Supplier Name'=>'Gopal HQ Limited',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"240 Okhla Industrial Estate"
			,'Supplier Address Line 2'=>'Phase III'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'New Delhi'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'India'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'110020'

			,'Supplier Main Plain Telephone'=>'00911126320185'
		);
	}

	if (preg_match('/^CraftS$/i',$supplier_code)) {
		$supplier_code='CraftS';
		$the_supplier_data=array(
			'Supplier Name'=>'Craftstones Europe Ltd',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"52/54 Homethorphe Avenue"
			,'Supplier Address Line 2'=>'Homethorphe Ind. Estate'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Redhill'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'RH1 2NL'

			,'Supplier Main Contact Name'=>'Jose'

			,'Supplier Main Plain Telephone'=>'01737767363'
			,'Supplier Main Plain FAX'=>'01737768627'
		);
	}

	if (preg_match('/^Simpson$/i',$supplier_code)) {
		$supplier_code='CraftS';
		$the_supplier_data=array(
			'Supplier Name'=>'Simpson Packaging',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Unit 1"
			,'Supplier Address Line 2'=>'Shaw Cross Business Park'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Dewsbury'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'WF12 7RF'


			,'Supplier Main Plain Email'=>'sales@simpson-packaging.co.uk'
			,'Supplier Main Plain Telephone'=>'01924869010'
			,'Supplier Main Plain FAX'=>'01924439252'
			,'Supplier Main Web Site'=>'wwww.simpson-packaging.co.uk'
		);
	}



	if (preg_match('/^amanis$/i',$supplier_code)) {
		$supplier_code='AmAnis';
		$the_supplier_data=array(
			'Supplier Name'=>'Amanis',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Unit 6"
			,'Supplier Address Line 2'=>'Bowlimng Court Industrial Estate'
			,'Supplier Address Line 3'=>'Mary Street'
			,'Supplier Address Town'=>'Bradford'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'BD4 8TT'


			,'Supplier Main Plain Email'=>'saltlamps@aol.com'
			,'Supplier Main Plain Telephone'=>'4401274394100'
			,'Supplier Main Plain FAX'=>'4401274743243'
			,'Supplier Main Web Site'=>'www.saltlamps-r-us.com'
		);
	}


	if (preg_match('/^amanis$/i',$supplier_code)) {
		$supplier_code='AmAnis';
		$the_supplier_data=array(
			'Supplier Name'=>'Amanis',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Unit 6"
			,'Supplier Address Line 2'=>'Bowlimng Court Industrial Estate'
			,'Supplier Address Line 3'=>'Mary Street'
			,'Supplier Address Town'=>'Bradford'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'BD4 8TT'

			,'Supplier Main Plain Email'=>'saltlamps@aol.com'
			,'Supplier Main Plain Telephone'=>'4401274394100'
			,'Supplier Main Plain FAX'=>'4401274743243'
			,'Supplier Main Web Site'=>'www.saltlamps-r-us.com'
		);
	}


	if (preg_match('/^Wenzels$/i',$supplier_code)) {
		$supplier_code='Wenzels';
		$the_supplier_data=array(
			'Supplier Name'=>'Richard Wenzel GMBH & CO KG',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>"Benzstraße 5"
			,'Supplier Address Line 2'=>''
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Aschaffenburg'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'Germany'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'63741'



			,'Supplier Main Plain Telephone'=>'49602134690'
			,'Supplier Main Plain FAX'=>'496021346940'

		);
	}


	if (preg_match('/^AW$/i',$supplier_code)) {
		$supplier_code='AW';
		$the_supplier_data=array(
			'Supplier Name'=>'Ancient Wisdom Marketing',
			'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'Block B'
			,'Supplier Address Line 2'=>'Parkwood Business Park'
			,'Supplier Address Line 3'=>'Parkwood Road'
			,'Supplier Address Town'=>'Sheffield'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'S3 8AL'

			,'Supplier Main Plain Email'=>'mail@ancientwisdom.biz'
			,'Supplier Main Plain Telephone'=>'44 (0)114 2729165'

		);
	}


	if (preg_match('/^EB$/i',$supplier_code)) {
		$supplier_code='EB';
		$the_supplier_data=array(
			'Supplier Name'=>'Elements Bodycare Ltd'
			,'Supplier Code'=>$supplier_code


			,'Supplier Address Line 1'=>'Unit 2'
			,'Supplier Address Line 2'=>'Carbrook Bussiness Park'
			,'Supplier Address Line 3'=>'Dunlop Street'
			,'Supplier Address Town'=>'Sheffield'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'S9 2HR'


			,'Supplier Main Plain Telephone'=>'011422434000'
			,'Supplier Main Web Site'=>'www.elements-bodycare.co.uk'
			,'Supplier Main Plain Email'=>'info@elements-bodycare.co.uk'

		);
	}

	if (preg_match('/^Paradise$/i',$supplier_code)) {
		$supplier_code='Paradise';
		$the_supplier_data=array(
			'Supplier Name'=>'Paradise Music Ltd'
			,'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'PO BOX 998'
			,'Supplier Address Line 2'=>'Carbrook Bussiness Park'
			,'Supplier Address Line 3'=>'Dunlop Street'
			,'Supplier Address Town'=>'Tring'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'HP23 4ZJ'


			,'Supplier Main Plain Telephone'=>'01296668193'


		);
	}
	if (preg_match('/^MCC$/i',$supplier_code)) {
		$supplier_code='MCC';
		$the_supplier_data=array(
			'Supplier Name'=>'Manchester Candle Company'
			,'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'The Manchester Group'
			,'Supplier Address Line 2'=>'Kenwood Road'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'North Reddish'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'SK5 6PH'

			,'Supplier Main Contact Name'=>'Brian'
			,'Supplier Main Plain Telephone'=>'01614320811'
			,'Supplier Main Plain FAX'=>'01614310328'
			,'Supplier Main Web Site'=>'manchestercandle.com'

		);
	}
	if (preg_match('/^Aquavision$/i',$supplier_code)) {
		$supplier_code='Aquavision';
		$the_supplier_data=array(
			'Supplier Name'=>'Aquavision Music Ltd'
			,'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'PO BOX 2796'
			,'Supplier Address Line 2'=>''
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Iver'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'SL0 9ZR'


			,'Supplier Main Plain Telephone'=>'01753653188'
			,'Supplier Main Plain FAX'=>'01753655059'
			,'Supplier Main Web Site'=>'www.aquavisionwholesale.co.uk'
			,'Supplier Main Plain Email'=>'info@aquavisionwholesale.co.uk'
		);
	}

	if (preg_match('/^CXD$/i',$supplier_code)) {
		$supplier_code='CXD';
		$the_supplier_data=array(
			'Supplier Name'=>'CXD Designs Ltd'
			,'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'Unit 2'
			,'Supplier Address Line 2'=>'Imperial Park'
			,'Supplier Address Line 3'=>'Towerfiald Road'
			,'Supplier Address Town'=>'Shoeburyness'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>'Essex'
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'SS3 9QT'

			,'Supplier Main Plain Telephone'=>'01702292028'
			,'Supplier Main Plain FAX'=>'01702298486'

		);
	}
	if (preg_match('/^(AWR|costa)$/i',$supplier_code)) {
		$supplier_code='AWR';
		$the_supplier_data=array(
			'Supplier Name'=>'Costa Imports'
			,'Supplier Code'=>$supplier_code

			,'Supplier Address Line 1'=>'Nave 8'
			,'Supplier Address Line 2'=>'Polígono Ind. Alhaurín de la Torre Fase 1'
			,'Supplier Address Line 3'=>'Paseo de la Hispanidad'
			,'Supplier Address Town'=>'Málaga'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'Spain'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'29130'

			,'Supplier Main Contact Name'=>'Carlos'
			,'Supplier Main Plain Email'=>'carlos@aw-regalos.com'
			,'Supplier Main Plain Telephone'=>'(+34) 952 417 609'
		);
	}

	if (preg_match('/^(salco)$/i',$supplier_code)) {
		$supplier_code='Salco';
		$the_supplier_data=array(
			'Supplier Name'=>'Salco Group'
			,'Supplier Code'=>$supplier_code
			,'Supplier Address Line 1'=>'Salco House'
			,'Supplier Address Line 2'=>'5 Central Road'
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Harlow'
			,'town_d1'=>''
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>'Essex'
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'CM20 2ST'
			,'Supplier Main Plain Email'=>'alco@salcogroup.com'
			,'Supplier Main Plain Telephone'=>'01279 439991'
		);
	}
	if (preg_match('/^(apac)$/i',$supplier_code)) {
		$supplier_code='Salco';
		$the_supplier_data=array(
			'Supplier Name'=>'APAC Packaging Ltd'
			,'Supplier Code'=>$supplier_code
			,'Supplier Address Line 1'=>'Loughborough Road'
			,'Supplier Address Line 2'=>''
			,'Supplier Address Line 3'=>''
			,'Supplier Address Town'=>'Leicester'
			,'town_d1'=>'Rothley'
			,'town_d2'=>''
			,'Supplier Address Country Name'=>'UK'
			,'country_d1'=>''
			,'country_d2'=>''
			,'default_country_id'=>$myconf['country_id']
			,'Supplier Address Postal Code'=>'LE7 7NL'
			,'Supplier Main Plain Email'=>''
			,'Supplier Main Plain Telephone'=>'0116 230 2555'
			,'Supplier Main Web Site'=>'www.apacpackaging.com'
			,'Supplier Main Plain FAX'=>'0116 230 3555'
		);
	}
	if (preg_match('/^(andy.*?)$/i',$supplier_code)) {
		$supplier_code='Andy';
		$the_supplier_data=array(
			'Supplier Name'=>'Andy'
			,'Supplier Code'=>$supplier_code
		);
	}


	if ($supplier_code=='' or $supplier_code=='0'   or preg_match('/^costa$/i',$supplier_code)  ) {
		$supplier_code='Unknown';
		$the_supplier_data=array(
			'Supplier Name'=>'Proveedor Desconocido'
			,'Supplier Code'=>$supplier_code
		);
	}

	$supplier=new Supplier('code',$supplier_code);



	if (!$supplier->id) {
		//print "neew: $supplier_code\n";
		//print_r($the_supplier_data);

		$supplier=new Supplier('find',$the_supplier_data,'create update');
	}

	if (strlen($supplier->data['Supplier Name'])<=1) {
		print "$code (supplier name -> ".$supplier->data['Supplier Name']." to short)\n";
	}if ($supplier->data['Supplier Code']=='UNK') {
		print "$code supplier unknown\n";
	}

	//exit;


	$scode=_trim($scode);
	$scode=preg_replace('/^\"\s*/','',$scode);
	$scode=preg_replace('/\s*\"$/','',$scode);

	if ($scode=='' or $scode=='0') {
		$scode='';
		print "$code wrong supplier code -> ($scode)\n";
	}


	if (preg_match('/\d+ or more|0.10000007|8.0600048828125|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$scode)) {
		print "$code wrong supplier code -> ($scode)\n";

		$scode='';
	}if (preg_match('/^(\?|new|\d|0.25|0.5|0.8|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$scode)) {
		print "$code wrong supplier code -> ($scode)\n";

		$scode='';
	}
	if ($scode=='same') {
		$scode=$code;
		print "$code strange supplier code -> (same)\n";
	}


	if ($scode=='') {
		//   print "$code wrong supplier code using ?$code\n";

		$scode='?'.$code;
	}
	$sp_data=array(
		'editor'=>$editor,
		'Supplier Key'=>$supplier->id,
		'Supplier Product Code'=>$scode,
		'Supplier Product Units Per Case'=>1,
		'SPH Case Cost'=>sprintf("%.2f",$supplier_cost),
		'Supplier Product Name'=>$description,
		'Supplier Product Description'=>$description,
		'Supplier Product Valid From'=>$editor['Date'],
		'Supplier Product Valid To'=>$editor['Date']
	);
	// print_r($sp_data);
	$supplier_product=new SupplierProduct('find',$sp_data,'create');
	// print_r($supplier_product->data);

	if ($supplier_product->found_in_key) {
		print "$code (duplicate supplier code)\n";
	}elseif ($supplier_product->found_in_code) {
		print "$code (duplicate supplier code (diff cost))\n";
	}


	$part_data=array(
		'editor'=>$editor,
		'Part Most Recent'=>'Yes',
		'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
		'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
		'Part Unit Description'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description'))),

		'part valid from'=>$editor['Date'],
		'part valid to'=>$editor['Date'],
		'Part Gross Weight'=>$w
	);
	$part=new Part('new',$part_data);
	if ($part->new) {

	}



	$spp_header=array(
		'Supplier Product Part Type'=>'Simple',
		'Supplier Product Part Most Recent'=>'Yes',
		'Supplier Product Part Valid From'=>$editor['Date'],
		'Supplier Product Part Valid To'=>$editor['Date'],
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

	$part_list[]=array(
		'Part SKU'=>$part->get('Part SKU'),
		'Parts Per Product'=>1,
		'Product Part Type'=>'Simple'
	);



	$product->new_current_part_list(array(),$part_list)  ;

	$supplier_product->update_sold_as();
	$supplier_product->update_store_as();
	$product->update_parts();
	$part->update_used_in();
	$part->update_supplied_by();
	$product->update_cost_supplier();


}

function set_part_as_available($product) {

	$current_part_skus=$product->get_current_part_skus();
	foreach ($current_part_skus as $_part_sku) {
		$part=new Part($_part_sku);
		//$part->update_status('Not In Use');

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
