<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Account extends DB_Table{

	function Account($a1=false, $a2=false) {
		global $db;
		$this->db=$db;

		$this->table_name='Account';


		$this->get_data();
	}


	function get_data() {


		$sql=sprintf("select * from `Account Dimension` where `Account Key`=1 ");


		if ($result=$this->db->query($sql)) {
			if ($this->data = $result->fetch()) {
				$this->id=1;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



	}




	function get($key, $data=false) {

		if (!$this->id)
			return;



		switch ($key) {
		case 'Setup Metadata':
			return json_decode($this->data['Account Setup Metadata'], true);
			break;
		case 'National Employment Code Label':

			switch ($this->data['Account Country 2 Alpha Code']) {
			case 'GB':
				return _('National insurance number');
				break;
			case 'ES':
				return _('DNI');
				break;
			default:
				return '';
				break;
			}

			break;

		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Account '.$key, $this->data))
				return $this->data['Account '.$key];
		}
		return '';
	}


	protected function update_field_switcher($field, $value, $options='', $metadata='') {


		switch ($field) {
		case 'Company Name':


		case('Account Currency'):
			$this->update_currency($value);
			break;
		default:

			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}


			break;
		}
	}


	function update_name($value) {


		$sql=sprintf("update `Account Dimension` set `Account Name`=%s", prepare_mysql($value));
		$this->db->exec($sql);

		$this->updated=true;
		$this->new_value=$value;
	}







	function update_currency($value) {
		$value=strtoupper($value);
		$sql=sprintf("select * from kbase.`Currency Dimension` where `Currency Code`=%s", prepare_mysql($value));

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$sql=sprintf("update `Account Dimension` set `Account Currency`=%s", prepare_mysql($value));
				$this->db->exec($sql);

				$this->updated=true;
				$this->new_value=$value;

			}else {
				$this->error=true;
				$this->msg='Currency Code '.$value.' not valid';

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


	function add_account_history($history_key, $type=false) {
		$this->post_add_history($history_key, $type=false);
	}


	function post_add_history($history_key, $type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Account History Bridge` (`History Key`,`Type`) values (%d,%s)",
			$history_key,
			prepare_mysql($type)
		);
		$this->db->exec($sql);
		//print $sql;
	}


	function get_current_staff_with_position_code($position_code, $options='') {


		if (preg_match('/smarty/i', $options))
			$smarty=true;
		else
			$smarty=false;

		$positions=array();
		$sql=sprintf('Select * from `Staff Dimension` SD  left join `Company Position Staff Bridge` B on (B.`Staff Key`=SD.`Staff Key`)  where  `Position Code`=%s and `Staff Currently Working`="Yes"'
			, prepare_mysql($position_code)
		);



		if ($result=$db->this->query($sql)) {
			foreach ($result as $row) {

				if ($smarty) {
					$_row=array();
					foreach ($row as $key=>$value) {
						$_row[preg_replace('/\s/', '', $key)]=$value;
					}

					$positions[$row['Staff Key']]=$_row;
				}else
					$positions[$row['Staff Key']]=$row;

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		return $positions;
	}


	function get_store_keys() {
		$store_keys=array();
		$sql=sprintf('select `Store Key` from `Store Dimension`');
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$store_keys[]=$row['Store Key'];
		}
		return $store_keys;
	}


	function create_staff($data) {
		$this->new_employee=false;

		$data['editor']=$this->editor;
		$staff= new Staff('find', $data, 'create');

		if ($staff->id) {
			$this->new_employee_msg=$staff->msg;

			if ($staff->new) {
				$this->new_employee=true;
				$this->update_employees_data();
			} else {
				$this->error=true;
				if ($staff->found) {
					$this->msg=_('Duplicated employee code');
				}else {
					$this->msg=$staff->msg;
				}
			}
			return $staff;
		}
		else {
			$this->error=true;
			$this->msg=$staff->msg;
		}
	}


	function create_supplier($data) {
		$this->new_employee=false;

		$data['editor']=$this->editor;



		if ( !array_key_exists('Supplier Code', $data)  or $data['Supplier Code']==''  ) {
			$this->error=true;
			$this->msg='error, no supplier code';

			return;
		}


		$address_fields=array(
			'Address Recipient'=>$data['Supplier Main Contact Name'],
			'Address Organization'=>$data['Supplier Company Name'],
			'Address Line 1'=>'',
			'Address Line 2'=>'',
			'Address Sorting Code'=>'',
			'Address Postal Code'=>'',
			'Address Dependent Locality'=>'',
			'Address Locality'=>'',
			'Address Administrative Area'=>'',
			'Address Country 2 Alpha Code'=>$data['Supplier Contact Address country'],

		);
		unset($data['Supplier Contact Address country']);

		if (isset($data['Supplier Contact Address addressLine1'])) {
			$address_fields['Address Line 1']=$data['Supplier Contact Address addressLine1'];
			unset($data['Supplier Contact Address addressLine1']);
		}
		if (isset($data['Supplier Contact Address addressLine2'])) {
			$address_fields['Address Line 2']=$data['Supplier Contact Address addressLine2'];
			unset($data['Supplier Contact Address addressLine2']);
		}
		if (isset($data['Supplier Contact Address sortingCode'])) {
			$address_fields['Address Sorting Code']=$data['Supplier Contact Address sortingCode'];
			unset($data['Supplier Contact Address sortingCode']);
		}
		if (isset($data['Supplier Contact Address postalCode'])) {
			$address_fields['Address Postal Code']=$data['Supplier Contact Address postalCode'];
			unset($data['Supplier Contact Address postalCode']);
		}

		if (isset($data['Supplier Contact Address dependentLocality'])) {
			$address_fields['Address Dependent Locality']=$data['Supplier Contact Address dependentLocality'];
			unset($data['Supplier Contact Address dependentLocality']);
		}

		if (isset($data['Supplier Contact Address locality'])) {
			$address_fields['Address Locality']=$data['Supplier Contact Address locality'];
			unset($data['Supplier Contact Address locality']);
		}

		if (isset($data['Supplier Contact Address administrativeArea'])) {
			$address_fields['Address Administrative Area']=$data['Supplier Contact Address administrativeArea'];
			unset($data['Supplier Contact Address administrativeArea']);
		}

		//print_r($address_fields);
		// print_r($data);

		//exit;

		$supplier= new Supplier('new', $data, $address_fields);

		if ($supplier->id) {
			$this->new_supplier_msg=$supplier->msg;

			if ($supplier->new) {
				$this->new_supplier=true;
				$this->update_suppliers_data();
			} else {
				$this->error=true;
				$this->msg=$supplier->msg;

			}
			return $supplier;
		}
		else {
			$this->error=true;
			$this->msg=$supplier->msg;
		}
	}


	function create_manufacture_task($data) {
		$this->new_manufacture_task=false;

		$data['editor']=$this->editor;


		if (!isset($data['Manufacture Task From'])) {
			$data['Manufacture Task From']=gmdate('Y-m-d H:i:s');
		}
		if (isset($data['Manufacture Task Lower Target Per Hour'])) {

			if ($data['Manufacture Task Lower Target Per Hour']==0) {
				$this->error=true;
				$this->msg=_("Lower target per hour can't be zero");
				return false;
			} elseif ($data['Manufacture Task Lower Target Per Hour']<0) {
				$this->error=true;
				$this->msg=_("Lower target per hour can't be negative");
				return false;
			}

			$data['Manufacture Task Lower Target']=3600/$data['Manufacture Task Lower Target Per Hour'];
			unset($data['Manufacture Task Lower Target Per Hour']);
		}

		if (isset($data['Manufacture Task Upper Target Per Hour'])) {

			if ($data['Manufacture Task Upper Target Per Hour']==0) {
				$this->error=true;
				$this->msg=_("Upper target per hour can't be zero");
				return false;
			} elseif ($data['Manufacture Task Upper Target Per Hour']<0) {
				$this->error=true;
				$this->msg=_("Upper target per hour can't be negative");
				return false;
			}

			$data['Manufacture Task Upper Target']=3600/$data['Manufacture Task Upper Target Per Hour'];
			unset($data['Manufacture Task Upper Target Per Hour']);
		}



		$manufacture_task= new Manufacture_Task('find', $data, 'create');

		if ($manufacture_task->id) {
			$this->new_manufacture_task_msg=$manufacture_task->msg;

			if ($manufacture_task->new) {
				$this->new_manufacture_task=true;
				return $manufacture_task;
			} else {
				$this->error=true;
				if ($manufacture_task->found) {
					$this->msg=_('Duplicated manufacture task name');
				}else {
					$this->msg='Error '.$manufacture_task->msg;
				}
			}
			return false;
		}
		else {
			$this->error=true;
			$this->msg='Error '.$manufacture_task->msg;
			return false;
		}
	}


	function update_employees_data() {
		$number_employees=0;
		$sql=sprintf('select count(*) as num from `Staff Dimension` where `Staff Currently Working`="Yes" ');
		if ($row = $this->db->query($sql)->fetch()) {
			$number_employees=$row['num'];
		}

		$this->update(array('Account Employees'=>$number_employees), 'no_history');

	}


	function get_field_label($field) {

		switch ($field) {
		case 'Account Stores':
			$label=_('stores');
			break;
		case 'Account Websites':
			$label=_('Websites');
			break;
		case 'Account Products':
			$label=_('Products');
			break;
		case 'Account Customers':
			$label=_('Customers');
			break;
		case 'Account Invoices':
			$label=_('Invoices');
			break;
		case 'Account Order Transactions':
			$label=_("Order's Items");
			break;

		default:
			$label=$field;
		}
		return $label;

	}


	function create_data_sets($data) {

		$data_set=new Data_Sets('find', $data, 'create');

	}


	function update_suppliers_data() {
		// TODO
	}


}


?>
