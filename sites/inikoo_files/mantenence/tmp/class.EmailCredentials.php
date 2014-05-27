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
	var $updated_data=array();

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
			if ( $this->updated==true) {
				$this->updated_data[]=array('key'=>$key,'new_value'=>$this->new_value);
			}
			$this->updated=false;



		}

		if (count($this->updated_data)==0 and $this->msg=='')
			$this->msg.=_('Nothing to be updated')."\n";

	}


	function get_amazon_keys($key) {
		$password=$this->data['Email Address Amazon Mail'];
	
		$password=AESDecryptCtr(base64_decode($password),$key,256);
		$password=preg_replace('/^\_Hello \:\)/','',$password);
		return $password;
	}

	function get_password($key) {
		
		switch($this->data['Email Provider']){
			case 'Gmail':
				$password=$this->data['Password Gmail'];
			break;
			case 'Other':
				$password=$this->data['Password Other'];
			break;
		}

		$password=AESDecryptCtr(base64_decode($password),$key,256);
		$password=preg_replace('/^\_Hello \:\)/','',$password);
		return $password;
	}

	function encrypt_password($password) {

		$salt='_Hello :)';
		$value=base64_encode(AESEncryptCtr($salt.$password,CKEY,256));
		return $value;
	}


	function update_field_switcher($field,$value,$options='') {


		$base_data=$this->base_data();

		if ($field=='Password') {
		
			$salt='_Hello :)';
//print $salt.$value;exit;
			$value=base64_encode(AESEncryptCtr($salt.$value,CKEY,256));
		
		}



		if (array_key_exists($field,$base_data)) {

			if ($value!=$this->data[$field]) {
				$this->update_field($field,$value,$options);

			}
		}

	}


	function cure_data($data) {

		switch ($data['Email Provider']) {
		case('Gmail'):
			//$data['Incoming Mail Server']='imap.gmail.com:993/imap/ssl/novalidate-cert';
			//$data['Outgoing Mail Server']='smtp.gmail.com';
			break;
		default:
		}
		return $data;

	}

	function get_amazon_Data(){
		return array('email'=>$this->data['Email Address Amazon Mail'], 'access_key'=>$this->data['Amazon Access Key'], 'secret_key'=>$this->data['Amazon Secret Key']);
	}

	function save_amazon_data($email, $secret_key, $access_key){
		
	}


	function get_smtp_data(){
		switch($this->data['Email Provider']){
			case 'Gmail' :
				return array('login'=>$this->data['Email Address Gmail'], 'password'=>$this->get_password(CKEY), 'outgoing_server'=>'smtp.gmail.com');
			break;
			case 'Other':
				return array('login'=>$this->data['Email Address Other'], 'password'=>$this->get_password(CKEY), 'outgoing_server'=>$this->data['Outgoing Mail Server']);
			break;
		}
	
	}

	function get_direct_mail_data(){
		return $this->data['Email Address Direct Mail'];
	}

	function save_gmail_data($login, $password){
		$password=$this->encrypt_password($password);
		$sql=sprintf("insert into `Email Credentials Dimension` (`Email Credentials Method`, `Email Provider`, `Email Address Gmail`, `Password Gmail`, `Outgoing Mail Server`) values ('%s', '%s', '%s','%s', '%s')", 'SMTP', 'Gmail', $login, $password, 'smtp.gmail.com');
		mysql_query($sql);

	}

	function save_other_data($email, $login, $password, $incoming_server, $outgoing_server){
		$password=$this->encrypt_password($password);
		$sql=sprintf("insert into `Email Credentials Dimension` (`Email Credentials Method`, `Email Provider`, `Email Address Other`, `Login Other`, `Password Other`, `Incoming Mail Server`, `Outgoing Mail Server`) values ('%s', '%s', '%s','%s','%s', '%s', '%s')", 'SMTP', 'Other', $email, $login, $password, $incoming_server, $outgoing_server);
		mysql_query($sql);
		
	}

	function create($data) {

		$this->new=false;

		$data=$this->cure_data($data);
		$base_data=$this->base_data();




		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$base_data)) {
				if ($key=='Password') {
					$value=$this->encrypt_password($value);
				}
				$base_data[$key]=_trim($value);

			}
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Email Credentials Dimension` %s %s",$keys,$values);
//print $sql;
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
