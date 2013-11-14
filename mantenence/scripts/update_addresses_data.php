<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Address.php';
include_once '../../class.Customer.php';
include_once '../../class.Supplier.php';
include_once '../../class.Store.php';
include_once '../../common_functions.php';


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

$sql="select * from `Address Dimension` order by `Address Key`   ";
$result=mysql_query($sql);
$num_rows = mysql_num_rows($result);
$count=0;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
$count++;
	$address=new Address($row['Address Key']);
	$address->update_parents();
	
	print percentage($count,$num_rows,3)."\r";
	

}

?>
