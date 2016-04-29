<?php
/*
 File: Warehouse.php

 This file contains the Warehouse Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.WarehouseArea.php';
include_once 'class.Location.php';

class Warehouse extends DB_Table{

	var $areas=false;
	var $locations=false;

	function Warehouse($a1, $a2=false, $a3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Warehouse';
		$this->ignore_fields=array('Warehouse Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}elseif ($a1=='find') {
			$this->find($a2, $a3);

		}else
			$this->get_data($a1, $a2);
	}


	function get_data($key, $tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Warehouse Dimension` where `Warehouse Key`=%d", $tag);
		else if ($key=='code')
			$sql=sprintf("select  * from `Warehouse Dimension` where `Warehouse Code`=%s ", prepare_mysql($tag));
		else if ($key=='name')
			$sql=sprintf("select  *  from `Warehouse Dimension` where `Warehouse Name`=%s ", prepare_mysql($tag));

		else
			return;


		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Warehouse Key'];
			$this->code=$this->data['Warehouse Code'];
		}



	}


	function get_flags_data() {
		$this->flags=array();
		$sql=sprintf("select * from `Warehouse Flag Dimension` where `Warehouse Key`=%d", $this->id);
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->flags[$row['Warehouse Flag Key']]=$row;
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


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data))
				$data[$key]=_trim($value);
		}



		if ($data['Warehouse Code']=='' ) {
			$this->error=true;
			$this->msg='Warehouse code empty';
			return;
		}

		if ($data['Warehouse Name']=='')
			$data['Warehouse Name']=$data['Warehouse Code'];




		$sql=sprintf("select `Warehouse Key` from `Warehouse Dimension` where `Warehouse Code`=%s  "
			, prepare_mysql($data['Warehouse Code'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Warehouse Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Warehouse Code';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		$sql=sprintf("select `Warehouse Key` from `Warehouse Dimension` where `Warehouse Name`=%s  "
			, prepare_mysql($data['Warehouse Name'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Warehouse Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Warehouse Name';
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
			if (preg_match('/^(Warehouse Address|Warehouse Company Name|Warehouse Company Number|Warehouse VAT Number|Warehouse Telephone|Warehouse Email)$/i', $key))
				$values.=prepare_mysql($value, false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Warehouse Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->msg=_("Warehouse Added");
			$this->get_data('id', $this->id);
			$this->new=true;


			if ( is_numeric($this->editor['User Key']) and $this->editor['User Key']>1) {

				$sql=sprintf("insert into `User Right Scope Bridge` values(%d,'Warehouse',%d)",
					$this->editor['User Key'],
					$this->id
				);
				$this->db->exec($sql);

			}


			$flags=array('Blue'=>_('Blue'), 'Green'=>_('Green'), 'Orange'=>_('Orange'), 'Pink'=>_('Pink'), 'Purple'=>_('Purple'), 'Red'=>_('Red'), 'Yellow'=>_('Yellow'));
			foreach ($flags as $flag=>$flag_label) {
				$sql=sprintf("INSERT INTO `Warehouse Flag Dimension` (`Warehouse Flag Key`, `Warehouse Key`, `Warehouse Flag Color`, `Warehouse Flag Label`, `Warehouse Flag Number Locations`, `Warehouse Flag Active`) VALUES (NULL, %d, %s,%s, '0', 'Yes')",
					$this->id,
					prepare_mysql($flag),
					prepare_mysql($flag_label)
				);

				$this->db->exec($sql);

			}


			return;
		}else {
			$this->msg=_(" Error can not create warehouse");
			print $sql;
			exit;
		}
	}




	function get($key, $data=false) {

		if (!$this->id) {
			return '';
		}



		switch ($key) {
		case('num_areas'):
		case('number_areas'):
			if (!$this->areas)
				$this->load('areas');
			return count($this->areas);
			break;
		case('areas'):
			if (!$this->areas)
				$this->load('areas');
			return $this->areas;
			break;
		case('area'):
			if (!$this->areas)
				$this->load('areas');
			if (isset($this->areas[$data['id']]))
				return $this->areas[$data['id']];
			break;
		default:




			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Warehouse '.$key, $this->data))
				return $this->data['Warehouse '.$key];


			if (preg_match('/(Warehouse )?Flag Label (.+)$/', $key, $match)) {

				if (isset($this->flags[$match[2]])) {
					return $this->flags[$match[2]]['Warehouse Flag Label'];
				}
			}


		}
		return '';
	}


	function update_field_switcher($field, $value, $options='', $metadata='') {


		if ($this->deleted)return;

		switch ($field) {
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field, $value, $options);
				}
			}
			
			if (preg_match('/(Warehouse Flag Label) (.+)$/', $field, $match)) {


$this->update_flag($match[2], $match[1], $value);

				
			}

			
			
		}
	}




	function add_area($data) {
		// print_r($data);
		$this->new_area=false;
		$data['Warehouse Key']=$this->id;
		$area= new WarehouseArea('find', $data, 'create');
		$this->new_area_msg=$area->msg;
		if ($area->new) {
			$this->new_area=true;
			$this->new_area_key=$area->id;
		}
	}


	function update_children() {
		$sql=sprintf('select count(*) as number from `Location Dimension` where `Location Warehouse Key`=%d', $this->id);
		$number_locations=0;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_locations=$row['number'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf('select count(*) as number from `Shelf Dimension` where `Shelf Warehouse Key`=%d', $this->id);
		$number_shelfs=0;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_shelfs=$row['number'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf('select count(*) as number from `Warehouse Area Dimension` where `Warehouse Key`=%d', $this->id);
		$number_areas=0;

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_areas=$row['number'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf('update `Warehouse Dimension` set `Warehouse Number Locations`=%d ,`Warehouse Number Shelfs`=%d  ,`Warehouse Number Areas`=%d  where `Warehouse Key`=%d'
			, $number_locations
			, $number_shelfs
			, $number_areas
			, $this->id
		);
		$this->db->exec($sql);
		$this->get_data('id', $this->id);
	}


	function update_inventory_snapshot($from, $to=false) {

		if (!$to) {
			$to=$from;
		}

		$sql=sprintf("select `Date`  from kbase.`Date Dimension` where `Date`>=%s and `Date` <= %s  ",
			prepare_mysql($from),
			prepare_mysql($to)
		);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				$dormant_1y_open_value_at_day=0;

				$sql=sprintf('select ITF.`Part SKU`,`Part Cost`,`Value At Day Cost` from `Inventory Spanshot Fact` ITF left join `Part Dimension` P  on (P.`Part SKU`=ITF.`Part SKU`)where `Warehouse Key`=%d and `Date`=%s and `Value At Day Cost`!=0 and `Part Valid From`>%s',
					$this->id,
					prepare_mysql($row['Date']),
					prepare_mysql(date("Y-m-d H:i:s", strtotime($row['Date'].' 23:59:59 -1 year')))
				);


				if ($result2=$this->db->query($sql)) {
					foreach ($result2 as $row2) {

						$sql=sprintf("select count(*) as num from `Inventory Transaction Fact` where `Part SKU`=%d and  `Inventory Transaction Type`='Sale' and `Date`>=%s and `Date`<=%s ",
							$row2['Part SKU'],
							prepare_mysql(date("Y-m-d H:i:s", strtotime($row['Date'].' 23:59:59 -1 year'))),
							prepare_mysql($row['Date'].' 23:59:59')
						);


						if ($result3=$this->db->query($sql)) {
							if ($row3 = $result3->fetch()) {
								if ($row3['num']==0) {
									$dormant_1y_open_value_at_day+=$row2['Value At Day Cost'];
								}
							}
						}else {
							print_r($error_info=$this->db->errorInfo());
							exit;
						}





					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}




				$sql=sprintf("select `Date`,
			count(DISTINCT `Part SKU`) as parts,`Date`, count(DISTINCT `Location Key`) as locations,
			sum(`Value At Cost Open`) as open ,sum(`Value At Cost High`) as high,sum(`Value At Cost Low`) as low,sum(`Value At Cost`) as close ,
			sum(`Value At Day Cost Open`) as open_value_at_day ,sum(`Value At Day Cost High`) as high_value_at_day,sum(`Value At Day Cost Low`) as low_value_at_day,sum(`Value At Day Cost`) as close_value_at_day,
			sum(`Value Commercial Open`) as open_commercial_value ,sum(`Value Commercial High`) as high_commercial_value,sum(`Value Commercial Low`) as low_commercial_value,sum(`Value Commercial`) as close_commercial_value
			from `Inventory Spanshot Fact` where `Warehouse Key`=%d and `Date`=%s",
					$this->id,
					prepare_mysql($row['Date'])

				);


				if ($result2=$this->db->query($sql)) {
					if ($row2= $result2->fetch()) {






						$sql=sprintf("insert into `Inventory Warehouse Spanshot Fact` (`Date`,`Warehouse Key`,`Parts`,`Locations`,
				`Value At Cost`,`Value At Day Cost`,`Value Commercial`,`Value At Cost Open`,`Value At Cost High`,`Value At Cost Low`,`Value At Day Cost Open`,`Value At Day Cost High`,`Value At Day Cost Low`,
				`Value Commercial Open`,`Value Commercial High`,`Value Commercial Low`,`Dormant 1 Year Value At Day Cost`

				) values (%s,%d,%.2f,%.2f,%.2f, %f,%f,%f,%f,%f,%f,%f,%f,%f,%d,%d,%.2f) ON DUPLICATE KEY UPDATE
					`Value At Cost`=%.2f, `Value At Day Cost`=%.2f,`Value Commercial`=%.2f,
			`Value At Cost Open`=%f,`Value At Cost High`=%f,`Value At Cost Low`=%f,
			`Value At Day Cost Open`=%f,`Value At Day Cost High`=%f,`Value At Day Cost Low`=%f,
			`Value Commercial Open`=%f,`Value Commercial High`=%f,`Value Commercial Low`=%f,
			`Parts`=%d,`Locations`=%d,`Dormant 1 Year Value At Day Cost`=%.2f
			",
							prepare_mysql($row['Date']),

							$this->id,
							$row2['parts'],
							$row2['locations'],

							$row2['close'],
							$row2['close_value_at_day'],
							$row2['close_commercial_value'],
							$row2['open'],
							$row2['high'],
							$row2['low'],

							$row2['open_value_at_day'],
							$row2['high_value_at_day'],
							$row2['low_value_at_day'],
							$row2['open_commercial_value'],
							$row2['high_commercial_value'],
							$row2['low_commercial_value'],
							$dormant_1y_open_value_at_day,

							$row2['close'],
							$row2['close_value_at_day'],
							$row2['close_commercial_value'],
							$row2['open'],
							$row2['high'],
							$row2['low'],

							$row2['open_value_at_day'],
							$row2['high_value_at_day'],
							$row2['low_value_at_day'],
							$row2['open_commercial_value'],
							$row2['high_commercial_value'],
							$row2['low_commercial_value'],
							$row2['parts'],
							$row2['locations'],
							$dormant_1y_open_value_at_day


						);
						$this->db->exec($sql);

						// print "$sql\n";




					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}





			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



	}


	function update_location_flags_numbers() {


		$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Key`=%d  ", $this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->update_location_flag_number($row['Warehouse Flag Key']);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



	}


	function update_location_flag_number($flag_key) {
		$num=0;
		$sql=sprintf("select count(*) as num  from  `Location Dimension` where `Warehouse Flag Key`=%d ", $flag_key);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$num=$row['num'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("update  `Warehouse Flag Dimension`  set `Warehouse Flag Number Locations`=%d where `Warehouse Flag Key`=%d ",
			$num,
			$flag_key);
		$this->db->exec($sql);


	}


	function get_default_flag_key() {
		$flag_key=0;
		$sql=sprintf("select `Warehouse Flag Key` from  `Warehouse Flag Dimension` where `Warehouse Flag Color`=%s and `Warehouse Key`=%d",
			prepare_mysql($this->data['Warehouse Default Flag Color']),
			$this->id
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$flag_key=$row['Warehouse Flag Key'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $flag_key;

	}


	function update_flag($flag_key, $field, $value) {

		if (in_array($field, array('Warehouse Flag Label', 'Warehouse Flag Active'))) {


			$sql=sprintf("select * from  `Warehouse Flag Dimension` where  `Warehouse Flag Key`=%d and `Warehouse Key`=%d",
				$flag_key,
				$this->id
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {


					$default_flag_key=$this->get_default_flag_key();
					if ($default_flag_key==$value and $field=='Warehouse Flag Active' and $value=='No') {
						$this->error=true;
						$this->msg='can not disable default flag';
					}




					$sql=sprintf("update  `Warehouse Flag Dimension`  set `%s`=%s where `Warehouse Flag Key`=%d ",
						$field,
						prepare_mysql($value),
						$flag_key

					);
					$this->db->exec($sql);


					if ($field=='Warehouse Flag Active' and $value=='No') {
						$sql=sprintf("select `Location Key` from `Location Dimension` where `Location Warehouse Key`=%d and `Warehouse Flag Key`=%d",
							$this->id,
							$row['Warehouse Flag Key']

						);

						if ($result2=$this->db->query($sql)) {
							foreach ($result2 as $row2) {
								$location=new Location($row2['Location Key']);
								$location->update_warehouse_flag_key($default_flag_key);
							}
						}else {
							print_r($error_info=$this->db->errorInfo());
							exit;
						}




					}


					$this->updated=true;
					$this->new_value=$value;

                    $this->get_flags_data();

				}else {
					$this->error=true;
					$this->msg='unknown flag';
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}





		}else {
			$this->error=true;
			$this->msg='unknown field';
		}

	}


	function create_part($data) {
		$this->new_part=false;

		$data['editor']=$this->editor;


		if (!isset($data['Part From'])) {
			$data['Part From']=gmdate('Y-m-d H:i:s');
		}




		$part= new Part('find', $data, 'create');

		if ($part->id) {
			$this->new_part_msg=$part->msg;

			if ($part->new) {
				$this->new_part=true;


				$sql=sprintf("insert into `Part Warehouse Bridge` (`Part SKU`,`Warehouse Key`) values (%d,%d)", $part->sku, $this->id);

				$this->db->exec($sql);

				return $part;
			} else {
				$this->error=true;
				if ($part->found) {
					$this->msg=_('Duplicated part');
				}else {
					$this->msg='Error '.$part->msg;
				}
			}
			return false;
		}
		else {
			$this->error=true;
			$this->msg='Error '.$part->msg;
			return false;
		}
	}



	function get_field_label($field) {

		switch ($field) {

		case 'Warehouse Code':
			$label=_('code');
			break;
		case 'Warehouse Name':
			$label=_('name');
			break;
		case 'Warehouse Address':
			$label=_('address');
			break;

		default:

			if (preg_match('/Warehouse Flag Label (.+)$/', $field, $match)) {
				$label='<i class="fa fa-flag '.strtolower($match[1]).'" aria-hidden="true"></i> ' ._($match[1]) ;
				return $label;
			}

			$label=$field;

		}

		return $label;

	}


}


?>
