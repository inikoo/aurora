<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw2';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Part Dimension` limit 1";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $part=new Part($row['Part Key']);
  print $part->data['Part SKU']."\r";
  $sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type` like 'Sale' and `Part SKU`=".$part->data['Part SKU'];
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    //  print_r($row2);
    
    if($row2['Inventory Transaction Quantity']!=0)
      $cost=$row2['Inventory Transaction Amount']/$row2['Inventory Transaction Quantity'];
    else
      $cost=0;

    $keys=get_sp($part->data['Part SKU'],$row2['Date'],$cost);
    if(count($keys)==1){
      
      $sql=sprintf("update `Inventory Transaction Fact` set `Supplier Product ID`=%d where `Inventory Transaction Type` like 'Sale' and `Part SKU`=%d and `Date`=%s",$keys[0]['key'], $part->data['Part SKU'],prepare_mysql($row2['Date']));
      //print "$sql\n";
      mysql_query($sql);
    }else
      print count($keys)."\n";
    
  }


 }

function get_sp($part_sku,$date,$cost){


  $sql=sprintf(" select (`Supplier Product Cost`*`Supplier Product Units Per Part`)-1.4 as icost, `Supplier Product Part Valid From`,`Supplier Product Part Valid To`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product ID`,`Supplier Product Cost` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  where `Part SKU`=%s  and `Supplier Product Valid To`>=%s and  `Supplier Product Valid From`<=%s   and abs((`Supplier Product Cost`*`Supplier Product Units Per Part`)-%s) <0.009    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date),$cost);
  // print "\n\n\n\n$sql\n";
  $result=mysql_query($sql);
  $keys=array();
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $keys[]=array(
					      'key'=>$row['Supplier Product ID']
					      ,'cost'=>$row['icost']
					      ,'from'=>$row['Supplier Product Valid From']
					      ,'to'=>$row['Supplier Product Valid To']
					      );
  }

 //  if(count($keys)!=1){
//     print "$date, $cost $sql\n";
//   }


  return $keys;



}

?>