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
		if (array_key_exists($key, $this->data))
			return $this->data[$key];
		switch ($key) {
    
        case ('Valid From'):
        
            return (($this->data['Staff Valid From']=='' or $this->data['Staff Valid From']=='0000-00-00 00:00:00') ?'':strftime("%Y-%m-%d",strtotime($this->data['Staff Valid From'])));
            
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
		case('First Name'):
			if (!is_object($this->contact))
				$this->contact=new Contact($this->data['Staff Contact Key']);
			if ($this->contact->id)
				return $this->contact->data['Contact First Name'];
			else
				return '';
			break;
		case('Surname'):
			if (!is_object($this->contact))
				$this->contact=new Contact($this->data['Staff Contact Key']);
			if ($this->contact->id)
				return $this->contact->data['Contact Surname'];
			else
				return '';
			break;
		case('Email'):
			if (!is_object($this->contact))
				$this->contact=new Contact($this->data['Staff Contact Key']);
			if ($this->contact->id)
				return strip_tags($this->contact->data['Contact Main XHTML Email']);
			else
				return '';
			break;


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

			$data=array(
				'Staff Address Line 1'=>'',
				'Staff Address Town'=>'',
				'Staff Address Line 2'=>'',
				'Staff Address Line 3'=>'',
				'Staff Address Postal Code'=>'',
				'Staff Address Country Code'=>'',
				'Staff Address Country Name'=>$company->data['Company Main Country'],
				'Staff Address Country First Division'=>'',
				'Staff Address Country Second Division'=>''
			);

			foreach ($raw_data as $key=>$value) {
				$data[$key]=$value;
			}


			$this->create($data);

		}



	}


	function create($data) {
		$sql="select `Account Company Key` from `Account Dimension`";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$company_key=$row['Account Company Key'];
			$company=new Company($company_key);
		}else {
			exit("Error no corporation\n");
		}




		$child=new Contact ('find in staff create update', $data);



		if ($child->error) {
			$this->error=true;
			$this->error=$child->error;
			return;
		}




		$company->create_contact_bridge($child->id);
		$data['Staff Contact Key']=$child->id;


		$contact=new Contact($data['Staff Contact Key']);
		$data['Staff Name']=$contact->display('name');



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


			$sql=sprintf("insert into `Contact Bridge` values (%d,'Staff',%d,'Yes','Yes')", $child->id, $this->id);
			mysql_query($sql);


			if (!$this->data['Staff ID']) {
				$sql=sprintf("update `Staff Dimension` set `Staff ID`=%d where `Staff Key`=%d", $this->id, $this->id);
				mysql_query($sql);
			}


			$history_data=array(
				'History Abstract'=>_('Staff Created')
				, 'History Details'=>_trim(_('New staff')." \"".$this->data['Staff Name']."\"  "._('added'))
				, 'Action'=>'created'
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


	function update_name($value) {

		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Name');
			return;
		}

		$contact=new Contact($this->data['Staff Contact Key']);
		$contact->editor=$this->editor;
		$contact->update(array('Contact Name'=>$value));


		if ($contact->updated) {

			$this->updated=true;
			$this->new_value=$contact->new_value;
			$this->data['Staff Name']=$value;
		}

	}


	function update_alias($value) {
		$this->update_field('Staff Alias', $value, '');
	}


	function update_field_switcher($field, $value, $options='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {

		case('Staff Currently Working'):
			$this->update_is_working($value, $options);
			break;

		case('Staff Alias'):
			$this->update_alias($value);
			break;
		case('Staff Name'):
		case('name'):
			$this->update_name($value);
			break;
		case('Staff Position'):
			$this->update_positions($value);
			break;
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
	}


	function update_is_working($value, $options) {


		if ($value=='No' and $this->data['Staff Currently Working']=='Yes') {



			$this->update_field('Staff Valid To', gmdate('Y-m-d H:i:s'), 'no_history');
		}


		$this->update_field('Staff Currently Working', $value, $options);





	}


	function get_name() {
		return $this->data['Staff Name'];
	}


	function update_positions($values) {

		foreach ($values as $key=>$value) {
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


	function get_other_telecoms_data($type='Telephone') {

		$sql=sprintf("select B.`Telecom Key`,`Telecom Description` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Telecom Type`=%s  and `Subject Type`='Staff' and `Subject Key`=%d ",
			prepare_mysql($type),
			$this->id
		);
		//print $sql;
		$telecom_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Telecom Key']!=$this->data["Staff Main $type Key"]) {

				$telecom=new Telecom($row['Telecom Key']);

				$telecom_keys[$row['Telecom Key']]= array(
					'number'=>$telecom->display('plain'),
					'xhtml'=>$telecom->display('xhtml'),
					'label'=>$row['Telecom Description']
				);

			}
		}
		return $telecom_keys;

	}


	function get_other_telephones_data() {
		return $this->get_other_telecoms_data('Telephone');
	}


	function get_other_faxes_data() {
		return $this->get_other_telecoms_data('FAX');
	}


	function get_other_mobiles_data() {
		return $this->get_other_telecoms_data('Mobile');
	}


	function get_other_emails_data() {

		$sql=sprintf("select B.`Email Key`,`Email`,`Email Description`,`User Key` from
        `Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)
        left join `User Dimension` U on (`User Handle`=E.`Email` and `User Type`='Staff' and `User Parent Key`=%d )
        where  `Subject Type`='STAFF' and `Subject Key`=%d "
			, $this->id
			, $this->id
		);

		$email_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Email Key']!=$this->data['Staff Main Email Key'])
				$email_keys[$row['Email Key']]= array(
					'email'=>$row['Email'],
					'key'=>$row['Email Key'],
					'xhtml'=>'<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
					'label'=>$row['Email Description'],
					'user_key'=>$row['User Key']
				);
		}
		return $email_keys;

	}


}



?>
