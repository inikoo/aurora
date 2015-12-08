<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 18:54:03 GMT Sheffield UK

 Version 2.0
*/


class Timesheet_Record extends DB_Table {


	function Timesheet_Record($arg1=false, $arg2=false) {
		global $db;

		$this->db=$db;
		$this->table_name='Timesheet Record';
		$this->ignore_fields=array('Timesheet Record Key');

		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
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

		if ($tipo=='id')
			$sql=sprintf("select * from `Timesheet Record Dimension` where `Timesheet Record Key`=%d", $tag);
		else
			return;
		if ($this->data = $this->db->query($sql)->fetch()) {

			$this->id=$this->data['Timesheet Record Key'];
		}

	}





	function get($key='') {



		switch ($key) {
		case 'Source':


			switch ($this->data['Timesheet Record Source']) {
			case 'ClockingMachine':
				$scope=_('Clocking machine');
				break;
			case 'Manual':
				$scope=_('Manual');
				break;
			default:
				$scope=$this->data['Timesheet Record Source'];
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

		$sql="insert into `Timesheet Record Dimension` ($keys) values ($values)";

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
				$this->msg=_('Record already exists');
			}else {
				$this->msg='Can not create Timesheet Record. '.$error_info[2];
			}






		}



	}






	function get_field_label($field) {

		switch ($field) {

		case 'Timesheet Record Source':
			$label=_('source');
			break;
		case 'Timesheet Record Date':
			$label=_('date');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


	function update_field_switcher($field, $value, $options='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {

		case('Timesheet Record Ignored'):
			$this->update_field($field, $value, $options);

			if ($value=='Yes') {
				$this->update_field('Timesheet Record Action Type', 'ignored', 'no_history');

			}

			include_once 'class.Timesheet.php';
			$timesheet=new TimeSheet($this->data['Timesheet Record Timesheet Key']);


			$timesheet->process_clocking_records_action_type();
			$timesheet->update_clocked_time();
			$timesheet->update_working_time();
			$timesheet->update_unpaid_overtime();


			$sql=sprintf('select `Timesheet Record Action Type`,`Timesheet Record Key`   from `Timesheet Record Dimension` where `Timesheet Record Timesheet Key`=%d   and `Timesheet Record Type`="ClockingRecord" ',
				$this->data['Timesheet Record Timesheet Key']
			);
			$records_data=array();
			if ($result=$this->db->query($sql)) {

				foreach ($result as $row) {

					switch ($row['Timesheet Record Action Type']) {
					case 'Start':
						$action_type='<span id="action_type_'.$row['Timesheet Record Key'].'"><span  class="success"><i class="fa fa-fw fa-sign-in"></i> '._('In').'</span></span>';
						break;
					case 'End':
						$action_type='<span id="action_type_'.$row['Timesheet Record Key'].'" ><span class="error"><i class="fa fa-fw fa-sign-out"></i> '._('Out').'</span></span>';
						break;
					case 'Unknown':
						$action_type='<span id="action_type_'.$row['Timesheet Record Key'].'"  ><span class="disabled"><i class="fa fa-fw fa-question"></i> '._('Unknown').'</span></span>';
						break;
					case 'Ignored':
						$action_type='<span id="action_type_'.$row['Timesheet Record Key'].'"  ><span class="disabled"><i class="fa fa-fw fa-eye-slash"></i> '._('Ignored').'</span></span>';
						break;


					default:
						$action_type=$row['Timesheet Record Action Type'];
						break;
					}
					$records_data[$row['Timesheet Record Key']]['action_type']=$action_type;

				}
			}


			$this->other_fields_updated=array(
				'records_data'=>$records_data,
				'updated_data'=>array(
				'Timesheet_Clocked_Time'=>$timesheet->get('Clocked Time'),
				'Timesheet_Working_Time'=>$timesheet->get('Working Time'),
				'Timesheet_Breaks_Time'=>$timesheet->get('Breaks Time'),
				'Timesheet_Unpaid_Overtime'=>$timesheet->get('Unpaid Overtime')
				
				),
				'updated_titles'=>array('Timesheet_Clocked_Time'=>$timesheet->get('Clocked Hours'))

			);


			break;

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();

	}


}
