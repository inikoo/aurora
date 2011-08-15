<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('local_map.php');

error_reporting(E_ALL);






$ymap=$_y_map_old;


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='6900.xls';
$csv_file='tmp.csv';
//exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=1;
$dept_no_dept=new Department('code_store','ND',$store_key);
if(!$dept_no_dept->id){
  $dept_data=array(
		   'code'=>'ND',
		   'name'=>'Products Without Department',
		   'store_key'=>$store_key
		   );
  $dept_no_dept=new Department('create',$dept_data);
  $dept_no_dept_key=$dept_no_dept->id;
}
$dept_promo=new Department('code_store','Promo',$store_key);
if(!$dept_promo->id){
  $dept_data=array(
		   'code'=>'Promo',
		   'name'=>'Promotional Items',
		   'store_key'=>$store_key
		   );
  $dept_promo=new Department('create',$dept_data);
  
}


$dept_no_dept_key=$dept_no_dept->id;
$dept_promo_key=$dept_promo->id;

$fam_no_fam=new Family('code_store','PND_GB',$store_key);
if(!$fam_no_fam->id){
  $fam_data=array(
		   'Product Family Code'=>'PND_GB',
		   'Product Family Name'=>'Products Without Family',
		   'Product Family Main Department Key'=>$dept_no_dept_key
		   );
  $fam_no_fam=new Family('create',$fam_data);
  $fam_no_fam_key=$fam_no_fam->id;
  $dept_no_dept->load('products_info');
}
$fam_promo=new Family('code_store','Promo_GB',$store_key);
if(!$fam_promo->id){
  $fam_data=array(
		   'code'=>'Promo_GB',
		   'name'=>'Promotional Items',
		   'Product Family Main Department Key'=>$dept_promo_key
		   );
  $fam_promo=new Family('create',$fam_data);
  $dept_promo->load('products_info');
}


