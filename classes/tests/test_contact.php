<?
/*
 Script: test_contact.php
 Tests for contact class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/


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

// Create a contact
//$data=array('Contact Name'=>'Raul Perusquia');
//$contact=new Contact('find create',$data);
//print_r($contact);


// Create a contact with email
$data=array(
	    'Contact Name'=>'Raul Perusquia'
	    ,'Contact Main Plain Email'=>'rulovico@gmail.com'
	    ,'Contact Main Telephone'=>'+44 114 277731'
	    ,'Contact Main FAX'=>'+44 114 277732'
	    );
$contact=new Contact('find create',$data);
print_r($contact);

?>