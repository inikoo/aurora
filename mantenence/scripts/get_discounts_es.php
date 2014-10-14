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
include_once '../../class.DealCampaign.php';




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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2002-spain_descuentos.xls';
$csv_file='es_desc_tmp.csv';
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);
//exit;

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;
$set_part_as_available=false;

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



$reread_deals=true;
if ($reread_deals) {


	$sql=sprintf("select `Deal Key` from `Deal Dimension` where `Deal Store Key`=%d",$store_key);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$sql=sprintf("delete from `Deal Target Bridge` where `Deal Key`=%d",$row['Deal Key']);
		mysql_query($sql);
		$sql=sprintf("delete from `Order Deal Bridge` where `Deal Key`=%d",$row['Deal Key']);
		mysql_query($sql);
		$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Deal Key`=%d",$row['Deal Key']);
		mysql_query($sql);
		$sql=sprintf("delete from `Order No Product Transaction Deal Bridge` where `Deal Key`=%d",$row['Deal Key']);
		mysql_query($sql);
	}

	$sql=sprintf("delete from `Deal Target Bridge` where `Deal Store Key`=%d",$store_key);
	mysql_query($sql);

	$sql=sprintf("delete from `Deal Campaign Dimension` where `Deal Campaign Store Key`=%d",$store_key);
	mysql_query($sql);
	$sql=sprintf("delete from `Deal Component Dimension` where `Deal Component Store Key`=%d",$store_key);
	mysql_query($sql);
	$sql=sprintf("delete from `Deal Dimension` where `Deal Store Key`=%d",$store_key);
	mysql_query($sql);


}




$campaign_data=array('Deal Campaign Code'=>'Oro','Deal Campaign Name'=>'Club Oro','Deal Campaign Store Key'=>$store_key);
$gold_camp=new DealCampaign('find create',$campaign_data);
$campaign_data=array('Deal Campaign Code'=>'Vol','Deal Campaign Name'=>'Alto volumen','Deal Campaign Store Key'=>$store_key);
$vol_camp=new DealCampaign('find create',$campaign_data);
//$campaign_data=array('Deal Campaign Code'=>'Bogof','Deal Campaign Name'=>'Compra 1 te damos 1 gratis','Deal Campaign Store Key'=>$store_key);
//$bogof_camp=new DealCampaign('find create',$campaign_data);

$gold_deal_data=array(
	'Deal Code'=>'ORO.'.$store->data['Store Code'],
	'Deal Store Key'=>$store_key,
	'Deal Name'=>'Club Oro',
	'Deal Description'=>'Ordena dentro de 30 dias de tu último pedido, y además, si tu pedido dentro del club Oro supera los 150€ de precio neto te obsequiamos con  una botella de vino Blanco ó Rioja o un regalo alternativo',

	'Deal Trigger'=>'Order',
	'Deal Trigger Key'=>'0',
	'Deal Trigger XHTML Label'=>'',
	'Deal Terms Type'=>'Order Interval'

);

$deal_gold=$gold_camp->add_deal($gold_deal_data);





$current_promotion='';





//$bogof_camp=new Deal('code','Bogof');




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

	if ($code=='?')
		continue;


	// print_r($cols);
	// exit;
	$code=preg_replace('/\s.*$/','',$code);

	$code=_trim($code);




	if ( !preg_match('/\-/',$code) and !is_numeric($price))
		$is_product=false;

	if ($code==''  or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
		$is_product=false;



	if (preg_match('/\-st$/i',$code))
		continue;


	if (preg_match('/^(ob\-108|ob\-156|ish\-94|rds\-47)/i',$code))
		continue;
	if (preg_match('/^staf-set/i',$code) and $price=='')
		continue;
	if (preg_match('/^hook-/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^shop-fit-/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
		$is_product=false;
	if (preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
		$is_product=false;

	if (in_array($code,array('FL05/B','T10C','T54C')))
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

	if (!$is_product) {




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


		if (isset($cols['42'])) {
			if (preg_match('/\d+\;\d+/',$cols['42'])) {

				printf("bien tienes descuento en %s %s\n",$cols[5],$cols['42']);

				$family=new Family('code_store',$cols[5],$store_key);

				if ($family->id) {

					$deal_metadata=preg_split('/\;/',$cols['42']);


					$allowance=$deal_metadata[1].'% off';
					$terms=$deal_metadata[0].' or more';
					$deal_data_base=array('Deal Code'=>'Vol',
						'Deal Store Key'=>$store_key,
						'Deal Name'=>'',
						'Deal Description'=>'',
						'Deal Trigger'=>'Family',
						'Deal Terms Type'=>'Family Quantity Ordered',
						'component'=>array(
							'Deal Component Name'=>'',
							'Deal Component XHTML Name Label'=>'Descuento por volumen',

							'Deal Component Trigger Key'=>'',
							'Deal Component Terms Description'=>'order '.$terms,
							'Deal Component XHTML Terms Description Label'=>'Ordena '.preg_replace('/or more/','o mas',$terms),

							'Deal Component Allowance Description'=>$allowance,
							'Deal Component XHTML Allowance Description Label'=>preg_replace('/off/','de descuento',$allowance),
							'Deal Component Allowance Type'=>'Percentage Off',
							'Deal Component Allowance Target'=>'Family',
							'Deal Component Allowance Target Key'=>''
						)
					);


					$deal_data=$deal_data_base;

					$deal_data['Deal Code']='Vol.'.$family->data['Product Family Code'];
					$deal_data['Deal Name']=$family->data['Product Family Code'].' descuento por volumen';
					$deal_data['Deal Description']="ordena ".preg_replace('/or more/','o mas',$terms)." products de la familia ". $family->data['Product Family Code'].' y obtén ' .preg_replace('/off/',' de descuento',$allowance);
					$deal_data['Deal Trigger Key']=$family->id;
					$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);
					$deal_data['component']['Deal Component Trigger Key']=$family->id;

					$deal_data['component']['Deal Component Allowance Target Key']=$family->id;
					$deal_data['component']['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);

					$deal_data['component']['Deal Component Name']=$family->data['Product Family Code'].' descuento por volumen';
					$promotion='';
					$current_promotion='';
					$deal=$vol_camp->add_deal($deal_data);

					$deal_component=$deal->add_component($deal_data['component']);
					$deal_component->update_status('Active');


					if ($family->data['Product Family Code']=='MFF') {
						$allowance='15% off';
					}


					$deal_data_base=array(
						'Deal Code'=>'ORO.'.$store->data['Store Code'],
						'Deal Store Key'=>$store_key,
						'Deal Name'=>'Goldprämie',
						'Deal Description'=>'Ordena dentro de 30 dias de tu último pedido, y además, si tu pedido dentro del club Oro supera los 150€ de precio neto te obsequiamos con  una botella de vino Blanco ó Rioja o un regalo alternativo',
						'Deal Trigger'=>'Order',
						'Deal Trigger Key'=>'0',
						'Deal Trigger XHTML Label'=>'',
						'Deal Terms Type'=>'Order Interval',
						'component'=>array(
							'Deal Component Name'=>'',
							'Deal Component XHTML Name Label'=>'Clob Oro',
							'Deal Component Terms Description'=>'last order within 30 days',
							'Deal Component XHTML Terms Description Label'=>'',
							'Deal Component Allowance Description'=>$allowance,
							'Deal Component XHTML Allowance Description Label'=>preg_replace('/off/','de descuento',$allowance),
							'Deal Component Allowance Type'=>'Percentage Off',
							'Deal Component Allowance Target'=>'Family',
							'Deal Component Allowance Target Key'=>''
						)
					);


					$deal_data=$deal_data_base;
					$deal_data['component']['Deal Component Trigger Key']=$family->id;
					$deal_data['component']['Deal Component Trigger']='Family';
					$deal_data['component']['Deal Component Allowance Target Key']=$family->id;
					$deal_data['component']['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',$family->id,$family->data['Product Family Code']);
					$deal_data['component']['Deal Component Name']=$family->data['Product Family Code'].' Club Oro';
					$promotion='';$current_promotion='';


					$deal_component=$deal_gold->add_component($deal_data['component']);
					$deal_component->update_status('Active');


				}else {
					print "error, family code not found ->".$cols[5]."\n";
				}







			}

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



?>
