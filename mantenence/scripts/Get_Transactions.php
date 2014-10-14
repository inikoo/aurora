<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Customer.php');

require_once 'MDB2.php';$PEAR_Error_skiptrace = &PEAR::getStaticProperty('PEAR_Error','skiptrace');$PEAR_Error_skiptrace = true;
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  

require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');






$software='Get_Transactions.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql=" select * from aw.transaction left join aw.orden on (order_id=orden.id) limit 1";

$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  //print_r($row);

  $sql="insert into `Order Accumulating Fact` (`Order ID`,`Product Key`,`Supplier Key`)";
  
 }