<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2016 at 18:04:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


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



require_once 'utils/get_addressing.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Address.php';




$sql=sprintf('select `Customer Key` from `Customer Dimension` where `Customer Key`=210902 order by `Customer Key` desc ');
$sql=sprintf('select `Customer Key` from `Customer Dimension` order by `Customer Key` desc ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$customer=new Customer($row['Customer Key']);

		$store=new Store($customer->get('Store Key'));
		$default_country=$store->get('Store Home Country Code 2 Alpha');

		$recipient=$customer->get('Main Contact Name');
		$organization=$customer->get('Company Name');


		if ($organization==$recipient) {
			$organization='';
		}

		if (!$customer->get('Customer Main Address Key')) {
			continue;
		}

		$address_fields=address_fields($customer->get('Customer Main Address Key'), $recipient, $organization, $default_country);



		$customer->update_address('Contact', $address_fields);

		$location=$customer->get('Contact Address Locality');
		if ($location=='') {
			$location=$customer->get('Contact Address Administrative Area');
		}
		if ($location=='') {
			$location=$customer->get('Customer Contact Address Postal Code');
		}


		$customer->update(array(
				'Customer Location'=>trim(sprintf('<img src="/art/flags/%s.gif" title="%s"> %s',
						strtolower($customer->get('Contact Address Country 2 Alpha Code')),
						$customer->get('Contact Address Country 2 Alpha Code'),
						$location))
			), 'no_history');





		$address=new _Address($customer->get('Customer Main Delivery Address Key'));




		if ($address->data['Address Contact']!='') {
			$address_contact=trim(preg_replace('/\s+/', ' ',  $address->data['Address Contact']));
			if (strtolower($address_contact)==strtolower($recipient)) {

			}elseif (strtolower($address_contact)==strtolower($organization)) {

			}else {
				print $customer->id." DEL ==================\n";
				print "->$recipient<-\n";
				print "->$organization<-\n";
				print "->$address_contact<-\n";
				$recipient=$address_contact;
				$organization='';

			}
		}
		$address_fields=address_fields($customer->get('Customer Main Delivery Address Key'), $recipient, $organization, $default_country);
		$customer->update_address('Delivery', $address_fields);





		$fiscal_name=get_fiscal_name($customer);
		$organization=$fiscal_name;
		$recipient=$customer->data['Customer Main Contact Name'];
		if ($fiscal_name=='') {
			$organization=$customer->get('Company Name');
		}else {
			$organization=$fiscal_name;
		}
		if ($organization==$recipient) {
			$organization='';
		}

		$recipient=trim(preg_replace('/\s+/', ' ',  $recipient));
		$organization=trim(preg_replace('/\s+/', ' ',  $organization));

		$address=new _Address($customer->get('Customer Billing Address Key'));
		if ($address->data['Address Contact']!='') {
			$address_contact=trim(preg_replace('/\s+/', ' ',  $address->data['Address Contact']));
			if (strtolower($address_contact)==strtolower($recipient)) {

			}elseif (strtolower($address_contact)==strtolower($organization)) {

			}else {
				print $customer->id."==================\n";
				print "->$recipient<-\n";
				print "->$organization<-\n";
				print "->$address_contact<-\n";
				$recipient=$address_contact;
				$organization='';

			}
		}
		$address_fields=address_fields($customer->get('Customer Billing Address Key'), $recipient, $organization, $default_country);
		$customer->update_address('Invoice', $address_fields);

		$other_delivery_address_keys=get_delivery_address_keys($db, $customer->id, $customer->get('Customer Billing Address Key'));


		if (count($other_delivery_address_keys)>0) {

			foreach ($other_delivery_address_keys as $other_delivery_address_key) {
				$recipient=$customer->get('Main Contact Name');
				$organization=$customer->get('Company Name');


				$address=new _Address($other_delivery_address_key);
				if ($address->data['Address Contact']!='') {
					$address_contact=trim(preg_replace('/\s+/', ' ',  $address->data['Address Contact']));
					if (strtolower($address_contact)==strtolower($recipient)) {

					}elseif (strtolower($address_contact)==strtolower($organization)) {

					}else {
						print $customer->id." Other DEL ==================\n";
						print "->$recipient<-\n";
						print "->$organization<-\n";
						print "->$address_contact<-\n";
						$recipient=$address_contact;
						$organization='';

					}
				}
				$address_fields=address_fields($other_delivery_address_key, $recipient, $organization, $default_country);
				$customer->add_other_delivery_address($address_fields);



			}


		}


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}




function trim_value(&$value) {
	$value = trim(preg_replace('/\s+/', ' ', $value));
}


