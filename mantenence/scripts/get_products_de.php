<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Campaign.php');
include_once('../../class.Charge.php');

include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Warehouse.php');
include_once('../../class.Node.php');
include_once('../../class.Shipping.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           


$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2009Germany.xls';
$csv_file='tmp.csv';
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=2;


$dept_data=array(
		 'Product Department Code'=>'ND',
		 'Product Department Name'=>'Products Without Department',
		 'Product Department Store Key'=>$store_key
		 );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		 'Product Department Code'=>'Promo',
		 'Product Department Name'=>'Promotional Items',
		 'Product Department Store Key'=>$store_key
		 );
$dept_promo=new Department('find',$dept_data,'create');
$dept_promo_key=$dept_promo->id;

$fam_data=array(
		'Product Family Code'=>'PND_GB',
		'Product Family Name'=>'Products Without Family',
		'Product Family Main Department Key'=>$dept_no_dept_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		'Product Family Code'=>'Promo_GB',
		'Product Family Name'=>'Promotional Items',
		'Product Family Main Department Key'=>$dept_promo_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$campaign=array(
		'Campaign Name'=>'Goldprämie'
		,'Campaign Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Order Interval'
		,'Campaign Deal Terms Description'=>'last order within 1 month'
		,'Campaign Deal Terms Lock'=>'Yes'

		);
$gold_camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'[Product Family Code] Goldprämie'
	    ,'Deal Trigger'=>'Family'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Allowance Target'=>'Family'
	    ,'Deal Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Campaign Name'=>'Volumen Discount'
		,'Campaign Trigger'=>'Family'
		,'Campaign Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Terms Lock'=>'No'
		);
$vol_camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'[Product Family Code] Volume Discount'
	    ,'Deal Trigger'=>'Family'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Allowance Target'=>'Family'
	    ,'Deal Allowance Lock'=>'No'

	    );
$vol_camp->add_deal_schema($data);

$volume_cam_id=$vol_camp->id;


$free_shipping_campaign_data=array(
				   'Campaign Name'=>'Free Shipping'
		     
				   ,'Campaign Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Campaign Begin Date'=>''
				   ,'Campaign Expiration Date'=>''
				   ,'Campaign Deal Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Terms Lock'=>'No'
				   );
$free_shipping_campaign=new Campaign('find create',$free_shipping_campaign_data);


$data=array(
	    'Deal Name'=>'[Country Name] Free Shipping'
	    ,'Deal Trigger'=>'Order'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'Free Shipping'
	    ,'Deal Allowance Target'=>'Shipping'
	    ,'Deal Allowance Lock'=>'Yes'

	    );
$free_shipping_campaign->add_deal_schema($data);

$free_shipping_campaign_id=$free_shipping_campaign->id;


