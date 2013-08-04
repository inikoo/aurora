<?php
/*
 File: PartLocation.php

 This file contains the PartLocation Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'class.Part.php';
include_once 'class.Location.php';
include_once 'class.InventoryAudit.php';
class PartLocation extends DB_Table {

	var $ok=false;

	function PartLocation($arg1=false,$arg2=false,$arg3=false) {

		$this->table_name='Part Location';

		if (is_array($arg1)) {
			$data=$arg1;
			if (isset($data['LocationPart'])) {
				$tmp=split("_",$data['LocationPart']);
				$this->location_key=$tmp[1];
				$this->part_sku=$tmp[2];

			} else {
				//print "---- $data   --------\n";
				$this->location_key=$data['Location Key'];
				$this->part_sku=$data['Part SKU'];
			}
			$this->date=date("Y-m-d");
		} else {

			if ($arg1=='find') {
				$this->find($arg2,$arg3);
				return;
			}
			elseif (is_numeric($arg1) and is_numeric($arg2)) {
				$this->part_sku=$arg1;
				$this->location_key=$arg2;
				$this->get_data();
				return;

			}
			else {


				$tmp=preg_split("/\_/",$arg1);
				if (count($tmp)==2) {
					$this->part_sku=$tmp[0];
					$this->location_key=$tmp[1];
					$this->get_data();
				}
				return;
			}
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
			$_key=$key;
			$data[$_key]=$val;
		}

		$this->location=new Location($data['Location Key']);
		if (!$this->location->id) {

			$this->location=new Location(1);
			if (!$this->location->id) {
				$sql="INSERT INTO `Location Dimension` (`Location Key` ,`Location Warehouse Key` ,`Location Warehouse Area Key` ,`Location Code` ,`Location Mainly Used For` ,`Location Max Weight` ,`Location Max Volume` ,`Location Max Slots` ,`Location Distinct Parts` ,`Location Has Stock` ,`Location Stock Value`)VALUES ('1', '1', '1','Unknown', 'Picking', NULL , NULL , NULL , '0', 'Unknown', '0.00');";
				mysql_query($sql);
				$this->location=new Location(1);
				$this->new=true;

			}

		}
		$this->location_key=$this->location->id;
		$data['Part Location Warehouse Key']=$this->location->data['Location Warehouse Key'];

		$this->part=new Part($data['Part SKU']);
		if (!$this->part->id) {
			$this->error=true;
			$this->msg=_('Part not found');
		} else
			$this->part_sku=$this->part->sku;

		$sql=sprintf("select `Location Key`,`Part SKU` from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d"
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);




		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->get_data();
		}

		if ($create and !$this->found)
			$this->create($data,$options);

		if ($update and $this->found)
			$this->update($data,$options);




	}

	function get_data() {
		$this->current=false;
		$sql=sprintf("select * from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d",$this->part_sku,$this->location_key);
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->ok=true;
			$this->current=true;

		}

		$this->part=new Part($this->part_sku);
		$this->location=new Location($this->location_key);

	}

	function last_inventory_date() {
		$sql=sprintf("select `Date` from `Inventory Spanshot Fact` where  `Part Sku`=%d   order by `Date` desc limit 1",$this->part_sku);
		//   print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			return $row['Date'];
		} else
			return false;

	}

	function first_inventory_transacion() {
		$sql=sprintf("select DATE(`Date`) as Date from `Inventory Transaction Fact`
                     where  `Part Sku`=%d and (`Inventory Transaction Type`='Associate' )  order by `Date`",$this->part_sku);
		$result=mysql_query($sql);
		// print $sql;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			return $row['Date'];
		} else
			return false;

	}

	function last_inventory_audit() {
		$sql=sprintf("select DATE(`Date`) as Date from `Inventory Transaction Fact` where  `Part Sku`=%d and  `Location Key`=%d and (`Inventory Transaction Type`='Audit' or `Inventory Transaction Type`='Not Found' )  order by `Date` desc",$this->part_sku,$this->location_key);
		$result=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			return $row['Date'];
		} else
			return false;

	}

	function update_can_pick($value) {


		// Note:Inverse Translation InvT
		if (preg_match('/^(yes|si)$/i',$value))
			$value='Yes';
		else
			$value='No';
		$sql=sprintf("update `Part Location Dimension` set `Can Pick`=%s ,`Last Updated`=NOW() where `Part SKU`=%d and `Location Key`=%d "
			,prepare_mysql($value)
			,$this->part_sku
			,$this->location_key
		);
		//print $sql;
		if (mysql_query($sql)) {
			$this->updated=true;
			$this->data['Can Pick']=$value;
			$this->part->update_picking_location();
		}



	}
	function update_min($value) {

		if (!is_numeric($value) or $value<0) {
			$value='';
		}

		if ($value!='') {

			$sql=sprintf("select `Maximum Quantity` from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d "
				,$this->part_sku
				,$this->location_key
			);
			$result=mysql_query($sql);
			if ($row=mysql_fetch_assoc($result)) {

				if (is_numeric($row['Maximum Quantity'])) {
					if ($row['Maximum Quantity'] < $value || $value<0) {
						$this->updated=false;
						$this->error=true;
						$this->msg=_('Minimum quantity has to be lower than the maximum quantity');
						return;
					}
				}
			}
		}

		$sql=sprintf("update `Part Location Dimension` set `Minimum Quantity`=%s where `Part SKU`=%d and `Location Key`=%d "
			,prepare_mysql($value)
			,$this->part_sku
			,$this->location_key
		);
		//print $sql;
		if (mysql_query($sql)) {
			$this->updated=true;
			$this->data['Minimum Quantity']=$value;
			//$this->part->update_picking_location();
		}



	}

	function update_max($value) {

		if (!is_numeric($value) or $value<0) {
			$value='';
		}

		if ($value!='') {

			$sql=sprintf("select `Minimum Quantity` from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d "
				,$this->part_sku
				,$this->location_key
			);
			
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_assoc($result)) {
				if (is_numeric($row['Minimum Quantity'])) {
					if ($row['Minimum Quantity'] > $value) {
						$this->updated=false;
						$this->error=true;
						$this->msg='Maximum quantity has to be greater than minimum quantity';
						return;
					}
				}
			}
		}

		$sql=sprintf("update `Part Location Dimension` set `Maximum Quantity`=%s where `Part SKU`=%d and `Location Key`=%d "
			,prepare_mysql($value)
			,$this->part_sku
			,$this->location_key
		);
		//print $sql;
		if (mysql_query($sql)) {
			$this->updated=true;
			$this->data['Maximum Quantity']=$value;
			//$this->part->update_picking_location();
		}



	}

	function update_move_qty($value) {


		if (!is_numeric($value) or $value<=0) {
			$value='';

		}

		$sql=sprintf("update `Part Location Dimension` set `Moving Quantity`=%s where `Part SKU`=%d and `Location Key`=%d "
			,prepare_mysql($value)
			,$this->part_sku
			,$this->location_key
		);
		//print $sql;
		if (mysql_query($sql)) {
			$this->updated=true;
			$this->data['Moving Quantity']=$value;
		}



	}




	function audit($qty,$note='',$date=false,$include_current=false,$parent='') {

		if (!$date) {
			$historic=true;
			$date=gmdate('Y-m-d H:i:s');
		}else {
			$historic=false;

		}

		if (!is_numeric($qty) or $qty<0) {
			$this->error=true;
			$this->msg=_('Quantity On Hand should be a number');
		}




		$sql=sprintf("select sum(ifnull(`Inventory Transaction Quantity`,0)) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  `Date`<".($include_current?'=':'')."%s and `Part SKU`=%d and `Location Key`=%d"
			,prepare_mysql($date)
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		$old_qty=0;
		$old_value=0;

		if ($row=mysql_fetch_array($res)) {
			$old_qty=round($row['stock'],3);
			$old_value=$row['value'];
		}

		//$unit_cost=$this->get_unit_value($date);
		//$value=$qty*$unit_cost;

		$qty_change=$qty-$old_qty;


		$value_change=$this->get_value_change($qty_change,$old_qty,$old_value,$date);

		$this->updated=true;

		if ($note) {
			$details='<b>'.$note.'</b>, ';

		}else {
			$details='<b>'._('Audit').'</b>, ';
		}
		if ($this->location->id)
			$location_link='<a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>';
		else
			$location_link='<span style="font-style:italic">'._('deleted').'</span>';
		if ($parent=='associate') {
			$details.='<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' &#8692; '.$location_link;
		}else if ($parent=='disassociate') {
				$details.='<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' &#8603; '.$location_link;
			}else {
			$details.='<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('stock in').' '.$location_link.' '._('set to').': <b>'.number($qty).'</b>';
		}

		$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Part Location Stock`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%f)"
			,$this->part_sku
			,$this->location_key
			,"'Audit'"
			,0
			,0
			,$this->editor['User Key']
			,prepare_mysql($details,false)
			,prepare_mysql($date)
			,$qty

		);
		//print $sql;
		mysql_query($sql);
		$audit_key=mysql_insert_id();



		if ($qty_change!=0 or $value_change!=0) {



			$details='Audit: <b>['.number($qty).']</b> <a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('adjust quantity').' '.$location_link.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
			if ($note) {
				$details.=', '.$note;

			}

			$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`) values (%d,%d,%s,%f,%.3f,%s,%s,%s,%s)"
				,$this->part_sku
				,$this->location_key
				,"'Adjust'"
				,$qty_change
				,$value_change
				,$this->editor['User Key']
				,prepare_mysql($details,false)
				,prepare_mysql($date)
				,prepare_mysql($audit_key)
			);

			mysql_query($sql);
		}



		$this->update_stock();

		return $audit_key;



	}



	function get_selling_price($part_sku,$date) {


		$sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`>=%s and  `Product Valid From`<=%s    ",prepare_mysql($part_sku),prepare_mysql($date),prepare_mysql($date));
		// print "\n\n\n\n$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (is_numeric($row['cost']))
				return $row['cost'];
		}


		$sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  and `Product Valid To`<=%s limit 1 ",prepare_mysql($part_sku),prepare_mysql($date));

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (is_numeric($row['cost']))
				return $row['cost'];
		}

		$sql=sprintf(" select AVG(PD.`Product Price` * PPL.`Parts Per Product`) as cost from `Product Dimension` PD left join `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%s  order by  `Product Valid To` desc ",prepare_mysql($part_sku),prepare_mysql($date));
		//   print "\n\n\n\n$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			if (is_numeric($row['cost']))
				return $row['cost'];
		}


		exit("error can no found product last selling  ciost\n");


	}

	function create($data) {

		//print_r($data);

		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=_trim($value);
		}


		$location=new Location($this->data['Location Key']);

		if ($location->data['Location Mainly Used For']=='Picking')
			$this->data['Can Pick']='Yes';
		else
			$this->data['Can Pick']='No';



		$keys='(';
		$values='values(';
		foreach ($this->data as $key=>$value) {
			$keys.="`$key`,";
			$_mode=true;
			if ($key=='Last Updated')
				$values.='NOW(),';
			else
				$values.=prepare_mysql($value,$_mode).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Part Location Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id= mysql_insert_id();
			$this->new=true;

			$this->part_sku=$this->data['Part SKU'];
			$this->location_key=$this->data['Location Key'];
			$this->get_data();
			$note=_('Part added to location');
			$details=_('Part')." ".'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('associated with location').": <a href='location.php?id=".$this->location->id."'>".$this->location->data['Location Code'].'</a>';


			//$date=date("Y-m-d H:i:s");

			//print_r($this->editor);
			if (array_key_exists('Date',$data))
				$date=$data['Date'];
			elseif (!$this->editor['Date'])
				$date=gmdate("Y-m-d H:i:s");
			else
				$date=$this->editor['Date'];

			$associate_data=array('date'=>$date);
			$this->associate($associate_data);




			$this->new=true;
			$part=new Part($this->part_sku);
			$part->load('locations');
			$location=new Location($this->location_key);
			$location->load('parts');

		} else {
			exit($sql);
		}

	}



	function delete() {
		$this->disassociate();
	}

	function get_unit_value($date=false) {







		if (!$date) {
			return $this-> get_current_unit_value();

		}


		list($qty,$value,$in_process)=$this->get_stock($date);

		//print "$qty,$value,$in_process ***";


		if (is_numeric($value) and is_numeric($qty) and   $qty!=0   ) {
			return $value/$qty;
		}else {
			return $this->part->get('Unit Cost',$date);
		}



	}


	function get_current_unit_value() {
		$qty=$this->data['Quantity On Hand'];
		$value=$this->data['Stock Value'];



		if (is_numeric($value) and is_numeric($qty) and   $qty!=0   ) {
			return $value/$qty;
		}else {
			return $this->part->get('Unit Cost',$date);
		}

	}



	function identify_unknown($location_key) {
		if ($this->location_key!=1) {
			$this->error=true;
			return;
		}
		$old_qty=$this->data['Quantity On Hand'];
		$old_value=$this->data['Stock Value'];
		$this->disassociate();


		$data=array(
			'Location Key'=>$location_key
			,'Part SKU'=>$this->part_sku
			,'editor'=>$this->editor
		);



		$part_location=new PartLocation('find',$data,'create');




		$data_inventory_audit=array(
			'Inventory Audit Date'=>$this->editor['Date'],
			'Inventory Audit Part SKU'=>$this->part_sku,
			'Inventory Audit Location Key'=>$location_key,
			'Inventory Audit Note'=>'',
			'Inventory Audit Type'=>'Identify',
			'Inventory Audit User Key'=>$this->editor['User Key'],
			'Inventory Audit Quantity'=>$old_qty
		);
		$audit=new InventoryAudit('find',$data_inventory_audit,'create');
		$part_location->set_audits();
		$part_location->update_stock();
		$part_location->part->update_stock();

	}

	function add_stock($data,$date) {
		$this->stock_transfer(array(
				'Quantity'=>$data['Quantity'],
				'Transaction Type'=>'In',
				'Destination'=>$this->location_key,
				'Origin'=>$data['Origin']
			),$date);

	}

	function move_stock($data,$date) {


		if ($this->error) {
			$this->msg=_('Unknown error');
			return;
		}

		if ($data['Quantity To Move']=='all') {
			$data['Quantity To Move']=$this->data['Quantity On Hand'];

		}


		if (!is_numeric($this->data['Quantity On Hand'])) {
			$this->error=true;
			$this->msg=_('Unknown stock in this location');
			return;
		}
		if ($this->data['Quantity On Hand']<$data['Quantity To Move']) {
			$this->error=true;
			$this->msg=_('To Move Quantity greater than the stock on the location');
			return;
		}


		if ($data['Destination Key']==$this->location_key) {
			$this->error=true;
			$this->msg=_('Destination location is the same as this one');
			return;
		}

		$destination_data=array('Location Key'=>$data['Destination Key'],'Part SKU'=>$this->part_sku,'editor'=>$this->editor);


		$destination=new PartLocation('find',$destination_data,'create');

		if (!is_numeric($destination->data['Quantity On Hand'])) {
			$this->error=true;
			$this->msg=_('Unknown stock in the destination location');
			return;
		}


		if ($data['Quantity To Move']!=0) {
			$from_transaction_id=$this->stock_transfer(array(
					'Quantity'=>-$data['Quantity To Move'],
					'Transaction Type'=>'Move Out',
					'Destination'=>$destination->location->data['Location Code']

				),$date);
			if ($this->error) {
				return;
			}

			$to_transaction_id=$destination->stock_transfer(array(
					'Quantity'=>$data['Quantity To Move'],
					'Transaction Type'=>'Move In',
					'Origin'=>$this->location->data['Location Code'],
					'Value'=>-1*$this->value_change

				),$date);



			$details=_('Inter-warehouse transfer').' <b>['.number($data['Quantity To Move']).']</b>,  <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> &rarr; <a href="location.php?id='.$destination->location->id.'">'.$destination->location->data['Location Code'].'</a>';

			$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%s)"
				,$this->part_sku
				,$data['Destination Key']
				,prepare_mysql('Move')
				,0
				,0
				,$this->editor['User Key']
				,prepare_mysql($details,false)
				,prepare_mysql($this->editor['Date'])
				,prepare_mysql($from_transaction_id.','.$to_transaction_id)
			);


			mysql_query($sql);
			$move_transaction_id=mysql_insert_id();

			$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%s where `Inventory Transaction Key`=%d"
				,prepare_mysql($move_transaction_id)
				,$from_transaction_id
			);
			mysql_query($sql);
			$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%s where `Inventory Transaction Key`=%d"
				,prepare_mysql($move_transaction_id)
				,$to_transaction_id
			);
			mysql_query($sql);

		}








		$this->location->load('parts');
		$destination->location->load('parts');

	}

	function set_stock_as_lost($data,$date) {

		if (!is_numeric($this->data['Quantity On Hand'])) {
			$this->error;
			$this->msg=_('Unknown stock in the location');
			return;
		}

		if ($this->data['Quantity On Hand']<$data['Lost Quantity']) {
			$this->error;
			$this->msg=_('Lost Quantity greater than the stock on the location');
			return;
		}

		$qty=$data['Lost Quantity']*-1;

		$_data=array(
			'Quantity'=>$qty
			,'Transaction Type'=>$data['Type']
			,'Reason'=>$data['Reason']
			,'Action'=>$data['Action']
		);
		//print_r($_data);

		$this->stock_transfer($_data,$date);

	}

	function stock_transfer($data,$date) {
		// this is real time function


		if (!is_numeric($this->data['Quantity On Hand'])) {
			$this->data['Quantity On Hand']=0;
		}


		$qty_change=$data['Quantity'];
		$transaction_type=$data['Transaction Type'];

		if (array_key_exists('Value', $data)) {
			$value_change=$data['Value'];
		}else {
			//$value_change=$qty_change*$this->get_unit_value($date);
			$sql=sprintf("select sum(ifnull(`Inventory Transaction Quantity`,0)) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  `Date`<%s and `Part SKU`=%d and `Location Key`=%d"
				,prepare_mysql($date)
				,$this->part_sku
				,$this->location_key
			);

			$res=mysql_query($sql);
			$old_qty=0;
			$old_value=0;

			if ($row=mysql_fetch_array($res)) {
				$old_qty=round($row['stock'],3);
				$old_value=$row['value'];
			}

			$value_change=$this->get_value_change($qty_change,$old_qty,$old_value,$date);


		}
		//print_r($data);
		//print "$qty_change $value_change ".$this->get_unit_value($date);


		$this->value_change=$value_change;






		//$old_qty=$this->data['Quantity On Hand'];
		//$old_value=$this->data['Stock Value'];


		// $new_qty=$old_qty+$qty;
		// $new_value=$new_qty*$unit_value;


		$_new_value=$this->data['Stock Value']+$value_change;

		// print "\n** ".$this->data['Stock Value']."  -> ".$value_change." = $_new_value **\n";


		$sql=sprintf("update `Part Location Dimension` set `Quantity On Hand`=%f ,`Stock Value`=%.3f, `Last Updated`=NOW()  where `Part SKU`=%d and `Location Key`=%d "
			,$this->data['Quantity On Hand']+$qty_change
			,$this->data['Stock Value']+$value_change
			,$this->part_sku
			,$this->location_key
		);
		//print $sql;
		mysql_query($sql);
		$this->get_data();



		$details='';

		switch ($transaction_type) {
		case('Lost'):
			$tmp=$data['Reason'].', '.$data['Action'];
			$tmp=preg_replace('/, $/','',$tmp);
			if (preg_match('/^\s*,\s*$/',$tmp))
				$tmp='';
			else
				$tmp=' '.$tmp;
			$details=number(-$qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('lost from').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>'.$tmp.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
			break;
		case('Broken'):
			$tmp=$data['Reason'].', '.$data['Action'];
			$tmp=preg_replace('/, $/','',$tmp);
			if (preg_match('/^\s*,\s*$/',$tmp))
				$tmp='';
			else
				$tmp=' '.$tmp;
			$details=number(-$qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('broken').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>'.$tmp.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
			break;
		case('Other Out'):
			$tmp=$data['Reason'].', '.$data['Action'];
			$tmp=preg_replace('/, $/','',$tmp);
			if (preg_match('/^\s*,\s*$/',$tmp))
				$tmp='';
			else
				$tmp=' '.$tmp;
			$details=number(-$qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('stock out (other)').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>'.$tmp.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
			break;


		case('Move Out'):
			$destination_location=new Location('code',$data['Destination']);
			if ($destination_location->id) {
				$destination_link='<a href="location.php?id='.$destination_location->id.'">'.$destination_location->data['Location Code'].'</a>';
			} else {
				$destination_link=$data['Destination'];
			}
			$details=number(-$qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('move out from').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('to').' '.$destination_link.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
			break;
		case('Move In'):
			$details=number($qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('move in to').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('from').' '.$data['Origin'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';

			break;
		case('In'):



			$details=number($qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('received in').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('from').' '.$data['Origin'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
		}


		$editor=$this->get_editor_data();


		$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`)
		values (%d,%d,%s,%f,%.3f,%s,%s,%s)"
			,$this->part_sku
			,$this->location_key
			,prepare_mysql($transaction_type)
			,$qty_change
			,$value_change
			,$this->editor['User Key']
			,prepare_mysql($details,false)
			,prepare_mysql($editor['Date'])

		);

		//print $sql;
		mysql_query($sql);
		$transaction_id=mysql_insert_id();

		$this->part->update_stock();
		$this->location->update_parts();

		$this->updated=true;


		//     $part=new Part($this->part_sku);
		//     $part->load('calculate_stock_history','last');

		return $transaction_id;

	}

	function disassociate($data=false) {

		$date=date("Y-m-d H:i:s");

		if (!$this->editor['Date'])
			$date=$this->editor['Date'];
		if (is_array($data) and array_key_exists('Date', $data))
			$date=$data['Date'];




		$this->deleted=false;
//		if ( is_numeric($this->data['Quantity On Hand']) and  $this->data['Quantity On Hand']>0) {
//			$this->deleted_msg=_('There is still stock in this location');
//			//print $this->deleted_msg;
//			return;
//		}
		/*
               if($this->data['Quantity On Hand']<0){

                   $qty_change=-$this->data['Quantity On Hand'];
                   $value_change=-$this->data['Stock Value'];





                 $details='<a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('adjust due to disassociation with location').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>: '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';

                   $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
                                ,$this->part_sku
                                ,$this->location_key
                                ,"'Adjust'"
                                ,$qty_change
                                ,$value_change
                                ,$this->editor['User Key']
                                ,prepare_mysql($details,false)
                                ,prepare_mysql($this->editor['Date'])

                               );
               mysql_query($sql);

               }

         */


		$base_data=array('Date'=>$date,'Note'=>'','Metadata'=>'','History Type'=>'Admin');
		if (is_array($data)) {
			foreach ($data as $key=>$val) {
				if (array_key_exists($key,$base_data))
					$base_data[$key]=$val;
			}
		}


		$sql=sprintf("delete from `Part Location Dimension` where `Part SKU`=%d and `Location Key`=%d",$this->part_sku,$this->location_key);
		mysql_query($sql);
		//print $sql;



		/*
		list($stock,$stock_value,$in_process)=$this->get_stock($date);

		if ($stock!=0) {
			$data_inventory_audit=array(
				'Inventory Audit Date'=>$base_data['Date'],
				'Inventory Audit Part SKU'=>$this->part_sku,
				'Inventory Audit Location Key'=>$this->location_key,
				'Inventory Audit Note'=>'',
				'Inventory Audit Type'=>'Discontinued',
				'Inventory Audit User Key'=>0,
				'Inventory Audit Quantity'=>0
			);
			$audit=new InventoryAudit('find',$data_inventory_audit,'create');
			$this->set_audits();
		}
*/
		$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%d,%d,'Disassociate',0,0,%s,%s,%s)"
			,prepare_mysql($date)
			,$this->part_sku
			,$this->location_key
			,prepare_mysql($base_data['Note'],false)
			,prepare_mysql($base_data['Metadata'],false)
			,prepare_mysql($base_data['History Type'],false)

		);
		// print_r($base_data);
		//print "$sql\n";
		mysql_query($sql);

		$disassociate_transaction_key=mysql_insert_id();

		$this->deleted=true;
		$this->deleted_msg=_('Part no longer associated with location');





		$audit_key=$this->audit(0,_('Part disassociate with location'),$date,$include_current=true,'disassociate');
		$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d where `Inventory Transaction Key`=%d",$disassociate_transaction_key,$audit_key);
		mysql_query($sql);


		$this->location->update_parts();


	}

	function associate($data=false) {

		$base_data=array('date'=>gmdate('Y-m-d H:i:s'),'note'=>_('Part').' '.$this->part->get_sku().' '._('associated with location').' '.$this->location->data['Location Code'],'metadata'=>'','history_type'=>'Admin');
		if (is_array($data)) {
			foreach ($data as $key=>$val) {
				$base_data[$key]=$val;
			}
		}



		$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%d,%d,'Associate',0,0,%s,%s,%s)"
			,prepare_mysql($base_data['date'])
			,$this->part_sku
			,$this->location_key
			,prepare_mysql($base_data['note'],false)
			,prepare_mysql($base_data['metadata'],false)
			,prepare_mysql($base_data['history_type'],false)

		);
		//print_r($base_data);
		// print "$sql\n";
		// exit;

		mysql_query($sql);
		$associate_transaction_key=mysql_insert_id();
		$audit_key=$this->audit(0,_('Part associated with location'),$base_data['date'],$include_current=false,$parent='associate');
		$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d where `Inventory Transaction Key`=%d",$associate_transaction_key,$audit_key);
		mysql_query($sql);
		$this->location->update_parts();

	}

	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		case('Quantity On Hand'):
			$this->audit($value);
			break;
		case('Can Pick'):
			$this->update_can_pick($value);
			break;
		case('Minimum Quantity'):
			$this->update_min($value);
			break;
		case('Maximum Quantity'):
			$this->update_max($value);
			break;

		case('Moving Quantity'):
			$this->update_move_qty($value);
			break;
		}
	}

	function get_ohlc($date) {

		$day_before_date = date("Y-m-d", strtotime($date."-1 day", strtotime($date)));

		list ($open,$value_open,$in_process)=$this->get_stock($day_before_date." 23:59:59");

		$high=$open;
		$low=$open;
		$close=$open;

		$value_high=$value_open;
		$value_low=$value_open;
		$value_close=$value_open;

		$sql=sprintf("select `Inventory Transaction Quantity` as delta,`Inventory Transaction Amount` as value_delta  from `Inventory Transaction Fact` where  Date(`Date`)=%s and `Part SKU`=%d and `Location Key`=%d order by `Date` "
			,prepare_mysql($date)
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$close+=$row['delta'];
			if ($high<$close)
				$high=$close;
			if ($low>$close)
				$low=$close;

			$value_close+=$row['value_delta'];
			if ($value_high<$value_close)
				$value_high=$value_close;
			if ($value_low>$value_close)
				$value_low=$value_close;

		}
		return array($open,$high,$low,$close,$value_open,$value_high,$value_low,$value_close);

	}




	function get_stock($date='') {
		if (!$date)
			$date=gmdate('Y-m-d H:i:s');
	    
	    // find last audit ans take that value as seed
	    /*
	    $sql=sprintf("select `Part Location Stock`,`Date` where  Part SKU=%d and Location Key=%d and `Inventory Transaction Type`='Audit' and `Date`<=%s order by `Date` desc limit 1 ",
			 ,$this->part_sku
			 ,$this->location_key
			 ,prepare_mysql($date)
			 
			 
			 );
	     $res=mysql_query($sql);
	    if($row=mysql_fetch_assoc()){
		$audit_date=$row['Date'];
		$audit_stock=$row['Part Stock Location'];
		$audit_stock_value=$row['Part Stock Value'];
	    }else{
		
	    }
	    */
	    
	    
		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as stock ,sum(`Inventory Transaction Amount`) as value 
			       from `Inventory Transaction Fact` where  `Date`<=%s and `Part SKU`=%d and `Location Key`=%d"
			,prepare_mysql($date)
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);


		// print "$sql\n";

		$stock=0;
		$value=0;

		if ($row=mysql_fetch_array($res)) {
			$stock=round($row['stock'],3);
			$value=$row['value'];
		}
		//print "$stock\n";
		return array($stock,$value,0);

	}

	function get_sales($date='') {
		if (!$date)
			$date=date('Y-m-d');

		$sql=sprintf("select ifnull(sum(`Inventory Transaction Quantity`),0) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  Date(`Date`)=%s and `Part SKU`=%d and `Location Key`=%d and `Inventory Transaction Type`='Sale'"
			,prepare_mysql(date('Y-m-d',strtotime($date)))
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		//print "$sql\n";
		$stock=0;
		$value=0;
		if ($row=mysql_fetch_array($res)) {
			$stock=-$row['stock'];
			$value=-$row['value'];
		}
		//print "$stock,$value\n";
		return array($stock,$value);

	}

	function get_in($date='') {
		if (!$date)
			$date=date('Y-m-d');

		$sql=sprintf("select ifnull(sum(`Inventory Transaction Quantity`),0) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  Date(`Date`)=%s and `Part SKU`=%d and `Location Key`=%d and ( `Inventory Transaction Type` in ('In','Move In','Move Out') or  (`Inventory Transaction Type`='Audit' and `Inventory Transaction Quantity`>0 ) )   "
			,prepare_mysql(date('Y-m-d',strtotime($date)))
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		//print $sql;
		$stock=0;
		$value=0;
		if ($row=mysql_fetch_array($res)) {
			$stock=$row['stock'];
			$value=$row['value'];
		}

		return array($stock,$value);

	}

	function get_lost($date='') {
		if (!$date)
			$date=date('Y-m-d');

		$sql=sprintf("select ifnull(sum(`Inventory Transaction Quantity`),0) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  Date(`Date`)=%s and `Part SKU`=%d and `Location Key`=%d and ( `Inventory Transaction Type` in ('Broken','Lost') or  (`Inventory Transaction Type`='Audit' and `Inventory Transaction Quantity`<0 ))    "
			,prepare_mysql(date('Y-m-d',strtotime($date)))
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		//print $sql;
		$stock=0;
		$value=0;
		if ($row=mysql_fetch_array($res)) {
			$stock=$row['stock'];
			$value=$row['value'];
		}

		return array($stock,$value);

	}

	function update_stock() {

		list($stock,$value,$in_process)=$this->get_stock();

		$this->data['Quantity On Hand']=$stock;
		$this->data['Stock Value']=$value;
		$this->data['Quantity In Process']=$in_process;

		$sql=sprintf("update `Part Location Dimension` set `Quantity On Hand`=%f ,`Quantity In Process`=%f,`Stock Value`=%f where `Part SKU`=%d and `Location Key`=%d"
			,$stock
			,$in_process
			,$value
			,$this->part_sku
			,$this->location_key
		);
		// print $sql;
		mysql_query($sql);

		$this->part->update_stock();
	}

	function exist_on_date($date) {

		$date=date('U',strtotime($date));

		$intervals=$this->get_history_intervals();

		//print_r($intervals);
		foreach ($intervals as $interval) {


			if ($interval['To']) {
				if (!isset($interval['From'])) {
					print_r($this);
					print_r($interval);
					exit;
				}

				if ($date>=date('U',strtotime($interval['From']))  and  $date<=date('U',strtotime($interval['To']))  ) {
					return true;
				}

			}else {
				if ($date>=date('U',strtotime($interval['From'])) ) {
					return true;
				}

			}

		}

		return false;
	}

	function get_history_intervals() {
		$sql=sprintf("select  `Inventory Transaction Type`,(`Date`) as Date from `Inventory Transaction Fact` where  `Part SKU`=%d and  `Location Key`=%d and `Inventory Transaction Type` in ('Associate','Disassociate')  order by `Date` ,`Inventory Transaction Key` ",
			$this->part_sku,
			$this->location_key
		);
		// print "$sql\n";
		$dates=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$dates[$row['Date']]= $row['Inventory Transaction Type'];
		}

		$intervals=array();
		// print_r($dates);

		foreach ($dates as $date=>$type) {
			if ($type=='Associate')
				$intervals[]=array('From'=>date("Y-m-d",strtotime($date)),'To'=>false);
			if ($type=='Disassociate')
				$intervals[count($intervals)-1]['To']=date("Y-m-d",strtotime($date));
		}


		return $intervals;

	}

	function get_history_datetime_intervals() {
		$sql=sprintf("select  `Inventory Transaction Type`,(`Date`) as Date from `Inventory Transaction Fact` where  `Part SKU`=%d and  `Location Key`=%d and `Inventory Transaction Type` in ('Associate','Disassociate')  order by `Date` ,`Inventory Transaction Key` ",
			$this->part_sku,
			$this->location_key
		);
		// print "$sql\n";
		$dates=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$dates[$row['Date']]= $row['Inventory Transaction Type'];
		}

		$intervals=array();
		// print_r($dates);

		foreach ($dates as $date=>$type) {
			if ($type=='Associate')
				$intervals[]=array('From'=>date("Y-m-d H:i:s",strtotime($date)),'To'=>false);
			if ($type=='Disassociate')
				$intervals[count($intervals)-1]['To']=date("Y-m-d H:i:s",strtotime($date));
		}


		return $intervals;

	}




	function is_associated($date=false) {

		if(!$date){
			$date=gmdate('Y-m-d H:i:s');
		}

		// print "is test: $date ".$this->location_key."\n";

		$intervals=$this->get_history_datetime_intervals();
		//print_r($intervals);
		$date=strtotime($date);
		foreach ($intervals as $interval) {
			if (!$interval['To'])
				$to=gmdate('U')+1000000000;
			else {
				$to=strtotime($interval['To'].' +00:00');
			}

			$from=strtotime($interval['From'].' +00:00');
			// print "f: $from d: $date t: $to\n";
			if ($from<=$date and $to>=$date)
				return true;

		}
		//print "not asscated\n ";

		return false;



	}


	function update_stock_history_date($date) {

		if ($this->exist_on_date($date)) {
			//print "a\n";
			$this->update_stock_history_interval($date,$date);
		}else {

			$sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d and `Date`=%s",
				$this->part_sku,
				$this->location_key,
				prepare_mysql($date)
			);
			mysql_query($sql);
			//print "$sql\n";
		}


	}

	function update_stock_history() {
		$sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d and `Location Key`=%d",$this->part_sku,$this->location_key);
		mysql_query($sql);

		$intervals=$this->get_history_intervals();
		foreach ($intervals as $interval) {
			$this->update_stock_history_interval($interval['From'],($interval['To']?$interval['To']:date('Y-m-d',strtotime('now'))));
		}

	}

	function update_stock_history_interval($from,$to) {



		$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date`"
			,prepare_mysql($from)
			,prepare_mysql($to)
		);
		$result=mysql_query($sql);

		//print $this->part_sku." ".$this->location_key." $from $to \n";
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			list($stock,$value,$in_process)=$this->get_stock($row['Date'].' 23:59:59');
			list($sold,$sales_value)=$this->get_sales($row['Date'].' 23:59:59');
			list($in,$in_value)=$this->get_in($row['Date'].' 23:59:59');
			list($lost,$lost_value)=$this->get_lost($row['Date'].' 23:59:59');
			list($open,$high,$low,$close,$value_open,$value_high,$value_low,$value_close)=$this->get_ohlc($row['Date']);


			$storing_cost=0;

			$location_type="Unknown";
			$warehouse_key=1;


			if ($stock>0 or $open>0 or $high>0 or $low>0) {
				$commercial_value_unit_cost=$this->part->get_unit_commercial_value($row['Date'].' 23:59:59');
				$value_day_cost_unit_cost=$this->part->get_unit_cost($row['Date'].' 23:59:59');
			}

			if ($stock<=0) {
				$value_day_cost=0;
				$commercial_value=0;
			}
			else {
				$value_day_cost=$stock*$value_day_cost_unit_cost;
				$commercial_value=$stock*$commercial_value_unit_cost;
			}
			if ($open<=0) {
				$value_day_cost_open=0;
				$commercial_value_open=0;
			}
			else {
				$value_day_cost_open=$open*$value_day_cost_unit_cost;
				$commercial_value_open=$open*$commercial_value_unit_cost;
			}

			if ($high<=0) {
				$value_day_cost_high=0;
				$commercial_value_high=0;
			}
			else {
				$value_day_cost_high=$high*$value_day_cost_unit_cost;
				$commercial_value_high=$high*$commercial_value_unit_cost;
			}

			if ($low<=0) {
				$value_day_cost_low=0;
				$commercial_value_low=0;
			}
			else {
				$value_day_cost_low=$low*$value_day_cost_unit_cost;
				$commercial_value_low=$low*$commercial_value_unit_cost;
			}


			if ($value<0)$value=0;
			if ($value_open<0)$value_open=0;
			if ($value_low<0)$value_low=0;
			if ($value_high<0)$value_high=0;

			//print $row['Date']." $stock v: $value $value_day_cost_value  $commercial_value \n";
			//print $row['Date']." $stock v: $value $value_open  $value_low $value_clos \n";

			$sql=sprintf("insert into `Inventory Spanshot Fact` values (%s,%d,%d,%d,%f,%.2f ,%.2f,%.2f,%.2f ,%.f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%f,%s) ON DUPLICATE KEY UPDATE
			`Warehouse Key`=%d,`Quantity On Hand`=%f,`Value At Cost`=%.2f,`Sold Amount`=%.2f,`Value Commercial`=%.2f,`Value At Day Cost`=%.2f, `Storing Cost`=%.2f,`Quantity Sold`=%f,`Quantity In`=%f,`Quantity Lost`=%f,`Quantity Open`=%f,`Quantity High`=%f,`Quantity Low`=%f,
			`Value At Cost Open`=%f,`Value At Cost High`=%f,`Value At Cost Low`=%f,
			`Value At Day Cost Open`=%f,`Value At Day Cost High`=%f,`Value At Day Cost Low`=%f,
			`Value Commercial Open`=%f,`Value Commercial High`=%f,`Value Commercial Low`=%f,`Location Type`=%s",
				prepare_mysql($row['Date']),

				$this->part_sku,
				$warehouse_key,

				$this->location_key,

				$stock,
				$value,
				$value_day_cost,
				$sales_value,
				$commercial_value,

				$storing_cost,

				$sold,
				$in,
				$lost,
				$open,
				$high,
				$low,
				$value_open,
				$value_high,
				$value_low,

				$value_day_cost_open,
				$value_day_cost_high,
				$value_day_cost_low,
				$commercial_value_open,
				$commercial_value_high,
				$commercial_value_low,

				prepare_mysql($location_type),

				$warehouse_key,
				$stock,
				$value,

				$sales_value,
				$commercial_value,
				$value_day_cost,
				$storing_cost,

				$sold,
				$in,
				$lost,
				$open,
				$high,
				$low,
				$value_open,
				$value_high,
				$value_low,
				$value_day_cost_open,
				$value_day_cost_high,
				$value_day_cost_low,
				$commercial_value_open,
				$commercial_value_high,
				$commercial_value_low,
				prepare_mysql($location_type)


			);
			mysql_query($sql);



			// print "$sql\n\n";
			//exit;
		}

	}

	function set_audits() {

		$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Audit') and `Part SKU`=%d and `Location Key`=%d"
			,$this->part_sku
			,$this->location_key
		);
		// print "$sql\n";
		mysql_query($sql);

		$sql=sprintf('select `Inventory Audit Key` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d order by `Inventory Audit Date`,`Inventory Audit Key`'
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->set_audit($row['Inventory Audit Key']);

		}
		$this->update_stock();
	}

	function set_audit($audit_key) {

		include_once 'class.InventoryAudit.php';
		$audit=new InventoryAudit($audit_key);
		//print_r($audit->data);
		$sql=sprintf("select ifnull(sum(`Inventory Transaction Quantity`),0) as stock from `Inventory Transaction Fact` where  `Date`<=%s and `Part SKU`=%d and `Location Key`=%d"
			,prepare_mysql($audit->data['Inventory Audit Date'])
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);
		//print $sql;
		$stock=0;
		if ($row=mysql_fetch_array($res)) {
			$stock=$row['stock'];
		}

		$diff=$audit->data['Inventory Audit Quantity']-$stock;
		$cost_per_part=$this->part->get_unit_cost($audit->data['Inventory Audit Date']);
		$cost=$diff*$cost_per_part;
		//print $audit->data['Inventory Audit Type']."S: $stock ".$audit->data['Inventory Audit Quantity']."\n";
		$notes='';
		if ($audit->data['Inventory Audit Type']=='Audit')
			$notes=_('Change due Audit');
		elseif ($audit->data['Inventory Audit Type']=='Discontinued')
			$notes=_('Change due Discontinuation');
		elseif ($audit->data['Inventory Audit Type']=='Identify')
			$notes=_('Copying unknown location state');
		elseif ($audit->data['Inventory Audit Type']=='Out of Stock')
			$notes=_('Change due Out of Stock');

		if ($audit->data['Inventory Audit Note']) {
			$notes.=' ('.$audit->data['Inventory Audit Note'].')';
		}


		$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%d,%d,'Audit',%f,%f,%s,'')"
			,prepare_mysql($audit->data['Inventory Audit Date'])
			,$this->part_sku
			,$this->location_key
			,0
			,0
			,prepare_mysql($notes)
		);
		// print "$sql\n";
		mysql_query($sql);

	}


	function redo_adjusts() {

		$sql=sprintf("delete from `Inventory Transaction Fact` where `Inventory Transaction Type`='Adjust' and  `Part SKU`=%d and `Location Key`=%d   "
			,$this->part_sku
			,$this->location_key
		);

		mysql_query($sql);
		$sql=sprintf("select *  from `Inventory Transaction Fact` where `Inventory Transaction Type`='Audit' and  `Part SKU`=%d and `Location Key`=%d  order by `Date` "
			,$this->part_sku
			,$this->location_key
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$audit_key=$row['Inventory Transaction Key'];
			$date=$row['Date'];
			//$include_current=false;
			//$sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Disassociate' and  `Inventory Transaction Key`=%d  ",$row['Relations']);
			//$res2=mysql_query($sql);
			//if ($row2=mysql_fetch_array($res2)) {
			// $include_current=true;
			//}
			$include_current=true;
			$sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and  `Part SKU`=%d and `Location Key`=%d and `Date`=%s  "
				,$this->part_sku
				,$this->location_key
				,prepare_mysql($date)

			);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_array($res2)) {
				$include_current=false;
			}
			//print "$sql\n";


			$sql=sprintf("select sum(ifnull(`Inventory Transaction Quantity`,0)) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  `Date`<".($include_current?'=':'')."%s and `Part SKU`=%d and `Location Key`=%d"
				,prepare_mysql($date)
				,$this->part_sku
				,$this->location_key
			);
			$res3=mysql_query($sql);
			//print "$sql\n";
			$old_qty=0;
			$old_value=0;

			if ($row3=mysql_fetch_array($res3)) {
				$old_qty=round($row3['stock'],3);
				$old_value=$row3['value'];
			}

			$qty=$row['Part Location Stock'];

			$qty_change=$qty-$old_qty;




			//print "Q: $qty_change $qty $old_qty\n";

			$value_change=$this->get_value_change($qty_change,$old_qty,$old_value,$date);

			if ($qty_change!=0 or $value_change!=0) {

				$audit_key=$row['Inventory Transaction Key'];

				$note=$row['Note'];

				//print "$qty_change=$qty-$old_qty\n";
				$details='Audit: <b>['.number($qty).']</b> <a href="part.php?sku='.$this->part_sku.'">'.$this->part->get_sku().'</a>'.' '._('adjust quantity').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a>: '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
				//if ($note) {
				// $details.=', '.$note;
				//
				//   }



				$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`)
			values (%d,%d,%s,%f,%.3f,%s,%s,%s,%s)"
					,$this->part_sku
					,$this->location_key
					,"'Adjust'"
					,$qty_change
					,$value_change
					,$row['User Key']
					,prepare_mysql($details,false)
					,prepare_mysql($date)
					,prepare_mysql($audit_key)
				);
				//  print "$sql\n\n\n";
				//  if($date=='2012-02-27 02:43:48')
				//  exit;

				mysql_query($sql);

			}


		}

		$this->update_stock();

	}


	


	function get_value_change($qty_change,$old_qty,$old_value,$date) {
		$qty=$old_qty+$qty_change;
		if ($qty_change>0) {

			list($qty_above_zero,$qty_below_zero)=$this->qty_analysis($old_qty,$qty);
			$value_change=0;
			if ($qty_below_zero) {
				$unit_cost=$old_value/$old_qty;
				$value_change+=$qty_below_zero*$unit_cost;
			}

			if ($qty_above_zero) {

				$unit_cost=$this->part->get_unit_cost($date);
				$value_change+=$qty_above_zero*$unit_cost;
			}


		}
		elseif ($qty_change<0) {

			list($qty_above_zero,$qty_below_zero)=$this->qty_analysis($old_qty,$qty);

			$value_change=0;
			if ($qty_below_zero) {
				$unit_cost=$this->part->get_unit_cost($date);
				$value_change+=-$qty_below_zero*$unit_cost;

			}

			if ($qty_above_zero) {

				$unit_cost=$old_value/$old_qty;
				$value_change+=-$qty_above_zero*$unit_cost;

			}



		}
		else {

			$value_change=0;
		}

		return $value_change;
	}
	function qty_analysis($a,$b) {
		if ($b<$a) {
			$tmp=$a;
			$a=$b;
			$b=$tmp;
		}

		if ($a>=0 and $b>=0) {
			$above=$b-$a;
			$below=0;
		}else if ($a<=0 and $b<=0) {
				$above=0;
				$below=$b-$a;
			}else {
			$above=$b;
			$below=-$a;
		}
		return array($above,$below);

	}




}
?>
