<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
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

 // 	$data=array(
//  		    'code'=>'L&P-ST',
//  		    'price'=>76.80,
//  		    'rrp'=>'',
//  		    'units per case'=>1,
//  		    'name'=>'Starter - Mixed selection of 10 boxes (of 6) plus one bonus box. 66 jars in total'
//  		    );
//  	$product=new Product('create',$data);
 //  	$data=array(
//   		    'code'=>'Bag-03mx',
//   		    'price'=>47.25,
//   		    'rrp'=>'',
//   		    'units per case'=>1,
//   		    'name'=>'Velvet Pouch - 150 Mix'
//   		    );
//   	$product=new Product('create',$data);


 //  	$data=array(
//    		    'code'=>'LCC-MIX1',
//    		    'price'=>11.76,
//    		    'rrp'=>'',
//    		    'units per case'=>12,
//    		    'name'=>'12x Mixed Crystal Cubes - discounted price'
//    		    );
//    	$product=new Product('create',$data);
 
// exit;
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
  
  //print_r($cols);
  $is_product=true;

  $code=$cols[3];
  $price=$cols[4];



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





  if($is_product){
    

    print "$column\n";
    

    //  if( $fam_code!='' and $fam_name!='' and ($fam_position-$column)<2 ){
      $current_fam_name=$fam_name;
      $current_fam_code=$fam_code;
 //      $new_family=true;
      
//     }
    
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
      
      
      //  print "----------- $current_promotion\n";
	preg_match('/^\d+\% off/i',$current_promotion,$match);
	$allowance=$match[0];
	preg_match('/off.*more/',$current_promotion,$match);
	$terms=preg_replace('/^off\s*/i','',$match[0]);

	$deals[]=array(
		       'deal campain name'=>'Gold Reward'
		       ,'deal trigger'=>'Order'
		       ,'deal description'=>$allowance.' if last order within 30 days'
		       ,'deal terms type'=>'Order Interval'
		       ,'deal terms description'=>'last order within 30 days'
		       ,'deal allowance description'=>$allowance
		       ,'deal allowance type'=>'Percentage Off'
		       ,'deal allowance target'=>'Product'
		       ,'deal allowance target key'=>'Self'
		       );

	$deals[]=array(
		       'deal campain name'=>''
		       ,'deal trigger'=>'Product'
		       ,'deal description'=>$allowance.' if '.$terms.' same family'
		       ,'deal terms type'=>'Family Quantity Ordered'
		       ,'deal terms description'=>'order '.$terms
		       ,'deal allowance description'=>$allowance
		       ,'deal allowance type'=>'Percentage Off'
		       ,'deal allowance target'=>'Product'
		       ,'deal allowance target key'=>'Self'
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
		       ,'deal description'=>$allowance.' if last order within 30 days'
		     ,'deal terms type'=>'Order Interval'
		     ,'deal terms description'=>'last order within 30 days'
		     ,'deal allowance description'=>$allowance
		     ,'deal allowance type'=>'Percentage Off'
		     ,'deal allowance target'=>'Product'
		     ,'deal allowance target key'=>'Self'
		       );

	$deals[]=array(
		       'deal campain name'=>''
		       ,'deal trigger'=>'Product'
		       ,'deal description'=>$allowance.' if '.$terms.' same family'
		       ,'deal terms type'=>'Family Quantity Ordered'
		       ,'deal terms description'=>'order '.$terms
		       ,'deal allowance description'=>$allowance
		       ,'deal allowance type'=>'Percentage Off'
		       ,'deal allowance target'=>'Product'
		       ,'deal allowance target key'=>'Self'
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
		       ,'deal allowance target key'=>'Self'
		     );	


  }else
       $deals=array();
    


    //    $fam_code='';
    // $fam_name='';
    //$fam_position=0;
    

    $units=$cols[5];
    $description=$cols[6];
    $rrp=$cols[16];
    if($price>=0  ){
      

   //    print "$price $code\n";

      

      $product=new Product('code',$code);
      if(!$product->id){
	$data=array(
		    'code'=>$code,
		    'price'=>$price,
		    'rrp'=>$rrp,
		    'units per case'=>$units,
		    'name'=>$description,
		    'family code'=>$current_fam_code,
		    'family name'=>$current_fam_name,
		    'department code'=>$department_name,
		    'deals'=>$deals
		    

		    );
	print_r($data);
	//  	if($code=='BW-01')
	//  exit;
	//	exit;
       	$product=new Product('create',$data);
	print_r($product->data);
	exit;
      }else{
	print $count."\r";
	//	print_r($product->data);
	//print $product->get('product code')."\n";
      }
	
      //print "C:$code D:$units x $description P:$price\n";
    }
    $count++;
    $product_position=$column;
  }else{

    $new_family=true;

    // print "Col $column\n";
    //print_r($cols);
    if($cols[3]!='' and $cols[6]!=''){
      $fam_code=$cols[3];
      $fam_name=$cols[6];
      $fam_position=$column;

      
    }
    
    if(preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free/i',_trim($cols[6]))){
      

      $promotion=$cols[6];

      $promotion=preg_replace('/^\s*order\s*/i','',$promotion);
      $promotion=preg_replace('/discount\s*$/i','',$promotion);
      $promotion=preg_replace('/\s*off\s*$/i','',$promotion);

      $promotion=_trim($promotion);
      $promotion_position=$column;
        print "*********** Promotion $promotion $promotion_position \n";
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