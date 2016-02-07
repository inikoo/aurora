<?php
/*
  File: Customer.php

  This file cSontains the Customer Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0


  The customer dimension is the  critical element for a CRM, a customer can be a Company or a Contact.

*/
include_once 'class.Subject.php';
include_once 'class.Order.php';
//include_once 'class.Address.php';
include_once 'class.Attachment.php';

class Customer extends Subject {
	var $contact_data=false;
	var $ship_to=array();
	var $billing_to=array();
	var $fuzzy=false;
	var $tax_number_read=false;
	var $warning_messages=array();
	var $warning=false;

	function __construct($arg1=false, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;

		$this->label=_('Customer');
		$this->table_name='Customer';
		$this->ignore_fields=array(
			'Customer Key'
			, 'Customer Has More Orders Than'
			, 'Customer Has More  Invoices Than'
			, 'Customer Has Better Balance Than'
			, 'Customer Is More Profiteable Than'
			, 'Customer Order More Frecuently Than'
			, 'Customer Older Than'
			, 'Customer Orders Position'
			, 'Customer Invoices Position'
			, 'Customer Balance Position'
			, 'Customer Profit Position'
			, 'Customer Order Interval'
			, 'Customer Order Interval STD'
			, 'Customer Orders Top Percentage'
			, 'Customer Invoices Top Percentage'
			, 'Customer Balance Top Percentage'
			, 'Customer Profits Top Percentage'
			, 'Customer First Order Date'
			, 'Customer Last Order Date'
		);


		$this->status_names=array(0=>'new');

		if (is_numeric($arg1) and !$arg2) {
			$this->get_data('id', $arg1);
			return;
		}



		if ($arg1=='new') {
			$this->find($arg2, $arg3 , 'create');
			return;
		}


		$this->get_data($arg1, $arg2, $arg3);


	}


