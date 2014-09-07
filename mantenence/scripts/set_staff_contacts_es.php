<?php
/*
 Script: Set_Staff_Contacts.php
 This script creates contacts associated with staff table.

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
//include("../../external_libs/adminpro/adminpro_config.php");

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_tmp';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");

require_once 'timezone.php'; 
date_default_timezone_set(TIMEZONE) ;
require('../../external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

require_once '../../conf/conf.php';           
include_once('../../set_locales.php');
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

//Create company



$data=array(
	    'Company Name'=>'AW Regalos'
	    ,'Company Fiscal Name'=>'Costa Import S.L.'
	    ,'Company Tax Number'=>'(ES) B-92544691'
	    ,'Company Registration Number'=>''
	    ,'Company Main XHTML Telephone'=>'(+34) 952 417 609'
	    ,'Company Main XHTML FAX'=>'(+34) 952 175 621 '
	    ,'Company Main Plain Email'=>'info@aw-regalos.com'
	    ,'Company Address Line 1'=>'Pol’gono Ind. Alhaur’n de la Torre, Fase 1'
	    ,'Company Address Town'=>'M‡laga'
	    ,'Company Address Postal Code'=>'29130'
	    ,'Company Address Country Name'=>'Spain'
	    ,'Company Main Contact Name'=>'Carlos'
);


$company=new Company('find create auto',$data);


$sql="select * from  `Staff Dimension`  where `Staff Name`!='David' ";
$sql="select * from  `Staff Dimension`  ";
;
$res=mysql_query($sql);
while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
  
  //$name='Contact Name'=>ucwords($row['Staff Name']);
  print "caca";
  $data_contact=array(
		      'Contact Name'=>ucwords($row['Staff Alias'])
		      //,'Contact Company Key'=>$company->id
		      );
  if($row['Staff Alias']=='raul'){
    $data_contact=array(
			'Contact Name'=>'Mr Raul Alejandro Perusquia Flores'
			,'Contact Main Pain Email'=>'rulovico@gmail.com'
			);
  }
   if($row['Staff Alias']=='martina'){
    $data_contact=array(
			'Contact Name'=>'Martina Otte'
			,'Contact Main Plain Email'=>'martina@aw-gechenke.com'
			);
  }if($row['Staff Alias']=='kerry'){
    $data_contact=array(
			'Contact Name'=>'Miss Kerry Miskelly'
			,'Contact Main Plain Email'=>'kerry@ancientwisdom.biz'
			);
  }if($row['Staff Alias']=='katka'){
    $data_contact=array(
			'Contact Name'=>'Katka Buchy'
			,'Contact Main Plain Email'=>'katka@ancientwisdom.biz'
			);
  }if($row['Staff Alias']=='david'){
    $data_contact=array(
			'Contact Name'=>'David'
				,'Contact Main Plain Email'=>'david@aw-regalos.com'
			
			);
  }if($row['Staff Alias']=='carlos'){
    $data_contact=array(
			'Contact Name'=>'Carlos'
						,'Contact Main Plain Email'=>'carlos@aw-regalos.com'

			);
  }if($row['Staff Alias']=='lucia'){
    $data_contact=array(
			'Contact Name'=>'Lucia'
			,'Contact Main Plain Email'=>'lucia@aw-regalos.com'
			);
  }if($row['Staff Alias']=='juani'){
    $data_contact=array(
			'Contact Name'=>'Juani'
			,'Contact Main Plain Email'=>'juani@aw-regalos.com'
			);
  }

   
  print_r($data_contact);
  $contact=new contact('find in company create',$data_contact);
//  print_r($contact);
//  exit;
  $company->add_contact($contact->id,'no_principal');
     
  $contact->associate_address(array(
				  'Address Key'=>$company->data['Company Main Address Key']
				  ,'Address Type'=>array('Work')
				  ,'Address Function'=>array('Contact')

			      ));
  

  if($row['Staff Currently Working']=='No'){
    $company->remove_contact($contact->id);
    if($company->error){
      print $company->msg."\n";
      exit;
    }
  }

  $sql=sprintf("update `Staff Dimension` set `Staff Alias`=%s,`Staff Name`=%s,`Staff Contact Key`=%d where `Staff Key`=%d"
	       ,prepare_mysql(strtolower($row['Staff Alias']))
	       ,prepare_mysql($contact->display('name'))
	       ,$contact->id
	       ,$row['Staff Key']
	       );
  mysql_query($sql);
  print $sql;
 }
 mysql_free_result($res);
