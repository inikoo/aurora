<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Vendors.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql="select * from aw_old.supplier where id=119";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=$res->fetchRow()) {
      if($row['name_long']=='')
	$row['name_long']=$row['name'];
      $data=array(
		  'name'=>$row['name_long'],
		  'code'=>$row['name']
		  );

      $supplier=new Supplier('new',$data);
      $supplier_company_id=$supplier->get('Company Key');

      $company=new company('id',$supplier_company_id);
      // print_r($company->data);
      if($row['email']!='')
	$company->add_email(array(
				  'email'=>$row['email']
				  ,'email_type'=>'Work'
				  ),'principal work');
       if($row['fax1']!=''){
	$data['country key']=$company->get('Company Country Key');
	$data['telecom number']=$row['fax1'];
	$data['telecom type']='Office Fax';
	$company->add_tel($data,'principal');
      }
       if($row['fax2']!=''){
	$data['country key']=$company->get('Company Country Key');
	$data['telecom number']=$row['fax2'];
	$data['telecom type']='Office Fax';
	$company->add_tel($data,'');
      }
       if($row['fax3']!=''){
	$data['country key']=$company->get('Company Country Key');
	$data['telecom number']=$row['fax3'];
	$data['telecom type']='Office Fax';
	$company->add_tel($data,'');
      }


      if($row['tel_office']!=''){
	$data['country key']=$company->get('Company Country Key');
	$data['telecom number']=$row['tel_office'];
	$data['telecom type']='Office Phone';
	$company->add_tel($data,'principal');
      }
    }



?>