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
include_once 'class.DB_Table.php';
include_once 'class.Contact.php';
include_once 'class.Order.php';
include_once 'class.Address.php';
include_once 'class.Attachment.php';

class Customer extends DB_Table {
	var $contact_data=false;
	var $ship_to=array();
	var $fuzzy=false;
	var $tax_number_read=false;
	var $warning_messages=array();
	var $warning=false;
	function __construct($arg1=false,$arg2=false) {

		$this->table_name='Customer';
		$this->ignore_fields=array(
			'Customer Key'
			,'Customer Has More Orders Than'
			,'Customer Has More  Invoices Than'
			,'Customer Has Better Balance Than'
			,'Customer Is More Profiteable Than'
			,'Customer Order More Frecuently Than'
			,'Customer Older Than'
			,'Customer Orders Position'
			,'Customer Invoices Position'
			,'Customer Balance Position'
			,'Customer Profit Position'
			,'Customer Order Interval'
			,'Customer Order Interval STD'
			,'Customer Orders Top Percentage'
			,'Customer Invoices Top Percentage'
			,'Customer Balance Top Percentage'
			,'Customer Profits Top Percentage'
			,'Customer First Order Date'
			,'Customer Last Order Date'
		);


		$this->status_names=array(0=>'new');

		if (is_numeric($arg1) and !$arg2) {
			$this->get_data('id',$arg1);
			return;
		}
		if (preg_match('/create anonymous|create anonimous$/i',$arg1)) {
			$this->create_anonymous();
			return;
		}


		if ($arg1=='new') {
			$this->find($arg2,'create');
			return;
		}
		elseif (preg_match('/^find staff/',$arg1)) {
			$this->find_staff($arg2,$arg1);
			return;
		}
		elseif (preg_match('/^find/',$arg1)) {
			$this->find($arg2,$arg1);
			return;
		}
		elseif (preg_match('/^force_create/',$arg1)) {
			$this->prepare_force_create($arg2,$arg1);
			return;
		}

		$this->get_data($arg1,$arg2);


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

	function prepare_force_create($data) {

		if (array_key_exists('Customer Main Plain Email',$data)) {
			$sql=sprintf("select `Customer Key` from `Customer Dimension` left join `Email Bridge` EB on (`Subject Key`=`Customer Key`) left join `Email Dimension` E on (E.`Email Key`=EB.`Email Key`)  where `Subject Type`='Customer'  and `Email`=%s  ", $data['Customer Main Plain Email']);
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->error=true;
				$this->msg='Email already in';
				return;

			}
		}


		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$this->create($data);

	}


	/*
      Method: find_staff
      Find Staff Customer
    */

	function find_staff($staff,$options='') {

		$sql=sprintf("select * from `Customer Dimension` where `Customer Staff`='Yes' and `Customer Staff Key`=%d",$staff->id);
		//print $sql;exit;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$this->id=$this->data['Customer Key'];
		}

