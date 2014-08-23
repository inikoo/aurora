<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
include_once('common_read_orders_functions.php');



//  $this->update_historic_sales_data();
 //     $this->update_sales_data();
  //    $this->update_same_code_sales_data();


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$store_code='FR';


switch($store_code){
case('FR'):
$table='fr_orders_data.data';
include_once('fr_local_map.php');
include_once('fr_map_order_functions.php');
$meta_letter='F';
}



 $sql="select * from `Delivery Note Dimension` where `Delivery Note Metadata` like '".$meta_letter."%' ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
  $order_data_id=preg_match('/\d+/',$row['Delivery Note Metadata']);
  print $row['Delivery Note Metadata']." $order_data_id\n";
  
  $sql="select * from $table where id=".$order_data_id;
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2)   ){
    $header=mb_unserialize($row2['header']);
    $map_act=$_map_act;
    $map=$_map;
    $y_map=$_y_map;
    $prod_map=$y_map;
    list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map,false);
    print_r($header_data);
  }



exit($sql);

}





 



?>