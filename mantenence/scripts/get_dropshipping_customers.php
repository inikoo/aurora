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
include_once '../../class.Country.php';

include_once '../../edit_customers_functions.php';
include_once 'dropshipping_common_functions.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');




$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db("dw", $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);
$store=new Store('code','DS');

$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity` where email='matt.priest@scldirect.co.uk' ";
$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity` limit 1500,1";
$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity` ";
//$sql= "SELECT * FROM ancient_dropshipnew.`customer_entity` where entity_id=488 ";

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$store_code=$store->data['Store Code'];
	$order_data_id=$row['entity_id'];

	$sql=sprintf("select * from `Customer Import Metadata` where `Metadata`=%s and `Import Date`>=%s",
		prepare_mysql($store_code.$order_data_id),
		prepare_mysql($row['updated_at'])

	);
	$resxx=mysql_query($sql);
	if ($rowxx=mysql_fetch_assoc($resxx)) {

		continue;
	}

print $row['entity_id']."\n";
	
	$email=$row['email'];
	$name='';
	$tel='';
	$fax='';
	$mob='';
	$company='';
	$www='';
	$tax_number='';
	$mobile='';


	$address1='';$address2='';$town='';$postcode='';$country_div='';$country='UNK';

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 512 AND `entity_id` =%d",$row['entity_id']);
	//print "$sql\n";
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$company=$row3['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 15 AND `entity_id` =%d",$row['entity_id']);
	//print "$sql\n";
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$tax_number=$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 4 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$name.=' '.$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 5 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$name.=' '.$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 6 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$name.=' '.$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 7 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$name.=' '.$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 8 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$name.=' '.$row3['value'];
	}

	$name=_trim($name);



	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 513 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$tel=$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 514 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$mob=$row3['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_entity_varchar` WHERE `attribute_id` = 520 AND `entity_id` =%d",$row['entity_id']);
	$res3=mysql_query($sql);
	while ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$www=$row3['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity` WHERE  `parent_id` =%d",$row['entity_id']);
	$res2=mysql_query($sql);

	if ($row2=mysql_fetch_assoc($res2)) {









		list($address1,$address2,$town,$postcode,$country_div,$country)=get_address($row2['entity_id']);

	}
	$country=new Country('2alpha',$country);
	//print_r($country);
	//exit;

	list($tipo_customer,$company_name,$contact_name)=parse_company_person($company,$name);

	$customer_data=array(
		"Customer Type"=>$tipo_customer,
		'Customer First Contacted Date'=>$row['created_at'],
		"Customer Store Key"=>$store->id,
		"Customer Old ID"=>$row['entity_id'],
		"Customer Name"=>$company_name
		,"Customer Main Contact Name"=>$contact_name
		,"Customer Tax Number"=>$tax_number
		,"Customer Website"=>$www
		,"Customer Main Plain Email"=>$email
		,"Customer Main Plain Telephone"=>$tel
		,"Customer Main Plain FAX"=>$fax
		,"Customer Main Plain Mobile"=>$mob
		,"Customer Address Line 1"=>$address1
		,"Customer Address Line 2"=>$address2
		,"Customer Address Line 3"=>''
		,"Customer Address Town"=>$town
		,"Customer Address Postal Code"=>$postcode
		,"Customer Address Country Name"=>$country->data['Country Name']
		,"Customer Address Country Code"=>$country->data['Country Code']
		,"Customer Address Town Second Division"=>''
		,"Customer Address Town First Division"=>$country_div
		,"Customer Address Country First Division"=>''
		,"Customer Address Country Second Division"=>''
		,"Customer Address Country Third Division"=>''
		,"Customer Address Country Forth Division"=>''
		,"Customer Address Country Fifth Division"=>''

	);

	$editor['Date']=$row['created_at'];
	if ($customer_data['Customer Address Country Code']=='')
		$customer_data['Customer Address Country Code']='UNK';

	$customer_data['editor']=$editor;
	//$customer_data['Customer Main Plain Telephone']='0114 321 8600';
	//print_r($customer_data);
	$customer=new Customer('old_id',$row['entity_id'],$store->id);
	if ($customer->id) {

		$update_address_data=array();

		$update_address_data['id']=$customer->data['Customer Main Address Key'];
		$update_address_data['subject']='Customer';
		$update_address_data['subject_key']=$customer->id;

		//  $customer_data['Customer Address Line 3']='sss';

		$update_address_data['value']=array(
			'country_code'=>$customer_data['Customer Address Country Code'],
			'country_d1'=>$customer_data['Customer Address Country First Division'],
			'country_d2'=>$customer_data['Customer Address Country Second Division'],
			'town'=>$customer_data['Customer Address Town'],
			'town_d1'=>$customer_data['Customer Address Town First Division'],
			'town_d2'=>$customer_data['Customer Address Town Second Division'],
			'postal_code'=>$customer_data['Customer Address Postal Code'],
			'street'=>$customer_data['Customer Address Line 3'],
			'internal'=>$customer_data['Customer Address Line 2'],
			'building'=>$customer_data['Customer Address Line 1'],
			'contact'=>'',//$customer_data['Customer Main Contact Name'],
			'use_contact'=>'',
			'telephone'=>$customer_data['Customer Main Plain Telephone'],
			'use_tel'=>'',
			'key'=>$customer->data['Customer Main Address Key']
		);




		edit_address($update_address_data);


		$customer->update_field_switcher('Customer Name',$customer_data['Customer Name']);
		$customer->update_field_switcher('Customer Main Contact Name',$customer_data['Customer Main Contact Name']);
		$customer->update_field_switcher('Customer Main Plain Email',$customer_data['Customer Main Plain Email']);
		$customer->update_field_switcher('Customer Website',$customer_data['Customer Website']);
		$customer->update_field_switcher('Customer Tax Number',$customer_data['Customer Tax Number']);

		$customer->update_field_switcher('Customer Main Plain Telephone',$customer_data['Customer Main Plain Telephone']);
		$customer->update_field_switcher('Customer Main Plain Mobile',$customer_data['Customer Main Plain Mobile']);
		$customer->update_field_switcher('Customer Main Plain FAX',$customer_data['Customer Main Plain FAX']);

	}
	else {




		$response=add_customer($customer_data) ;
		//exit;

	}


$sql=sprintf("INSERT INTO `Customer Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s",
				prepare_mysql($store_code.$order_data_id),
				prepare_mysql($row['updated_at']),
				prepare_mysql($row['updated_at'])
			);

		mysql_query($sql);

}