		if (!$this->id and preg_match('/create|new/',$options)) {
			$raw_data['Customer Type']='Person';

			$raw_data['Customer Staff']='Yes';
			if ($staff->id) {



				$contact=new Contact($staff->data['Staff Contact Key']);
				$_raw_data=$contact->data;
				foreach ($_raw_data as $key=>$value) {
					$raw_data[preg_replace('/Contact/','Customer',$key)]=$value;
				}

				$raw_data['Customer Staff Key']=$staff->id;
				$raw_data['Customer Main Contact Key']=$staff->data['Staff Contact Key'];
				$raw_data['Customer Name']=$staff->data['Staff Name'];
			} else {
				$contact=new Contact('create anonymous');
				$_raw_data=$contact->data;
				foreach ($raw_data as $key=>$value) {
					$raw_data[preg_replace('/Contact/','Customer',$key)]=$value;
				}
				$raw_data['Customer Staff Key']=0;
				$raw_data['Customer Main Contact Key']=$contact->id;
				$raw_data['Customer Name']='';
			}


			$this->create($raw_data);
		}


	}
	/*

      Method: find
      Find Customer with similar data


    */





	function find($raw_data,$options='') {

		$this->found_child=false;
		$this->found_child_key=0;
		$this->found=false;
		$this->found_key=0;


		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$type_of_search='complete';
		if (preg_match('/fuzzy/i',$options)) {
			$type_of_search='fuzzy';
		}
		elseif (preg_match('/fast/i',$options)) {
			$type_of_search='fast';
		}

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		if (
			!isset($raw_data['Customer Store Key']) or
			!preg_match('/^\d+$/i',$raw_data['Customer Store Key']) ) {
			$raw_data['Customer Store Key']=1;

		}

		if (!isset($raw_data['Customer Type']) or !preg_match('/^(Company|Person)$/i',$raw_data['Customer Type']) ) {


			// Try to detect if is a company or a person
			if (
				(isset($raw_data['Customer Company Name']) and  $raw_data['Customer Company Name']!='' )
				or (isset($raw_data['Customer Company Key']) and  $raw_data['Customer Company Key'] )
			)$raw_data['Customer Type']='Company';
			else
				$raw_data['Customer Type']='Person';


		}



		$raw_data['Customer Type']=ucwords($raw_data['Customer Type']);
		if ($raw_data['Customer Type']=='Person') {
			$child=new Contact ("find in customer $type_of_search",$raw_data);
		} else {
			$child=new Company ("find in customer $type_of_search",$raw_data);
		}

		$this->found_in_another_store=false;
		$this->found_key_in_another_store=0;


		if ($child->found) {
			//print "Customer child found\n";
			$this->found_child=true;
			$this->found_child_key=$child->found_key;
			$customer_found_keys=$child->get_customer_keys();

			if (count($customer_found_keys)>0) {
				foreach ($customer_found_keys as $customer_found_key) {
					$tmp_customer=new Customer($customer_found_key);
					if ($tmp_customer->id) {
						if ($tmp_customer->data['Customer Store Key']==$raw_data['Customer Store Key']) {
							$this->found=true;
							$this->found_key=$customer_found_key;
						} else {
							$this->found_in_another_store=true;
							$this->found_key_in_another_store=$tmp_customer->id;


						}
					}
				}
			}


		}



		$this->candidate=$child->candidate;



		if ($this->found) {
			$this->get_data('id',$this->found_key);
		}



		// $this->child=$child;

		if ($create and (
				($raw_data['Customer Main Contact Name']=='' and  $raw_data['Customer Type']=='Person')
				or ($raw_data['Customer Company Name']=='' and  $raw_data['Customer Type']=='Company')
			)
		) {

			global $myconf;
			$raw_data['Customer Company Name']=$myconf['unknown_contact'];
			$raw_data['Customer Main Contact Name']=$myconf['unknown_contact'];
			$raw_data['Customer Name']=$myconf['unknown_contact'];
			//   $this->create_anonymous($raw_data);
			// return;
		}

		//print_r($raw_data);
		//print "A".$this->found."  B".$this->found_child."\n";
		//exit("in cust class\n");
		if ($create) {

			if ($this->found) {

				if ($raw_data['Customer Type']=='Person') {



					if (
						isset($child->data['Contact Key']) and
						$raw_data['Customer Main Plain Email']!='' and
						$raw_data['Customer Main Plain Email']==$child->data['Contact Main Plain Email']
						and (levenshtein($child->data['Contact Name'],$raw_data['Customer Main Contact Name'])/(strlen($child->data['Contact Name'])+1))>.3
						and !preg_match("/".str_replace( '/', '\/', $child->data['Contact Name'] )."/",$raw_data['Customer Main Contact Name'] )
						and !preg_match("/".str_replace( '/', '\/', $raw_data['Customer Main Contact Name'] )."/",$child->data['Contact Name'] )
					) {
						//print "super change!!\n";
						// exit;
						$email=new Email($child->data['Contact Main Email Key']);
						$email->editor=$this->editor;
						$email->delete();

						$_customer = new Customer ( 'find create  $type_of_search', $raw_data );

						$this->get_data('id',$_customer->id);


						return;
					}


					$child=new Contact ("find in customer $type_of_search create update",$raw_data);




				} else {// Bussiness



					$child=new Company ("find in customer $type_of_search create update",$raw_data);



					//print "ssssssss";
				}


				$raw_data_to_update=array();
				if (isset($raw_data['Customer Old ID']))
					$raw_data_to_update['Customer Old ID']=$raw_data['Customer Old ID'];
				if (isset($raw_data['Customer Send Newsletter']))
					$raw_data_to_update['Customer Send Newsletter']=$raw_data['Customer Send Newsletter'];
				if (isset($raw_data['Customer Send Email Marketing']))
					$raw_data_to_update['Customer Send Email Marketing']=$raw_data['Customer Send Email Marketing'];
				if (isset($raw_data['Customer Send Postal Marketing']))
					$raw_data_to_update['Customer Send Postal Marketing']=$raw_data['Customer Send Postal Marketing'];
				if (isset($raw_data['Customer Sticky Note']))
					$raw_data_to_update['Customer Sticky Note']=$raw_data['Customer Sticky Note'];
				$this->update($raw_data_to_update);

				$this->get_data('id',$this->id);

			} else {// customer not found
				if ($this->found_child) {

					if ($raw_data['Customer Type']=='Person') {

						//print_r($raw_data);
						//print_r($child->data);

						if (
							isset($child->data['Contact Key']) and
							$raw_data['Customer Main Plain Email']!='' and
							$raw_data['Customer Main Plain Email']==$child->data['Contact Main Plain Email'] and
							(levenshtein($child->data['Contact Name'],$raw_data['Customer Main Contact Name'])/(strlen($child->data['Contact Name'])+1))>.3

						) {
							//print "super change2!\n";
							// $child->remove_email($child->data['Contact Main Email Key']);
							$email=new Email($child->data['Contact Main Email Key']);
							$email->editor=$this->editor;
							$email->delete();
							//  print_r($child);
							//exit;
							$_customer = new Customer ( 'find create $type_of_search', $raw_data );

							$this->get_data('id',$_customer->id);
							return;


						}

						//$contact=new contact('id',$this->found_child_key);
						// print_r($contact->data);
						// print_r($raw_data);
						// print "lets update the contact\n";
						$contact=new contact("find in customer $type_of_search create update",$raw_data);
						//print "updated contact\n";
						//print_r($contact);
						$raw_data['Customer Main Contact Key']=$contact->id;

					} else {
						$company=new company("find in customer $type_of_search create update",$raw_data);
						$raw_data['Customer Company Key']=$company->id;
					}


				}
				$this->create($raw_data);

			}

		}



	}


	function update_correlations() {

		$sql=sprintf("delete from  `Customer Correlation` where `Customer A Key`=%d or `Customer B Key`=%d  ",$this->id,$this->id);
		mysql_query($sql);

		$correlated_customers=array();
		$data=$this->data;
		if ($data['Customer Type']=='Person')
			$subject=new contact('find from customer complete',$data);
		else
			$subject=new company('find from customer complete',$data);



		foreach ($subject->candidate as $contact_key=>$score) {
			if ($score<100)
				continue;
			$contact=new Contact($contact_key);
			$customer_keys=$contact->get_customer_keys('Customer');

			foreach ($customer_keys as $customer_key) {
				$customer_correlated=new Customer($customer_key);
				if ($customer_correlated->data['Customer Store Key']==$this->data['Customer Store Key'])
					$correlated_customers[$customer_key]=array('name'=>$customer_correlated->data['Customer Name'],'score'=>$score);
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

	function get_name() {
		return $this->data['Customer Name'];
	}

	function get_greetings($locale=false) {

		if ($locale) {

			if (preg_match('/^es_/',$locale)) {
				$unknown_name='A quien corresponda';
				$greeting_prefix='Estimado';
			} else {
				$unknown_name=_('To whom it corresponds');
				$greeting_prefix=_('Dear');
			}
		} else {
			$unknown_name=_('To whom it corresponds');
			$greeting_prefix=_('Dear');
		}
		if ($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
			return $unknown_name;
		$greeting=$greeting_prefix.' '.$this->data['Customer Main Contact Name'];
		if ($this->data['Customer Type']=='Company') {
			$greeting.=', '.$this->data['Customer Name'];
		}
		return $greeting;

	}

	function get_data($tag,$id) {
		if ($tag=='id')
			$sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s",prepare_mysql($id));
		elseif ($tag=='email')
			$sql=sprintf("select * from `Customer Dimension` where `Customer Email`=%s",prepare_mysql($id));
		elseif ($tag='all') {
			$this->find($id);
			return true;
		}
		else
			return false;
		$result=mysql_query($sql);

		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->id=$this->data['Customer Key'];
		}
	}



	function load($key='',$arg1=false) {
		switch ($key) {
		case('contact_data'):
		case('contact data'):
			$contact=new Contact($this->get('customer contact key'));
			if ($contact->id)
				$this->contact_data=$contact->data;
			else
				$this->errors[]='Error geting contact data object. Contact key:'.$this->get('customer contact key');
			break;
		case('ship to'):

			$sql=sprintf('select * from `Ship To Dimension` where `Ship To Key`=%d ',$arg1);

			//  print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->ship_to[$row['Ship To Key']]=$row;


			} else
				$this->errors[]='Error loading ship to data. Ship to Key:'.$arg1;

			break;
		}

	}


	function create($raw_data,$args='') {


		$main_telephone_key=false;
		$main_fax_key=false;
		$main_email_key=false;

		//print_r ($raw_data);

		$this->data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$this->data)) {
				$this->data[$key]=_trim($value);
			}
		}

		if ($this->data['Customer First Contacted Date']=='') {
			$this->data['Customer First Contacted Date']=date('Y-m-d H:i:s');
		}

		$this->data['Customer Active Ship To Records']=0;
		$this->data['Customer Total Ship To Records']=0;


		// Ok see if we have a billing address!!!

		$this->data['Customer Main Email Key']=0;
		$this->data['Customer Main XHTML Email']='';
		$this->data['Customer Main Plain Email']='';
		$this->data['Customer Main Telephone Key']=0;
		$this->data['Customer Main XHTML Telephone']='';
		$this->data['Customer Main Plain Telephone']='';
		$this->data['Customer Main FAX Key']=0;
		$this->data['Customer Main XHTML FAX']='';
		$this->data['Customer Main Plain FAX']='';


		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			if (preg_match('/Customer Main|Customer Company/i',$key))
				continue;
			$keys.=",`".$key."`";

			if (preg_match('/Key$/',$key))
				$values.=','.prepare_mysql($value);
			else
				$values.=','.prepare_mysql($value,false);
		}
		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);



		$sql="insert into `Customer Dimension` ($keys) values ($values)";

		//   print $sql;
		//exit;
		if (mysql_query($sql)) {

			$this->id=mysql_insert_id();


			$history_data=array(
				'History Abstract'=>_('Customer Created'),
				'History Details'=>_trim(_('New customer')." ".$this->data['Customer Name']." "._('added')),
				'Action'=>'created'
			);
			$this->add_customer_history($history_data);
			$this->new=true;


			if ($this->data['Customer Type']=='Company') {

				if (!$this->data['Customer Company Key']) {
					//print_r($raw_data);
					$company=new company('find in customer fast create update',$raw_data);
				} else {
					$company=new company('id',$this->data['Customer Company Key']);
				}

				// print_r($company);
				$company_key=$company->id;
				$this->data['Customer File As']=$company->data['Company File As'];
				$this->data['Customer Name']=$company->data['Company Name'];

				if ($company->last_associated_contact_key)
					$contact=new Contact($company->last_associated_contact_key);
				else {
					$contact=new Contact($company->data['Company Main Contact Key']);

					$contact->editor=$this->editor;
				}
			}
			elseif ($this->data['Customer Type']=='Person') {


				if (!$this->data['Customer Main Contact Key']) {

					$contact=new contact('find in customer fast create update',$raw_data);
				} else {
					$contact=new contact('id',$this->data['Customer Main Contact Key']);
					$contact->editor=$this->editor;
				}


				$this->data['Customer Name']=$contact->display('name');
				$this->data['Customer File As']=$contact->data['Contact File As'];

				$this->data['Customer Company Key']=0;


			}
			else {
				$this->error=true;
				$this->msg.=' Error, Wrong Customer Type ->'.$this->data['Customer Type'];
			}


			if ($this->data['Customer Type']=='Company') {


				$this->associate_company($company->id);
				$this->associate_contact($contact->id);


				$mobile=new Telecom($contact->data['Contact Main Mobile Key']);
				if ($mobile->id) {

					$contact->update_parents_principal_mobile_keys(($this->new?false:true));
					$mobile->editor=$this->editor;
					$mobile->new=true;
					$mobile->update_parents(($this->new?false:true));
				}


				$address=new Address($company->data['Company Main Address Key']);
				$address->editor=$this->editor;
				$address->new=true;
				//print_r($address);

				$this->create_contact_address_bridge($address->id);


				$address->update_parents_principal_telecom_keys('Telephone',($this->new?false:true));
				$address->update_parents_principal_telecom_keys('FAX',($this->new?false:true));



				$tel=new Telecom($address->get_principal_telecom_key('Telephone'));
				$tel->editor=$this->editor;
				$tel->new=true;

				if ($tel->id)
					$tel->update_parents(($this->new?false:true));



				$fax=new Telecom($address->get_principal_telecom_key('FAX'));
				$fax->editor=$this->editor;
				$fax->new=true;
				if ($fax->id)
					$fax->update_parents(($this->new?false:true));




			} else {
				$this->associate_contact($contact->id);

				//$contact->update_parents_principal_address_keys($contact->data['Contact Main Address Key'],($this->new?false:true));




				$address=new Address($contact->data['Contact Main Address Key']);


				$address->editor=$this->editor;
				$address->new=true;

				$this->create_contact_address_bridge($address->id);
				$address->update_parents_principal_telecom_keys('Telephone',($this->new?false:true));
				$address->update_parents_principal_telecom_keys('FAX',($this->new?false:true));


				$address->update_parents(false,($this->new?false:true));

				$this->get_data('id',$this->id);


				//  exit;

				$tel=new Telecom($address->get_principal_telecom_key('Telephone'));




				$tel->editor=$this->editor;
				$tel->new=true;
				if ($tel->id)

					$tel->update_parents(($this->new?false:true));
				$fax=new Telecom($address->get_principal_telecom_key('FAX'));
				$fax->editor=$this->editor;
				$fax->new=true;
				if ($fax->id)

					$fax->update_parents(($this->new?false:true));
			}





			$contact->update_parents_principal_email_keys();


			$email=new Email($contact->get_principal_email_key());
			$email->editor=$this->editor;
			$email->new=true;
			if ($email->id) {
				$email->update_parents(($this->new?false:true));

			}

			$this->get_data('id',$this->id);

			//  print_r($this->data);
			//        exit;

			//          if ($this->data['Customer Billing Address Link']!='None') {
			$this->data['Customer Billing Address Link']=='Contact';
			//}

			// if( !($this->data['Customer Delivery Address Link']=='None'  or $this->data['Customer Delivery Address Link']=='Billing')   ) {
			$this->data['Customer Delivery Address Link']=='Contact';
			//}

			$this->associate_billing_address($address->id);
			$this->associate_delivery_address($address->id);

			$this->get_data('id',$this->id);





		} else {
			print "Error can not create customer $sql\n";
		}

		$sql="select * from `Customer Dimension` order by `Customer Key` DESC limit 0, 1";
		$res=mysql_query($sql);
		$row=mysql_fetch_array($res, MYSQL_ASSOC);
		$id=$row['Customer Key'];

		//Adding Custom Field Data

		$keys='`Customer Key`';
		$values=$id;
		$new_subject=array();
		$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer' and `Custom Field In New Subject`='Yes'");
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC))
			$new_subject[] = array('custom_field_name'=>$row['Custom Field Name']);

		//print_r ($raw_data);
		foreach ($raw_data as $key=>$value) {
			foreach ($new_subject as $field) {
				if (strcmp($field['custom_field_name'],$key)==0) {
					$keys.=",`".$key."`";
					$values.=','.prepare_mysql($value);
				}
			}
		}
		$sql="insert into `Customer Custom Field Dimension` ($keys) values ($values)";
		//print $sql;
		mysql_query($sql);

		//------------------------

		$sql=sprintf("insert into `Customer Custom Field Dimension` (`Customer Key`) VALUES (%d)", $id);
		//print $sql;
		mysql_query($sql);

		$this->update_full_search();

	}





	private function create_anonymous($raw_data) {


		$store_key=$raw_data['Customer Store Key'];


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



		foreach ($raw_data as $key=>$val) {
			if (array_key_exists($key,$address_data))
				$address_data[$key]=$val;

		}
		$address_data['Address Input Format']='3 Line';
		$anon_address=new Address();
		$anon_address->create($address_data);



		$contact=new Contact('create anonymous',$raw_data,'from customer');
		$data=$contact->data;
		foreach ($data as $key=>$value) {
			$data[preg_replace('/Contact/','Customer',$key)]=$value;
		}
		$data['Customer Main Address Key']=$anon_address->id;
		$data['Customer Billing Address Key']=$anon_address->id;
		if (isset($raw_data['Customer First Contacted Date']))
			$data['Customer First Contacted Date']=$raw_data['Customer First Contacted Date'];
		else
			$data['Customer First Contacted Date']=date("Y-m-d H:i:s");

		$data['Customer Main Country Code']=$anon_address->data['Address Country Code'];
		$data['Customer Main Country 2 Alpha Code']=$anon_address->data['Address Country 2 Alpha Code'];
		$data['Customer Main Location']=$anon_address->data['Address Location'];
		$data['Customer Main Town']=$anon_address->data['Address Town'];
		$data['Customer Main Postal Code']=$anon_address->data['Address Postal Code'];
		$data['Customer Main Country First Division']=$anon_address->data['Address Country First Division'];
		$data['Customer Main XHTML Address']=$anon_address->display('html');
		$data['Customer Main Plain Address']=$anon_address->display('plain');

		$data['Customer Staff Key']=0;
		$data['Customer Main Contact Key']=$contact->id;
		$data['Customer Name']='';
		$data['Customer File As']='';
		$data['Customer Store Key']=$store_key;

		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$this->data)) {
				$this->data[$key]=_trim($value);
			}
		}

		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";

			if (preg_match('/Key$/',$key))
				$values.=','.prepare_mysql($value);
			else
				$values.=','.prepare_mysql($value,false);
		}
		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Customer Dimension` ($keys) values ($values)";

		if (mysql_query($sql)) {

			$this->id=mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->fuzzy=true;
			$history_data=array(
				'History Abstract'=>_('Anonymous Customer Created'),
				'History Details'=>_trim(_('New anonymous customer added').' ('.$this->get_formated_id_link().')' ),
				'Action'=>'created'

			);
			$this->add_customer_history($history_data);
			$this->new=true;

		}
	}

	function associate_ship_to_key($ship_to_key,$date,$current_ship_to=false) {

		if ($current_ship_to) {
			$principal='No';
		} else {
			$principal='Yes';
			$current_ship_to=$ship_to_key;
		}

		$sql=sprintf('select * from `Customer Ship To Bridge` where `Customer Key`=%d and `Ship To Key`=%d',$this->id,$ship_to_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$from_date=$row['Ship To From Date'];
			$to_date=$row['Ship To Last Used'];


			if (strtotime($date)< strtotime($from_date))
				$from_date=$date;
			if (strtotime($date)> strtotime($to_date))
				$to_date=$date;

			$sql=sprintf('update `Customer Ship To Bridge` set `Ship To From Date`=%s,`Ship To Last Used`=%s,`Is Principal`=%s,`Ship To Current Key`=%d where `Customer Key`=%d and `Ship To Key`=%d',

				prepare_mysql($from_date),
				prepare_mysql($to_date),
				prepare_mysql($principal),
				$current_ship_to,
				$this->id,
				$ship_to_key
			);
			mysql_query($sql);

		} else {
			$sql=sprintf("insert into `Customer Ship To Bridge` (`Customer Key`,`Ship To Key`,`Is Principal`,`Times Used`,`Ship To From Date`,`Ship To Last Used`,`Ship To Current Key`) values (%d,%d,%s,0,%s,%s,%d)",
				$this->id,
				$ship_to_key,
				prepare_mysql($principal),
				prepare_mysql($date),
				prepare_mysql($date),
				$current_ship_to
			);
			mysql_query($sql);
		}

	}



	function update_ship_to($data) {

		$ship_to_key=$data['Ship To Key'];
		$current_ship_to=$data['Current Ship To Is Other Key'];

		$this->associate_ship_to_key($ship_to_key,$data['Date'],$current_ship_to);
		$sql=sprintf("update `Customer Dimension` set `Customer Last Ship To Key`=%d where `Customer Key`=%d",
			$current_ship_to,
			$this->id
		);
		mysql_query($sql);



		$this->update_ship_to_stats();
	}

	function update_last_ship_to_key() {
		$sql=sprintf('select `Ship To Key`  from  `Customer Ship To Bridge` where `Customer Key`=%d  order by `Ship To Last Used` desc  ',$this->id);
		$res2=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res2)) {
			$sql=sprintf("update `Customer Dimension` set `Customer Last Ship To Key`=%s where `Customer Key`=%d",
				prepare_mysql($row['Ship To Key']),

				$this->id
			);
			mysql_query($sql);
		}

	}

	function update_ship_to_stats() {

		$total_active_ship_to=0;
		$total_ship_to=0;
		$sql=sprintf('select sum(if(`Ship To Status`="Normal",1,0)) as active  ,count(*) as total  from  `Customer Ship To Bridge` where `Customer Key`=%d ',$this->id);
		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2)) {
			$total_active_ship_to=$row2['active'];
			$total_ship_to=$row2['total'];
		}
		$sql=sprintf("update `Customer Dimension` set `Customer Active Ship To Records`=%d,`Customer Total Ship To Records`=%d where `Customer Key`=%d"

			,$total_active_ship_to
			,$total_ship_to
			,$this->id
		);
		mysql_query($sql);
	}


	function update_main_ship_to_old($ship_to_key=false) {

		if ($ship_to_key)
			$ship_to=new Ship_To($ship_to_key);

		else
			$ship_to=new Ship_To($this->data['Customer Main Delivery Address Key']);





		$sql=sprintf("update `Customer Dimension` set `Customer Main Delivery Address Key`=%d,`Customer Main Delivery Address Town`=%s,`Customer Main Delivery Address Postal Code`=%s,`Customer Main Delivery Address Country`=%s,`Customer Main Delivery Address Country Key`=%s,`Customer Main Delivery Address Country Code`=%s,`Customer Main Delivery Address Country 2 Alpha Code`=%s where `Customer Key`=%d"
			,$ship_to->id
			,prepare_mysql($ship_to->data['Ship To Town'])
			,prepare_mysql($ship_to->data['Ship To Postal Code'])
			,prepare_mysql($ship_to->data['Ship To Country Name'])
			,prepare_mysql($ship_to->data['Ship To Country Key'])
			,prepare_mysql($ship_to->data['Ship To Country Code'])
			,prepare_mysql($ship_to->data['Ship To Country 2 Alpha Code'])

			,$this->id
		);
		mysql_query($sql);


		$this->data['Customer Main Delivery Address Key']=$ship_to->id;
		$this->data['Customer Main Delivery Address Town']=$ship_to->data['Ship To Town'];
		$this->data['Customer Main Delivery Address Country']=$ship_to->data['Ship To Country Name'];
		$this->data['Customer Main Delivery Address Postal Code']=$ship_to->data['Ship To Postal Code'];
		$this->data['Customer Main Delivery Address Country Key']=$ship_to->data['Ship To Country Key'];
		$this->data['Customer Main Delivery Address Country Code']=$ship_to->data['Ship To Country Code'];
		$this->data['Customer Main Delivery Address Country 2 Alpha Code']=$ship_to->data['Ship To Country 2 Alpha Code'];




	}




	/*Function: update_field_switcher
     */





	function update_field_switcher($field,$value,$options='') {

		//print ": $field,$value";


		if (is_string($value))
			$value=_trim($value);

		if (preg_match('/^custom_field_/i',$field)) {
			//$field=preg_replace('/^custom_field_/','',$field);
			$this->update_field($field,$value,$options);


			return;
		}

		switch ($field) {
		case('Customer Main XHTML Telephone'):
		case('Customer Main Telephone Key'):
		case('Customer Main XHTML Mobile'):
		case('Customer Main Mobile Key'):

		case('Customer Main XHTML FAX'):
		case('Customer First Contacted Date'):
		case('Customer Main FAX Key'):
		case('Customer Main XHTML Email'):
		case('Customer Main Email Key'):
			break;
		case('Customer Sticky Note'):
			$this->update_field($field,$value,'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('Note'):
			$this->add_note($value);
			break;
		case('Attach'):
			$this->add_attach($value);
			break;
		case('Customer Name'):
			$this->update_child_name($value);
			break;
		case('Customer Main Contact Name'):
			$this->update_child_main_contact_name($value);
			break;
		case('Customer Main Plain Telephone'):
		case('Customer Main Plain FAX'):


			$old_value=$this->data[$field];
			if ($old_value!=$value) {

				if ($field=='Customer Main Plain Telephone') {
					$type='Telephone';
					$this->remove_principal_telephone();
				}else {
					$this->remove_principal_fax();
					$type='FAX';
				}


				$telephone_data=array();
				$telephone_data['editor']=$this->editor;
				$telephone_data['Telecom Raw Number']=$value;
				$telephone_data['Telecom Type']=$type;
				$telephone=new Telecom("find fast create",$telephone_data);



				//$telephone=new Telecom('new',$telephone_data);
				//print_r($telephone);
				if ($telephone->id) {
					$customers_with_this_telephone=$telephone->get_customer_keys();
					//print_r($customers_with_this_telephone);
					//exit;
					if (in_array($this->id,$customers_with_this_telephone)) {
						$this->msg=_('The customer already has this number');
						$this->error=true;
						$this->warning_messages[]=$this->msg;
						return;

					}


					$address=new Address($this->data['Customer Main Address Key']);
					$address->associate_telecom($telephone->id,$type);




					$address->associate_telecom_to_parents($type,'Customer',$this->id,$telephone->id);
					$address->associate_telecom_to_parents($type,'Contact',$this->data['Customer Main Contact Key'],$telephone->id);


					if ($this->data['Customer Company Key']) {
						// print "x3";
						$address->associate_telecom_to_parents($type,'Company',$this->data['Customer Company Key'],$telephone->id);

					}
					// print "x4";
					$telephone->update_parents();



					$this->updated=1;

					$this->new_value=$value;


				}









			}

			break;
		case('Customer Main Plain Mobile'):


			$old_value=$this->data['Customer Main Plain Mobile'];
			if ($old_value!=$value) {
				$this->remove_principal_mobile();
				if ($value!='') {

					$type='Mobile';
					$telephone_data=array();
					$telephone_data['editor']=$this->editor;
					$telephone_data['Telecom Raw Number']=$value;
					$telephone_data['Telecom Type']=$type;
					$proposed_telephone=new Telecom("find complete country code ".$this->data['Customer Main Country Code'],$telephone_data);

					if ($proposed_telephone->id) {


						$customers_with_this_number=$proposed_telephone->get_customer_keys();
						// Check if email already in this customer an return

						// print_r($customers_with_this_number);


						if (in_array($this->id,$customers_with_this_number)) {

							$this->error=true;
							$this->msg='<img art="art/icons/error.png" alt="'._('Error').'"/> '._('These customer already has this number');

							return;

						}


						// Check if email already in this store an return
						foreach ($customers_with_this_number as $customer_with_this_number ) {
							$other_customer_with_this_number=new Customer($customer_with_this_number);
							if ($other_customer_with_this_number->data['Customer Store Key']==$this->data['Customer Store Key']) {
								$error_customer_in_the_same_store=$other_customer_with_this_number;
								$customer_name_with_this_number=$other_customer_with_this_number->data['Customer Name'];
								//$this->error=true;
								$this->warning=true;
								$this->msg=_('Warning number also associated with customer').' <a href="customer.php?id='.$error_customer_in_the_same_store.'">'.$customer_name_with_this_email.'</a>';

								//return;
							}
						}
					}
					$contact=new Contact($this->data['Customer Main Contact Key']);
					$contact-> update_field_switcher('Add Other Mobile',$value);
					$new_princial_key=$contact->other_mobile_key;
					$telecom=new Telecom($new_princial_key);


					if ($telecom->id) {
						//print "x1";
						$contact->associate_mobile_to_parents('Customer',$this->id,$telecom->id);
						// print "x2";
						if ($this->data['Customer Company Key']) {
							// print "x3";
							$contact->associate_mobile_to_parents('Company',$this->data['Customer Company Key'],$telecom->id,false);
						}
						// print "x4";
						$telecom->update_parents();
						//  print "x5";
						$this->updated=1;
						//$this->msg=_('Mo removed');
						$this->new_value=$value;

					}else {
						$this->error=1;
						$this->msg='unknown error';
						$this->new_value='';
					}
				}
				else {
					$this->updated=1;
					$this->msg=_('Mobile removed');
					$this->new_value='';
				}
			}
			break;


		case('Add Other Mobile'):
			$this->add_other_telecom('Mobile',$value);
			break;

		case('Add Other FAX'):
			$this->add_other_telecom('FAX',$value);
			break;
		case('Add Other Telephone'):
			$this->add_other_telecom('Telephone',$value);
			break;
		case('Add Other Email'):

			if ($value=='') {
				return;
			}
			$email=new Email('email',$value);
			if ($email->id) {
				$customers_with_this_email=$email->get_customer_keys();

				if (in_array($this->id,$customers_with_this_email)) {
					$this->msg='<img art="art/icons/error.png" alt="'._('Error').'"/> '._('The customer already has this email');
					$this->error=true;

					return;
				}
				unset($customers_with_this_email[$this->id]);


				foreach ($customers_with_this_email as $customer_with_this_email) {
					$other_customer_with_this_email=new Customer($customer_with_this_email);
					if ($other_customer_with_this_email->data['Customer Store Key']==$this->data['Customer Store Key']) {
						$this->msg=_('Email could not be added, it belongs to customer').' <a href="customer.php?id='.$other_customer_with_this_email->id.'">'.$other_customer_with_this_email->data['Customer Name'].'</a>';

						$this->error=true;

						return;
					}

				}
			}

			$contact=new Contact($this->data['Customer Main Contact Key']);
			$contact-> update_field_switcher('Add Other Email',$value);
			$this->updated=$contact->updated;
			$this->msg=$contact->msg;
			$this->new_value=$contact->new_value;

			if ($email_key=$contact->other_email_key) {

				if ($this->data['Customer Company Key']) {
					$contact->associate_email_to_parents('Company',$this->data['Customer Company Key'],$email_key,false);
				}
				$contact->associate_email_to_parents('Customer',$this->id,$email_key,false);

			}
			$this->new_email_key=$email_key;
			break;
		case('Customer Main Plain Email'):

			$old_value=$this->data['Customer Main Plain Email'];
			if ($old_value!=$value) {
				$this->remove_principal_email();
				if ($value!='') {

					$email = new Email('email',$value);
					if ($email->id) {


						$customers_with_this_email=$email->get_customer_keys();
						// Check if email already in this customer an return
						if (in_array($this->id,$customers_with_this_email)) {

							$this->error=true;
							$this->msg='<img art="art/icons/error.png" alt="'._('Error').'"/> '._('The customer already has this email');

							return;

						}


						// Check if email already in this store an return
						foreach ($customers_with_this_email as $customer_with_this_email) {
							$other_customer_with_this_email=new Customer($customer_with_this_email);
							if ($other_customer_with_this_email->data['Customer Store Key']==$this->data['Customer Store Key']) {
								$error_customer_in_the_same_store=$customer_with_this_email;
								$customer_name_with_this_email=$other_customer_with_this_email->data['Customer Name'];
								$this->error=true;
								$this->msg=_('Email could not be updated, it belongs to customer').' <a href="customer.php?id='.$error_customer_in_the_same_store.'">'.$customer_name_with_this_email.'</a>';

								return;
							}
						}


					}


					$contact=new Contact($this->data['Customer Main Contact Key']);
					$contact-> update_field_switcher('Add Other Email',$value);
					$new_princial_key=$contact->other_email_key;
					$email=new Email($new_princial_key);
					if ($email->id) {

						$contact->associate_email_to_parents('Customer',$this->id,$email->id);
						if ($this->data['Customer Company Key']) {
							$contact->associate_email_to_parents('Company',$this->data['Customer Company Key'],$email->id,false);
						}
						$email->update_parents();
						$this->updated=1;
						$this->msg=_('Email removed');
						$this->new_value=$value;


					}else {
						$this->error=1;
						$this->msg='unknown error';
						$this->new_value='';

					}

				}
				else {

					$this->updated=1;
					$this->msg=_('Email removed');
					$this->new_value='';
				}
			}

			break;
		default:
			$base_data=$this->base_data();
			//print_r($base_data);
			if (array_key_exists($field,$base_data)) {
				//print "*** $field\n";
				$this->update_field($field,$value,$options);
			}
		}
	}



	function update_other_email_label($email_key,$label) {
		if (!array_key_exists($email_key,$this->get_email_keys())) {
			$this->error=true;
			$this->msg=_('Email not associated with customer');
			return;
		}

		$sql=sprintf('update `Email Bridge` set `Email Description`=%s where `Subject Type`="Customer" and `Email Key`=%d  and `Subject Key`=%d ',
			prepare_mysql($label),
			$email_key,
			$this->id
		);
		//print $sql;
		mysql_query($sql);

		if (mysql_affected_rows()) {
			$this->new_value=$label;
			$this->updated=true;
		}

	}

	function update_other_email($email_key,$value) {

		if (!array_key_exists($email_key,$this->get_other_emails_data())) {
			$this->error=true;
			$this->msg=_('Email not associated with customer');
			return;
		}

		if ($value=='') {
			$this->remove_email($email_key);

		} else {
			$email_data['Email']=$value;
			$email_data['Email Contact Name']=$this->data['Customer Main Contact Name'];
			$email_data['editor']=$this->editor;
			$email=new Email('find',$email_data);
			//print_r($email);
			if ($email->found) {

				$customers_with_this_email=$email->get_customer_keys();

				if (array_key_exists($this->id, $customers_with_this_email)) {
					$this->error=true;
					$this->msg=_('Customer has already this email');
					return;
				}

				foreach ($customers_with_this_email as $customer_with_this_email) {
					$other_customer_with_this_email=new Customer($customer_with_this_email);
					if ($other_customer_with_this_email->data['Customer Store Key']==$this->data['Customer Store Key']) {
						$this->error=true;
						$this->msg=_('Email could not be updated, it belongs to customer').' <a href="customer.php?id='.$other_customer_with_this_email->id.'">'.$other_customer_with_this_email->data['Customer Name'].'</a>';

						return;
					}

				}


				$this->remove_email($email->id);
				$contact=new Contact($this->data['Customer Main Contact Key']);
				$contact->update(array('New Other Email'=>$value));
				$this->updated=$contact->updated;
				$this->msg=$contact->msg;
				$this->new_value=$contact->new_value;

				if ($email_key=$contact->other_email_key) {

					if ($this->data['Customer Company Key']) {
						$contact->associate_email_to_parents('Company',$this->data['Customer Company Key'],$email_key,false);

					}
					$contact->associate_email_to_parents('Customer',$this->id,$email_key,false);

				}

				$this->new_email_key=$email_key;



			} else {
				// print "xxx";

				// $contact=new Contact($this->data['Customer Main Contact Key']);
				// $contact->associate_email($email->id);
				$email=new Email($email_key);
				$email->update_Email($value);
				$this->new_value=$email->new_value;
				$this->updated=$email->updated;
				$this->msg=$email->msg;

				// print_r($email)

			}


		}



	}

	function update_other_fax($telecom_key,$value) {
		return $this->update_other_telecom('FAX',$telecom_key,$value);
	}
	function update_other_mobile($telecom_key,$value) {
		return $this->update_other_telecom('Mobile',$telecom_key,$value);
	}
	function update_other_telephone($telecom_key,$value) {
		return $this->update_other_telecom('Telephone',$telecom_key,$value);
	}






	function update_other_telecom_label($type,$telecom_key,$label) {
		if (!array_key_exists($telecom_key,$this->get_telecom_keys($type))) {
			$this->error=true;
			$this->msg=_('Telecom not associated with customer');
			return;
		}

		$sql=sprintf('update `Telecom Bridge` set `Telecom Description`=%s where `Subject Type`="Customer" and `Telecom Key`=%d  and `Subject Key`=%d ',
			prepare_mysql($label),
			$telecom_key,
			$this->id
		);
		// print $sql;
		mysql_query($sql);

		if (mysql_affected_rows()) {
			$this->new_value=$label;
			$this->updated=true;
		}

	}


	function update_other_telecom($type,$telecom_key,$value) {

		if (!array_key_exists($telecom_key,$this->get_other_telecoms_data($type))) {
			$this->error=true;
			$this->msg=_('Telecom not associated with customer');
			return;
		}

		if ($value=='') {
			//print $telecom_key;//fax67795;fax67796
			$this->remove_telecom($type,$telecom_key);

		} else {
			$this->add_other_telecom($type,$value,$telecom_key);

		}



	}






	function add_other_telecom($type='Telephone',$value,$telecom_key_to_replace=0) {

		if ($value=='') {
			return;
		}

		$telephone_data=array();
		$telephone_data['editor']=$this->editor;
		$telephone_data['Telecom Raw Number']=$value;
		$telephone_data['Telecom Type']=$type;
		$proposed_telephone=new Telecom("find complete country code ".$this->data['Customer Main Country Code'],$telephone_data);
		//$proposed_telephone=new Telecom('new',$telephone_data);

		if ($proposed_telephone->id) {
			$customers_with_this_telephone=$proposed_telephone->get_customer_keys();

			if (in_array($this->id,$customers_with_this_telephone)) {
				$this->msg=_('This customer already has this number');
				$this->error=true;


				$this->warning_messages[]=$this->msg;
				return;

			}
			unset($customers_with_this_telephone[$this->id]);


			foreach ($customers_with_this_telephone as $customer_with_this_telephone) {
				$other_customer_with_this_telephone=new Customer($customer_with_this_telephone);
				if ($other_customer_with_this_telephone->data['Customer Store Key']==$this->data['Customer Store Key']) {
					$this->msg=_('Warning number also found in customer').' <a href="customer.php?id='.$other_customer_with_this_telephone->id.'">'.$other_customer_with_this_telephone->data['Customer Name'].'</a>';



					// return;
				}

			}
		}


		$this->remove_telecom($type,$telecom_key_to_replace);

		if ($type=='Mobile') {
			$contact=new Contact($this->data['Customer Main Contact Key']);
			$contact->update_field_switcher('Add Other Mobile',$value);
			$this->updated=$contact->updated;
			$this->msg=$contact->msg;
			$this->new_value=$contact->new_value;

			if ($telecom_key=$contact->other_mobile_key) {


				$contact->associate_mobile_to_parents('Customer',$this->id,$telecom_key,false);
				// $contact->associate_mobile_to_parents($type,'Contact',$this->data['Customer Main Contact Key'],$telecom_key,false);

			}
		}
		else {

			$address=new Address($this->data['Customer Main Address Key']);
			//print "aa:".$address->get_principal_telecom_key($type);
			//exit;

			$address->update_field_switcher('Add Other '.$type,$value);
			$this->updated=$address->updated;
			$this->msg=$address->msg;
			$this->new_value=$address->new_value;

			if ($telecom_key=$address->other_telecom_key) {

				if ($this->data['Customer Company Key']) {
					$address->associate_telecom_to_parents($type,'Company',$this->data['Customer Company Key'],$telecom_key,false);
				}
				$address->associate_telecom_to_parents($type,'Customer',$this->id,$telecom_key,false);
				$address->associate_telecom_to_parents($type,'Contact',$this->data['Customer Main Contact Key'],$telecom_key,false);

			}


		}
		if ($type=='Telephone')
			$this->new_telephone_key=$telecom_key;
		elseif ($type=='Mobile')
			$this->new_mobile_key=$telecom_key;
		else
			$this->new_fax_key=$telecom_key;



	}



	function update_name($value,$options='') {
		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Customer Name');
			return;
		}

		$field='Customer Name';
		$this->update_field($field,$value,$options);

	}
	function update_file_as($value,$options='') {
		$field='Customer File As';
		$this->update_field($field,$value,$options.' nohistory');

	}


	function update_main_contact_name($value,$options='') {
		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Customer Contact Name');
			return;
		}
		$field='Customer Main Contact Name';
		$this->update_field($field,$value,$options);
	}




	function update_child_main_contact_name($value) {

		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Contact Name');
			return;
		}

		$contact=new Contact($this->data['Customer Main Contact Key']);
		$contact->editor=$this->editor;
		$contact->update(array('Contact Name'=>$value));


		if ($contact->updated) {

			$this->updated=true;
			$this->new_value=$contact->new_value;
		}

	}


	function update_child_name($value) {

		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Customer Name');
			return;
		}

		if ($this->data['Customer Type']=='Company') {



			$company=new Company($this->data['Customer Company Key']);
			$company->editor=$this->editor;
			$company->update(array('Company Name'=>$value));


			if ($company->updated) {

				$this->updated=true;
				$this->new_value=$company->new_value;
			}

		} else {
			$contact=new Contact($this->data['Customer Main Contact Key']);
			$contact->editor=$this->editor;
			$contact->update(array('Contact Name'=>$value));

			if ($contact->updated) {

				$this->updated=true;
				$this->new_value=$contact->new_value;
			}

		}

	}


	/*
      function:update_main_contact_key
    */
	function update_main_contact_key($contact_key=false) {

		if (!$contact_key)
			return;

		$contact=new Contact($contact_key);
		if (!$contact->id)
			return;

		if ($this->data['Customer Type']=='Company') {
			$sql=sprintf("select `Is Active` from `Contact Bridge` where `Subject`='Company' and `Subjet Key`=%d and `Contact Key`=%d "
				,$this->data['Customer Comapany Key']
				,$contact->id
			);
			$res=mysql_query($sql);
			$number=mysql_num_rows($res);
			if ($number==0) {
				$this->error=true;
				$msg=_('Contact not in company').".";
				$this->msg.=$msg;
				$this->msg_updated.=$msg;
				return;
			}


		}
		$old_key_value=$this->data['Customer Main Contact Key'];
		$old_value=$this->data['Customer Main Contact Name'];
		$old_contact=new Contact ($this->data['Customer Main Contact Key']);
		$sql=sprintf("update `Customer Dimension` set `Customer Main Contact Key`=%d ,`Customer Main Contact Name`=%s where `Customer Key`=%d"
			,$contact->id
			,prepare_mysql($contact->display('name'))
			,$this->id
		);

		mysql_query($sql);
		$this->data['Customer Main Contact Key']=$contact->id;
		$this->data['Customer Main Contact Name']=$contact->display('name');

		$updated=false;
		if ($this->data['Customer Main Contact Key']==$old_key_value) {
			if ($this->data['Customer Main Contact Name']!=$old_value) {
				$updated=true;
				$field='Customer Contact Name';
				$note=$field.' '._('Changed');
				$details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Customer Main Contact Name']."\"";
			}

		} else {// new contact
			$updated=true;
			$field='Customer Contact';
			$note=$field.' '._('Changed');

			$details=$field.' '._('changed from')." \""
				.$old_value."\"(".$old_contact->get("ID").") "
				._('to')." \"".$this->data['Customer Main Contact Name']."\" (".$contact->get("ID").")";

		}


		if ($updated) {
			$this->updated=true;
			$this->msg=$details;
			$this->msg_updated=$details;
			$history_data=array(
				'Indirect Object'=>$field
				,'History Details'=>$details
				,'History Abstract'=>$note
			);
			$this->add_customer_history($history_data);
		}

	}




	/*
      function:update_email
    */








	/*
      function:update_contact
    */
	function update_contact($contact_key=false) {
		$this->associated=false;
		if (!$contact_key)
			return;
		$contact=new contact($contact_key);
		if (!$contact->id) {
			$this->msg='contact not found';
			return;

		}


		$old_contact_key=$this->data['Customer Main Contact Key'];

		if ($old_contact_key  and $old_contact_key!=$contact_key   ) {
			$this->remove_contact();
		}

		$sql=sprintf("insert into `Contact Bridge` values (%d,'Customer',%d,'Yes','Yes')",
			$contact->id,
			$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			$this->associated=true;

		}



		$old_name=$this->data['Customer Main Contact Name'];
		if ($old_name!=$contact->display('name') or $this->new) {


			if ($this->data['Customer Type']=='Person'
				and $this->data['Customer Name']!=$contact->display('name')) {
				$old_customer_name=$this->data['Customer Name'];
				$this->data['Customer Name']=$contact->display('name');
				$this->data['Customer File As']=$contact->data['Contact File As'];
				$sql=sprintf("update `Customer Dimension` set `Customer Name`=%s,`Customer File As`=%s where `Customer Key`=%d"
					,prepare_mysql($this->data['Customer Name'])
					,prepare_mysql($this->data['Customer File As'])
					,$this->id
				);
				mysql_query($sql);
				$note=_('Contact name changed');
				$details=_('Customer Name changed from')." \"".$old_customer_name."\" "._('to')." \"".$this->data['Customer Name']."\"";
				$history_data=array(
					'Indirect Object'=>'Customer Name'
					,'History Details'=>$details
					,'History Abstract'=>$note
					,'Action'=>'edited'
				);
				$this->add_customer_history($history_data);

			}

			$this->data['Customer Main Contact Key']=$contact->id;
			$this->data['Customer Main Contact Name']=$contact->display('name');
			$sql=sprintf("update `Customer Dimension` set `Customer Main Contact Key`=%d,`Customer Main Contact Name`=%s where `Customer Key`=%d"

				,$this->data['Customer Main Contact Key']
				,prepare_mysql($this->data['Customer Main Contact Name'])
				,$this->id
			);
			mysql_query($sql);



			$this->updated=true;






			$note=_('Customer contact name changed');
			if ($old_contact_key) {
				$details=_('Customer contact name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Customer Main Contact Name']."\"";
			} else {
				$details=_('Customer contact set to')." \"".$this->data['Customer Main Contact Name']."\"";
			}

			$history_data=array(
				'Indirect Object'=>'Customer Main Contact Name',
				'History Details'=>$details,
				'History Abstract'=>$note,
				'Action'=>'edited'
			);
			$this->add_customer_history($history_data);

		}


		if ($this->associated) {
			$note=_('Contact Associted with Customer');
			$details=_('Contact')." ".$contact->display('name')." (".$contact->get_formated_id_link().") "._('associated with Customer:')." ".$this->data['Customer Name']." (".$this->get_formated_id_link().")";
			$history_data=array(
				'Indirect Object'=>'Customer Name'
				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited',
				'Deep'=>2
			);
			$this->add_customer_history($history_data,true);
		}

	}

	function update_company($company_key=false) {


		$this->associated=false;
		if (!$company_key) {
			print "error no comapby key";
			return;
		}


		$company=new company($company_key);
		if (!$company->id) {
			$this->msg='company not found';
			print $this->msg;
			return;

		}


		$old_company_key=$this->data['Customer Company Key'];

		if ($old_company_key  and $old_company_key!=$company_key   ) {
			$this->remove_company();
		}

		$sql=sprintf("insert into `Company Bridge` values (%d,'Customer',%d,'Yes','Yes')",
			$company->id,
			$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			$this->associated=true;

		}



		$old_name=$this->data['Customer Company Name'];
		// print $old_name.'->'.$company->data['Company Name'];

		if ($old_name!=$company->data['Company Name'] or $this->new) {


			if ($this->data['Customer Type']=='Company' and $this->data['Customer Name']!=$company->data['Company Name']) {
				$old_customer_name=$this->data['Customer Name'];
				$this->data['Customer Name']=$company->data['Company Name'];
				$this->data['Customer File As']=$company->data['Company File As'];
				$sql=sprintf("update `Customer Dimension` set `Customer Main Name`=%d,`Customer File As`=%s where `Customer Key`=%d"
					,prepare_mysql($this->data['Customer Name'])
					,prepare_mysql($this->data['Customer File As'])
					,$this->id
				);
				mysql_query($sql);
				$note=_('Company name changed');
				$details=_('Customer Name changed from')." \"".$old_customer_name."\" "._('to')." \"".$this->data['Customer Name']."\"";
				$history_data=array(
					'Indirect Object'=>'Customer Name'
					,'History Details'=>$details
					,'History Abstract'=>$note
					,'Action'=>'edited'
				);
				$this->add_customer_history($history_data);

			}

			$this->data['Customer Company Key']=$company->id;
			$this->data['Customer Company Name']=$company->data['Company Name'];
			$sql=sprintf("update `Customer Dimension` set `Customer Company Key`=%d,`Customer Company Name`=%s where `Customer Key`=%d"

				,$this->data['Customer Company Key']
				,prepare_mysql($this->data['Customer Company Name'])
				,$this->id
			);
			mysql_query($sql);

			//print $sql;

			$this->updated=true;






			$note=_('Customer company name changed');
			if ($old_company_key) {
				$details=_('Customer company name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Customer Company Name']."\"";
			} else {
				$details=_('Customer company set to')." \"".$this->data['Customer Company Name']."\"";
			}

			$history_data=array(
				'Indirect Object'=>'Customer Company Name'

				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited'
			);
			//$this->add_customer_history($history_data);

		}


		if ($this->associated) {
			$note=_('Company associated with Customer');
			$details=_('Company')." ".$company->data['Company Name']." (".$company->get_formated_id_link().") "._('associated with Customer:')." ".$this->data['Customer Name']." (".$this->get_formated_id_link().")";
			$history_data=array(
				'Indirect Object'=>'Customer Name'
				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited',
				'Deep'=>2
			);
			$this->add_customer_history($history_data,true);
		}

		$this->update_contact($company->data['Company Main Contact Key']);

	}



	public function update_no_normal_data() {

		return;

		$sql="select min(`Order Date`) as date   from `Order Dimension` where `Order Customer Key`=".$this->id;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$first_order_date=date('U',strtotime($row['date']));
			if ($row['date']!=''
				and (
					$this->data['Customer First Contacted Date']==''
					or ( date('U',strtotime($this->data['Customer First Contacted Date']))>$first_order_date  )
				)
			) {

				//print $this->data['Customer First Contacted Date']." ->  ".$row['date']."\n";

				$sql=sprintf("update `Customer Dimension` set `Customer First Contacted Date`=%s  where `Customer Key`=%d"
					,prepare_mysql($row['date'])
					,$this->id
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
			$this->data['Customer Lost Date']=date('Y-m-d H:i:s',strtotime($this->data['Customer First Contacted Date']." +".$store->data['Store Lost Customer Interval']." seconds"));
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
			$this->data['Customer Lost Date']=date('Y-m-d H:i:s',
				strtotime($this->data['Customer Last Order Date']." +$lost_interval seconds")
			);

		}

		$sql=sprintf("update `Customer Dimension` set `Customer Active`=%s,`Customer Type by Activity`=%s , `Customer Lost Date`=%s where `Customer Key`=%d"
			,prepare_mysql($this->data['Customer Active'])
			,prepare_mysql($this->data['Customer Type by Activity'])
			,prepare_mysql($this->data['Customer Lost Date'])
			,$this->id
		);

		//   print "\n $orders\n$sql\n";
		//  exit;
		if (!mysql_query($sql))
			exit("\n$sql\n error");

	}

	public function update_activity_old($date='') {
		if ($date=='')
			$date=date("Y-m-d H:i:s");
		$sigma_factor=3.2906;//99.9% value assuming normal distribution


		$this->data['Customer Lost Date']='';
		$this->data['Actual Customer']='Yes';

		$orders= $this->data['Customer Orders'];





		//print $this->id." $orders  \n";

		if ($orders==0) {

		}
		elseif ($orders==1) {
			$sql="select avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>1";

			$result2=mysql_query($sql);
			if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$average_max_interval=$row2['a'];
				//  print "-------> $average_max_interval\n";
				if (is_numeric($average_max_interval)) {
					if (   (strtotime('now')-strtotime($this->data['Customer Last Order Date']))/(3600*24)  <  $average_max_interval) {

						$this->data['Active Customer']='Maybe';
						$this->data['Customer Type by Activity']='Active';

					} else {
						$this->data['Active Customer']='No';
						$this->data['Customer Type by Activity']='Inactive';
						//   print $this->data['Customer Last Order Date']." +$average_max_interval days\n";
						$this->data['Customer Lost Date']=date("Y-m-d H:i:s",strtotime($this->data['Customer Last Order Date']." +".ceil($average_max_interval)." day" ));
					}
				} else {
					$this->data['Active Customer']='Unknown';
					$this->data['Customer Type by Activity']='Unknown';
				}

			} else {
				$this->data['Active Customer']='Unknown';
				$this->data['Customer Type by Activity']='Unknown';
			}

		}
		else {
			//print $this->data['Customer Last Order Date']."\n";

			$last_date=date('U',strtotime($this->data['Customer Last Order Date']));
			//print ((date('U')-$last_date)/3600/24)."\n";
			// print_r($this->data);

			if ($orders==2) {
				$sql="select avg(`Customer Order Interval`) as i, avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>2";

				$result2=mysql_query($sql);
				if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$a_inteval=$row2['a'];
					$i_inteval=$row2['i'];
				}
				if ($i_inteval==0)
					$factor=3;
				else
					$factor=$a_inteval/$i_inteval;

				$interval=ceil($this->data['Customer Order Interval']*$factor);

				// print "----------> $interval $factor  \n";


			} else
				$interval=ceil($this->data['Customer Order Interval']+($sigma_factor*$this->data['Customer Order Interval STD']));


			if ($interval<$min_interval)
				$interval=$min_interval;
			// print "----------> $interval\n";

			if ( (date('U')-$last_date)/24/3600  <$interval) {
				$this->data['Active Customer']='Yes';
				$this->data['Customer Type by Activity']='Active';
			} else {
				$this->data['Active Customer']='No';
				$this->data['Customer Type by Activity']='Inactive';
				$this->data['Customer Lost Date']=date("Y-m-d H:i:s",strtotime($this->data['Customer Last Order Date']." +".$interval." day" ));
			}
		}

		$sql=sprintf("update `Customer Dimension` set `Actual Customer`=%s,`Active Customer`=%s,`Customer Type by Activity`=%s , `Customer Lost Date`=%s where `Customer Key`=%d"
			,prepare_mysql($this->data['Actual Customer'])
			,prepare_mysql($this->data['Active Customer'])
			,prepare_mysql($this->data['Customer Type by Activity'])
			,prepare_mysql($this->data['Customer Lost Date'])
			,$this->id
		);

		//   print "$sql\n";
		if (!mysql_query($sql))
			exit("$sql error");

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
		//    print (date('U')." ".strtotime($this->data['Customer First Contacted Date']))." $interval  \n";
		//    print $sql;
		mysql_query($sql);
			


	}
	public function update_orders_old() {
		$sigma_factor=3.2906;//99.9% value assuming normal distribution

		$sql="select sum(`Order Profit Amount`) as profit,sum(`Order Net Refund Amount`+`Order Net Credited Amount`) as net_refunds,sum(`Order Outstanding Balance Net Amount`) as net_outstanding, sum(`Order Balance Net Amount`) as net_balance,sum(`Order Tax Refund Amount`+`Order Tax Credited Amount`) as tax_refunds,sum(`Order Outstanding Balance Tax Amount`) as tax_outstanding, sum(`Order Balance Tax Amount`) as tax_balance, min(`Order Date`) as first_order_date ,max(`Order Date`) as last_order_date,count(*)as orders, sum(if(`Order Current Payment State` like '%Cancelled',1,0)) as cancelled,  sum( if(`Order Current Payment State` like '%Paid%'    ,1,0)) as invoiced,sum( if(`Order Current Payment State` like '%Refund%'    ,1,0)) as refunded,sum(if(`Order Current Dispatch State`='Unknown',1,0)) as unknown   from `Order Dimension` where `Order Customer Key`=".$this->id;

		$this->data['Customer Orders']=0;
		$this->data['Customer Orders Cancelled']=0;
		$this->data['Customer Orders Invoiced']=0;
		$this->data['Customer First Order Date']='';
		$this->data['Customer Last Order Date']='';
		$this->data['Customer Order Interval']='';
		$this->data['Customer Order Interval STD']='';

		$this->data['Customer Net Balance']=0;
		$this->data['Customer Net Refunds']=0;
		$this->data['Customer Net Payments']=0;
		$this->data['Customer Tax Balance']=0;
		$this->data['Customer Tax Refunds']=0;
		$this->data['Customer Tax Payments']=0;
		$this->data['Customer Profit']=0;
		$this->data['Customer With Orders']='No';

		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data['Customer Orders']=$row['orders'];
			$this->data['Customer Orders Cancelled']=$row['cancelled'];
			$this->data['Customer Orders Invoiced']=$row['invoiced'];
			//print_r($row);
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

			if ($this->data['Customer Orders']==1) {
				$sql="select avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension`";

				$result2=mysql_query($sql);
				if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$average_max_interval=$row2['a'];
					if (is_numeric($average_max_interval)) {
						if (   (strtotime('now')-strtotime($this->data['Customer Last Order Date']))/(3600*24)  <  $average_max_interval) {
							$this->data['Active Customer']='Maybe';
							$this->data['Customer Type by Activity']='New';

						} else {
							$this->data['Active Customer']='No';
							$this->data['Customer Type by Activity']='Inactive';

						}
					} else
						$this->data['Active Customer']='Unknown';
					$this->data['Customer Type by Activity']='Unknown';


				}

			}

			if ($this->data['Customer Orders']>1) {
				$sql="select `Order Date` as date from `Order Dimension` where `Order Customer Key`=".$this->id." order by `Order Date`";
				$last_order=false;
				$intervals=array();
				$result2=mysql_query($sql);
				while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
					$this_date=date('U',strtotime($row2['date']));
					if ($last_order) {
						$intervals[]=($this_date-$last_date)/3600/24;
					}

					$last_date=$this_date;
					$last_order=true;

				}
				//  print $sql;
				//print_r($intervals);


				$this->data['Customer Order Interval']=average($intervals);
				$this->data['Customer Order Interval STD']=deviation($intervals);




			}



			$sql=sprintf("update `Customer Dimension` set `Customer Net Balance`=%.2f,`Customer Orders`=%d,`Customer Orders Cancelled`=%d,`Customer Orders Invoiced`=%d,`Customer First Order Date`=%s,`Customer Last Order Date`=%s,`Customer Order Interval`=%s,`Customer Order Interval STD`=%s,`Customer Net Refunds`=%.2f,`Customer Net Payments`=%.2f,`Customer Outstanding Net Balance`=%.2f,`Customer Tax Balance`=%.2f,`Customer Tax Refunds`=%.2f,`Customer Tax Payments`=%.2f,`Customer Outstanding Tax Balance`=%.2f,`Customer Profit`=%.2f where `Customer Key`=%d",
				$this->data['Customer Net Balance']
				,$this->data['Customer Orders']
				,$this->data['Customer Orders Cancelled']
				,$this->data['Customer Orders Invoiced']
				,prepare_mysql($this->data['Customer First Order Date'])
				,prepare_mysql($this->data['Customer Last Order Date'])
				,prepare_mysql($this->data['Customer Order Interval'])
				,prepare_mysql($this->data['Customer Order Interval STD'])
				,$this->data['Customer Net Refunds']
				,$this->data['Customer Net Payments']
				,$this->data['Customer Outstanding Net Balance']

				,$this->data['Customer Tax Balance']
				,$this->data['Customer Tax Refunds']
				,$this->data['Customer Tax Payments']
				,$this->data['Customer Outstanding Tax Balance']

				,$this->data['Customer Profit']



				,$this->id
			);
			//print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql error");
		}


		//      $sql=sprintf("select `Customer Orders` from `Customer Dimension` order by `Customer Order`");



	}

	public function update_orders() {


		setlocale(LC_ALL, 'en_GB');
		$sigma_factor=3.2906;//99.9% value assuming normal distribution

		$sql="select sum(`Order Profit Amount`) as profit,sum(`Order Net Refund Amount`+`Order Net Credited Amount`) as net_refunds,sum(`Order Outstanding Balance Net Amount`) as net_outstanding, sum(`Order Balance Net Amount`) as net_balance,sum(`Order Tax Refund Amount`+`Order Tax Credited Amount`) as tax_refunds,sum(`Order Outstanding Balance Tax Amount`) as tax_outstanding, sum(`Order Balance Tax Amount`) as tax_balance, min(`Order Date`) as first_order_date ,max(`Order Date`) as last_order_date,count(*)as orders, sum(if(`Order Current Payment State` like '%Cancelled',1,0)) as cancelled,  sum( if(`Order Current Payment State` like '%Paid%'    ,1,0)) as invoiced,sum( if(`Order Current Payment State` like '%Refund%'    ,1,0)) as refunded,sum(if(`Order Current Dispatch State`='Unknown',1,0)) as unknown   from `Order Dimension` where `Order Customer Key`=".$this->id;

		$this->data['Customer Orders']=0;
		$this->data['Customer Orders Cancelled']=0;
		$this->data['Customer Orders Invoiced']=0;
		$this->data['Customer First Order Date']='';
		$this->data['Customer Last Order Date']='';
		$this->data['Customer Order Interval']='';
		$this->data['Customer Order Interval STD']='';

		$this->data['Customer Net Balance']=0;
		$this->data['Customer Net Refunds']=0;
		$this->data['Customer Net Payments']=0;
		$this->data['Customer Tax Balance']=0;
		$this->data['Customer Tax Refunds']=0;
		$this->data['Customer Tax Payments']=0;
		$this->data['Customer Profit']=0;
		$this->data['Customer With Orders']='No';

		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data['Customer Orders']=$row['orders'];
			$this->data['Customer Orders Cancelled']=$row['cancelled'];
			$this->data['Customer Orders Invoiced']=$row['invoiced'];
			//print_r($row);
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



			if ($this->data['Customer Orders']>1) {
				$sql="select `Order Date` as date from `Order Dimension` where `Order Customer Key`=".$this->id." order by `Order Date`";
				$last_order=false;
				$intervals=array();
				$result2=mysql_query($sql);
				while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
					$this_date=date('U',strtotime($row2['date']));
					if ($last_order) {
						$intervals[]=($this_date-$last_date);
					}

					$last_date=$this_date;
					$last_order=true;

				}
				$this->data['Customer Order Interval']=average($intervals);
				$this->data['Customer Order Interval STD']=deviation($intervals);




			}



			$sql=sprintf("update `Customer Dimension` set `Customer Net Balance`=%.2f,`Customer Orders`=%d,`Customer Orders Cancelled`=%d,`Customer Orders Invoiced`=%d,`Customer First Order Date`=%s,`Customer Last Order Date`=%s,`Customer Order Interval`=%s,`Customer Order Interval STD`=%s,`Customer Net Refunds`=%.2f,`Customer Net Payments`=%.2f,`Customer Outstanding Net Balance`=%.2f,`Customer Tax Balance`=%.2f,`Customer Tax Refunds`=%.2f,`Customer Tax Payments`=%.2f,`Customer Outstanding Tax Balance`=%.2f,`Customer Profit`=%.2f ,`Customer With Orders`=%s  where `Customer Key`=%d",
				$this->data['Customer Net Balance']
				,$this->data['Customer Orders']
				,$this->data['Customer Orders Cancelled']
				,$this->data['Customer Orders Invoiced']
				,prepare_mysql($this->data['Customer First Order Date'])
				,prepare_mysql($this->data['Customer Last Order Date'])
				,prepare_mysql($this->data['Customer Order Interval'])
				,prepare_mysql($this->data['Customer Order Interval STD'])
				,$this->data['Customer Net Refunds']
				,$this->data['Customer Net Payments']
				,$this->data['Customer Outstanding Net Balance']

				,$this->data['Customer Tax Balance']
				,$this->data['Customer Tax Refunds']
				,$this->data['Customer Tax Payments']
				,$this->data['Customer Outstanding Tax Balance']

				,$this->data['Customer Profit']
				,prepare_mysql($this->data['Customer With Orders'])


				,$this->id
			);
			//print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql error");
		}


		//      $sql=sprintf("select `Customer Orders` from `Customer Dimension` order by `Customer Order`");



	}





	function updatex($values,$args='') {
		$res=array();
		foreach ($values as $data) {

			$key=$data['key'];
			$value=$data['value'];
			$res[$key]=array('ok'=>false,'msg'=>'');

			switch ($key) {

			case('tax_number_valid'):
				if ($value)
					$this->data['tax_number_valid']=1;
				else
					$this->data['tax_number_valid']=0;

				break;

			case('tax_number'):
				$this->data['tax_number']=$value;
				if ($value=='')
					$this->update(array(array('key'=>'tax_number_valid','value'=>0)),'save');
				break;
			case('main_email'):
				$main_email=new email($value);
				if (!$main_email->id) {
					$res[$key]['msg']=_('Email not found');
					$res[$key]['ok']=false;
					continue;
				}
				$this->old['main_email']=$this->data['main']['email'];
				$this->data['main_email']=$value;
				$this->data['main']['email']=$main_email->data['email'];
				$res[$key]['ok']=true;


			}
			if (preg_match('/save/',$args)) {
				$this->save($key);
			}

		}
		return $res;
	}



	function get($key,$arg1=false) {

		if ($key=='Customer Tax Number' or $key=='Tax Number') {
			return $this->get_tax_number();
		}
		if ($key=='Customer Registration Number' or $key=='Registration Number') {
			return $this->get_registration_number();
		}
		elseif ($key=='Customer Fiscal Name' or $key=='Fiscal Name') {
			return $this->get_fiscal_name();
		}
		elseif (array_key_exists($key,$this->data)) {
			return $this->data[$key];
		}
		elseif (preg_match('/^contact /i',$key)) {
			if (!$this->contact_data)
				$this->load('contact data');
			if (isset($this->contact_data[$key]))
				return $this->contact_data[$key];
		}
		elseif (preg_match('/^ship to /i',$key)) {
			if (!$arg1)
				$ship_to_key=$this->data['Customer Main Delivery Address Key'];
			else
				$ship_to_key=$arg1;
			if (!$this->ship_to[$ship_to_key])
				$this->load('ship to',$ship_to_key);
			if (isset($this->ship_to[$ship_to_key])    and  array_key_exists($key,$this->ship_to[$ship_to_key]) )
				return $this->ship_to[$ship_to_key][$key];
		}



		switch ($key) {
		case('Lost Date'):
		case('Last Order Date'):
		case('First Order Date'):
		case('First Contacted Date'):
		case('Last Order Date'):
			if ($this->data['Customer '.$key]=='')
				return '';
			return strftime("%a %e %b %Y", strtotime($this->data['Customer '.$key]." +00:00"));
			break;
		case('Orders'):
			return number($this->data['Customer Orders']);
			break;
		case('Notes'):
			$sql=sprintf("select count(*) as total from  `Customer History Bridge`     where `Customer Key`=%d and `Type`='Notes'  ",$this->id);
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
		case("Formated ID"):
			return $this->get_formated_id();
		case("Sticky Note"):
			return nl2br($this->data['Customer Sticky Note']);
			break;
		case('Net Balance'):
			return money($this->data['Customer Net Balance']);
			break;
		case('Total Net Per Order'):
			if ($this->data['Customer Orders Invoiced']>0)
				return money($this->data['Customer Net Balance']/$this->data['Customer Orders Invoiced']);
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
		case('order within'):

			if (!$args)
				$args='1 MONTH';
			//get customer last invoice;
			$sql="select count(*)as num  from `Order Dimension` where `Order Type`='Order' and `Order Current Dispatch State`!='Cancelled' and `Order Customer Key`=".$this->id." and DATE_SUB(CURDATE(),INTERVAL $args) <=`Order Date`  ";
			// print $sql;

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				if ($row['num']>0)
					return true;
			}
			return false;
			break;
		case('Tax Rate'):
			return $this->get_tax_rate();
			break;
		case('Tax Code'):
			return $this->data['Customer Tax Category Code'];
			break;
		case('xhtml ship to'):


			if (!$arg1)
				$ship_to_key=$this->data['Customer Main Delivery Address Key'];
			else
				$ship_to_key=$arg1;

			if (!$ship_to_key) {
				print_r($this->data);
				print "\n*** Warning no ship to key un customer.php\n";
				sdsd();
				exit("error in class customer\n");
				return false;

			}

			if (!isset($this->ship_to[$ship_to_key]['ship to key']))
				$this->load('ship to',$ship_to_key);


			//print_r($this->ship_to);

			if (isset($this->ship_to[$ship_to_key]['Ship To Key'])) {
				$contact=$this->ship_to[$ship_to_key]['Ship To Contact Name'];
				$company=$this->ship_to[$ship_to_key]['Ship To Company Name'];
				$address=$this->ship_to[$ship_to_key]['Ship To XHTML Address'];
				$tel=$this->ship_to[$ship_to_key]['Ship To Telephone'];
				$ship_to='';
				if ($contact!='')
					$ship_to.='<b>'.$contact.'</b>';
				if ($company!='')
					$ship_to.='<br/>'.$company;
				if ($address!='')
					$ship_to.='<br/>'.$address;
				if ($tel!='')
					$ship_to.='<br/>'.$tel;
				return $ship_to;
			}

			return false;
			break;

			//   case('customer main address key')


			//   case('location'):
			//      if(!isset($this->data['location']))
			//        $this->load('location');
			//      return $this->data['location']['country_code'].$this->data['location']['town'];
			//      break;
			//    case('super_total'):
			//           return $this->data['total_nd']+$this->data['total'];
			//    break;
			//    case('orders'):
			//      return $this->data['num_invoices']+$this->data['num_invoices_nd'];
			//      break;
			//    default:
			//      if(isset($this->data[$key]))
			//        return $this->data[$key];
			//      else
			//        return '';
		}

		$_key=ucwords($key);
		if (isset($this->data[$_key]))
			return $this->data[$_key];

		//print "Error ->$key not found in get,* from Customer\n";
		//exit;
		return false;

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




	function update_address_data($address_key=false) {

		if (!$address_key)
			return;
		$address=new Address($address_key);
		if (!$address->id)
			return;

		if ($address->id!=$this->data['Customer Main Address Key'] and $this->data['Customer Billing Address Link']=='Contact') {
			$this->data['Customer Billing Address Key']=$address->id;
			$sql=sprintf("update `Customer Dimension` set `Customer Billing Address Key`=%d   where `Customer Key`=%d"

				,$this->data['Customer Billing Address Key']



				,$this->id
			);


			mysql_query($sql);


		}


		if (
			$address->id!=$this->data['Customer Main Address Key']
			or $address->display('xhtml')!=$this->data['Customer Main XHTML Address']
			or $address->display('plain')!=$this->data['Customer Main Plain Address']
			or $address->display('location')!=$this->data['Customer Main Location']      ) {
			$old_value=$this->data['Customer Main XHTML Address'];
			$this->data['Customer Main Address Key']=$address->id;
			$this->data['Customer Main XHTML Address']=$address->display('xhtml');
			$this->data['Customer Main Country Code']=$address->data['Address Country Code'];
			$this->data['Customer Main Country 2 Alpha Code']=$address->data['Address Country 2 Alpha Code'];



			$this->data['Customer Main Country']=$address->data['Address Country Name'];
			$this->data['Customer Main Location']=$address->display('location');
			$this->data['Customer Main Town']=$address->data['Address Town'];
			$this->data['Customer Main Postal Code']=$address->data['Address Postal Code'];
			$this->data['Customer Main Country First Division']=$address->data['Address Country First Division'];


			$sql=sprintf("update `Customer Dimension` set `Customer Main Address Key`=%d,`Customer Main Plain Address`=%s,`Customer Main XHTML Address`=%s,`Customer Main Country`=%s,`Customer Main Location`=%s,`Customer Main Country Code`=%s,`Customer Main Country 2 Alpha Code`=%s,`Customer Main Town`=%s,`Customer Main Postal Code`=%s ,`Customer Main Country First Division`=%s    where `Customer Key`=%d"

				,$this->data['Customer Main Address Key']
				,prepare_mysql($this->data['Customer Main Plain Address'],false)
				,prepare_mysql($this->data['Customer Main XHTML Address'])
				,prepare_mysql($this->data['Customer Main Country'])
				,prepare_mysql($this->data['Customer Main Location'])
				,prepare_mysql($this->data['Customer Main Country Code'])
				,prepare_mysql($this->data['Customer Main Country 2 Alpha Code'])
				,prepare_mysql($this->data['Customer Main Town'])
				,prepare_mysql($this->data['Customer Main Postal Code'])
				,prepare_mysql($this->data['Customer Main Country First Division'])


				,$this->id
			);


			if (!mysql_query($sql))
				exit("\n\nerror $sql\n");












			if ($old_value!=$this->data['Customer Main XHTML Address']) {

				$note=_('Address Changed');
				if ($old_value!='') {
					$details=_('Customer address changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Customer Main XHTML Address']."\"";
				} else {
					$details=_('Customer address set to')." \"".$this->data['Customer Main XHTML Address']."\"";
				}

				$history_data=array(
					'Indirect Object'=>'Address'
					,'History Details'=>$details
					,'History Abstract'=>$note
				);
				$this->add_customer_history($history_data);

			}




		}

	}


	/*function:get_formated_id_link
      Returns formated id_link
    */
	function get_formated_id_link($customer_id_prefix='') {
		return sprintf('<a class="id" href="customer.php?id=%d">%s</a>',$this->id, $this->get_formated_id($customer_id_prefix));

	}



	function get_formated_id($customer_id_prefix='') {

		;
		$sql="select count(*) as num from `Customer Dimension`";
		$res=mysql_query($sql);
		$min_number_zeros=4;
		if ($row=mysql_fetch_array($res)) {
			if (strlen($row['num'])-1>$min_number_zeros)
				$min_number_zeros=strlen($row['num'])-01;
		}
		if (!is_numeric($min_number_zeros))
			$min_number_zeros=4;

		return sprintf("%s%0".$min_number_zeros."d",$customer_id_prefix, $this->id);

	}

	/* Method: add_tel
       Add/Update an telecom to the Customer
    */

	function update_custom_fields($id, $value) {
		//print 'update';
		//$subject=new CustomField($this->data['Customer Key']);
		//$subject->editor=$this->editor;
		$this->update(array($id=>$value));

		//$this->updated=$subject->updated;
		//$this->msg=$subject->msg;
		//$this->error=$subject->error;
		//$this->new_value=$subject->new_value;
	}

	function update_fiscal_name($value) {
		if ($this->data['Customer Type']=='Person') {
			$this->msg=_("Can't update fiscal name of a person");
			$this->error=true;
			return;
		} else {
			$subject=new Company($this->data['Customer Company Key']);
			$subject->editor=$this->editor;
			$subject->update(array('Company Fiscal Name'=>$value));

		}
		$this->updated=$subject->updated;
		$this->msg=$subject->msg;
		$this->error=$subject->error;
		$this->new_value=$subject->new_value;
	}

	function update_tax_number($value) {
	
	$this->update_field('Customer Tax Number',$value);
	if($this->updated){
		$sql=sprintf("update `Customer Dimension` set `Customer Tax Number Valid`='Unknown', `Customer Tax Number Details Match`='Unknown', `Customer Tax Number Validation Date`=NULL where `Customer Key`=%d",
		$this->id
		);
		mysql_query($sql);
		
	$this->new_value=$value;
	}
	


		
	}


	function update_registration_number_old($value) {
		if ($this->data['Customer Type']=='Person') {
			$subject=new Contact($this->data['Customer Main Contact Key']);
			$subject->editor=$this->editor;

			$subject->update(array('Contact Identification Number'=>$value));

		} else {
			$subject=new Company($this->data['Customer Company Key']);
			$subject->editor=$this->editor;

			$subject->update(array('Company Registration Number'=>$value));

		}
		$this->updated=$subject->updated;
		$this->msg=$subject->msg;
		$this->error=$subject->error;
		$this->new_value=$subject->new_value;
	}

	function get_fiscal_name() {
		if ($this->data['Customer Type']=='Person') {
			$this->data['Customer Fiscal Name']=$this->data['Customer Name'];
			return $this->data['Customer Fiscal Name'];
		} else {
			$subject='Company';
			$subject_key=$this->data['Customer Company Key'];
		}

		$sql=sprintf("select `$subject Fiscal Name` as fiscal_name from `$subject Dimension` where `$subject Key`=%d ",$subject_key);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Customer Fiscal Name']=$row['fiscal_name'];

			return $this->data['Customer Fiscal Name'];
		} else {
			$this->error;
			return '';
		}


	}

	function get_tax_number($reread=false) {
		return $this->data['Customer Tax Number'];
	}

	function get_registration_number($reread=false) {
		return $this->data['Customer Registration Number'];
	}




	function remove_company($company_key=false) {


		if (!$company_key) {
			$company_key=$this->data['Customer Main Company Key'];
		}




		$company=new company($company_key);
		$company->editor=$this->editor;
		if (!$company->id) {
			$this->error=true;
			$this->msg='Wrong company key when trying to remove it';
			$this->msg_updated='Wrong company key when trying to remove it';
		}

		$company->set_scope('Customer',$this->id);
		if ( $company->associated_with_scope) {

			$sql=sprintf("delete `Company Bridge`  where `Subject Type`='Customer' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id

				,$this->data['Customer Main Company Key']
			);
			mysql_query($sql);

			if ($company->id==$this->data['Customer Main Company Key']) {
				$sql=sprintf("update `Customer Dimension` set `Customer Company Name`='' , `Customer Company Key`=''  where `Customer Key`=%d"
					,$this->id
				);

				mysql_query($sql);
				if ($this->data['Customer Type']=='Company') {
					$sql=sprintf("update `Customer Dimension` set `Customer Name`='' , `Customer File As`=''  where `Customer Key`=%d"
						,$this->id
					);

					mysql_query($sql);

				}


			}
		}
	}



	function remove_contact($contact_key=false) {


		if (!$contact_key) {
			$contact_key=$this->data['Customer Main Contact Key'];
		}


		$contact=new contact($contact_key);
		if (!$contact->id) {
			$this->error=true;
			$this->msg='Wrong contact key when trying to remove it';
			$this->msg_updated='Wrong contact key when trying to remove it';
		}

		$contact->set_scope('Customer',$this->id);
		$contact->editor=$this->editor;
		if ( $contact->associated_with_scope) {

			$sql=sprintf("delete `Contact Bridge`  where `Subject Type`='Customer' and  `Subject Key`=%d  and `Contact Key`=%d",
				$this->id

				,$this->data['Customer Main Contact Key']
			);
			mysql_query($sql);

			if ($contact->id==$this->data['Customer Main Contact Key']) {
				$sql=sprintf("update `Customer Dimension` set `Customer Main Contact Name`='' , `Customer Main Contact Key`=''  where `Customer Key`=%d"
					,$this->id
				);

				mysql_query($sql);
				if ($this->data['Customer Type']=='Person') {
					$sql=sprintf("update `Customer Dimension` set `Customer Name`='' , `Customer File As`=''  where `Customer Key`=%d"
						,$this->id
					);

					mysql_query($sql);

				}


			}
		}
	}




	function get_last_order() {
		$order_key=0;
		$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d order by `Order Date` desc  ",$this->id);
		// $sql=sprintf("select *  from `Order Dimension` limit 10");
		// print "$sql\n";
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res,MYSQL_ASSOC)) {
			//   print_r($row);
			$order_key=$row['Order Key'];
			//print "****************$order_key\n";

			//  exit;
		}

		return $order_key;
	}

	function prepare_note($note,$details) {
		$note=_trim($note);
		if ($note=='') {
			$this->msg=_('Empty note');
			return array(0,0,0);
		}


		if ($details=='') {


			$details='';
			if (strlen($note)>1000) {
				$words=preg_split('/\s/',$note);
				$len=0;
				$note='';
				$details='';
				foreach ($words as $word) {
					$len+=strlen($word);
					if ($note=='')
						$note=$word;
					else {
						if ($len<1000)
							$note.=' '.$word;
						else
							$details.=' '.$word;

					}
				}



			}

		}
		return array(1,$note,$details);

	}


	function edit_note($note_key,$note,$details='',$change_date) {

		list($ok,$note,$details)=$this->prepare_note($note,$details);
		if (!$ok) {
			return;
		}
		$sql=sprintf("update `History Dimension` set `History Abstract`=%s ,`History Details`=%s where `History Key`=%d and `Indirect Object`='Customer' and `Indirect Object Key`=%s ",
			prepare_mysql($note),
			prepare_mysql($details),
			$note_key,
			$this->id);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			if ($change_date=='update_date') {
				$sql=sprintf("update `History Dimension` set `History Date`=%s where `History Key`=%d  ",
					prepare_mysql(date("Y-m-d H:i:s")),
					$note_key
				);
				mysql_query($sql);
			}

			$this->updated=true;
			$this->new_value=$note;
		}

	}

	function add_note($note,$details='',$date=false,$deleteable='No',$customer_history_type='Notes',$author=false,$subject=false,$subject_key=false) {


		list($ok,$note,$details)=$this->prepare_note($note,$details);
		if (!$ok) {
			return;
		}
		$history_data=array(
			'History Abstract'=>$note,
			'History Details'=>$details,
			'Action'=>'created',
			'Direct Object'=>'Note',
			'Prepostion'=>'on',
			'Indirect Object'=>'Customer',
			'Indirect Object Key'=>$this->id



		);

		if ($author) {
			$history_data['Author Name']=$author;
		}
		if ($subject) {
			$history_data['Subject']=$subject;
			$history_data['Subject Key']=$subject_key;
		}

		if ($date!='')
			$history_data['Date']=$date;




		$history_key=$this->add_customer_history($history_data,$force_save=true,$deleteable,$customer_history_type);


		$this->updated=true;
		$this->new_value=$history_key;


	}



	function add_customer_history($history_data,$force_save=true,$deleteable='No',$type='Changes') {
		$history_key=$this->add_history($history_data,$force_save=true);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,%s,'No',%s)",
			$this->id,
			$history_key,
			prepare_mysql($deleteable),
			prepare_mysql($type)
		);
		// print $sql;
		mysql_query($sql);
		return $history_key;
	}


	function add_attachment($raw_data) {



		$data=array(
			'file'=>$raw_data['Filename'],
			'Attachment Caption'=>$raw_data['Attachment Caption'],
			'Attachment MIME Type'=>$raw_data['Attachment MIME Type'],
			'Attachment File Original Name'=>$raw_data['Attachment File Original Name']
		);

		$attach=new Attachment('find',$data,'create');
		if ($attach->new) {




			$history_data=array(
				'History Abstract'=>$attach->get_abstract(),
				'History Details'=>$attach->get_details(),
				'Action'=>'associated',
				'Direct Object'=>'Attachment',
				'Prepostion'=>'',
				'Indirect Object'=>'Customer',
				'Indirect Object Key'=>$this->id
			);
			$history_key=$this->add_customer_history($history_data,true,'No','Attachments');

			$sql=sprintf("insert into `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`) values (%d,'Customer History Attachment',%d)",
				$attach->id,
				$history_key
			);
			mysql_query($sql);
			//   print $sql;

			$this->updated=true;
			$this->new_value='';
		} else {
			$this->error;
			$this->msg=$attach->msg;
		}

	}








	function delivery_address_xhtml() {

		if ($this->data['Customer Delivery Address Link']=='None') {

			$address=new Address($this->data['Customer Main Delivery Address Key']);

		}
		elseif ($this->data['Customer Delivery Address Link']=='Billing')
			$address=new Address($this->data['Customer Billing Address Key']);
		else
			$address=new Address($this->data['Customer Main Address Key']);

		$tel=$address->get_formated_principal_telephone();
		if ($tel!='') {
			$tel=_('Tel').': '.$tel.'</br>';
		}

		return $tel.$address->display('xhtml');

	}

	function billing_address_xhtml() {


		if ($this->data['Customer Billing Address Link']=='None') {

			$address=new Address($this->data['Customer Billing Address Key']);

		} else
			$address=new Address($this->data['Customer Main Address Key']);

		return $address->display('xhtml');

	}



	function get_address_keys() {


		$sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}
		return $address_keys;

	}

	function get_is_billing_address($address_key) {
		$is_billing_address=false;

		$sql=sprintf("select * from `Address Bridge` CB where  `Address Function` in ('Billing')  and `Subject Type`='Customer' and `Subject Key`=%d  and `Address Key`=%d  ",
			$this->id,
			$address_key

		);

		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$is_billing_address=true;
		}
		return $is_billing_address;
	}


	function get_delivery_address_objects() {


		$sql=sprintf("select * from `Address Bridge` CB where  `Address Function` in ('Shipping','Contact')  and `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= new Address($row['Address Key']);
		}
		return $address_keys;

	}

	function get_billing_address_objects() {


		$sql=sprintf("select * from `Address Bridge` CB where  `Address Function` in ('Billing')  and `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= new Address($row['Address Key']);
		}
		return $address_keys;

	}



	function get_main_address_key() {
		return $this->data['Customer Main Address Key'];
	}

	function set_current_ship_to($return='key') {
		if (preg_match('/object/i',$return))
			return $this->set_current_ship_to_get_object();
		else
			return $this->set_current_ship_to_get_key();

	}


	function set_current_ship_to_get_key() {

		if ($this->data['Customer Delivery Address Link']=='None') {

			$address=new Address($this->data['Customer Main Delivery Address Key']);
		}
		elseif ($this->data['Customer Delivery Address Link']=='Billing')
			$address=new Address($this->data['Customer Billing Address Key']);
		else
			$address=new Address($this->data['Customer Main Address Key']);




		$line=$address->display('3lines');



		$shipping_addresses['Ship To Line 1']=$line[1];
		$shipping_addresses['Ship To Line 2']=$line[2];
		$shipping_addresses['Ship To Line 3']=$line[3];
		$shipping_addresses['Ship To Town']=$address->data['Address Town'];
		$shipping_addresses['Ship To Postal Code']=$address->data['Address Postal Code'];
		$shipping_addresses['Ship To Country Name']=$address->data['Address Country Name'];
		$shipping_addresses['Ship To Country Key']=$address->data['Address Country Key'];
		$shipping_addresses['Ship To Country Code']=$address->data['Address Country Code'];
		$shipping_addresses['Ship To Country 2 Alpha Code']=$address->data['Address Country 2 Alpha Code'];
		$shipping_addresses['Ship To XHTML Address']=$address->display('xhtml');

		$shipping_addresses['Ship To Country First Division']=$address->data['Address Country First Division'];
		$shipping_addresses['Ship To Country Second Division']=$address->data['Address Country Second Division'];

		//  print_r($shipping_addresses);

		$ship_to= new Ship_To('find create',$shipping_addresses);



		return $ship_to->id;


	}


	function set_current_ship_to_get_object() {
		$ship_to=new Ship_To($this->set_current_ship_to());
		return $ship_to;


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

	function get_telecom_keys($type='Telephone') {


		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`=%s and     `Subject Type`='Customer' and `Subject Key`=%d  group by `Telecom Key` order by `Is Main` desc  "
			,prepare_mysql($type)
			,$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Telecom Key']]= $row['Telecom Key'];
		}
		return $address_keys;

	}


	function get_other_faxes_data() {
		return $this->get_other_telecoms_data('FAX');
	}

	function get_other_mobiles_data() {
		return $this->get_other_telecoms_data('Mobile');
	}
	function get_other_telephones_data() {
		return $this->get_other_telecoms_data('Telephone');
	}



	function get_principal_telecom_comment($type) {
		$comment='';
		if ($this->data['Customer Main '.$type.' Key']) {

			$sql=sprintf("select `Telecom Description` from `Telecom Bridge` B where `Telecom Key`=%d  and `Subject Type`='Customer' and `Subject Key`=%d ",
				$this->data['Customer Main '.$type.' Key'],
				$this->id
			);
			$result=mysql_query($sql);
			//print $sql;
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$comment=$row['Telecom Description'];
			}
		}

		return $comment;
	}



	function get_other_telecoms_data($type='Telephone') {

		$sql=sprintf("select B.`Telecom Key`,`Telecom Description` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Telecom Type`=%s  and `Subject Type`='Customer' and `Subject Key`=%d ",
			prepare_mysql($type),
			$this->id
		);
		//print $sql;
		$telecom_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Telecom Key']!=$this->data["Customer Main $type Key"]) {

				$telecom=new Telecom($row['Telecom Key']);

				$telecom_keys[$row['Telecom Key']]= array(
					'number'=>$telecom->display('plain'),
					'xhtml'=>$telecom->display('xhtml'),
					'label'=>$row['Telecom Description']
				);

			}
		}
		return $telecom_keys;

	}


	function get_principal_email_comment() {
		$comment='';
		if ($this->data['Customer Main Email Key']) {

			$sql=sprintf("select `Email Description` from `Email Bridge` B where `Email Key`=%d  and `Subject Type`='Customer' and `Subject Key`=%d ",
				$this->data['Customer Main Email Key'],
				$this->id
			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$comment=$row['Email Description'];
			}
		}

		return $comment;
	}

	function get_users_keys() {
		$user_keys=array();
		$sql=sprintf("select `User Key` from`Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)
        left join `User Dimension` U on (`User Handle`=E.`Email` and `User Type`='Customer' and `User Parent Key`=%d )
        where  `Subject Type`='Customer' and `Subject Key`=%d "
			,$this->id
			,$this->id
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

			,prepare_mysql($this->data['Customer Main Plain Email'])
			,$this->id
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user_key=$row['User Key'];
		}
		return $user_key;
	}

	function get_other_emails_data() {



		$sql=sprintf("select B.`Email Key`,`Email`,`Email Description`,`User Key` from
        `Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)
        left join `User Dimension` U on (`User Handle`=E.`Email` and `User Type`='Customer' and `User Parent Key`=%d )
        where  `Subject Type`='Customer' and `Subject Key`=%d "
			,$this->id
			,$this->id
		);

		$email_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Email Key']!=$this->data['Customer Main Email Key'])
				$email_keys[$row['Email Key']]= array(
					'email'=>$row['Email'],
					'key'=>$row['Email Key'],
					'xhtml'=>'<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
					'label'=>$row['Email Description'],
					'user_key'=>$row['User Key']
				);
		}
		return $email_keys;

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

	function get_email_keys() {
		$sql=sprintf("select `Email Key` from `Email Bridge` where  `Subject Type`='Customer' and `Subject Key`=%d "
			,$this->id );

		$email_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$email_keys[$row['Email Key']]= $row['Email Key'];
		}
		return $email_keys;

	}
	function get_ship_to_keys() {
		$sql=sprintf("select `Ship To Key` from `Customer Ship To Bridge` where `Customer Key`=%d "
			,$this->id );

		$ship_to=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$ship_to[$row['Ship To Key']]= $row['Ship To Key'];
		}
		return $ship_to;

	}

	function associate_contact($contact_key) {
		$contact_keys=$this->get_contact_keys();
		if (!array_key_exists($contact_key,$contact_keys)) {
			$this->create_contact_bridge($contact_key);

		}
	}

	function associate_company($company_key) {
		$company_keys=$this->get_company_keys();
		if (!array_key_exists($company_key,$company_keys)) {
			$this->create_company_bridge($company_key);

		}
	}

	function create_contact_bridge($contact_key) {
		$sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,%s,%d,'No')  "
			,$contact_key
			,prepare_mysql('Customer')
			,$this->id

		);
		mysql_query($sql);
		if (!$this->get_principal_contact_key()) {
			$this->update_principal_contact($contact_key);
		}



	}

	function create_company_bridge($company_key) {
		$sql=sprintf("insert into  `Company Bridge` (`Company Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,%s,%d,'No')  "
			,$company_key
			,prepare_mysql('Customer')
			,$this->id

		);
		mysql_query($sql);
		if (!$this->get_principal_company_key()) {
			$this->update_principal_company($company_key);
		}



	}
	function update_principal_company($company_key) {
		$main_company_key=$this->get_principal_company_key();

		if ($main_company_key!=$company_key) {
			$company=new Company($company_key);
			$company->editor=$this->editor;
			$company->new=$this->new;

			$sql=sprintf("update `Company Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id
				,$company_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Company Bridge`  set `Is Main`='Yes' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id
				,$company_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Customer Dimension` set  `Customer Company Key`=%d where `Customer Key`=%d",$company->id,$this->id);
			mysql_query($sql);


			$this->data['Customer Company Key']=$company->id;
			$company->update_parents(($this->new?false:true));

		}

	}


	function update_principal_contact($contact_key) {
		$main_contact_key=$this->get_principal_contact_key();

		if ($main_contact_key!=$contact_key) {
			$contact=new Contact($contact_key);
			$contact->editor=$this->editor;
			$contact->new=$this->new;

			$sql=sprintf("update `Contact Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Contact Key`=%d",
				$this->id
				,$contact_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Contact Bridge`  set `Is Main`='Yes' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Contact Key`=%d",
				$this->id
				,$contact_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Customer Dimension` set  `Customer Main Contact Key`=%d where `Customer Key`=%d",$contact->id,$this->id);
			mysql_query($sql);


			$this->data['Customer Main Contact Key']=$contact->id;
			$contact->update_parents(($this->new?false:true));
			$contact->update_parents_principal_email_keys();
			$email=new Email($contact->get_principal_email_key());
			$email->editor=$this->editor;
			$email->new=$this->new;
			if ($email->id)
				$email->update_parents($this->new?false:true);



		}

	}







	function get_principal_contact_key() {

		$sql=sprintf("select `Contact Key` from `Contact Bridge` where `Subject Type`='Customer' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_contact_key=$row['Contact Key'];
		} else {
			$main_contact_key=0;
		}

		return $main_contact_key;
	}


	function get_principal_company_key() {
		$sql=sprintf("select `Company Key` from `Company Bridge` where `Subject Type`='Customer' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_company_key=$row['Company Key'];
		} else {
			$main_company_key=0;
		}

		return $main_company_key;
	}


	function get_contact_keys() {

		$sql=sprintf("select `Contact Key` from `Contact Bridge` where  `Subject Type`='Customer' and `Subject Key`=%d   "
			,$this->id
		);
		$contacts=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$contacts[$row['Contact Key']]= $row['Contact Key'];
		}
		return $contacts;
	}


	function get_contact_cards() {
		$cards=array();

		foreach ($this->get_contact_keys() as $contact_key) {
			$contact=new Contact($contact_key);
			$cards[]=$contact->display('card');
		}
		return $cards;
	}

	function get_company_keys() {

		$sql=sprintf("select `Company Key` from `Company Bridge` where  `Subject Type`='Customer' and `Subject Key`=%d   "
			,$this->id
		);
		$companies=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$companies[$row['Company Key']]= $row['Company Key'];
		}
		return $companies;
	}

	function is_tax_number_valid() {
		if ($this->data['Customer Tax Number']=='')
			return false;
		else {
			return true;
		}

	}


	function get_delivery_address_keys() {


		$sql=sprintf("select * from `Address Bridge` CB where  `Address Function`='Shipping' and  `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}
		return $address_keys;

	}
	function get_billing_address_keys() {


		$sql=sprintf("select * from `Address Bridge` CB where  `Address Function`='Billing' and  `Subject Type`='Customer' and `Subject Key`=%d  group by `Address Key` ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}
		return $address_keys;

	}

	function disassociate_email($email_key) {


		$sql=sprintf("delete from `Email Bridge` where `Subject Type`='Customer' and `Subject Key`=%d  and `Email Key`=%d ",
			$this->id,
			$email_key
		);
		mysql_query($sql);

	}

	function associate_delivery_address($address_key) {
		if (!$address_key) {
			return;

		}
		$address_keys=$this->get_delivery_address_keys();
		if (!array_key_exists($address_key,$address_keys)) {
			$this->create_delivery_address_bridge($address_key);
			$this->updated=true;
			$this->new_data=$address_key;
		}


	}


	function associate_billing_address($address_key) {
		if (!$address_key) {
			return;

		}
		$address_keys=$this->get_billing_address_keys();
		if (!array_key_exists($address_key,$address_keys)) {
			$this->create_billing_address_bridge($address_key);
			$this->updated=true;
			$this->new_data=$address_key;
		}


	}


	function disassociate_billing_address($address_key) {
		if (!$address_key) {
			return;

		}
		$current_billing_address=$this->get_principal_billing_address_key();

		$sql=sprintf("delete * from `Address Bridge` CB where  `Address Function`='Billing' and  `Subject Type`='Customer' and `Subject Key`=%d  and `Address Key` ",$address_key);
		mysql_query($sql);



		if ($current_billing_address==$address_key) {

			$address_keys=$this->get_billing_address_keys();





			if (count($address_keys)==0) {
				$this->update_principal_billing_address($this->get_principal_contact_address_key());
			} else {
				$new_billing_address=array_pop($address_keys);
				$this->update_principal_billing_address($new_billing_address);

			}

		}

		$address=new Address($address_key);
		if ($address->id and !$address->has_parents()) {
			$address->delete();
		}


	}




	function create_contact_address_bridge($address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Customer','Contact',%d,%d)  ",
			$this->id,
			$address_key
		);
		mysql_query($sql);
		//print $this->get_principal_contact_address_key()." $sql\n";
		if (
			!$this->get_principal_contact_address_key()
		) {
			$this->update_only_principal_contact_address($address_key);
		}
	}

	function create_delivery_address_bridge($address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Customer','Shipping',%d,%d)  ",
			$this->id,
			$address_key

		);
		mysql_query($sql);
		if (
			!$this->get_principal_delivery_address_key()
			or ! $this->data['Customer Main Delivery Address Key']
		) {

			$this->update_principal_delivery_address($address_key);
		}

	}
	function create_billing_address_bridge($address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Customer','Billing',%d,%d)  ",
			$this->id,
			$address_key

		);
		mysql_query($sql);

		if (
			!$this->get_principal_billing_address_key()
			or ! $this->data['Customer Billing Address Key']
		) {

			$this->update_principal_billing_address($address_key);
		}

	}


	function update_only_principal_contact_address($address_key) {
		$this->update_principal_address($address_key,false);

	}


	function update_principal_contact_address($address_key) {
		$this->update_principal_address($address_key);
	}

	function update_principal_address($address_key,$update_other_address_type=true) {



		$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Address Key`=%d",
			$this->id
			,$address_key
		);
		mysql_query($sql);
		$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Customer' and  `Subject Key`=%d  and `Address Key`=%d",
			$this->id
			,$address_key
		);
		mysql_query($sql);
		$sql=sprintf("update `Customer Dimension` set `Customer Main Address Key`=%d where `Customer Key`=%d"
			,$address_key
			,$this->id
		);
		mysql_query($sql);
		//print $sql;
		if ($update_other_address_type) {
			if ($this->data['Customer Delivery Address Link']=='Contact') {
				$this->update_principal_delivery_address($address_key);

			}
			if ($this->data['Customer Billing Address Link']=='Contact') {
				$this->update_principal_billing_address($address_key);

			}
		}
		$address=new Address($address_key);
		$address->editor=$this->editor;

		$address->update_parents('Customer',($this->new?false:true));


		$this->updated=true;
		$this->new_value=$address_key;


	}

	function update_principal_delivery_address($address_key) {

		//  $main_address_key=$this->get_principal_delivery_address_key();
		$main_address_key=$this->data['Customer Main Delivery Address Key'];


		if ($main_address_key!=$address_key
			or ( $this->data['Customer Delivery Address Link']='Contact' and  $address_key!=$this->data['Customer Main Address Key']  )
			or ( $this->data['Customer Delivery Address Link']='Billing' and  $address_key!=$this->data['Customer Billing Address Key']  )
			or ( $this->data['Customer Delivery Address Link']='None' and ( $address_key==$this->data['Customer Billing Address Key']
					or $address_key==$this->data['Customer Main Address Key']  ) )

		) {

			$address=new Address($address_key);
			$address->editor=$this->editor;
			$address->new=$this->new;

			$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and `Address Function`='Shipping' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$main_address_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Customer' and `Address Function`='Shipping' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$address_key
			);
			mysql_query($sql);

			if ($address->id==$this->data['Customer Main Address Key']) {
				$this->data['Customer Delivery Address Link']='Contact';
			}
			elseif ($address->id==$this->data['Customer Billing Address Key']) {
				$this->data['Customer Delivery Address Link']='Billing';
			}
			else {
				$this->data['Customer Delivery Address Link']='None';
			}

			$sql=sprintf("update `Customer Dimension` set  `Customer Delivery Address Link`=%s,`Customer Main Delivery Address Key`=%d where `Customer Key`=%d"
				,prepare_mysql($this->data['Customer Delivery Address Link'])
				,$address->id
				,$this->id);
			$this->data['Customer Main Delivery Address Key']=$address->id;
			mysql_query($sql);
			$address->update_parents(false,($this->new?false:true));
			$this->get_data('id',$this->id);
			$this->updated=true;
			$this->new_value=$address->id;
		}

	}

	function update_principal_billing_address($address_key) {


		//  $main_address_key=$this->get_principal_billing_address_key();
		$main_address_key=$this->data['Customer Billing Address Key'];


		if ($main_address_key!=$address_key or
			( $this->data['Customer Billing Address Link']=='Contact'  and $address_key!=$this->data['Customer Main Address Key'] )
			or ( $this->data['Customer Billing Address Link']=='None'  and $address_key==$this->data['Customer Main Address Key'] )
		) {
			$address=new Address($address_key);
			$address->editor=$this->editor;
			$address->new=$this->new;

			$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and `Address Function`='Billing' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$main_address_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Customer' and `Address Function`='Billing' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$address_key
			);
			mysql_query($sql);

			if ($address->id==$this->data['Customer Main Address Key']) {
				$this->data['Customer Billing Address Link']='Contact';
			} else {
				$this->data['Customer Billing Address Link']='None';
			}

			$sql=sprintf("update `Customer Dimension` set  `Customer Billing Address Link`=%s,`Customer Billing Address Key`=%d where `Customer Key`=%d"
				,prepare_mysql($this->data['Customer Billing Address Link'])
				,$address->id
				,$this->id);
			$this->data['Customer Billing Address Key']=$address->id;
			mysql_query($sql);

			$address->update_parents(false,($this->new?false:true));

			$this->get_data('id',$this->id);

			//  $history_data=array(
			//                 'History Abstract'=>_('Billing address Changed'),
			//               'History Details'=>'<div class="history_address" style="border:1px solid grey;padding:5px;width:250px">'.$this->display('xhtml')."</div> "._('address associated with')." ".$parent_object->get_name()." ".$parent_label;

			//             'Action'=>'created'
			//       );
			//$this->add_customer_history($history_data);


			$this->updated=true;
			$this->new_value=$address->id;
		}

	}

	function get_principal_contact_address_key() {
		$main_address_key=0;
		$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Customer' and `Address Function`='Contact' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_address_key=$row['Address Key'];
		}
		return $main_address_key;
	}





	function get_principal_billing_address_key() {

		switch ($this->data['Customer Billing Address Link']) {
		case 'Contact':
			return $this->data['Customer Main Address Key'];
			break;

		default:
			$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Customer' and `Address Function`='Billing' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$main_address_key=$row['Address Key'];
			} else {
				$main_address_key=$this->data['Customer Main Address Key'];
			}

			return $main_address_key;
			break;
		}


	}
	function get_principal_delivery_address_key() {

		switch ($this->data['Customer Delivery Address Link']) {
		case 'Contact':
			return $this->data['Customer Main Address Key'];
			break;
		case 'Billing':
			return $this->data['Customer Billing Address Key'];
		default:
			$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Customer' and `Address Function`='Shipping' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$main_address_key=$row['Address Key'];
			} else {
				$main_address_key=$this->data['Customer Main Address Key'];
			}

			return $main_address_key;
			break;
		}


	}





	function get_ship_to($date=false) {

		if (!$date) {
			$date=date("Y-m-d H:i:s");
		}
		if ($this->data['Customer Active Ship To Records']==0 or !$this->data['Customer Last Ship To Key']) {
			$ship_to= $this->set_current_ship_to('return object');

			$data_ship_to=array(
				'Ship To Key'=>$ship_to->id,
				'Current Ship To Is Other Key'=>false,
				'Date'=>$date
			);

			$this->update_ship_to($data_ship_to);
		} else {



			$ship_to= new Ship_To($this->data['Customer Last Ship To Key']);
		}

		return $ship_to;

	}

	function get_ship_to_data() {
		$address=new address($this->data['Customer Main Delivery Address Key']);
		if ($address->id)
			return $address->get_data_for_ship_to();
		else
			return array();
	}

	function display($tipo='card',$option='') {
		switch ($tipo) {
		case 'card':



			$email_label="E:";
			$tel_label="T:";
			$fax_label="F:";
			$mobile_label="M:";
			$contact_label="C:";

			$email='';
			$tel='';
			$fax='';
			$mobile='';
			$contact='';
			$name=sprintf('<span class="name">%s</span>',$this->data['Customer Name']);
			if ($this->data['Customer Main Contact Name'] and $this->data['Customer Type']=='Company')
				$contact=sprintf('<span class="name">%s %s</span><br/>',$contact_label,$this->data['Customer Main Contact Name']);


			if ($this->data['Customer Main XHTML Email']) {
				$main_email_user_key=$this->get_main_email_user_key();
				if ($main_email_user_key) {
					$user_icon='<a href="site_user.php?id='.$main_email_user_key.'"><img src="art/icons/world_bw.jpg" style="height:11px;position:relative;top:-1px" alt="*"/></a> ';
				}else {
					$user_icon='';
				}
				$email=$user_icon.sprintf('<span class="email">%s</span><br/>',$this->data['Customer Main XHTML Email']);
			}
			if ($this->data['Customer Main XHTML Telephone'])
				$tel=sprintf('<span class="tel">%s %s</span><br/>',$tel_label,$this->data['Customer Main XHTML Telephone']);
			if ($this->data['Customer Main XHTML Mobile'])
				$mobile=sprintf('<span class="tel">%s %s</span><br/>',$mobile_label,$this->data['Customer Main XHTML Mobile']);
			if ($this->data['Customer Main XHTML FAX'])
				$fax=sprintf('<span class="fax">%s %s</span><br/>',$fax_label,$this->data['Customer Main XHTML FAX']);


			$address=sprintf('<span class="mobile">%s</span>',$this->data['Customer Main XHTML Address']);

			$card=sprintf('<div class="contact_card">%s <div  class="tels">%s %s %s %s %s</div><div  class="address">%s</div> </div>'
				,$name
				,$contact
				,$email
				,$tel
				,$mobile
				,$fax

				,$address
			);




			$card=preg_replace('/\<div class=\"contact_card\"\>/','<div class="contact_card"><a href="customer.php?id='.$this->id.'" style="float:left;color:SteelBlue">'.$this->get_formated_id($option).'</a>',$card);
			return $card;

			break;
		default:

			break;
		}


	}

	function display_delivery_address($tipo='xhtml') {
		switch ($tipo) {

		case 'xhtml':
			$address=new address($this->data['Customer Main Delivery Address Key']);
			return $address->display('xhtml');
			break;
		default:
			$address=new address($this->data['Customer Main Delivery Address Key']);
			return $address->get($tipo);
			break;
		}

	}

	function display_billing_address($tipo='xhtml') {
		switch ($tipo) {

		case 'xhtml':
			$address=new address($this->data['Customer Billing Address Key']);
			return $address->display('xhtml');
			break;
		default:
			$address=new address($this->data['Customer Billing Address Key']);
			return $address->get($tipo);
			break;
		}

	}

	function display_contact_address($tipo='xhtml') {
		switch ($tipo) {
		case 'label':
			$address=new address($this->data['Customer Main Address Key']);
			return $this->data['Customer Name']."\n".($this->data['Customer Type']=='Company'?$this->data['Customer Main Contact Name']."\n":'').$address->display('label');
			break;
		case 'xhtml':
			$address=new address($this->data['Customer Main Address Key']);
			return $address->display('xhtml');
			break;
		default:
			$address=new address($this->data['Customer Main Address Key']);
			return $address->get($tipo);
			break;
		}

	}




	function get_address_bridge_data($address_key) {

		$sql=sprintf("select * from `Address Bridge` where `Subject Type`='Customer' and `Subject Key`=%d and `Address Key`=%d",$this->id,$address_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return array('Address Type'=>$row['Address Type'],'Address Function'=>$row['Address Function']);
		}
		return false;
	}






	function update_full_search() {
		$address=new Address($this->data['Customer Main Address Key']);
		$address_plain='';
		if ($address->id) {
			$address_plain=$address->display('Plain');
		}
		$address_plain=$address->data['Address Country Name'].' '.$address->data['Address Postal Code'].' '.$address->data['Address Town'].' '.preg_replace('/[^a-z^A-Z^\d]/','',$address->data['Address Postal Code']);
		$first_full_search=$this->data['Customer Name'].' '.$this->data['Customer Name'].' '.$address_plain.' '.$this->data['Customer Main Contact Name'].' '.$this->data['Customer Main Plain Email'];
		$second_full_search=$this->data['Customer Type'];


		$description='';

		if ($this->data['Customer Type']=='Company') {
			$name='<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formated_id_link().')<br/>'.$this->data['Customer Main Contact Name'];
		} else {
			$name='<b>'.$this->data['Customer Name'].'</b> (Id:'.$this->get_formated_id_link().')';

		}
		$name.='<br/>'._('Orders').':<b>'.number($this->data['Customer Orders']).'</b>';


		$_address=$this->data['Customer Main Plain Email'];

		if ($this->data['Customer Main Telephone Key'])$_address.='<br/>T: '.$this->data['Customer Main XHTML Telephone'];
		$_address.='<br/>'.$this->data['Customer Main Location'];
		if ($this->data['Customer Main Postal Code'])$_address.=', '.$this->data['Customer Main Postal Code'];
		$_address=preg_replace('/^\<br\/\>/','',$_address);

		$description='<table ><tr style="border:none;"><td class="col1">'.$name.'</td><td class="col2">'.$_address.'</td></tr></table>';

		$sql=sprintf("insert into `Search Full Text Dimension`  (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)
                     values  (%s,'Customer',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s"
			,$this->data['Customer Store Key']
			,$this->id
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search)
			,prepare_mysql($this->data['Customer Name'])
			,prepare_mysql($description)
			,"''"
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search)
			,prepare_mysql($this->data['Customer Name'])
			,prepare_mysql($description)


			,"''"
		);
		mysql_query($sql);
	}
	function add_history_new_order($order) {

		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;


		switch ($_SESSION ['text_locale_code'].'_'.$_SESSION ['text_locale_country_code']) {
		default :
			$note = sprintf( '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order'),$order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($order->data['Order Original Data MIME Type']='application/inikoo') {

				if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
					$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',$this->editor['Author Key'],$this->editor['Author Alias'] ,$this->get ( 'Customer Name' ), $this->id,$this->get('Formated ID'), strftime( "%e %b %Y %H:%M", strtotime( $order->data ['Order Date'] ) ) );
				} else {
					$details = sprintf( 'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',
						$this->get ( 'Customer Name' ),
						$this->id,$this->get('Formated ID'),
						$tz_date
					);

				}
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s',
					$this->get ( 'Customer Name' ), $this->id,$this->get('Formated ID'),
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
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$this->id,$history_key);

		mysql_query($sql);

	}
	function add_history_order_cancelled($order) {


		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Cancelled Date']." +00:00" ) );
		$tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :
			$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (Cancelled)',$order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
				$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> cancel %s (<a href="customer.php?id=%d">%s</a>) order <a href="order.php?id=%d">%s</a>  on %s',
					$this->editor['Author Key'],
					$this->editor['Author Alias'] ,
					$this->get ( 'Customer Name' ),
					$this->id,
					$this->get('Formated ID'),
					$order->data ['Order Key'],
					$order->data ['Order Public ID'],
					$tz_date
				);
			} else {
				$details = sprintf( 'Someone cancel  %s (<a href="customer.php?id=%d">%s</a>)  order <a href="order.php?id=%d">%s</a>  on %s',

					$this->get ( 'Customer Name' ),
					$this->id,$this->get('Formated ID'),
					$order->data ['Order Key'],
					$order->data ['Order Public ID'],
					$tz_date
				);

			}
			if ($order->data ['Order Cancel Note']!='')
				$details.='<div> Note: '.$order->data ['Order Cancel Note'].'</div>';


		}
		$history_data=array(
			'Date'=>$order->data ['Order Cancelled Date'],
			'Subject'=>'Customer',
			'Subject Key'=>$this->id,
			'Direct Object'=>'Order',
			'Direct Object Key'=>$order->data ['Order Key'],
			'History Details'=>$details,
			'History Abstract'=>$note,
			'Metadata'=>'Cancelled'

		);

		$sql=sprintf("update `History Dimension` set `Deep`=2 where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d ",
			$this->id,
			$order->id
		);
		mysql_query($sql);
		$history_key=$order->add_history($history_data);
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$this->id,$history_key);
		mysql_query($sql);



		switch ($lang) {
		default :
			$note_created = sprintf( '%s <a href="order.php?id=%d">%s</a> (Created)', _('Order'),$order->data ['Order Key'], $order->data ['Order Public ID'] );

		}
		$sql=sprintf("update `History Dimension` set `History Abstract`=%s where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d and `Metadata`='Process'",
			prepare_mysql($note_created),
			$this->id,
			$order->id
		);
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
			$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (Suspended)',$order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
				$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> suspended %s (<a href="customer.php?id=%d">%s</a>) order <a href="order.php?id=%d">%s</a>  on %s',
					$this->editor['Author Key'],
					$this->editor['Author Alias'] ,
					$this->get ( 'Customer Name' ),
					$this->id,
					$this->get('Formated ID'),
					$order->data ['Order Key'],
					$order->data ['Order Public ID'],
					$tz_date
				);
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>)  order <a href="order.php?id=%d">%s</a>  has been suspended on %s',

					$this->get ( 'Customer Name' ),
					$this->id,$this->get('Formated ID'),
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
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$this->id,$history_key);
		mysql_query($sql);


		switch ($lang) {
		default :
			$note_created = sprintf( '%s <a href="order.php?id=%d">%s</a> (Created)', _('Order'),$order->data ['Order Key'], $order->data ['Order Public ID'] );

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
			$note = sprintf( '%s <a href="dn.php?id=%d">%s</a> (%s)',$dn->data['Delivery Note Type'],$dn->data ['Delivery Note Key'], $dn->data ['Delivery Note ID'],$state );
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
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$this->id,$history_key);
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



			$note=$refund->data['Invoice XHTML Orders'].' '._('refunded for').' '.money(-1*$refund->data['Invoice Total Amount'],$refund->data['Invoice Currency']);
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

		$history_key=$this->add_customer_history($history_data,$force_save=true,$deleteable='No',$type='Orders');



	}

	function update_history_order_in_warehouse($order) {


		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Cancelled Date']." +00:00" ) );
		$tz_date_created=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;

		if (!isset($_SESSION ['lang']))
			$lang=0;
		else
			$lang=$_SESSION ['lang'];

		switch ($lang) {
		default :
			$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (%s) %s %s' ,
				$order->data ['Order Key'],
				$order->data ['Order Public ID'],
				$order->data['Order Current XHTML State'],
				$order->get('Weight'),
				money($order->data['Order Balance Total Amount'],$order->data['Order Currency'])

			);



		}

		$sql=sprintf("update `History Dimension` set `History Abstract`=%s where `Subject`='Customer' and `Subject Key`=%d  and `Direct Object`='Order' and `Direct Object Key`=%d and `Metadata`='Process'",
			prepare_mysql($note),
			$this->id,
			$order->id
		);
		mysql_query($sql);
		//print "$sql\n";

	}
	function add_history_new_post_order($order,$type) {

		date_default_timezone_set(TIMEZONE) ;
		$tz_date=strftime( "%e %b %Y %H:%M %Z", strtotime( $order->data ['Order Date']." +00:00" ) );

		date_default_timezone_set('GMT') ;


		switch ($_SESSION ['lang']) {
		default :
			$note = sprintf( '%s <a href="order.php?id=%d">%s</a> (In Process)', _('Order'),$order->data ['Order Key'], $order->data ['Order Public ID'] );
			if ($order->data['Order Original Data MIME Type']='application/inikoo') {

				if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
					$details = sprintf( '<a href="staff.php?id=%d&took_order">%s</a> took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',$this->editor['Author Key'],$this->editor['Author Alias'] ,$this->get ( 'Customer Name' ), $this->id,$this->get('Formated ID'), strftime( "%e %b %Y %H:%M", strtotime( $order->data ['Order Date'] ) ) );
				} else {
					$details = sprintf( 'Someone took an order for %s (<a href="customer.php?id=%d">%s</a>) on %s',
						$this->get ( 'Customer Name' ),
						$this->id,$this->get('Formated ID'),
						$tz_date
					);

				}
			} else {
				$details = sprintf( '%s (<a href="customer.php?id=%d">%s</a>) place an order on %s',
					$this->get ( 'Customer Name' ), $this->id,$this->get('Formated ID'),
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
		$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Orders')",$this->id,$history_key);
		mysql_query($sql);

	}

	function get_number_of_orders() {
		$sql=sprintf("select count(*) as number from `Order Dimension` where `Order Customer Key`=%d ",$this->id);
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
		$sql=sprintf("update `Customer Dimension` set `Customer Account Operative`='No' where `Customer Key`=%d ",$this->id);
		mysql_query();

	}





	function get_mobiles() {


		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Mobile'    and `Subject Type`='Customer' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
		$mobiles=array();
		$result=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$mobile= new Telecom($row['Telecom Key']);
			$mobile->set_scope('Contact',$this->id);
			$mobiles[]= $mobile;
			$mobile->data['Mobile Is Main']=$row['Is Main'];

		}
		$this->number_mobiles=count($mobiles);
		return $mobiles;

	}

	function get_telephones() {
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Telephone'    and `Subject Type`='Customer' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
		$mobiles=array();
		$result=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$mobile= new Telecom($row['Telecom Key']);
			$mobile->set_scope('Contact',$this->id);
			$mobiles[]= $mobile;
			$mobile->data['Mobile Is Main']=$row['Is Main'];

		}
		//$this->number_mobiles=count($mobiles);
		return $mobiles;
	}

	function get_work_telephones($company_key=false) {
		$telephones=array();
		$in_company='';
		if ($company_key)
			$in_company=sprintf(" and `Auxiliary Key`=%s",$company_key);
		$sql=sprintf('select * from `Telecom Bridge` TB  left join `Telecom Dimension` T on T.`Telecom Key`=TB.`Telecom Key`  where `Subject Key`=%d and `Telecom Type`="Work Telephone"  and `Subject Type`="Contact" %s order by `Is Main` desc ',$this->id,$in_company);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$tel=new Telecom('id',$row['Telecom Key']);

			$telephones[]=array(
				'id'=>$row['Telecom Key']
				,'type'=>$row['Telecom Type']
				,'country_code'=>$row['Telecom Country Telephone Code']
				,'national_access_code'=>$row['Telecom National Access Code']
				,'area_code'=>$row['Telecom Area Code']
				,'number'=>$row['Telecom Number']
				,'extension'=>$row['Telecom Extension']
				,'formated_number'=>$tel->display('formated')

			);
		}
		return $telephones;
	}

	function remove_principal_email() {
		$this->remove_email($this->data['Customer Main Email Key']);
	}

	function remove_principal_mobile() {
		$this->remove_telecom('Mobile',$this->data['Customer Main Mobile Key']);
	}

	function remove_principal_telephone() {
		$this->remove_telecom('Telephone',$this->data['Customer Main Telephone Key']);
	}

	function remove_principal_fax() {
		$this->remove_telecom('Fax',$this->data['Customer Main FAX Key']);
	}

	function remove_email($email_key) {

		$email=new Email($email_key);
		if (!$email->id) {
			return;
			$this->msg='Error, main email not found';
		}


		$email_to_delete_handle=$email->data['Email'];

		$email_customer_keys=$email->get_parent_keys('Customer');
		unset($email_customer_keys[$this->id]);
		$email_contacts_keys=$email->get_parent_keys('Contact');
		unset($email_contacts_keys[$this->data['Customer Main Contact Key']]);

		$email_companies_keys=$email->get_parent_keys('Company');
		unset($email_companies_keys[$this->data['Customer Company Key']]);


		$email_suppliers_keys=$email->get_parent_keys('Supplier');

		$email_customer_number_keys=count($email_customer_keys);
		$email_contacts_number_keys=count($email_contacts_keys);
		$email_suppliers_number_keys=count($email_suppliers_keys);
		$email_companies_number_keys=count($email_companies_keys);



		$email->remove_from_parent('Customer',$this->id);
		if (($email_customer_number_keys+$email_contacts_number_keys+$email_suppliers_number_keys)==0) {


			if ($this->data['Customer Type']=='Company') {
				$company=new Company($this->data['Customer Company Key']);
				$company_customers_keys=$company->get_parent_keys('Customer');
				unset($company_customers_keys[$this->id]);
				$company_suppliers_keys=$company->get_parent_keys('Supplier');
				$company_customers_number_keys=count($company_customers_keys);
				$company_suppliers_number_keys=count($company_suppliers_keys);
				if (($company_suppliers_number_keys+$company_customers_number_keys)==0) {
					$email->remove_from_parent('Company',$company->id);
				}
			}
			$contact=new Contact($this->data['Customer Main Contact Key']);
			$contact_customers_keys=$contact->get_parent_keys('Customer');
			//  print_r($contact_customers_keys);
			unset($contact_customers_keys[$this->id]);
			$contact_suppliers_keys=$contact->get_parent_keys('Supplier');
			$contact_customers_number_keys=count($contact_customers_keys);
			$contact_suppliers_number_keys=count($contact_suppliers_keys);



			if (($contact_suppliers_number_keys+$contact_customers_number_keys)==0) {
				$email->remove_from_parent('Contact',$contact->id);
				$email->delete();
			}


		}

		$sql=sprintf("select `User Key` from  `User Dimension` where `User Handle`=%s and `User Type`='Customer' and `User Parent Key`=%d  and `User Active`='Yes' "

			,prepare_mysql($email_to_delete_handle)
			,$this->id
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user_key=$row['User Key'];
			$_user=new user($user_key);
			$_user->deactivate();
		}


		$this->updated=true;
		$this->msg=_('Email Removed from Customer');;
		$this->new_value='';
		return;


	}

	function remove_telecom($type,$telecom_key) {

		$telecom=new Telecom($telecom_key);
		if (!$telecom->id) {

			$this->msg='Error, main telecom not found';
			return;
		}


		$telecom_customer_keys=$telecom->get_parent_keys('Customer');
		unset($telecom_customer_keys[$this->id]);
		$telecom_contacts_keys=$telecom->get_parent_keys('Contact');
		unset($telecom_contacts_keys[$this->data['Customer Main Contact Key']]);

		$telecom_companies_keys=$telecom->get_parent_keys('Company');
		unset($telecom_companies_keys[$this->data['Customer Company Key']]);


		$telecom_suppliers_keys=$telecom->get_parent_keys('Supplier');

		$telecom_customer_number_keys=count($telecom_customer_keys);
		$telecom_contacts_number_keys=count($telecom_contacts_keys);
		$telecom_suppliers_number_keys=count($telecom_suppliers_keys);
		$telecom_companies_number_keys=count($telecom_companies_keys);
		$telecom->remove_from_parent('Customer',$this->id,$type);
		if (($telecom_customer_number_keys+$telecom_contacts_number_keys+$telecom_suppliers_number_keys)==0) {


			if ($this->data['Customer Type']=='Company') {
				$company=new Company($this->data['Customer Company Key']);
				$company_customers_keys=$company->get_parent_keys('Customer');
				unset($company_customers_keys[$this->id]);
				$company_suppliers_keys=$company->get_parent_keys('Supplier');
				$company_customers_number_keys=count($company_customers_keys);
				$company_suppliers_number_keys=count($company_suppliers_keys);
				if (($company_suppliers_number_keys+$company_customers_number_keys)==0) {
					$telecom->remove_from_parent('Company',$company->id);
				}
			}
			$contact=new Contact($this->data['Customer Main Contact Key']);
			$contact_customers_keys=$contact->get_parent_keys('Customer');
			//  print_r($contact_customers_keys);
			unset($contact_customers_keys[$this->id]);
			$contact_suppliers_keys=$contact->get_parent_keys('Supplier');
			$contact_customers_number_keys=count($contact_customers_keys);
			$contact_suppliers_number_keys=count($contact_suppliers_keys);

			//print_r($contact_customers_keys);
			// print_r($contact_suppliers_keys);

			if (($contact_suppliers_number_keys+$contact_customers_number_keys)==0) {
				$telecom->remove_from_parent('Contact',$contact->id);
			}

			$this->updated=true;
			$this->msg=_('Telecom Removed from Customer');;
			$this->new_value='';
			return;
		} else {
			$telecom->delete();
		}

		$this->updated=true;
		$this->msg='';
		$this->new_value='';
		return;



	}

	function delete($note='',$customer_id_prefix='') {
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

		$this->add_history($history_data,$force_save=true);



		$company_keys=array();

		$contact_keys=$this->get_contact_keys();
		$company_keys=$this->get_company_keys();



		$sql=sprintf("delete from `Customer Dimension` where `Customer Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer Correlation` where `Customer A Key`=%d or `Customer B Key`=%s",$this->id,$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer History Bridge` where `Customer Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `List Customer Bridge` where `Customer Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer Ship To Bridge` where `Customer Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Customer Send Post` where `Customer Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Search Full Text Dimension` where `Subject`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Address Bridge` where `Subject Type`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Category Bridge` where `Subject`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Company Bridge` where `Subject Type`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Contact Bridge` where `Subject Type`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Email Bridge` where `Subject Type`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Telecom Bridge` where `Subject Type`='Customer' and `Subject Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Customer Ship To Bridge` where  `Customer Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Customer Send Post` where  `Customer Key`=%d",$this->id);
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

		$sql=sprintf("insert into `Customer Deleted Dimension` value (%d,%d,%s,%s,%s) ",
			$this->id,
			$this->data['Customer Store Key'],
			prepare_mysql($this->display('card',$customer_id_prefix)),
			prepare_mysql($this->editor['Date']),
			prepare_mysql($note,false)
		);


		mysql_query($sql);



		if ($this->data['Customer Type']=='Company') {

			//unset($company_keys[$this->data['Customer Company Key']]);
			//     print_r($company_keys);

			foreach ($company_keys as  $company_key) {
				$company=new Company($company_key);
				$company_customer_keys=$company->get_parent_keys('Customer');
				$company_supplier_keys=$company->get_parent_keys('Supplier');
				$company_hq_keys=$company->get_parent_keys('HQ');
				$company_telecom_keys=$company->get_telecom_keys();

				$company_address_keys=$company->get_address_keys();
				$company_contact_keys=$company->get_parent_keys('Contact');

				unset($company_customer_keys[$this->id]);
				foreach ($contact_keys as $contact_key) {
					unset($company_contact_keys[$contact_key]);
				}
				//  print_r($company_contact_keys);
				//  print_r($company_customer_keys);
				if (count($company_customer_keys)==0 and count($company_supplier_keys)==0 and count($company_contact_keys)==0 and count($company_hq_keys)==0) {
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


	function merge($customer_key,$customer_id_prefix='') {
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





		$sql=sprintf("select `History Key` from `Customer History Bridge` where `Type` in ('Orders','Notes') and `Customer Key`=%d ",$customer_to_merge->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$history_key=$row['History Key'];
			$sql=sprintf("select * from `History Dimension` where `History Key`=%d ",$history_key);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("update `Subject Key`=%d from `History Dimension` where `History Key`=%d and `Subject`='Customer' ",$this->id,$history_key);
				mysql_query($sql);
				$sql=sprintf("update `Direct Object Key`=%d from `History Dimension` where `History Key`=%d and `Direct Object`='Customer' ",$this->id,$history_key);
				mysql_query($sql);
				$sql=sprintf("update `Indirect Object Key`=%d from `History Dimension` where `History Key`=%d and `Indirect Object`='Customer' ",$this->id,$history_key);
				mysql_query($sql);
			}
		}
		$sql=sprintf("update `Customer History Bridge` set `Customer Key`=%d where `Type` in ('Orders','Notes') and `Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Customer History Bridge` set `Customer Key`=%d where `Type` in ('Orders','Notes') and `Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Customer Ship To Bridge` set `Customer Key`=%d where `Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Delivery Note Dimension` set `Delivery Note Customer Key`=%d where `Delivery Note Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Invoice Dimension` set `Invoice Customer Key`=%d where `Invoice Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Order Dimension` set `Order Customer Key`=%d where `Order Customer Key`=%d ",$this->id,$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("update `Order Transaction Fact` set `Customer Key`=%d where `Customer Key`=%d ",$this->id,$customer_to_merge->id);
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
			'History Abstract'=>_('Customer').' '.$customer_to_merge->get_formated_id_link($customer_id_prefix).' '._('merged'),
			'History Details'=>_('Orders Transfered').':'.$customer_to_merge->get('Orders').'<br/>'._('Notes Transfered').':'.$customer_to_merge->get('Notes').'<br/>',
			'Direct Object'=>'Customer',
			'Direct Object Key'=>$customer_to_merge->id,
			'Indirect Object'=>'Customer',
			'Indirect Object Key'=>$this->id,
			'Action'=>'merged',
			'Preposition'=>'to'
		);
		$this->add_customer_history($history_data);


		$customer_to_merge->update_orders();

		$this->update_orders();

		$store=new Store($this->data['Customer Store Key']);
		$store->update_customer_activity_interval();

		$this->update_activity();
		$this->update_is_new();

		$customer_to_merge->delete('',$customer_id_prefix);


		$sql=sprintf("update `Customer Merge Bridge` set `Customer Key`=%d,`Date Merged`=%s where `Customer Key`=%d ",$this->id,prepare_mysql($this->editor['Date']),$customer_to_merge->id);
		$res=mysql_query($sql);

		$sql=sprintf("insert into  `Customer Merge Bridge` values(%d,%d,%s) ",$customer_to_merge->id,$this->id,prepare_mysql($this->editor['Date']));
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
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Fax'    and `Subject Type`='Contact' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
		$telephones=array();
		$result=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telephone= new Telecom($row['Telecom Key']);
			$telephone->set_scope('Contact',$this->id);
			$telephones[]= $telephone;
			$telephone->data['Mobile Is Main']=$row['Is Main'];

		}
		//$this->number_mobiles=count($mobiles);
		return $telephones;
	}

	function update_principal_faxes($fax_key) {


		$main_fax_key=$this->get_principal_fax_key();

		if ($main_fax_key!=$fax_key) {
			$fax=new Telecom($fax_key);
			$fax->editor=$this->editor;
			$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='Contact'   and `Subject Key`=%d  and `Telecom Key`=%d and `Telecom Type`='Fax'  "
				,$this->id
				,$main_fax_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact'  and  `Subject Key`=%d  and `Telecom Key`=%d"
				,$this->id
				,$fax->id
			);
			mysql_query($sql);


			$sql=sprintf("update `Contact Dimension` set  `Contact Main Fax Key`=%d where `Contact Key`=%d",$fax->id,$this->id);
			$this->data['Contact Main Fax Key']=$fax->id;
			mysql_query($sql);
			$this->updated=true;
			$this->new_value=$fax->display('xhtml');

			$this->update_parents_principal_fax_keys();
			$fax->new=$this->new;
			$fax->update_parents();


		}

	}

	function get_principal_telephone_key() {

		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge`   TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`='Telephone'  and   `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'"
			,$this->id );

		//print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_telephone_key=$row['Telecom Key'];
		} else {
			$main_telephone_key=0;
		}

		return $main_telephone_key;
	}

	function get_principal_fax_key() {

		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge`   TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`='Fax'  and   `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'"
			,$this->id );

		//print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_fax_key=$row['Telecom Key'];
		} else {
			$main_fax_key=0;
		}

		return $main_fax_key;
	}

	function update_parents_principal_telephone_keys() {
		$telephone_key=$this->data['Contact Main Telephone Key'];
		if (!$telephone_key)
			return;
		//$parents=array('Company','Customer','Supplier');
		$parents=array('Customer');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
			//   print "$sql\n";

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}
				/*
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                elseif($parent=='Company') {
                    $parent_object=new Company($row['Parent Key']);
                    $parent_label=_('Company');
                }
                */
				$parent_telecoms=$parent_object->get_telecom_keys();

				if (!array_key_exists($telephone_key,$parent_telecoms)) {
					$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`,`Is Active`) values (%d,'$parent',%d,'No','Yes')  "
						,$telephone_key
						,$parent_object->id
					);
					mysql_query($sql);
				}
				//print "$sql\n";

				$old_principal_mobile_key=$parent_object->data[$parent.' Main Telephone Key'];
				if ($old_principal_mobile_key!=$telephone_key) {

					$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='Telephone'  ",
						$parent_object->id

					);
					mysql_query($sql);
					$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
						$parent_object->id
						,$telephone_key
					);
					mysql_query($sql);
					$sql=sprintf("update `$parent Dimension` set `$parent Main Telephone Key`=%d where `$parent Key`=%d"
						,$telephone_key
						,$parent_object->id
					);
					mysql_query($sql);
					//print "$sql\n";



				}
			}
		}
	}

	function update_parents_principal_fax_keys() {
		$fax_key=$this->data['Contact Main Fax Key'];
		if (!$fax_key)
			return;
		//$parents=array('Company','Customer','Supplier');
		$parents=array('Customer');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
			//   print "$sql\n";

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}
				/*
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                elseif($parent=='Company') {
                    $parent_object=new Company($row['Parent Key']);
                    $parent_label=_('Company');
                }
                */
				$parent_telecoms=$parent_object->get_telecom_keys();

				if (!array_key_exists($fax_key,$parent_telecoms)) {
					$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`,`Is Active`) values (%d,'$parent',%d,'No','Yes')  "
						,$fax_key
						,$parent_object->id
					);
					mysql_query($sql);
				}
				//print "$sql\n";

				$old_principal_mobile_key=$parent_object->data[$parent.' Main FAX Key'];
				if ($old_principal_mobile_key!=$fax_key) {

					$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='Fax'  ",
						$parent_object->id

					);
					mysql_query($sql);
					$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
						$parent_object->id
						,$fax_key
					);
					mysql_query($sql);
					$sql=sprintf("update `$parent Dimension` set `$parent Main FAX Key`=%d where `$parent Key`=%d"
						,$fax_key
						,$parent_object->id
					);
					mysql_query($sql);
					//print "$sql\n";



				}
			}
		}
	}
	function update_principal_telephone($telephone_key) {


		$main_telephone_key=$this->get_principal_telephone_key();

		if ($main_telephone_key!=$telephone_key) {
			$telephone=new Telecom($telephone_key);
			$telephone->editor=$this->editor;
			$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='Contact'   and `Subject Key`=%d  and `Telecom Key`=%d and `Telecom Type`='Telephone'  "
				,$this->id
				,$main_telephone_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact'  and  `Subject Key`=%d  and `Telecom Key`=%d"
				,$this->id
				,$telephone->id
			);
			mysql_query($sql);


			$sql=sprintf("update `Contact Dimension` set  `Contact Main Telephone Key`=%d where `Contact Key`=%d",$telephone->id,$this->id);
			$this->data['Contact Main Telephone Key']=$telephone->id;
			mysql_query($sql);
			$this->updated=true;
			$this->new_value=$telephone->display('xhtml');

			$this->update_parents_principal_telephone_keys();
			$telephone->new=$this->new;
			$telephone->update_parents();


		}

	}

	function badge_state_gold() {

		if ( $this->data['Customer With Orders']=='Yes' and $this->data['Customer Last Order Date']!='' and    strtotime($this->data['Customer Last Order Date'] .' +1 month')>time()) {
			return true;
		} else {
			return false;
		}


	}
	function badge_caption_gold($state) {

		if ($state) {


			return _('Valid until').": ".date('d-m-Y',strtotime($this->data['Customer Last Order Date'] .' +1 month'));



		} else {

			if ($this->data['Customer Orders'])
				return _('Expired');
			else
				return '';
		}

	}


	function badge_state_freedom() {


		$sql=sprintf("select `Order Transaction Fact Key` from `Order Transaction Fact` where `Customer Key`=%s and `Product Code` like 'free-%%' ",$this->id);





		$res=mysql_query($sql);


		if (mysql_num_rows($res)) {
			return true;
		}

		$sql=sprintf("select `Order Transaction Fact Key` from `Order Transaction Fact` where `Customer Key`=%s and `Product Code` like 'freeinc-%%' ",$this->id);
		$res=mysql_query($sql);
		if (mysql_num_rows($res)) {
			return true;
		}

		$sql=sprintf("select `Order Transaction Fact Key` from `Order Transaction Fact` where `Customer Key`=%s and `Product Code` like 'style-%%' ",$this->id);
		$res=mysql_query($sql);
		if (mysql_num_rows($res)) {
			return true;
		}
		return false;



	}
	function badge_caption_freedom($state) {




		if ($state) {


			return _('Thanks');



		} else {


			return _('Buy').': Free, FreeInc or Style ';

		}

	}


	function badge_state_profile() {
		return false;
	}


	function badge_caption_profile($state) {
		return percentage(0,1,0);
	}


	function badge_state_connected() {
		if ($this->data['Customer Send Newsletter']=='Yes' and $this->data['Customer Send Email Marketing'])
			return true;
		else
			return false;

	}


	function badge_caption_connected($state) {
		return '';
	}


	function badge_state_loyalty() {
		if ($this->data['Customer Orders Invoiced']>=10)
			return true;
		else
			return false;
	}


	function badge_caption_loyalty($state) {

		if (!$state) {
			$to_go=10-$this->data['Customer Orders Invoiced'];
			return number($to_go);
		}else {
			return "";
		}

	}

	function badge_info($badge_key) {

		$badge_data=array(
			1=>array(
				'Badge Image On'=>'art/gold.jpg',
				'Badge Image Off'=>'art/gold_off.jpg',
				'Badge Code'=>'gold',
				'Badge Description'=>'gold Info'
			),
			2=>array(
				'Badge Image On'=>'art/freedom.jpg',
				'Badge Image Off'=>'art/freedom_off.jpg',
				'Badge Code'=>'freedom',
				'Badge Description'=>'Freedom Info'
			),
			3=>array(
				'Badge Image On'=>'art/profile.jpg',
				'Badge Image Off'=>'art/profile_off.jpg',
				'Badge Code'=>'profile',
				'Badge Description'=>'Profile Info'
			),
			4=>array(
				'Badge Image On'=>'art/connected.jpg',
				'Badge Image Off'=>'art/connected_off.jpg',
				'Badge Code'=>'connected',
				'Badge Description'=>'Conencted Info'
			),
			5=>array(
				'Badge Image On'=>'art/loyalty.jpg',
				'Badge Image Off'=>'art/loyalty_off.jpg',
				'Badge Code'=>'loyalty',
				'Badge Description'=>'Loyality Info'
			)

		);
		return $badge_data[$badge_key]['Badge Description'];
	}


	function display_badge($badge_key) {

		$badge_data=array(
			1=>array(
				'Badge Image On'=>'art/gold.jpg',
				'Badge Image Off'=>'art/gold_off.jpg',
				'Badge Code'=>'gold',
				'Badge Description'=>'gold Info'
			),
			2=>array(
				'Badge Image On'=>'art/freedom.jpg',
				'Badge Image Off'=>'art/freedom_off.jpg',
				'Badge Code'=>'freedom',
				'Badge Description'=>'Freedom Info'
			),
			3=>array(
				'Badge Image On'=>'art/profile.jpg',
				'Badge Image Off'=>'art/profile_off.jpg',
				'Badge Code'=>'profile',
				'Badge Description'=>'Profile Info'
			),
			4=>array(
				'Badge Image On'=>'art/connected.jpg',
				'Badge Image Off'=>'art/connected_off.jpg',
				'Badge Code'=>'connected',
				'Badge Description'=>'Conencted Info'
			),
			5=>array(
				'Badge Image On'=>'art/loyalty.jpg',
				'Badge Image Off'=>'art/loyalty_off.jpg',
				'Badge Code'=>'loyalty',
				'Badge Description'=>'Loyality Info'
			)

		);

		$state=false;
		$caption='';
		if ($badge_key==1) {
			$state= $this->badge_state_gold();
			$caption= $this->badge_caption_gold($state);
		} else if ($badge_key==2) {
				$state= $this->badge_state_freedom();
				$caption= $this->badge_caption_freedom($state);
			} else if ($badge_key==3) {
				$state= $this->badge_state_profile();
				$caption= $this->badge_caption_profile($state);
			}else if ($badge_key==4) {
				$state= $this->badge_state_connected();
				$caption= $this->badge_caption_connected($state);
			}else if ($badge_key==5) {
				$state= $this->badge_state_loyalty();
				$caption= $this->badge_caption_loyalty($state);
			}

		if ($state) {
			$html=sprintf('<div style="text-align:center"><img src="%s" alt="" style="width:70px;height:70px"/><div style="font-size:10px;margin-top:5px">%s</div></div>',$badge_data[$badge_key]['Badge Image On'],$caption);
		} else {
			$html=sprintf('<div style="text-align:center"><img src="%s" alt="" style="width:70px;height:70px"/><div style="font-size:10px;margin-top:5px">%s</div></div>',$badge_data[$badge_key]['Badge Image Off'],$caption);

		}


		return $html;
	}


	function get_category_data() {
		$sql=sprintf("select * from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where B.`Subject Key`=%d and B.`Subject`='Customer'", $this->id);
		//print $sql;
		$category_data=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {

			$sql=sprintf("select * from `Category Dimension` where `Category Key`=%d", $row['Category Parent Key']);
			$res=mysql_query($sql);
			$r=mysql_fetch_assoc($res);
			if ($row['Is Category Field Other']=='Yes') {
				$value=$row['Customer Other Note'];
			}
			else {
				$value=$row['Category Label'];
			}
			$category_data[$r['Category Label']]=array('name'=>$row['Category Label'], 'value'=>$value);
		}

		return $category_data;
	}
}
?>
