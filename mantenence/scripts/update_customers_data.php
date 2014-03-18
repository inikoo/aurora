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

$sql="select count(*) as total from `Customer Dimension`  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time0=date('U');
$sql="select `Customer Key` from `Customer Dimension`   order by `Customer Net Balance` desc ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$customer=new Customer($row['Customer Key']);
	$contador++;
	//$customer->update_location_type();
	//$customer->update_web_data();
	//$customer->update_orders();
	//$customer->update_activity();
	//$customer->update_is_new();
	$customer->update_rankings();
	$lap_time1=date('U');
		print 'Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

	
	
}



$sql="select * from `Store Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$store=new Store($row['Store Key']);
	$store->update_interval_sales();
	$store->update_customers_data();

}


?>
