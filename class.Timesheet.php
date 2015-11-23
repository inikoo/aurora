<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 22 November 2015 at 17:46:16 GMT Sheffield UK

 Version 2.0
*/


class Timesheet extends DB_Table {


	function Timesheet($arg1=false, $arg2=false, $arg3=false) {
		global $db;

		$this->db=$db;
		$this->table_name='Timesheet';
		$this->ignore_fields=array('Timesheet Key');

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
			$sql=sprintf("select * from `Timesheet Dimension` where `Timesheet Key`=%d", $tag);
		else
			return;
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Timesheet Key'];
		}

	}





	function get($key='') {



		switch ($key) {
		
	
		case 'Clocked Hours':
		return number($this->data['TImesheet ',$key],2);
		
		break;
		
		case 'Date':
		return ($this->data['Timesheet Date']!=''?strftime("%a %e %b %Y", strtotime($this->data['Timesheet Date'])):'')
		
		break;
		
	
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
			$_key=ucfirst($key);
			if (isset($this->data[$_key]))
				return $this->data[$_key];

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


		$sql=sprintf("select `Timesheet Key` from `Timesheet Dimension` where `Timesheet Date`=%s and `Timesheet Staff Key`=%d ",
			prepare_mysql($data['Timesheet Date']),
			$data['Timesheet Staff Key']
		);



		if ($row= $this->db->query($sql)->fetch()) {


			$this->found=true;
			$this->found_key=$row['Timesheet Key'];
			$this->get_data('id', $this->found_key);
		}


		if ($create and !$this->found) {




			$this->create($raw_data);

		}



	}


	function create($data) {

		$this->duplicated=false;
		$this->new=false;

		$this->editor=$data['editor'];
		unset($data['editor']);
		$this->data=$data;

		$keys='';
		$values='';

		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value, false);
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Timesheet Dimension` ($keys) values ($values)";

		//print  $sql;
		if ($this->db->exec($sql)) {

			$this->id=$this->db->lastInsertId();
			$this->new=true;

			$this->get_data('key', $this->id);
		} else {
			$this->error=true;



			$error_info=$this->db->errorInfo();
			if ($error_info[0]==23000) {
				$this->duplicated=true;
				$this->msg=_('Record already exists');
			}else {
				$this->msg='Can not create Timesheet. '.$error_info[2];
			}

		}

	}



	function get_field_label($field) {

		switch ($field) {

		case 'Timesheet Source':
			$label=_('source');
			break;
		case 'Timesheet Date':
			$label=_('date');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


	function process_records() {


	}


}
