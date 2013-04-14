<?php
/*
 File: Location.php

 This file contains the Location Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Part.php';
include_once 'class.Warehouse.php';
include_once 'class.WarehouseArea.php';
include_once 'class.Shelf.php';



class Location extends DB_Table {


	var $parts=false;
	var $warehouse=false;
	var $warehouse_area=false;
	var $shelf=false;

	function Location($arg1=false,$arg2=false,$arg3=false) {

		$this->table_name='Location';
		$this->ignore_fields=array('Location Key');

		if (preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)) {
			$this->create($arg2);
			return;
		}
		if (preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg3);
			return;
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}
		$this->get_data($arg1,$arg2);

	}



	/*
      Method: find
      Find Location with similar data
     */

	function find($raw_data,$options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}


		$this->found=false;
		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
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
			,$data['Location Warehouse Key']
			,prepare_mysql($data['Location Code']));

		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->found_key=$row['Location Key'];
		}

		//what to do if found
		if ($this->found) {
			$this->get_data('id',$this->found_key);
		}


		if ($create) {
			if ($this->found) {
				$this->update($data,$options);
			} else {

				$this->create($data,$options);

			}


		}
	}





	function create($data) {


		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=_trim($value);
		}
		$warehouse_area=new WarehouseArea($this->data['Location Warehouse Area Key']);
		if (!$warehouse_area->id) {
			$this->error=true;
			$this->msg='WA not found';
			return;

		}
		$warehouse=new Warehouse($this->data['Location Warehouse Key']);
		if (!$warehouse->id) {
			$this->error=true;
			$this->msg='W not found';
			return;
		}

		if ($warehouse->id!=$warehouse_area->data['Warehouse Key']) {
			$this->error=true;
			$this->msg='WA not in W';
			return;
		}

		if ($this->data['Location Code']=='') {
			$error=true;
			$this->msg=_('Wrong location code');
			return;
		}

		if (!preg_match('/^(Picking|Storing|Loading|Displaying|Other)$/i',$this->data['Location Mainly Used For'])) {
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
			$values.=prepare_mysql($value,$_mode).",";
		}

		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);

		$sql=sprintf("insert into `Location Dimension` %s %s",$keys,$values);
		//print "$sql\n";
		// exit;
		if (mysql_query($sql)) {
			$this->id= mysql_insert_id();

			$note=_('Location Created');
			$details=_('Location')." ".$this->data['Location Code']." "._('created');
			$history_data=array(
				'History Abstract'=>$note
				,'History Details'=>$details
				,'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;
			$this->get_data('id',$this->id);

			$warehouse->update_children();
			$warehouse_area->update_children();

			if ($data['Location Shelf Key']) {
				$shelf=new Shelf($data['Location Shelf Key']);
				if ($shelf->id)
					$shelf->update_children();
			}

		} else {
			exit($sql);
		}


	}

	function get_data($key,$tag) {


		$sql=sprintf("select * from `Location Dimension`");
		if ($key=='id')
			$sql.=sprintf("where `Location Key`=%d ",$tag);
		else if ($key=='name' or $key=='code')
				$sql.=sprintf("where  `Location Code`=%s ",prepare_mysql($tag));
			else
				return;


			$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Location Key'];
		else
			$this->msg=_('Location do not exist');



	}


	function xupdate($data,$options=false) {
		foreach ($data as $key =>$value)
			switch ($key) {
			case('name'):
				$name=_trim($value);

				if ($name=='') {
					$this->msg=_('Wrong location name');
					$this->updated=false;
					return;
				}

				if ($name==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->updated=false;
					return;
				}

				$location=new Location('name',$value);
				if ($location->id) {
					$this->msg=_('Name already exists');
					$this->updated=false;
					return;
				}
				$this->data['name']=$name;
				$this->msg=_('Location name change');
				$this->updated=true;
				break;


			case('deep'):
				$value=_trim($value);

				if (!is_numeric($value)) {
					$this->msg=_('The maximum deep for this location show be numeric');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The deep can not be negative');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The deep can not be zero');
					$this->updated=false;
					return;
				}
				if ($value==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->updated=false;
					return;
				}

				$this->data['max_height']=$value;
				$this->msg=_('Location deep changed');
				$this->updated=true;
				break;
			case('width'):
				$value=_trim($value);

				if (!is_numeric($value)) {
					$this->msg=_('The maximum width for this location show be numeric');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The width can not be negative');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The width can not be zero');
					$this->updated=false;
					return;
				}
				if ($value==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->updated=false;
					return;
				}

				$this->data['max_height']=$value;
				$this->msg=_('Location width changed');
				$this->updated=true;
				break;
			case('width'):
				$value=_trim($value);

				if (!is_numeric($value)) {
					$this->msg=_('The maximum width for this location show be numeric');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The width can not be negative');
					$this->updated=false;
					return;
				}
				if ($value < 0) {
					$this->msg=_('The width can not be zero');
					$this->updated=false;
					return;
				}
				if ($value==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->updated=false;
					return;
				}

				$this->data['max_height']=$value;
				$this->msg=_('Location width changed');
				$this->updated=true;
				break;
			case('max_products'):
				$value=_trim($value);

				if (!is_numeric($value) or $value<=0) {
					$value='';
				}
				if ($value==$this->get($tipo)) {
					$this->msg=_('Nothing to change');
					$this->updated=false;
					return;
				}


				$this->data['max_products']=$value;
				$this->msg=_('Location max_products changed');
				$this->updated=true;
				break;

			}


	}


	function update_code($value) {

		$code=_trim($value);

		if ($code=='') {
			$this->msg=_('Wrong location code');
			$this->updated=false;
			return;
		}

		if ($code==$this->data['Location Code']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}

		$sql=sprintf('select `Location Key` from `Location Dimension` where `Location Key`!=%d and `Location Code`=%s'
			,$this->id
			,prepare_mysql($value)
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->msg=_('Other location has this code');
			$this->updated=false;
			return;
		}
		$sql=sprintf("update `Location Dimension` set `Location Code`=%s where `Location Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		mysql_query($sql);

		$this->data['Location Code']=$code;
		$this->msg=_('Location code changed');
		$this->updated=true;


	}

	function update_field_switcher($field,$value,$options='') {
	
		switch ($field) {
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
			if (array_key_exists($field,$base_data)) {

				if ($value!=$this->data[$field]) {

					$this->update_field($field,$value,$options);
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
			mysql_query($sql);
			$this->msg=_('Location warehouse area changed');
			$this->updated=true;
			$this->new_value=array('code'=>$new_area->data['Warehouse Area Code'],'key'=>$new_area->id);

		}




	}

	/*
function update($key,$a1=false,$a2=false) {

//print_r($key);

foreach($key as $a=>$b){
	$_key=$a;
	$value=$b;
}

	$sql=sprintf("");

	switch ($_key){
	case 'used_for':
		$this->update_used_for($a1);
		break;

	case 'shape':
		$this->update_shape($a1);
		break;
	case 'has_stock':
		$this->update_stock($a1);
		break;
	}

}

*/

	function update_warehouse_flag_key($value) {


		$sql=sprintf("select `Warehouse Key`,`Warehouse Flag Color` from  `Warehouse Flag Dimension` where `Warehouse Flag Key`=%d",
			$value);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if ($row['Warehouse Key']!=$this->data['Location Warehouse Key']) {
				$this->error=true;
				$this->msg='flag key not in this warehouse';
				return;
			}

			$old_key=$this->data['Warehouse Flag Key'];

			$sql=sprintf("update `Location Dimension` set `Warehouse Flag Key`=%d ,`Warehouse Flag`=%s where `Location Key`=%d"
				,$value
				,prepare_mysql($row['Warehouse Flag Color'])
				,$this->id
			);
			mysql_query($sql);
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

	}

	function update_max_weight($value) {
		list($value,$original_units)=parse_weight($value);
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
			,$value
			,$this->id
		);
		mysql_query($sql);
		$this->data['Location Max Weight']=$value;
		$this->new_value=weight($this->data['Location Max Weight']);
		$this->msg=_('Location maximum weight changed');
		$this->updated=true;
	}
	function update_max_volume($value) {
		list($value,$original_units)=parse_volume($value);
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
			,$value
			,$this->id
		);
		mysql_query($sql);
		$this->data['Location Max Volume']=$value;
		$this->new_value=volume($this->data['Location Max Volume']);
		$this->msg=_('Location maximum volume changed');
		$this->updated=true;
	}

	function update_used_for($value) {
		$value=_trim($value);
		if ($value==$this->data['Location Mainly Used For']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		if (!preg_match('/^(Picking|Storing|Displaying|Loading|Other)$/',$value)) {
			$this->msg=_('Wrong location type');
			$this->updated=false;
			return;
		}

		$old_value=$this->data['Location Mainly Used For'];

		$sql=sprintf("update `Location Dimension` set `Location Mainly Used For`=%s where `Location Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		//print $sql; exit;
		mysql_query($sql);
		$this->data['Location Mainly Used For']=$value;
		$this->new_value=$value;
		$this->new_data=array('old_value'=>$old_value, 'type'=>'used_for' );
		$this->msg=_('Location type changed');
		$this->updated=true;



	}

	function update_shape($value) {
		$value=_trim($value);
		if ($value==$this->data['Location Shape Type']) {
			$this->msg=_('Nothing to change');
			$this->updated=false;
			return;
		}
		if (!preg_match('/^(Box|Cylinder|Unknown)$/',$value)) {
			$this->msg=_('Wrong location shape');
			$this->updated=false;
			return;
		}

		$old_value=$this->data['Location Shape Type'];
		$sql=sprintf("update `Location Dimension` set `Location Shape Type`=%s where `Location Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		//print $sql; exit;
		mysql_query($sql);
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
		if (!preg_match('/^(Yes|No|Unknown)$/',$value)) {
			$this->msg=_('Wrong location stock');
			$this->updated=false;
			return;
		}

		$old_value=$this->data['Location Has Stock'];
		$sql=sprintf("update `Location Dimension` set `Location Has Stock`=%s where `Location Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		//print $sql; exit;
		mysql_query($sql);
		$this->data['Location Has Stock']=$value;
		$this->new_value=$value;
		$this->new_data=array('old_value'=>$old_value, 'type'=>'has_stock' );
		$this->msg=_('Location stock changed');
		$this->updated=true;



	}

	function update_parts() {
		$sql=sprintf("select `Part SKU`,sum(`Quantity On Hand`) as qty from `Part Location Dimension`  where `Location Key`=%d  group by `Part SKU`"
			,$this->id

		);

		$this->parts=array();
		$result=mysql_query($sql);
		$has_stock='No';
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			// $part=new part('sku',$row['Part SKU']);
			$this->parts[$row['Part SKU']]=array(
				// 'id'=>$part->id,
				'sku'=>$row['Part SKU'],
			);
			if (is_numeric($row['qty']) and $row['qty']>0)
				$has_stock='Yes';
		}


		$this->data['Location Distinct Parts']=count($this->parts);
		$this->data['Location has Stock']=$has_stock;
		$sql=sprintf("update `Location Dimension` set `Location Distinct Parts`=%d,`Location Has Stock`=%s where `Location Key`=%d"
			,$this->data['Location Distinct Parts']
			,prepare_mysql($this->data['Location has Stock'])
			,$this->id
		);
		mysql_query($sql);

	}


	function load($key='',$args=false) {
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

			$sql=sprintf("select count(`Part SKU`) as skus,sum(`Quantity On Hand`) as qty from `Inventory Spanshot Fact`  where `Location Key`=%d  and `Date`=%s group by `Part SKU`",$this->id,prepare_mysql($date));
			// print $sql;

			$this->parts=array();
			$result=mysql_query($sql);
			$has_stock='No';
			$parts=0;
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$parts++;
				if (is_numeric($row['qty']) and $row['qty']>0)
					$has_stock='Yes';
			}


			$this->data['Location Distinct Parts']=$parts;
			$this->data['Location has Stock']=$has_stock;
			$sql=sprintf("update `Location Dimension` set `Location Distinct Parts`=%d,`Location Has Stock`=%s where `Location Key`=%d"
				,$this->data['Location Distinct Parts']
				,prepare_mysql($this->data['Location has Stock'])
				,$this->id
			);
			//print "$sql\n";
			mysql_query($sql);
			break;

		}


	}


	function get($key) {

		if (preg_match('/^warehouse area/i',$key)) {
			if (!$this->warehouse_area)
				$warehouse_area=new WarehouseArea($this->data['Location Warehouse Area Key']);
			return $warehouse_area->get($key);
		}
		if (preg_match('/^warehouse/i',$key)) {
			if (!$this->warehouse)
				$warehouse=new Warehouse($this->data['Location Warehouse Key']);
			return $warehouse->get($key);
		}
		if (preg_match('/^shelf/i',$key)) {
			if (!$this->data['Location Shelf Key'])return false;
			if (!$this->shelf)
				$shelf=new Shelf($this->data['Location Shelf Key']);
			return $shelf->get($key);
		}


		switch ($key) {
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
			else
				return '';

		}


	}

	function get_date($key='',$tipo='dt') {
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

		mysql_query($sql);




		$sql=sprintf("delete from `Location Dimension` where `Location Key`=%d",$this->id);
		mysql_query($sql);

		if (mysql_affected_rows()>0) {
			$this->deleted=true;
		} else {
			$this->deleted_msg='Error Location not found';
		}

	}


}

?>
