<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
mysql_query("SET time_zone ='+0:00'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');





$software='Get_Parts.php';
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

  if($code=='' or !preg_match('/\-/',$code))
    $read=false;


  if($products and  $read){
    $scode=$cols[20];
    $supplier=$cols[21];
    $cost=$cols[25];
    
    if($scode=='')
      $scode=$code;
    if($supplier=='')
      $supplier='UNK';
    if(is_numeric($cost)){
      if($cost<0.01)
	$cost=0.01;
      $cost=sprintf("%.2f",$cost);
    }else
      $cost='';

    //print "$code SC:$scode S:$supplier C:$cost\n";
    
    $parts[]=array('supplier'=>$supplier,'code'=>$code,'scode'=>$scode,'cost'=>$cost);
    $_supplier[strtolower($supplier)]=0;
    
    //$sql="insert into `Part Dimension` ()"
    
  }


  $column++;
 }

foreach($_supplier as $key =>$value){
  $supplier=new Supplier('code',$key);
  if($supplier->id)
    $_supplier[$key]=$supplier->id;
  else{
     $data=array(
		  'name'=>$key,
		  'code'=>$key,
		  );
     $supplier=new Supplier('new',$data);
     $_supplier[$key]=$supplier->id;
  }
}

foreach($parts as $part){
  $sql=sprintf("insert into `Part Dimension` (`Customer Uniqe ID
`,`Part Code`,`Part Current Cost`) values (%d,%s,%s)"
	       ,$_supplier[strtolower($part['supplier'])]
	       ,prepare_mysql($part['code'])
	       ,prepare_mysql($part['cost'])
	       );
  mysql_query($sql);
  print $_supplier[strtolower($part['supplier'])]." S:".$part['supplier']."\n" ;
  //print $sql;
  //exit;
  
}


?>