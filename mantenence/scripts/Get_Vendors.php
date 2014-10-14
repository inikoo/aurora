<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  

require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');





$software='Get_Vendors.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql=" select * from aw_old.supplier left join aw_old.address on address_id=address.id ";
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data=array();

      
      if($row['name_long']=='')
	$row['name_long']=$row['name'];
      $data=array(
		  'name'=>$row['name_long'],
		  'code'=>$row['name'],
		  'contact_name'=>$row['att'],
		  );


      if($row['address_id']){
	$data['address_data']=array(
				    'type'=>'3line',
				    'address1'=>$row['line1'],
				    'address2'=>$row['line2'],
				    'address3'=>$row['line3'],
				    'town'=>$row['city'],
				    'country'=>$row['country'],
				    'country_d1'=>'',
				    'country_d2'=>'',
				    'postcode'=>$row['postcode'],
				    'default_country_id'=>30
				    );
      }
      //  print_r($data);
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