$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$__cols=array();
$inicio=false;
while(($_cols = fgetcsv($handle_csv))!== false){
    $code=$_cols[$ymap['code']];

    //print "$code\n";
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
$department_code='';

$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


foreach($__cols as $cols){
  

  $is_product=true;
  
  $code=_trim($cols[$ymap['code']]);
  $description=_trim( mb_convert_encoding($cols[$ymap['description']], "UTF-8", "ISO-8859-1,UTF-8"));
  $units=$cols[$ymap['units']];
  $price=$cols[$ymap['price']];
  $supplier_code=_trim($cols[$ymap['supplier_code']]);
  $part_code=_trim($cols[22]);
  $supplier_cost=$cols[$ymap['supplier_product_cost']];
  $rrp=$cols[$ymap['rrp']];
  $w=$cols[$ymap['w']];


  // if(preg_match('/EO-/i',$code)){
  //     print_r($cols);
  //   exit;   }
  
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
  
  
  if(preg_match('/^hot-01|credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
    $is_product=false;


  
  if($is_product){
    
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
    if(preg_match('/off\s+\d+\s+or\s+more/i',_trim($current_promotion))){
      if(preg_match('/^\d+\% off/i',$current_promotion,$match))
	$allowance=$match[0];
      if(preg_match('/off.*more/i',$current_promotion,$match))
	$terms=preg_replace('/^off\s*/i','',$match[0]);
      else
	//	print "************".$current_promotion."\n";
      $deals[]=array(
		     'deal campain name'=>'Gold Reward'
		     ,'deal trigger'=>'Order'
		     ,'deal description'=>$allowance.' if last order within 1 calendar month'
		     ,'deal terms type'=>'Order Interval'
		     ,'deal terms description'=>'last order within 1 calendar month'
		     ,'deal allowance description'=>$allowance
		     ,'deal allowance type'=>'Percentage Off'
		     ,'deal allowance target'=>'Product'
		     ,'deal allowance target key'=>''
		     ,'deal begin date'=>'2006-01-01 00:00:00'
		     ,'deal expiration date'=>date("Y-m-d 23:59:59",strtotime('now + 1 year'))
		     );
      
      $deals[]=array(
		     'deal campain name'=>''
		     ,'deal trigger'=>'Family'
		     ,'deal description'=>$allowance.' if '.$terms.' same family'
		     ,'deal terms type'=>'Family Quantity Ordered'
		     ,'deal terms description'=>'order '.$terms
		     ,'deal allowance description'=>$allowance
		     ,'deal allowance type'=>'Percentage Off'
		     ,'deal allowance target'=>'Product'
		     ,'deal allowance target key'=>''
		     ,'deal begin date'=>'2006-01-01 00:00:00'
		     ,'deal expiration date'=>date("Y-m-d 23:59:59",strtotime('now + 1 year'))
		     );	

      
      
    }elseif(preg_match('/\d+\s*or more\s*\d+\%$/i',_trim($current_promotion))){
      // print $current_promotion." *********\n";
      preg_match('/\d+\%$/i',$current_promotion,$match);
      $allowance=$match[0].' off';
      preg_match('/\d+\s*or more/i',$current_promotion,$match);
      $terms=_trim(strtolower($match[0]));

      $deals[]=array(
		     'deal campain name'=>'Gold Reward'
		     ,'deal trigger'=>'Order'
		     ,'deal description'=>$allowance.' if last order within 1 calendar month'
		     ,'deal terms type'=>'Order Interval'
		     ,'deal terms description'=>'last order within 1 calendar month'
		     ,'deal allowance description'=>$allowance
		     ,'deal allowance type'=>'Percentage Off'
		     ,'deal allowance target'=>'Product'
		     ,'deal allowance target key'=>''
		        ,'deal begin date'=>'2006-01-01 00:00:00'
		       ,'deal expiration date'=>date("Y-m-d 23:59:59",strtotime('now + 1 year'))
		       );

	$deals[]=array(
		       'deal campain name'=>''
		       ,'deal trigger'=>'Family'
		       ,'deal description'=>$allowance.' if '.$terms.' same family'
		       ,'deal terms type'=>'Family Quantity Ordered'
		       ,'deal terms description'=>'order '.$terms
		       ,'deal allowance description'=>$allowance
		       ,'deal allowance type'=>'Percentage Off'
		       ,'deal allowance target'=>'Product'
		       ,'deal allowance target key'=>''
		       ,'deal begin date'=>'2006-01-01 00:00:00'
		       ,'deal expiration date'=>date("Y-m-d 23:59:59",strtotime('now + 1 year'))
		       
		       );	
	

    }elseif(preg_match('/^buy \d+ get \d+ free$/i',_trim($current_promotion))){
      // print $current_promotion." *********\n";
      preg_match('/buy \d+/i',$current_promotion,$match);
      $buy=_trim(preg_replace('/[^\d]/','',$match[0]));

      preg_match('/get \d+/i',$current_promotion,$match);
      $get=_trim(preg_replace('/[^\d]/','',$match[0]));

      $deals[]=array(
		       'deal campain name'=>'BOGOF'
		       ,'deal trigger'=>'Product'
		       ,'deal description'=>'buy '.$buy.' get '.$get.' free'
		       ,'deal terms type'=>'Product Quantity Ordered'
		       ,'deal terms description'=>'foreach '.$buy
		       ,'deal allowance description'=>$get.' free'
		       ,'deal allowance type'=>'Get Free'
		       ,'deal allowance target'=>'Product'
		       ,'deal allowance target key'=>''
		       ,'deal begin date'=>'2006-01-01 00:00:00'
		       ,'deal expiration date'=>date("Y-m-d 23:59:59",strtotime('now + 1 year'))
		     );	


    }else
       $deals=array();
    

    if($units=='' OR $units<=0)
      $units=1;




 //    if(preg_match('/wsl-535/i',$code)){
//       print_r($cols);
//       exit;

//     }



    if($code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St' 
       or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
       or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
       ){
      print "Skipping $code\n";
      
    }else{

      
      if(!is_numeric($price) or $price<=0){
	print "Price Zero  $code \n";
	$price=0;
      }


      if($code=='Tib-20')
	$supplier_cost=0.2;

      if($code=='L&P-ST'){
	$supplier_cost=36.30;
	$price=86.40;
      }

    if(!is_numeric($supplier_cost)  or $supplier_cost<=0 ){
      //   print_r($cols);
      print "$code   assumind supplier cost of 40%  \n";
      $supplier_cost=0.4*$price/$units;
      
    }



    $product=new Product('code',$code);
    // print "** ".$product->data['Product Code']."\n";
    if(!$product->id){
      if($units=='')
	$units=1;
      
      if(is_numeric($rrp))
	$rrp=sprintf("%.2f",$rrp*$units);
      else
	$rrp='';
      
      
    //   $_f=preg_replace('/s$/i','',$current_fam_name);
//       //print "$_f\n";
//       $special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
//       $special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
      $fam_special_char=$current_fam_name;
      $special_char=$description;

      if(is_numeric($w)){
	$w=$w*$units;
	if($w<0.001 and $w>0)
	  $_w=0.001;
	else
	  $_w=sprintf("%.3f",$w);
      }else
	$_w='';
      

      if($current_fam_code=='LavF / PF')
	$current_fam_code='PF';
      if($current_fam_code=='MIST / AM')
	$current_fam_code='MIST';
       if($current_fam_code=='LBI / IS')
	$current_fam_code='LBI';

       if($current_fam_code=='Leb - Lebp')
	 $current_fam_code='Leb';
       if($current_fam_code=='Bot/Pack/Wb')
	 $current_fam_code='Bot';
       


      $data=array(
		  'product sales state'=>'For sale',
		  'product type'=>'Normal',
		  'product record type'=>'Normal',
		  'Product Web Configuration'=>'Online Auto',

		  'product code'=>$code,
		  'product price'=>sprintf("%.2f",$price),
		  'product rrp'=>$rrp,
		  'product units per case'=>$units,
		  'product name'=>$description,
		  'product family code'=>$current_fam_code,
		  'product family name'=>$current_fam_name,
		  'product main department name'=>$department_name,
		  'product main department code'=>$department_code,
		  'product special characteristic'=>$special_char,
		  'product family special characteristic'=>$fam_special_char,
		  'product net weight'=>$_w,
		  'product gross weight'=>$_w,
		  'deals'=>$deals
		    );
      //     print_r($cols);
      //print_r($data);
      
       if(preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($code))){

	 $dept_key=$dept_promo_key;
	 $data['Product Family Key']=$fam_promo_key;
	 $data['Product main Department Key']=$dept_promo_key;


       }

       	$product=new Product('create',$data);

	$scode=_trim($cols[$ymap['supplier_product_code']]);
	$supplier_code=$cols[$ymap['supplier_code']];
       
	if(preg_match('/^SG\-|^info\-/i',$code))
	  $supplier_code='AW';
	if($supplier_code=='AW')
	  $scode=$code;



	if($scode=='SSK-452A' and $supplier_code=='Smen')
	  $scode='SSK-452A bis';


	if(preg_match('/^(StoneM|Smen)$/i',$supplier_code)){
	  $supplier_code='StoneM';
	}


		$the_supplier_data=array(
		      'name'=>$supplier_code,
		      'code'=>$supplier_code,
		      );

	// Suppplier data
	if(preg_match('/Ackerman|Ackerrman|Akerman/i',$supplier_code)){
	  $supplier_code='Ackerman';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Ackerman Group',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Unit 15/16'
							 ,'address2'=>'Hickman Avenue'
							 ,'address3'=>''
							 ,'town'=>'London'
							 ,'town_d1'=>''
							 ,'town_d2'=>'Chingford'
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'E4 9JG'
							 ),
				   'email'=>'office@ackerman.co.uk'
				   ,'telephone'=>'020 8527 6439'
				   );
	}
if(preg_match('/^puck$/i',$supplier_code)){
	  $supplier_code='Puck';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Puckator',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Lowman Works'
							 ,'address2'=>''
							 ,'address3'=>''
							 ,'town'=>'East Taphouse'
							 ,'town_d1'=>''
							 ,'town_d2'=>'Near Liskeard'
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'PL14 4NQ'
							 ),
				   'email'=>'accounts@puckator.co.uk'
				   ,'telephone'=>'1579321550'
				   ,'fax'=>'1579321520'
				   );
	}
 
 if(preg_match('/^decent gem$/i',$supplier_code)){
   $supplier_code='DecGem';
   $the_supplier_data=array(
			    'Supplier Name'=>'Decent Gemstone Exports',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"Besides Balaji's Mandir"
						  ,'address2'=>'Near Rajputwad'
						  ,'address3'=>''
						  ,'town'=>'Khambhat'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'India'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'388620'
						  ),
			    'email'=>'decentstone@sancharnet.in'
			    ,'telephone'=>'00917926578604'
			    ,'fax'=>'00917926584997'
			    );
 }
  if(preg_match('/^kiran$/i',$supplier_code)){

   $the_supplier_data=array(
			    'Supplier Name'=>'Kiran Agencies',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"4D Garstin Place"
						  ,'address2'=>''
						  ,'address3'=>''
						  ,'town'=>'Kolkata'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'India'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'700001'
						  )
			    ,'telephone'=>'919830020595'

			    );
 }
 

