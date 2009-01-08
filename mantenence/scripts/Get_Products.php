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

 if(preg_match('/-st$/i',$code) and $price=='')
    $read=false;
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

  if($products and  $read){

    $units=$cols[5];
    $description=$cols[6];

    if($price>=0 )
      print "C:$code D:$units x $description P:$price\n";
    
  }


  $column++;
 }



?>