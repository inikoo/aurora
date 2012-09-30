
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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$set_part_as_available=false;


$_argv=$_SERVER['argv'];

if (isset($_argv[1]))
	$file_name=$_argv[1];
else
	$file_name='/data/new_excel_orderform/AWorder2002.xls';
if (isset($_argv[2]))
	$date=$_argv[2];
else
	$date=date("Y-m-d H:i:s");

if (isset($_argv[3]) and $_argv[3]=='old') {
	$map=$_y_map_old;
	$is_old=true;
} else {
	$map=$_y_map;
	$is_old=false;
}
$editor=array(
	'Date'=>$date,
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>0,
	'User Key'=>0,
);





$csv_file='gb.csv';

exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=1;
$create_cat=false;

$gold_camp=new Deal('code','UK.GR');
$vol_camp=new Deal('code','UK.Vol');
$bogof_camp=new Deal('code','UK.BOGOF');
$fam_promo=$fam_promo=new Family('code','Promo_UK',$store_key);
$fam_promo_key=$fam_promo->id;
$fam_products_no_family=new Family('code','PND_UK',$store_key);
$fam_products_no_family_key=$fam_products_no_family->id;



$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {


	$code=$_cols[$map['code']];


	if (($code=='FO-A1' or $code=='AWFO-01') and !$inicio) {
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

	}
	elseif ($code=='Credit') {
		break;
	}
	elseif (preg_match('/First Order Bonus - Welcome/',$_cols[6])) {
		break;
	}

	$__cols[]=$_cols;
}




$fam_name='Products Without Family';
$fam_code='PND_UK';


$new_family=true;


$department_code='ND_UK';
$department_name='Products Without Department';

$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';





