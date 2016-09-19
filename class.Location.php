<?php
/*
 File: Location.php

 This file contains the Location Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Refurbished: 28 April 2016 at 15:40:46 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


class Location extends DB_Table {


	var $parts=false;
	var $warehouse=false;
	var $warehouse_area=false;
	var $shelf=false;

	function Location($arg1=false, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Location';
		$this->ignore_fields=array('Location Key');

		if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
			$this->create($arg2);
			return;
		}
		if (preg_match('/find/i', $arg1)) {
			$this->find($arg2, $arg3);
			return;
		}
		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return;
		}
		$this->get_data($arg1, $arg2);

	}


	function find($raw_data, $options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;
			}
		}


		$this->found=false;
		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}






		$data=$this->base_data();
		foreach ($raw_data as $key=>$val) {
			/*       if(preg_match('/from supplier/',$options)) */
			/* 	$_key=preg_replace('/^Location /i','',$key); */
			/*       else */
			$_key=$key;
			$data[$_key]=$val;
		}


		//look for areas with the same code in the same warehouse
		$sql=sprintf("select `Location Key` from `Location Dimension` where `Location Warehouse Key`=%d and `Location Code`=%s"
			, $data['Location Warehouse Key']
			, prepare_mysql($data['Location Code']));

		// print $sql;

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Location Key'];


				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Location Code';
				return;


			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		if ($create) {

			$this->create($data, $options);



		}
	}





	function create($data) {


		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data))
				$this->data[$key]=_trim($value);
		}

		$this->data['Location File As']=$this->get_file_as($this->data['Location Code']);

		//exit;
		$warehouse=new Warehouse($this->data['Location Warehouse Key']);
		if (!$warehouse->id) {
			$this->error=true;
			$this->msg='W not found';
			return;
		}

		

		if ($this->data['Location Code']=='') {
			$error=true;
			$this->msg=_('Wrong location code');
			return;
		}

		if (!preg_match('/^(Picking|Storing|Loading|Displaying|Other)$/i', $this->data['Location Mainly Used For'])) {
			$error=true;
			$this->msg='Wrong location usage: '.$this->data['Location Mainly Used For'];
			return;
		}
		if (!$this->data['Location Max Volume']) {
			if ($this->data['Location Shape Type']=='Box'
				and is_numeric($this->data['Location Width']) and $this->data['Location Width']>0
				and is_numeric($this->data['Location Deep']) and $this->data['Location Deep']>0
				and is_numeric($this->data['Location Height']) and $this->data['Location Height']>0
			) {
				$this->data['Location Max Volume']=$this->data['Location Width']*$this->data['Location Deep']*$this->data['Location Height']*0.001;
			}
			if ($this->data['Location Shape Type']=='Cylinder'
				and is_numeric($this->data['Location Radius']) and $this->data['Location Radius']>0
				and is_numeric($this->data['Location Height']) and $this->data['Location Height']>0
			) {
				$this->data['Location Max Volume']=3.151592*$this->data['Location Radius']*$this->data['Location Radius']*$this->data['Location Height']*0.001;
			}
		}
		$keys='(';
		$values='values(';
		foreach ($this->data as $key=>$value) {

			$keys.="`$key`,";
			$_mode=true;
			$values.=prepare_mysql($value, $_mode).",";
		}

		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);

		$sql=sprintf("insert into `Location Dimension` %s %s", $keys, $values);




		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();

			
			
			
			
				$history_data=array(
				'History Abstract'=>sprintf(_('%s location created'), $this->data['Location Code']),
				'History Details'=>'',
				'Action'=>'created'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			
			
			
			$this->new=true;

			$this->get_data('id', $this->id);
			$sql=sprintf("select `Warehouse Flag Key` from  `Warehouse Flag Dimension` where `Warehouse Flag Color`=%s and `Warehouse Key`=%d",
				prepare_mysql($this->data['Warehouse Flag']),
				$this->data['Location Warehouse Key']
			);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->update_warehouse_flag_key($row['Warehouse Flag Key']);

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				print $sql;
				exit;
			}

			//$warehouse->update_children();
			//$warehouse_area->update_children();
