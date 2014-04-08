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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

//$sql="select * from `Supplier Product Dimension` where `Supplier Product ID`=963";

$start_time=date('U');

print date('r')." Start\n";





$sql="select count(*) as total from `Part Dimension`  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;

$lap_time0=date('U');
$sql="select `Part SKU` from `Part Dimension`   order by `Part SKU`   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$part->update_up_today_sales();

	$part->update_interval_sales();
	$part->update_last_period_sales();

	//$part->update_available_forecast();
	$part->update_used_in();
	$part->update_main_state();
	$part->update_stock_state();
	$contador++;
	$lap_time1=date('U');
	//print 'Part Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

$lap_time1=date('U');


print 'Part Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";



}
$lap_time1=date('U');
print date('r')." Part\n";

print 'Part Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";




$sql="select count(*) as total from `Category Dimension` where `Category Subject`='Part'  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;

$lap_time0=date('U');
$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Part' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	$category->update_part_category_status();
	$contador++;
	$lap_time1=date('U');
	//print 'cat Pa Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";
}
$lap_time1=date('U');
print date('r')." Cat Parts\n";

print 'cat Pa Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";





$sql="select count(*) as total from `Supplier Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time0=date('U');

$sql="select * from `Supplier Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$supplier=new Supplier($row['Supplier Key']);


	$supplier->update_previous_years_data();

	$supplier->update_products_info();
	$supplier->update_up_today_sales();
	$supplier->update_interval_sales();
	$supplier->update_last_period_sales();
	//print "Supplier ".$supplier->data['Supplier Code']."\r";
	$contador++;
	// print 'S '.percentage($contador,$total,3)."\r";
$lap_time1=date('U');
print 'Supplier '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

}

print date('r')." Supplier\n";

$lap_time1=date('U');
print 'Supplier '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";


$sql="select count(*) as total from `Category Dimension` where `Category Subject`='Supplier'";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time0=date('U');
$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Supplier' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	$category->update_supplier_category_previous_years_data();
	$contador++;
	//print 'SCat '.percentage($contador,$total,3)."\r";
	
	$lap_time1=date('U');

print 'Supplier cat'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

	
}
$lap_time1=date('U');
print date('r')." Supplier Cat\n";
print 'Supplier cat'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";


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
print 'Supplier Prod'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

	
	
}
$lap_time1=date('U');
print date('r')." Supplier Prod\n";

print 'Supplier Prod'.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";





?>
