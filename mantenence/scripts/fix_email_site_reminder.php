<?php

// @author Raul Perusquia <rulovico@gmail.com>
// Created: 13 February 2014 23:58:22 CET, Malaga Spain
//Copyright (c) 2014
include_once '../../app_files/db/dns.php';

include_once '../../class.Product.php';
include_once '../../class.User.php';
include_once '../../class.Customer.php';

include_once '../../class.Store.php';
include_once '../../class.Site.php';
include_once '../../class.EmailSiteReminder.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;




$sql=sprintf("select * from `Email Site Reminder Dimension`");
$res2=mysql_query($sql);
while ($row2=mysql_fetch_assoc($res2)) {
	$_user=new User($row2['User Key']);
	$product=new Product('pid',$row2['Trigger Scope Key']);
	$sql=sprintf("update `Email Site Reminder Dimension` set `Customer Name`=%s ,`Trigger Scope Name`=%s where `Email Site Reminder Key`=%d",
		prepare_mysql($_user->get_customer_name()),
		prepare_mysql($product->data['Product Code']),
		$row2['Email Site Reminder Key']
	);
	mysql_query($sql);
	//print "$sql\n";

}


?>