	function is_user_customer($data) {
		$sql=sprintf("select * from `User Dimension` where `User Parent Key`=%d and `User Type`='Customer' ", $data);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC))
			return array(true, $row);
	}


	function number_of_user_logins() {
		list($is_user, $row)=$this->is_user_customer($this->id);
		if ($is_user) {
			$sql=sprintf("select * from `User Log Dimension` where `User Key`=%d", $row['User Key']);
			$result=mysql_query($sql);
			if ($num=mysql_num_rows($result))
				return $num;
			else
				return 0;
		} else
			return 0;
	}






	function find($raw_data, $address_raw_data, $options='') {







		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}

		$type_of_search='complete';
		if (preg_match('/fuzzy/i', $options)) {
			$type_of_search='fuzzy';
		}
		elseif (preg_match('/fast/i', $options)) {
			$type_of_search='fast';
		}

		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}
		if (preg_match('/update/i', $options)) {
			$update='update';
		}


		if (
			!isset($raw_data['Customer Store Key']) or
			!preg_match('/^\d+$/i', $raw_data['Customer Store Key']) ) {
			$this->error=true;
			$this->msg='missing store key';

		}



		if ( $raw_data['Customer Company Name']!='' )
			$raw_data['Customer Type']='Company';
		else
			$raw_data['Customer Type']='Person';


		$sql=sprintf('select `Customer Key` from `Customer Dimension` where `Customer Store Key`=%d and `Customer Main Plain Email`=%s ',
			$raw_data['Customer Store Key'],
			prepare_mysql($raw_data['Customer Main Plain Email'])
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->error=true;
				$this->found=true;
				$this->msg=_('Another customer with same email has been found');

				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create) {

			$this->create($raw_data, $address_raw_data);
		}




	}


	function update_correlations() {

		$sql=sprintf("delete from  `Customer Correlation` where `Customer A Key`=%d or `Customer B Key`=%d  ", $this->id, $this->id);
		mysql_query($sql);

		$correlated_customers=array();
		$data=$this->data;
		if ($data['Customer Type']=='Person')
			$subject=new contact('find from customer complete', $data);
		else
			$subject=new company('find from customer complete', $data);



		foreach ($subject->candidate as $contact_key=>$score) {
			if ($score<100)
				continue;
			$contact=new Contact($contact_key);
			$customer_keys=$contact->get_customer_keys('Customer');

			foreach ($customer_keys as $customer_key) {
				$customer_correlated=new Customer($customer_key);
				if ($customer_correlated->data['Customer Store Key']==$this->data['Customer Store Key'])
					$correlated_customers[$customer_key]=array('name'=>$customer_correlated->data['Customer Name'], 'score'=>$score);
			}

		}


		foreach ($correlated_customers as $key=>$value) {

			if ($key==$this->id) {
				continue;
			}
			elseif ($key<$this->id) {
				$customer_a=$key;
				$customer_a_name=$value['name'];

				$customer_b=$this->id;
				$customer_b_name=$this->data['Customer Name'];

			}
			else {
				$customer_a=$this->id;
				$customer_a_name=$this->data['Customer Name'];

				$customer_b=$key;
				$customer_b_name=$value['name'];
			}

			$sql=sprintf("insert into  `Customer Correlation` values (%d,%s,%d,%s,%f,%d)  ",
				$customer_a,
				prepare_mysql($customer_a_name),
				$customer_b,
				prepare_mysql($customer_b_name),
				$value['score'],
				$this->data['Customer Store Key']
			);
			mysql_query($sql);
		}
	}







	function get_data($tag, $id, $id2=false) {
		if ($tag=='id')
			$sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s", prepare_mysql($id));
		elseif ($tag=='email')
			$sql=sprintf("select * from `Customer Dimension` where `Customer Main Plain Email`=%s", prepare_mysql($id));
		elseif ($tag=='old_id')
			$sql=sprintf("select * from `Customer Dimension` where `Customer Old ID`=%s and `Customer Store Key`=%d",
				prepare_mysql($id),
				$id2

			);
		elseif ($tag='all') {
			$this->find($id);
			return true;
		}
		else
			return false;





		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Customer Key'];
		}


	}




	function create($raw_data, $address_raw_data, $args='') {


		$this->data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=_trim($value);
			}
		}
		$this->editor=$raw_data['editor'];

		if ($this->data['Customer First Contacted Date']=='') {
			$this->data['Customer First Contacted Date']=gmdate('Y-m-d H:i:s');
		}


		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			//if ($key=='') {
			// $values.=','.prepare_mysql($value, true);
			//}else {
			$values.=','.prepare_mysql($value, false);
			//}
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Customer Dimension` ($keys) values ($values)";


		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->get_data('id', $this->id);





			if ($this->data['Customer Company Name']!='') {
				$customer_name=$this->data['Customer Company Name'];
			}else {
				$customer_name=$this->data['Customer Main Contact Name'];
			}
			$this->update_field('Customer Name', $customer_name, 'no_history');



			$this->update_address('Contact', $address_raw_data);
			$this->update_address('Invoice', $address_raw_data);
			$this->update_address('Delivery', $address_raw_data);


			$history_data=array(
				'History Abstract'=>sprintf(_('%s customer record created'), $this->get('Name')),
				'History Details'=>'',
				'Action'=>'created'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$this->new=true;








		}else {
			$this->error=true;
			$this->msg='Error inserting customer record';
		}

		$this->update_full_search();
		$this->update_location_type();

	}






	function update_field_switcher($field, $value, $options='') {




		if (is_string($value))
			$value=_trim($value);


		if ($this->update_subject_field_switcher($field, $value, $options)) {
			return;
		}


		switch ($field){

		case 'Customer Invoice Address':
			$this->update_address('Invoice', json_decode($value, true));
			break;
		case 'Customer Delivery Address':
			$this->update_address('Delivery', json_decode($value, true));
			break;
		case 'new delivery address':
			$this->add_other_delivery_address(json_decode($value, true));

			break;



		case('Customer Tax Number'):
			$this->update_tax_number($value);
			break;
		case('Customer Tax Number Valid'):
			$this->update_tax_number_valid($value);
			break;




		case('Customer First Contacted Date'):

			break;
		case('Customer Sticky Note'):
			$this->update_field_switcher('Sticky Note', $value);
			break;
		case('Sticky Note'):
			$this->update_field('Customer '.$field, $value, 'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('Note'):
			$this->add_note($value);
			break;
		case('Attach'):
			$this->add_attach($value);
			break;


		default:



			if (preg_match('/^custom_field_/i', $field)) {
				//$field=preg_replace('/^custom_field_/','',$field);
				$this->update_field($field, $value, $options);


				return;
			}






			if (preg_match('/^Customer Other Delivery Address (\d+)/i', $field, $matches)) {

				$customer_delivery_address_key=$matches[1];


				$this->update_other_delivery_address($customer_delivery_address_key, $field, json_decode($value, true), $options);

				return;
			}



			$base_data=$this->base_data();
			//print_r($base_data);
			if (array_key_exists($field, $base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field, $value, $options);
				}
			}
		}
	}




	function update_other_delivery_address($customer_delivery_address_key, $field, $fields, $options='') {



		$sql=sprintf("select * from `Customer Other Delivery Address Dimension` where `Customer Other Delivery Address Key`=%d ",
			$customer_delivery_address_key
		);
		if ($result=$this->db->query($sql)) {
			if ($address_data = $result->fetch()) {



			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		//print_r($address_data);





		$address_fields=array();
		$updated_fields_number=0;
		$updated_recipient_fields=false;
		$updated_address_fields=false;
		foreach ($fields as $field=>$value) {


			$sql=sprintf('update `Customer Other Delivery Address Dimension` set `%s`=%s where `Customer Other Delivery Address Key`=%d ',
				addslashes('Customer Other Delivery '.$field),
				prepare_mysql($value, true),
				$customer_delivery_address_key
			);

			$update_op=$this->db->prepare($sql);
			$update_op->execute();
			$affected=$update_op->rowCount();


			// print "$sql\n";

			if ($affected>0) {



				$updated_fields_number++;
				if ($field=='Address Recipient' or $field=='Address Organization') {
					$updated_recipient_fields=true;
				}else {
					$updated_address_fields=true;
				}
			}
		}


		if ($updated_fields_number>0) {
			$this->updated=true;
		}


		if ($this->updated or true) {

			include_once 'utils/get_addressing.php';


			$address_fields=$this->get_other_delivery_address_fields($customer_delivery_address_key);




			$new_checksum= md5(json_encode($address_fields));

			$sql=sprintf('update `Customer Other Delivery Address Dimension` set `Customer Other Delivery Address Checksum`=%s where `Customer Other Delivery Address Key`=%d ',
				prepare_mysql($new_checksum, true),
				$customer_delivery_address_key
			);
			$this->db->exec($sql);

			$store=new Store($this->get('Store Key'));


			list($address, $formatter, $postal_label_formatter)=get_address_formatter(
				$store->get('Store Home Country Code 2 Alpha'),
				$store->get('Store Locale')
			);


			$address = $address
			->withRecipient($address_fields['Address Recipient'])
			->withOrganization($address_fields['Address Organization'])
			->withAddressLine1($address_fields['Address Line 1'])
			->withAddressLine2($address_fields['Address Line 2'])
			->withSortingCode($address_fields['Address Sorting Code'])
			->withPostalCode($address_fields['Address Postal Code'])
			->withDependentLocality($address_fields['Address Dependent Locality'])
			->withLocality($address_fields['Address Locality'])
			->withAdministrativeArea($address_fields['Address Administrative Area'])
			->withCountryCode($address_fields['Address Country 2 Alpha Code']);

			$xhtml_address=$formatter->format($address);
			$xhtml_address=preg_replace('/<br>\s/', "\n", $xhtml_address);
			$xhtml_address=preg_replace('/class="recipient"/', 'class="recipient fn"', $xhtml_address);
			$xhtml_address=preg_replace('/class="organization"/', 'class="organization org"', $xhtml_address);
			$xhtml_address=preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
			$xhtml_address=preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
			$xhtml_address=preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
			$xhtml_address=preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


			$sql=sprintf('update `Customer Other Delivery Address Dimension` set `Customer Other Delivery Address Formatted`=%s where `Customer Other Delivery Address Key`=%d ',
				prepare_mysql($xhtml_address, true),
				$customer_delivery_address_key
			);
			$this->db->exec($sql);

			$sql=sprintf('update `Customer Other Delivery Address Dimension` set `Customer Other Delivery Address Postal Label`=%s where `Customer Other Delivery Address Key`=%d ',
				prepare_mysql($postal_label_formatter->format($address), true),
				$customer_delivery_address_key
			);
			$this->db->exec($sql);




		}





	}




	function add_other_delivery_address($fields, $options='') {


		include_once 'utils/get_addressing.php';

		$checksum= md5(json_encode($fields));
		if ($checksum==$this->get('Customer Delivery Address Checksum')) {
			$this->error=true;
			$this->msg=_('Duplicated address');

			return;
		}

		$sql=sprintf('select `Customer Other Delivery Address Checksum` from `Customer Other Delivery Address Dimension` where `Customer Other Delivery Address Customer Key`=%d',
			$this->id
		);
		if ($result=$this->db->query($sql)) {



			foreach ($result as $row) {
				if ($checksum==$row['Customer Other Delivery Address Checksum']) {
					$this->error=true;
					$this->msg=_('Duplicated address');

					return;
				}


			}


		}

		else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$store=new Store($this->get('Store Key'));


		list($address, $formatter, $postal_label_formatter)=get_address_formatter(
			$store->get('Store Home Country Code 2 Alpha'),
			$store->get('Store Locale')
		);


		$address = $address
		->withRecipient($fields['Address Recipient'])
		->withOrganization($fields['Address Organization'])
		->withAddressLine1($fields['Address Line 1'])
		->withAddressLine2($fields['Address Line 2'])
		->withSortingCode($fields['Address Sorting Code'])
		->withPostalCode($fields['Address Postal Code'])
		->withDependentLocality($fields['Address Dependent Locality'])
		->withLocality($fields['Address Locality'])
		->withAdministrativeArea($fields['Address Administrative Area'])
		->withCountryCode($fields['Address Country 2 Alpha Code']);

		$xhtml_address=$formatter->format($address);
		$xhtml_address=preg_replace('/<br>\s/', "\n", $xhtml_address);
		$xhtml_address=preg_replace('/class="recipient"/', 'class="recipient fn"', $xhtml_address);
		$xhtml_address=preg_replace('/class="organization"/', 'class="organization org"', $xhtml_address);
		$xhtml_address=preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
		$xhtml_address=preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
		$xhtml_address=preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
		$xhtml_address=preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);



		$sql=sprintf('insert into `Customer Other Delivery Address Dimension` (
        `Customer Other Delivery Address Store Key`,
        `Customer Other Delivery Address Customer Key`,
        `Customer Other Delivery Address Recipient`,
        `Customer Other Delivery Address Organization`,
        `Customer Other Delivery Address Line 1`,
        `Customer Other Delivery Address Line 2`,
        `Customer Other Delivery Address Sorting Code`,
        `Customer Other Delivery Address Postal Code`,
        `Customer Other Delivery Address Dependent Locality`,
        `Customer Other Delivery Address Locality`,
        `Customer Other Delivery Address Administrative Area`,
        `Customer Other Delivery Address Country 2 Alpha Code`,
         `Customer Other Delivery Address Checksum`,
        `Customer Other Delivery Address Formatted`,
        `Customer Other Delivery Address Postal Label`

        ) values (%d,%d,
        %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
        ) ',
			$this->get('Store Key'),
			$this->id,
			prepare_mysql($fields['Address Recipient'], false),
			prepare_mysql($fields['Address Organization'], false),
			prepare_mysql($fields['Address Line 1'], false),
			prepare_mysql($fields['Address Line 2'], false),
			prepare_mysql($fields['Address Sorting Code'], false),
			prepare_mysql($fields['Address Postal Code'], false),
			prepare_mysql($fields['Address Dependent Locality'], false),
			prepare_mysql($fields['Address Locality'], false),
			prepare_mysql($fields['Address Administrative Area'], false),
			prepare_mysql($fields['Address Country 2 Alpha Code'], false),
			prepare_mysql($checksum),
			prepare_mysql($xhtml_address),
			prepare_mysql($postal_label_formatter->format($address))
		);

		$prep=$this->db->prepare($sql);


		try{
			$prep->execute();

			$inserted_key = $this->db->lastInsertId();

			//print $sql;
			if ($inserted_key) {

				$this->field_created=true;



				$this->add_changelog_record(_("delivery address"), '', $this->get("Other Delivery Address $inserted_key"), '', $this->table_name, $this->id , 'added');





				$this->new_fields_info=array(
					array(
						'clone_from'=>'Customer_Other_Delivery_Address',
						'field'=>'Customer_Other_Delivery_Address_'.$inserted_key,
						'render'=>true,
						'edit'=>'address',
						'value'=>$this->get('Customer Other Delivery Address '.$inserted_key),
						'formatted_value'=>$this->get('Other Delivery Address '.$inserted_key),
						'label'=>'',



					));

			}else {
				$this->error=true;

				$this->msg=_('Duplicated address').' (1)';
			}

		} catch(PDOException $e) {
			$this->error=true;

			if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
				$this->msg=_('Duplicated address').' (2)';
			}else {

				$this->msg=$e->getMessage();
			}

		}



	}


	public function update_no_normal_data() {

		return;

		$sql="select min(`Order Date`) as date   from `Order Dimension` where `Order Customer Key`=".$this->id;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$first_order_date=date('U', strtotime($row['date']));
			if ($row['date']!=''
				and (
					$this->data['Customer First Contacted Date']==''
					or ( gmdate('U', strtotime($this->data['Customer First Contacted Date']))>$first_order_date  )
				)
			) {

				//print $this->data['Customer First Contacted Date']." ->  ".$row['date']."\n";

				$sql=sprintf("update `Customer Dimension` set `Customer First Contacted Date`=%s  where `Customer Key`=%d"
					, prepare_mysql($row['date'])
					, $this->id
				);
				mysql_query($sql);
			}
		}
		// $address_fuzzy=false;
		// $email_fuzzy=false;
		// $tel_fuzzy=false;
		// $contact_fuzzy=false;


		// $address=new Address($this->get('Customer Main Address Key'));
		// if($address->get('Fuzzy Address'))
		//  $address_fuzzy=true;



	}


	public function update_activity() {


		$this->data['Customer Lost Date']='';
		$this->data['Actual Customer']='Yes';

		$orders= $this->data['Customer Orders'];

		$store=new Store($this->data['Customer Store Key']);

		if ($orders==0) {
			$this->data['Customer Type by Activity']='Active';
			$this->data['Customer Active']='Yes';
			if (strtotime('now')-strtotime($this->data['Customer First Contacted Date'])>$store->data['Store Losing Customer Interval']   ) {
				$this->data['Customer Type by Activity']='Losing';
			}
			if (strtotime('now')-strtotime($this->data['Customer First Contacted Date'])>$store->data['Store Lost Customer Interval']   ) {
				$this->data['Customer Type by Activity']='Lost';
				$this->data['Customer Active']='No';
			}

			//print "\n\n".$this->data['Customer First Contacted Date']." +".$this->data['Customer First Contacted Date']." seconds\n";
			$this->data['Customer Lost Date']=gmdate('Y-m-d H:i:s', strtotime($this->data['Customer First Contacted Date']." +".$store->data['Store Lost Customer Interval']." seconds"));
		} else {


			$losing_interval=$store->data['Store Losing Customer Interval'];
			$lost_interval=$store->data['Store Lost Customer Interval'] ;

			if ($orders>20) {
				$sigma_factor=3.2906;//99.9% value assuming normal distribution

				$losing_interval=$this->data['Customer Order Interval']+$sigma_factor*$this->data['Customer Order Interval STD'];
				$lost_interval=$losing_interval*4.0/3.0;
			}

			$lost_interval=ceil($lost_interval);
			$losing_interval=ceil($losing_interval);

			$this->data['Customer Type by Activity']='Active';
			$this->data['Customer Active']='Yes';
			if (strtotime('now')-strtotime($this->data['Customer Last Order Date'])>$losing_interval  ) {
				$this->data['Customer Type by Activity']='Losing';
			}
			if (strtotime('now')-strtotime($this->data['Customer Last Order Date'])>$lost_interval   ) {
				$this->data['Customer Type by Activity']='Lost';
				$this->data['Customer Active']='No';
			}
			//print "\n xxx ".$this->data['Customer Last Order Date']." +$losing_interval seconds"."    \n";
			$this->data['Customer Lost Date']=gmdate('Y-m-d H:i:s',
				strtotime($this->data['Customer Last Order Date']." +$lost_interval seconds")
			);

		}

		$sql=sprintf("update `Customer Dimension` set `Customer Active`=%s,`Customer Type by Activity`=%s , `Customer Lost Date`=%s where `Customer Key`=%d"
			, prepare_mysql($this->data['Customer Active'])
			, prepare_mysql($this->data['Customer Type by Activity'])
			, prepare_mysql($this->data['Customer Lost Date'])
			, $this->id
		);

		//   print "\n $orders\n$sql\n";
		//  exit;
		if (!mysql_query($sql))
			exit("\n$sql\n error");

	}


	function update_is_new($new_interval=604800) {

		$interval=gmdate('U')-strtotime($this->data['Customer First Contacted Date']);

		if ( $interval<$new_interval
			//        or $this->data['Customer Type by Activity']=='Lost'
		) {
			$this->data['Customer New']='Yes';
		} else {
			$this->data['Customer New']='No';
		}

		$sql=sprintf("update `Customer Dimension` set `Customer New`=%s where `Customer Key`=%d",
			prepare_mysql($this->data['Customer New']),
			$this->id
		);
		// if($this->data['Customer New']=='Yes')
		//    print (gmdate('U')." ".strtotime($this->data['Customer First Contacted Date']))." $interval  \n";
		//    print $sql;
		mysql_query($sql);



	}


	public function update_orders() {


		setlocale(LC_ALL, 'en_GB');
		$sigma_factor=3.2906;//99.9% value assuming normal distribution

		$this->data['Customer Orders']=0;
		$this->data['Customer Orders Cancelled']=0;
		$this->data['Customer Orders Invoiced']=0;
		$this->data['Customer First Order Date']='';
		$this->data['Customer Last Order Date']='';
		$this->data['Customer First Invoiced Order Date']='';
		$this->data['Customer Last Invoiced Order Date']='';

		$this->data['Customer Order Interval']='';
		$this->data['Customer Order Interval STD']='';

		$this->data['Customer Net Balance']=0;
		$this->data['Customer Net Refunds']=0;
		$this->data['Customer Net Payments']=0;
		$this->data['Customer Tax Balance']=0;
		$this->data['Customer Tax Refunds']=0;
		$this->data['Customer Tax Payments']=0;

		$this->data['Customer Total Balance']=0;
		$this->data['Customer Total Refunds']=0;
		$this->data['Customer Total Payments']=0;

		$this->data['Customer Profit']=0;
		$this->data['Customer With Orders']='No';


		$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='Cancelled' ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Customer Orders Cancelled']=$row['num'];
		}

		$sql=sprintf("select count(*) as num ,
		min(`Order Date`) as first_order_date ,
		max(`Order Date`) as last_order_date

		from `Order Dimension` where `Order Customer Key`=%d and `Order Invoiced`='Yes'  ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Customer Orders Invoiced']=$row['num'];
			$this->data['Customer First Invoiced Order Date']=$row['first_order_date'];
			$this->data['Customer Last Invoiced Order Date']=$row['last_order_date'];
		}




		if ($this->data['Customer Orders Invoiced']>1) {
			$sql="select `Order Date` as date from `Order Dimension` where `Order Invoiced`='Yes'  and `Order Customer Key`=".$this->id." order by `Order Date`";
			$last_order=false;
			$intervals=array();
			$result2=mysql_query($sql);
			while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
				$this_date=gmdate('U', strtotime($row2['date']));
				if ($last_order) {
					$intervals[]=($this_date-$last_date);
				}

				$last_date=$this_date;
				$last_order=true;

			}
			$this->data['Customer Order Interval']=average($intervals);
			$this->data['Customer Order Interval STD']=deviation($intervals);




		}


		//get payments data directly from payment

		$this->data['Customer Last Invoiced Dispatched Date']='';

		$sql=sprintf("select max(`Order Dispatched Date`) as last_order_dispatched_date from `Order Dimension` where `Order Customer Key`=%d  and `Order Current Dispatch State`='Dispatched' and `Order Invoiced`='Yes'",
			$this->id
		);
		// print $sql."\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Customer Last Invoiced Dispatched Date']=$row['last_order_dispatched_date'];

		}


		$sql=sprintf("select
		sum(`Order Invoiced Profit Amount`) as profit,
		sum(`Order Net Refund Amount`+`Order Net Credited Amount`) as net_refunds,
		sum(`Order Invoiced Outstanding Balance Net Amount`) as net_outstanding,
		sum(`Order Invoiced Balance Net Amount`) as net_balance,
		sum(`Order Tax Refund Amount`+`Order Tax Credited Amount`) as tax_refunds,
		sum(`Order Invoiced Outstanding Balance Tax Amount`) as tax_outstanding,
		sum(`Order Invoiced Balance Tax Amount`) as tax_balance,
		min(`Order Date`) as first_order_date ,
		max(`Order Date`) as last_order_date,
		count(*) as orders
		from `Order Dimension` where `Order Customer Key`=%d  and `Order Current Dispatch State` not in ('Cancelled','Cancelled by Customer','In Process by Customer','Waiting for Payment') ",
			$this->id
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {



			$this->data['Customer Orders']=$row['orders'];

			$this->data['Customer Net Balance']=$row['net_balance'];
			$this->data['Customer Net Refunds']=$row['net_refunds'];
			$this->data['Customer Net Payments']=$row['net_balance']-$row['net_outstanding'];
			$this->data['Customer Outstanding Net Balance']=$row['net_outstanding'];

			$this->data['Customer Tax Balance']=$row['tax_balance'];
			$this->data['Customer Tax Refunds']=$row['tax_refunds'];
			$this->data['Customer Tax Payments']=$row['tax_balance']-$row['tax_outstanding'];
			$this->data['Customer Outstanding Tax Balance']=$row['tax_outstanding'];

			$this->data['Customer Profit']=$row['profit'];


			if ($this->data['Customer Orders']>0) {
				$this->data['Customer First Order Date']=$row['first_order_date'];
				$this->data['Customer Last Order Date']=$row['last_order_date'] ;
				$this->data['Customer With Orders']='Yes';
			}









		}


		$sql=sprintf("update `Customer Dimension` set `Customer Last Invoiced Dispatched Date`=%s,`Customer Net Balance`=%.2f,`Customer Orders`=%d,`Customer Orders Cancelled`=%d,`Customer Orders Invoiced`=%d,`Customer First Order Date`=%s,`Customer Last Order Date`=%s,`Customer Order Interval`=%s,`Customer Order Interval STD`=%s,`Customer Net Refunds`=%.2f,`Customer Net Payments`=%.2f,`Customer Outstanding Net Balance`=%.2f,`Customer Tax Balance`=%.2f,`Customer Tax Refunds`=%.2f,`Customer Tax Payments`=%.2f,`Customer Outstanding Tax Balance`=%.2f,`Customer Profit`=%.2f ,`Customer With Orders`=%s  where `Customer Key`=%d",
			prepare_mysql($this->data['Customer Last Invoiced Dispatched Date'])
			, $this->data['Customer Net Balance']
			, $this->data['Customer Orders']
			, $this->data['Customer Orders Cancelled']
			, $this->data['Customer Orders Invoiced']
			, prepare_mysql($this->data['Customer First Order Date'])
			, prepare_mysql($this->data['Customer Last Order Date'])
			, prepare_mysql($this->data['Customer Order Interval'])
			, prepare_mysql($this->data['Customer Order Interval STD'])
			, $this->data['Customer Net Refunds']
			, $this->data['Customer Net Payments']
			, $this->data['Customer Outstanding Net Balance']

			, $this->data['Customer Tax Balance']
			, $this->data['Customer Tax Refunds']
			, $this->data['Customer Tax Payments']
			, $this->data['Customer Outstanding Tax Balance']

			, $this->data['Customer Profit']
			, prepare_mysql($this->data['Customer With Orders'])


			, $this->id
		);
		mysql_query($sql);
		//print "$sql\n\n";


	}







	function get($key, $arg1=false) {


		if (!$this->id)return false;

		list($got, $result)=$this->get_subject_common($key, $arg1);
		if ($got)return $result;

		switch ($key) {


		case 'Tax Number':
			if ($this->data['Customer Tax Number']!='') {
				if ($this->data['Customer Tax Number Valid']=='Yes') {
					return sprintf('<span class="ok">%s</span>', $this->data['Customer Tax Number']);
				}elseif ($this->data['Customer Tax Number Valid']=='Unknown') {
					return sprintf('<span class="disabled">%s</span>', $this->data['Customer Tax Number']);
				}else {
					return sprintf('<span class="error">%s</span>', $this->data['Customer Tax Number']);
				}
			}

			break;



		case('Tax Number Valid'):
			if ($this->data['Customer Tax Number']!='') {

				if ($this->data['Customer Tax Number Validation Date']!='') {
					$_tmp=gmdate("U")-gmdate("U", strtotime($this->data['Customer Tax Number Validation Date'].' +0:00'));
					if ( $_tmp<3600  ) {
						$date=strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['Customer Tax Number Validation Date'].' +0:00'));

					}elseif ($_tmp<86400   ) {
						$date=strftime("%e %b %Y %H:%M %Z", strtotime($this->data['Customer Tax Number Validation Date'].' +0:00'));

					}else {
						$date=strftime("%e %b %Y", strtotime($this->data['Customer Tax Number Validation Date'].' +0:00'));
					}
				}else {
					$date='';
				}

				$msg=$this->data['Customer Tax Number Validation Message'];

				if ($this->data['Customer Tax Number Validation Source']=='Online') {
					$source='<i title=\''._('Validated online').'\' class=\'fa fa-globe\'></i>';



				}elseif ($this->data['Customer Tax Number Validation Source']=='Manual') {
					$source='<i title=\''._('Set up manually').'\' class=\'fa fa-hand-rock-o\'></i>';
				}else {
					$source='';
				}

				$validation_data=trim($date.' '.$source.' '.$msg);
				if ($validation_data!='')$validation_data=' <span class=\'discret\'>('.$validation_data.')</span>';

				switch ($this->data['Customer Tax Number Valid']) {
				case 'Unknown':
					return _('Not validated').$validation_data;
					break;
				case 'Yes':
					return _('Validated').$validation_data;
					break;
				case 'No':
					return _('Not valid').$validation_data;
				default:
					return $this->data['Customer Tax Number Valid'].$validation_data;

					break;
				}
			}
			break;
		case('Tax Number Details Match'):
			switch ($this->data['Customer '.$key]) {
			case 'Unknown':
				return _('Unknown');
				break;
			case 'Yes':
				return _('Yes');
				break;
			case 'No':
				return _('No');
			default:
				return $this->data['Customer '.$key];

				break;
			}

			break;
		case('Lost Date'):
		case('Last Order Date'):
		case('First Order Date'):
		case('First Contacted Date'):
		case('Last Order Date'):
		case('Tax Number Validation Date'):
			if ($this->data['Customer '.$key]=='')
				return '';
			return '<span title="'.strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Customer '.$key]." +00:00")).'">'.strftime("%a %e %b %Y", strtotime($this->data['Customer '.$key]." +00:00")).'</span>';
			break;
		case('Orders'):
			return number($this->data['Customer Orders']);
			break;
		case('Notes'):
			$sql=sprintf("select count(*) as total from  `Customer History Bridge`     where `Customer Key`=%d and `Type`='Notes'  ", $this->id);
			$res=mysql_query($sql);
			$notes=0;
			if ($row=mysql_fetch_assoc($res)) {
				$notes=$row['total'];
			}


			return number($notes);
			break;
		case('Send Newsletter'):
		case('Send Email Marketing'):
		case('Send Postal Marketing'):

			return $this->data['Customer '.$key]=='Yes'?_('Yes'):_('No');

			break;
		case("ID"):
		case("Formatted ID"):
			return $this->get_formatted_id();
		case("Sticky Note"):
			return nl2br($this->data['Customer Sticky Note']);
			break;
		case('Net Balance'):
		case('Account Balance'):
			return money($this->data['Customer '.$key], $this->data['Customer Currency Code']);
			break;
		case('Total Net Per Order'):
			if ($this->data['Customer Orders Invoiced']>0)
				return money($this->data['Customer Net Balance']/$this->data['Customer Orders Invoiced'], $this->data['Customer Currency Code']);
			else
				return _('ND');
			break;
		case('Order Interval'):
			$order_interval=$this->get('Customer Order Interval')/24/3600;

			if ($order_interval>10) {
				$order_interval=round($order_interval/7);
				if ( $order_interval==1)
					$order_interval=_('week');
				else
					$order_interval=$order_interval.' '._('weeks');

			} else if ($order_interval=='')
				$order_interval='';
			else
				$order_interval=round($order_interval).' '._('days');
			return $order_interval;
			break;

		case('Tax Rate'):
			return $this->get_tax_rate();
			break;
		case('Tax Code'):
			return $this->data['Customer Tax Category Code'];
			break;

		default:





			if (preg_match('/^Customer Other Delivery Address (\d+)/i', $key, $matches)) {

				$address_fields=$this->get_other_delivery_address_fields($matches[1]);


				return json_encode($address_fields);

			}

			if (preg_match('/^Other Delivery Address (\d+)/i', $key, $matches)) {


				$customer_delivery_key=$matches[1];
				$sql=sprintf("select `Customer Other Delivery Address Formatted` from `Customer Other Delivery Address Dimension` where `Customer Other Delivery Address Key`=%d ",
					$customer_delivery_key
				);
				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {
						return $row['Customer Other Delivery Address Formatted'];
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}

			}



		}


		return '';

	}


	function get_other_delivery_address_fields($other_delivery_address_key) {

		$sql=sprintf("select * from `Customer Other Delivery Address Dimension` where `Customer Other Delivery Address Key`=%d ",
			$other_delivery_address_key
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$address_fields=array(

					'Address Recipient'=>$row['Customer Other Delivery Address Recipient'],
					'Address Organization'=>$row['Customer Other Delivery Address Organization'],
					'Address Line 1'=>$row['Customer Other Delivery Address Line 1'],
					'Address Line 2'=>$row['Customer Other Delivery Address Line 2'],
					'Address Sorting Code'=>$row['Customer Other Delivery Address Sorting Code'],
					'Address Postal Code'=>$row['Customer Other Delivery Address Postal Code'],
					'Address Dependent Locality'=>$row['Customer Other Delivery Address Dependent Locality'],
					'Address Locality'=>$row['Customer Other Delivery Address Locality'],
					'Address Administrative Area'=>$row['Customer Other Delivery Address Administrative Area'],
					'Address Country 2 Alpha Code'=>$row['Customer Other Delivery Address Country 2 Alpha Code'],


				);
				return $address_fields;


			}else {

				return false;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function get_hello() {


		$unknown_name='';
		$greeting_prefix=_('Hello');

		if ($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
			return $unknown_name;
		$greeting=$greeting_prefix.' '.$this->data['Customer Main Contact Name'];
		if ($this->data['Customer Type']=='Company') {
			$greeting.=', '.$this->data['Customer Name'];
		}
		return $greeting;

	}





	function get_formatted_id_link($customer_id_prefix='') {
		return sprintf('<a class="id" href="customer.php?id=%d">%s</a>', $this->id, $this->get_formatted_id($customer_id_prefix));

	}



	function get_formatted_id($customer_id_prefix='') {
		return sprintf("%s%04d", $customer_id_prefix, $this->id);
	}


	function update_custom_fields($id, $value) {
		$this->update(array($id=>$value));
	}



	function update_tax_number($value) {

		include_once 'utils/validate_tax_number.php';

		$this->update_field('Customer Tax Number', $value);



		if ($this->updated) {

			$tax_validation_data=validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Billing Address 2 Alpha Country Code']);

			$this->update(
				array(
					'Customer Tax Number Valid'=>$tax_validation_data['Tax Number Valid'],
					'Customer Tax Number Details Match'=>$tax_validation_data['Tax Number Details Match'],
					'Customer Tax Number Validation Date'=>$tax_validation_data['Tax Number Validation Date'],
					'Customer Tax Number Validation Source'=>'Online',
					'Customer Tax Number Validation Message'=>$tax_validation_data['Tax Number Validation Message'],
				)
				, 'no_history');


			$this->new_value=$value;



		}

		$this->other_fields_updated=array(
			'Customer_Tax_Number_Valid'=>array(
				'field'=>'Customer_Tax_Number_Valid',
				'render'=>($this->get('Customer Tax Number')==''?false:true),
				'value'=>$this->get('Customer Tax Number Valid'),
				'formatted_value'=>$this->get('Tax Number Valid'),


			)
		);


	}


	function update_tax_number_valid($value) {

		include_once 'utils/validate_tax_number.php';

		if ($value=='Auto') {

			$tax_validation_data=validate_tax_number($this->data['Customer Tax Number'], $this->data['Customer Billing Address 2 Alpha Country Code']);

			$this->update(
				array(
					'Customer Tax Number Valid'=>$tax_validation_data['Tax Number Valid'],
					'Customer Tax Number Details Match'=>$tax_validation_data['Tax Number Details Match'],
					'Customer Tax Number Validation Date'=>$tax_validation_data['Tax Number Validation Date'],
					'Customer Tax Number Validation Source'=>'Online',
					'Customer Tax Number Validation Message'=>$tax_validation_data['Tax Number Validation Message'],
				)
				, 'no_history');

		}else {
			$this->update_field('Customer Tax Number Valid', $value);
			$this->update(
				array(
					'Customer Tax Number Details Match'=>'Unknown',
					'Customer Tax Number Validation Date'=>$this->editor['Date'],
					'Customer Tax Number Validation Source'=>'Manual',
					'Customer Tax Number Validation Message'=>$this->editor['Author Name'],
				)
				, 'no_history');
		}


		$this->other_fields_updated=array(
			'Customer_Tax_Number'=>array(
				'field'=>'Customer_Tax_Number',
				'render'=>true,
				'value'=>$this->get('Customer Tax Number'),
				'formatted_value'=>$this->get('Tax Number'),


			)
		);



	}





	function get_last_order() {
		$order_key=0;
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d order by `Order Date` desc  ", $this->id);
		// $sql=sprintf("select *  from `Order Dimension` limit 10");
		// print "$sql\n";
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			//   print_r($row);
			$order_key=$row['Order Key'];
			//print "****************$order_key\n";

			//  exit;
		}

		return $order_key;
	}



	function add_customer_history($history_data, $force_save=true, $deleteable='No', $type='Changes') {

		return $this->add_subject_history($history_data, $force_save, $deleteable, $type);
	}















	function get_tax_rate() {
		$rate=0;
		$sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
			prepare_mysql($this->data['Customer Tax Category Code']));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$rate=$row['Tax Category Rate'];
		}
		return $rate;
	}





	function users_last_login() {

		$user_keys=array();
		$sql=sprintf("select max(`User Last Login`) as last_login from `User Dimension` U      where  `User Type`='Customer' and `User Parent Key`=%d "
			, $this->id

		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			return strftime('%x', strtotime($row['last_login']));
		}

		return '';
	}


	function users_last_failed_login() {

		$user_keys=array();
		$sql=sprintf("select max(`User Last Failed Login`) as last_login from `User Dimension` U      where  `User Type`='Customer' and `User Parent Key`=%d "
			, $this->id

		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			return strftime('%x', strtotime($row['last_login']));
		}

		return '';
	}


	function users_number_logins() {

		$user_keys=array();
		$sql=sprintf("select sum(`User Login Count`) as logins from `User Dimension` U      where  `User Type`='Customer' and `User Parent Key`=%d "
			, $this->id

		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			return number($row['logins']);
		}

		return 0;
	}


	function users_number_failed_logins() {

		$user_keys=array();
		$sql=sprintf("select sum(`User Failed Login Count`) as logins from `User Dimension` U      where  `User Type`='Customer' and `User Parent Key`=%d "
			, $this->id

		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			return number($row['logins']);
		}

		return 0;
	}


	function get_users_keys() {
		$user_keys=array();
		$sql=sprintf("select `User Key` from `User Dimension` U
        where  `User Type`='Customer' and `User Parent Key`=%d "
			, $this->id

		);



		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$user_keys[$row['User Key']]=$row['User Key'];
		}

		return $user_keys;
	}


	function get_main_email_user_key() {
		$user_key=0;
		$sql=sprintf("select `User Key` from  `User Dimension` where `User Handle`=%s and `User Type`='Customer' and `User Parent Key`=%d "

			, prepare_mysql($this->data['Customer Main Plain Email'])
			, $this->id
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user_key=$row['User Key'];
		}
		return $user_key;
	}



	function get_other_delivery_addresses_data() {
		$sql=sprintf("select `Customer Other Delivery Address Key`,`Customer Other Delivery Address Formatted`,`Customer Other Delivery Address Label` from `Customer Other Delivery Address Dimension` where `Customer Other Delivery Address Customer Key`=%d order by `Customer Other Delivery Address Key`",
			$this->id
		);

		$delivery_address_keys=array();

		if ($result=$this->db->query($sql)) {

			foreach ($result as $row) {
				$delivery_address_keys[$row['Customer Other Delivery Address Key']]= array(
					'value'=>$this->get('Customer Other Delivery Address '.$row['Customer Other Delivery Address Key']),
					'formatted_value'=>$row['Customer Other Delivery Address Formatted'],
					'label'=>$row['Customer Other Delivery Address Label'],
				);
			}

		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $delivery_address_keys;


	}


	function get_other_email_login_handle() {
		$other_login_handle_emails=array();
		foreach ($this->get_other_emails_data() as $email) {
			$sql=sprintf("select `User Key` from `User Dimension` where `User Handle`='%s'", $email['email']);

			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result)) {
				$other_login_handle_emails[$email['email']]=$email['email'];
			}
		}

		return $other_login_handle_emails;
	}








	function is_tax_number_valid() {
		if ($this->data['Customer Tax Number']=='')
			return false;
		else {
			return true;
		}

	}









	function get_order_in_process_key($dispatch_state='all') {

		if ($dispatch_state=='all') {
			$dispatch_state_valid_values="'In Process by Customer','Waiting for Payment Confirmation'";
		}else {
			$dispatch_state_valid_values="'In Process by Customer'";
		}

		$order_key=false;
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State` in (%s) ",
			$this->id,
			$dispatch_state_valid_values
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			//print_r($row);

			$order_key=$row['Order Key'];
		}
		return $order_key;
	}


	function get_order_in_process_keys($dispatch_state='all') {

		if ($dispatch_state=='all') {
			$dispatch_state_valid_values="'In Process by Customer','Waiting for Payment Confirmation'";
		}else {
			$dispatch_state_valid_values="'In Process by Customer'";
		}

		$order_keys=array();
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State` in (%s) ",
			$this->id,
			$dispatch_state_valid_values
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			//print_r($row);

			$order_keys[$row['Order Key']]=$row['Order Key'];
		}
		return $order_keys;
	}


	function get_pending_orders_keys($dispatch_state='') {

		$dispatch_state_valid_values="'Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Packing','Packed','Packed Done'";

		if ($dispatch_state=='all') {

			$dispatch_state_valid_values.=",'In Process','In Process by Customer','Waiting for Payment Confirmation'";
		}

		$order_keys=array();
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State` in (%s) ",
			$this->id,
			$dispatch_state_valid_values
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			//print_r($row);

			$order_keys[$row['Order Key']]=$row['Order Key'];
		}
		return $order_keys;
	}


	function get_credits() {

		$sql=sprintf("select sum(`Credit Saved`) as value from `Order Post Transaction Dimension` where `Customer Key`=%d and `State`='Saved' ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$credits=$row['value'];
		}
		return $credits;
	}


	function get_credits_formatted() {

		$credits=$this->get_credits();

		$store=new Store($this->data['Customer Store Key']);



		return money($credits, $store->data['Store Currency Code']);
	}





	function update_full_search() {

		$store=new Store($this->data['Customer Store Key']);


		$address_plain=strip_tags($this->get('Contact Address'));
		$first_full_search=$this->data['Customer Name'].' '.$this->data['Customer Name'].' '.$address_plain.' '.$this->data['Customer Main Contact Name'].' '.$this->data['Customer Main Plain Email'];
		$second_full_search=$this->data['Customer Type'];


		$description='';

		if ($this->data['Customer Type']=='Company') {
			$name='<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formatted_id_link().')<br/>'.$this->data['Customer Main Contact Name'];
		} else {
			$name='<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formatted_id_link().')';

		}
		$name.='<br/>'._('Orders').':<b>'.number($this->data['Customer Orders']).'</b>';


		$_address=$this->data['Customer Main Plain Email'];

		if ($this->data['Customer Main Telephone Key'])$_address.='<br/>T: '.$this->data['Customer Main XHTML Telephone'];
		$_address.='<br/>'.$this->data['Customer Main Location'];
		if ($this->data['Customer Main Postal Code'])$_address.=', '.$this->data['Customer Main Postal Code'];
		$_address=preg_replace('/^\<br\/\>/', '', $_address);

		$description='<table ><tr style="border:none;"><td class="col1">'.$name.'</td><td class="col2">'.$_address.'</td></tr></table>';

		//$sql=sprintf("select `Search Full Text Key` from `Search Full Text Dimension` where `Store Key`=%d,`Subject`='Customer',`Subject Key`=%d",
		//
		//,$this->data['Customer Store Key']
		// ,$this->id
		//);



		$sql=sprintf("insert into `Search Full Text Dimension`  (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)
                     values  (%s,%s,%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s",
			$this->data['Customer Store Key'],
			prepare_mysql('Customer'),
			$this->id,
			prepare_mysql($first_full_search),
			prepare_mysql($second_full_search),
			prepare_mysql($this->data['Customer Name']),
			prepare_mysql($description),
			"''",
			prepare_mysql($first_full_search),
			prepare_mysql($second_full_search),
			prepare_mysql($this->data['Customer Name']),
			prepare_mysql($description),
			"''"
		);
		//print $sql;
		$this->db->exec($sql);
	}


	function add_history_login($data) {
		$history_data=array(
			// 'login','logout','fail_login','password_request','password_reset'
			'Date'=>$data['Date'],

			'Direct Object'=>'Site',
			'Direct Object Key'=>$data['Site Key'],
			'History Details'=>$data['Details'],
			'History Abstract'=>$data['Note'],
			'Action'=>$data['Action'],
			'Preposition'=>'Preposition',
			'Indirect Object'=>$data['Indirect Object'],
			'User Key'=>$data['User Key']
		);

		$history_key=$this->add_history($history_data, $force_save=true);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','WebLog')", $this->id, $history_key);

		mysql_query($sql);
	}




	function add_history_new_order($order, $text_locale='en_GB') {

		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;


		switch ($text_locale) {
		default :
			$note = sprintf( '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order Processed'), $order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($order->data['Order Original Data MIME Type']='application/inikoo') {

				if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
					$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->editor['Author Key'], $this->editor['Author Alias'] , $this->get ( 'Customer Name' ), $this->id, $this->get('Formatted ID'), strftime( "%e %b %Y %H:%M", strtotime( $order->data ['Order Date'] ) ) );
				} else {
					$details = sprintf( 'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',
						$this->get ( 'Customer Name' ),
						$this->id, $this->get('Formatted ID'),
						$tz_date
					);

				}
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s',
					$this->get ( 'Customer Name' ), $this->id, $this->get('Formatted ID'),
					$tz_date
				);
			}
			if ($order->data['Order Original Data MIME Type']='application/vnd.ms-excel') {
				if ($order->data['Order Original Data Filename']!='') {

					$details .='<div >'._('Original Source').":<img src='art/icons/page_excel.png'> ".$order->data['Order Original Data MIME Type']."</div>";

					$details .='<div>'._('Original Source Filename').": ".$order->data['Order Original Data Filename']."</div>";



				}
			}

		}
		$history_data=array(
			'Date'=>$order->data ['Order Date'],
			'Subject'=>'Customer',
			'Subject Key'=>$this->id,
			'Direct Object'=>'Order',
			'Direct Object Key'=>$order->data ['Order Key'],
			'History Details'=>$details,
			'History Abstract'=>$note,
			'Metadata'=>'Process'
		);

		$history_key=$order->add_history($history_data);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')", $this->id, $history_key);

		mysql_query($sql);

	}


	function add_history_order_cancelled($history_key) {





		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')", $this->id, $history_key);
		mysql_query($sql);



	}


	function add_history_order_suspended($order) {


		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Suspended Date']." +00:00" ) );
		$tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :
			$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (Suspended)', $order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
				$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> suspended %s (<a href="customer.php?id=%d">%s</a>) order <a href="order.php?id=%d">%s</a>  on %s',
					$this->editor['Author Key'],
					$this->editor['Author Alias'] ,
					$this->get ( 'Customer Name' ),
					$this->id,
					$this->get('Formatted ID'),
					$order->data ['Order Key'],
					$order->data ['Order Public ID'],
					$tz_date
				);
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>)  order <a href="order.php?id=%d">%s</a>  has been suspended on %s',

					$this->get ( 'Customer Name' ),
					$this->id, $this->get('Formatted ID'),
					$order->data ['Order Key'],
					$order->data ['Order Public ID'],
					$tz_date
				);

			}
			if ($order->data ['Order Suspend Note']!='')
				$details.='<div> Note: '.$order->data ['Order Suspend Note'].'</div>';


		}
		$history_data=array(
			'Date'=>$order->data ['Order Suspended Date'],
			'Subject'=>'Customer',
			'Subject Key'=>$this->id,
			'Direct Object'=>'Order',
			'Direct Object Key'=>$order->data ['Order Key'],
			'History Details'=>$details,
			'History Abstract'=>$note,
			'Metadata'=>'Suspended'

		);

		$sql=sprintf("update `History Dimension` set `Deep`=2 where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d ",
			$this->id,
			$order->id
		);
		mysql_query($sql);
		$history_key=$order->add_history($history_data);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')", $this->id, $history_key);
		mysql_query($sql);


		switch ($lang) {
		default :
			$note_created = sprintf( '%s <a href="order.php?id=%d">%s</a> (Created)', _('Order'), $order->data ['Order Key'], $order->data ['Order Public ID'] );

		}
		$sql=sprintf("update `History Dimension` set `History Abstract`=%s where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d and `Metadata`='Process'",
			prepare_mysql($note_created),
			$this->id,
			$order->id
		);
		mysql_query($sql);

	}


	function add_history_post_order_in_warehouse($dn) {


		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $dn->data ['Delivery Note Date Created']." +00:00" ) );
		$tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $dn->data ['Delivery Note Date Created']." +00:00" ) );

		date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :
			$state=$dn->data['Delivery Note State'];
			$note = sprintf( '%s <a href="dn.php?id=%d">%s</a> (%s)', $dn->data['Delivery Note Type'], $dn->data ['Delivery Note Key'], $dn->data ['Delivery Note ID'], $state );
			$details=$dn->data['Delivery Note Title'];

			if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
				$details.= '';
			} else {
				$details.= '';

			}



		}
		$history_data=array(
			'Date'=>$dn->data ['Delivery Note Date Created'],
			'Subject'=>'Customer',
			'Subject Key'=>$this->id,
			'Direct Object'=>'After Sale',
			'Direct Object Key'=>$dn->data ['Delivery Note Key'],
			'History Details'=>$details,
			'History Abstract'=>$note,
			'Metadata'=>'Post Order'

		);

		//   print_r($history_data);

		$history_key=$dn->add_history($history_data);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')", $this->id, $history_key);
		mysql_query($sql);




	}


	function add_history_order_refunded($refund) {
		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $refund->data ['Invoice Date']." +00:00" ) );
		//    $tz_date_created=strftime ( "%e %b %Y %H:%M %Z", strtotime ( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :



			$note=$refund->data['Invoice XHTML Orders'].' '._('refunded for').' '.money(-1*$refund->data['Invoice Total Amount'], $refund->data['Invoice Currency']);
			$details=_('Date refunded').": $tz_date";





		}


		$history_data=array(
			'History Abstract'=>$note,
			'History Details'=>$details,
			'Action'=>'created',
			'Direct Object'=>'Invoice',
			'Direct Object Key'=>$refund->id,
			'Prepostion'=>'on',
			// 'Indirect Object'=>'User'
			//'Indirect Object Key'=>0,
			'Date'=>$refund->data ['Invoice Date']



		);




		//print_r($history_data);

		$history_key=$this->add_subject_history($history_data, $force_save=true, $deleteable='No', $type='Orders');



	}


	function update_history_order_in_warehouse($order) {


		//  date_default_timezone_set(TIMEZONE) ;
		//  $tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Cancelled Date']." +00:00" ) );
		//  $tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		//  date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :
			$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (%s) %s %s' ,
				$order->data ['Order Key'],
				$order->data ['Order Public ID'],
				$order->data['Order Current XHTML Payment State'],
				$order->get('Weight'),
				money($order->data['Order Invoiced Balance Total Amount'], $order->data['Order Currency'])

			);



		}

		$sql=sprintf("update `History Dimension` set  `History Abstract`=%s where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d and `Metadata`='Process'",

			prepare_mysql($note),
			$this->id,
			$order->id
		);
		mysql_query($sql);

		/*
		$sql=sprintf("update `History Dimension` set `History Date`=%s, `History Abstract`=%s where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d and `Metadata`='Process'",
			prepare_mysql($date),
			prepare_mysql($note),
			$this->id,
			$order->id
		);
		mysql_query($sql);
		//print "$sql\n";
		*/

	}


	function add_history_new_post_order($order, $type) {

		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;


		switch ($_SESSION ['lang']) {
		default :
			$note = sprintf( '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order'), $order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($order->data['Order Original Data MIME Type']='application/inikoo') {

				if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
					$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s', $this->editor['Author Key'], $this->editor['Author Alias'] , $this->get ( 'Customer Name' ), $this->id, $this->get('Formatted ID'), strftime( "%e %b %Y %H:%M", strtotime( $order->data ['Order Date'] ) ) );
				} else {
					$details = sprintf( 'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',
						$this->get ( 'Customer Name' ),
						$this->id, $this->get('Formatted ID'),
						$tz_date
					);

				}
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s',
					$this->get ( 'Customer Name' ), $this->id, $this->get('Formatted ID'),
					$tz_date
				);
			}
			if ($order->data['Order Original Data MIME Type']='application/vnd.ms-excel') {
				if ($order->data['Order Original Data Filename']!='') {

					$details .='<div >'._('Original Source').":<img src='art/icons/page_excel.png'> ".$order->data['Order Original Data MIME Type']."</div>";

					$details .='<div>'._('Original Source Filename').": ".$order->data['Order Original Data Filename']."</div>";



				}
			}

		}
		$history_data=array(
			'Date'=>$order->data ['Order Date'],
			'Subject'=>'Customer',
			'Subject Key'=>$this->id,
			'Direct Object'=>'Order',
			'Direct Object Key'=>$order->data ['Order Key'],
			'History Details'=>$details,
			'History Abstract'=>$note,
			'Metadata'=>'Process'
		);
		$history_key=$order->add_history($history_data);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')", $this->id, $history_key);
		mysql_query($sql);

	}


	function get_number_of_orders() {
		$sql=sprintf("select count(*) as number from `Order Dimension` where `Order Customer Key`=%d ", $this->id);
		$number=0;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number=$row['number'];
		}
		return $number;


	}


	function remove_principal_address() {

		$this->remove_address($this->data['Customer Main Address Key']);

	}


	function remove_address($address_key) {


		if ($this->data['Customer Type']=='Person') {
			$contact=new Contact($this->data['Customer Company Key']);
			$contact->remove_address($address_key);

		}
		elseif ($this->data['Customer Type']=='Company') {
			$company=new Company($this->data['Customer Company Key']);

			$company->remove_address($address_key);
		}


	}


	function close_account() {
		$sql=sprintf("update `Customer Dimension` set `Customer Account Operative`='No' where `Customer Key`=%d ", $this->id);
		mysql_query();

	}






	function delete($note='', $customer_id_prefix='') {
		$this->deleted=false;
		$deleted_company_keys=array();

		$address_to_delete=array();
		$emails_to_delete=array();
		$telecom_to_delete=array();



		$has_orders=false;
		$sql="select count(*) as total  from `Order Dimension` where `Order Customer Key`=".$this->id;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['total']>0)
				$has_orders=true;
		}

		if ($has_orders) {
			$this->msg=_("Customer can't be deleted");
			return;
		}

		$address_to_delete=$this->get_address_keys();
		$emails_to_delete=$this->get_email_keys();
		$telecom_to_delete=$this->get_telecom_keys();

		$history_data=array(
			'History Abstract'=>_('Customer Deleted'),
			'History Details'=>'',
			'Action'=>'deleted'
		);

		$this->add_history($history_data, $force_save=true);



		$company_keys=array();

		$contact_keys=$this->get_contact_keys();
		$company_keys=$this->get_company_keys();



		$sql=sprintf("delete from `Customer Dimension` where `Customer Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer Correlation` where `Customer A Key`=%d or `Customer B Key`=%s", $this->id, $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer History Bridge` where `Customer Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `List Customer Bridge` where `Customer Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer Ship To Bridge` where `Customer Key`=%d", $this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Customer Billing To Bridge` where `Customer Key`=%d", $this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Customer Send Post` where `Customer Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Search Full Text Dimension` where `Subject`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Address Bridge` where `Subject Type`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Category Bridge` where `Subject`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Company Bridge` where `Subject Type`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Contact Bridge` where `Subject Type`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Email Bridge` where `Subject Type`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Telecom Bridge` where `Subject Type`='Customer' and `Subject Key`=%d", $this->id);
		mysql_query($sql);



		$sql=sprintf("delete from `Customer Send Post` where  `Customer Key`=%d", $this->id);
		mysql_query($sql);


		//$sql=sprintf("select `User Key` from `User Dimension`  where `User Type`='Customer' and `User Parent Key`=%d ",$this->id);
		//$res=mysql_query($sql);
		//while ($row=mysql_fetch_assoc($res)) {
		// $sql=sprintf("delete from `User Group User Bridge` where `User Key`=%d",$row['User Key']);
		// mysql_query($sql);
		// $sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d",$row['User Key']);
		// mysql_query($sql);
		// $sql=sprintf("delete from `User Rights Bridge` where `User Key`=%d",$row['User Key']);
		// mysql_query($sql);
		//}

		//$sql=sprintf("delete from `User Dimension` where `User Type`='Customer' and `User Parent Key`=%d",$this->id);
		//mysql_query($sql);


		$users_to_desactivate=$this->get_users_keys();
		foreach ($users_to_desactivate as $_user_key) {
			$_user=new User($_user_key);
			if ($_user->id) {
				$_user->deactivate();
			}
		}






		// Delete if the email has not been send yet
		//Email Campaign Mailing List

		$sql=sprintf("insert into `Customer Deleted Dimension` value (%d,%d,%s,%s,%s,%s,%s,%s) ",
			$this->id,
			$this->data['Customer Store Key'],
			prepare_mysql($this->data['Customer Name']),
			prepare_mysql($this->data['Customer Main Contact Name']),
			prepare_mysql($this->data['Customer Main Plain Email']),
			prepare_mysql($this->display('card', $customer_id_prefix)),
			prepare_mysql($this->editor['Date']),
			prepare_mysql($note, false)
		);


		mysql_query($sql);



		if ($this->data['Customer Type']=='Company') {

			//unset($company_keys[$this->data['Customer Company Key']]);
			//     print_r($company_keys);

			foreach ($company_keys as  $company_key) {
				$company=new Company($company_key);
				$company_customer_keys=$company->get_parent_keys('Customer');
				$company_supplier_keys=$company->get_parent_keys('Supplier');
				$company_account_key=$company->get_parent_keys('Account');
				$company_telecom_keys=$company->get_telecom_keys();

				$company_address_keys=$company->get_address_keys();
				$company_contact_keys=$company->get_parent_keys('Contact');

				unset($company_customer_keys[$this->id]);
				foreach ($contact_keys as $contact_key) {
					unset($company_contact_keys[$contact_key]);
				}
				//  print_r($company_contact_keys);
				//  print_r($company_customer_keys);
				if (count($company_customer_keys)==0 and count($company_supplier_keys)==0 and count($company_contact_keys)==0 and count($company_account_key)==0) {
					$company->delete();
					$deleted_company_keys[$company->id]=$company->id;



					foreach ($company_address_keys as $company_address_key) {
						$address_to_delete[$company_address_key]=$company_address_key;
					}
					foreach ($company_telecom_keys as $company_telecom_key) {
						$telecom_to_delete[$company_telecom_key]=$company_telecom_key;
					}


				} else {

				}
			}

		}

		foreach ($contact_keys as $contact_key) {
			$contact=new Contact($contact_key);

			$contact_email_keys=$contact->get_email_keys();
			$contact_telecom_keys=$contact->get_telecom_keys();
			$contact_address_keys=$contact->get_address_keys();

			$contact_customer_keys=$contact->get_parent_keys('Customer');
			$contact_supplier_keys=$contact->get_parent_keys('Supplier');
			$contact_company_keys=$contact->get_parent_keys('Company');



			$contact_staff_keys=$contact->get_parent_keys('Staff');

			foreach ($deleted_company_keys as $deleted_company_key) {
				unset($contact_company_keys[$deleted_company_key]);
			}
			unset($contact_customer_keys[$this->id]);


			if (count($contact_customer_keys)==0 and count($contact_supplier_keys)==0 and count($contact_company_keys)==0 and count($contact_staff_keys)==0) {



				$contact->delete();

				foreach ($contact_email_keys as $contact_email_key) {
					$emails_to_delete[$contact_email_key]=$contact_email_key;
				}
				foreach ($contact_address_keys as $contact_address_key) {
					$address_to_delete[$contact_address_key]=$contact_address_key;
				}
				foreach ($contact_telecom_keys as $contact_telecom_key) {
					$telecom_to_delete[$contact_telecom_key]=$contact_telecom_key;
				}


			} else {

			}


		}



		foreach ($emails_to_delete as $email_key) {
			$email=new Email($email_key);
			if ($email->id and !$email->has_parents()) {
				$email->delete();
			}
		}



		foreach ($address_to_delete as $address_key) {
			$address=new Address($address_key);
			if ($address->id and !$address->has_parents()) {
				$address->delete();
			}
		}



		foreach ($telecom_to_delete as $telecom_key) {
			$telecom=new Telecom($telecom_key);
			if ($telecom->id and !$telecom->has_parents()) {
				$telecom->delete();
			}
		}
		$store=new Store($this->data['Customer Store Key']);
		$store->update_customers_data();

		$this->deleted=true;
	}


	function merge($customer_key, $customer_id_prefix='') {
		$this->merged=false;

		$customer_to_merge=new Customer($customer_key);
		$customer_to_merge->editor=$this->editor;

		if (!$customer_to_merge->id) {
			$this->error=true;
			$this->msg='Customer not found';
			return;
		}

		if ($this->id==$customer_to_merge->id) {
			$this->error=true;
			$this->msg=_('Same Customer');
			return;
		}


		if ($this->data['Customer Store Key']!=$customer_to_merge->data['Customer Store Key']) {
			$this->error=true;
			$this->msg=_('Customers from different stores');
			return;
		}

		// Deactivate to_marge_users & change the customer key

		$users_to_desactivate=$customer_to_merge->get_users_keys();
		foreach ($users_to_desactivate as $_user_key) {
			$_user=new User($_user_key);
			if ($_user->id) {
				$_user->deactivate();
			}
		}

		foreach ($users_to_desactivate as $_user_key) {

			$sql=sprintf("update `User Dimension` set `User Parent Key`=%d where `User Key`=%d  "     ,
				$this->id,
				$_user_key
			);
			mysql_query($sql);
		}





		$sql=sprintf("select `History Key` from `Customer History Bridge` where `Type` in ('Orders','Notes') and `Customer Key`=%d ", $customer_to_merge->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$history_key=$row['History Key'];
			$sql=sprintf("select * from `History Dimension` where `History Key`=%d ", $history_key);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("update `History Dimension` set `Subject Key`=%d   where `History Key`=%d and `Subject`='Customer' ", $this->id, $history_key);
				mysql_query($sql);
				$sql=sprintf("update `History Dimension` set `Direct Object Key`=%d   where `History Key`=%d and `Direct Object`='Customer' ", $this->id, $history_key);
				mysql_query($sql);
				$sql=sprintf("update `History Dimension` set `Indirect Object Key`=%d  where `History Key`=%d and `Indirect Object`='Customer' ", $this->id, $history_key);
				mysql_query($sql);
			}
		}
		$sql=sprintf("update `Customer History Bridge` set `Customer Key`=%d where `Type` in ('Orders','Notes') and `Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Customer History Bridge` set `Customer Key`=%d where `Type` in ('Orders','Notes') and `Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Customer Ship To Bridge` set `Customer Key`=%d where `Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);
		$sql=sprintf("update `Customer Billing To Bridge` set `Customer Key`=%d where `Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Delivery Note Dimension` set `Delivery Note Customer Key`=%d where `Delivery Note Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Invoice Dimension` set `Invoice Customer Key`=%d where `Invoice Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Order Dimension` set `Order Customer Key`=%d where `Order Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Order Transaction Fact` set `Customer Key`=%d where `Customer Key`=%d ", $this->id, $customer_to_merge->id);
		$res=mysql_query($sql);



		if (strtotime($customer_to_merge->data['Customer First Contacted Date'])<strtotime($this->data['Customer First Contacted Date'])) {
			$customer->data['Customer First Contacted Date']=$customer_to_merge->data['Customer First Contacted Date'];
			$sql=sprintf("update `Customer Dimension` set `Customer First Contacted Date`=%s where `Customer Key`=%d ",
				prepare_mysql($customer->data['Customer First Contacted Date']),
				$this->id);
			$res=mysql_query($sql);
			$sql=sprintf("update `History Dimension` set `History Date`=%s   where `Action`='created' and `Direct Object`='Customer' and `Direct Object Key`=%d  and `Indirect Object`='' ",
				prepare_mysql($customer->data['Customer First Contacted Date']),
				$this->id);
			$res=mysql_query($sql);

		}











		$history_data=array(
			'History Abstract'=>_('Customer').' '.$customer_to_merge->get_formatted_id_link($customer_id_prefix).' '._('merged'),
			'History Details'=>_('Orders Transfered').':'.$customer_to_merge->get('Orders').'<br/>'._('Notes Transfered').':'.$customer_to_merge->get('Notes').'<br/>',
			'Direct Object'=>'Customer',
			'Direct Object Key'=>$customer_to_merge->id,
			'Indirect Object'=>'Customer',
			'Indirect Object Key'=>$this->id,
			'Action'=>'merged',
			'Preposition'=>'to'
		);
		$this->add_subject_history($history_data);


		$customer_to_merge->update_orders();

		$this->update_orders();

		$store=new Store($this->data['Customer Store Key']);
		$store->update_customer_activity_interval();

		$this->update_activity();
		$this->update_is_new();

		$customer_to_merge->delete('', $customer_id_prefix);


		$sql=sprintf("update `Customer Merge Bridge` set `Customer Key`=%d,`Date Merged`=%s where `Customer Key`=%d ", $this->id, prepare_mysql($this->editor['Date']), $customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("insert into  `Customer Merge Bridge` values(%d,%d,%s) ", $customer_to_merge->id, $this->id, prepare_mysql($this->editor['Date']));
		$res=mysql_query($sql);

		$store=new Store($this->data['Customer Store Key']);
		$store->update_customer_activity_interval();


		$this->merged=true;;

		//Customer Key


		//Email Campaign Mailing List

	}


	function update_subscription($customer_id, $type) {
		if (!isset($customer_id) || !isset($type)) {
			return;
		}


	}


	function get_order_key() {
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d order by `Order Key` DESC", $this->id);
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			return $row['Order Key'];
		} else
			return -1;
	}


	function get_faxes() {
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Fax'    and `Subject Type`='Contact' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ", $this->id);
		$telephones=array();
		$result=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telephone= new Telecom($row['Telecom Key']);
			$telephone->set_scope('Contact', $this->id);
			$telephones[]= $telephone;
			$telephone->data['Mobile Is Main']=$row['Is Main'];

		}
		//$this->number_mobiles=count($mobiles);
		return $telephones;
	}












	function get_image_src() {
		$image=false;

		$user_keys=$this->get_users_keys();

		if (count($user_keys)>0) {

			$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key` in (%s)",
				join($user_keys));
			$res=mysql_query($sql);



			$image=false;
			while ($row=mysql_fetch_array($res)) {
				//if ($row['Image Height']!=0)
				// $ratio=$row['Image Width']/$row['Image Height'];
				//else
				// $ratio=1;
				//  print_r($row);
				if ($row['Image Key']) {

					$image='image.php?id='.$row['Image Key'].'&size=small';


					return $image;
				}

			}

			return $image;
		}
		return false;


	}


	function update_web_data() {

		$failed_logins=0;
		$logins=0;
		$requests=0;

		$sql=sprintf("select sum(`User Login Count`) as logins, sum(`User Failed Login Count`) as failed_logins, sum(`User Requests Count`) as requests  from `User Dimension` where `User Type`='Customer' and `User Parent Key`=%d",
			$this->id
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$failed_logins=$row['failed_logins'];
			$logins=$row['logins'];
			$requests=$row['requests'];
		}

		$sql=sprintf("update `Customer Dimension` set `Customer Number Web Logins`=%d , `Customer Number Web Failed Logins`=%d, `Customer Number Web Requests`=%d where `Customer Key`=%d",
			$logins,
			$failed_logins,
			$requests,
			$this->id
		);
		//print "$sql\n";
		mysql_query($sql);

	}


	function get_category_data() {
		$sql=sprintf("select `Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where  `Category Branch Type`='Head'  and B.`Subject Key`=%d and B.`Subject`='Customer'", $this->id);

		$category_data=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {



			$sql=sprintf("select `Category Label`,`Category Code` from `Category Dimension` where `Category Key`=%d", $row['Category Root Key']);

			$res=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res)) {
				$root_label=$row2['Category Label'];
				$root_code=$row2['Category Code'];
			}


			if ($row['Is Category Field Other']=='Yes' and $row['Other Note']!='') {
				$value=$row['Other Note'];
			}
			else {
				$value=$row['Category Label'];
			}
			$category_data[]=array('root_label'=>$root_label, 'root_code'=>$root_code, 'label'=>$row['Category Label'], 'label'=>$row['Category Code'], 'value'=>$value);
		}

		return $category_data;
	}


	function update_rankings() {
		$total_customers_with_less_invoices=0;
		$total_customers_with_less_balance=0;
		$total_customers_with_less_orders=0;
		$total_customers_with_less_profit=0;

		$total_customers=0;
		$sql=sprintf("select count(*) as customers from `Customer Dimension` where `Customer Store Key`=%d",
			$this->data['Customer Store Key']);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$total_customers=$row['customers'];

		}




		$sql=sprintf("select count(*) as customers from `Customer Dimension` USE INDEX (`Customer Orders Invoiced`)  where `Customer Store Key`=%d and `Customer Orders Invoiced`<%d",
			$this->data['Customer Store Key'],
			$this->data['Customer Orders Invoiced']

		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$total_customers_with_less_invoices=$row['customers'];

		}
		$sql=sprintf("select count(*) as customers from `Customer Dimension` USE INDEX (`Customer Orders`) where `Customer Store Key`=%d and `Customer Orders`<%d",
			$this->data['Customer Store Key'],

			$this->data['Customer Orders']
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$total_customers_with_less_orders=$row['customers'];

		}


		$sql=sprintf("select count(*) as customers from `Customer Dimension` USE INDEX (`Customer Net Balance`) where `Customer Store Key`=%d and `Customer Net Balance`<%f",
			$this->data['Customer Store Key'],

			$this->data['Customer Net Balance']
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$total_customers_with_less_balance=$row['customers'];

		}
		$sql=sprintf("select count(*) as customers from `Customer Dimension` USE INDEX (`Customer Profit`) where `Customer Store Key`=%d and `Customer Profit`<%f",
			$this->data['Customer Store Key'],
			$this->data['Customer Profit']
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$total_customers_with_less_profit=$row['customers'];

		}

		$this->data['Customer Invoices Top Percentage']=($total_customers==0?0:$total_customers_with_less_invoices/$total_customers);
		$this->data['Customer Orders Top Percentage']=($total_customers==0?0:$total_customers_with_less_orders/$total_customers);
		$this->data['Customer Balance Top Percentage']=($total_customers==0?0:$total_customers_with_less_balance/$total_customers);
		$this->data['Customer Profits Top Percentage']=($total_customers==0?0:$total_customers_with_less_profit/$total_customers);

		$sql=sprintf("update `Customer Dimension` set `Customer Invoices Top Percentage`=%f ,`Customer Orders Top Percentage`=%f ,`Customer Balance Top Percentage`=%f ,`Customer Profits Top Percentage`=%f  where `Customer Key`=%d",
			$this->data['Customer Invoices Top Percentage'],
			$this->data['Customer Orders Top Percentage'],
			$this->data['Customer Balance Top Percentage'],
			$this->data['Customer Profits Top Percentage'],

			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";

	}


	function update_location_type() {

		$store=new Store($this->data['Customer Store Key']);

		if ($this->data['Customer Contact Address Country 2 Alpha Code']==$store->data['Store Home Country Code 2 Alpha'] or $this->data['Customer Contact Address Country 2 Alpha Code']=='XX') {
			$location_type='Domestic';
		}else {
			$location_type='Export';
		}

		$this->update(array('Customer Location Type'=>$location_type));



	}


	function update_postal_address() {
		$store=new Store($this->data['Customer Store Key']);
		$locale=$store->data['Store Locale'];

		$address=new Address($this->data['Customer Main Address Key']);

		$separator="\n";
		$postal_address='';
		if ($this->data['Customer Name']==$this->data['Customer Main Contact Name']) {
			$postal_address=$this->data['Customer Name'];
		}else {
			$postal_address=_trim($this->data['Customer Name']);
			if ($postal_address!='')$postal_address.=$separator;
			$postal_address.=_trim($this->data['Customer Main Contact Name']);

		}
		if ($postal_address!='')$postal_address.=$separator;
		$postal_address.=$address->display('postal', $locale);

		$this->data['Customer Main Postal Address']=_trim($postal_address);

		$sql=sprintf("update `Customer Dimension` set `Customer Main Postal Address`=%s where `Customer Key`=%d",
			prepare_mysql($this->data['Customer Main Postal Address']),
			$this->id
		);

		mysql_query($sql);

	}


	function get_pending_payment_amount_from_account_balance() {
		$pending_amount=0;
		$sql=sprintf("select `Amount` from `Order Payment Bridge` B left join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) left join `Payment Dimension` PD on (PD.`Payment Key`=B.`Payment Key`)  where `Is Account Payment`='Yes' and`Order Customer Key`=%d  and `Payment Transaction Status`='Pending' ",
			$this->id

		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$pending_amount=$row['Amount'];

		}
		return $pending_amount;
	}


	function get_formatted_pending_payment_amount_from_account_balance() {
		return money($this->get_pending_payment_amount_from_account_balance(), $this->data['Customer Currency Code']);
	}


	function get_number_saved_credit_cards($billing_to_key, $ship_to_key) {

		$number_saved_credit_cards=0;
		$sql=sprintf("select count(*) as number from `Customer Credit Card Token Dimension` where `Customer Key`=%d and `Billing To Key`=%d and `Ship To Key`=%d and `Valid Until`>NOW()",
			$this->id,
			$billing_to_key,
			$ship_to_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$number_saved_credit_cards=$row['number'];
		}
		return $number_saved_credit_cards;
	}


	function get_saved_credit_cards($billing_to_key, $ship_to_key) {

		$key=md5($this->id.','.$billing_to_key.','.$ship_to_key.','.CKEY);

		$card_data=array();
		$sql=sprintf("select * from `Customer Credit Card Token Dimension` where `Customer Key`=%d and `Billing To Key`=%d and `Ship To Key`=%d and `Valid Until`>NOW()",
			$this->id,
			$billing_to_key,
			$ship_to_key
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$_card_data=json_decode(AESDecryptCtr($row['Metadata'], $key, 256), true);
			$_card_data['id']=$row['Customer Credit Card Token Key'];

			$card_data[]=$_card_data;

		}

		return $card_data;

	}


	function delete_credit_card($card_key) {


		$tokens=array();
		$sql=sprintf("select `CCUI` from `Customer Credit Card Token Dimension` where `Customer Key`=%d  and `Customer Credit Card Token Key`=%d ",
			$this->id,

			$card_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {


			$sql=sprintf('select `Customer Credit Card Token Key`,`Billing To Key`,`Ship To Key` from `Customer Credit Card Token Dimension`  where `Customer Key`=%d and `CCUI`=%s',
				$this->id,
				prepare_mysql($row['CCUI'])
			);

			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_assoc($res2)) {
				$tokens[]=$this->get_credit_card_token(
					$row2['Customer Credit Card Token Key'],
					$row2['Billing To Key'],
					$row2['Ship To Key']
				);

				$sql=sprintf('delete from `Customer Credit Card Token Dimension`  where `Customer Credit Card Token Key`=%d',
					$row2['Customer Credit Card Token Key']
				);

				mysql_query($sql);
			}
		}

		return $tokens;

	}


	function get_credit_card_token($card_key, $billing_to_key, $ship_to_key) {

		$key=md5($this->id.','.$billing_to_key.','.$ship_to_key.','.CKEY);

		$token=false;
		$sql=sprintf("select `Metadata` from `Customer Credit Card Token Dimension` where `Customer Key`=%d and `Billing To Key`=%d and `Ship To Key`=%d and   `Valid Until`>NOW() and  `Customer Credit Card Token Key`=%d ",
			$this->id,
			$billing_to_key,
			$ship_to_key,
			$card_key
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$_card_data=json_decode(AESDecryptCtr($row['Metadata'], $key, 256), true);
			$token=$_card_data['Token'];

		}

		return $token;

	}


	function save_credit_card($vault, $card_info, $billing_to_key, $ship_to_key) {
		include_once 'aes.php';

		$key=md5($this->id.','.$billing_to_key.','.$ship_to_key.','.CKEY);

		$card_data=AESEncryptCtr(
			json_encode(
				array(
					'Token'=>$card_info['token'],
					'Card Type'=>preg_replace('/\s/', '', $card_info['cardType']),
					'Card Number'=>substr($card_info['bin'], 0, 4).' ****  **** '.$card_info['last4'],
					'Card Expiration'=>$card_info['expirationMonth'].'/'.$card_info['expirationYear'],
					'Card CVV Length'=>($card_info['cardType']=='American Express'?4:3),
					'Random'=>password_hash(time(), PASSWORD_BCRYPT)

				)
			), $key, 256);


		$sql=sprintf("insert into `Customer Credit Card Token Dimension` (`Customer Key`,`Billing To Key`,`Ship To Key`,`CCUI`,`Metadata`,`Created`,`Updated`,`Valid Until`,`Vault`) values (%d,%d,%d,%s,%s,%s,%s,%s,%s)
		ON DUPLICATE KEY UPDATE `Metadata`=%s , `Updated`=%s,`Valid Until`=%s
		 ",
			$this->id,
			$billing_to_key,
			$ship_to_key,
			prepare_mysql($card_info['uniqueNumberIdentifier']),
			prepare_mysql($card_data),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gmdate('Y-m-d H:i:s', strtotime($card_info['expirationYear'].'-'.$card_info['expirationMonth'].'-01 +1 month' ))),
			prepare_mysql($vault),

			prepare_mysql($card_data),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gmdate('Y-m-d H:i:s', strtotime($card_info['expirationYear'].'-'.$card_info['expirationMonth'].'-01 +1 month' )))

		);
		mysql_query($sql);

	}


	function get_custmon_fields() {

		$custom_field=array();
		$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field In Showcase`='Yes' and `Custom Field Table`='Customer'");
		$res = mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
		}

		$show_case=array();
		$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {

			foreach ($custom_field as $key=>$value) {
				$show_case[$value]=$row[$key];
			}
		}

		return $show_case;

	}


	function get_correlation_info() {
		$correlation_msg='';
		$msg='';
		$sql=sprintf("select * from `Customer Correlation` where `Customer A Key`=%d and `Correlation`>200", $this->id);
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_assoc($res2)) {
			$msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>", $this->id, $row2['Customer B Key'], sprintf("%05d", $row2['Customer B Key']));
		}
		$sql=sprintf("select * from `Customer Correlation` where `Customer B Key`=%d and `Correlation`>200", $this->id);
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_assoc($res2)) {
			$msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>", $this->id, $row2['Customer A Key'], sprintf("%05d", $row2['Customer A Key']));
		}

		$msg=preg_replace('/^,/', '', $msg);
		if ($msg!='') {
			$correlation_msg='<p>'._('Potential duplicated').': '.$msg.'</p>';

		}

		return $correlation_msg;

	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Customer Registration Number':
			$label=_('registration number');
			break;
		case 'Customer Tax Number':
			$label=_('tax number');
			break;
		case 'Customer Tax Number Valid':
			$label=_('tax number validity');
			break;
		case 'Customer Company Name':
			$label=_('company name');
			break;
		case 'Customer Main Contact Name':
			$label=_('contact name');
			break;
		case 'Customer Main Plain Email':
			$label=_('email');
			break;
		case 'Customer Main Email':
			$label=_('main email');
			break;
		case 'Customer Other Email':
			$label=_('other email');
			break;
		case 'Customer Main Plain Telephone':
		case 'Customer Main XHTML Telephone':
			$label=_('telephone');
			break;
		case 'Customer Main Plain Mobile':
		case 'Customer Main XHTML Mobile':
			$label=_('mobile');
			break;
		case 'Customer Main Plain FAX':
		case 'Customer Main XHTML Fax':
			$label=_('fax');
			break;
		case 'Customer Other Telephone':
			$label=_('other telephone');
			break;
		case 'Customer Preferred Contact Number':
			$label=_('main contact number');
			break;
		case 'Customer Fiscal Name':
			$label=_('fiscal name');
			break;

		case 'Customer Contact Address':
			$label=_('contact address');
			break;

		case 'Customer Invoice Address':
			$label=_('invoice address');
			break;
		case 'Customer Delivery Address':
			$label=_('delivery address');
			break;
		case 'Customer Other Delivery Address':
			$label=_('other delivery address');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


}


?>
