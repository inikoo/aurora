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
include_once '../../class.DeliveryNote.php';
include_once '../../class.Order.php';

include_once '../../class.Customer.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$sql="truncate `Order Transaction Deal Bridge`;truncate `Order Deal Bridge`";
mysql_query($sql);





$sql=sprintf("select `Order Key` from `Order Dimension`  ");
//print $sql;
$res2=mysql_query($sql);
while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
	$order=new Order($row2['Order Key']);
	$order->get_allowances();
	$sql=sprintf("select `Order Transaction Total Discount Amount`,`Product ID`,`Product Family Key`,`Product Key`,`Order Transaction Fact Key`,`Order Key`,`Order Transaction Total Discount Amount`/`Order Transaction Gross Amount` as fraction from  `Order Transaction Fact`  where `Order Transaction Total Discount Amount`>0  and `Order Transaction Gross Amount`>0 and `Order Key`=%d",
		$order->id
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




		$discount_factor=$row['fraction'];

		$deal_component_key=0;
		if (array_key_exists($row['Product Family Key'],$order->allowance['Family Percentage Off'])) {

			$discount_factor_lower_limit=$discount_factor-0.01;
			$discount_factor_upper_limit=$discount_factor+0.01;
			

			if (
			$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']>=$discount_factor_lower_limit
			and
			$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']<=$discount_factor_upper_limit
			
			
			) {
				$deal_component_key=$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Component Key'];
			}

		}

		if ($deal_component_key) {



			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)"
				,$row['Order Transaction Fact Key']
				,$order->id

				,$row['Product Key']
				,$row['Product ID']
				,$row['Product Family Key']

				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Campaign Key']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Key']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Component Key']

				,prepare_mysql($order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Info'])
				,$row['Order Transaction Total Discount Amount']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']
			);
			mysql_query($sql);
			//print "$sql\n";

		}else {


		}



	}



	$order->update_deal_bridge_from_assets_deals();
	$order->update_deals_usage();
}




?>
