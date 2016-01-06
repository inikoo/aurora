<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 5 January 2016 at 19:24:34 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/


class Manufacture_Task extends DB_Table {


	function Manufacture_Task($arg1=false, $arg2=false, $arg3=false) {
		global $db;

		$this->db=$db;
		$this->table_name='Manufacture Task';
		$this->ignore_fields=array('Manufacture Task Key');

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
			$sql=sprintf("select * from `Manufacture Task Dimension` where `Manufacture Task Key`=%d", $tag);
		else
			return;
		if ($this->data = $this->db->query($sql)->fetch()) {

			$this->id=$this->data['Manufacture Task Key'];
		}

	}


	function update_field_switcher($field, $value, $options='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {

		case 'Manufacture Task Lower Target Per Hour':
		case 'Manufacture Task Upper Target Per Hour':

			if ($value==0) {
				$this->error=true;
				$this->msg=_("Value can't be zero");
				return;
			}elseif ($value<0) {
				$this->error=true;
				$this->msg=_("Value can't be negative");
				return;
			}

			$value=3600/$value;
			$field=preg_replace('/ Per Hour/', '', $field);
			$this->update_field($field, $value, $options);
			$this->other_fields_updated=array(

				'Manufacture_Task_Targets'=>array(
					'field'=>'Manufacture_Task_Targets',
					'value'=>$this->get('Targets'),
					'formated_value'=>$this->get('Targets'),


				)
			);
			break;

		case 'Manufacture Task Lower Target':
		case 'Manufacture Task Upper Target':
			$this->update_field($field, $value, $options);
			$this->other_fields_updated=array(

				'Manufacture_Task_Targets'=>array(
					'field'=>'Manufacture_Task_Targets',
					'value'=>$this->get('Targets'),
					'formated_value'=>$this->get('Targets'),


				)
			);
			break;

		case 'Manufacture Task Materials Cost':
		case 'Manufacture Task Energy Cost':
		case 'Manufacture Task Other Cost':
		case 'Manufacture Task Work Cost':
			$this->update_field($field, $value, $options);

			$this->other_fields_updated=array(
				'Manufacture_Task_Cost'=>array(
					'field'=>'Manufacture_Task_Cost',
					'value'=>$this->get('Cost'),
					'formated_value'=>$this->get('Cost'),


				),

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


	function get($key='') {

		global $account;


		switch ($key) {
		case 'Targets':
			include_once 'utils/natural_language.php';
			return _('Lower').': <span title="'.$this->get('Lower Target').'">'.$this->get('Lower Target Per Hour').'</span><br>'
				._('Upper').': <span title="'.$this->get('Upper Target').'">'.$this->get('Upper Target Per Hour').'</span>';

			break;
		case 'Cost':
			$cost_label='unknown';
			$cost=0;
			if ($this->data['Manufacture Task Materials Cost']!='') {
				$cost+=$this->data['Manufacture Task Materials Cost'];
				$cost_label='';
			}
			if ($this->data['Manufacture Task Energy Cost']!='') {
				$cost+=$this->data['Manufacture Task Energy Cost'];
				$cost_label='';
			}
			if ($this->data['Manufacture Task Other Cost']!='') {
				$cost+=$this->data['Manufacture Task Other Cost'];
				$cost_label='';
			}
			if ($this->data['Manufacture Task Other Cost']!='') {
				$cost+=$this->data['Manufacture Task Other Cost'];
				$cost_label='';
			}
			if ($this->data['Manufacture Task Work Cost']!='') {
				$cost+=$this->data['Manufacture Task Work Cost'];
				$cost_label='';
			}
			if ($cost_label=='unknown') {
				return _('Unknown');
			}else {
				return money($cost, $account->get('Currency'));
			}

			break;
		case 'Materials Cost':
		case 'Energy Cost':
		case 'Other Cost':
		case 'Work Cost':
		case 'Operative Reward Amount':
			if ($this->data['Manufacture Task '.$key]=='')return '';
			return money($this->data['Manufacture Task '.$key], $account->get('Currency'));
			break;
		case 'Lower Target':
		case 'Upper Target':

			if ($this->data['Manufacture Task '.$key]=='')return '';
			return seconds_to_string($this->data['Manufacture Task '.$key]).' '._('per task');
			break;

		case 'Lower Target Per Hour':
		case 'Upper Target Per Hour':
			$key=preg_replace('/ Per Hour/', '', $key);
			if ($this->data['Manufacture Task '.$key]=='')return '';
			return number(3600/$this->data['Manufacture Task '.$key], 2).'/'._('hour');
			break;
		case 'Manufacture Task Lower Target Per Hour':
		case 'Manufacture Task Upper Target Per Hour':
			$key=preg_replace('/ Per Hour/', '', $key);
			if ($this->data[$key]=='')return '';
			return 3600/$this->data[$key];
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];

			if (array_key_exists('Manufacture Task '.$key, $this->data))
				return $this->data['Manufacture Task '.$key];

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


		$sql=sprintf("select `Manufacture Task Key` from `Manufacture Task Dimension` where `Manufacture Task Name`=%s", prepare_mysql($data['Manufacture Task Name']));

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Manufacture Task Key'];
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

		$this->duplicated=false;
		$this->new=false;

		$this->editor=$data['editor'];
		unset($data['editor']);
		$this->data=$data;

		$keys='';
		$values='';


		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";

			if (in_array($key, array('Manufacture Task Work Cost', 'Manufacture Task Materials Cost', 'Manufacture Task Energy Cost', 'Manufacture Task Other Cost', 'Manufacture Task Lower Target', 'Manufacture Task Upper Target'))) {
				$values.=','.prepare_mysql($value, true);
			}else {
				$values.=','.prepare_mysql($value, false);
			}
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Manufacture Task Dimension` ($keys) values ($values)";

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
				$this->msg=_('Manufacture task already exists');
			}else {
				$this->msg='Can not create manufacture task. '.$error_info[2];
			}






		}



	}






	function get_field_label($field) {

		switch ($field) {
		case 'Manufacture Task Code':
			$label=_('Code');
			break;
		case 'Manufacture Task Name':
			$label=_('name');
			break;
		case 'Manufacture Task Active':
			$label=_('active');
			break;
		case 'Manufacture Task Cost':
			$label=_('cost');
			break;
		case 'Manufacture Task Work Cost':
			$label=_('manpower cost');
			break;
		case 'Manufacture Task Energy Cost':
			$label=_('energy cost');
			break;
		case 'Manufacture Task Materials Cost':
			$label=_('materials cost');
			break;
		case 'Manufacture Task Other Cost':
			$label=_('other cost');
			break;
		case 'Manufacture Task Work Cost':
			$label=_('manpower cost');
			break;
		case 'Manufacture Task Targets':
			$label=_('targets');
			break;
		case 'Manufacture Task Lower Target':
			$label=_('lower target').' ('._('seconds').')';
			break;
		case 'Manufacture Task Upper Target':
			$label=_('upper target').' ('._('seconds').')';
			break;
		case 'Manufacture Task Lower Target Per Hour':
			$label=_('lower target').' ('._('task/h').')';
			break;
		case 'Manufacture Task Upper Target Per Hour':
			$label=_('upper target').' ('._('task/h').')';
			break;
		case 'Manufacture Task Operative Reward Terms':
			$label=_('employee reward terms');
			break;
		case 'Manufacture Task Operative Reward Allowance Type':
			$label=_('reward type');
			break;
		case 'Manufacture Task Operative Reward Amount':
			$label=_('reward amount');
			break;
		default:
			$label=$field;

		}

		return $label;

	}




}
