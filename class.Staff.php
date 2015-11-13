<?php
/*
 File: Staff.php

 This file contains the Staff Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

//require_once 'class.Name.php';
require_once 'class.Email.php';
require_once 'class.User.php';

class Staff extends DB_Table{

	function __construct($arg1=false, $arg2=false, $arg3=false) {
		global $db;
		$this->db=$db;

		$this->table_name='Staff';
		$this->ignore_fields=array('Staff Key');

		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return ;
		}
		if (preg_match('/^find/i', $arg1)) {

			$this->find($arg2, $arg3);
			return;
		}

		if (preg_match('/create|new/i', $arg1)) {

			$this->find($arg2, 'create');
			return;
		}
		$this->get_data($arg1, $arg2);



	}


	function get_data($key, $id) {

		if ($key=='alias')
			$sql=sprintf("select * from `Staff Dimension` where `Staff Alias`=%s", prepare_mysql($id));
		elseif ($key=='id')
			$sql=sprintf("select * from  `Staff Dimension`     where `Staff Key`=%d", $id);
		else
			return;

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Staff Key'];


	}


	function get($key) {


		if (!$this->id)
			return;



		switch ($key) {
		case('Telephone'):
			return $this->data['Staff Telephone Formated'];
			break;
		
		case('User Active'):
		if(array_key_exists('Staff User Active',$this->data)){
			switch ( $this->data['Staff User Active']) {
			case('Yes'):
				$formated_value=_('Yes');
				break;
			case('No'):
				$formated_value=_('No');
				break;

			default:
				$formated_value=$this->data['Staff User Active'];
			}

			return $formated_value;
			
			}else{
			    return _('No');
			}
			
			break;




		case('Staff Position'):
			return $this->get_positions();
			break;
		case('Position'):
			return $this->get_formated_positions();
			break;
		case('Staff Supervisor'):
			return $this->get_supervisors();
			break;
		case('Supervisor'):
			return $this->get_formated_supervisors();
			break;

		case ('Valid From'):
		case ('Valid To'):
		case ('Birthday'):
			return ($this->data['Staff '.$key]=='' or $this->data['Staff '.$key]=='0000-00-00 00:00:00') ?'':strftime("%Y-%m-%d", strtotime($this->data['Staff '.$key]));

			break;

		case('Currently Working'):

			switch ( $this->data['Staff Currently Working']) {
			case('Yes'):
				$formated_value=_('Yes');
				break;
			case('No'):
				$formated_value=_('No');
				break;

			default:
				$formated_value=$this->data['Staff Currently Working'];
			}

			return $formated_value;

			break;
		case('Type'):
			switch ( $this->data['Staff Type']) {
			case('Employee'):
				$type=_('Employee');
				break;
			case('Volunteer'):
				$type=_('Volunteer');
				break;
			case('Contractor'):
				$type=_('Contractor');
				break;
			case('TemporalWorker'):
				$type=_('Temporal Worker');
				break;
			case('WorkExperience'):
				$type=_('Work Experience');
				break;

			default:
				$type=$this->data['Staff Type'];
			}

			return $type;
			break;
		case('Formated ID'):
		case("ID"):
			return $this->get_formated_id();

		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Staff '.$key, $this->data))
				return $this->data['Staff '.$key];

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


		$sql=sprintf("select `Staff Key` from `Staff Dimension` where `Staff Alias`=%s", prepare_mysql($data['Staff Alias']));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->found_key=$row['Staff Key'];
			$this->get_data('id', $this->found_key);
		}


		if ($create and !$this->found) {

			$sql="select `Account Company Key` from `Account Dimension`";
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$company_key=$row['Account Company Key'];
				$company=new Company($company_key);
			}else {
				exit("Error no corporation\n");
			}



			$this->create($raw_data);

		}



	}


	function create($data) {





		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=_trim($value);
			}
		}






		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value, false);
		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Staff Dimension` ($keys) values ($values)";
		//print $sql;

		if (mysql_query($sql)) {

			$this->id=mysql_insert_id();
			$this->get_data('id', $this->id);




			if (!$this->data['Staff ID']) {
				$sql=sprintf("update `Staff Dimension` set `Staff ID`=%d where `Staff Key`=%d", $this->id, $this->id);
				mysql_query($sql);
			}


			$history_data=array(
				'History Abstract'=>sprintf(_('%s employee record created'), $this->data['Staff Alias']),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;


			$this->create_user();



		}else {
			// print "Error can not create staff $sql\n";
		}




	}


	function create_user($user_handle='') {


		if (!$user_handle) {
			$user_handle=$this->data['Staff Alias'];
		}

		$password=generatePassword(8, 10);
		$user_data=array(
			'User Handle'=>$user_handle,
			'User Alias'=>$this->data['Staff Name'],

			'User Password'=>hash('sha256', $password),
			'User Active'=>'No',
			'User Type'=>'Staff',

			'User Parent Key'=>$this->id,

		);

		$user= new User('find', $user_data, 'create');

		return $user;

	}


	function update_name($value, $options='') {

		if ($value=='') {
			$this->error=true;
			$this->msg='invalid value';
			return;
		}

		$this->get_user_data();
		$system_user=new User($this->data['Staff User Key']);
		if ($system_user->id) {

			$system_user->update(array('User Alias'=>$value), $options);
		}



		$this->update_field('Staff Name', $value);

	}





	function update_field_switcher($field, $value, $options='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {
        
        
		case('Staff Currently Working'):
			$this->update_is_working($value, $options);
			break;
		case('Staff Name'):
			$this->update_name($value);
			break;
		case('Staff Position'):
			$this->update_positions($value);
			break;
		case('Staff Supervisor'):
			$this->update_supervisors($value);
			break;
		case('Staff User Handle'):
		case('Staff User Password'):
		case('Staff User PIN'):
		case('Staff User Active'):

			$this->get_user_data();
			$system_user=new User($this->data['Staff User Key']);

			$user_field=preg_replace('/^Staff /', '', $field);

			$system_user->update(array($user_field=>$value), $options);
			$this->error=$system_user->error;
			$this->msg=$system_user->msg;
			$this->updated=$system_user->updated;

			break;

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();
		$this->get_user_data();
	}


	function update_is_working($value, $options) {





		$this->update_field('Staff Currently Working', $value, $options);

		if ($value=='No' ) {
			$this->update_field('Staff Valid To', gmdate('Y-m-d H:i:s'), 'no_history');
		}else {
			$this->update_field('Staff Valid To', '', 'no_history');

		}

		$this->other_fields_updated=array(
			'Staff_Valid_To'=>array(
				'field'=>'Staff_Valid_To',
				'render'=>($this->get('Staff Currently Working')=='Yes'?false:true),
				'value'=>$this->get('Staff Valid To'),
				'formated_value'=>$this->get('Valid To'),


			)
		);

	}


	function get_name() {
		return $this->data['Staff Name'];
	}


	function update_positions($values) {

		$positions=array();
		$sql=sprintf('select `Company Position Key` from `Company Position Dimension`  ');
		foreach ($this->db->query($sql) as $row) {
			$positions[$row['Company Position Key']]=false;
		}

		foreach (preg_split('/,/', $values) as $selected_position) {
			$positions[$selected_position]['selected']=true;
		}

		foreach ($positions as $key=>$value) {
			if ($value) {
				$this->add_position($key);
			}else {
				$this->remove_position($key);
			}
		}

	}


	function remove_position($position_key) {

		$sql=sprintf("delete from  `Company Position Staff Bridge` where `Position Key`=%d and `Staff Key`=%d", $position_key, $this->id);
		if (mysql_query($sql)) {
			$this->updated=true;
		}
	}


	function add_position($value) {
		$updated=false;
		$sql=sprintf("insert into `Company Position Staff Bridge` (`Position Key`, `Staff Key`) values (%d, %d)   ON DUPLICATE KEY UPDATE  `Position Key`= %d", $value, $this->id, $value);
		// print $sql."\n";
		if (mysql_query($sql)) {
			$this->update=true;
		}
	}


	function get_positions() {
		$positions='';
		$sql=sprintf('select GROUP_CONCAT(`Company Position Key`) as positions  from `Company Position Dimension` CPD left join `Company Position Staff Bridge` B on (B.`Position Key`=CPD.`Company Position Key`) where  `Staff Key`=%d ', $this->id);

		if ($row = $this->db->query($sql)->fetch()) {
			$positions=$row['positions'];
		}
		return $positions;
	}


	function get_formated_positions() {

		$positions='';
		$sql=sprintf('select GROUP_CONCAT(`Company Position Title`  order by `Company Position Title` separator ", ") as positions  from `Company Position Dimension` CPD left join `Company Position Staff Bridge` B on (B.`Position Key`=CPD.`Company Position Key`)  where  `Staff Key`=%d  ', $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$positions=$row['positions'];
		}
		return $positions;
	}


	function update_supervisors($values) {

		$supervisors=array();
		$sql=sprintf('select `Staff Key` from `Staff Dimension`  ');
		foreach ($this->db->query($sql) as $row) {
			$supervisors[$row['Staff Key']]=false;
		}

		foreach (preg_split('/,/', $values) as $selected_supervisor) {
			if (is_numeric($selected_supervisor)  and array_key_exists($selected_supervisor, $supervisors) )
				$supervisors[$selected_supervisor]=true;
		}



		foreach ($supervisors as $key=>$value) {
			if ($value) {
				$this->add_supervisor($key);
			}else {
				$this->remove_supervisor($key);
			}
		}

	}



	function remove_supervisor($supervisor_key) {

		$sql=sprintf("delete from  `Staff Supervisor Bridge` where `Supervisor Key`=%d and `Staff Key`=%d", $supervisor_key, $this->id);
		if (mysql_query($sql)) {
			$this->updated=true;
		}
	}


	function add_supervisor($value) {
		$updated=false;
		$sql=sprintf("insert into `Staff Supervisor Bridge` (`Supervisor Key`, `Staff Key`) values (%d, %d)   ON DUPLICATE KEY UPDATE  `Supervisor Key`= %d", $value, $this->id, $value);
		if (mysql_query($sql)) {
			$this->update=true;
		}
	}


	function get_supervisors() {
		$supervisors='';
		$sql=sprintf('select GROUP_CONCAT(B.`Supervisor Key`) as supervisors  from `Staff Supervisor Bridge` B where  `Staff Key`=%d ', $this->id);

		if ($row = $this->db->query($sql)->fetch()) {
			$supervisors=$row['supervisors'];
		}
		return $supervisors;
	}


	function get_formated_supervisors() {

		$supervisors='';
		$sql=sprintf('select GROUP_CONCAT(`Staff Alias`  order by `Staff Alias` separator ", ") as supervisors   from  `Staff Supervisor Bridge` B left join `Staff Dimension` S on (B.`Supervisor Key`=S.`Staff Key`)  where  B.`Staff Key`=%d ', $this->id);
		if ($row = $this->db->query($sql)->fetch()) {

			$supervisors=$row['supervisors'];
		}

		$supervisors=preg_replace('/, $/', '', $supervisors);
		return $supervisors;
	}



	function get_user_data() {

		$sql=sprintf('select * from `User Dimension` where `User Type`="Staff" and `User Parent Key`=%d ', $this->id);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data['Staff '.$key]=$value;
				// print "$key -> $value\n";
			}
		}



	}


}



?>
