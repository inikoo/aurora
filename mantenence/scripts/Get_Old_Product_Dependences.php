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
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');

$sql=sprintf("select (select code from aw_old.product p where p.id=bulk_id) as r1 ,(select code from aw_old.product p where p.id=product_id) as r2,(select count(*) from aw_old.product_relations as prtmp where prtmp.product_id=aw_old.product_relations.product_id) as multiplicity   from aw_old.product_relations;");
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){

  if($row['multiplicity']==1){





  print $row['r1'].' '.$row['r2']."\n";
  //find if there is more the one product id
  $sql=sprintf("select `Product ID`,`Product Key`,`Product Code`,`Product Price`,`Product Short Description` from `Product Dimension` where `Product Code` like %s group by `Product ID`"
	       ,prepare_mysql($row['r1'])
	       );
  $res_relation1=mysql_query($sql);
  $num_products_id_relation1=mysql_num_rows($res_relation1);

  
  
  $sql=sprintf("select `Product ID`,`Product Key`,`Product Code`,`Product Price`,`Product Short Description` from `Product Dimension` where `Product Code` like %s group by `Product ID`"
	       ,prepare_mysql($row['r2'])
	       );
  $res_relation2=mysql_query($sql);
  $num_products_id_relation2=mysql_num_rows($res_relation2);
  if($num_products_id_relation1>1 or $num_products_id_relation2>1 )
    print("********* error more the 1 product ID\n");
  
 }


 }



function merge_product($product1,$product2){
  if($p1->data['Product Units Per Case']==$p2->data['Product Units Per Case']){
    exit("same units\n");
  }if($p1->data['Product Units Per Case']>$p2->data['Product Units Per Case']){
    $product_parts_to_keep=$p2;
    $product_parts_to_delete=$p1;
  }else{
    $product_parts_to_keep=$p1;
    $product_parts_to_delete=$p2;
  }

  

}

?>