<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 7 January 2016 at 15:40:47 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/


class Timeseries extends DB_Table {


	function Timeseries($arg1=false, $arg2=false, $arg3=false) {
		global $db;

		$this->db=$db;
		$this->table_name='Timeseries';
		$this->ignore_fields=array('Timeseries Key');

		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return ;
		}
		if (preg_match('/^find/i', $arg1)) {

			$this->find($arg2, $arg3);
			return;
		}

		if (preg_match('/^(create|new)/i', $arg1)) {
			$this->create($arg2);
			return;
		}

		$this->get_data($arg1, $arg2);
		return ;

	}



	function get_data($tipo, $tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Timeseries Dimension` where `Timeseries Key`=%d", $tag);
		else
			return;
		if ($this->data = $this->db->query($sql)->fetch()) {

			$this->id=$this->data['Timeseries Key'];
			if ($this->data['Timeseries Parent']=='Store') {
				include_once 'class.Store.php';
				$this->parent=new Store($this->data['Timeseries Parent Key']);

			}else {
				$this->parent=new Account($this->db);
			}
		}




	}


	function update_field_switcher($field, $value, $options='', $metadata='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {


		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();

	}


	function get($key='') {

		global $account;


		switch ($key) {
		case 'Name':
			switch ($this->data['Timeseries Type']) {
			case 'StoreSales':

				$name=_('Store sales').' ('.$this->parent->get('Code').')';

				break;

			default:
				$name=$this->data['Timeseries Type'].' ('.$this->parent->get('Code').')';

				break;
			}
			return $name;
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];

			if (array_key_exists('Timeseries '.$key, $this->data))
				return $this->data['Timeseries '.$key];

			return false;

		}



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
		if (preg_match('/update/i', $options)) {
			$update='update';
		}


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
		}


		$sql=sprintf("select `Timeseries Key` from `Timeseries Dimension` where `Timeseries Type`=%s and `Timeseries Frequency`=%s and `Timeseries Scope`=%s  and `Timeseries Parent`=%s  and `Timeseries Parent Key`=%s",
			prepare_mysql($data['Timeseries Type']),
			prepare_mysql($data['Timeseries Frequency']),
			prepare_mysql($data['Timeseries Scope']),
			prepare_mysql($data['Timeseries Parent']),
			prepare_mysql($data['Timeseries Parent Key'])

		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Timeseries Key'];
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

		$data['Timeseries Created']=gmdate('Y-m-d H:i:s');

		$this->duplicated=false;
		$this->new=false;

		$this->editor=$data['editor'];
		unset($data['editor']);
		$this->data=$data;

		$keys='';
		$values='';



		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";

			if (in_array($key, array('Timeseries Parent', 'Timeseries Parent Key', 'Timeseries Scope'))) {
				$values.=','.prepare_mysql($value, true);
			}else {
				$values.=','.prepare_mysql($value, false);
			}
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Timeseries Dimension` ($keys) values ($values)";

		//print  $sql;
		if ($this->db->exec($sql)) {

			$this->id=$this->db->lastInsertId();
			$this->new=true;
			$this->get_data('id', $this->id);



		} else {
			$this->error=true;
			$error_info=$this->db->errorInfo();
			if ($error_info[0]==23000) {
				$this->duplicated=true;
				$this->msg=_('Timeseries already exists');
			}else {
				$this->msg='Can not create timeseries. '.$error_info[2];
			}
		}
	}


	function get_field_label($field) {

		switch ($field) {
		case 'Timeseries Type':
			$label=_('Type');
			break;
		default:
			$label=$field;
		}
		return $label;

	}


	function create_record($data) {

		$sql=sprintf('select `Timeseries Record Key`,`Timeseries Record Date` from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=%d and  `Timeseries Record Date`=%s',
			$this->id,
			prepare_mysql($data['Timeseries Record Date'])
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				return array($row['Timeseries Record Key'], $row['Timeseries Record Date']);
			}else {
				$sql=sprintf('insert into `Timeseries Record Dimension` (`Timeseries Record Timeseries Key`, `Timeseries Record Date`) VALUES (%d,%s)',
					$this->id,
					prepare_mysql($data['Timeseries Record Date'])
				);
				if ($this->db->exec($sql)) {
					return array($this->db->lastInsertId(), $data['Timeseries Record Date']);
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


	function update_stats() {

		$sql=sprintf('select count(*) as num , min(`Timeseries Record Date`) as from_date , max(`Timeseries Record Date`) as  to_date from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=%d',
			$this->id
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$num=$row['num'];
				$from=$row['from_date'];
				$to=$row['to_date'];
			}else {
				$num=0;
				$from='';
				$to='';
			}
			$sql=sprintf('update `Timeseries Dimension` set
                    `Timeseries From`=%s ,
                    `Timeseries To`=%s ,
                    `Timeseries Number Records`=%d
                    where `Timeseries Key`=%d
                      ',
				prepare_mysql($from, true),
				prepare_mysql($to, true),
				$num,
				$this->id

			);

			$this->db->exec($sql);
			include_once('class.Data_Sets.php');
			$data_set=new Data_Sets('code','Timeseries');
			if($data_set->id){
			    $data_set->update_stats();
			}


		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

	}


}
