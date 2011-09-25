<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
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

$sql="select * from `Supplier Product Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $supplier_product=new SupplierProduct($row['Supplier Product Key']);
    $supplier_product->update_up_today_sales();
    $supplier_product->update_interval_sales();
    $supplier_product->update_last_period_sales();

}

$sql="select * from `Supplier Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $supplier=new Supplier($row['Supplier Key']);
    $supplier->update_up_today_sales();
    $supplier->update_interval_sales();
    $supplier->update_last_period_sales();

}
exit;

$sql="select * from `Product Family Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

    $family=new Family($row['Product Family Key']);
    $family->update_up_today_sales();
    $family->update_interval_sales();
    $family->update_last_period_sales();

}



$sql="select * from `Store Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


    $store=new Store($row['Store Key']);
    $store->update_up_today_sales();
    $store->update_customer_activity_interval();
    $store->update_interval_sales();
    $store->update_last_period_sales();

}






mysql_free_result($result);
$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


    $category=new Category($row['Category Key']);
    $category->update_invoice_category_up_today_sales();
    $category->update_invoice_category_last_period_sales();
    $category->update_invoice_category_interval_sales();

}


?>
