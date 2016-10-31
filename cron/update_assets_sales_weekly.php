<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 October 2016 at 09:54:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/aes.php';
require_once 'utils/new_fork.php';
require_once 'conf/timeseries.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


require_once 'class.Product.php';
require_once 'class.Category.php';
require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Part.php';


$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

//shortcuts($db);





$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_stores_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));
	
$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_invoices_categories_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));	



$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_products_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));

$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_parts_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));

$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_part_categories_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));

$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_product_categories_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));


$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_suppliers_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));
	
	
$msg=new_housekeeping_fork('au_asset_sales',
	array(
		'type'=>'update_supplier_categories_sales_data',
		'interval'=>'Last Week',
		'mode'=>array(true, true)
	),
	$account->get('Account Code'));
	
	
function shortcuts($db){

$sql=sprintf('update `Store Data` set `Store Last Week Acc Invoiced Discount Amount`=`Store Week To Day Acc Invoiced Discount Amount` ,`Store Week To Day Acc Invoiced Discount Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Invoiced Amount`=`Store Week To Day Acc Invoiced Amount` ,`Store Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Profit`=`Store Week To Day Acc Profit` ,`Store Week To Day Acc Profit`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Orders`=`Store Week To Day Acc Orders` ,`Store Week To Day Acc Orders`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Invoices`=`Store Week To Day Acc Customers` ,`Store Week To Day Acc Invoices`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Replacements`=`Store Week To Day Acc Replacements` ,`Store Week To Day Acc Replacements`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Delivery Notes`=`Store Week To Day Acc Delivery Notes` ,`Store Week To Day Acc Delivery Notes`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Refunds`=`Store Week To Day Acc Refunds` ,`Store Week To Day Acc Refunds`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Contacts`=`Store Week To Day Acc Contacts` ,`Store Week To Day Acc Contacts`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Customers`=`Store Week To Day Acc Customers` ,`Store Week To Day Acc Customers`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Repeat Customers`=`Store Week To Day Acc Repeat Customers` ,`Store Week To Day Acc Repeat Customers`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Lost Contacts`=`Store Week To Day Acc Lost Contacts` ,`Store Week To Day Acc Lost Contacts`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Lost Customers`=`Store Week To Day Acc Lost Customers` ,`Store Week To Day Acc Lost Customers`=0  ');$db->exec($sql);
$sql=sprintf('update `Store Data` set `Store Last Week Acc Dispatch Time`=`Store Week To Day Acc Dispatch Time` ,`Store Week To Day Acc Dispatch Time`=0  ');$db->exec($sql);


$sql=sprintf('update `Store DC Data` set `Store DC Last Week Acc Invoiced Discount Amount`=`Store DC Week To Day Acc Invoiced Discount Amount` ,`Store DC Week To Day Acc Invoiced Discount Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Store DC Data` set `Store DC Last Week Acc Invoiced Amount`=`Store DC Week To Day Acc Invoiced Amount` ,`Store DC Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Store DC Data` set `Store DC Last Week Acc Profit`=`Store DC Week To Day Acc Profit` ,`Store DC Week To Day Acc Profit`=0  ');$db->exec($sql);



$sql=sprintf('update `Invoice Category Data` set `Invoice Category Last Week Acc Discount Amount`=`Invoice Category Week To Day Acc Discount Amount` ,`Invoice Category Week To Day Acc Discount Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category Data` set `Invoice Category Last Week Acc Amount`=`Invoice Category Week To Day Acc Amount` ,`Invoice Category Week To Day Acc Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category Data` set `Invoice Category Last Week Acc Refunded Amount`=`Invoice Category Week To Day Acc Refunded Amount` ,`Invoice Category Week To Day Acc Refunded Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category Data` set `Invoice Category Last Week Acc Invoices`=`Invoice Category Week To Day Acc Invoices` ,`Invoice Category Week To Day Acc Invoices`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category Data` set `Invoice Category Last Week Acc Refunds`=`Invoice Category Week To Day Acc Refunds` ,`Invoice Category Week To Day Acc Refunds`=0  ');$db->exec($sql);

$sql=sprintf('update `Invoice Category DC Data` set `Invoice Category DC Last Week Acc Discount Amount`=`Invoice Category DC Week To Day Acc Discount Amount` ,`Invoice Category DC Week To Day Acc Discount Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category DC Data` set `Invoice Category DC Last Week Acc Amount`=`Invoice Category DC Week To Day Acc Amount` ,`Invoice Category DC Week To Day Acc Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category DC Data` set `Invoice Category DC Last Week Acc Refunded Amoun`=`Invoice Category DC Week To Day Acc Refunded Amoun` ,`Invoice Category DC Week To Day Acc Refunded Amoun`=0  ');$db->exec($sql);
$sql=sprintf('update `Invoice Category DC Data` set `Invoice Category DC Last Week Acc Profit`=`Invoice Category DC Week To Day Acc Profit` ,`Invoice Category DC Week To Day Acc Profit`=0  ');$db->exec($sql);



$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Invoiced Amount`=`Supplier Week To Day Acc Invoiced Amount` ,`Store Supplier To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Profit`=`Supplier Week To Day Acc Profit` ,`Store Supplier To Day Acc Profit`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Required`=`Supplier Week To Day Acc Required` ,`Store Supplier To Day Acc Required`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Dispatched`=`Supplier Week To Day Acc Dispatched` ,`Store Supplier To Day Acc Dispatched`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Keeping Days`=`Supplier Week To Day Acc Keeping Days` ,`Store Supplier To Day Acc Keeping Days`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc With Stock Days`=`Supplier Week To Day Acc With Stock Days` ,`Store Supplier To Day Acc With Stock Days`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Deliveries`=`Supplier Week To Day Acc Deliveries` ,`Store Supplier To Day Acc Deliveries`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Data` set `Supplier Last Week Acc Repeat Customers`=`Supplier Week To Day Acc Repeat Customers` ,`Store Supplier To Day Acc Repeat Customers`=0  ');$db->exec($sql);


$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Invoiced Amount`=`Supplier Category Week To Day Acc Invoiced Amount` ,`Store Supplier Category To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Profit`=`Supplier Category Week To Day Acc Profit` ,`Store Supplier Category To Day Acc Profit`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Required`=`Supplier Category Week To Day Acc Required` ,`Store Supplier Category To Day Acc Required`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Dispatched`=`Supplier Category Week To Day Acc Dispatched` ,`Store Supplier Category To Day Acc Dispatched`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Keeping Days`=`Supplier Category Week To Day Acc Keeping Days` ,`Store Supplier Category To Day Acc Keeping Days`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc With Stock Days`=`Supplier Category Week To Day Acc With Stock Days` ,`Store Supplier Category To Day Acc With Stock Days`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Deliveries`=`Supplier Category Week To Day Acc Deliveries` ,`Store Supplier Category To Day Acc Deliveries`=0  ');$db->exec($sql);
$sql=sprintf('update `Supplier Category Data` set `Supplier Category Last Week Acc Repeat Customers`=`Supplier Category Week To Day Acc Repeat Customers` ,`Store Supplier Category To Day Acc Repeat Customers`=0  ');$db->exec($sql);



$sql=sprintf('update `Product Data` set `Product Last Week Acc Invoiced Amount`=`Product Week To Day Acc Invoiced Amount` ,`Product Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Profit`=`Product Week To Day Acc Profit`, `Product Week To Day Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Quantity Ordered`=`Product Week To Day Acc Quantity Ordered`, `Product Week To Day Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Quantity Invoiced`=`Product Week To Day Acc Quantity Invoiced`,`Product Week To Day Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Quantity Delivered`=`Product Week To Day Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Days On Sale`=`Product Week To Day Acc Days On Sale`,`Product Week To Day Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Days Available`=`Product Week To Day Acc Days Available`,`Product Week To Day Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Invoices`=`Product Week To Day Acc Invoices`,`Product Week To Day Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Customers`=`Product Week To Day Acc Customers`,`Product Week To Day Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Last Week Acc Repeat Customers`=`Product Week To Day Acc Repeat Customers`,`Product Week To Day Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product DC Data` set `Product DC Last Week Acc Invoiced Amount`=`Product DC Week To Day Acc Invoiced Amount` ,`Product DC Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product DC Data` set `Product DC Last Week Acc Profit`=`Product DC Week To Day Acc Profit`, `Product DC Week To Day Acc Profit`=0 ');$db->exec($sql);

$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Invoiced Amount`=`Product Category Week To Day Acc Invoiced Amount` ,`Product Category Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Profit`=`Product Category Week To Day Acc Profit`, `Product Category Week To Day Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Quantity Ordered`=`Product Category Week To Day Acc Quantity Ordered`, `Product Category Week To Day Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Quantity Invoiced`=`Product Category Week To Day Acc Quantity Invoiced`,`Product Category Week To Day Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Quantity Delivered`=`Product Category Week To Day Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Days On Sale`=`Product Category Week To Day Acc Days On Sale`,`Product Category Week To Day Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Days Available`=`Product Category Week To Day Acc Days Available`,`Product Category Week To Day Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Invoices`=`Product Category Week To Day Acc Invoices`,`Product Category Week To Day Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Customers`=`Product Category Week To Day Acc Customers`,`Product Category Week To Day Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Last Week Acc Repeat Customers`=`Product Category Week To Day Acc Repeat Customers`,`Product Category Week To Day Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product Category DC Data` set `Product Category DC Last Week Acc Invoiced Amount`=`Product Category DC Week To Day Acc Invoiced Amount` ,`Product Category DC Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category DC Data` set `Product Category DC Last Week Acc Profit`=`Product Category DC Week To Day Acc Profit`, `Product Category DC Week To Day Acc Profit`=0 ');$db->exec($sql);

$sql=sprintf('update `Part Data` set `Part Last Week Acc Invoiced Amount`=`Part Week To Day Acc Invoiced Amount` ,`Part Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Profit`=`Part Week To Day Acc Profit`, `Part Week To Day Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Required`=`Part Week To Day Acc Required`, `Part Week To Day Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Dispatched`=`Part Week To Day Acc Dispatched`,`Part Week To Day Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Keeping Days`=`Part Week To Day Acc Keeping Days`,`Part Week To Day Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc With Stock Days`=`Part Week To Day Acc With Stock Days`,`Part Week To Day Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Deliveries`=`Part Week To Day Acc Deliveries`,`Part Week To Day Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Customers`=`Part Week To Day Acc Customers`,`Part Week To Day Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Last Week Acc Repeat Customers`=`Part Week To Day Acc Repeat Customers`,`Part Week To Day Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Invoiced Amount`=`Part Category Week To Day Acc Invoiced Amount` ,`Part Category Week To Day Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Profit`=`Part Category Week To Day Acc Profit`, `Part Category Week To Day Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Required`=`Part Category Week To Day Acc Required`, `Part Category Week To Day Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Dispatched`=`Part Category Week To Day Acc Dispatched`,`Part Category Week To Day Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Keeping Days`=`Part Category Week To Day Acc Keeping Days`,`Part Category Week To Day Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc With Stock Days`=`Part Category Week To Day Acc With Stock Days`,`Part Category Week To Day Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Deliveries`=`Part Category Week To Day Acc Deliveries`,`Part Category Week To Day Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Customers`=`Part Category Week To Day Acc Customers`,`Part Category Week To Day Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Last Week Acc Repeat Customers`=`Part Category Week To Day Acc Repeat Customers`,`Part Category Week To Day Acc Repeat Customers`=0');$db->exec($sql);

}







?>