/*
			if ($data['Location Shelf Key']) {
				$shelf=new Shelf($data['Location Shelf Key']);
				if ($shelf->id)
					$shelf->update_children();
			}
			*/

		} else {
			exit($sql);
		}


	}



	function get_data($key, $tag) {


		$sql=sprintf("select * from `Location Dimension`");
		if ($key=='id')
			$sql.=sprintf("where `Location Key`=%d ", $tag);
		else if ($key=='name' or $key=='code')
			$sql.=sprintf("where  `Location Code`=%s ", prepare_mysql($tag));
		else
			return;


		if ($this->data = $this->db->query($sql)->fetch()) {

			$this->id=$this->data['Location Key'];
		}else {
			$this->msg=_('Location do not exist');
		}


	}



	function update_code($value, $options=false) {

		$code=_trim($value);

		if ($code=='') {
			$this->msg=_('Wrong location code');
			$this->updated=false;
			return;
		}



		$sql=sprintf('select `Location Key` from `Location Dimension` where `Location Key`!=%d and `Location Code`=%s'
			, $this->id
			, prepare_mysql($value)
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->msg=_('Other location has this code');
				$this->updated=false;
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$this->update_field('Location File As', $this->get_file_as($code), 'no_history');

		$this->update_field('Location Code', $code, $options);




	}


	function update_field_switcher($field, $value, $options='', $metadata='') {

		switch ($field) {
		case('Location Code'):
			$this->update_code($value, $options);
			break;

		case('Location Area Key'):
			$this->update_area_key($value);
			break;
		case('Location Mainly Used For'):
			$this->update_used_for($value);
			break;
		case('Location Max Weight'):
			$this->update_max_weight($value);
			break;
		case('Location Max Volume'):
			$this->update_max_volume($value);
			break;
		case('Warehouse Flag Key'):
			$this->update_warehouse_flag_key($value);
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


	function update_area_key($data) {
		if ($data==$this->data['Location Warehouse Area Key']) {
			$this->msg='no_change';
			return;
		}

		$old_area=new WarehouseArea($this->data['Location Warehouse Area Key']);

		$new_area=new WarehouseArea($data);

		if ($new_area->id) {
			$this->data['Location Warehouse Area Key']=$new_area->id;
			$this->data['Location Shelf Key']=0;


			$sql=sprintf("update `Location Dimension` set `Location Warehouse Area Key`=%d,`Location Shelf Key`=%d where `Location Key`=%d",
				$this->data['Location Warehouse Area Key'],
				$this->data['Location Shelf Key'],
				$this->id
			);
			$this->db->exec($sql);
			$this->msg=_('Location warehouse area changed');
			$this->updated=true;
			$this->new_value=array('code'=>$new_area->data['Warehouse Area Code'], 'key'=>$new_area->id);

		}




	}




	function update_warehouse_flag_key($value) {


		$sql=sprintf("select `Warehouse Key`,`Warehouse Flag Color` from  `Warehouse Flag Dimension` where `Warehouse Flag Key`=%d",
			$value);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {



				if ($row['Warehouse Key']!=$this->data['Location Warehouse Key']) {
					$this->error=true;
					$this->msg='flag key not in this warehouse';
					return;
				}

				$old_key=$this->data['Warehouse Flag Key'];

				$sql=sprintf("update `Location Dimension` set `Warehouse Flag Key`=%d ,`Warehouse Flag`=%s where `Location Key`=%d"
					, $value
					, prepare_mysql($row['Warehouse Flag Color'])
					, $this->id
				);
				$this->db->exec($sql);
				$this->data['Warehouse Flag Key']=$value;
				$this->new_value=$this->data['Warehouse Flag Key'];
				$this->msg=_('Location flag changed');
				$this->updated=true;

				$warehouse=new Warehouse($this->data['Location Warehouse Key']);
				$warehouse->update_location_flag_number($this->data['Warehouse Flag Key']);
				if ($old_key) {
					$warehouse->update_location_flag_number($old_key);

				}




			}else {
				$this->error=true;
				$this->msg='flag key not found';
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


	function update_max_weight($value) {
		list($value, $original_units)=parse_weight($value);
		if (!is_numeric($value)) {
			$this->msg=_('The maximum weight for this location show be numeric');
			$this->updated=false;
			return;
		}
		if ($value < 0) {
			$this->msg=_('The maximum weight can not be negative');
			$this->updated=false;
			return;
		}
		if ($value == 0) {
			$this->msg=_('The maximum weight can not be zero');
			$this->updated=false;
			return;
		}
		if ($value==$this->data['Location Max Weight']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		$sql=sprintf("update `Location Dimension` set `Location Max Weight`=%f where `Location Key`=%d"
			, $value
			, $this->id
		);
		$this->db->exec($sql);
		$this->data['Location Max Weight']=$value;
		$this->new_value=weight($this->data['Location Max Weight']);
		$this->msg=_('Location maximum weight changed');
		$this->updated=true;
	}


	function update_max_volume($value) {
		list($value, $original_units)=parse_volume($value);
		if (!is_numeric($value)) {
			$this->msg=_('The maximum volume for this location show be numeric');
			$this->updated=false;
			return;
		}
		if ($value < 0) {
			$this->msg=_('The maximum volume can not be negative');
			$this->updated=false;
			return;
		}
		if ($value == 0) {
			$this->msg=_('The maximum volume can not be zero');
			$this->updated=false;
			return;
		}
		if ($value==$this->data['Location Max Volume']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		$sql=sprintf("update `Location Dimension` set `Location Max Volume`=%f where `Location Key`=%d"
			, $value
			, $this->id
		);
		$this->db->exec($sql);
		$this->data['Location Max Volume']=$value;
		$this->new_value=volume($this->data['Location Max Volume']);
		$this->msg=_('Location maximum volume changed');
		$this->updated=true;
	}


	function update_used_for($value,$options='') {
		
		if (!preg_match('/^(Picking|Storing|Displaying|Loading|Other)$/', $value)) {
			$this->msg=_('Wrong location type');
			$this->updated=false;
			return;
		}
		
		$this->update_field('Location Mainly Used For',$value,$options);

		


	}


	function update_shape($value) {
		$value=_trim($value);
		if ($value==$this->data['Location Shape Type']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		if (!preg_match('/^(Box|Cylinder|Unknown)$/', $value)) {
			$this->msg=_('Wrong location shape');
			$this->updated=false;
			return;
		}

		$old_value=$this->data['Location Shape Type'];
		$sql=sprintf("update `Location Dimension` set `Location Shape Type`=%s where `Location Key`=%d"
			, prepare_mysql($value)
			, $this->id
		);
		//print $sql; exit;
		$this->db->exec($sql);
		$this->data['Location Shape Type']=$value;
		$this->new_value=$value;
		$this->new_data=array('old_value'=>$old_value, 'type'=>'shape' );
		$this->msg=_('Location shape changed');
		$this->updated=true;



	}


	function update_stock($value) {
		$value=_trim($value);
		if ($value==$this->data['Location Has Stock']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		if (!preg_match('/^(Yes|No|Unknown)$/', $value)) {
			$this->msg=_('Wrong location stock');
			$this->updated=false;
			return;
		}

		$old_value=$this->data['Location Has Stock'];
		$sql=sprintf("update `Location Dimension` set `Location Has Stock`=%s where `Location Key`=%d"
			, prepare_mysql($value)
			, $this->id
		);
		//print $sql; exit;
		$this->db->exec($sql);
		$this->data['Location Has Stock']=$value;
		$this->new_value=$value;
		$this->new_data=array('old_value'=>$old_value, 'type'=>'has_stock' );
		$this->msg=_('Location stock changed');
		$this->updated=true;



	}


	function update_parts() {
		$this->parts=array();

		$sql=sprintf("select `Part SKU`,sum(`Quantity On Hand`) as qty from `Part Location Dimension`  where `Location Key`=%d  group by `Part SKU`"
			, $this->id

		);



		$has_stock='No';



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				// $part=new part('sku',$row['Part SKU']);
				$this->parts[$row['Part SKU']]=array(
					// 'id'=>$part->id,
					'sku'=>$row['Part SKU'],
				);
				if (is_numeric($row['qty']) and $row['qty']>0)
					$has_stock='Yes';

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}







		$this->data['Location Distinct Parts']=count($this->parts);
		$this->data['Location has Stock']=$has_stock;
		$sql=sprintf("update `Location Dimension` set `Location Distinct Parts`=%d,`Location Has Stock`=%s where `Location Key`=%d"
			, $this->data['Location Distinct Parts']
			, prepare_mysql($this->data['Location has Stock'])
			, $this->id
		);
		$this->db->exec($sql);

	}


	function load($key='', $args=false) {
		switch ($key) {
		case('items'):
		case('parts'):
		case('part'):
			$this->update_parts();

			//  print "$sql\n";
			break;
		case('parts_data'):

			if (!$args)
				$date=date("Y-m-d");
			else
				$date=$args;

			$this->parts=array();
			$has_stock='No';
			$parts=0;

			$sql=sprintf("select count(`Part SKU`) as skus,sum(`Quantity On Hand`) as qty from `Inventory Spanshot Fact`  where `Location Key`=%d  and `Date`=%s group by `Part SKU`", $this->id, prepare_mysql($date));
			// print $sql;


			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {

					$parts++;
					if (is_numeric($row['qty']) and $row['qty']>0)
						$has_stock='Yes';

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}






			$this->data['Location Distinct Parts']=$parts;
			$this->data['Location has Stock']=$has_stock;
			$sql=sprintf("update `Location Dimension` set `Location Distinct Parts`=%d,`Location Has Stock`=%s where `Location Key`=%d"
				, $this->data['Location Distinct Parts']
				, prepare_mysql($this->data['Location has Stock'])
				, $this->id
			);
			//print "$sql\n";
			$this->db->exec($sql);
			break;

		}


	}


	function get($key) {


		if (!$this->id) {
			return '';
		}



		switch ($key) {
		case 'Mainly Used For':
		switch ($this->data['Location Mainly Used For']) {
		    case 'Picking':
		        return _('Picking');
		        break;
		          case 'Storing':
		        return _('Storing');
		        break;
		          case 'Loading':
		        return _('Loading');
		        break;
		          case 'Displaying':
		        return _('Displaying');
		        break;
		          case 'Other':
		        return _('Other');
		        break;
		          case 'Default':
		        return _('Default');
		        break;
		    default:
		        return $this->data['Lcoation Mainly Used For'];
		        break;
		}
		
		break;
		default:

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Location '.$key, $this->data))
				return $this->data['Location '.$key];



			if (preg_match('/^warehouse area/i', $key)) {
				if (!$this->warehouse_area)
					$warehouse_area=new WarehouseArea($this->data['Location Warehouse Area Key']);
				return $warehouse_area->get($key);
			}
			if (preg_match('/^warehouse/i', $key)) {
				if (!$this->warehouse)
					$warehouse=new Warehouse($this->data['Location Warehouse Key']);
				return $warehouse->get($key);
			}
			if (preg_match('/^shelf/i', $key)) {
				if (!$this->data['Location Shelf Key'])return false;
				if (!$this->shelf)
					$shelf=new Shelf($this->data['Location Shelf Key']);
				return $shelf->get($key);
			}
			return '';

		}



	}


	function get_file_as($StartCode) {

		$PaddingAmount=4;
		$s = preg_replace("/[^0-9]/", "-", $StartCode);

		for ($qq=0;$qq<10;$qq++) {
			$s = preg_replace("/--/", "-", $s);
		}



		$pieces = explode("-", $s);

		for ($qq=0;$qq<count($pieces);$qq++) {
			$ss =  str_pad( $pieces[$qq], $PaddingAmount, '0', STR_PAD_LEFT );
			if (strlen($pieces[$qq]) > 0) {
				$StartCode = preg_replace('/'.$pieces[$qq].'/', ';xyz;', $StartCode, 1);
				$arr_parts[$qq] = $ss;
			}

		}



		for ($qq=0;$qq<count($pieces);$qq++) {

			if (strlen($pieces[$qq]) > 0) {
				$ss =  $arr_parts[$qq];
				$StartCode = preg_replace('/;xyz;/', $ss, $StartCode, 1);
			}


		}


		return $StartCode;


	}


	function get_date($key='', $tipo='dt') {
		if (isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ) {

			switch ($tipo) {
			case('dt'):
			default:
				return strftime("%e %B %Y %H:%M", $porder['date_expected']);
			}
		} else
			return false;
	}


	function delete() {
		$this->deleted=false;
		$this->deleted_msg='';


		if ($this->id==1) {
			$this->deleted_msg='Error location unknown can not be deleted';
			return;
		}


		$sql=sprintf("update `Part Location Dimension` set `Location Key`=1 where `Location Key`=%d", $this->id);

		$this->db->exec($sql);




		$sql=sprintf("delete from `Location Dimension` where `Location Key`=%d", $this->id);
		$this->db->exec($sql);
		$this->deleted=true;


	}

function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Location Code':
			$label=_('code');
			break;

		case 'Location Mainly Used For':
			$label=_('used for');
			break;



		default:
			$label=$field;

		}

		return $label;

	}

}


?>
