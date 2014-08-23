<?php
//include("../../external_libs/adminpro/adminpro_config.php");
//include("../../external_libs/adminpro/mysql_dialog.php");

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Customer.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='costa';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');


$sql="select * from ci.customer";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $tipo='Person';
  $company_name='';
  $contact_name='';
  $email='';
  $tel='';
  $fax='';
  $sql="select * from ci.contact where id=".$row['contact_id'];
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
    if($row2['tipo']==0){
       $tipo='Company';
       $company_name=$row2['name'];

       $sql="select contact.name from ci.contact_relations left join ci.contact on (contact.id=child_id) where parent_id=".$row['contact_id'];
       $result3=mysql_query($sql);
       if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){
	 $contact_name=$row3['name'];

       }
    }else{
      $tipo='Person';
      $contact_name=$row2['name'];
    }

 /*    $sql="select email from ci.email where contact_id=".$row['contact_id']; */
/*        $result3=mysql_query($sql); */
/*        if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){ */
/* 	 $email=$row3['email']; */

/*        } */
/*       $sql="select number from ci.telecom where (tipo=1 or tipo=4) and contact_id=".$row['contact_id']; */
/*        $result3=mysql_query($sql); */
/*        if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){ */
/* 	 $tel=$row3['number']; */
/*        } */
/*        $sql="select number from ci.telecom where (tipo=3 or tipo=5) and contact_id=".$row['contact_id']; */
/*        $result3=mysql_query($sql); */
/*        if($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ){ */
/* 	 $fax=$row3['number']; */
/*        } */

  }else{
    
    
  }
    
  $data=array(
		'Customer Name'=>$row['name']
		,'Customer Type'=>$tipo
	
		,'Customer Main XHTML Telephone'=>$tel
		,'Customer Main XHTML FAX'=>$fax
		,'Customer Main Plain Email'=>$email
	
		,'Customer Main Contact Name'=>$contact_name
		,'Customer Company Name'=>$company_name
		);
    

$customer=new Customer('find create auto',$data);
if(!$customer->new){
  print_r($data);
  exit;
}

}


?>