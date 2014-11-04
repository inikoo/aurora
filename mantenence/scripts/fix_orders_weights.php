<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Order.php';

include_once '../../common_units_functions.php';
include_once '../../common_geometry_functions.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql="select `Order Key` from `Order Dimension` where `Order Current Dispatch State`='In Process' limit 1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$order=new Order($row['Order Key']);
	$sql=sprintf("select `Order Transaction Fact Key`, `Product Package Weight`, P.`Product ID`,`Order Bonus Quantity`,`Order Quantity` from `Order Transaction Fact` OTF left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  where `Order Key`=%d ",$row['Order Key']);

	$result2=mysql_query($sql);
	while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

		if ($row2['Product ID']) {
			$estimated_weight=$row2['Product Package Weight']*($row2['Order Bonus Quantity']+$row2['Order Quantity']);
			$sql=sprintf("update `Order Transaction Fact` set `Estimated Weight`=%f where `Order Transaction Fact Key`=%d",
				$estimated_weight,
				$row2['Order Transaction Fact Key']
			);

			//print "$sql\n";
		}

	}

	$order->get_items_totals_by_adding_transactions();

	$order->update_totals_from_order_transactions();
	
	
	foreach ($order->get_delivery_notes_objects() as $dn) {
		$dn->update_item_totals();
	}

}







?>