if(preg_match('/^watkins$/i',$supplier_code)){

   $the_supplier_data=array(
			    'Supplier Name'=>'Watkins Soap Co Ltd',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"Reed Willos Trading Est"
						  ,'address2'=>'Finborough Rd'
						  ,'address3'=>''
						  ,'town'=>'Stowmarket'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'UK'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'IP14 3BU'
						  )

			    ,'telephone'=>'01142501012'
			    ,'fax'=>'01142501006'
			    );
 }



if(preg_match('/^decree$/i',$supplier_code)){

   $the_supplier_data=array(
			    'Supplier Name'=>'Decree Thermo Limited',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"300 Shalemoor"
						  ,'address2'=>'Finborough Rd'
						  ,'address3'=>''
						  ,'town'=>'Sheffield'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'UK'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'S3 8AL'
						  )
			    ,'contact_name'=>'Zoie'
			    ,'email'=>'Watkins@soapfactory.fsnet.co.uk'
			    ,'telephone'=>'01449614445'
			    ,'fax'=>'014497111643'
			    );
 }

if(preg_match('/^cbs$/i',$supplier_code)){

   $the_supplier_data=array(
			    'Supplier Name'=>'Carrierbagshop',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"Unit C18/21"
						  ,'address2'=>'Hastingwood trading Estate'
						  ,'address3'=>'35 Harbet Road'
						  ,'town'=>'London'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'UK'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'N18 3HU'
						  )
			    ,'contact_name'=>'Neil'
			    ,'email'=>'info@carrierbagshop.co.uk'
			    ,'telephone'=>'08712300980'
			    ,'fax'=>'08712300981'
			    );
 }


