<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
$PEAR_Error_skiptrace = &PEAR::getStaticProperty('PEAR_Error','skiptrace');$PEAR_Error_skiptrace = true;// Fix memory leak
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');





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
