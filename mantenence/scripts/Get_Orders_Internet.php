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
$db->query("SET NAMES 'utf8'");

require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Orders_Internet.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$data=array(
	    'type'=>'imap_email_mals-e',
	    'email'=>'orders.aw@googlemail.com',
	    'pwd'=>'eo-01,eid-04',
	    'product code exceptions'=>array('FIRST','Please'),
	    'product code replacements'=>array(
					       'jutesc-04'=>array(
								  array(
									'replacement'=>'JuteS-04C',
									'from'=>date('U',strtotime('- 10 year')),
									'to'=>date('U',strtotime('+ 10 year')),
									'confirm'=>'JuteSC-04'
									)
								  ),
					       'jutesc-03'=>array(
								  array(
									'replacement'=>'JuteS-04C',
									'from'=>date('U',strtotime('- 10 year')),
									'to'=>date('U',strtotime('+ 10 year')),
									'confirm'=>'JuteSC-03'
									)
								  ),
					       'jutesc-02'=>array(
								  array(
									'replacement'=>'JuteS-02C',
									'from'=>date('U',strtotime('- 10 year')),
									'to'=>date('U',strtotime('+ 10 year')),
									'confirm'=>'JuteSC-02'
									)
								  ),
					        'l0-11'=>array(
								  array(
									'replacement'=>'LO-11',
									'from'=>date('U',strtotime('- 10 year')),
									'to'=>date('U',strtotime('+ 10 year')),
									'confirm'=>'L0-11'
									)
								  ),
					       '10x'=>array(
							    array('replacement'=>'Scrun-01',
								  'from'=>date('U',strtotime('- 10 year')),
								  'to'=>date('U',strtotime('+ 10 year')),
								  'line'=>'10x Scrun-01'),
							     array('replacement'=>'Scrun-02',
								  'from'=>date('U',strtotime('- 10 year')),
								  'to'=>date('U',strtotime('+ 10 year')),
								  'line'=>'10x Scrun-02'),
							    array('replacement'=>'Scrun-03',
								  'from'=>date('U',strtotime('- 10 year')),
								  'to'=>date('U',strtotime('+ 10 year')),
								  'line'=>'10x Scrun-03'),
							      array('replacement'=>'Scrun-04',
								  'from'=>date('U',strtotime('- 10 year')),
								  'to'=>date('U',strtotime('+ 10 year')),
								  'line'=>'10x Scrun-04'),
							    )
					       )

);

$order= new Order('new',$data);





?>
