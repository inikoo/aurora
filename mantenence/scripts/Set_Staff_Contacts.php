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

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Staff.php');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw_avant2';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");

require_once '../../conf/timezone.php'; 
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
	    'editor'=>array('Date'=>'2003-08-28 09:00:00')
	    ,'Company Name'=>'Ancient Wisdom'
	    ,'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd'
	    ,'Company Tax Number'=>'764298589'
	    ,'Company Registration Number'=>'4108870'
	   // ,'Company Main XHTML Telephone'=>'+44 1142729165'
	   // ,'Company Main XHTML FAX'=>'+44 (0) 114 2706571'
	   // ,'Company Main Plain Email'=>'mail@ancientwisdom.biz'
	   // ,'Company Address Line 1'=>'BLOCK B, Parkwood Business Park, Parkwood Road'
	   // ,'Company Address Town'=>'Sheffield'
	   // ,'Company Address Postal Code'=>'S3 8AL '
	  //  ,'Company Address Country Name'=>'UK'
	 //   ,'Company Main Contact Name'=>'Mr David Hardy'
);


$company=new Company('find create auto',$data);
exit;
$sql=sprintf("insert into `Account Dimension` values (%s,%d,'GBP') ",$company->data['Company Name'],$company->id );
mysql_query($sql);


//$sql="select * from  `Staff Dimension`  where `Staff Name`!='David' ";
$sql="select * from  `User Dimension` where `User Handle`!='root' ";;
$res=mysql_query($sql);
while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
  
  $data_contact=array();
$data_contact['Contact Name']=ucwords($row['User Handle']);

  if($row['User Handle']=='raul'){
    $data_contact=array(
			'Contact Name'=>'Mr Raul Alejandro Perusquia Flores'
			,'Contact Main Pain Email'=>'rulovico@gmail.com'
			);
  }
   if($row['User Handle']=='martina'){
    $data_contact=array(
			'Contact Name'=>'Martina Otte'
			,'Contact Main Plain Email'=>'martina@aw-gechenke.com'
			);
  }if($row['User Handle']=='kerry'){
    $data_contact=array(
			'Contact Name'=>'Miss Kerry Miskelly'
			,'Contact Main Plain Email'=>'kerry@ancientwisdom.biz'
			);
  }if($row['User Handle']=='katka'){
    $data_contact=array(
			'Contact Name'=>'Katka Buchy'
			,'Contact Main Plain Email'=>'katka@ancientwisdom.biz'
			);
  }if($row['User Handle']=='philippe'){
    $data_contact=array(
			'Contact Name'=>'Philippe Buchy'
			,'Contact Main XHTML Mobile'=>''
			);
  }if($row['User Handle']=='amanda'){
    $data_contact=array(
			'Contact Name'=>'Miss Amanda Fray'
			,'Contact Main XHTML Mobile'=>''
			);
  }if($row['User Handle']=='slavka'){
    $data_contact=array(
			'Contact Name'=>'Slavka Hardy'
			,'Contact Main Plain Email'=>'slavka@ancientwisdom.biz'
			);
  }if($row['User Handle']=='alan'){
    $data_contact=array(
			'Contact Name'=>'Mr Alan Wormald'
			,'Contact Main Plain Email'=>'alan@ancientwisdom.biz'
			);
  }

$data_contact['Staff Alias']=ucwords($row['User Handle']);


   //  print_r($data_contact);
$staff=new Staff('find',$data_contact,'create');
 


 //print_r($contact);
  $contact=new Contact($staff->data['Staff Contact Key']);
  $company->add_contact($contact->id,'no_principal');
     
 /*  $contact->add_address(array( */
/* 				  'Address Key'=>$company->data['Company Main Address Key'] */
/* 				  ,'Address Type'=>array('Work') */
/* 				  ,'Address Function'=>array('Contact') */

/* 			      )); */
  

  

  $sql=sprintf("update `User Dimension` set `User Parent Key`=%d, `User Key`=%d"
	       ,$staff->id
	       ,$row['User Key']
	       );
  mysql_query($sql);
 }
 mysql_free_result($res);