if(preg_match('/^giftw$/i',$supplier_code)){

   $the_supplier_data=array(
			    'Supplier Name'=>'Giftworks Ltd',
			    'Supplier Code'=>$supplier_code,
			    'address_data'=>array(
						  'type'=>'3line'
						  ,'address1'=>"Unit 14"
						  ,'address2'=>'Cheddar Bussiness Park'
						  ,'address3'=>'Wedmore Road'
						  ,'town'=>'Cheddar'
						  ,'town_d1'=>''
						  ,'town_d2'=>''
						  ,'country'=>'UK'
						  ,'country_d1'=>''
						  ,'country_d2'=>''
						  ,'default_country_id'=>$myconf['country_id']
						  ,'postcode'=>'BS27 3EB'
						  )
			    ,'email'=>'info@giftworks.tv'
			    ,'telephone'=>'441934742777'
			    ,'fax'=>'441934740033'
			    ,'www.giftworks.tv'
			    );
 }


 if(preg_match('/^Sheikh$/i',$supplier_code)){
	  $supplier_code='Sheikh';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Sheikh Enterprises',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"Eidgah Road"
							 ,'address2'=>'Opp. Islamia Inter College'
							 ,'address3'=>''
							 ,'town'=>'Saharanpur'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'India'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'247001'
							 )

				   );
	}
