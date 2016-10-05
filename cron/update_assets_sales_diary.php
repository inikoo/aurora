<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2016 at 23:37:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/aes.php';
require_once 'utils/new_fork.php';


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







$sql=sprintf('update `Product Data` set `Product Yesterday Acc Invoiced Amount`=`Product Today Acc Invoiced Amount` ,`Product Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Profit`=`Product Today Acc Profit`, `Product Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Ordered`=`Product Today Acc Quantity Ordered`, `Product Today Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Invoiced`=`Product Today Acc Quantity Invoiced`,`Product Today Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Delivered`=`Product Today Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Days On Sale`=`Product Today Acc Days On Sale`,`Product Today Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Days Available`=`Product Today Acc Days Available`,`Product Today Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Invoices`=`Product Today Acc Invoices`,`Product Today Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Customers`=`Product Today Acc Customers`,`Product Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Repeat Customers`=`Product Today Acc Repeat Customers`,`Product Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product DC Data` set `Product DC Yesterday Acc Invoiced Amount`=`Product DC Today Acc Invoiced Amount` ,`Product DC Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product DC Data` set `Product DC Yesterday Acc Profit`=`Product DC Today Acc Profit`, `Product DC Today Acc Profit`=0 ');$db->exec($sql);

$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Invoiced Amount`=`Product Category Today Acc Invoiced Amount` ,`Product Category Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Profit`=`Product Category Today Acc Profit`, `Product Category Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Ordered`=`Product Category Today Acc Quantity Ordered`, `Product Category Today Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Invoiced`=`Product Category Today Acc Quantity Invoiced`,`Product Category Today Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Delivered`=`Product Category Today Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Days On Sale`=`Product Category Today Acc Days On Sale`,`Product Category Today Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Days Available`=`Product Category Today Acc Days Available`,`Product Category Today Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Invoices`=`Product Category Today Acc Invoices`,`Product Category Today Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Customers`=`Product Category Today Acc Customers`,`Product Category Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Repeat Customers`=`Product Category Today Acc Repeat Customers`,`Product Category Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product Category DC Data` set `Product Category DC Yesterday Acc Invoiced Amount`=`Product Category DC Today Acc Invoiced Amount` ,`Product Category DC Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category DC Data` set `Product Category DC Yesterday Acc Profit`=`Product Category DC Today Acc Profit`, `Product Category DC Today Acc Profit`=0 ');$db->exec($sql);



$sql=sprintf('update `Part Data` set `Part Yesterday Acc Invoiced Amount`=`Part Today Acc Invoiced Amount` ,`Part Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Profit`=`Part Today Acc Profit`, `Part Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Required`=`Part Today Acc Required`, `Part Today Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Dispatched`=`Part Today Acc Dispatched`,`Part Today Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Keeping Days`=`Part Today Acc Keeping Days`,`Part Today Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc With Stock Days`=`Part Today Acc With Stock Days`,`Part Today Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Deliveries`=`Part Today Acc Deliveries`,`Part Today Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Customers`=`Part Today Acc Customers`,`Part Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Repeat Customers`=`Part Today Acc Repeat Customers`,`Part Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Invoiced Amount`=`Part Category Today Acc Invoiced Amount` ,`Part Category Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Profit`=`Part Category Today Acc Profit`, `Part Category Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Required`=`Part Category Today Acc Required`, `Part Category Today Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Dispatched`=`Part Category Today Acc Dispatched`,`Part Category Today Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Keeping Days`=`Part Category Today Acc Keeping Days`,`Part Category Today Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc With Stock Days`=`Part Category Today Acc With Stock Days`,`Part Category Today Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Deliveries`=`Part Category Today Acc Deliveries`,`Part Category Today Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Customers`=`Part Category Today Acc Customers`,`Part Category Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Repeat Customers`=`Part Category Today Acc Repeat Customers`,`Part Category Today Acc Repeat Customers`=0');$db->exec($sql);


$intervals=array('Month To Day', 'Quarter To Day', 'Year To Day', 'Week To Day', '1 Year', '1 Quarter','1 Month','1 Week');



foreach ($intervals as $interval) {

	$msg=new_housekeeping_fork('au_asset_sales',
		array(
			'type'=>'update_products_sales_data',
			'interval'=>$interval
		),
		$account->get('Account Code'));

	$msg=new_housekeeping_fork('au_asset_sales',
		array(
			'type'=>'update_parts_sales_data',
			'interval'=>$interval
		),
		$account->get('Account Code'));

	$msg=new_housekeeping_fork('au_asset_sales',
		array(
			'type'=>'update_part_categories_sales_data',
			'interval'=>$interval
		),
		$account->get('Account Code'));

	$msg=new_housekeeping_fork('au_asset_sales',
		array(
			'type'=>'update_product_categories_sales_data',
			'interval'=>$interval
		),
		$account->get('Account Code'));


}






?>
