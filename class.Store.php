<?php
/*
  File: Company.php

  This file contains the Company Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

/* class: Store
   Class to manage the *Company Dimension* table
*/

include_once 'class.DB_Table.php';

class Store extends DB_Table {


	function Store($a1, $a2=false, $a3=false) {

		global $db;

		$this->db=$db;

		$this->table_name='Store';
		$this->ignore_fields=array(
			'Store Key',
			'Store Departments',
			'Store Families',
			'Store For Sale Products',
			'Store In Process Products',
			'Store Not For Sale Products',
			'Store Discontinued Products',
			'Store Unknown Sales State Products',
			'Store Surplus Availability Products',
			'Store Optimal Availability Products',
			'Store Low Availability Products',
			'Store Critical Availability Products',
			'Store Out Of Stock Products',
			'Store Unknown Stock Products',
			'Store Total Invoiced Gross Amount',
			'Store Total Invoiced Discount Amount',
			'Store Total Invoiced Amount',
			'Store Total Profit',
			'Store Total Quantity Ordered',
			'Store Total Quantity Invoiced',
			'Store Total Quantity Delivere',
			'Store Total Days On Sale',
			'Store Total Days Available',
			'Store 1 Year Acc Invoiced Gross Amount',
			'Store 1 Year Acc Invoiced Discount Amount',
			'Store 1 Year Acc Invoiced Amount',
			'Store 1 Year Acc Profit',
			'Store 1 Year Acc Quantity Ordered',
			'Store 1 Year Acc Quantity Invoiced',
			'Store 1 Year Acc Quantity Delivere',
			'Store 1 Year Acc Days On Sale',
			'Store 1 Year Acc Days Available',
			'Store 1 Quarter Acc Invoiced Gross Amount',
			'Store 1 Quarter Acc Invoiced Discount Amount',
			'Store 1 Quarter Acc Invoiced Amount',
			'Store 1 Quarter Acc Profit',
			'Store 1 Quarter Acc Quantity Ordered',
			'Store 1 Quarter Acc Quantity Invoiced',
			'Store 1 Quarter Acc Quantity Delivere',
			'Store 1 Quarter Acc Days On Sale',
			'Store 1 Quarter Acc Days Available',
			'Store 1 Month Acc Invoiced Gross Amount',
			'Store 1 Month Acc Invoiced Discount Amount',
			'Store 1 Month Acc Invoiced Amount',
			'Store 1 Month Acc Profit',
			'Store 1 Month Acc Quantity Ordered',
			'Store 1 Month Acc Quantity Invoiced',
			'Store 1 Month Acc Quantity Delivere',
			'Store 1 Month Acc Days On Sale',
			'Store 1 Month Acc Days Available',
			'Store 1 Week Acc Invoiced Gross Amount',
			'Store 1 Week Acc Invoiced Discount Amount',
			'Store 1 Week Acc Invoiced Amount',
			'Store 1 Week Acc Profit',
			'Store 1 Week Acc Quantity Ordered',
			'Store 1 Week Acc Quantity Invoiced',
			'Store 1 Week Acc Quantity Delivere',
			'Store 1 Week Acc Days On Sale',
			'Store 1 Week Acc Days Available',
			'Store Total Quantity Delivered',
			'Store 1 Year Acc Quantity Delivered',
			'Store 1 Month Acc Quantity Delivered',
			'Store 1 Quarter Acc Quantity Delivered',
			'Store 1 Week Acc Quantity Delivered'


		);
		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}
		elseif ($a1=='find') {
			$this->find($a2, $a3);

		}
		else
			$this->get_data($a1, $a2);

	}





	function get_data($tipo, $tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Store Dimension` where `Store Key`=%d", $tag);
		elseif ($tipo=='code')
			$sql=sprintf("select * from `Store Dimension` where `Store Code`=%s", prepare_mysql($tag));
		else
			return;

		if ($this->data = $this->db->query($sql)->fetch()) {

			$this->id=$this->data['Store Key'];
			$this->code=$this->data['Store Code'];
		}


	}


	function load_acc_data() {
		$sql=sprintf("select * from `Store Data Dimension` where `Store Key`=%d", $this->id);



		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				foreach ($row as $key=>$value) {
					$this->data[$key]=$value;
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function find($raw_data, $options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;

		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}
		if (preg_match('/update/i', $options)) {
			$update='update';
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data))
				$data[$key]=_trim($value);
		}

		//    print_r($raw_data);

		if ($data['Store Code']=='' ) {
			$this->error=true;
			$this->msg='Store code empty';
			return;
		}

		if ($data['Store Name']=='')
			$data['Store Name']=$data['Store Code'];


		$sql=sprintf("select `Store Key` from `Store Dimension` where `Store Code`=%s  "
			, prepare_mysql($data['Store Code'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Store Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Store Code';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		$sql=sprintf("select `Store Key` from `Store Dimension` where `Store Name`=%s  "
			, prepare_mysql($data['Store Name'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Store Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Store Name';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}



	}


	function get($key='') {



		if (!$this->id) {
			return '';
		}


		if (isset($this->data[$key]))
			return $this->data[$key];






		switch ($key) {

		case('Currency Code'):
			include_once 'utils/natural_language.php';
			return currency_label($this->data['Store Currency Code'],$this->db);
			break;

		case('Currency Symbol'):
			include_once 'utils/natural_language.php';
			return currency_symbol($this->data['Store Currency Code']);
			break;

		case("Sticky Note"):
			return nl2br($this->data['Store Sticky Note']);
			break;
		case('Contacts'):
		case('Active Contacts'):
		case('New Contacts'):
		case('Lost Contacts'):
		case('Losing Contacts'):
		case('Contacts With Orders'):
		case('Active Contacts With Orders'):
		case('New Contacts With Orders'):
		case('Lost Contacts With Orders'):
		case('Losing Contacts With Orders'):
			return number($this->data['Store '.$key]);
		case('Potential Customers'):
			return number($this->data['Store Active Contacts']-$this->data['Store Active Contacts With Orders']);
		case('Total Users'):
			return number($this->data['Store Total Users']);
		case('All To Pay Invoices'):
			return $this->data['Store Total Acc Invoices']-$this->data['Store Paid Invoices']-$this->data['Store Paid Refunds'];
		case('All Paid Invoices'):
			return $this->data['Store Paid Invoices']-$this->data['Store Paid Refunds'];
		case('code'):
			return $this->data['Store Code'];
			break;
		case('type'):
			return $this->data['Store Type'];
			break;
		case('Total Products'):
			return $this->data['Store For Sale Products']+$this->data['Store In Process Products']+$this->data['Store Not For Sale Products']+$this->data['Store Discontinued Products']+$this->data['Store Unknown Sales State Products'];
			break;
		case('For Sale Products'):
			return number($this->data['Store For Sale Products']);
			break;
		case('For Public Sale Products'):
			return number($this->data['Store For Public Sale Products']);
			break;
		case('Families'):
			return number($this->data['Store Families']);
			break;
		case('Departments'):
			return number($this->data['Store Departments']);
			break;
		case('Percentage Active Contacts'):
			return percentage($this->data['Store Active Contacts'], $this->data['Store Contacts']);
		case('Percentage Total With Orders'):
			return percentage($this->data['Store Contacts With Orders'], $this->data['Store Contacts']);



		}



		if (preg_match('/^(Total|1).*(Amount|Profit)$/', $key)) {

			$amount='Store '.$key;

			return money($this->data[$amount]);
		}
		if (preg_match('/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Customers|Customers Contacts)$/', $key) or preg_match('/^(Active Customers)$/', $key)) {

			$amount='Store '.$key;

			return number($this->data[$amount]);
		}
		if (preg_match('/^Delivery Notes For (Orders|Replacements|Shortages|Samples|Donations)$/', $key)) {

			$amount='Store '.$key;

			return number($this->data[$amount]);
		}

		if (preg_match('/(Orders|Delivery Notes|Invoices) Acc$/', $key)) {

			$amount='Store '.$key;

			return number($this->data[$amount]);
		}elseif (preg_match('/(Orders|Delivery Notes|Invoices|Refunds|Orders In Process)$/', $key)) {

			$amount='Store '.$key;

			return number($this->data[$amount]);
		}


		if (array_key_exists($key, $this->data))
			return $this->data[$key];

		if (array_key_exists('Store '.$key, $this->data))
			return $this->data['Store '.$key];



	}


	function delete() {
		$this->deleted=false;
		$this->update_product_data();

		if ($this->data['Store Contacts']==0) {

			$sql=sprintf("select `Category Key` from `Category Dimension where `Category Store Key`=%d", $this->id);

			include_once 'class.Category.php';
			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$category=new Category($row['Category Key']);
					$category->delete();
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}






			$sql=sprintf("delete from `Store Dimension` where `Store Key`=%d", $this->id);
			$this->db->exec($sql);
			$this->deleted=true;
			$sql=sprintf("delete from `User Right Scope Bridge` where `Scope`='Store' and `Scope Key`=%d ", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Store Default Currency` where `Store Key`=%d ", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Store Data Dimension` where `Store Key`=%d ", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Invoice Category Dimension` where `Invoice Category Store Key`=%d ", $this->id);
			$this->db->exec($sql);
			$sql=sprintf("delete from `Category Dimension` where `Category Store Key`=%d ", $this->id);
			$this->db->exec($sql);





			$history_key=$this->add_history(array(
					'Action'=>'deleted',
					'History Abstract'=>sprintf(_('Store %d deleted'), $this->data['Store Name']),
					'History Details'=>''
				), true);

			include_once 'class.Account.php';

			$hq=new Account();
			$hq->add_account_history($history_key);



			$this->deleted=true;
		} else {
			$this->msg=_('Store can not be deleted because it has contacts');

		}
	}








	function update_children_data() {
		$this->update_product_data();

	}





	function update_field_switcher($field, $value, $options='', $metadata='') {


		switch ($field) {
		case('Store Sticky Note'):
			$this->update_field_switcher('Sticky Note', $value);
			break;
		case('Sticky Note'):
			$this->update_field('Store '.$field, $value, 'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;

		case('Store Code'):
		case('Store Name'):

			if ($value=='') {
				$this->error=true;
				$this->msg=_("Value can't be empty");
			}
			$this->update_field($field, $value, $options);
			break;


		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field, $value, $options);
				}
			}

		}


	}


	function create($data) {



		$this->new=false;
		$basedata=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $basedata))
				$basedata[$key]=_trim($value);
		}

		$keys='(';
		$values='values (';
		foreach ($basedata as $key=>$value) {
			$keys.="`$key`,";
			if (preg_match('/Store Email|Store Telephone|Store Telephone|Slogan|URL|Fax|Sticky Note|Store VAT Number/i', $key))
				$values.=prepare_mysql($value, false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Store Dimension` %s %s", $keys, $values);

		$sql="insert into `Store Dimension` $keys  $values";

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();



			$this->msg=_("Store Added");
			$this->get_data('id', $this->id);
			$this->new=true;

			if ( is_numeric($this->editor['User Key']) and $this->editor['User Key']>1) {

				$sql=sprintf("insert into `User Right Scope Bridge` values(%d,'Store',%d)",
					$this->editor['User Key'],
					$this->id
				);
				$this->db->exec($sql);

			}

			$sql="insert into `Store Default Currency` (`Store Key`) values(".$this->id.");";
			$this->db->exec($sql);

			$sql="insert into `Store Data Currency` (`Store Key`) values(".$this->id.");";
			$this->db->exec($sql);

			/*

			$dept_data=array(
				'Product Department Code'=>'ND_'.$this->data['Store Code'],
				'Product Department Name'=>_('Products without department'),
				'Product Department Store Key'=>$this->id,
				'Product Department Sales Type'=>'Not for Sale',
				'editor'=>$this->editor
			);

			$dept_no_dept=new Department('find', $dept_data, 'create');
			$this->data['Store No Products Department Key']=$dept_no_dept->id;



			$fam_data=array(
				'Product Family Code'=>'PND_'.$this->data['Store Code'],
				'Product Family Name'=>_('Products without family'),
				'Product Family Main Department Key'=>$dept_no_dept->id,
				'Product Family Store Key'=>$this->id,
				'Product Family Special Characteristic'=>'None',
				'Product Family Sales Type'=>'Not for Sale',
				'Product Family Availability'=>'No Applicable',
				'editor'=>$this->editor
			);

			$fam_no_fam=new Family('find', $fam_data, 'create');
			$this->data['Store No Products Family Key']=$fam_no_fam->id;



			$sql=sprintf("update `Store Dimension` set `Store No Products Department Key`=%d ,`Store No Products Family Key`=%d where `Store Key`=%d",
				$dept_no_dept->id,
				$fam_no_fam->id,
				$this->id

			);

			mysql_query($sql);
*/

			/*

			$sql=sprintf("select `SR Category Key` from `Account Dimension` ");
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$parent_category_key=$row['SR Category Key'];

			}

			if ($parent_category_key) {
				$this->create_sr_category($parent_category_key);

			}
*/



			$history_data=array(
				'History Abstract'=>sprintf(_('Store %s (%s) created'), $this->data['Store Name'], $this->data['Store Code']),
				'History Details'=>'',
				'Action'=>'created'
			);

			$history_key=$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());


			include_once 'class.Account.php';
			$account=new Account();
			$account->add_account_history($history_key);

			return;
		} else {
			print $sql;
			exit;
			$this->msg=_("Error can not create store");

		}

	}


	function create_sr_category($parent_category_key, $suffix='') {




		$parent_category=new Category($parent_category_key);
		if (!$parent_category->id)return;

		$data=array('Category Store Key'=>$this->id, 'Category Code'=>$this->data['Store Code'].($suffix!=''?'.'.$suffix:''), 'Category Subject'=>'Invoice', 'Category Function'=>'if(true)');
		$category=$parent_category->create_children($data);
		if (!$category->new) {
			if ($suffix=='') {
				$this->sr_category_suffix=2;
			}else {
				$this->sr_category_suffix++;
			}
			$this->create_sr_category($parent_category_key, $this->sr_category_suffix);


		}




	}


	function update_product_data() {

		$availability='No Applicable';
		$sales_type='No Applicable';
		$in_process=0;
		$public_sale=0;
		$private_sale=0;
		$discontinued=0;
		$not_for_sale=0;
		$sale_unknown=0;
		$availability_optimal=0;
		$availability_low=0;
		$availability_critical=0;
		$availability_outofstock=0;
		$availability_unknown=0;
		$availability_surplus=0;
		$new=0;




		$sql=sprintf("select sum(if(`Product Stage`='New',1,0)) as new,sum(if(`Product Stage`='In process',1,0)) as in_process,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown,
		sum(if(`Product Main Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Main Type`='NoSale',1,0)) as not_for_sale,
		sum(if(`Product Main Type`='Sale',1,0)) as public_sale,sum(if(`Product Main Type`='Private',1,0)) as private_sale,
		sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Store Key`=%d", $this->id);

		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$new=$row['new'];

			$in_process=$row['in_process'];
			$public_sale=$row['public_sale'];
			$private_sale=$row['private_sale'];
			$discontinued=$row['discontinued'];
			$not_for_sale=$row['not_for_sale'];
			$sale_unknown=$row['sale_unknown'];
			$availability_optimal=$row['availability_optimal'];
			$availability_low=$row['availability_low'];
			$availability_critical=$row['availability_critical'];
			$availability_outofstock=$row['availability_outofstock'];
			$availability_unknown=$row['availability_unknown'];
			$availability_surplus=$row['availability_surplus'];
		}

		$sql=sprintf("update `Store Dimension` set `Store In Process Products`=%d,`Store For Public Sale Products`=%d, `Store For Private Sale Products`=%d ,`Store Discontinued Products`=%d ,`Store Not For Sale Products`=%d ,`Store Unknown Sales State Products`=%d, `Store Optimal Availability Products`=%d , `Store Low Availability Products`=%d ,`Store Critical Availability Products`=%d ,`Store Out Of Stock Products`=%d,`Store Unknown Stock Products`=%d ,`Store Surplus Availability Products`=%d ,`Store New Products`=%d where `Store Key`=%d  ",
			$in_process,
			$public_sale,
			$private_sale,
			$discontinued,
			$not_for_sale,
			$sale_unknown,
			$availability_optimal,
			$availability_low,
			$availability_critical,
			$availability_outofstock,
			$availability_unknown,
			$availability_surplus,
			$new,
			$this->id
		);
		// print "$sql\n";
		mysql_query($sql);





	}


	function update_customers_data() {

		$this->data['Store Contacts']=0;
		$this->data['Store New Contacts']=0;
		$this->data['Store Contacts With Orders']=0;
		$this->data['Store Active Contacts']=0;
		$this->data['Store Losing Contacts']=0;
		$this->data['Store Lost Contacts']=0;
		$this->data['Store New Contacts With Orders']=0;
		$this->data['Store Active Contacts With Orders']=0;
		$this->data['Store Losing Contacts With Orders']=0;
		$this->data['Store Lost Contacts With Orders']=0;
		$this->data['Store Contacts Who Visit Website']=0;



		$sql=sprintf("select count(*) as num from  `Customer Dimension`    where   `Customer Number Web Logins`>0  and `Customer Store Key`=%d  ", $this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Contacts Who Visit Website']=$row['num'];

			}else {
				$this->data['Store Contacts Who Visit Website']=0;

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("select count(*) as num ,sum(IF(`Customer New`='Yes',1,0)) as new,  sum(IF(`Customer Type by Activity`='Active'   ,1,0)) as active, sum(IF(`Customer Type by Activity`='Losing',1,0)) as losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d ", $this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Contacts']=$row['num'];
				$this->data['Store New Contacts']=$row['new'];
				$this->data['Store Active Contacts']=$row['active'];
				$this->data['Store Losing Contacts']=$row['losing'];
				$this->data['Store Lost Contacts']=$row['lost'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$sql=sprintf("select count(*) as num ,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) as active, sum(IF(`Customer Type by Activity`='Losing',1,0)) as losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d and `Customer With Orders`='Yes'", $this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Contacts With Orders']=$row['num'];
				$this->data['Store New Contacts With Orders']=$row['new'];
				$this->data['Store Active Contacts With Orders']=$row['active'];
				$this->data['Store Losing Contacts With Orders']=$row['losing'];
				$this->data['Store Lost Contacts With Orders']=$row['lost'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("update `Store Dimension` set
                     `Store Contacts`=%d,
                     `Store New Contacts`=%d,
                     `Store Active Contacts`=%d ,
                     `Store Losing Contacts`=%d ,
                     `Store Lost Contacts`=%d ,

                     `Store Contacts With Orders`=%d,
                     `Store New Contacts With Orders`=%d,
                     `Store Active Contacts With Orders`=%d,
                     `Store Losing Contacts With Orders`=%d,
                     `Store Lost Contacts With Orders`=%d,
                     `Store Contacts Who Visit Website`=%d
                     where `Store Key`=%d  ",
			$this->data['Store Contacts'],
			$this->data['Store New Contacts'],
			$this->data['Store Active Contacts'],
			$this->data['Store Losing Contacts'],
			$this->data['Store Lost Contacts'],

			$this->data['Store Contacts With Orders'],
			$this->data['Store New Contacts With Orders'],
			$this->data['Store Active Contacts With Orders'],
			$this->data['Store Losing Contacts With Orders'],
			$this->data['Store Lost Contacts With Orders'],
			$this->data['Store Contacts Who Visit Website'],

			$this->id
		);

		$this->db->exec($sql);

	}








	function update_orders() {

		$this->data['Store Total Acc Orders']=0;
		$this->data['Store Dispatched Orders']=0;
		$this->data['Store Cancelled Orders']=0;
		$this->data['Store Orders In Process']=0;
		$this->data['Store Unknown Orders']=0;
		$this->data['Store Suspended Orders']=0;

		$this->data['Store Total Acc Invoices']=0;
		$this->data['Store Invoices']=0;
		$this->data['Store Refunds']=0;
		$this->data['Store Paid Invoices']=0;
		$this->data['Store Paid Refunds']=0;
		$this->data['Store Partially Paid Invoices']=0;
		$this->data['Store Partially Paid Refunds']=0;

		$this->data['Store Total Acc Delivery Notes']=0;
		$this->data['Store Ready to Pick Delivery Notes']=0;
		$this->data['Store Picking Delivery Notes']=0;
		$this->data['Store Packing Delivery Notes']=0;
		$this->data['Store Ready to Dispatch Delivery Notes']=0;
		$this->data['Store Dispatched Delivery Notes']=0;
		$this->data['Store Cancelled Delivery Notes']=0;


		$this->data['Store Delivery Notes For Orders']=0;
		$this->data['Store Delivery Notes For Replacements']=0;
		$this->data['Store Delivery Notes For Samples']=0;
		$this->data['Store Delivery Notes For Donations']=0;
		$this->data['Store Delivery Notes For Shortages']=0;




		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Total Acc Orders']=$row['Store Total Acc Orders'];
				$this->data['Store Dispatched Orders']=$row['Store Dispatched Orders'];
				$this->data['Store Cancelled Orders']=$row['Store Cancelled Orders'];
				$this->data['Store Unknown Orders']=$row['Store Unknown Orders'];
				$this->data['Store Suspended Orders']=$row['Store Suspended Orders'];

				$this->data['Store Orders In Process']=  $this->data['Store Total Acc Orders']- $this->data['Store Dispatched Orders']-$this->data['Store Cancelled Orders']-$this->data['Store Unknown Orders']-$this->data['Store Suspended Orders'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$sql="select count(*) as `Store Total Invoices`,sum(IF(`Invoice Type`='Invoice',1,0 )) as `Store Invoices`,sum(IF(`Invoice Type`!='Invoice',1,0 )) as `Store Refunds` ,sum(IF(`Invoice Paid`='Yes' AND `Invoice Type`='Invoice',1,0 )) as `Store Paid Invoices`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Type`='Invoice',1,0 )) as `Store Partially Paid Invoices`,sum(IF(`Invoice Paid`='Yes' AND `Invoice Type`!='Invoice',1,0 )) as `Store Paid Refunds`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Type`!='Invoice',1,0 )) as `Store Partially Paid Refunds` from `Invoice Dimension`   where `Invoice Store Key`=".$this->id;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Total Acc Invoices']=$row['Store Total Invoices'];
				$this->data['Store Invoices']=$row['Store Invoices'];
				$this->data['Store Paid Invoices']=$row['Store Paid Invoices'];
				$this->data['Store Partially Paid Invoices']=$row['Store Partially Paid Invoices'];
				$this->data['Store Refunds']=$row['Store Refunds'];
				$this->data['Store Paid Refunds']=$row['Store Paid Refunds'];
				$this->data['Store Partially Paid Refunds']=$row['Store Partially Paid Refunds'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql="select count(*) as `Store Total Delivery Notes`,
             sum(IF(`Delivery Note State`='Cancelled'  or `Delivery Note State`='Cancelled to Restock' ,1,0 )) as `Store Returned Delivery Notes`,
             sum(IF(`Delivery Note State`='Ready to be Picked' ,1,0 )) as `Store Ready to Pick Delivery Notes`,
             sum(IF(`Delivery Note State`='Picking & Packing' or `Delivery Note State`='Picking' or `Delivery Note State`='Picker Assigned' or `Delivery Note State`='' ,1,0 )) as `Store Picking Delivery Notes`,
             sum(IF(`Delivery Note State`='Packing' or `Delivery Note State`='Packer Assigned' or `Delivery Note State`='Picked' ,1,0 )) as `Store Packing Delivery Notes`,
             sum(IF(`Delivery Note State`='Approved' or `Delivery Note State`='Packed' ,1,0 )) as `Store Ready to Dispatch Delivery Notes`,
             sum(IF(`Delivery Note State`='Dispatched' ,1,0 )) as `Store Dispatched Delivery Notes`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' or `Delivery Note Type`='Replacement' ,1,0 )) as `Store Delivery Notes For Replacements`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' or `Delivery Note Type`='Shortages' ,1,0 )) as `Store Delivery Notes For Shortages`,
             sum(IF(`Delivery Note Type`='Sample' ,1,0 )) as `Store Delivery Notes For Samples`,
             sum(IF(`Delivery Note Type`='Donation' ,1,0 )) as `Store Delivery Notes For Donations`,
             sum(IF(`Delivery Note Type`='Order' ,1,0 )) as `Store Delivery Notes For Orders`
             from `Delivery Note Dimension`   where `Delivery Note Store Key`=".$this->id;

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data['Store Total Acc Delivery Notes']=$row['Store Total Delivery Notes'];
				$this->data['Store Ready to Pick Delivery Notes']=$row['Store Ready to Pick Delivery Notes'];
				$this->data['Store Picking Delivery Notes']=$row['Store Picking Delivery Notes'];
				$this->data['Store Packing Delivery Notes']=$row['Store Packing Delivery Notes'];
				$this->data['Store Ready to Dispatch Delivery Notes']=$row['Store Ready to Dispatch Delivery Notes'];
				$this->data['Store Dispatched Delivery Notes']=$row['Store Dispatched Delivery Notes'];
				$this->data['Store Returned Delivery Notes']=$row['Store Returned Delivery Notes'];
				$this->data['Store Delivery Notes For Replacements']=$row['Store Delivery Notes For Replacements'];
				$this->data['Store Delivery Notes For Shortages']=$row['Store Delivery Notes For Shortages'];
				$this->data['Store Delivery Notes For Samples']=$row['Store Delivery Notes For Samples'];
				$this->data['Store Delivery Notes For Donations']=$row['Store Delivery Notes For Donations'];
				$this->data['Store Delivery Notes For Orders']=$row['Store Delivery Notes For Orders'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("update `Store Dimension` set `Store Suspended Orders`=%d,`Store Dispatched Orders`=%d,`Store Cancelled Orders`=%d,`Store Orders In Process`=%d,`Store Unknown Orders`=%d
                    ,`Store Invoices`=%d ,`Store Refunds`=%d ,`Store Paid Invoices`=%d ,`Store Paid Refunds`=%d ,`Store Partially Paid Invoices`=%d ,`Store Partially Paid Refunds`=%d
                     ,`Store Ready to Pick Delivery Notes`=%d,`Store Picking Delivery Notes`=%d,`Store Packing Delivery Notes`=%d,`Store Ready to Dispatch Delivery Notes`=%d,`Store Dispatched Delivery Notes`=%d,`Store Returned Delivery Notes`=%d
                     ,`Store Delivery Notes For Replacements`=%d,`Store Delivery Notes For Shortages`=%d,`Store Delivery Notes For Samples`=%d,`Store Delivery Notes For Donations`=%d,`Store Delivery Notes For Orders`=%d
                     where `Store Key`=%d",
			$this->data['Store Suspended Orders'],
			$this->data['Store Dispatched Orders'],
			$this->data['Store Cancelled Orders'],
			$this->data['Store Orders In Process'],
			$this->data['Store Unknown Orders'],
			$this->data['Store Invoices'],
			$this->data['Store Refunds'],
			$this->data['Store Paid Invoices'],
			$this->data['Store Paid Refunds'],
			$this->data['Store Partially Paid Invoices'],
			$this->data['Store Partially Paid Refunds'],
			$this->data['Store Ready to Pick Delivery Notes'],
			$this->data['Store Picking Delivery Notes'],
			$this->data['Store Picking Delivery Notes'],
			$this->data['Store Ready to Dispatch Delivery Notes'],
			$this->data['Store Dispatched Delivery Notes'],
			$this->data['Store Returned Delivery Notes'],
			$this->data['Store Delivery Notes For Replacements'],
			$this->data['Store Delivery Notes For Shortages'],
			$this->data['Store Delivery Notes For Samples'],
			$this->data['Store Delivery Notes For Donations'],
			$this->data['Store Delivery Notes For Orders'],
			$this->id
		);
		$this->db->exec($sql);

		$sql=sprintf("update `Store Data Dimension` set `Store Total Acc Orders`=%d,`Store Total Acc Invoices`=%d ,`Store Total Acc Delivery Notes`=%d where `Store Key`=%d",
			$this->data['Store Total Acc Orders'],
			$this->data['Store Total Acc Invoices'],
			$this->data['Store Total Acc Delivery Notes'],
			$this->id
		);
		$this->db->exec($sql);



	}



	function update_up_today_sales() {
		$this->update_sales_from_invoices('Total');
		$this->update_sales_from_invoices('Today');
		$this->update_sales_from_invoices('Week To Day');
		$this->update_sales_from_invoices('Month To Day');
		$this->update_sales_from_invoices('Year To Day');
	}


	function update_last_period_sales() {

		$this->update_sales_from_invoices('Yesterday');
		$this->update_sales_from_invoices('Last Week');
		$this->update_sales_from_invoices('Last Month');
	}


	function update_interval_sales() {
		$this->update_sales_from_invoices('1 Quarter');
		$this->update_sales_from_invoices('3 Year');
		$this->update_sales_from_invoices('1 Year');
		$this->update_sales_from_invoices('6 Month');
		$this->update_sales_from_invoices('1 Month');
		$this->update_sales_from_invoices('10 Day');
		$this->update_sales_from_invoices('1 Week');
	}


	function update_up_today_dispatch_times() {
		$this->update_dispatch_times('Total');
		$this->update_dispatch_times('Today');
		$this->update_dispatch_times('Week To Day');
		$this->update_dispatch_times('Month To Day');
		$this->update_dispatch_times('Year To Day');
	}


	function update_last_period_dispatch_times() {

		$this->update_dispatch_times('Yesterday');
		$this->update_dispatch_times('Last Week');
		$this->update_dispatch_times('Last Month');
	}


	function update_interval_dispatch_times() {
		$this->update_dispatch_times('3 Year');
		$this->update_dispatch_times('1 Year');
		$this->update_dispatch_times('6 Month');
		$this->update_dispatch_times('1 Quarter');
		$this->update_dispatch_times('1 Month');
		$this->update_dispatch_times('10 Day');
		$this->update_dispatch_times('1 Week');
	}


	function get_formatted_dispatch_time($interval) {


		$interval=addslashes($interval);

		return number(($this->data["Store $interval Average Dispatch Time"]/3600));
	}




	function update_dispatch_times($interval) {

		$to_date='';

		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($interval);

		setlocale(LC_ALL, 'en_GB');

		//   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

		$this->data["Store $db_interval Average Dispatch Time"]='';


		$sql=sprintf("select `Order Date`,`Order Dispatched Date`,`Order Current Dispatch State` from `Order Dimension` where `Order Store Key`=%d and `Order Date`>=%s %s" ,
			$this->id,
			prepare_mysql($from_date),
			($to_date?sprintf('and `Order Date`<%s', prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$interval \n$sql\n";
		$number_samples=0;
		$sum_interval=0;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$interval=0;
			if ($row['Order Dispatched Date']!='') {
				$interval=strtotime($row['Order Dispatched Date'])-strtotime($row['Order Date']);

				//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'
			}
			//else if (!in_array($row['Order Current Dispatch State'],array('In Process by Customer','Unknown','Packing','Cancelled','Suspended'))) {
			//  $interval=strtotime(gmdate('Y-m-d H:i:s'))-strtotime($row['Order Date']);
			// }

			if ($interval>0) {
				$sum_interval+=$interval;
				$number_samples++;
			}


		}

		if ($number_samples>0) {
			$this->data["Store $db_interval Average Dispatch Time"]=$sum_interval/$number_samples;

		}
		$sql=sprintf("update `Store Data Dimension` set `Store $db_interval Acc Average Dispatch Time`=%f where `Store Key`=%d "
			, $this->data["Store $db_interval Average Dispatch Time"]

			, $this->id
		);

		mysql_query($sql);


		//print "$sql\n\n";




	}



	function update_sales_from_invoices($interval) {

		$to_date='';

		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb)=calculate_interval_dates($interval);

		setlocale(LC_ALL, 'en_GB');

		//  print "$interval\t\t | $from_date | t\t todate:  $to_date\t\t $from_date_1yb\t\t $to_1yb\n";



		$this->data["Store $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Store $db_interval Acc Invoiced Amount"]=0;
		$this->data["Store $db_interval Acc Invoices"]=0;
		$this->data["Store $db_interval Acc Refunds"]=0;
		$this->data["Store $db_interval Acc Replacements"]=0;
		$this->data["Store $db_interval Acc Delivery Notes"]=0;
		$this->data["Store $db_interval Acc Profit"]=0;
		$this->data["Store DC $db_interval Acc Invoiced Amount"]=0;
		$this->data["Store DC $db_interval Acc Invoiced Discount Amount"]=0;
		$this->data["Store DC $db_interval Acc Profit"]=0;

		$sql=sprintf("select sum(if(`Invoice Type`='Invoice',1,0))  as invoices, sum(if(`Invoice Type`='Refund',1,0))  as refunds,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit from `Invoice Dimension` where `Invoice Store Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Store $db_interval Acc Invoiced Discount Amount"]=$row["discounts"];
			$this->data["Store $db_interval Acc Invoiced Amount"]=$row["net"];
			$this->data["Store $db_interval Acc Invoices"]=$row["invoices"];
			$this->data["Store $db_interval Acc Refunds"]=$row["refunds"];
			$this->data["Store $db_interval Acc Profit"]=$row["profit"];
			$this->data["Store DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
			$this->data["Store DC $db_interval Acc Invoiced Discount Amount"]=$row["dc_discounts"];
			$this->data["Store DC $db_interval Acc Profit"]=$row["dc_profit"];
		}


		$sql=sprintf("select count(*)  as replacements from `Delivery Note Dimension` where `Delivery Note Type` in ('Replacement & Shortages','Replacement','Shortages') and `Delivery Note Store Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and `Delivery Note Date`>%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Delivery Note Date`<%s', prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Store $db_interval Acc Replacements"]=$row["replacements"];
		}

		$sql=sprintf("select count(*)  as delivery_notes from `Delivery Note Dimension` where `Delivery Note Type` in ('Order') and `Delivery Note Store Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and `Delivery Note Date`>%s', prepare_mysql($from_date)):''),
			($to_date?sprintf('and `Delivery Note Date`<%s', prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Store $db_interval Acc Delivery Notes"]=$row["delivery_notes"];
		}


		$sql=sprintf("update `Store Data Dimension` set
                     `Store $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `Store $db_interval Acc Invoiced Amount`=%.2f,
                     `Store $db_interval Acc Invoices`=%d,
                      `Store $db_interval Acc Refunds`=%d,
                       `Store $db_interval Acc Replacements`=%d,
                       `Store $db_interval Acc Delivery Notes`=%d,
                    `Store $db_interval Acc Profit`=%.2f
                     where `Store Key`=%d "
			, $this->data["Store $db_interval Acc Invoiced Discount Amount"]
			, $this->data["Store $db_interval Acc Invoiced Amount"]
			, $this->data["Store $db_interval Acc Invoices"]
			, $this->data["Store $db_interval Acc Refunds"]
			, $this->data["Store $db_interval Acc Replacements"]
			, $this->data["Store $db_interval Acc Delivery Notes"]
			, $this->data["Store $db_interval Acc Profit"]
			, $this->id
		);

		mysql_query($sql);

		//print "$sql\n";

		$sql=sprintf("update `Store Default Currency` set
                     `Store DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `Store DC $db_interval Acc Invoiced Amount`=%.2f,
                     `Store DC $db_interval Acc Profit`=%.2f
                     where `Store Key`=%d "
			, $this->data["Store DC $db_interval Acc Invoiced Discount Amount"]
			, $this->data["Store DC $db_interval Acc Invoiced Amount"]
			, $this->data["Store DC $db_interval Acc Profit"]
			, $this->id
		);
		//print "$sql\n";
		mysql_query($sql);


		if ($from_date_1yb) {
			$this->data["Store $db_interval Acc 1YB Invoices"]=0;
			$this->data["Store $db_interval Acc 1YB Refunds"]=0;

			$this->data["Store $db_interval Acc 1YB Replacements"]=0;
			$this->data["Store $db_interval Acc 1YB Delivery Notes"]=0;

			$this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]=0;
			$this->data["Store $db_interval Acc 1YB Invoiced Amount"]=0;
			$this->data["Store $db_interval Acc 1YB Profit"]=0;
			$this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]=0;
			$this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]=0;
			$this->data["Store DC $db_interval Acc 1YB Profit"]=0;

			$sql=sprintf("select  sum(if(`Invoice Type`='Invoice',1,0)) as invoices,sum(if(`Invoice Type`='Refund',1,0)) as refunds,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Date`>%s and `Invoice Date`<%s" ,
				$this->id,
				prepare_mysql($from_date_1yb),
				prepare_mysql($to_1yb)
			);

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
				$this->data["Store $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
				$this->data["Store $db_interval Acc 1YB Invoices"]=$row["invoices"];
				$this->data["Store $db_interval Acc 1YB Refunds"]=$row["refunds"];
				$this->data["Store $db_interval Acc 1YB Profit"]=$row["profit"];
				$this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]=$row["dc_net"];
				$this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]=$row["dc_discounts"];
				$this->data["Store DC $db_interval Acc 1YB Profit"]=$row["dc_profit"];
			}


			$sql=sprintf("select count(*)  as replacements from `Delivery Note Dimension` where `Delivery Note Type` in ('Replacement & Shortages','Replacement','Shortages') and `Delivery Note Store Key`=%d  and `Delivery Note Date`>%s and `Delivery Note Date`<%s" ,
				$this->id,
				prepare_mysql($from_date_1yb),
				prepare_mysql($to_1yb)
			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Store $db_interval Acc 1YB Replacements"]=$row["replacements"];
			}

			$sql=sprintf("select count(*)  as delivery_notes from `Delivery Note Dimension` where `Delivery Note Type` in ('Order') and `Delivery Note Store Key`=%d  and `Delivery Note Date`>%s and `Delivery Note Date`<%s" ,
				$this->id,
				prepare_mysql($from_date_1yb),
				prepare_mysql($to_1yb)

			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Store $db_interval Acc 1YB Delivery Notes"]=$row["delivery_notes"];
			}



			$sql=sprintf("update `Store Data Dimension` set
                         `Store $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Store $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Store $db_interval Acc 1YB Invoices`=%d,
                           `Store $db_interval Acc 1YB Replacements`=%d,
                             `Store $db_interval Acc 1YB Delivery Notes`=%d,
                         `Store $db_interval Acc 1YB Refunds`=%d,
                        `Store $db_interval Acc 1YB Profit`=%.2f
                         where `Store Key`=%d "
				, $this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]
				, $this->data["Store $db_interval Acc 1YB Invoiced Amount"]
				, $this->data["Store $db_interval Acc 1YB Invoices"]
				, $this->data["Store $db_interval Acc 1YB Replacements"]
				, $this->data["Store $db_interval Acc 1YB Delivery Notes"]
				, $this->data["Store $db_interval Acc 1YB Refunds"]
				, $this->data["Store $db_interval Acc 1YB Profit"]
				, $this->id
			);

			mysql_query($sql);
			//print "$sql\n";
			$sql=sprintf("update `Store Default Currency` set
                         `Store DC $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Store DC $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Store DC $db_interval Acc 1YB Profit`=%.2f
                         where `Store Key`=%d "
				, $this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]
				, $this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]
				, $this->data["Store DC $db_interval Acc 1YB Profit"]
				, $this->id
			);
			// print "$sql\n";
			mysql_query($sql);
		}

		return array(substr($from_date, -19, -9), date("Y-m-d"));

	}


	function get_from_date($period) {
		return $this->update_sales_from_invoices($period);

	}


	function update_customer_activity_interval() {




		$losing_interval=false;


		$sigma_factor=3.2906;//99.9% value assuming normal distribution
		$sql="select count(*) as num,avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>2";
		$result2=mysql_query($sql);
		if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
			if ($row2['num']>30) {
				$this->data['Store Losing Customer Interval']=$row2['a'];
				$this->data['Store Lost Customer Interval']=$this->data['Store Losing Customer Interval']*4.0/3.0;
			}
		}

		if (!$losing_interval) {
			$losing_interval=5259487;
			$lost_interval=7889231;
		}
		$sql=sprintf("update `Store Dimension` set
                     `Store Losing Customer Interval`=%d,
                     `Store Lost Customer Interval`=%d
                     where `Store Key`=%d "
			, $this->data["Store Losing Customer Interval"]
			, $this->data["Store Lost Customer Interval"]

			, $this->id
		);
		//print "$sql\n";
		mysql_query($sql);

	}




	function update_email_campaign_data() {
		$sql=sprintf("select count(*) as email_campaign from `Email Campaign Dimension` where `Email Campaign Store Key`=%d  ", $this->id);

		$res=mysql_query($sql);
		$sites=array();
		while ($row=mysql_fetch_assoc($res)) {
			$email_campaign=$row['email_campaign'];
		}

		$sql=sprintf('update `Store Dimension` set `Store Email Campaigns`=%d where `Store Key`=%d',
			$email_campaign,
			$this->id
		);

	}


	function update_newsletter_data() {

	}


	function update_email_reminder_data() {

	}



	function update_deals_data() {

		$deals=0;

		$sql=sprintf("select count(*) as num from `Deal Dimension` where `Deal Store Key`=%d and `Deal Status`='Active' ", $this->id);
		$res=mysql_query($sql);
		$sites=array();
		if ($row=mysql_fetch_assoc($res)) {
			$deals=$row['num'];
		}

		$sql=sprintf('update `Store Dimension` set `Store Active Deals`=%d where `Store Key`=%d',
			$deals,
			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
	}


	function update_campaings_data() {

		$campaings=0;

		$sql=sprintf("select count(*) as num from `Deal Campaign Dimension` where `Deal Campaign Store Key`=%d and `Deal Campaign Status`='Active' ", $this->id);
		$res=mysql_query($sql);
		$sites=array();
		if ($row=mysql_fetch_assoc($res)) {
			$campaings=$row['num'];
		}

		$sql=sprintf('update `Store Dimension` set `Store Active Deal Campaigns`=%d where `Store Key`=%d',
			$campaings,
			$this->id
		);

		mysql_query($sql);
		//print "$sql\n";
	}


	function create_site($data) {


		$data['Site Store Key']=$this->id;



		if ($data['Site Name']=='')
			$data['Site Name']=$this->data['Store Name'];

		if ($data['Site Code']=='')
			$data['Site Code']=$this->data['Store Code'];

		if (!array_key_exists('Site Contact Telephone', $data) or $data['Site Contact Telephone']=='')
			$data['Site Contact Telephone']=$this->data['Store Telephone'];
		if (!array_key_exists('Site Contact Address', $data) or  $data['Site Contact Address']=='')
			$data['Site Contact Address']=$this->data['Store Address'];

		$data['editor']=$this->editor;

		$site=new Site('new', $data);
		return $site;
	}


	function get_active_sites_keys() {
		$sql=sprintf("select `Site Key` from `Site Dimension` where `Site Store Key`=%d and `Site Active`='Yes' ", $this->id);

		$res=mysql_query($sql);
		$sites=array();
		while ($row=mysql_fetch_assoc($res)) {
			$sites[$row['Site Key']]=$row['Site Key'];
		}
		//print "$sql\n";
		//print_r($sites);
		return $sites;
	}







	function get_email_credentials_data($type='Newsletters') {
		$credentials=array();
		$sql=sprintf("select * from `Email Credentials Dimension` C left join `Email Credentials Store Bridge` SB on (SB.`Email Credentials Key`=C.`Email Credentials Key`) left join `Email Credentials Scope Bridge`  SCB  on (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    where   `Scope`=%s and `Store Key`=%d ",
			prepare_mysql($type),
			$this->id
		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$credentials[$row['Email Credentials Key']]=$row;
		}

		return $credentials;

	}


	function get_number_sites() {
		$number_sites=0;
		$sql=sprintf("select count(*) as number_sites from `Site Dimension` where `Site Store Key`=%d ", $this->id);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_sites=$row['number_sites'];
		}
		return $number_sites;
	}


	function get_sites_data($smarty=false) {
		$data=array();
		$sql=sprintf("select  `Site Key`,`Site URL`,`Site Name` from `Site Dimension` where `Site Store Key`=%d ", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ($smarty) {
				$_row=array();
				foreach ($row as $key=>$value) {
					$_row[str_replace(' ', '', $key)]=$value;
				}

				$data[]=$_row;
			}else {
				$data[]=$row;
			}
		}
		return $data;
	}


	function get_site_keys() {

		$site_keys=array();


		$sql=sprintf("select  `Site Key` from `Site Dimension` where `Site Store Key`=%d ", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$site_keys[$row['Site Key']]=$row['Site Key'];

		}


		return $site_keys;

	}


	function get_site_key() {

		$site_key=0;


		$sql=sprintf("select  `Site Key` from `Site Dimension` where `Site Store Key`=%d ", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$site_key=$row['Site Key'];

		}


		return $site_key;

	}






	function update_number_sites() {
		$current_sites=$this->get_number_sites();
		if ($this->data['Store Websites']!=$current_sites) {

			$this->update_field_switcher('Store Websites', $current_sites);
		}

	}


	function get_formatted_email_credentials($type) {

		$credentials=$this->get_email_credentials_data($type);

		$formatted_credentials='';
		foreach ($credentials as $credential) {
			$formatted_credentials.=','.$credential['Email Address'];
		}

		$formatted_credentials=preg_replace('/^,/', '', $formatted_credentials);
		return $formatted_credentials;


	}


	//$type='Newsletters'
	function get_email_credential_key($type='Newsletters') {

		$sql=sprintf("select C.`Email Credentials Key` from `Email Credentials Dimension` C left join `Email Credentials Store Bridge` SB on (SB.`Email Credentials Key`=C.`Email Credentials Key`) left join `Email Credentials Scope Bridge`  SCB  on (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    where   `Scope`=%s and `Store Key`=%d ",
			prepare_mysql($type),
			$this->id
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Email Credentials Key'];
		} else {

			return false;
		}


	}


	function get_credential_type() {
		include_once 'class.EmailCredentials.php';
		$keys=$this->get_email_credential_key();
		$email_credential = new EmailCredentials($keys);
		if ($email_credential->id) {
			return $email_credential->data['Email Provider'];
		}
		else
			return false;
	}


	function associate_email_credentials($email_credentials_key, $scope='Newsletters') {

		if (!$email_credentials_key) {
			$this->error=true;
			return;
		}

		$current_email_credentials_key=$this->get_email_credential_key();
		if ($email_credentials_key==$current_email_credentials_key) {
			return;
		}




		$sql=sprintf("delete from `Email Credentials Store Bridge` where `Store Key`=%d w ",
			$this->id);
		mysql_query($sql);
		$this->db->exec($sql);

		$sql=sprintf("delete from `Email Credentials Scope Bridge` where `Scope`='%s'", $scope);
		$this->db->exec($sql);

		include_once 'class.EmailCredentials.php';

		$old_email_credentials=new EmailCredentials($current_email_credentials_key);
		$old_email_credentials->delete();

		$sql=sprintf("insert into `Email Credentials Store Bridge` values (%d,%d)", $email_credentials_key, $this->id);
		$this->db->exec($sql);

		$sql=sprintf("insert into `Email Credentials Scope Bridge` values (%d, '%s')", $email_credentials_key, $scope);
		$this->db->exec($sql);




		$this->updated=true;
		$this->msg='Updated';
		$this->newvalue=$email_credentials_key;


	}


	function post_add_history($history_key, $type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Store History Bridge` (`Store Key`,`History Key`,`Type`) values (%d,%d,%s)",
			$this->id,
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);

	}


	function add_campaign($data) {
		$data['Deal Campaign Store Key']=$this->id;
		$campaign=new DealCampaign('find create', $data);

		return $campaign;

	}


	function get_valid_to() {
		/*
	To do discintinued Store
	if($this->data['Store Record Type']=='Discontinued'){
			return $this->data['Store Valid To'];
		}else{
			return gmdate("Y-m-d H:i:s");
		}

		*/
		return gmdate("Y-m-d H:i:s");

	}


	function update_sales_averages() {

		include_once 'common_stat_functions.php';

		$sql=sprintf("select sum(`Sales`) as sales,sum(`Availability`) as availability  from `Order Spanshot Fact` where `Store Key`=%d   group by `Date`;",
			$this->id
		);
		$res=mysql_query($sql);

		$counter_available=0;
		$counter=0;
		$sum=0;
		while ($row=mysql_fetch_assoc($res)) {

			$sum+=$row['sales'];
			$counter++;
			if ($row['sales']==$row['availability']) {
				$counter_available++;
			}


		}


		if ($counter>0) {
			$this->data['Store Number Days on Sale']=$counter;
			$this->data['Store Avg Day Sales']=$sum/$counter;
			$this->data['Store Number Days Available']=$counter_available;

		}else {
			$this->data['Store Number Days on Sale']=0;
			$this->data['Store Avg Day Sales']=0;
			$this->data['Store Number Days Available']=0;


		}

		$sql=sprintf("select sum(`Sales`) as sales  from `Order Spanshot Fact` where `Store Key`=%d and sales>0  group by `Date`;",
			$this->id
		);
		$res=mysql_query($sql);
		$data_sales=array();
		$max_value=0;
		$counter=0;
		$sum=0;
		while ($row=mysql_fetch_assoc($res)) {
			$data_sales[]=$row['sales'];
			$sum+=$row['sales'];
			$counter++;
			if ($row['sales']>$max_value) {
				$max_value=$row['sales'];
			}
		}


		if ($counter>0) {






			$this->data['Store Number Days with Sales']=$counter;
			$this->data['Store Avg with Sale Day Sales']=$sum/$counter;
			$this->data['Store STD with Sale Day Sales']=standard_deviation($data_sales);
			$this->data['Store Max Day Sales']=$max_value;
		}else {
			$this->data['Store Number Days with Sales']=0;
			$this->data['Store Avg with Sale Day Sales']=0;
			$this->data['Store STD with Sale Day Sales']=0;
			$this->data['Store Max Day Sales']=0;

		}

		$sql=sprintf("update `Store Dimension` set `Store Number Days on Sale`=%d,`Store Avg Day Sales`=%d,`Store Number Days Available`=%f,`Store Number Days with Sales`=%d,`Store Avg with Sale Day Sales`=%f,`Store STD with Sale Day Sales`=%f,`Store Max Day Sales`=%f where `Store Key`=%d",
			$this->data['Store Number Days on Sale'],
			$this->data['Store Avg Day Sales'],
			$this->data['Store Number Days Available'],
			$this->data['Store Number Days with Sales'],
			$this->data['Store Avg with Sale Day Sales'],
			$this->data['Store STD with Sale Day Sales'],
			$this->data['Store Max Day Sales'],
			$this->id
		);
		mysql_query($sql);

	}


	function get_tax_rate() {
		$rate=0;
		$sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
			prepare_mysql($this->data['Store Tax Category Code']));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$rate=$row['Tax Category Rate'];
		}
		return $rate;
	}


	function get_payment_account_key() {
		$payment_account_key=0;
		$sql=sprintf("select PA.`Payment Account Key` from `Payment Account Dimension` PA left join `Payment Account Site Bridge` B on (PA.`Payment Account Key`=B.`Payment Account Key`)  where `Payment Type`='Account' and `Store Key`=%d ",
			$this->id);
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$payment_account_key=$row['Payment Account Key'];
		}


		return $payment_account_key;

	}


	function get_payment_accounts_data() {
		$payment_accounts_data=array();
		$sql=sprintf("select *from `Payment Account Dimension` PA left join `Payment Account Site Bridge` B on (PA.`Payment Account Key`=B.`Payment Account Key`) left join `Payment Service Provider Dimension` PSPD on (PSPD.`Payment Service Provider Key`=PA.`Payment Service Provider Key`)  where  `Status`='Active' and `Store Key`=%d ",
			$this->id);
		// print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {


			if ($row['Payment Type']=='Account')
				continue;
			$payment_service_provider=new Payment_Service_Provider($row['Payment Service Provider Key']);

			$payment_accounts_data[]=array(
				'key'=>$row['Payment Account Key'],
				'code'=>$row['Payment Account Code'],
				'type'=>$row['Payment Type'],
				'service_provider_code'=>$row['Payment Service Provider Code'],
				'service_provider_name'=>$row['Payment Service Provider Name'],
				'valid_payment_methods'=>join(',', preg_replace('/\s/', '', $payment_service_provider->get_valid_payment_methods()))

			);

		}


		return $payment_accounts_data;

	}


	function cancel_old_orders_in_basket() {
		include_once 'common_natural_language.php';

		if (!$this->data['Cancel Orders In Basket Older Than']) {
			return;
		}

		$date=gmdate('Y-m-d H:i:s', strtotime(sprintf("now -%d seconds +0:00", $this->data['Cancel Orders In Basket Older Than'])));

		$sql=sprintf("select `Order Key` from `Order Dimension` where  `Order Current Dispatch State`='In Process By Customer' and `Order Store Key`=%d and `Order Last Updated Date`<%s",
			$this->id,
			prepare_mysql($date)
		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$order=new Order($row['Order Key']);
			$order->editor=$this->editor;
			$note=sprintf(_('Order cancelled because has been untouched in the basket for more than %s'), seconds_to_string($this->data['Cancel Orders In Basket Older Than']));


			$order->cancel($note, false, true);

			//print $order->data['Order Date']." ".$order->data['Order Last Updated Date']." ".$order->data['Order Public ID']."  \n";
			// exit;
		}



	}


	function get_field_label($field) {

		switch ($field) {

		case 'Store Code':
			$label=_('Code');
			break;
		case 'Store Name':
			$label=_('Name');
			break;



		default:
			$label=$field;

		}

		return $label;

	}


	function create_timeseries($data) {

		$data['Timeseries Parent']='Store';
		$data['Timeseries Parent Key']=$this->id;

		$timeseries=new Timeseries('find', $data, 'create');
		if ($timeseries->new ) {
			require_once 'utils/date_functions.php';

			if ($this->data['Store Valid From']!='') {
				$from=date('Y-m-d', strtotime($this->get('Valid From')));

			}else {
				$from='';
			}

			if ($this->get('State')=='No') {
				$to=$this->get('Valid To');
			}else {
				$to=date('Y-m-d');
			}

			if ($from and $to) {


				$dates=date_range($from, $to);
				foreach ($dates as $date) {
					list($timeseries_record_key, $date)=$timeseries->create_record(array('Timeseries Record Date'=> date('Y-m-d', strtotime($date.' 00:00:00'))));
					$updated=$this->update_timeseries_record($timeseries, $timeseries_record_key, $date);

					$timeseries->update_stats();
					if ($updated) {
						$timeseries->update(array('Timeseries Updated'=>gmdate('Y-m-d H:i:s')), 'no_history');
					}

				}


			}
		}

	}


	function update_timeseries_record($timeseries, $timeseries_record_key, $date) {

		if ($timeseries->get('Type')=='StoreSales') {

			$sql=sprintf("select
                    sum(if(`Invoice Type`='Invoice',1,0))  as invoices,
                    sum(if(`Invoice Type`='Refund',1,0))  as refunds,
                    sum(`Invoice Total Net Amount`) net,
                    sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net
                     from `Invoice Dimension` where `Invoice Store Key`=%d and Date(`Invoice Date`)=%s" ,
				$this->id,
				prepare_mysql($date)
			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$invoices=$row['invoices'];
					$refunds=$row['refunds'];
					$net=$row['net'];
					$dc_net=$row['dc_net'];
				}else {
					$invoices=0;
					$refunds=0;
					$net=0;
					$dc_net=0;
				}

				$sql=sprintf('update `Timeseries Record Dimension` set
                    `Timeseries Record Integer A`=%d ,
                    `Timeseries Record Integer B`=%d ,
                    `Timeseries Record Float A`=%.2f ,
                    `Timeseries Record Float B`=%.2f ,
                    `Timeseries Record Type`=%s
                    where `Timeseries Record Key`=%d
                      ',
					$invoices,
					$refunds,
					$net,
					$dc_net,
					prepare_mysql('Data'),
					$timeseries_record_key

				);

				$update_sql = $this->db->prepare($sql);
				$update_sql->execute();




				if ($update_sql->rowCount() or $date==date('Y-m-d')) {
					return true;
				}else {
					return false;
				}


			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



		}

		return false;

	}


	function create_customer($data) {
		$this->new_customer=false;

		$data['editor']=$this->editor;
		$data['Customer Store Key']=$this->id;


		$address_fields=array(
			'Address Recipient'=>$data['Customer Main Contact Name'],
			'Address Organization'=>$data['Customer Company Name'],
			'Address Line 1'=>'',
			'Address Line 2'=>'',
			'Address Sorting Code'=>'',
			'Address Postal Code'=>'',
			'Address Dependent Locality'=>'',
			'Address Locality'=>'',
			'Address Administrative Area'=>'',
			'Address Country 2 Alpha Code'=>$data['Customer Contact Address country'],

		);
		unset($data['Customer Contact Address country']);

		if (isset($data['Customer Contact Address addressLine1'])) {
			$address_fields['Address Line 1']=$data['Customer Contact Address addressLine1'];
			unset($data['Customer Contact Address addressLine1']);
		}
		if (isset($data['Customer Contact Address addressLine2'])) {
			$address_fields['Address Line 2']=$data['Customer Contact Address addressLine2'];
			unset($data['Customer Contact Address addressLine2']);
		}
		if (isset($data['Customer Contact Address sortingCode'])) {
			$address_fields['Address Sorting Code']=$data['Customer Contact Address sortingCode'];
			unset($data['Customer Contact Address sortingCode']);
		}
		if (isset($data['Customer Contact Address postalCode'])) {
			$address_fields['Address Postal Code']=$data['Customer Contact Address postalCode'];
			unset($data['Customer Contact Address postalCode']);
		}

		if (isset($data['Customer Contact Address dependentLocality'])) {
			$address_fields['Address Dependent Locality']=$data['Customer Contact Address dependentLocality'];
			unset($data['Customer Contact Address dependentLocality']);
		}

		if (isset($data['Customer Contact Address locality'])) {
			$address_fields['Address Locality']=$data['Customer Contact Address locality'];
			unset($data['Customer Contact Address locality']);
		}

		if (isset($data['Customer Contact Address administrativeArea'])) {
			$address_fields['Address Administrative Area']=$data['Customer Contact Address administrativeArea'];
			unset($data['Customer Contact Address administrativeArea']);
		}

		//print_r($address_fields);
		// print_r($data);

		//exit;

		$customer= new Customer('new', $data, $address_fields);

		if ($customer->id) {
			$this->new_customer_msg=$customer->msg;

			if ($customer->new) {
				$this->new_customer=true;
				$this->update_customers_data();
			} else {
				$this->error=true;
				$this->msg=$customer->msg;

			}
			return $customer;
		}
		else {
			$this->error=true;
			$this->msg=$customer->msg;
		}
	}


	function get_sales_timeseries_sql() {

		$table='`Order Spanshot Fact` TR ';
		$where=sprintf(' where `Store Key`=%d', $this->id);

		$order='`Date`';
		$fields="`Sales`,`Sales DC`,`Availability`,`Customers`,`Invoices`";

		$sql="select $fields from $table $where  order by $order ";

		return $sql;

	}


}


?>
