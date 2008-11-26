<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Family.php');
require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
require_once '../../myconf/conf.php';           

$sql="select id from product_group ";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=$res->fetchRow()) {
    $id=$row['id'];
    $family=new Family($id);
    $family->load('first_date','save');
    $family->load('sales_metadata','save');
     printf("$id\r");
  }

?>