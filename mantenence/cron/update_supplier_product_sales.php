<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Category.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

//$sql="select * from `Supplier Product Dimension` where `Supplier Product ID`=963";

$start_time=date('U');

print date('r')." sup producs Start\n";





$sql="select count(*) as total from `Supplier Product Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time1=date('U');

$sql="select * from `Supplier Product Dimension`";
$result=mysql_query($sql);
$lap_time0=date('U');
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
	$supplier_product->update_up_today_sales();
	$supplier_product->update_interval_sales();
	$supplier_product->update_last_period_sales();
	$supplier_product->update_previous_years_data();



	//print "Supplier Product ".$supplier_product->pid."\t\t\r";
	$contador++;
	//print 'SP '.percentage($contador,$total,3)."\r";

	$lap_time1=date('U');
	//print 'Supplier Prod'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";



}
$lap_time1=date('U');

print 'Supplier Prod'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";

print date('r')." end Supplier Prod\n";




?>
