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


while(($cols = fgetcsv($handle_csv))!== false){
  $read=true;
  $code=$cols[3];
  if($code=='FO-A1'){
    $products=true;
  }

  if($code=='Credit'){
    $products=false;
    break;
  }
  $price=$cols[7];
  
 


  if($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
    $read=false;
 if(preg_match('/^(ob\-108|ob\-156|ish\-94|rds\-47)/i',$code))
    $read=false;

 // if(preg_match('/-st$/i',$code) and $price=='')
//     $read=false;
 if(preg_match('/^staf-set/i',$code) and $price=='')
   $read=false;
 if(preg_match('/^hook-/i',$code) and $price=='')
   $read=false;
if(preg_match('/^shop-fit-/i',$code) and $price=='')
   $read=false;
if(preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
   $read=false;
if(preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
   $read=false;

//    if(!preg_match('/SunCH-0/i',$code) )     $read=false;
//    else{
//        print_r($cols);
//     print "$products $price $read $code\n";
//     //  exit;
    
//    }
  if($products and  $read){
    //  print_r($cols);
    //exit;
  //   if(preg_match('/EarC-06-0/i',$code) )
//       print "$code\n";
    $units=$cols[5];
    $description=$cols[6];
    $rrp=$cols[16];
    if($price>=0  ){
      $product=new Product('code',$code);
      if(!$product->id){
	$data=array(
		    'code'=>$code,
		    'price'=>$price,
		    'rrp'=>$rrp,
		    'units per case'=>$units,
		    'name'=>$description
		    );
	print_r($data);
	$product=new Product('create',$data);
	print_r($product->data);

	//print "xxx\n";
	//exit;
      }else{
	print $count."\r";
	//	print_r($product->data);
	//print $product->get('product code')."\n";
      }
	
      //print "C:$code D:$units x $description P:$price\n";
    }
    $count++;
  }


  $column++;
 }



?>