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
include_once '../../class.DeliveryNote.php';
include_once '../../class.Order.php';
include_once '../../class.DealCampaign.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';


$sql="truncate `Order Transaction Deal Bridge`";
mysql_query($sql);

$sql="truncate `Order Deal Bridge`";
mysql_query($sql);



$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Key`='1807670' ");
$sql=sprintf("select `Order Key` from `Order Dimension` order by `Order Date` desc ");

$res2=mysql_query($sql);
while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
	$order=new Order($row2['Order Key']);
	$order->get_allowances_from_family_trigger();
	$date=$order->data['Order Date'];


//print "------------------\n";
	$sql=sprintf("select `Order Transaction Fact Key`,`Order Transaction Total Discount Amount`,`Product ID`,`Product Family Key`,`Product Key`,`Order Transaction Fact Key`,`Order Key`,`Order Transaction Total Discount Amount`/`Order Transaction Gross Amount` as fraction from  `Order Transaction Fact`  where `Order Transaction Total Discount Amount`>0  and `Order Transaction Gross Amount`>0 and `Order Key`=%d ",
		$order->id
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		//print_r($row);

		$discount_factor=$row['fraction'];

		$deal_component_key=0;

//print_r($order->allowance);

		if (isset($order->allowance['Percentage Off']) and is_array($order->allowance['Percentage Off']) and array_key_exists($row['Order Transaction Fact Key'],$order->allowance['Percentage Off'])) {
			//print_r($order->allowance);

			$discount_factor_lower_limit=$discount_factor-0.01;
			$discount_factor_upper_limit=$discount_factor+0.01;


			if (
				$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Percentage Off']>=$discount_factor_lower_limit
				and
				$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Percentage Off']<=$discount_factor_upper_limit


			) {



				$deal_component_key=$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Component Key'];
			}

		}

		if ($deal_component_key) {

			$deal_info=$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Info'];
			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)"
				,$row['Order Transaction Fact Key']
				,$order->id

				,$row['Product Key']
				,$row['Product ID']
				,$row['Product Family Key']

				,$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Campaign Key']
				,$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Key']
				,$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Component Key']

				,prepare_mysql($deal_info)
				,$row['Order Transaction Total Discount Amount']
				,$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Percentage Off']
			);

			// print "$sql\n";

			mysql_query($sql);



			$sql=sprintf("update `Deal Component Dimension` set `Deal Component Begin Date`=%s  where   (`Deal Component Begin Date` is NULL or `Deal Component Begin Date`='' or `Deal Component Begin Date`>%s)  and `Deal Component Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Component Key']
			);
			mysql_query($sql);


			$sql=sprintf("update `Deal Dimension` set `Deal Begin Date`=%s  where   (`Deal Begin Date` is NULL or `Deal Begin Date`='' or `Deal Begin Date`>%s)  and `Deal Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Key']
			);
			mysql_query($sql);

			$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Valid From`=%s  where   (`Deal Campaign Valid From` is NULL or `Deal Campaign Valid From`='' or `Deal Campaign Valid From`>%s)  and `Deal Campaign Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Campaign Key']
			);
			mysql_query($sql);


			$deal=new Deal($order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Key']);
			$deal->update_usage();

			$campaign=new DealCampaign($order->allowance['Percentage Off'][$row['Order Transaction Fact Key']]['Deal Campaign Key']);
			$campaign->update_usage();

		}else {



			$deal_info=percentage($row['fraction'],1).' Off';

			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)",
				$row['Order Transaction Fact Key'],
				$order->id,
				$row['Product Key'],
				$row['Product ID'],
				$row['Product Family Key'],
				0,
				0,
				0,
				prepare_mysql($deal_info,false),
				$row['Order Transaction Total Discount Amount'],
				$row['fraction']
			);

			mysql_query($sql);
			
			


		}



	}


$sql=sprintf("select `Product ID`,`Product Family Key`,`Product Key`,`Order Transaction Fact Key`,`Order Key`,`Order Bonus Quantity`  from  `Order Transaction Fact`  where  `Order Bonus Quantity`>=1 and `Order Key`=%d",
		$order->id
	);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	
			if (isset($order->allowance['Get Free']) and is_array($order->allowance['Get Free']) and array_key_exists($row['Product ID'],$order->allowance['Get Free'])) {
			
				$deal_info=$order->allowance['Get Free'][$row['Product ID']]['Deal Info'];
			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,0,0,%d)"
				,$row['Order Transaction Fact Key']
				,$order->id

				,$row['Product Key']
				,$row['Product ID']
				,$row['Product Family Key']

				,$order->allowance['Get Free'][$row['Product ID']]['Deal Campaign Key']
				,$order->allowance['Get Free'][$row['Product ID']]['Deal Key']
				,$order->allowance['Get Free'][$row['Product ID']]['Deal Component Key']

				,prepare_mysql($deal_info)
				,$row['Order Bonus Quantity']
				
			);

			// print "$sql\n";

			mysql_query($sql);



			$sql=sprintf("update `Deal Component Dimension` set `Deal Component Begin Date`=%s  where   (`Deal Component Begin Date` is NULL or `Deal Component Begin Date`='' or `Deal Component Begin Date`>%s)  and `Deal Component Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Get Free'][$row['Product ID']]['Deal Component Key']
			);
			mysql_query($sql);


			$sql=sprintf("update `Deal Dimension` set `Deal Begin Date`=%s  where   (`Deal Begin Date` is NULL or `Deal Begin Date`='' or `Deal Begin Date`>%s)  and `Deal Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Get Free'][$row['Product ID']]['Deal Key']
			);
			mysql_query($sql);

			$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Valid From`=%s  where   (`Deal Campaign Valid From` is NULL or `Deal Campaign Valid From`='' or `Deal Campaign Valid From`>%s)  and `Deal Campaign Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Get Free'][$row['Product ID']]['Deal Campaign Key']
			);
			mysql_query($sql);


			$deal=new Deal($order->allowance['Get Free'][$row['Product ID']]['Deal Key']);
			$deal->update_usage();

			$campaign=new DealCampaign($order->allowance['Get Free'][$row['Product ID']]['Deal Campaign Key']);
			$campaign->update_usage();

			
			}

	
	}

	$order->update_deal_bridge_from_assets_deals();
	$order->update_deals_usage();
}




?>
