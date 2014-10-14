<?php
//include("../../external_libs/adminpro/adminpro_config.php");

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Invoice.php');
include_once('../../class.DeliveryNote.php');



error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';

mysql_set_charset('utf8');

require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$_SESSION['lang']=1;
include_once('local_map.php');

include_once('map_order_functions.php');

$software='Get_Orders_DB.php';
$version='V 1.0';//75693

$Data_Audit_ETL_Software="$software $version";
srand(12344);

$sql="select * from  orders_data.orders   order by filename desc ";
//$sql="select * from  orders_data.orders where filename like '%refund.xls'   order by filename";
//$sql="select * from  orders_data.orders  where filename like '/mnt/%/Orders/15297.xls'  order by filename";


$contador=0;
//print $sql;
$res=mysql_query($sql);

while($row2=mysql_fetch_array($res, MYSQL_ASSOC)){

 
  $sql="select * from orders_data.data where id=".$row2['id'];
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){



    $header=mb_unserialize($row['header']);
    $products=mb_unserialize($row['products']);

    $filename_number=str_replace('.xls','',str_replace($row2['directory'],'',$row2['filename']));
    $map_act=$_map_act;$map=$_map;$y_map=$_y_map;
     
    // tomando en coeuntas diferencias en la posicion de los elementos
  
    if($filename_number==19015){
      $y_map['code']=4;
    }
     
  
    if($filename_number<18803){// Change map if the orders are old
      $y_map=$_y_map_old;
      foreach($_map_old as $key=>$value)
 	$map[$key]=$value;
    }
    $prod_map=$y_map;
    if($filename_number==53378){
      $prod_map['no_price_bonus']=true;
      $prod_map['no_reorder']=true;
      $prod_map['bonus']=11;
    }elseif($filename_number==64607){
      $prod_map['no_price_bonus']=true;
      $prod_map['no_reorder']=true;
      $prod_map['bonus']=11;
    }
     


    list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
    $header_data=filter_header($header_data);
    list($tipo_order,$parent_order_id,$header_data)=get_tipo_order($header_data['ltipo'],$header_data);

  
    if(!$header_data['notes']){
      $header_data['notes']='';
    }

  if(preg_match('/^(Int Freight|Intl Freight|Internation Freight|Intl FreightInternation Freight|International Frei.*|International Freigth|Internation Freight|Internatinal Freight|nternation(al)? Frei.*|Internationa freight|International|International freight|by sea)$/i',$header_data['notes']))
      $header_data['notes']='International Freight';

    //delete no data notes
   
    
    if(is_to_be_collected($header_data['notes']))
      $header_data['notes']='';
    $header_data=is_shipping_supplier($header_data);
    $header_data=is_staff_sale($header_data);
    
    if(is_showroom($header_data['notes']))
      $header_data['notes']='';
    
    
    if(!preg_match('/^(|International Freight)$/',$header_data['notes'])){
      print "N1: ".$header_data['notes']."\n";
      print "N2: ".$header_data['notes2']."\n\n";
    }
    // if(!preg_match('/^(|0|\s*)$/',$header_data['notes2']))
    
    if($header_data['notes']=='Local')
      print_r($header_data);
    $header_data['notes2']=_trim($header_data['notes'].', '.$header_data['notes2']);
    $header_data['notes']='';
    
     
    
     }
     
  }