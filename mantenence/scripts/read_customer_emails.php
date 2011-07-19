<?php

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
include_once('../../class.Node.php');
include_once('../../class.Customer.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';      

require_once("../../class.ReadEmail.php");
$host="{imap.gmail.com:993/imap/ssl/novalidate-cert}"; 
$login="raul@ancientwisdom.biz"; 
$password="ajolote11"; 
$savedirpath="" ; // attachement will save in same directory where scripts run othrwise give abs path
$jk=new ReadEmail(); // Creating instance of class####
//$jk->read_mailbox('customer_communication','Sent Messages'); // calling member function

$jk->read_customer_communications(1);

//$jk->read_mailbox('customer_communication',''); 
?>
