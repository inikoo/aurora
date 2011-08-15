<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);

$link = mysql_connect('mysql.freeola.net', 'sr1050741', 'starhtweb2');
if(!$link){print "Error can not connect with database server\n";exit;}
mysql_select_db('sr1050741', $link);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$sql="select condicion,code,stock,web_tipo,p2s.supplier_id from  aw_old.product left join aw_old.product2supplier as p2s on (product.id=p2s.product_id) ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $code=$row['code'];
  $web_state=$row['web_tipo'];
  $stock=$row['stock'];
  $supplier_id=$row['supplier_id'];
  $condicion=$row['condicion'];
  //print "$stock $stock\n";
  if($condicion==2 and ($stock==0 or $stock==''))
    $web_state='Online Force Out of Stock';
  elseif($supplier_id==2 and $web_state=='')
    $web_state='Online Force For Sale';
  elseif($web_state=='O')
     $web_state='Online Force Out of Stock';
  elseif($web_state=='D')
     $web_state='Online Force Out of Stock';
  else
    $web_state='Online Auto';

  if(!is_numeric($stock) or $stock=='' or $stock<0){
    $stock='NULL';
  }

  $sql=sprintf("update `Product Dimension` set `Product Availability`=%s ,`Product Web Configuration`=%s where `Product Code`=%s "
	       ,$stock
	       ,prepare_mysql($web_state)
	       ,prepare_mysql($code)
	       );
  //print $sql;
  mysql_query($sql,$con);
   $num_affected=mysql_affected_rows();
   if($num_affected>0){
    print "$code updated\n";
   }
  
}

?>