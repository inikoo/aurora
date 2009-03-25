<?
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


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Part Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $part=new Part($row['Part Key']);
  
  //Get  status
  if(isset($argv[1]) and $argv[1]=='status'){
  $part_valid_from=$part->data['Part Valid From'];
  $part_valid_to=$part->data['Part Valid To'];
  $in_use='Not In Use';
  $sql=sprintf(" select `Product Sales State`,`Product Code` from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%d   ",$part->data['Part SKU']);
  // print "$sql\n";
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    if(preg_match('/^For sale|In process|Not for sale|Unknown/i',$row2['Product Sales State'])){
      $in_use='In Use';
      $part_valid_to= date("Y-m-d H:i:s");
    }else{
      
      $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Inventory Transaction Type`='Sale' and `Inventory Transaction Quantity`!=0    order by `Date` desc limit 1 ",$part->data['Part SKU']);
      $resultxx=mysql_query($sql);
      
      //   print "$sql\n";
      if($rowxx=mysql_fetch_array($resultxx, MYSQL_ASSOC)   ){

	if(strtotime($rowxx['Date'])< strtotime( $part->data['Part Valid From'])){
	  $part_valid_from=$rowxx['Date'];
	  //	  	print "$sql\n".$rowxx['Date']." ".$part->data['Part Valid From']." ".$part->data['Part Valid To']."\n";
	}
	$part_valid_to= $rowxx['Date'];
      }else{
	$part_valid_to=$part->data['Part Valid From'];
      }
    
      
    }

  }


    $sql=sprintf("update `Part Dimension` set `Part Status`=%s ,`Part Valid From`=%s ,`Part Valid To`=%s where `Part SKU`=%d   ",prepare_mysql($in_use)
		 ,prepare_mysql($part_valid_from)
		 ,prepare_mysql($part_valid_to),$part->data['Part SKU']);

    //print "$sql\n";
  if(!mysql_query($sql))
    exit("ERROR $sql\n");
  

    $part->load('sales');

  // print "$sql\n";
  //if(!mysql_query($sql))
  //  exit("ERROR $sql\n");
  }
  
  $part->load('sales');
  
    $part->load('used in');

    $part->load('supplied by');



     $part->load('stock');
     $part->load('stock_history');
    print $row['Part Key']."\r";

 }



?>