<?
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2002.xls';
$csv_file='tmp.csv';
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;



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


  $price=$cols[7];
  $supplier_code=_trim($cols[21]);
  $part_code=_trim($cols[22]);
  $supplier_cost=$cols[25];
  


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
  
  
  if(preg_match('/^credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
    $is_product=false;


  
  if($is_product){
    
    print "$code\r";

    
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
    
    $units=$cols[5];
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
      
    }else{

      
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



    $product=new Product('code',$code);
    // print "** ".$product->data['Product Code']."\n";
    if(!$product->id){
      if($units=='')
	$units=1;
      
      if(is_numeric($rrp))
	$rrp=sprintf("%.2f",$rrp*$units);
      else
	$rrp='';
      
      
      $_f=preg_replace('/s$/i','',$current_fam_name);
      //print "$_f\n";
      $special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
      $special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
	    
	

      if(is_numeric($w)){
	$w=$w*$units;
	if($w<0.001 and $w>0)
	  $_w=0.001;
	else
	  $_w=sprintf("%.3f",$w);
      }else
	$_w='';
      
      $data=array(
		  'product sale state'=>'For sale',
		  'product code'=>$code,
		  'product price'=>sprintf("%.2f",$price),
		  'product rrp'=>$rrp,
		  'product units per case'=>$units,
		  'product name'=>$description,
		  'product family code'=>$current_fam_code,
		  'product family name'=>$current_fam_name,
		  'product main department name'=>$department_name,
		  'product main department code'=>$department_name,
		  'product special characteristic'=>$special_char,
		  'product net weight'=>$_w,
		  'product gross weight'=>$_w,
		  'deals'=>$deals
		    );
      //     print_r($cols);
      //print_r($data);
      
       	$product=new Product('create',$data);

	$scode=_trim($cols[20]);
	$supplier_code=$cols[21];
       
	if(preg_match('/^SG\-|^info\-/i',$code))
	  $supplier_code='AW';
	if($supplier_code=='AW')
	  $scode=$code;

	$the_supplier_data=array(
		      'name'=>$supplier_code,
		      'code'=>$supplier_code,
		      );

	if($scode=='SSK-452A' and $supplier_code=='Smen')
	  $scode='SSK-452A bis';


	if(preg_match('/^(StoneM|Smen)$/i',$supplier_code)){
	  $supplier_code='StoneM';
	}

	// Suppplier data
	if(preg_match('/Ackerman|Ackerrman|Akerman/i',$supplier_code)){
	  $supplier_code='Ackerman';
	  $the_supplier_data=array(
				   'name'=>'Ackerman Group',
				   'code'=>$supplier_code,
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
				   'name'=>'Puckator',
				   'code'=>$supplier_code,
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
			    'name'=>'Decent Gemstone Exports',
			    'code'=>$supplier_code,
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
			    'name'=>'Kiran Agencies',
			    'code'=>$supplier_code,
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
			    'name'=>'Watkins Soap Co Ltd',
			    'code'=>$supplier_code,
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
			    'name'=>'Decree Thermo Limited',
			    'code'=>$supplier_code,
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
			    'name'=>'Carrierbagshop',
			    'code'=>$supplier_code,
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
			    'name'=>'Giftworks Ltd',
			    'code'=>$supplier_code,
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
				   'name'=>'Sheikh Enterprises',
				   'code'=>$supplier_code,
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
				   'name'=>'Gopal Corporation Limited',
				   'code'=>$supplier_code,
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
				   'name'=>'Craftstones Europe Ltd',
				   'code'=>$supplier_code,
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
				   'name'=>'Simpson Packaging',
				   'code'=>$supplier_code,
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
				   'name'=>'Amanis',
				   'code'=>$supplier_code,
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
				   'name'=>'Amanis',
				   'code'=>$supplier_code,
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
				   'name'=>'Richard Wenzel GMBH & CO KG',
				   'code'=>$supplier_code,
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
				   'name'=>'Ancient Wisdom Marketing',
				   'code'=>$supplier_code,
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
				   'name'=>'Elements Bodycare Ltd'
				   ,'code'=>$supplier_code
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
				   'name'=>'Paradise Music Ltd'
				   ,'code'=>$supplier_code
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
				   'name'=>'Manchester Candle Company'
				   ,'code'=>$supplier_code
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
				   'name'=>'Aquavision Music Ltd'
				   ,'code'=>$supplier_code
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
				   'name'=>'CXD Designs Ltd'
				   ,'code'=>$supplier_code
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
				   'name'=>'Costa Imports'
				   ,'code'=>$supplier_code
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
				   'name'=>'Salco Group'
				   ,'code'=>$supplier_code
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
				   'name'=>'APAC Packaging Ltd'
				   ,'code'=>$supplier_code
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
				   'name'=>'Andy'
				   ,'code'=>$supplier_code
				   );
	}


	if($supplier_code=='' or $supplier_code=='0'){
	  $supplier_code='Unknown';
	  $the_supplier_data=array(
				   'name'=>'Unknown Supplier'
				   ,'code'=>$supplier_code
				   );
	}
	$supplier=new Supplier('code',$supplier_code);
	if(!$supplier->id){
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
		       'supplier product supplier key'=>$supplier->id,
		       'supplier product supplier code'=>$supplier->data['Supplier Code'],
		       'supplier product supplier name'=>$supplier->data['Supplier Name'],
		       'supplier product code'=>$scode,
		       'supplier product cost'=>sprintf("%.4f",$supplier_cost),
		       'supplier product name'=>$description,
		       'supplier product description'=>$description
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
    if($cols[3]!='' and $cols[6]!=''){
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

    if($cols[6]!='' and preg_match('/Sub Total/i',$cols[11])){
      $department_name=$cols[6];
      $department_position=$column;
    }
    
    $posible_fam_code=$cols[3];
    $posible_fam_name=$cols[6];
  }
  

  
  $column++;
  }






?>