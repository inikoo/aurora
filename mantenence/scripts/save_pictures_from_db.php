<?php
date_default_timezone_set('UTC');

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Image.php');

include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

 
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

 $sql=sprintf("select `Image Dimension`.`Image Key` from `Image Dimension` left join `Image Bridge` on (`Image Dimension`.`Image Key`=`Image Bridge`.`Image Key`)where `Subject Type`='Product' limit 1");
  //print "$sql\n";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
  $image=new Image($row['Image Key']);
  $image->get_image_data();
  $image->save_to_disk();
  }
mysql_free_result($sql);



?>