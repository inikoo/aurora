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






$software='Get_Vendors.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$sql=" select aw.customer.main_tel,aw.customer.main_email,main_del_address,aw.contact.main_contact,aw.customer.main_bill_address,aw.customer.name as name,aw.contact.tipo,aw.contact.name as contact_name from aw.customer left join aw.contact on contact_id=contact.id ";
//print $sql;
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $data=array();
      $type='Unknown';
      $contact_name='';
      $company_name='';
      if($row['tipo']==0){
	$type='Company';
	$company_name=$row['contact_name'];

	$sql=sprintf("select * from aw.contact where id=%d",$row['main_contact']);
	$res2 = mysql_query($sql);
	if($row2=$res2->fetchRow()) {
	  //	  $contact_name=_trim($row2['prefix'].' '.$row2['first'].' '.$row2['maddle'].' '.$row2['last']);
	  $contact_name=$row2['name'];
	}

      }
      elseif($row['tipo']==1){
	$type='Person';
	$contact_name=$row['contact name'];
      }

      $data=array(
		  'type'=>$type,
		  'company_name'=>$company_name,
		  'contact_name'=>$contact_name,
		  );


       if($row['main_bill_address']){
	 	$sql=sprintf("select * from aw.address where id=%d",$row['main_bill_address']);
		$res2 = mysql_query($sql);
		if($row2=$res2->fetchRow()) {
		  $address_data=$row2;
		  $address_data['type']='aw';
		  $data['address_data']=$address_data;

		}
       }
       if($row['main_email']){
	 	$sql=sprintf("select * from aw.email where id=%d",$row['main_email']);

		$res2 = mysql_query($sql);
		if($row2=$res2->fetchRow()) {

		  $data['email']=$row2['email'];

		}
       }
       
       if($row['main_tel']){
	 $sql=sprintf("select * from aw.telecom where id=%d",$row['main_tel']);
	 // print "$sql\n";
	 $res2 = mysql_query($sql);
	 if($row2=$res2->fetchRow()) {
	   $data['telephone']='';
	   if($row2['icode']!='')
	     $data['telephone'].='+'.$row2['icode'];
	   $data['telephone'].=' '.$row2['ncode'].$row2['number'];
	   if($row2['ext']!='')
		    $data['telephone'].=' ext'.$row2['ext'];
	   
	   $data['telephone']=_trim($data['telephone']);
	   // print_r($data);
	 }
       }
       

       //  print_r($data);
       $supplier=new Customer('new',$data);
//       $supplier_company_id=$supplier->get('Company Key');
      

//       $company=new company('id',$supplier_company_id);
//       // print_r($company->data);
//       if($row['email']!='')
// 	$company->add_email(array(
// 				  'email'=>$row['email']
// 				  ,'email_type'=>'Work'
// 				  ),'principal work');
//        if($row['fax1']!=''){
// 	$data['country key']=$company->get('Company Country Key');
// 	$data['telecom number']=$row['fax1'];
// 	$data['telecom type']='Office Fax';
// 	$company->add_tel($data,'principal');
//       }
//        if($row['fax2']!=''){
// 	$data['country key']=$company->get('Company Country Key');
// 	$data['telecom number']=$row['fax2'];
// 	$data['telecom type']='Office Fax';
// 	$company->add_tel($data,'');
//       }
//        if($row['fax3']!=''){
// 	$data['country key']=$company->get('Company Country Key');
// 	$data['telecom number']=$row['fax3'];
// 	$data['telecom type']='Office Fax';
// 	$company->add_tel($data,'');
//       }


//       if($row['tel_office']!=''){
// 	$data['country key']=$company->get('Company Country Key');
// 	$data['telecom number']=$row['tel_office'];
// 	$data['telecom type']='Office Phone';
// 	$company->add_tel($data,'principal');
//       }
   

      


 }



?>