$shipping_uk=new Shipping('find',array('Country Code'=>'DEU'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DEU','€500');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'DNK'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','DNK','€500');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$shipping_uk=new Shipping('find',array('Country Code'=>'AUT'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€500');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$shipping_uk=new Shipping('find',array('Country Code'=>'NOR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','AUT','€795');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);

$campaign=array(
		'Campaign Name'=>'BOGOF'
		,'Campaign Description'=>'Buy one Get one Free'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Terms Description'=>'Buy 1'
		,'Campaign Deal Terms Lock'=>'Yes'
		);
$bogof_camp=new Campaign('find create',$campaign);
$data=array(
	    'Deal Name'=>'[Product Family Code] BOGOF'
	    ,'Deal Trigger'=>'Family'
	    ,'Deal Allowance Type'=>'Get Free'
	    ,'Deal Allowance Description'=>'get 1 free'
	    ,'Deal Allowance Target'=>'Product'
	    ,'Deal Allowance Lock'=>'Yes'
	    );
$bogof_camp->add_deal_schema($data);

$data=array(
	    'Deal Name'=>'[Product Code] BOGOF'
	    ,'Deal Trigger'=>'Product'
	    ,'Deal Allowance Type'=>'Get Same Free'
	    ,'Deal Allowance Description'=>'get 1 free'
	    ,'Deal Allowance Target'=>'Product'
	    ,'Deal Allowance Lock'=>'Yes'

	    );
$bogof_camp->add_deal_schema($data);


$bogof_cam_id=$bogof_camp->id;
$campaign=array(
		'Campaign Name'=>'First Order Bonus'
		,'Campaign Trigger'=>'Order'
		,'Campaign Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Terms Lock'=>'Yes'
		);
$camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'First Order Bonus [Counter]'
	    ,'Deal Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Allowance Type'=>'Get Free'
	    ,'Deal Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Allowance Target'=>'Product'
	    ,'Deal Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);


$__cols=array();
$inicio=false;
while(($_cols = fgetcsv($handle_csv))!== false){
  

  $code=$_cols[3];

 
  if($code=='FO-A1' and !$inicio){
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

  }elseif($code=='Credit'){
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


foreach($__cols as $cols){
  $is_product=true;
  $code=_trim($cols[3]);
  $price=$cols[9];
  $supplier_code=_trim($cols[23]);
  $part_code=_trim($cols[24]);
  $supplier_cost=$cols[27];
  $units=$cols[5];
  $rrp=$cols[18];
  $supplier_code=_trim($cols[23]);
  $w=$cols[31];
  $description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
  $fam_special_char=_trim( mb_convert_encoding($cols[7], "UTF-8", "ISO-8859-1,UTF-8"));
  $special_char=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
  
  if(!preg_match('/^DONE$/',$cols[0]))
    $is_product=false;
  $code=_trim($code);
  if($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
    $is_product=false;
  if(preg_match('/^(ob\-108|ob\-156|ish\-94|rds\-47)/i',$code))
    $is_product=false;
  if(preg_match('/^staf-set/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^hook-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^shop-fit-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
    $is_product=false;
  
  
  if(preg_match('/^credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
    $is_product=false;


  
  if($is_product){
    
  
    // if(preg_match('/po/',$code))
    print "$code\n";
    $part_list=array();
    $rules=array();
    
    $current_fam_name=$fam_name;
    $current_fam_code=$fam_code;
    if($new_family){
      //    print "New family $column $promotion_position \n";
      if($promotion!='' and  ($column-$promotion_position)<4 ){
	$current_promotion=$promotion;
      }else
	$current_promotion='';
      $new_family=false;
    }


  

    
    $deals=array();
    if(preg_match('/oder mehr/i',_trim($current_promotion))){
      if(preg_match('/^\d+\%/i',$current_promotion,$match))
	$allowance=$match[0];
      if(preg_match('/\d+ oder mehr/i',$current_promotion,$match))
	$terms=$match[0];
      
      //	print "************".$current_promotion."\n";
      $deals[]=array(
		     'Deal Name'=>'Gold Reward'
		     ,'Deal Allowance Description'=>$allowance
		     
		     );
      
      $deals[]=array(
		     'Deal Name'=>'Family Volume Discount'
		     
		     ,'Deal Allowance Description'=>$allowance
		     
		     ,'Deal Terms Description'=>'beim kauf von '.$terms
		   
		     );	

      
      
    }else
      $deals=array();
    
  
  if($units=='' OR $units<=0)
    $units=1;
  

  $description=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));

  //    if(preg_match('/wsl-535/i',$code)){
  //       print_r($cols);
  //       exit;

  //     }

  $rrp=$cols[16];
  $supplier_code=_trim($cols[21]);

  $w=$cols[28];

  if($code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St' 
     or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
     or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
     ){
    print "Skipping $code\n";
    continue;
  }

      
  if(!is_numeric($price) or $price<=0){
    print "Price Zero  $code \n";
    $price=0;
  }
    
  
  if($code=='Tib-20')
    $supplier_cost=0.2;
  
  if(!is_numeric($supplier_cost)  or $supplier_cost<=0 ){
      //   print_r($cols);
    print "$code   assumind supplier cost of 40%  \n";
    $supplier_cost=0.4*$price/$units;
    
  }

    
    $uk_product=new Product('code_store',$code,1);

    
    
    if($units=='')
      $units=1;
      
    if(is_numeric($rrp))
      $rrp=sprintf("%.2f",$rrp*$units);
    else
      $rrp='';
      
    
    if($fam_special_char=='' or $special_char==''){
      
      $_f=preg_replace('/s$/i','',$current_fam_name);
      $special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
      $special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
      $special_char=_trim($special_char);
      if($special_char==$description){
	$description=$current_fam_name.' '.$special_char;
	$fam_special_char=$current_fam_name;
      }else
	$fam_special_char=preg_replace('/'.str_replace('/','\/',$special_char).'$/i','',$description);
    }
	    
	

    if(is_numeric($w)){
      $w=$w*$units;
      if($w<0.001 and $w>0)
	$_w=0.001;
      else
	$_w=sprintf("%.3f",$w);
    }else
      $_w='';
      
    if(preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($code))){

	
      $family=new Family($fam_promo_key);
	 
    }else{
      $dep_data=array(
		      'Product Department Code'=>$department_code,
		      'Product Department Name'=>$department_name,
		      'Product Department Store Key'=>$store_key
		      );
      $department=new Department('find',$dep_data,'create');

      if($department->error){
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


    if(!$family->id){
      print_r($family);
      exit;
   
    }


    foreach($deals as $deal_data){
      //         print_r($deal_data);
      //exit;

      $deal_data['Store Key']=$store_key;

      if(preg_match('/Family Volume/i',$deal_data['Deal Name'])){

	$data=array(
		    'Deal Allowance Target Key'=>$family->id,
		    'Deal Trigger Key'=>$family->id,

		    'Deal Allowance Description'=>$deal_data['Deal Allowance Description'],
		    'Deal Terms Description'=>$deal_data['Deal Terms Description']
		    
		    );

	$vol_camp->create_deal('[Product Family Code] Volume Discount',$data);


      }


      if(preg_match('/Gold/i',$deal_data['Deal Name'])){

	$data=array(
		    'Deal Trigger Key'=>$family->id,
		    'Deal Allowance Target Key'=>$family->id,
		    'Deal Allowance Description'=>$deal_data['Deal Allowance Description']
		    );

	$gold_camp->create_deal('[Product Family Code] Goldprämie',$data);

      }

      if(preg_match('/bogof/i',$deal_data['Deal Name'])){
	$data=array(
		    'Deal Trigger Key'=>$family->id,
		    'Deal Allowance Target Key'=>$family->id,
		    'Deal Allowance Description'=>$deal_data['Deal Allowance Description']
		    );

	$bogof_camp->create_deal('[Product Family Code] BOGOF',$data);
      }
    }  
    $data=array(
		'product code'=>$code,
		'product store key'=>$store_key,
		'product locale'=>'de_DE',
		'product currency'=>'EUR',

		'product sales state'=>'For Sale',
		'product type'=>'Normal',
		'product record type'=>'New',
		'product web state'=>'Online Auto',

		  
		'product price'=>sprintf("%.2f",$price),
		'product rrp'=>$rrp,
		'product units per case'=>$units,
		'product name'=>$description,
		'product family key'=>$family->id,
		'product special characteristic'=>$special_char,
		'product family special characteristic'=>$fam_special_char,
		'product net weight'=>$_w,
		'product gross weight'=>$_w,
		 'date1'=>date('Y-m-d H:i:s'),
		  'date2'=>date('Y-m-d H:i:s'),
		//'deals'=>$deals
		);
    //     print_r($cols);

    $parts=$uk_product->get('Parts SKU');
    $product=new Product('find',$data,'create');
    if($product->new){
      $product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("now +1 seconds")));
      if(isset($parts[0])){
 	$part_list[]=array(
 			   'Product ID'=>$product->get('Product ID'),
 			   'Part SKU'=>$parts[0],
 			   'Product Part Id'=>1,
 			   'requiered'=>'Yes',
 			   'Parts Per Product'=>1,
 			   'Product Part Type'=>'Simple Pick'
 			   );
	
 	$product->new_part_list('',$part_list);
	//	print_r($product->data);
 	$product->load('parts');
	$part =new Part('sku',$parts[0]);
 	$part->load('used in');
      }
    }
  


}else{

    $new_family=true;
    
    // print "Col $column\n";
    //print_r($cols);
    if(  preg_match('/donef/i',$cols[0])       ){
      $fam_code=$cols[3];
      $fam_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
      $fam_position=$column;

      
    }
    
    if(preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free/i',_trim($cols[6]))){
      

      $promotion=$cols[6];

      $promotion=preg_replace('/^\s*order\s*/i','',$promotion);
      $promotion=preg_replace('/discount\s*$/i','',$promotion);
      $promotion=preg_replace('/\s*off\s*$/i','',$promotion);

      $promotion=_trim($promotion);
      $promotion_position=$column;
      // print "*********** Promotion $promotion $promotion_position \n";
    }
    if($cols[3]=='' and $cols[6]==''){
      $blank_position=$column;
    }

    if(preg_match('/doned/i',$cols[0])){
      $department_name=_trim( mb_convert_encoding($cols[6], "UTF-8", "ISO-8859-1,UTF-8"));
      $department_code=_trim( mb_convert_encoding($cols[3], "UTF-8", "ISO-8859-1,UTF-8"));
      $department_position=$column;
    }
    
 
  }
  

  
  $column++;
}






?>