if(preg_match('/^Gopal$/i',$supplier_code)){
	  $supplier_code='Gopal';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Gopal HQ Limited',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"240 Okhla Industrial Estate"
							 ,'address2'=>'Phase III'
							 ,'address3'=>''
							 ,'town'=>'New Delhi'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'India'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'110020'
							 )
				   ,'telephone'=>'00911126320185'
				   );
	}

  if(preg_match('/^CraftS$/i',$supplier_code)){
	  $supplier_code='CraftS';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Craftstones Europe Ltd',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"52/54 Homethorphe Avenue"
							 ,'address2'=>'Homethorphe Ind. Estate'
							 ,'address3'=>''
							 ,'town'=>'Redhill'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'RH1 2NL'
							 ),
				   'contact_name'=>'Jose'

				   ,'telephone'=>'01737767363'
				   ,'fax'=>'01737768627'
				   );
	}

 if(preg_match('/^Simpson$/i',$supplier_code)){
	  $supplier_code='CraftS';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Simpson Packaging',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"Unit 1"
							 ,'address2'=>'Shaw Cross Business Park'
							 ,'address3'=>''
							 ,'town'=>'Dewsbury'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'WF12 7RF'
							 ),

				   'email'=>'sales@simpson-packaging.co.uk'
				   ,'telephone'=>'01924869010'
				   ,'fax'=>'01924439252'
				   ,'www'=>'wwww.simpson-packaging.co.uk'
				   );
	}



 if(preg_match('/^amanis$/i',$supplier_code)){
	  $supplier_code='AmAnis';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Amanis',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"Unit 6"
							 ,'address2'=>'Bowlimng Court Industrial Estate'
							 ,'address3'=>'Mary Street'
							 ,'town'=>'Bradford'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'BD4 8TT'
							 ),

				   'email'=>'saltlamps@aol.com'
				   ,'telephone'=>'4401274394100'
				   ,'fax'=>'4401274743243'
				   ,'www'=>'www.saltlamps-r-us.com'
				   );
	}


if(preg_match('/^amanis$/i',$supplier_code)){
	  $supplier_code='AmAnis';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Amanis',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"Unit 6"
							 ,'address2'=>'Bowlimng Court Industrial Estate'
							 ,'address3'=>'Mary Street'
							 ,'town'=>'Bradford'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'BD4 8TT'
							 ),

				   'email'=>'saltlamps@aol.com'
				   ,'telephone'=>'4401274394100'
				   ,'fax'=>'4401274743243'
				   ,'www'=>'www.saltlamps-r-us.com'
				   );
	}


