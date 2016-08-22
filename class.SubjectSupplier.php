<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2016 at 10:56:11 GMT+7, Bandung, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.Subject.php';


class SubjectSupplier extends Subject {

	function create_order($_data) {

		include_once 'class.Staff.php';
		include_once 'class.Warehouse.php';

		$staff=new Staff($_data['user']->get('User Parent Key'));
		$warehouse=new Warehouse($_data['warehouse_key']);

		$order_data=array(
			'Purchase Order Parent'=>$this->table_name,
			'Purchase Order Parent Key'=>$this->id,
			'Purchase Order Parent Name'=>$this->get('Name'),
			'Purchase Order Parent Code'=>$this->get('Code'),
			'Purchase Order Parent Contact Name'=>$this->get('Main Contact Name'),
			'Purchase Order Parent Email'=>$this->get('Main Plain Email'),
			'Purchase Order Parent Telephone'=>$this->get('Preferred Contact Number Formatted Number'),
			'Purchase Order Parent Address'=>$this->get('Contact Address Formatted'),

			'Purchase Order Currency Code'=>$this->get('Default Currency Code'),
			'Purchase Order Incoterm'=>$this->get('Default Incoterm'),
			'Purchase Order Port of Import'=>$this->get('Default Port of Import'),
			'Purchase Order Port of Export'=>$this->get('Default Port of Export'),


			'Purchase Order Warehouse Key'=>$warehouse->data['Warehouse Key'],
			'Purchase Order Warehouse Code'=>$warehouse->data['Warehouse Code'],
			'Purchase Order Warehouse Name'=>$warehouse->data['Warehouse Name'],
			'Purchase Order Warehouse Address'=>$warehouse->data['Warehouse Address'],
			'Purchase Order Warehouse Company Name'=>$warehouse->data['Warehouse Company Name'],
			'Purchase Order Warehouse Company Number'=>$warehouse->data['Warehouse Company Number'],
			'Purchase Order Warehouse VAT Number'=>$warehouse->data['Warehouse VAT Number'],
			'Purchase Order Warehouse Telephone'=>$warehouse->data['Warehouse Telephone'],
			'Purchase Order Warehouse Email'=>$warehouse->data['Warehouse Email'],

			'Purchase Order Terms and Conditions'=>$this->get('Default PO Terms and Conditions'),
			'Purchase Order Main Buyer Key'=>$staff->id,
			'Purchase Order Main Buyer Name'=>$staff->get('Staff Name'),
			'editor'=>$this->editor
		);




		if ($this->get('Show Warehouse TC in PO')=='Yes') {

			if ($order_data['Purchase Order Terms and Conditions']!='') {
				$order_data['Purchase Order Terms and Conditions'].='<br><br>';
			}
			$order_data['Purchase Order Terms and Conditions'].=$warehouse->data['Warehouse Default PO Terms and Conditions'];
		}








		$order=new PurchaseOrder('new', $order_data);



		if ($order->error) {
			$this->error=true;
			$this->msg=$order->msg;

		}




		return $order;

	}


