<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2002France.xls';
$csv_file='tmp.csv';
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=3;
$dept_no_dept=new Department('code_store','ND',$store_key);
if(!$dept_no_dept->id){
  $dept_data=array(
		   'code'=>'FR.ND',
		   'name'=>'Products Without Department',
		   'store_key'=>$store_key
		   );
  $dept_no_dept=new Department('create',$dept_data);
  $dept_no_dept_key=$dept_no_dept->id;
}
$dept_promo=new Department('code_store','Promo',$store_key);
if(!$dept_promo->id){
  $dept_data=array(
		   'code'=>'FR.Promo',
		   'name'=>'Promotional Items',
		   'store_key'=>$store_key
		   );
  $dept_promo=new Department('create',$dept_data);
  
}


$dept_no_dept_key=$dept_no_dept->id;
$dept_promo_key=$dept_promo->id;

$fam_no_fam=new Family('code_store','ND',$store_key);
if(!$fam_no_fam->id){
  $fam_data=array(
		   'Product Family Code'=>'PNF_FR',
		   'Product Family Name'=>'Products Without Family',
		   'Product Family Main Department Key'=>$dept_no_dept_key
		   );
  $fam_no_fam=new Family('create',$fam_data);
  $fam_no_fam_key=$fam_no_fam->id;
  $dept_no_dept->load('products_info');
}
$fam_promo=new Family('code_store','Promo',$store_key);
if(!$fam_promo->id){
  $fam_data=array(
		   'code'=>'Promo_FR',
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
$department_code='';

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
  
  

  if(!preg_match('/^done$/i',$cols[0]))
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


    //  print "$code $current_fam_name $current_fam_code \n"; 

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

    if(!is_numeric($supplier_cost)  or $supplier_cost<=0 ){
      //   print_r($cols);
      print "$code   assumind supplier cost of 40%  \n";
      $supplier_cost=0.4*$price/$units;
      
    }


    $uk_product=new Product('code_store',$code,1);
    $product=new Product('code_store',$code,3);
    // print "** ".$product->data['Product Code']."\n";
    if(!$product->id ){
      

      
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
      



      $data=array(

		  'product code'=>$code,
		  'product store key'=>3,
		  'product currency'=>'EUR',
		  'product locale'=>'fr_FR',
		  
		  'product sales state'=>'For Sale',
		  'product type'=>'Normal',
		  'product record type'=>'New',
		  'product web state'=>'Online Auto',

		  'product price'=>sprintf("%.2f",$price),
		  'product rrp'=>$rrp,
		  'product units per case'=>$units,
		  'product name'=>$description,
		  'product family code'=>$current_fam_code,
		  'product family name'=>$current_fam_name,
		  'product main department name'=>$department_name,
		  'product main department code'=>'FR.'.ucfirst($department_code),
		  'product special characteristic'=>$special_char,
		  'product family special characteristic'=>$fam_special_char,
		  'product net weight'=>$_w,
		  'product gross weight'=>$_w,
		  'deals'=>$deals
		    );
      //     print_r($cols);

      
      $parts=$uk_product->get('Parts SKU');
     


      $product=new Product('create',$data);

// 	$scode=_trim($cols[20]);
// 	$supplier_code=$cols[21];
       
// 	if(preg_match('/^SG\-|^info\-/i',$code))
// 	  $supplier_code='AW';
// 	if($supplier_code=='AW')
// 	  $scode=$code;

// 	$the_supplier_data=array(
// 		      'name'=>$supplier_code,
// 		      'code'=>$supplier_code,
// 		      );

// 	$supplier=new Supplier('code',$supplier_code);
// 	if(!$supplier->id){
// 	  exit("supplier not found")
// 	}
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
	//exit;
      }
 }
    
    }
  }else{
    
    $new_family=true;
    
    // print "Col $column\n";
    //print_r($cols);
    if(   preg_match('/donef/i',$cols[0])      )   {
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