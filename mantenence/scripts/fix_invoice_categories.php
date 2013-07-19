<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Customer.php';
include_once '../../class.Invoice.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


//$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' ");
//mysql_query($sql);

$sql="select * from `Invoice Dimension` order by `Invoice Key` desc   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$invoice=new Invoice ($row['Invoice Key']);
	
	$customer=new Customer($invoice->data['Invoice Customer Key']);
	
	
	
	$customer_level=$customer->data['Customer Level Type'];
	
	
	$invoice->data['Invoice Customer Level Type']=$customer_level;
	
	$sql=sprintf("update `Invoice Dimension` set `Invoice Customer Level Type`=%s where `Invoice Key`=%d ",
	prepare_mysql($customer_level),
	$invoice->id
	
	);
	mysql_query($sql);
	
	$invoice->categorize();
	print $invoice->id."\r";
}


?>
