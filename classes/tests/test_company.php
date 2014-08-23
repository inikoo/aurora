<?php
/*
 Script: test_contacts.php
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

global $company;


test(1);

function  test($id){
  global $company;
  switch($id){
  case 1:
  default:
    $data=array(
		'Company Name'=>'Ancient Wisdom'
		,'Company Fiscal Name'=>'Ancient Wisdom Marketing Ltd'
		,'Company Tax Number'=>'764298589'
		,'Company Registration Number'=>'4108870'
		,'Company Main XHTML Telephone'=>'ex dir'
		,'Company Main XHTML FAX'=>'+44 (0) 114 2706571'
		,'Company Main Plain Email'=>'mail@ancientwisdom.biz'
		,'Company Address Line 1'=>'BLOCK B, Parkwood Business Park, Parkwood Road'
		,'Company Address Town'=>'Sheffield'
		,'Company Address Postal Code'=>'S3 8AL '
		,'Company Address Country Name'=>'UK'
		,'Company Main Contact Name'=>'Mr David Hardy'
		);
    

$company=new Company('find create auto',$data);
}

}

?>