function address_fields($address_key, $recipient, $organization, $default_country) {
	$address=new _Address($address_key);
	if ($address->id >0 ) {




		$address_format=get_address_format(($address->data['Address Country 2 Alpha Code']=='XX'?'GB':$address->data['Address Country 2 Alpha Code']));


		$_tmp=preg_replace('/,/', '', $address_format->getFormat());

		$used_fields=preg_split('/\s+/', preg_replace('/%/', '', $_tmp ) );


		$lines=$address->display('2lines');

		$address_fields=array(
			'Address Recipient'=>$recipient,
			'Address Organization'=>$organization,
			'Address Line 1'=>$lines[1],
			'Address Line 2'=>$lines[2],
			'Address Sorting Code'=>'',
			'Address Postal Code'=>$address->get('Address Postal Code'),
			'Address Dependent Locality'=>$address->display('Town Divisions'),
			'Address Locality'=>$address->get('Address Town'),
			'Address Administrative Area'=>$address->display('Country Divisions'),
			'Address Country 2 Alpha Code'=>($address->data['Address Country 2 Alpha Code']=='XX'? $default_country:$address->data['Address Country 2 Alpha Code']),

		);
		//print_r($used_fields);

		if (!in_array('recipient', $used_fields) or  !in_array('organization', $used_fields) or  !in_array('addressLine1', $used_fields)       ) {
			print_r($used_fields);
			print_r($address->data);
			exit('no recipient or organization');
		}

		if (!in_array('addressLine2', $used_fields) ) {

			if ($address_fields['Address Line 2']!='') {
				$address_fields['Address Line 1'].=', '.$address_fields['Address Line 2'];
			}
			$address_fields['Address Line 2']='';
		}

		if (!in_array('dependentLocality', $used_fields) ) {

			if ($address_fields['Address Line 2']=='') {
				$address_fields['Address Line 2']=$address_fields['Address Dependent Locality'];
			}else {
				$address_fields['Address Line 2'].=', '.$address_fields['Address Dependent Locality'];
			}

			$address_fields['Address Dependent Locality']='';
		}

		if (!in_array('administrativeArea', $used_fields)   and $address->display('Country Divisions')!='' ) {
			$address_fields['Address Administrative Area']='';
			//print_r($address->data);
			//print_r($address_fields);

			//print $address->display();


			//exit;

			//print_r($used_fields);
			//print_r($address->data);
			//exit('administrativeArea problem');

		}

		if (!in_array('postalCode', $used_fields)   and $address->display('Address Postal Code')!='' ) {

			if (in_array('sortingCode', $used_fields) ) {
				$address_fields['Address Sorting Code']=$address_fields['Address Postal Code'];
				$address_fields['Address Postal Code']='';

			}else {
				if (in_array('addressLine2', $used_fields)) {
					$address_fields['Address Line 2'].=trim(' '.$address_fields['Address Postal Code']);
					$address_fields['Address Postal Code']='';
				}


				/*
                print_r($used_fields);
				print_r($address->data);
				print_r($address_fields);

				print $address->display();


				exit("\nError2\n");
                */
			}

		}

		if (!in_array('locality', $used_fields)   and ($address->display('Address Locality')!='' or $address->display('Address Dependent Locality')!='' ) ) {


			//$address_fields['Address Locality']='';
			//$address_fields['Address Dependent Locality']='';

			if (in_array('addressLine2', $used_fields)) {

				if ($address_fields['Address Line 1']=='' and $address_fields['Address Line 2']=='') {
					$address_fields['Address Line 1'].=$address_fields['Address Dependent Locality'];
					$address_fields['Address Line 2'].=$address_fields['Address Locality'];

				}elseif ($address_fields['Address Line 1']!='' and $address_fields['Address Line 2']=='') {
					$address_fields['Address Line 2']=preg_replace('/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']);

				}else {
					$address_fields['Address Line 2']=preg_replace('/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']);

				}
			}else {

				print_r($used_fields);
				print_r($address->data);
				print_r($address_fields);

				print $address->display();


				exit("Error3\n");

			}




		}


	}
	else {


		$address_format=get_address_format($default_country);


		$address_fields=array(
			'Address Recipient'=>$recipient,
			'Address Organization'=>$organization,
			'Address Line 1'=>'',
			'Address Line 2'=>'',
			'Address Sorting Code'=>'',
			'Address Postal Code'=>'',
			'Address Dependent Locality'=>'',
			'Address Locality'=>'',
			'Address Administrative Area'=>'',
			'Address Country 2 Alpha Code'=>$default_country,

		);

	}

	array_walk($address_fields, 'trim_value');
	//print "\n".$customer->id."\n";
	//print_r($address_fields);

	return $address_fields;
}


function get_fiscal_name($customer) {
	if ($customer->data['Customer Type']=='Person') {
		$customer->data['Customer Fiscal Name']=$customer->data['Customer Name'];
		return $customer->data['Customer Fiscal Name'];
	} else {
		$subject='Company';
		$subject_key=$customer->data['Customer Company Key'];
	}

	$sql=sprintf("select `$subject Fiscal Name` as fiscal_name from `$subject Dimension` where `$subject Key`=%d ", $subject_key);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_assoc($res)) {
		$customer->data['Customer Fiscal Name']=$row['fiscal_name'];

		return $customer->data['Customer Fiscal Name'];
	} else {
		$customer->error;
		return '';
	}


}


function get_delivery_address_keys($db, $customer_key, $main_address_key) {


	$sql=sprintf("select * from `Address Bridge` CB where  `Address Function` in ('Shipping')  and `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` order by `Address Key`   ",
		$customer_key);
	$address_keys=array();



	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			if ($row['Address Key']==$main_address_key) {
				continue;
			}

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	return $address_keys;




}


?>