	function update_orders() {
		$number_purchase_orders=0;
		$number_open_purchase_orders=0;
		$number_delivery_notes=0;
		$number_invoices=0;

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and `Purchase Order Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and  `Purchase Order Parent Key`=%d and `Purchase Order State` not in ('Done','Cancelled')",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_open_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Parent`=%s and  `Supplier Delivery Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_delivery_notes=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("update `%s Dimension` set `%s Purchase Orders`=%d,`%s Open Purchase Orders`=%d ,`%s Supplier Deliveries`=%d  where `%s Key`=%d",
			$this->table_name,
			$this->table_name,
			$number_purchase_orders,
			$this->table_name,
			$number_open_purchase_orders,
			$this->table_name,
			$number_delivery_notes,
			$this->table_name,

			$this->table_name,
			$this->id);
		$this->db->exec($sql);

	}


	function get_user_data() {

		$sql=sprintf('select * from `User Dimension` where `User Type`=%s and `User Parent Key`=%d ',
			prepare_mysql($this->table_name),
			$this->id);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data['Supplier '.$key]=$value;
			}
		}



	}


	function get_users($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.User.php';
		}


		$users=array();
		$sql=sprintf("select `User Key` from `User Dimension` whereUser Type`=%s and `User Parent Key`=%d  ",
			prepare_mysql($this->table_name),
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($scope=='objects') {

					$users[$row['User Key']]=new User($row['User Key']);

				}else {
					$users[$row['User Key']]=$row['User Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $users;

	}






	function create_user($data) {



		if (isset($this->data[$this->table_name.' User Key']) and $this->data[$this->table_name.' User Key']) {
			$this->create_user_error=true;
			if ($this->table_name=='Supplier')
				$this->create_user_msg=_('Supplier has already a system user');
			else
				$this->create_user_msg=_('Agent has already a system user');

			$this->user=false;
			return false;
		}


		$data['editor']=$this->editor;

		if (!array_key_exists('User Handle', $data) or $data['User Handle']=='' ) {
			$this->create_user_error=true;
			$this->create_user_msg=_('User login must be provided');
			$this->user=false;
			return false;

		}

		if (!array_key_exists('User Password', $data) or $data['User Password']=='' ) {
			include_once 'utils/password_functions.php';
			$data['User Password']=hash('sha256', generatePassword(8, 3));
		}

		$data['User Type']=$this->table_name;


		$data['User Parent Key']=$this->id;
		$data['User Alias']=$this->get('Name');
		$user= new User('find', $data, 'create');
		$this->get_user_data();
		$this->create_user_error=$user->error;
		$this->create_user_msg=$user->msg;
		$this->user=$user;

		return $user;


	}


	function get_subject_supplier_common($key) {


		if (!$this->id)return array(false, false);;

		list($got, $result)=$this->get_subject_common($key);
		if ($got)return array(true, $result);




		switch ($key) {

		case('Valid From'):
		case('Valid To'):
			if ($this->get($this->table_name.' '.$key)=='') {
				return array(true, '');
			}else {
				return array(true, strftime("%a, %e %b %y", strtotime($this->get($this->table_name.' '.$key).' +0:00')));
			}
			break;
		case ('Default Currency'):

			if ($this->data[$this->table_name.' Default Currency Code']!='') {



				$options_currencies=array();
				$sql=sprintf("select `Currency Code`,`Currency Name`,`Currency Symbol` from kbase.`Currency Dimension` where `Currency Code`=%s",
					prepare_mysql($this->data[$this->table_name.' Default Currency Code']));



				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {
						return array(true, sprintf("%s (%s)", $row['Currency Name'], $row['Currency Code']));
					}else {
						return array(true, $this->data[$this->table_name.' Default Currency Code']);
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}
			}else {
				return array(true, '');
			}

			break;
		case 'Average Delivery Days':
			if ($this->data[$this->table_name.' Average Delivery Days']=='') return array(true, '');
			return array(true, number($this->data[$this->table_name.' Average Delivery Days']));
			break;
		case 'Delivery Time':


			include_once 'utils/natural_language.php';
			if ($this->get($this->table_name.' Average Delivery Days')=='') {
				return array(true, '<span class="italic very_discreet">'._('Unknown').'</span>');
			}else {
				return array(true, seconds_to_natural_string(24*3600*$this->get($this->table_name.' Average Delivery Days')));
			}
			break;


		case 'Products Origin Country Code':
			if ($this->get($this->table_name.' Products Origin Country Code')) {
				include_once 'class.Country.php';
				$country=new Country('code', $this->data[$this->table_name.' Products Origin Country Code']);
				return array(true, _($country->get('Country Name')).' ('.$country->get('Country Code').')');
			}else {
				return array(true, '');
			}

			break;


		case('Purchase Orders'):
		case('Open Purchase Orders'):
		case('Delivery Notes'):
		case('Invoices'):
			return array(true, number($this->data[$this->table_name.' '.$key]));
			break;

		case('Formatted ID'):
		case("ID"):
			return array(true, $this->get_formatted_id());
		case('Total Acc Parts Sold Amount'):
			return array(true, money($this->data[$this->table_name.' Total Acc Parts Sold Amount']));
			break;
		case('Total Acc Parts Profit'):
			return array(true, money($this->data[$this->table_name.' Total Acc Parts Profit After Storing']));
			break;
		case('Stock Value'):

			if (!is_numeric($this->data[$this->table_name.' Stock Value']))
				return array(true, _('Unknown'));
			else
				return array(true, money($this->data[$this->table_name.' Stock Value']));
			break;
		default;


		}

		return array(false, false);

	}


}


?>
