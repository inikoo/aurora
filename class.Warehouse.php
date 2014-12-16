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

	function Warehouse($a1,$a2=false,$a3=false) {

		$this->table_name='Warehouse';
		$this->ignore_fields=array('Warehouse Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		}elseif ($a1=='find') {
			$this->find($a2,$a3);

		}else
			$this->get_data($a1,$a2);
	}


	function get_data($key,$tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Warehouse Dimension` where `Warehouse Key`=%d",$tag);
		else if ($key=='code')
				$sql=sprintf("select  * from `Warehouse Dimension` where `Warehouse Code`=%s ",prepare_mysql($tag));
			else if ($key=='name')
					$sql=sprintf("select  *  from `Warehouse Dimension` where `Warehouse Name`=%s ",prepare_mysql($tag));

				else
					return;
				$result=mysql_query($sql);
			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->id=$this->data['Warehouse Key'];
				$this->code=$this->data['Warehouse Code'];

			}




	}

	function find($raw_data,$options) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);
		}


		//    print_r($raw_data);

		if ($data['Warehouse Code']=='' ) {
			$this->error=true;
			$this->msg='Warehouse code empty';
			return;
		}

		if ($data['Warehouse Name']=='')
			$data['Warehouse Name']=$data['Warehouse Code'];


		$sql=sprintf("select `Warehouse Key` from `Warehouse Dimension` where `Warehouse Code`=%s  "
			,prepare_mysql($data['Warehouse Code'])
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Warehouse Key'];
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}
		if ($this->found)
			$this->get_data('id',$this->found_key);

		if ($update and $this->found) {

		}


	}

	function create($data) {
		$this->new=false;
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Warehouse Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Warehouse Added");
			$this->get_data('id',$this->id);
			$this->new=true;


			if ( is_numeric($this->editor['User Key']) and $this->editor['User Key']>1) {

				$sql=sprintf("insert into `User Right Scope Bridge` values(%d,'Warehouse',%d)",
					$this->editor['User Key'],
					$this->id
				);
				mysql_query($sql);

			}


			$flags=array('Blue'=>_('Blue'),'Green'=>_('Green'),'Orange'=>_('Orange'),'Pink'=>_('Pink'),'Purple'=>_('Purple'),'Red'=>_('Red'),'Yellow'=>_('Yellow'));
			foreach ($flags as $flag=>$flag_label) {
				$sql=sprintf("INSERT INTO `Warehouse Flag Dimension` (`Warehouse Flag Key`, `Warehouse Key`, `Warehouse Flag Color`, `Warehouse Flag Label`, `Warehouse Flag Number Locations`, `Warehouse Flag Active`) VALUES (NULL, %d, %s,%s, '0', 'Yes')",
					$this->id,
					prepare_mysql($flag),
					prepare_mysql($flag_label)
				);

				mysql_query($sql);

			}


			return;
		}else {
			$this->msg=_(" Error can not create warehouse");
		}
	}




	function xupdate($data) {
		foreach ($data as $key =>$value)
			switch ($key) {
			case('name'):
				$name=_trim($value);

				if ($name=='') {
					$this->msg=_('Wrong warehouse name');
					$this->update_ok=false;
					return;
				}

				if ($name==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->update_ok=false;
					return;
				}

				$location=new Warehouse('name',$value);
				if ($location->id) {
					$this->msg=_('Another ware house has the same name');
					$this->update_ok=false;
					return;
				}
				$this->data['name']=$name;
				$this->msg=_('Warehouse name changed');
				$this->update_ok=true;
				break;
			}


	}


	function load($key='') {
		switch ($key) {
		case('areas'):
			$sql=sprintf("select * from `Warehouse Area Dimension` where `Warehouse Key`=%d ",$this->id);

			$result =mysql_query($sql);
			$this->areas=array();
			while ($row=mysql_fetch_array($result)) {
				$this->areas[$row['id']]=array(
					'id'=>$row['`Warehouse Area Key`'],
					'code'=>$row['Warehouse Area Code'],
				);
			}
			break;

		}


	}


	function get($key,$data=false) {
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

			if (array_key_exists($key,$this->data))
				return $this->data[$key];
			else {

				return $key;
			}
		}
		return '';
	}


	function add_area($data) {
		// print_r($data);
		$this->new_area=false;
		$data['Warehouse Key']=$this->id;
		$area= new WarehouseArea('find',$data,'create');
		$this->new_area_msg=$area->msg;
		if ($area->new) {
			$this->new_area=true;
			$this->new_area_key=$area->id;
		}
	}

	function update_children() {
		$sql=sprintf('select count(*) as number from `Location Dimension` where `Location Warehouse Key`=%d',$this->id);
		$res=mysql_query($sql);
		$number_locations=0;
		if ($row=mysql_fetch_array($res)) {
			$number_locations=$row['number'];
		}

		$sql=sprintf('select count(*) as number from `Shelf Dimension` where `Shelf Warehouse Key`=%d',$this->id);
		$res=mysql_query($sql);
		$number_shelfs=0;
		if ($row=mysql_fetch_array($res)) {
			$number_shelfs=$row['number'];
		}

		$sql=sprintf('select count(*) as number from `Warehouse Area Dimension` where `Warehouse Key`=%d',$this->id);
		$res=mysql_query($sql);
		$number_areas=0;
		if ($row=mysql_fetch_array($res)) {
			$number_areas=$row['number'];
		}


		$sql=sprintf('update `Warehouse Dimension` set `Warehouse Number Locations`=%d ,`Warehouse Number Shelfs`=%d  ,`Warehouse Number Areas`=%d  where `Warehouse Key`=%d'
			,$number_locations
			,$number_shelfs
			,$number_areas
			,$this->id
		);
		mysql_query($sql);
		$this->get_data('id',$this->id);
	}

	function update_inventory_snapshot($from,$to=false) {

		if (!$to) {
			$to=$from;
		}

		$sql=sprintf("select `Date`  from kbase.`Date Dimension` where `Date`>=%s and `Date` <= %s  ",
			prepare_mysql($from),
			prepare_mysql($to)
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$dormant_1y_open_value_at_day=0;

			$sql=sprintf('select ITF.`Part SKU`,`Part Cost`,`Value At Day Cost` from `Inventory Spanshot Fact` ITF left join `Part Dimension` P  on (P.`Part SKU`=ITF.`Part SKU`)where `Warehouse Key`=%d and `Date`=%s and `Value At Day Cost`!=0 and `Part Valid From`>%s',
				$this->id,
				prepare_mysql($row['Date']),
				prepare_mysql(date("Y-m-d H:i:s",strtotime($row['Date'].' 23:59:59 -1 year'))),
			);
			$res2=mysql_query($sql);

			while ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("select count(*) as num from `Inventory Transaction Fact` where `Part SKU`=%d and  `Inventory Transaction Type`='Sale' and `Date`>=%s and `Date`<=%s ",
					$row2['Part SKU'],
					prepare_mysql(date("Y-m-d H:i:s",strtotime($row['Date'].' 23:59:59 -1 year'))),
					prepare_mysql($row['Date'].' 23:59:59')
				);
				
				$res3=mysql_query($sql);
				if ($row3=mysql_fetch_array($res3)) {
					if ($row3['num']==0) {
						$dormant_1y_open_value_at_day+=$row2['Value At Day Cost'];
					}
				}

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




			$res2=mysql_query($sql);

			if ($row2=mysql_fetch_assoc($res2)) {





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
				mysql_query($sql);

			//	print "$sql\n";



			}

		}

	}

	function update_location_flags_numbers() {


		$sql=sprintf("select * from  `Warehouse Flag Dimension` where `Warehouse Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$this->update_location_flag_number($row['Warehouse Flag Key']);

		}
	}

	function update_location_flag_number($flag_key) {
		$num=0;
		$sql=sprintf("select count(*) as num  from  `Location Dimension` where `Warehouse Flag Key`=%d ",$flag_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$num=$row['num'];

		}
		$sql=sprintf("update  `Warehouse Flag Dimension`  set `Warehouse Flag Number Locations`=%d where `Warehouse Flag Key`=%d ",
			$num,
			$flag_key);
		mysql_query($sql);


	}

	function get_default_flag_key() {
		$flag_key=0;
		$sql=sprintf("select `Warehouse Flag Key` from  `Warehouse Flag Dimension` where `Warehouse Flag Color`=%s and `Warehouse Key`=%d",
			prepare_mysql($this->data['Warehouse Default Flag Color']),
			$this->id
		);


		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$flag_key=$row['Warehouse Flag Key'];
		}
		return $flag_key;

	}

	function update_flag($flag_key,$field,$value) {

		if (in_array($field,array('Warehouse Flag Label','Warehouse Flag Active'))) {


			$sql=sprintf("select * from  `Warehouse Flag Dimension` where  `Warehouse Flag Key`=%d and `Warehouse Key`=%d",
				$flag_key,
				$this->id
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {

				$default_flag_key=$this->get_default_flag_key();
				if ($default_flag_key==$value and $field=='Warehouse Flag Active' and $value=='No') {
					$this->error=true;
					$this->msg='can not disable defaukt flag';
				}




				$sql=sprintf("update  `Warehouse Flag Dimension`  set `%s`=%s where `Warehouse Flag Key`=%d ",
					$field,
					prepare_mysql($value),
					$flag_key

				);
				mysql_query($sql);


				if ($field=='Warehouse Flag Active' and $value=='No') {
					$sql=sprintf("select `Location Key` from `Location Dimension` where `Location Warehouse Key`=%d and `Warehouse Flag Key`=%d",
						$this->id,
						$row['Warehouse Flag Key']

					);
					//print $sql;
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_assoc($res2)) {
						$location=new Location($row2['Location Key']);
						$location->update_warehouse_flag_key($default_flag_key);
					}

				}


				$this->updated=true;
				$this->new_value=$value;


			}else {
				$this->error=true;
				$this->msg='unknown flag';
			}



		}else {
			$this->error=true;
			$this->msg='unknown field';
		}

	}




}
?>
