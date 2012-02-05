<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class EmailCredentials extends DB_Table{

	var $areas=false;
	var $locations=false;

	function EmailCredentials($a1=false,$a2=false,$a3=false) {

		$this->table_name='Email Credentials';
		$this->ignore_fields=array('Email Credentials Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		}elseif ($a1=='find') {
			$this->find($a2,$a3);

		}else
			$this->get_data($a1,$a2);
	}


	function get_data($key,$tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Email Credentials Dimension` where `Email Credentials Key`=%d",$tag);

		else
			return;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Email Credentials Key'];

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


		if ($data['Email Provider']=='' ) {
			$this->error=true;
			$this->msg='Email Provider';
			return;
		}



		if ($data['Login']=='' ) {
			$this->error=true;
			$this->msg='Email Login';
			return;
		}
		if ($data['Email Address']=='' ) {
			$this->error=true;
			$this->msg='Email Address';
			return;
		}


		$sql=sprintf("select `Email Credentials Key` from `Email Credentials Dimension` where `Email Address`=%s  "
			,prepare_mysql($data['Email Address'])
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Email Credentials Key'];
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}
		if ($this->found)
			$this->get_data('id',$this->found_key);

		if ($update and $this->found) {

			$this->update($data);
		}


	}


	public function update($data,$options='') {
		$data=$this->cure_data($data);
		if (!is_array($data)) {

			$this->error=true;
			return;
		}

		if (isset($data['editor'])) {

			foreach ($data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}



		foreach ($data as $key=>$value) {


			if (is_string($value))
				$value=_trim($value);

			$this->update_field_switcher($key,$value,$options);


		}

		if (!$this->updated and $this->msg=='')
			$this->msg.=_('Nothing to be updated')."\n";

	}

	function cure_data($data) {
		switch ($data['Email Provider']) {
		case('Gmail'):
			$data['Incoming Mail Server']='imap.gmail.com:993/imap/ssl/novalidate-cert';
			$data['Outgoing Mail Server']='smtp.gmail.com';
			$data['Login']=$data['Email Address'];
			break;
			defaut:
		}
		return $data;

	}


	function create($data) {

		$this->new=false;

		$data=$this->cure_data($data);
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
		$sql=sprintf("insert into `Email Credentials Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Email Credentials Added");
			$this->get_data('id',$this->id);
			$this->new=true;


			return;
		}else {
			$this->msg=_(" Error can not create email credentials");
		}
	}





	function get($key,$data=false) {
		switch ($key) {

		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
			else
				return '';
		}
		return '';
	}
	function delete() {

		$sql=sprintf("delete from `Email Credentials Dimension` where `Email Credentials Key`=%d",$this->id);
		mysql_query($sql);
	}

}

?>
