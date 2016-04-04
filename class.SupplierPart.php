<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 10:49:10 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';

class SupplierPart extends DB_Table{


	function SupplierPart($a1, $a2=false, $a3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Supplier Part';
		$this->ignore_fields=array('Supplier Part Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}elseif ($a1=='find') {
			$this->find($a2, $a3);

		}else
			$this->get_data($a1, $a2);
	}


	function get_data($key, $tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Supplier Part Dimension` where `Supplier Part Key`=%d", $tag);
		else
			return;


		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Part Key'];
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


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data))
				$data[$key]=_trim($value);
		}



		if ($data['Supplier Part Status']!='Discontinued') {
			$sql=sprintf("select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d and `Supplier Part Part SKU`=%d and `Supplier Part Status`!='Discontinued' ",
				$data['Supplier Part Supplier Key'],
				$data['Supplier Part Part SKU']
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$this->found=true;
					$this->found_key=$row['Supplier Part Key'];
					$this->get_data('id', $this->found_key);
					$this->duplicated_field='Available Supplier Part';
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

		}

		$sql=sprintf("select `Supplier Part Key` from `Supplier Part Dimension` where  `Supplier Part Supplier Key`=%d and `Supplier Part Reference`=%s  ",
			$data['Supplier Part Supplier Key'],
			prepare_mysql($data['Supplier Part Reference'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Supplier Part Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Supplier Part Reference';
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


	function create($data) {
		$this->new=false;
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}


		if ($base_data['Supplier Part From']=='') {
			$base_data['Supplier Part From']=gmdate('Y-m-d H:i:s');
		}


		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			//   if (preg_match('/^(Supplier Part Address|Supplier Part Company Name|Supplier Part Company Number|Supplier Part VAT Number|Supplier Part Telephone|Supplier Part Email)$/i', $key))
			//    $values.=prepare_mysql($value, false).",";
			//   else
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Supplier Part Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->msg=_("Supplier part added");
			$this->get_data('id', $this->id);
			$this->new=true;



			return;
		}else {
			$this->msg=_(" Error can not create supplier part");
			print $sql;
			exit;
		}
	}




	function get($key, $data=false) {

		if (!$this->id) {
			return '';
		}



		switch ($key) {

		default:

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Supplier Part '.$key, $this->data))
				return $this->data['Supplier Part '.$key];
		}
		return '';
	}



	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Supplier Part Code':
			$label=_('code');
			break;
		case 'Supplier Part Name':
			$label=_('name');
			break;

		default:
			$label=$field;

		}

		return $label;

	}


}


?>
