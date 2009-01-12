<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Orders_Internet.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$data=array(
	    'type'=>'imap_email_mals-e',
	    'email'=>'orders.aw@googlemail.com',
	    'pwd'=>'eo-01,eid-04'
);

$order= new Order('new',$data);


?>