function edit_address($data) {
	global $editor;
	$warning='';


	//print_r($data);

	$id=$data['id'];
	$subject=$data['subject'];
	$subject_key=$data['subject_key'];
	$raw_data=$data['value'];
	//    if ($subject=='Customer' and 'contact'=='Billing') {
	//        edit_billing_address($raw_data);
	//        exit;
	//    }


	switch ($subject) {
	case('Company'):
		$subject_object=new Company($subject_key);
		break;
	case('Contact'):
		$subject_object=new Contact($subject_key);
		break;
	case('Customer'):
		$subject_object=new Customer($subject_key);
		break;
	case('Supplier'):
		$subject_object=new Supplier($subject_key);
		break;


	}

	$address=new Address('id',$id);

	if (!$address->id) {
		$response=array('state'=>400,'msg'=>'Address not found');
		echo json_encode($response);
		return;
	}
	$address->set_editor($editor);



	$translator=array(
		'country_code'=>'Address Country Code',
		'country_d1'=>'Address Country First Division',
		'country_d2'=>'Address Country Second Division',
		'town'=>'Address Town',
		'town_d1'=>'Address Town First Division',
		'town_d2'=>'Address Town Second Division',
		'postal_code'=>'Address Postal Code',
		'street'=>'Street Data',
		'internal'=>'Address Internal',
		'building'=>'Address Building',
		'contact'=>'Address Contact'
	);


	$update_data=array('editor'=>$editor);

	foreach ($raw_data as $key=>$value) {
		if (array_key_exists($key, $translator)) {
			$update_data[$translator[$key]]=$value;
		}
	}

	$proposed_address=new Address("find complete in $subject $subject_key",$update_data);
	//print_r($proposed_address);
	//exit;
	if ($proposed_address->id) {

		//  print "xxxxaaxxx";

		if ($subject=='Customer') {

			if (preg_match('/^contact$/i','contact')) {

				if ($address->id==$proposed_address->id) {

					$address->update($update_data,'cascade');
					//   print_r($address);
					if ($address->updated) {

					} else {

					}


					return;
				} else {
					$subject_object->update_principal_address($proposed_address->id);



				}



			} else {

				//print_r($data['value']);

				if ($data['value']['use_tel'] or $data['value']['use_contact']) {


					return;





				} else {

					return;
				}
			}
		}
		else if ($subject=='Supplier') {
				if (preg_match('/^contact$/i','contact')) {
					$subject_object->update_principal_address($proposed_address->id);

					// print "new Address address".$subject_object->data['Customer Main Address Key']."\n";
					$address->delete();

					return;
				} else {

					return;
				}
			}
	}
	else {// address not found inside customer
		$proposed_address=new Address("find complete ",$update_data);

		if ($proposed_address->id) {
			$address_parents=$proposed_address->get_parent_keys($subject);

			$warning=_('Warning, address found also associated with')." ";
			switch ($subject) {
			case 'Customer':
				$parent_label='';

				foreach ($address_parents as $parent_key) {
					$parent=new Customer($parent_key);
					$parent_label.=sprintf(', <a href="customer.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Customer','Customers').' '.$parent_label;
				break;
			case 'Supplier':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Supplier($parent_key);
					$parent_label.=sprintf(', <a href="supplier.php?id=%d">%s</a>',$parent->id,$parent->data['Customer Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Supplier','Suppliers').' '.$parent_label;
				break;
			case 'Company':
				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Company($parent_key);
					$parent_label.=sprintf(', <a href="company.php?id=%d">%s</a>',$parent->id,$parent->data['Company Name']);
				}
				$parent_label=preg_replace('/^,/','',$parent_label);
				$warning.=ngettext(count($address_parents),'Company','Companies').' '.$parent_label;
				break;
			case('Contact'):


				$parent_label='';
				foreach ($address_parents as $parent_key) {
					$parent=new Contact($parent_key);
					if ($parent->data['Contact Company Key']!=$subject->data['Contact Company Key'] )
						$parent_label.=sprintf(', <a href="contact.php?id=%d">%s</a>',$parent->id,$parent->display('name'));
				}
				if ($parent_label=='')
					$warning='';
				else {
					$parent_label=preg_replace('/^,/','',$parent_label);
					$warning.=ngettext(count($address_parents),'Contact','Contacts').' '.$parent_label;
				}
				break;


			default:
				break;
			}

		}
	}

	//print_r($update_data);

	$address->update($update_data,'cascade');


	//print "-------------";

	$updated=$address->updated;
	if ($data['value']['use_tel']) {
		if ($data['value']['telephone']!='') {
			$tel_updated=edit_address_main_telephone($data['value']['telephone'],$address->id);
			if (!$updated and $tel_updated)
				$updated=true;

		}
	}




}


?>
