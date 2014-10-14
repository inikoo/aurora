<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Location.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  

require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');





$software='Transfer_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql="select sdescription,product.description,product.units,product.price,product.rrp,product.code ,product_group.description as family,product_group.name as family_code,product_department.name as department from aw_old.product left join aw_old.product_group on (group_id=product_group.id) left join aw_old.product_department on (department_id=product_department.id) ";
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      //print "$sdescription";
      $sdescription=_trim($row['sdescription']);
      $_family=$row['family'];
      $family=str_replace("\\","\\",$family);
      $family=str_replace("/","\/",$family);

      print "$_family\n";
      $sdescription=preg_replace("/^$_family\s*\-?\s*/",'',$sdescription);
      $sdescription=preg_replace("/\-?\s*$_family$/",'',$sdescription);
      $__family=preg_replace('/s$/','',$_family);
      $sdescription=preg_replace("/^$__family\s*\-?\s*/",'',$sdescription);
      $sdescription=preg_replace("/\-?\s*$__family$/",'',$sdescription);

      $sdescription=preg_replace("/^(large\s*)?scented pumice/",'',$sdescription);
      $sdescription=preg_replace("/\-?\s*\d+\s*ml(\s*essencial oils?)?$/i",'',$sdescription);
      $sdescription=preg_replace("/\-?\s*\(?\d+\s*kg\)?(\s*essencial oils?)?$/i",'',$sdescription);
      $sdescription=preg_replace("/\-?\s*Pot\s*\-?\s*pourri$/i",'',$sdescription);
      $sdescription=preg_replace("/rrp(.*\d)$/i",'',$sdescription);

      //    print ", $_family , $__family , $sdescription\n";
      

      $sdescription=_trim($sdescription);
      $data=array(
		  'units factor'=>$row['units'],
		  'code'=>$row['code'],
		  'family'=>$row['family'],
		  'department name'=>$row['department'],
		  'price'=>$row['price'],
		  'rrp'=>$row['rrp'],
		  'name'=>number($row['units']).'x '.$row['description'],
		  'short name'=>$row['description'],
		  'special characteristic'=>$sdescription,
		  'family name'=>$row['family'],
		  'family code'=>$row['family_code'],
		  'department name'=>$row['department'],
		  
		  );
      $product=new Product('new',$data);
      //  print_r($product);
      unset($product);
    }



?>