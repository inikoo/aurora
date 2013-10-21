<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Company.php';

class Account extends DB_Table{

	function Account($a1=false,$a2=false) {

		$this->table_name='Account';

		if ($a1=='create') {
			$this->create($a2);

		}else
			$this->get_data();
	}
	function get_data() {


		$sql=sprintf("select * from `Account Dimension` ");


		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=1;


			$this->company=new Company($this->data['Account Company Key']);
		}




	}
	function create($data) {
		$this->new=false;

		$company=new Company('find create auto',$data);

		$data['Account Company Key']=$company->id;
		$data['Account Company Name']=$company->data['Company Name'];
$data['Account Country Code']=$company->data['Company Main Country Code'];

$address=new Address($company->data['Company Main Address Key']);

$data['Account Country Code']=$company->data['Company Main Country Code'];
$data['Account Country 2 Alpha Code']=$address->data['Address Country 2 Alpha Code'];

		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			
			if($key=='Short Message')
						$values.=prepare_mysql($value,false).",";

			else
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("delete * from  `Account Dimension` " );
		mysql_query($sql);
		$sql=sprintf("insert into `Account Dimension` %s %s",$keys,$values);
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Account Added");
			$this->get_data();
			$this->new=true;



			return;
		}else {
			$this->msg="Error can not create account\n";
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


	protected function update_field_switcher($field,$value,$options='') {


		switch ($field) {
		case 'Company Name':

		case 'Account Name':
			$this->update_company_name($value);
			break;
		case('Account Currency'):
			$this->update_currency($value);
			break;
		default:
			$this->company->update_field_switcher($field,$value,$options);
			break;
		}
	}


	function update_name($value) {


		$sql=sprintf("update `Account Dimension` set `Account Name`=%s",prepare_mysql($value));
		mysql_query($sql);

		$this->updated=true;
		$this->new_value=$value;
	}





	function update_company_name($value) {

		$this->company->update_field_switcher('Company Name',$value);

		$this->updated=$this->company->updated;
		$this->new_value=$this->company->data['Company Name'];

	}


	function update_currency($value) {
		$value=strtoupper($value);
		$sql=sprintf("select * from kbase.`Currency Dimension` where `Currency Code`=%s",prepare_mysql($value));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("update `Account Dimension` set `Account Currency`=%s",prepare_mysql($value));
			mysql_query($sql);

			$this->updated=true;
			$this->new_value=$value;

		}else {
			$this->error=true;
			$this->msg='Currency Code '.$value.' not valid';

		}
	}
	
	
		function add_account_history($history_key,$type=false){
			$this->post_add_history($history_key,$type=false);
		}
	
		function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Account History Bridge` (`History Key`,`Type`) values (%d,%s)",
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);
		//print $sql;
	}

}

?>