foreach ($__cols as $cols) {

	if (preg_match('/First Order Bonus/i',$cols[$map['description']])) {
		break;
	}


	$is_product=true;

	$code=_trim($cols[$map['code']]);


	if (count($cols)<25 or($is_old and $code=='HOT-01')) {
		continue;
		//print_r($cols);

	}


	$price=$cols[$map['price']];
	$supplier_code=_trim($cols[$map['supplier_code']]);
	$part_code=_trim($cols[$map['supplier_product_code']]);
	$supplier_cost=$cols[$map['supplier_product_cost']];
	$rrp=$cols[$map['rrp']];


	//   if(!preg_match('/^soap-64$/i',$code)){
	// continue;
	//  }

	$code=_trim($code);
	if ($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
		$is_product=false;
	if (preg_match('/^(ob\-108|ish\-94|rds\-47)/i',$code))
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

		$part_list=array();
		$rules=array();

		$current_fam_name=$fam_name;
		$current_fam_code=$fam_code;
		if ($new_family) {
			//    print "New family $column $promotion_position \n";
			if ($promotion!='' and  ($column-$promotion_position)<4 ) {
				$current_promotion=$promotion;
			} else
				$current_promotion='';
			$new_family=false;
		}
		$deals=array();
		if (preg_match('/off\s+\d+\s+or\s+more/i',_trim($current_promotion))) {
			if (preg_match('/^\d+\% off/i',$current_promotion,$match))
				$allowance=$match[0];
			if (preg_match('/off.*more/i',$current_promotion,$match))
				$terms=preg_replace('/^off\s*/i','',$match[0]);

			// print "************".$current_promotion."\n";
			$deals[]=array(
				'Deal Metadata Name'=>'Gold Reward'
				,'Deal Metadata Trigger'=>'Order'

				,'Deal Description'=>$allowance.' if last order within 1 calendar month'
				,'Deal Metadata Terms Type'=>'Order Interval'
				,'Deal Metadata Terms Description'=>'last order within 1 calendar month'
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Family'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);

			$deals[]=array(
				'Deal Metadata Name'=>'Family Volume Discount'
				,'Deal Metadata Trigger'=>'Family'

				,'Deal Metadata Terms Type'=>'Family Quantity Ordered'
				,'Deal Metadata Terms Description'=>'order '.$terms
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Family'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);



		}
		elseif (preg_match('/\d+\s*or more\s*\d+\%$/i',_trim($current_promotion))) {
			// print $current_promotion." *********\n";
			preg_match('/\d+\%$/i',$current_promotion,$match);
			$allowance=$match[0].' off';
			preg_match('/\d+\s*or more/i',$current_promotion,$match);
			$terms=_trim(strtolower($match[0]));

			$deals[]=array(
				'Deal Metadata Name'=>'Gold Reward'
				,'Deal Metadata Trigger'=>'Order'
				,'Deal Description'=>$allowance.' if last order within 1 calendar month'
				,'Deal Metadata Terms Type'=>'Order Interval'
				,'Deal Metadata Terms Description'=>'last order within 1 calendar month'
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Family'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);

			$deals[]=array(
				'Deal Metadata Name'=>'Family Volume Discount'
				,'Deal Metadata Trigger'=>'Family'
				,'Deal Description'=>$allowance.' if '.$terms.' same family'
				,'Deal Metadata Terms Type'=>'Family Quantity Ordered'
				,'Deal Metadata Terms Description'=>'order '.$terms
				,'Deal Metadata Allowance Description'=>$allowance
				,'Deal Metadata Allowance Type'=>'Percentage Off'
				,'Deal Metadata Allowance Target'=>'Family'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''

			);


		}
		elseif (preg_match('/^buy \d+ get \d+ free$/i',_trim($current_promotion))) {
			// print $current_promotion." *********\n";
			preg_match('/buy \d+/i',$current_promotion,$match);
			$buy=_trim(preg_replace('/[^\d]/','',$match[0]));

			preg_match('/get \d+/i',$current_promotion,$match);
			$get=_trim(preg_replace('/[^\d]/','',$match[0]));

			$deals[]=array(
				'Deal Metadata Name'=>'BOGOF'
				,'Deal Metadata Trigger'=>'Product'
				,'Deal Description'=>'buy '.$buy.' get '.$get.' free'
				,'Deal Metadata Terms Type'=>'Product Quantity Ordered'
				,'Deal Metadata Terms Description'=>'foreach '.$buy
				,'Deal Metadata Allowance Description'=>$get.' free'
				,'Deal Metadata Allowance Type'=>'Get Free'
				,'Deal Metadata Allowance Target'=>'Family'
				,'Deal Metadata Allowance Target Key'=>''
				,'Deal Metadata Begin Date'=>''
				,'Deal Metadata Expiration Date'=>''
			);


		}
		else
			$deals=array();

		$units=$cols[$map['units']];
		if ($units=='' or $units<=0)
			$units=1;

		$description=_trim( mb_convert_encoding($cols[$map['description']], "UTF-8", "ISO-8859-1,UTF-8"));


		if ($description=='' and  $cols[$map['price']]=='') {
			continue;
		}


		//   if (!preg_match('/advent-01/i',$code))
		//     continue;

		if ($code=='Jhex-08')
			$description='Musk';


		$supplier_code=_trim($cols[$map['supplier_code']]);

		$w=$cols[$map['w']];
		$price=$cols[$map['price']];


		//print "-> $description <-  -> $price <- \n";



		if (  preg_match('/\-kit1$/i',$code) or  preg_match('/^bonus\-/i',$code)   or   preg_match('/\-Starter$/i',$code)  or   preg_match('/\-Starter\d$/i',$code)  or   preg_match('/\-st\d$/i',$code)  or  preg_match('/\-pack$/i',$code)  or    preg_match('/\-pst$/i',$code)  or    preg_match('/\-kit2$/i',$code)  or  preg_match('/\-kit1$/i',$code)  or preg_match('/\-st$/i',$code)  or   preg_match('/\-minst$/i',$code)  or  preg_match('/Bag-02Mx|Bag-04mx|Bag-05mx|Bag-06mix|Bag-07MX|Bag-12MX|Bag-13MX/i',$code) or      $code=='FishP-Mix' or  $code=='IncB-St' or $code=='IncIn-ST' or $code=='LLP-ST' or   $code=='LLP-ST' or  $code=='EO-XST' or $code=='AWRP-ST' or $code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St'
			or $code=='DOT-St' 	or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
			or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
			or ($description=='' and ( $price=='' or $price==0 ))


		) {
			print "Skipping $code\n";

		} else {


			if (!is_numeric($price) or $price<=0) {
				print "Price Zero  $code \n";
				$price=0;
				//if(!preg_match('/db/i',$code))
				//  exit;
			}


			if ($code=='Tib-20')
				$supplier_cost=0.2;



			if (!is_numeric($supplier_cost)  or $supplier_cost<=0 ) {
				//   print_r($cols);
				print "$code   assumind supplier cost of 40%  \n";
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
			} else
				$_w='';


			if ($current_fam_code=='LavF / PF')
				$current_fam_code='PF';
			if ($current_fam_code=='MIST / AM')
				$current_fam_code='MIST';
			if ($current_fam_code=='LBI / IS')
				$current_fam_code='LBI';

			if ($current_fam_code=='Leb - Lebp')
				$current_fam_code='Leb';
			if ($current_fam_code=='Bot/Pack/Wb')
				$current_fam_code='Bot';






			if (preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($code))) {


				$family=new Family($fam_promo_key);

			} else {


				$dep_data=array(
					'editor'=>$editor,
					'Product Department Code'=>$department_code,
					'Product Department Name'=>$department_name,
					'Product Department Store Key'=>$store_key
				);
				$department=new Department('find',$dep_data,'create');


				$current_fam_code=preg_replace('/ code/i','',$current_fam_code);

				$fam_data=array(
					'editor'=>$editor,
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
				exit("Error");

			}


			/*
                        foreach($deals as $deal_data) {
                            //         print_r($deal_data);
                            //exit;

                            $deal_data['Store Key']=$store_key;

                            if (preg_match('/Family Volume/i',$deal_data['Deal Metadata Name'])) {
                                //$deal_data['Deal Deal Key']=$volume_cam_id;
                                //$deal_data['Deal Metadata Name']=preg_replace('/Family/',$family->data['Product Family Code'],$deal_data['Deal Metadata Name']);
                                //$deal_data['Deal Description']=preg_replace('/same family/',$family->data['Product Family Name'].' outers',$deal_data['Deal Description']);

                                $data=array(
                                          'Deal Metadata Allowance Target Key'=>$family->id,
                                          'Deal Metadata Trigger Key'=>$family->id,

                                          'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description'],
                                          'Deal Metadata Terms Description'=>$deal_data['Deal Metadata Terms Description']

                                      );

                                $vol_camp->create_deal('[Product Family Code] Volume Discount',$data);


                            }


                            if (preg_match('/Gold/i',$deal_data['Deal Metadata Name'])) {
                                //$deal_data['Deal Deal Key']=$gold_reward_cam_id;
                                //$deal_data['Deal Metadata Name']=$family->data['Product Family Code'].' '.$deal_data['Deal Metadata Name'];

                                $data=array(
                                          'Deal Metadata Trigger Key'=>$family->id,
                                          'Deal Metadata Allowance Target Key'=>$family->id,
                                          'Deal Metadata Allowance Description'=>$deal_data['Deal Metadata Allowance Description']
                                      );

                                $gold_camp->create_deal('[Product Family Code] Gold Reward',$data);

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
				'editor'=>$editor,
				'product stage'=>'Normal',
				'product sales type'=>'Public Sale',
				'product type'=>'Normal',
				'Product stage'=>'Normal',
				'product record type'=>'Normal',
				'Product Web Configuration'=>'Online Auto',
				'product store key'=>$store_key,
				'product currency'=>'GBP',
				'product locale'=>'en_GB',
				'product code'=>$code,
				'product price'=>sprintf("%.2f",$price),
				'product rrp'=>$rrp,
				'product units per case'=>$units,
				'product name'=>$description,
				'product family key'=>$family->id,
				//'product main department key'=>$department->id,
				'product special characteristic'=>$special_char,
				'product net weight'=>$_w,
				'product gross weight'=>$_w,
				'product valid from'=>$editor['Date'],
				'product valid to'=>$editor['Date'],
				'deals'=>$deals
			);


			//print_r($data);
			if (array_key_exists($code,$codigos)) {
				print "Product: $code is duplicated\n";
				continue;
			}



			$codigos[$code]=1;

			$product=new Product('find',$data,'create');

			//print_r($family->data);
			if ($product->new_code) {
				$scode=_trim($cols[$map['supplier_product_code']]);
				$supplier_code=$cols[$map['supplier_code']];
				update_supplier_part($code,$scode,$supplier_code,$units,$w,$product,$description,$supplier_cost);
				
				
				$old_pids=$product->set_duplicates_as_historic($date);
				if (count($old_pids)>0) {
					$sql="select  GROUP_CONCAT(distinct `Part SKU`) skus from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)where `Product ID` in (".join(",",$old_pids).")  ";

					$res=mysql_query($sql);
					$skus='';
					if ($row=mysql_fetch_array($res)) {
						$skus= $row['skus'];
					}

					if ($skus!='') {
						$sql="update `Part Dimension` set `Part Status`='Not In Use',`Part Valid To`=".prepare_mysql($date)." where `Part SKU` in (".$skus.") ";
						if (!mysql_query($sql)) {
							exit("$sql\nError\n");
						}

					}
				}


			}

			$product->change_current_key($product->id);
		
			$product->update_rrp('Product RRP',$rrp);

			$product->update_stage('Normal');
			if ($set_part_as_available) {
				set_part_as_available($product);
			}

//print "zzzzzz1\n";
			if ($product->data['Product Family Key']==$fam_products_no_family_key) {
				$product->update_family_key($family->id);
			}
//print "zzzzzz2\n";
			if ($product->data['Product Sales Type']!='Private Sale') {
				$product->update_sales_type('Public Sale');



			}


//print "zzzzzz\n";

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

	} else {

		$new_family=true;

		// print "Col $column\n";
		//print_r($cols);
		if ($cols[$map['code']]!='' and $cols[$map['description']]!=''  and $cols[$map['code']]!='SHOP-Fit' and $cols[$map['code']]!='RDS-47' and $cols[$map['code']]!='ISH-94' and $cols[$map['code']]!='OB-108' and !preg_match('/^DB-/',$cols[$map['code']])  and !preg_match('/^pack-/i',$cols[$map['code']])  ) {
			$fam_code=$cols[$map['code']];
			$fam_name=_trim( mb_convert_encoding($cols[$map['description']], "UTF-8", "ISO-8859-1,UTF-8"));
			$fam_position=$column;


		}

		if (preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free/i',_trim($cols[$map['description']]))) {


			$promotion=$cols[$map['description']];

			$promotion=preg_replace('/^\s*order\s*/i','',$promotion);
			$promotion=preg_replace('/discount\s*$/i','',$promotion);
			$promotion=preg_replace('/\s*off\s*$/i','',$promotion);

			$promotion=_trim($promotion);
			$promotion_position=$column;
			// print "*********** Promotion $promotion $promotion_position \n";
		}
		if ($cols[$map['code']]=='' and $cols[$map['description']]=='') {
			$blank_position=$column;
		}

		if ($cols[$map['description']]!='' and preg_match('/Sub Total/i',$cols[$map['bonus']])) {
			$department_name=$cols[$map['description']];
			$department_position=$column;


			$department_code=_trim($department_name);
			if ($department_code=='Ancient Wisdom Home Fragrance') {
				$department_code='Home';
				$department_name='AW Home Fragrance';
			}
			if ($department_code=='Ancient Wisdom Aromatherapy Dept.') {
				$department_code='Aroma';
				$department_name='AW Aromatherapy Department';
			}
			if ($department_code=='Bathroom Heaven')
				$department_code='Bath';
			if ($department_code=='Exotic Incense Dept Order') {
				$department_code='Incense';
				$department_name='Exotic Incense Department';
			}
			if ($department_code=='While Stocks Last Order') {
				$department_code='WSL';
				$department_name='While Stocks Last';
			}
			if ($department_code=='Collectables Department') {
				$department_code='Collec';
			}
			if ($department_code=='Crystal Department' or $department_code=='Slovensky Crystal Order') {
				$department_code='Crystal';
			}
			if ($department_code=='Cards, Posters & Gift Wrap') {
				$department_code='Paper';
			}
			if ($department_code=='Retail Display Stands') {
				$department_code='RDS';
			}
			if ($department_code=='Candle Dept') {
				$department_code='Candles';
			}
			if (preg_match('/Stoneware/i',$department_code)) {
				$department_code='Stone';
				//$department_name='Stoneware Department';

			}
			if ($department_code=='Jewellery Quarter') {
				$department_code='Jewells';
			}
			if ($department_code=='Relaxing Music Collection') {
				$department_code='Music';
			}

			if ($department_code=='RibbonsRibbons.biz Ribbons Dept') {
				$department_code='Ribbons';
				$department_name='Ribbons Department';
			}


			if ($department_code=='Christmas Time') {
				$department_code='Xmas';
			}

			if ($department_code=='CraftsCrafts.biz') {
				$department_code='Crafts';
			}
			if ($department_code=='Florist-Supplies.biz') {
				$department_code='Flor';
			}
			if ($department_code=='Soft Furnishings & Textiles') {
				$department_code='Textil';
			}

			if (preg_match('/bagsbags/i',$department_code)) {

				$department_code='Bags';
				$department_name='Eco Bags Dept';
			}

			if (preg_match('/Packaging Dept/i',$department_code) or $department_code=='Packaging Ect Supplies' ) {
				$department_code='Pack';
				// $department_name='Packaging Dept';
			}
			if (preg_match('/Fashion/i',$department_code)) {
				$department_code='Scarv';
			}

			if (preg_match('/Gift Box Dept/i',$department_code)) {
				$department_code='GiftB';
			}


			if ($department_code=='Woodware Dept') {
				$department_code='Wood';
				$department_name='Woodware Department';

			}

			if (preg_match('/pouches/i',$department_code)) {

				$department_code='Pouches';
				$department_name='Pouches Dept';

			}


		}

		$posible_fam_code=$cols[$map['code']];
		$posible_fam_name=$cols[$map['description']];
	}



	$column++;
}





function update_supplier_part($code,$scode,$supplier_code,$units,$w,$product,$description,$supplier_cost) {
	global $myconf,$editor,$map;
	$product->update_for_sale_since(date("Y-m-d H:i:s",strtotime($editor['Date']." +1 seconds")));
	if (preg_match('/^SG\-|^info\-|^FO\-/i',$code))
		$supplier_code='AW';

	if (preg_match('/^(SG|FO|EO|PS|BO|EOB|AM)\-/i',$code))
		$supplier_code='AW';

	if ($supplier_code=='AW')
		$scode=$code;

	if ($scode=='SSK-452A' and $supplier_code=='Smen')
		$scode='SSK-452A bis';


	if (preg_match('/^(StoneM|Smen)$/i',$supplier_code)) {
		$supplier_code='StoneM';
	}

	if (preg_match('/Ashoke/i',$supplier_code)) {
		$supplier_code='Asoke';
	}

	$the_supplier_data=array(
		'name'=>$supplier_code,
		'code'=>$supplier_code,
	);

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
	if (preg_match('/^(puck|puckator)$/i',$supplier_code)) {
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
			'editor'=>$editor,
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


	if ($supplier_code=='' or $supplier_code=='0' or $supplier_code=='?' or preg_match('/\"[0-9]{3}/',$supplier_code) or preg_match('/disc 20\+/i',$supplier_code)  ) {
		$supplier_code='UNK';
		$the_supplier_data=array(
			'editor'=>$editor,
			'Supplier Name'=>'Unknown Supplier',
			'Supplier Code'=>$supplier_code
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
	}
	if ($supplier->data['Supplier Code']=='UNK') {
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


	if (preg_match('/\d+ or more|0.10000007|0\.300000152587891|8.0600048828125|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$scode)) {
		print "$code wrong supplier code -> ($scode)\n";

		$scode='';
	}
	if (preg_match('/^(\?|new|\d|0.25|0.5|0.8|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$scode)) {
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
	}
	elseif ($supplier_product->found_in_code) {
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
