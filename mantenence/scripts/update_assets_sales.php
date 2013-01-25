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


$sql="select `Part SKU` from `Part Dimension`   order by `Part SKU`   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);
	$part->update_used_in();
	$part->update_main_state();

	print 'SKU'. $part->sku."\r";
}

$sql="select `Product ID` from `Product Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$product=new Product('pid',$row['Product ID']);

	$product->update_parts();
	print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";
}



$sql="select `Part SKU` from `Part Dimension`   order by `Part SKU`   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$part->update_up_today_sales();
	
	$part->update_interval_sales();
	$part->update_last_period_sales();
	$part->update_available_forecast();
	$part->update_stock_state();
	print 'SKU'. $part->sku."\r";
}




//$sql="select `Product ID` from `Product Dimension` where `Product ID`=752";
//$sql="select `Product ID` from `Product Dimension`";
$sql="select `Product ID` from `Product Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$product=new Product('pid',$row['Product ID']);
	$product->update_availability();
	$product->update_up_today_sales();
	$product->update_interval_sales();
	$product->update_last_period_sales();
	$product->update_parts();
	print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";
}




$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Part' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	print "Category: ".$category->id."\t\t\r";
}


$sql="select `Part SKU` from `Part Dimension`   order by `Part SKU`   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$part=new Part('sku',$row['Part SKU']);

	$part->update_up_today_sales();
	
	$part->update_interval_sales();
	$part->update_last_period_sales();
	print 'SKU'. $part->sku."\r";
}






$sql="select * from `Supplier Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$supplier=new Supplier($row['Supplier Key']);
	$supplier->update_products_info();
	$supplier->update_up_today_sales();
	$supplier->update_interval_sales();
	$supplier->update_last_period_sales();
	print "Supplier ".$supplier->data['Supplier Code']."\r";
}


$sql="select * from `Supplier Product Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
	$supplier_product->update_up_today_sales();
	$supplier_product->update_interval_sales();
	$supplier_product->update_last_period_sales();
	print "Supplier Product ".$supplier_product->pid."\t\t\r";

}








$sql="select * from `Store Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$store=new Store($row['Store Key']);
	$store->update_up_today_sales();
	$store->update_customer_activity_interval();
	$store->update_interval_sales();
	$store->update_last_period_sales();
	$store->update_orders();

}






$sql="select * from `Product Family Dimension` where `Product Family Key`=4695";
$sql="select * from `Product Family Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$family=new Family($row['Product Family Key']);
	$family->update_product_data();
	$family->update_up_today_sales();
	$family->update_interval_sales();
	$family->update_last_period_sales();
	print "Family ".$family->data['Product Family Code']."\r";

}



$sql="select * from `Product Department Dimension` where `Product Department Key`=23 ";
$sql="select * from `Product Department Dimension`  ";

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


	print "Department ".$department->data['Product Department Code']."\r";
}








$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
		$category->update_invoice_category_up_today_sales();
		$category->update_invoice_category_interval_sales();
		$category->update_invoice_category_last_period_sales();
	$category->update_number_of_subjects();
		$category->update_no_assigned_subjects();
	print "Category ".$category->id."\t\t\n";
}









$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Supplier' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$category=new Category($row['Category Key']);
	$category->update_up_today();
	$category->update_last_period();
	$category->update_last_interval();
	print "Category ".$category->id."\t\t\n";
}



$sql="select * from `Product History Dimension` PH  order by `Product Key` desc  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)   ) {
	$product=new Product('id',$row['Product Key']);
	
	if($product->id){
	
	$product->update_up_today_historic_key_sales();
	$product->update_interval_historic_key_sales();
	$product->update_last_period_historic_key_sales();
	
	if(!array_key_exists('Product Code',$product->data)){
	print_r($product); 

	}
	
	
	print "PH ".$row['Product Key']."\t\t ".$product->data['Product Code']." \r";
}else{

print_r($product); 
exit;

}


}



?>
