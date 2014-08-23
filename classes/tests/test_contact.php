<?php
/*
 Script: test_contact.php
 Tests for contact class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/


include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");

require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$_SESSION['lang']=1;


global $contact;

// Create a contact
//$data=array('Contact Name'=>'Raul Perusquia');
//$contact=new Contact('find create',$data);
//print_r($contact);


// Create a contact with email

test(3);


function  test($id){
  global $contact;

switch($id){
case 2:
  // try to update/create  email
  
  test(1);
 
  $data=array('Contact Main Plain Email'=>'rulovico2@gmail.com');
  $contact->update($data);
  print_r($contact);
  break;
case 3:
  test(1);
  print "======================================================\n";
  print "=====================Test 3===========================\n";
    
  $data=array(
	      'Contact Name'=>'Raul Perusquia'
	      ,'Contact Main Plain Email'=>'rulovico2@gmail.com'
	      ,'Contact Main XHTML Telephone'=>'+44 114 277731'
	      ,'Contact Main XHTML FAX'=>'+44 114 277732'
	      ,'Contact Main XHTML Mobile'=>'+44 7111 112233'
	      ,'Contact Work Address Line 1'=>'BLOCK A, Parkwood Business Park, Parkwood Road'
	      ,'Contact Work Address Town'=>'Sheffield'
	      ,'Contact Work Address Postal Code'=>'S11 8AL '
	      ,'Contact Work Address Country Name'=>'UK'
	      ,'Contact Home Address Line 1'=>'Flat 43'
	      ,'Contact Home Address Line 2'=>'Jet Building'
	      ,'Contact Home Address Line 3'=>'35 St Marys Rd'
	      ,'Contact Home Address Town'=>'Sheffield'
	      ,'Contact Home Address Postal Code'=>'S2 4AH'
	      ,'Contact Home Address Country Name'=>'UK'
	      
	      );
  $contact=new Contact('find create',$data);
  break;
case 1:
default:
  $data=array(
	      'Contact Name'=>'Raul Perusquia'
	      ,'Contact Main Plain Email'=>'rulovico@gmail.com'
	      ,'Contact Main XHTML Telephone'=>'+44 114 277731'
	      ,'Contact Main XHTML FAX'=>'+44 114 277732'
	      ,'Contact Main XHTML Mobile'=>'+44 7111 112233'
	      ,'Contact Work Address Line 1'=>'BLOCK A, Parkwood Business Park, Parkwood Road'
	      ,'Contact Work Address Town'=>'Sheffield'
	      ,'Contact Work Address Postal Code'=>'S11 8AL '
	      ,'Contact Work Address Country Name'=>'UK'
	      ,'Contact Home Address Line 1'=>'Flat 43'
	      ,'Contact Home Address Line 2'=>'Jet Building'
	      ,'Contact Home Address Line 3'=>'35 St Marys Rd'
	      ,'Contact Home Address Town'=>'Sheffield'
	      ,'Contact Home Address Postal Code'=>'S2 4AH'
	      ,'Contact Home Address Country Name'=>'UK'
	      
	      );
  $contact=new Contact('find create',$data);
  
//$data=array('Contact Name'=>'Raul Perusquia Flores');
//$contact->update($data);



}




}

?>