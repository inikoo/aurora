<?php
//@author Raul Perusquia <rulovico@gmail.com>
// Created 1 August 2014 14:05:17 BST, Nottingham, UK
//Copyright (c) 2014 Inikoo Ltd
include_once '../../app_files/db/dns.php';
include_once '../../class.Address.php';
include_once '../../class.Customer.php';
include_once '../../class.Supplier.php';
include_once '../../class.Store.php';
include_once '../../common_functions.php';
include_once '../../set_locales.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


//where `Customer Key`=71045 

$sql="select `Customer Key` from `Customer Dimension`   order by `Customer Key`  desc ";
$result=mysql_query($sql);
$num_rows = mysql_num_rows($result);
$count=0;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$count++;
	$customer=new Customer($row['Customer Key']);


if(!$customer->data['Customer Main Address Key'] ){

	$address_data=array(
			'Customer Address Line 1'=>'',
			'Customer Address Town'=>'',
			'Customer Address Line 2'=>'',
			'Customer Address Line 3'=>'',
			'Customer Address Postal Code'=>'',
			'Customer Address Country Code'=>'',
			'Customer Address Country Name'=>'',
			'Customer Address Country First Division'=>'',
			'Customer Address Country Second Division'=>''
		);



		
		$address_data['Address Input Format']='3 Line';
		$anon_address=new Address();
		$anon_address->create($address_data);

$customer->create_contact_address_bridge($anon_address->id);
$anon_address->update_parents();

}





	if ( !$customer->data['Customer Billing Address Key'] or  ($customer->data['Customer Billing Address Link']=='None'  and $customer->data['Customer Main Address Key']==$customer->data['Customer Billing Address Key']) ) {
		$sql=sprintf("update `Customer Dimension` set `Customer Billing Address Link`='Contact' where `Customer Key`=%d",
			$customer->id
		);
		//print "$sql\n";
		mysql_query($sql);

	}

	$customer->get_data('id',$customer->id);
	if ($customer->data['Customer Billing Address Link']=='None') {
		$billing_address_key=$customer->data['Customer Billing Address Key'];
	}else {
		$billing_address_key=$customer->data['Customer Main Address Key'];
	}

	if ($billing_address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Customer','Billing',%d,%d)  ",
			$customer->id,
			$billing_address_key

		);
		mysql_query($sql);
		//print $sql;
		$customer->update_principal_billing_address($billing_address_key);


	}else {
	print_r($customer->data);
	
	print $customer->id."\n";
	
		exit("no billing addresss key\n");
	}





	if ( !$customer->data['Customer Main Delivery Address Key']  or  ($customer->data['Customer Delivery Address Link']=='Billing' or $customer->data['Customer Delivery Address Link']=='None') and $customer->data['Customer Main Address Key']==$customer->data['Customer Main Delivery Address Key'] ) {
		$sql=sprintf("update `Customer Dimension` set `Customer Delivery Address Link`='Contact' where `Customer Key`=%d",
			$customer->id
		);
		//print "$sql\n";
		mysql_query($sql);
	}

	$customer->get_data('id',$customer->id);


	if ($customer->data['Customer Delivery Address Link']=='Billing') {
		$delivery_address_key=$customer->data['Customer Billing Address Key'];
	}elseif ($customer->data['Customer Delivery Address Link']=='None') {
		$delivery_address_key=$customer->data['Customer Main Delivery Address Key'];
	}else{
	$delivery_address_key=$customer->data['Customer Main Address Key'];
	}



	if ($delivery_address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Customer','Shipping',%d,%d)  ",
			$customer->id,
			$delivery_address_key

		);
		mysql_query($sql);

		$customer->update_principal_delivery_address($delivery_address_key);

	}else {
		exit("no delivery addresss key\n");
	}




}

?>
