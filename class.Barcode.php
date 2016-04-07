<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2016 at 19:07:36 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';

class Barcode extends DB_Table{


	function Barcode($a1, $a2=false, $a3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Barcode';
		$this->ignore_fields=array('Barcode Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}elseif ($a1=='find') {
			$this->find($a2, $a3);

		}else
			$this->get_data($a1, $a2);
	}


	function get_data($key, $tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Barcode Dimension` where `Barcode Key`=%d", $tag);
		else
			return;

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Barcode Key'];

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




		$sql=sprintf("select `Barcode Key` from `Barcode Dimension` where  `Barcode Number`=%s  ",
			prepare_mysql($data['Barcode Number'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Barcode Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Barcode Number';
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




		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if (preg_match('/^(Barcode Sticky Note)$/i', $key))
				$values.=prepare_mysql($value, false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Barcode Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->msg=_("Barcode added");
			$this->get_data('id', $this->id);
			$this->new=true;



			return;
		}else {
			$this->msg=_("Error, can not create barcode");
			print $sql;
			exit;
		}
	}




	function get($key, $data=false) {

		if (!$this->id) {
			return '';
		}



		switch ($key) {

		case 'Status':

			switch ($this->data['Barcode Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-barcode" ></i> %s', _('Available'));
				break;
			case 'Used':
				$status=sprintf('<i class="fa fa-cube disabled" ></i> %s', _('Used'));

				break;
			case 'Reserved':
				$status=sprintf('<i class="fa fa-shield disabled" ></i> %s', _('Reserved'));

				break;
			default:
				$status=$this->data['Barcode Status'];
				break;
			}

			return $status;
			break;
		default:



			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Barcode '.$key, $this->data))
				return $this->data['Barcode '.$key];
		}
		return '';
	}


	function update_field_switcher($field, $value, $options='', $metadata='') {



		switch ($field) {



		default:



			$base_data=$this->base_data();

			if (array_key_exists($field, $base_data)) {

				if ($value!=$this->data[$field]) {


					$this->update_field($field, $value, $options);




				}
			}




		}


	}




	function assign_asset($asset_data) {

		$this->new_assigned_asset=false;

		$asset_data['Barcode Asset Barcode Key']=$this->id;

		if (!array_key_exists('Barcode Asset Assigned Date', $asset_data) or $asset_data['Barcode Asset Assigned Date']=='' ) {
			$asset_data['Barcode Asset Assigned Date']=gmdate('Y-m-d H:i:s');
		}


		$keys='(';
		$values='values(';
		foreach ($asset_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Barcode Asset Bridge` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {

			$this->msg=_("Barcode assigned to asset");
			$this->new_assigned_asset=true;

			$this->update_status();


			if ($asset_data['Barcode Asset Type']=='Part') {
				$part=new Part($asset_data['Barcode Asset Key']);
				$part->update(
					array(
						'Part Barcode Number'=>$this->get('Barcode Number'),
						'Part Barcode Key'=>$this->id,

					),  'no_history');
			}





			return;
		}else {
			$this->msg=_("Error, can not create assing asset");
			print $sql;
			exit;
		}




	}


	function update_status() {


		if ($this->get('Barcode Status')!='Reserved') {

			$sql=sprintf("select count(*) as num,min(`Barcode Asset Assigned Date`) as from_date from  `Barcode Asset Bridge` where `Barcode Asset Barcode Key`=%d and `Barcode Asset Status`='Assigned' ", $this->id);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {



					if ($row['num']>0) {
						$status='Used';
					}else {
						$status='Available';
					}
					$this->update(array('Barcode Status'=>$status), 'no_history');
					if ($row['from_date']!='') {

						$this->update(array('Barcode Used From'=>$row['from_date']), 'no_history');

					}

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
		}

	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Barcode Number':
			$label=_("code");
			break;
		case 'Barcode Status':
			$label=_('status');
			break;
		case 'Barcode Type':
			$label=_('type');
			break;
		case 'Barcode Used From':
			$label=_('used from');
			break;
		case 'Barcode Sticky Note':
			$label=_('sticky note');
			break;
		default:
			$label=$field;

		}

		return $label;

	}



}


?>