if(preg_match('/^Wenzels$/i',$supplier_code)){
	  $supplier_code='Wenzels';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Richard Wenzel GMBH & CO KG',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>"Benzstraße 5"
							 ,'address2'=>''
							 ,'address3'=>''
							 ,'town'=>'Aschaffenburg'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'Germany'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'63741'
							 ),


				   'telephone'=>'49602134690'
				   ,'fax'=>'496021346940'

				   );
	}
	

	if(preg_match('/^AW$/i',$supplier_code)){
	  $supplier_code='AW';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Ancient Wisdom Marketing',
				   'Supplier Code'=>$supplier_code,
				   'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Block B'
							 ,'address2'=>'Parkwood Business Park'
							 ,'address3'=>'Parkwood Road'
							 ,'town'=>'Sheffield'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'S3 8AL'
							 ),
				   'email'=>'mail@ancientwisdom.biz'
				   ,'telephone'=>'44 (0)114 2729165'

				   );
	}


	if(preg_match('/^EB$/i',$supplier_code)){
	  $supplier_code='EB';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Elements Bodycare Ltd'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Unit 2'
							 ,'address2'=>'Carbrook Bussiness Park'
							 ,'address3'=>'Dunlop Street'
							 ,'town'=>'Sheffield'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'S9 2HR'
							 )

				   ,'telephone'=>'011422434000'
				   ,'www'=>'www.elements-bodycare.co.uk'
				   ,'email'=>'info@elements-bodycare.co.uk'

				   );
	}

	if(preg_match('/^Paradise$/i',$supplier_code)){
	  $supplier_code='Paradise';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Paradise Music Ltd'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'PO BOX 998'
							 ,'address2'=>'Carbrook Bussiness Park'
							 ,'address3'=>'Dunlop Street'
							 ,'town'=>'Tring'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'HP23 4ZJ'
							 )

				   ,'telephone'=>'01296668193'


				   );
	}
	if(preg_match('/^MCC$/i',$supplier_code)){
	  $supplier_code='MCC';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Manchester Candle Company'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'The Manchester Group'
							 ,'address2'=>'Kenwood Road'
							 ,'address3'=>''
							 ,'town'=>'North Reddish'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'SK5 6PH'
							 )
				   ,'contact_name'=>'Brian'
				   ,'telephone'=>'01614320811'
				   ,'fax'=>'01614310328'
				   ,'www'=>'manchestercandle.com'

				   );
	}
	if(preg_match('/^Aquavision$/i',$supplier_code)){
	  $supplier_code='Aquavision';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Aquavision Music Ltd'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'PO BOX 2796'
							 ,'address2'=>''
							 ,'address3'=>''
							 ,'town'=>'Iver'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'SL0 9ZR'
							 )

				   ,'telephone'=>'01753653188'
				   ,'fax'=>'01753655059'
				   ,'www'=>'www.aquavisionwholesale.co.uk'
				   ,'email'=>'info@aquavisionwholesale.co.uk'
				   );
	}

	if(preg_match('/^CXD$/i',$supplier_code)){
	  $supplier_code='CXD';
	  $the_supplier_data=array(
				   'Supplier Name'=>'CXD Designs Ltd'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Unit 2'
							 ,'address2'=>'Imperial Park'
							 ,'address3'=>'Towerfiald Road'
							 ,'town'=>'Shoeburyness'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>'Essex'
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'SS3 9QT'
							 )

				   ,'telephone'=>'01702292028'
				   ,'fax'=>'01702298486'

				   );
	}
	if(preg_match('/^(AWR|costa)$/i',$supplier_code)){
	  $supplier_code='AWR';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Costa Imports'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Nave 8'
							 ,'address2'=>'Polígono Ind. Alhaurín de la Torre Fase 1'
							 ,'address3'=>'Paseo de la Hispanidad'
							 ,'town'=>'Málaga'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'Spain'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'29130'
							 )
				   ,'contact_name'=>'Carlos'
				   ,'email'=>'carlos@aw-regalos.com'
				   ,'telephone'=>'(+34) 952 417 609'
				   );
	}

	if(preg_match('/^(salco)$/i',$supplier_code)){
	  $supplier_code='Salco';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Salco Group'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Salco House'
							 ,'address2'=>'5 Central Road'
							 ,'address3'=>''
							 ,'town'=>'Harlow'
							 ,'town_d1'=>''
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>'Essex'
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'CM20 2ST'
							 )
				   //				   ,'contact_name'=>'Carlos'
				   ,'email'=>'alco@salcogroup.com'
				   ,'telephone'=>'01279 439991'
				   );
	}
	if(preg_match('/^(apac)$/i',$supplier_code)){
	  $supplier_code='Salco';
	  $the_supplier_data=array(
				   'Supplier Name'=>'APAC Packaging Ltd'
				   ,'Supplier Code'=>$supplier_code
				   ,'address_data'=>array(
							 'type'=>'3line'
							 ,'address1'=>'Loughborough Road'
							 ,'address2'=>''
							 ,'address3'=>''
							 ,'town'=>'Leicester'
							 ,'town_d1'=>'Rothley'
							 ,'town_d2'=>''
							 ,'country'=>'UK'
							 ,'country_d1'=>''
							 ,'country_d2'=>''
							 ,'default_country_id'=>$myconf['country_id']
							 ,'postcode'=>'LE7 7NL'
							 )
				   //				   ,'contact_name'=>'Carlos'
				   ,'email'=>''
				   ,'telephone'=>'0116 230 2555'
				   ,'www'=>'www.apacpackaging.com'
				   ,'fax'=>'0116 230 3555'
				   );
	}
	if(preg_match('/^(andy.*?)$/i',$supplier_code)){
	  $supplier_code='Andy';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Andy'
				   ,'Supplier Code'=>$supplier_code
				   );
	}


	if($supplier_code=='' or $supplier_code=='0'){
	  $supplier_code='Unknown';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Unknown Supplier'
				   ,'Supplier Code'=>$supplier_code
				   );
	}
	$supplier=new Supplier('code',$supplier_code);
	if(!$supplier->id){
	  print "neew: $supplier_code\n";
	  print_r($the_supplier_data);
	  $supplier=new Supplier('new',$the_supplier_data);
	}




	$scode=_trim($scode);
	$scode=preg_replace('/^\"\s*/','',$scode);
	$scode=preg_replace('/\s*\"$/','',$scode);
	


	if(preg_match('/\d+ or more|0.10000007|8.0600048828125|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$scode))
	  $scode='';
	if(preg_match('/^(\?|new|\d|0.25|0.5|0.8|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$scode))
	  $scode='';

	

	if($scode=='same')
	  $scode=$code;
	if($scode=='' or $scode=='0')
	  $scode='?'.$code;
	$sp_data=array(
		       'supplier key'=>$supplier->id,
		       'supplier code'=>$supplier->data['Supplier Code'],
		       'supplier name'=>$supplier->data['Supplier Name'],
		       'Supplier Product Code'=>$scode,
		       'Supplier Product Cost'=>sprintf("%.4f",$supplier_cost),
		       'Supplier Product Name'=>$description,
		       'Supplier Product Description'=>$description
		       );
	$new_supplier_product=false;
	$supplier_product=new SupplierProduct('supplier-code',$sp_data);
	if(!$supplier_product->id){
	  $new_supplier_product=true;
	  $supplier_product=new SupplierProduct('new',$sp_data);
	}
	$part_data=array(
			 'Part Most Recent'=>'Yes',
			 'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
			 'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
			 'Part XHTML Description'=>preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description')),
			 'part valid from'=>date('Y-m-d H:i:s'),
			 'part valid to'=>date('Y-m-d H:i:s'),
			 'Part Gross Weight'=>$w
			 );
	$part=new Part('new',$part_data);
	//	print_r($part->data);
	
	$rules[]=array('Part Sku'=>$part->data['Part SKU'],
		       'Supplier Product Units Per Part'=>$units
		       ,'supplier product part most recent'=>'Yes'
		       ,'supplier product part valid from'=>date('Y-m-d H:i:s')
		       ,'supplier product part valid to'=>date('Y-m-d H:i:s')
		       ,'factor supplier product'=>1
		       );
	$supplier_product->new_part_list('',$rules);
	
	$part_list[]=array(
			   'Product ID'=>$product->get('Product ID'),
			   'Part SKU'=>$part->get('Part SKU'),
			   'Product Part Id'=>1,
			   'requiered'=>'Yes',
			   'Parts Per Product'=>1,
			   'Product Part Type'=>'Simple Pick'
			   );
	$product->new_part_list('',$part_list);
	$supplier_product->load('used in');
	$product->load('parts');
	$part->load('used in');
	$part->load('supplied by');
    
 }
    
    }
  }else{

    $new_family=true;
    
    // print "Col $column\n";
    //print_r($cols);
    if($cols[$ymap['code']]!='' and $cols[$ymap['description']]!=''  and $cols[$ymap['code']]!='SHOP-Fit' and $cols[$ymap['code']]!='ISH-94' and $cols[$ymap['code']]!='OB-108' and !preg_match('/^DB-/',$cols[$ymap['code']])  and !preg_match('/^pack-/i',$cols[$ymap['code']])  ){
      $fam_code=$cols[$ymap['code']];
      $fam_name=_trim( mb_convert_encoding($cols[$ymap['description']], "UTF-8", "ISO-8859-1,UTF-8"));
      $fam_position=$column;

      
    }
    
    if(preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free/i',_trim($cols[$ymap['description']]))){
      

      $promotion=$cols[$ymap['description']];

      $promotion=preg_replace('/^\s*order\s*/i','',$promotion);
      $promotion=preg_replace('/discount\s*$/i','',$promotion);
      $promotion=preg_replace('/\s*off\s*$/i','',$promotion);

      $promotion=_trim($promotion);
      $promotion_position=$column;
      // print "*********** Promotion $promotion $promotion_position \n";
    }
    if($cols[$ymap['code']]=='' and $cols[$ymap['description']]==''){
      $blank_position=$column;
    }

    if($cols[$ymap['description']]!='' and preg_match('/Sub Total/i',$cols[$ymap['']])){
      $department_name=$cols[$ymap['description']];
      $department_position=$column;


        $department_code=_trim($department_name);
      if($department_code=='Ancient Wisdom Home Fragrance'){
	$department_code='Home';
	$department_name='AW Home Fragrance';
      }
      if($department_code=='Ancient Wisdom Aromatherapy Dept.'){
	$department_code='Aroma';
	$department_name='AW Aromatherapy Department';
      }if($department_code=='Bathroom Heaven')
	 $department_code='Bath';
      if($department_code=='Exotic Incense Dept Order'){
	$department_code='Incense';
	$department_name='Exotic Incense Department';
      }if($department_code=='While Stocks Last Order'){
	$department_code='WSL';
	$department_name='While Stocks Last';
      }if($department_code=='Collectables Department'){
	$department_code='Collec';
      }
      if($department_code=='Crystal Department'){
	$department_code='Crystal';
      }
   if($department_code=='Cards, Posters & Gift Wrap'){
	$department_code='Paper';
      }
   if($department_code=='Retail Display Stands'){
	$department_code='RDS';
      }
   if($department_code=='Stoneware'){
	$department_code='Stone';
	$department_name='Stoneware Department';

      }
   if($department_code=='Jewellery Quarter'){
	$department_code='Jewells';
      }
   if($department_code=='Relaxing Music Collection'){
	$department_code='Music';
      }
 if($department_code=='BagsBags.Biz'){
	$department_code='Bags';
      }
 if($department_code=='Christmas Time'){
	$department_code='Xmas';
      }

if($department_code=='CraftsCrafts.biz'){
	$department_code='Crafts';
      }
if($department_code=='Florist-Supplies.biz'){
	$department_code='Flor';
      }
if($department_code=='Soft Furnishings & Textiles'){
	$department_code='Textil';
      }
if($department_code=='Woodware Dept'){
  $department_code='Wood';
  $department_name='Woodware Department';

      }




    }
    
    $posible_fam_code=$cols[$ymap['code']];
    $posible_fam_name=$cols[$ymap['description']];
  }
  

  
  $column++;
  }






?>