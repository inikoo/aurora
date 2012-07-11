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


$sql="select  `Invoice Currency Exchange`,`Invoice Key`  from `Invoice Dimension` where `Invoice Currency Exchange`!=1;  ";
$result=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("update `Order Transaction Fact` set `Invoice Currency Exchange Rate`=%f where `Invoice Key`=%d",$row['Invoice Currency Exchange'],$row['Invoice Key']);
	print "$sql\n";
	//mysql_query($sql);


//exit;
}

?>
