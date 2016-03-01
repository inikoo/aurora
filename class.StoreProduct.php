<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Based in 2009 class.Product.php
 Created: 16 February 2016 at 22:35:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'class.Asset.php';

class StoreProduct extends Asset{

	function __construct($arg1=false, $arg2=false, $arg3=false) {
		global $db;
		$this->db=$db;


		$this->table_name='Store Product';
		$this->ignore_fields=array('Store Product Key');
		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return ;
		}
		if (preg_match('/^find/i', $arg1)) {

			$this->find($arg2, $arg3);
			return;
		}

		if (preg_match('/create|new/i', $arg1) and is_array($arg2) ) {

			$this->find($arg2, 'create');
			return;
		}
		$this->get_data($arg1, $arg2, $arg3);



	}


	function get_data($key, $id, $aux_id=false) {

		if ($key=='id')
			$sql=sprintf("select * from `Store Product Dimension` where `Store Product Key`=%d", $id);
		elseif ($key=='store_code')
			$sql=sprintf("select * from  `Store Product Dimension`  where `Store Product Store Key`=%s  and `Store Product Key`=%s", $id, prepare_mysql($aux_id));

		else
			return;



		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Store Product Key'];
		}
		$this->get_store_data();
	}


	function get_store_data() {

		$sql=sprintf('select * from `Store Dimension` where `Store Key`=%d ', $this->data['Store Product Store Key']);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}



	}


	function get_parts_data($with_objects=false) {

		include_once 'class.Part.php';

		$sql=sprintf("select `Linked Fields`,`Part SKU`,`Parts Per Product`,`Note` from `Store Product Part Bridge` where `Store Product Key`=%d ",
			$this->id
		);
		$parts_data=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$part_data=$row;
				if ($part_data['Linked Fields']=='') {
					$part_data['Linked Fields']=array();
					$part_data['Number Linked Fields']=0;
				}else {
					$part_data['Linked Fields']=json_decode($row['Linked Fields'], true);
					$part_data['Number Linked Fields']=count($part_data['Linked Fields']);
				}
				if ($with_objects) {
					$part_data['Part']=new Part($row['Part SKU']);
				}
				$parts_data[]=$part_data;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $parts_data;
	}


	function get($key, $arg1='') {

		list($got, $result)=$this->get_asset_common($key, $arg1);
		if ($got)return $result;

		if (!$this->id)
			return;

		switch ($key) {
		case 'Unit Type':
			if ($this->data['Store Product Unit Type']=='')return '';
			$unit_type_data=json_decode($this->data['Store Product Unit Type'], true);
			$unit_type_key=key($unit_type_data);

			$unit_type_value=$unit_type_data[$unit_type_key];
			$unit_type_key=_($unit_type_key);
			if ($unit_type_key!=$unit_type_value) {
				return "$unit_type_value ($unit_type_key)";
			}else {
				return $unit_type_key;
			}

			break;
		case 'Parts':
			$parts='';



			$parts_data=$this->get_parts_data(true);

			$part_warehouse=$_SESSION['current_warehouse'];
			//print_r($parts_data);
			foreach ($parts_data as $part_data) {

				$parts.=', '.number($part_data['Parts Per Product']).'x <span class="link" onClick="change_view(\'inventory/'.$part_warehouse.'/part/'.$part_data['Part']->id.'\')">'.$part_data['Part']->get('SKU').' ('.$part_data['Part']->get('Reference').')</span>';
			}

			if ($parts=='') {
				$parts='<span class="discret">'._('No parts assigned').'</span>';
			}
			$parts=preg_replace('/^, /', '', $parts);
			return $parts;

			break;
		case 'Outer Weight':
			return weight($this->data['Store Product Outer Weight']);


		case 'Store Product Outer Weight':
			$str = number_format($this->data['Store Product Outer Weight'], 4);

			return preg_replace('/(?<=\d{3})0+$/', '', $str);

		case 'Store Product Price':
			$str = number_format($this->data['Store Product Price'], 4);

			return preg_replace('/(?<=\d{2})0+$/', '', $str);
			break;
		case 'Price':
			return money($this->data['Store Product Price'], $this->data['Store Currency Code']);
			break;
		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Store Product '.$key, $this->data))
				return $this->data['Store Product '.$key];

		}


	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Store Product Key':
			$label=_('id');
			break;

		case 'Store Product Code':
			$label=_('code');
			break;
		case 'Store Product Outer Description':
			$label=_('description');
			break;
		case 'Store Product Unit Description':
			$label=_('unit description');
			break;
		case 'Store Product Price':
			$label=_('Price');
			break;
		case 'Store Product Outer Weight':
			$label=_('weight');
			break;
		case 'Store Product Outer Dimensions':
			$label=_('dimensions');
			break;
		case 'Store Product Units Per Outer':
			$label=_('retail units per outer');
			break;
		case 'Store Product Outer Tariff Code':
			$label=_('tariff code');
			break;
		case 'Store Product Outer Duty Rate':
			$label=_('duty rate');
			break;
		case 'Store Product Unit Type':
			$label=_('unit type');
			break;
		case 'Store Product Label in Family':
			$label=_('label in family');
			break;

		case 'Store Product Unit Weight':
			$label=_('unit weight');
			break;
		case 'Store Product Unit Dimensions':
			$label=_('unit dimensions');
			break;

		default:
			$label=$field;

		}

		return $label;

	}


	function find($raw_data, $options) {



		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}


		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}



		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
		}


		$sql=sprintf("select `Store Product Key` from `Store Product Dimension` where  `Store Product Store Key`=%s and `Store Product Code`=%s",
			$data['Store Product Store Key'],
			prepare_mysql($data['Store Product Code'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Store Product Key'];
				$this->get_data('id', $this->found_key);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create and !$this->found) {




			$this->create($raw_data);

		}



	}


	function create($data) {

		include_once 'utils/natural_language.php';


		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=_trim($value);
			}
		}
		$this->editor=$data['editor'];

		if ($this->data['Store Product Valid From']=='') {
			$this->data['Store Product Valid From']=gmdate('Y-m-d H:i:s');
		}


		$this->data['Store Product Code File As']=get_file_as($this->data['Store Product Code']);

		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			if (in_array($key, array('Store Product Valid To', 'Store Product Unit Weight', 'Store Product Outer Weight'))) {
				$values.=','.prepare_mysql($value, true);

			}else {
				$values.=','.prepare_mysql($value, false);
			}
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Store Product Dimension` ($keys) values ($values)";
		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->get_data('id', $this->id);

			$sql=sprintf("insert into  `Store Product DC Data`  (`Store Product Key`) values (%d) ", $this->id);
			$this->db->exec($sql);

			$sql=sprintf("insert into  `Store Product Data`  (`Store Product Key`) values (%d) ", $this->id);
			$this->db->exec($sql);




			$history_data=array(
				'History Abstract'=>sprintf(_('%s product record created'), $this->data['Store Product Outer Description']),
				'History Details'=>'',
				'Action'=>'created'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			$this->new=true;






		}else {
			$this->error=true;
			$this->msg='Error inserting Store Product record';
		}



	}




	function update_field_switcher($field, $value, $options='', $metadata='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {
		case 'Store Product Outer Dimensions':

			if ($value=='') {
				$dim='';
				$vol='';
			}else {
				$dim=parse_dimensions($value);
				if ($dim=='') {
					$this->error=true;
					$this->msg=_("Package dimensions can't be parsed");
					return;
				}
				$_tmp=json_decode($dim, true);
				$vol=$_tmp['vol'];
			}

			$this->update_field('Store Product Outer Dimensions', $dim, $options);
			$this->update_field('Store Product Outer Volume', $vol, $options);


			break;


		case 'Store Product Family Category Key':
			include_once 'class.Category.php';
			$family=new Category($value);
			$family->associate_subject($this->id);
			$this->update_field($field, $value, 'no_history');

			$sql=sprintf("select C.`Category Key` from `Category Dimension` C left join `Category Bridge` B on (C.`Category Key`=B.`Category Key`) where `Category Root Key`=%d and `Subject Key`=%d and `Subject`='Category' and `Category Branch Type`='Head'",

				$this->data['Store Department Category Key'],
				$family->id
			);
			//print $sql;
			$departmet_key='';
			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$departmet_key=$row['Category Key'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
			$this->update_field('Store Product Department Category Key', $departmet_key, 'no_history');


			$this->other_fields_updated=array(
				'Store_Product_Family_Category_Key'=>array(
					'field'=>'Store_Product_Family_Category_Key',
					'render'=>true,
					'value'=>$this->get('Family Category Key'),
					'formatted_value'=>$family->get('Code').', '.$family->get('Label')


				)
			);




		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();

	}



	function get_linked_fields_data() {

		$sql=sprintf("select `Part SKU`,`Linked Fields` from `Store Product Part Bridge` where `Store Product Key`=%d", $this->id);

		$linked_fields_data=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Linked Fields']!='') {
					$linked_fields=json_decode($row['Linked Fields'], true);

					foreach ($linked_fields as $key=>$value) {
						$value=preg_replace('/\s/', '_', $value);
						$linked_fields_data[$value]=$row['Part SKU'];
					}

				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		return $linked_fields_data;

	}


}




?>
