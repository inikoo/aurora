<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Product.php');
require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           
mysql_query("SET time_zone ='+0:00'");
date_default_timezone_set('UTC');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select id from product ";
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    $product=new Product($id);
    $product->load('first_date','save');
    $product->update_sales();
    $product->save('sales');
    $index=$product->get('num_images')+1;
    $sql="update product set image_index=$index where id=".$product->id;
    
    mysql_query($sql);
    //printf("$id\r");
  }

?>