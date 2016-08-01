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

require_once 'class.Supplier.php';
require_once 'class.Store.php';
require_once 'class.Address.php';
require_once 'class.SupplierProduct.php';
require_once 'class.Part.php';
require_once 'class.SupplierPart.php';


$sql=sprintf('select `Supplier Key` from `Supplier Dimension` where `Supplier Key`=6348  order by `Supplier Key` desc');
$sql=sprintf('select `Supplier Key` from `Supplier Dimension`   order by `Supplier Key` desc');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$supplier=new Supplier($row['Supplier Key']);

		$sql="insert into `Supplier Data` (`Supplier Key`) values(".$supplier->id.");";
		$db->exec($sql);

		if ($supplier->data['Supplier Main Country Code']=='UNK') {
			$supplier->update(array(
					'Supplier Main Country Key'=>30,
					'Supplier Main Country Code'=>'GBR',
					'Supplier Main Country'=>'United Kingdom',

				), 'no_history');
		}

	}
}


$sql=sprintf('select `Supplier Key` from `Supplier Dimension` where `Supplier Key`=6348 order by `Supplier Key` desc ');
$sql=sprintf('select `Supplier Key` from `Supplier Dimension`   order by `Supplier Key` ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$supplier=new Supplier($row['Supplier Key']);

		$supplier->update_supplier_parts();


		$default_country=$account->get('Account Country 2 Alpha Code');

		$other_emails=get_other_emails_data($db, $supplier);
		if (count($other_emails)>0) {
			//print $supplier->id."\n";

			foreach ($other_emails as $other_email) {
				$supplier->update(array('new email'=>$other_email['email']), 'no_history');
				//print_r($supplier);
			}

		}



		$recipient=$supplier->get('Main Contact Name');
		$organization=$supplier->get('Company Name');


		if ($organization==$recipient) {
			$organization='';
		}

		if (!$supplier->get('Supplier Main Address Key')) {
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
		}else {

			$address_fields=address_fields($supplier->get('Supplier Main Address Key'), $recipient, $organization, $default_country);

		}




		$supplier->update_address('Contact', $address_fields, 'no_history');



		$location=$supplier->get('Contact Address Locality');
		if ($location=='') {
			$location=$supplier->get('Contact Address Administrative Area');
		}
		if ($location=='') {
			$location=$supplier->get('Supplier Contact Address Postal Code');
		}


		$supplier->update(array(
				'Supplier Location'=>trim(sprintf('<img src="/art/flags/%s.gif" title="%s"> %s',
						strtolower($supplier->get('Contact Address Country 2 Alpha Code')),
						$supplier->get('Contact Address Country 2 Alpha Code'),
						$location))
			), 'no_history');


		if ($supplier->data['Supplier Main Plain Telephone']!='') {
			$supplier->update(array('Supplier Main Plain Telephone'=>$supplier->data['Supplier Main Plain Telephone']), 'no_history');
		}
		if ($supplier->data['Supplier Main Plain Mobile']!='') {
			$supplier->update(array('Supplier Main Plain Mobile'=>$supplier->data['Supplier Main Plain Mobile']), 'no_history');
		}
		if ($supplier->data['Supplier Main Plain FAX']!='') {
			$supplier->update(array('Supplier Main Plain FAX'=>$supplier->data['Supplier Main Plain FAX']), 'no_history');
		}


		add_other_telephone(get_other_telecoms_data($db, 'Telephone', $supplier), $supplier);
		add_other_telephone(get_other_telecoms_data($db, 'Mobile', $supplier), $supplier);
		add_other_telephone(get_other_telecoms_data($db, 'FAX', $supplier), $supplier);

		$supplier->update(array('Supplier Company Name'=>$supplier->get('Name')), 'no_history');







	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


function add_other_telephone($other_telephones, $supplier) {
	if (count($other_telephones)>0) {


		foreach ($other_telephones as $other_telephone) {
			$supplier->update(array('new telephone'=>$other_telephone['number']));
			if ($supplier->field_created_key and $other_telephone['label']!='') {
				$update_data=array();
				$update_data['Supplier Other Telephone Label '.$supplier->field_created_key]=$other_telephone['label'];
				$supplier->update($update_data);

			}




		}

	}

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
	//print "\n".$supplier->id."\n";
	//print_r($address_fields);

	return $address_fields;
}





function get_other_emails_data($db, $supplier) {



	$sql=sprintf("select B.`Email Key`,`Email`,`Email Description`,`User Key` from
        `Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)
        left join `User Dimension` U on (`User Handle`=E.`Email` and `User Type`='Supplier' and `User Parent Key`=%d )
        where  `Subject Type`='Supplier' and `Subject Key`=%d "
		, $supplier->id
		, $supplier->id
	);

	$email_keys=array();

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			if ($row['Email Key']!=$supplier->data['Supplier Main Email Key'])
				$email_keys[$row['Email Key']]= array(
					'email'=>$row['Email'],
					'key'=>$row['Email Key'],
					'xhtml'=>'<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
					'label'=>$row['Email Description'],
					'user_key'=>$row['User Key']
				);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	return $email_keys;

}


function get_other_telecoms_data($db, $type, $supplier) {

	$sql=sprintf("select B.`Telecom Key`,`Telecom Description`,`Telecom Plain Number` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Telecom Type`=%s  and   `Subject Type`='Supplier' and `Subject Key`=%d ",
		prepare_mysql($type),
		$supplier->id
	);
	//print $sql;
	$telecom_keys=array();


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			if ($row['Telecom Key']!=$supplier->data["Supplier Main $type Key"]) {


				$telecom_keys[$row['Telecom Key']]= array(
					'number'=>$row['Telecom Plain Number'],
					'label'=>$row['Telecom Description']
				);

			}
		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	return $telecom_keys;

}



?>
