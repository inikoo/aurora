<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');
include_once('../../class.Invoice.php');

error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$sql="select * from `Invoice Dimension` where `Invoice Store Key` in (2,3)";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){


  $invoice=new Invoice($row['Invoice Key']);
  
  $invoice->categorize('save');

  $force_values=array(
		      'Invoice Items Net Amount'=>$invoice->data['Invoice Items Net Amount']
		      ,'Invoice Total Net Amount'=>$invoice->data['Invoice Total Net Amount']
		      ,'Invoice Total Tax Amount'=>$invoice->data['Invoice Total Tax Amount']
		      ,'Invoice Total Amount'=>$invoice->data['Invoice Total Amount']
		      );
  // print_r($force_values);
    $invoice->get_totals();
  // $invoice->get_totals($force_values);
  print $invoice->id."\r";
 }





?>