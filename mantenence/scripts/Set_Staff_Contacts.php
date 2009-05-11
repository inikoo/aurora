<?
/*
 Script: Set_Staff_Contacts.php
 This script creates contacts associated with staff table.

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
//include("../../external_libs/adminpro/adminpro_config.php");

include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");

require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');
$_SESSION['lang']=1;

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

//Create company

$data=array(
	    'Company Name'=>'Ancient Wisdom'
	    ,'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd'
	    ,'Company Tax Number'=>'764298589'
	    ,'Company Registration Number'=>'4108870'
	    ,'Company Main Telephone'=>'+44 1142729165'
	    ,'Company Main FAX'=>'+44 (0) 114 2706571'
	    ,'Company Main Email'=>'mail@ancientwisdom.biz'
	    ,'Company Address Line 1'=>'BLOCK B, Parkwood Business Park, Parkwood Road'
	    ,'Company Address Town'=>'Sheffield'
	    ,'Company Address Postal Code'=>'S3 8AL '
	    ,'Company Address Country Name'=>'UK'
	    ,'Company Main Contact'=>'Mr David Hardy'
);


$company=new Company('find',$data);
exit;

$sql="select * from  `Staff Dimension` ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
  
  // $name='Contact Name'=>ucwords($row['Staff Name']);
  
  $data_contact=array(
		      'Contact Name'=>ucwords($row['Staff Name'])
		      );
  if($row['Staff Alias']=='raul'){
    $data_contact=array(
			'Contact Name'=>'Mr Raul Alejandro Perusquia Flores'
			,'Contact Main Mobile'=>''
			);
    
  }
  

  $contact=new contact('new',$data_contact);
  $sql=sprintf("update `Staff Dimension` set `Staff Alias`=%s,`Staff Name`=%s,`Staff Contact Key`=%d where `Staff Key`=%d"
	       ,prepare_mysql(strtolower($row['Staff Alias']))
	       ,prepare_mysql(ucwords($row['Staff Name']))
	       ,$contact->id
	       ,$row['Staff Key']
	       );
  mysql_query($sql);
 }
