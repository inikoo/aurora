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

include_once '../../class.Customer.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error! can not connect with database server\n";
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



$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Public ID`='183939' ");
$sql=sprintf("select `Order Key` from `Order Dimension` order by `Order Date` desc ");

$res2=mysql_query($sql);
while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
	$order=new Order($row2['Order Key']);
	$order->allowance=array('Family Percentage Off'=>array(),'Get Free'=>array(),'Get Same Free'=>array(),'Credit'=>array(),'No Item Transaction'=>array());
	$order->deals=array('Family'=>array('Deal'=>false,'Terms'=>false,'Deal Multiplicity'=>0,'Terms Multiplicity'=>0));

	$order->get_allowances_from_department_trigger();
	$order->get_allowances_from_family_trigger();
	$order->get_allowances_from_product_trigger();
	$order->get_allowances_from_customer_trigger();
	$order->get_allowances_from_order_trigger();

	$date=$order->data['Order Date'];
	//print_r($order->allowance['Family Percentage Off']);



	$sql=sprintf("select `Order Transaction Total Discount Amount`,`Product ID`,`Product Family Key`,`Product Key`,`Order Transaction Fact Key`,`Order Key`,`Order Transaction Total Discount Amount`/`Order Transaction Gross Amount` as fraction from  `Order Transaction Fact`  where `Order Transaction Total Discount Amount`>0  and `Order Transaction Gross Amount`>0 and `Order Key`=%d",
		$order->id
		);
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		//print_r($row);

		$discount_factor=$row['fraction'];

		$deal_component_key=0;



		if (array_key_exists($row['Product Family Key'],$order->allowance['Family Percentage Off'])) {

			$discount_factor_lower_limit=$discount_factor-0.01;
			$discount_factor_upper_limit=$discount_factor+0.01;


			if ($order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']>=$discount_factor_lower_limit and $order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']<=$discount_factor_upper_limit) {



				$deal_component_key=$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Component Key'];
			}

		}

		if ($deal_component_key) {

			$deal_info=$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Info'];
			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)"
				,$row['Order Transaction Fact Key']
				,$order->id

				,$row['Product Key']
				,$row['Product ID']
				,$row['Product Family Key']

				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Campaign Key']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Key']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Component Key']

				,prepare_mysql($deal_info)
				,$row['Order Transaction Total Discount Amount']
				,$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Percentage Off']
				);
			mysql_query($sql);



			$sql=sprintf("update `Deal Component Dimension` set `Deal Component Begin Date`=%s  where   (`Deal Component Begin Date` is NULL or `Deal Component Begin Date`='' or `Deal Component Begin Date`>%s)  and `Deal Component Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Component Key']
				);
			mysql_query($sql);


			$sql=sprintf("update `Deal Dimension` set `Deal Begin Date`=%s  where   (`Deal Begin Date` is NULL or `Deal Begin Date`='' or `Deal Begin Date`>%s)  and `Deal Key`=%d",
				prepare_mysql($date),
				prepare_mysql($date),

				$order->allowance['Family Percentage Off'][$row['Product Family Key']]['Deal Key']
				);
			mysql_query($sql);



		}else {


		}



		$order->update_deal_bridge_from_assets_deals();
		$order->update_deals_usage();

		$sql=sprintf("select `Deal Campaign Key` from `Order Deal Bridge` where `Order Key`=%d group by `Deal Campaign Key`",$order->id);
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$campaign=new DealCampaign($row['Deal Campaign Key']);
			if($campaign->data['Deal Campaign Valid From']=='' or strtotime($campaign->data['Deal Campaign Valid From'].' +0:00')>strtotime($order->data['Order Date'].' +0:00')){
				$campaign->update(array('Deal Campaign Valid From'=>$order->data['Order Date']),'no_history');
			}
		}

		$sql=sprintf("select `Deal Component Key` from `Order Deal Bridge` where `Order Key`=%d group by `Deal Component Key`",$order->id);
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$deal_component=new DealComponent($row['Deal Component Key']);
			if($deal_component->data['Deal Component Begin Date']=='' or strtotime($deal_component->data['Deal Component Begin Date'].' +0:00')>strtotime($order->data['Order Date'].' +0:00')){
				$deal_component->update(array('Deal Component Begin Date'=>$order->data['Order Date']),'no_history');
			}
		}

		$sql=sprintf("select `Deal Key` from `Order Deal Bridge` where `Order Key`=%d group by `Deal Key`",$order->id);
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$deal=new Deal($row['Deal Key']);
			if($deal->data['Deal Begin Date']=='' or strtotime($deal->data['Deal Begin Date'].' +0:00')>strtotime($order->data['Order Date'].' +0:00')){
				$deal->update(array('Deal Begin Date'=>$order->data['Order Date']),'no_history');
			}
		}

	}
}




?>