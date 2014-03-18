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
	
	$part->update_available_forecast();
	$part->update_used_in();
	$part->update_main_state();
	$part->update_stock_state();
	$contador++;
$lap_time1=date('U');
//print 'Part Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

}
$lap_time1=date('U');
print date('r')." Part\n";

print 'Part Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \n";




$sql="select count(*) as total from `Product Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;


$sql="select `Product ID` from `Product Dimension` where `Product ID`=1860";
$sql="select `Product ID` from `Product Dimension` ";

$lap_time0=date('U');
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$product=new Product('pid',$row['Product ID']);
	$product->update_availability();
	$product->update_up_today_sales();
	$product->update_interval_sales();
	$product->update_last_period_sales();
	$product->update_parts();
	$contador++;
	$lap_time1=date('U');
//print 'P Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

}
$lap_time1=date('U');
print date('r')." Product\n";

print 'P Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";



$contador=0;
$lap_time0=date('U');
$sql="select * from `Store Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$store=new Store($row['Store Key']);
	$store->update_up_today_sales();
	$store->update_customer_activity_interval();
	$store->update_interval_sales();
	$store->update_last_period_sales();
	$store->update_orders();
	$contador++;
}
$lap_time1=date('U');
print 'Store Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";



$sql="select count(*) as total from `Product Family Dimension`  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time0=date('U');
$sql="select * from `Product Family Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$family=new Family($row['Product Family Key']);
	$family->update_product_data();
	$family->update_up_today_sales();
	$family->update_interval_sales();
	$family->update_last_period_sales();
	$contador++;
	//$lap_time1=date('U');
	//print 'Fam '.percentage($contador,$total,3)."  cc  ".($lap_time1-$lap_time0)."s  ".($lap_time1-$lap_time0)/$contador." cc  \r";
}
$lap_time1=date('U');
print 'Fam Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";



$sql="select count(*) as total from `Product Department Dimension`  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;

$sql="select * from `Product Department Dimension`  ";
$lap_time0=date('U');
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$department=new Department($row['Product Department Key']);


	$department->update_up_today_sales();
	$department->update_interval_sales();
	$department->update_last_period_sales();


	//$department->update_sales_default_currency();

	$department->update_customers();
	$department->update_product_data();
	$department->update_families();


	$contador++;
	print 'Dept '.percentage($contador,$total,3)."\r";
}
$lap_time1=date('U');
print 'Dept Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";




//exit;


$sql="select count(*) as total from  `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;
$lap_time0=date('U');
$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
	$category->update_invoice_category_up_today_sales();
	$category->update_invoice_category_interval_sales();
	$category->update_invoice_category_last_period_sales();
	$category->update_number_of_subjects();
	$category->update_no_assigned_subjects();
	$contador++;
	//print 'Inv Cat '.percentage($contador,$total,3)."\r";
}
$lap_time1=date('U');
print 'Cat Inv Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";




exit;

$sql="select count(*) as total from `Product History Dimension`  ";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;












$sql="select * from `Product History Dimension` PH  order by `Product Key` desc  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$product=new Product('id',$row['Product Key']);

	if ($product->id) {

		$product->update_up_today_historic_key_sales();
		$product->update_interval_historic_key_sales();
		$product->update_last_period_historic_key_sales();

		if (!array_key_exists('Product Code',$product->data)) {
			print_r($product);

		}

	$contador++;

	print 'PH '.percentage($contador,$total,3)."\r";
	}


}




$sql="select count(*) as total from `Supplier Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;

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
	print 'S '.percentage($contador,$total,3)."\r";

}
$sql="select count(*) as total from `Category Dimension` where `Category Subject`='Supplier'";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;

$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Supplier' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	$category->update_supplier_category_previous_years_data();
	$contador++;
	print 'SCat '.percentage($contador,$total,3)."\r";
}


$sql="select count(*) as total from `Supplier Product Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;


$sql="select * from `Supplier Product Dimension`";
$result=mysql_query($sql);

while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
	$supplier_product->update_up_today_sales();
	$supplier_product->update_interval_sales();
	$supplier_product->update_last_period_sales();
	$supplier_product->update_previous_years_data();
	//print "Supplier Product ".$supplier_product->pid."\t\t\r";
	$contador++;
	print 'SP '.percentage($contador,$total,3)."\r";
}




?>
