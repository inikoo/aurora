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

mysql_set_charset('utf8');
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



 $sql="select *,`Delivery Note Key` as id from `Delivery Note Dimension` where       `Delivery Note Metadata` like '".$meta_letter."1376' order by `Delivery Note ID`  ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)   ){
  preg_match('/\d+/',$row['Delivery Note Metadata'],$match);
  $order_data_id=$match[0];

  print $row['Delivery Note ID'].' '.$row['Delivery Note Metadata']." $order_data_id ";
  
  $sql="select * from $table where id=".$order_data_id;
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2)   ){
    $header=mb_unserialize($row2['header']);
    $map_act=$_map_act;
    $map=$_map;
    $y_map=$_y_map;
    $prod_map=$y_map;
    list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map,false);
   
 
    fix_parcels($row['id'],$header_data,$store_code);

 }





}


function fix_parcels($dn_id,$header_data,$store_code){
  $parcels=$header_data['parcels'];
  $shipping=$header_data['shipping'];
  $number_parcels=1;
  $boxes=(float)$parcels;
$weight=$header_data['weight'];
// print_r($header_data);
  $type='Box';
  $num_parcels=0;
  if(is_numeric($parcels))
    $num_parcels=$parcels;


  if(preg_match('/pall?et/',$parcels) or ($store_code=='FR' and $shipping>80 and $header_data['postcode']=='France')){
    $type='Pallet';
    $num_parcels=1;

    if(preg_match('/(\d+)/',$parcels,$match)){
      $boxes=preg_replace('/[^\d]/','',$match[0]);
    }

    if(preg_match('/^\d+\s+pall?ets?$/',$parcels,$match)){
      $parcels=preg_replace('/[^\d]/','',$match[0]);
      $boxes='';
    }
  }
    if($num_parcels==0)
      $num_parcels='';
     
    list($w,$w_unit)=parse_weight($weight);

    $w_kg=convert_weigth($w,$w_unit,'Kg');
    $sql=sprintf("update kaw.`Delivery Note Dimension` set `Delivery Note Weight`=%s , `Delivery Note Number Parcels`=%s  ,`Delivery Note Parcel Type`=%s ,`Delivery Note Number Boxes`=%s  where `Delivery Note Key`=%d"
		 ,prepare_mysql($w_kg)

		 ,prepare_mysql($num_parcels)
		 ,prepare_mysql($type)
		 ,prepare_mysql($boxes)
		 ,$dn_id
		 );
    mysql_query($sql);
    print_r($header_data);
    //  print $sql;

 

    print "W $w_kg  P:  $parcels; S: $shipping;  $boxes $num_parcels $type  \n";

   



}



 





?>