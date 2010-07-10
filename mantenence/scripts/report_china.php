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
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  
  
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';         
  
  
  
date_default_timezone_set('GMT');

//select `Product Key`,`Product Code` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where `Supplier Product Key`=133;



$sql="select * from `Supplier Product Dimension` where `Supplier Key`=21 and `Supplier Product Code` like '?%' ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $sp=new SupplierProduct('id',$row['Supplier Product Current Key']);
  //$sp->load('used in');
  //$sp->load('current_key_sales');
  //$sp->load('sales');

$sp->load('parts');
//print_r($sp->parts_sku);

$code=preg_replace('/^\?/','',$row['Supplier Product Code']);

$part=new Part($sp->parts_sku[0]);
$part->data['Part Current Stock']=0;
$sql=sprintf("select stock from aw_old.product where code=%s",prepare_mysql($code));
$res=mysql_query($sql);
if($srow=mysql_fetch_array($res)){
if(is_numeric($srow['stock']))
$part->data['Part Current Stock']=$srow['stock'];
}

$days=(strtotime('now')-strtotime($part->data['Part Valid From']))/3600/24;


 $part->load('sales');
 $stock=$part->data['Part Current Stock'];
$total_req=$part->data['Part Total Required'];
$req_per_month=30*$total_req/$days;
$units_per_carton=$sp->data['Units Per Carton'];
if(is_numeric($units_per_carton)){
  $cartons_3m=ceil(((3*$req_per_month)-$stock)/$units_per_carton);
  $cartons_6m=ceil(((6*$req_per_month)-$stock)/$units_per_carton);
if($cartons_3m<0);
    $cartons_3m=0;
if($cartons_6m<0);
    $cartons_6m=0;
}else{
$cartons_3m='ND';
$cartons_6m='ND';
}
$u3m= ((3.0*$req_per_month)-$stock);
$u6m=(float) ((6.0*$req_per_month)-$stock);

if($u3m<0)
    $u3m=0;
    if($u6m<0)
    $u6m=0;

    if($u3m>0 and $cartons_3m==0 )
      $cartons_3m=1;
 if($u6m>0 and $cartons_6m==0 )
      $cartons_6m=1;
    print "$code,$stock,$units_per_carton,$days,$total_req,$req_per_month,$u3m,$cartons_3m,  $u6m,$cartons_6m\n";
 }



?>