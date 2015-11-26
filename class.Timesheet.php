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
		else {
			return;
		}

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Timesheet Key'];
		}


	}





	function get($key='') {



		switch ($key) {


		case 'Clocked Hours':

			$hours=$this->data['Timesheet '.$key];

			return  sprintf(ngettext("%.2f hr", "%.2f hrs", $hours), $hours); // 1 okno


			break;
		case 'IsoDate':
			return $this->data['Timesheet Date']!=''?date("Y-m-d", strtotime($this->data['Timesheet Date'])):'';


			break;

		case 'Date':
			return $this->data['Timesheet Date']!=''?strftime("%a %e %b %Y", strtotime($this->data['Timesheet Date'])):'';

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

			$this->get_data('id', $this->id);
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


	function create_timesheet_record($data) {

		$data['Timesheet Record Timesheet Key']=$this->id;
		$data['Timesheet Record Staff Key']=$this->data['Timesheet Staff Key'];
		$data['editor']=$this->editor;
		$timesheet_record=new Timesheet_Record('new', $data);

	
		if ($timesheet_record->new) {


			$this->process_records_action_type();
			$this->update_clocked_hours();
			$this->update_clocking_records();

		}else{
		    $this->error=true;
		    $this->msg=$timesheet_record->msg;
		   
		    
		}

		return $timesheet_record;

	}


	function get_field_label($field) {

		switch ($field) {

		case 'Timesheet Clocked Hours':
			$label=_('Clocked');
			break;
		case 'Timesheet Date':
			$label=_('date');
			break;
		default:
			$label=$field;

		}

		return $label;

	}


	function get_staff_data() {

		$sql=sprintf('select * from `Staff Dimension` where  `Staff Key`=%d ', $this->data['Timesheet Staff Key']);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data['Timesheet '.$key]=$value;
			}
		}



	}


	function update_clocking_records() {

		$clocking_records=0;
		$ignored_clocking_records=0;


		$sql=sprintf('select count(*) num,`Timesheet Record Ignored` from `Timesheet Record Dimension` where `Timesheet Record Timesheet Key`=%d and `Timesheet Record Type`="ClockingRecord" group by `Timesheet Record Ignored`  ',
			$this->id
		);

		if ($result=$this->db->query($sql)) {

			while ($row = $result->fetch()) {
				if ($row['Timesheet Record Ignored']=='Yes')
					$ignored_clocking_records=$row['num'];

				else
					$clocking_records=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;

		}

		$this->update(array('Timesheet Clocking Records'=>$clocking_records), 'no_history');
		$this->update(array('Timesheet Ignored Clocking Records'=>$ignored_clocking_records), 'no_history');


	}


	function update_clocked_hours() {
		$action_type='Start';

		$clocked_seconds=0;

		$sql=sprintf('select `Timesheet Record Date`,`Timesheet Record Key`, UNIX_TIMESTAMP(`Timesheet Record Date`) date from `Timesheet Record Dimension` where `Timesheet Record Timesheet Key`=%d and `Timesheet Record Ignored`="No"  and `Timesheet Record Type`="ClockingRecord" order by `Timesheet Record Date`',
			$this->id
		);

		if ($result=$this->db->query($sql)) {

			foreach ($result as $row) {
				//print_r($row);





				if ($action_type=='Start') {
					$start_date=$row['date'];


					$action_type='End';
				}else {

					$end_date=$row['date'];
					$clocked_seconds=$clocked_seconds+($end_date-$start_date);
					$action_type='Start';

				}

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->update(array('Timesheet Clocked Hours'=>($clocked_seconds/3600)), 'no_history');

	}


	function process_records_action_type() {

		$action_type='Start';

		$sql=sprintf('select `Timesheet Record Date`,`Timesheet Record Key` from `Timesheet Record Dimension` where `Timesheet Record Timesheet Key`=%d and `Timesheet Record Ignored`="No"  and `Timesheet Record Type`="ClockingRecord" order by `Timesheet Record Date`',
			$this->id

		);

		if ($result=$this->db->query($sql)) {

			foreach ($result as $row) {



				$sql=sprintf("update `Timesheet Record Dimension` set `Timesheet Record Action Type`=%s where `Timesheet Record Key`=%d  ",
					prepare_mysql($action_type),
					$row['Timesheet Record Key']
				);
				$this->db->exec($sql);

				if ($action_type=='Start') {
					$action_type='End';
				}else {
					$action_type='Start';

				}

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


}
