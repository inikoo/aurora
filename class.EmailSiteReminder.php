<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2014 11:55:34 CET, Malaga Spain

 Copyright (c) 2014, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class EmailSiteReminder extends DB_Table{

	function EmailSiteReminder($a1,$a2=false,$a3=false) {

		$this->table_name='Email Site Reminder';
		$this->ignore_fields=array('Email Site Reminder Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		}elseif ($a1=='find') {
			$this->find($a2,$a3);

		}else
			$this->get_data($a1,$a2);
	}


	function get_data($key,$tag) {

		if ($key=='id') {
			$sql=sprintf("select * from `Email Site Reminder Dimension` where `Email Site Reminder Key`=%d",$tag);


		}else {
			return;
		}

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Email Site Reminder Key'];

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
		$this->found_key=false;

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);
		}




		if ($data['Email Site Reminder Subject']=='User') {

			$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`=%s and  `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
				prepare_mysql($data['Trigger Scope']),
				$data['Trigger Scope Key'],
				$data['User Key']
			);

		}elseif ($data['Email Site Reminder Subject']=='User') {
			$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`=%s and  `Trigger Scope Key`=%d and `Customer Key`=%d and ``Email Site Reminder In Process`='Yes'",
				prepare_mysql($data['Trigger Scope']),
				$data['Trigger Scope Key'],
				$data['Customer Key']
			);

		}else {
			return;
		}


		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Email Site Reminder Key'];
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}
		if ($this->found)
			$this->get_data('id',$this->found_key);

		if ($update and $this->found) {

		}


	}

	function create($data) {
		$this->new=false;
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Email Site Reminder Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Email site reminder created");
			$this->get_data('id',$this->id);
			$this->new=true;




			return;
		}else {
			$this->msg=_("Error can not create email reminder");
		}
	}


	function get($key,$data=false) {
		switch ($key) {

		default:

			if (array_key_exists($key,$this->data))
				return $this->data[$key];
			else {

				return $key;
			}
		}
		return '';
	}


	function cancel() {
		$this->cancelled=false;
		if ($this->data['Email Site Reminder State']=='Send') {
			$this->msg=_('Email reminder already send');
			return;
		}
		if ($this->data['Email Site Reminder State']=='Cancelled') {
			$this->msg=_('Email reminder already cancelled');
			$this->canceled=true;
			return;
		}

		$sql=sprintf("update `Email Site Reminder Dimension` set `Email Site Reminder State`='Cancelled' ,`Email Site Reminder In Process`='No' ,`Finish Date`=%s where `Email Site Reminder Key`=%d ",
			prepare_mysql(gmdate("Y-m-d H:i:s")),
			$this->id

		);

		mysql_query($sql);
		$this->cancelled=true;

	}

	function mark_as_send() {

		$this->send=false;

		if ($this->data['Email Site Reminder State']=='Send') {
			$this->msg=_('Email reminder already send');
			return;
		}
		if ($this->data['Email Site Reminder State']=='Cancelled') {
			$this->msg=_('Email reminder is cancelled');
			$this->canceled=true;
			return;
		}


		$sql=sprintf("update `Email Site Reminder Dimension` set `Email Site Reminder State`='Send' ,`Email Site Reminder In Process`='No' ,`Finish Date`=%s where `Email Site Reminder Key`=%d ",
			prepare_mysql(gmdate("Y-m-d H:i:s")),
			$this->id

		);
		//print "$sql\n";
		mysql_query($sql);
		$this->send=true;



	}


}
?>
