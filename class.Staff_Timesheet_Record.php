<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 18:54:03 GMT GMT Sheffield UK

 Version 2.0
*/


class Staff_Timesheet_Record extends DB_Table {


	function Staff_Timesheet_Record($arg1=false, $arg2=false) {
		global $db;

		$this->db=$db;
		$this->table_name='Staff Timesheet Record';
		$this->ignore_fields=array('Staff Timesheet Record Key');

		if (is_numeric($arg1)) {
			$this->get_data('key', $arg1);
			return ;
		}
		if (preg_match('/^(create|new)/i', $arg1)) {
			$this->create($arg2);
			return;
		}

		$this->get_data($arg1, $arg2);
		return ;

	}



	function get_data($tipo, $tag) {

		if ($tipo=='key')
			$sql=sprintf("select * from `Staff Timesheet Record Dimension` where `Staff Timesheet Record Key`=%d", $tag);
		else
			return;
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Staff Timesheet Record Key'];
		}

	}





	function get($key='') {



		switch ($key) {
		case 'Source':


			switch ($this->data['Staff Timesheet Record Source']) {
			case 'ClockingMachine':
				$scope=_('Clocking machine');
				break;
			case 'Manual':
				$scope=_('Manual');
				break;
			default:
				$scope=$this->data['Staff Timesheet Record Source'];
				break;
			}
			return $scope;
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

		$sql="insert into `Staff Timesheet Record Dimension` ($keys) values ($values)";

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
				$this->msg='Can not create Staff Timesheet Record. '.$error_info[2];
			}






		}
		
		
		
	}






	function get_field_label($field) {

		switch ($field) {

		case 'Staff Timesheet Record Source':
			$label=_('source');
			break;
		case 'Staff Timesheet Record Date':
			$label=_('date');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


}
