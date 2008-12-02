<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Product.php');
require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
require_once '../../myconf/conf.php';           
$db->query("SET time_zone ='UTC'");
date_default_timezone_set('Europe/London');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select id from product ";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=$res->fetchRow()) {
    $id=$row['id'];
    $product=new Product($id);
    $product->load('first_date','save');
    $product->load('sales');
    $product->save('sales');
    $index=$product->get('num_images')+1;
    $sql="update product set image_index=$index where id=".$product->id;
    
    mysql_query($sql);
    //printf("$id\r");
  }

?>