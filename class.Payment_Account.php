<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 17:00:48 CEST, Malaga , Spain

 Version 2.0
*/


class Payment_Account extends DB_Table {


	function Payment_Account($arg1=false,$arg2=false) {

		$this->table_name='Payment Account';
		$this->ignore_fields=array('Payment Account Key');

		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return ;
		}
		if (preg_match('/^(create|new)/i',$arg1)) {
			$this->find($arg2,'create');
			return;
		}
		if (preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg1);
			return;
		}
		$this->get_data($arg1,$arg2);
		return ;

	}



	function get_data($tipo,$tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Payment Account Dimension` where `Payment Account Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Payment Account Key'];


	}


	function find($raw_data,$options) {

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();



		foreach ( $raw_data as $key=> $value) {
			if (array_key_exists($key,$data))
				$data[$key]=$value;

		}

		// print_r($raw_data);
		//  print_r($data);
		//  exit("s");


		$fields=array('Payment Account Code',);

		$sql=sprintf("select * from `Payment Account Dimension` where true  ");
		foreach ($fields as $field) {
			$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
		}
		//print $sql;

		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==0) {
			// address not found
			$this->found=false;


		} else if ($num_results==1) {
				$row=mysql_fetch_array($result, MYSQL_ASSOC);

				$this->get_data('id',$row['Payment Account Key']);
				$this->found=true;
				$this->found_key=$row['Payment Account Key'];

			} else {// Found in mora than one
			print("Warning several payments accounts $sql\n");
			$row=mysql_fetch_array($result, MYSQL_ASSOC);

			$this->get_data('id',$row['Payment Account Key']);
			$this->found=true;
			$this->found_key=$row['Payment Account Key'];


		}

		if (!$this->found and $create) {
			$this->create($data);

		}


	}



	function get($key='') {



		if (isset($this->data[$key]))
			return $this->data[$key];

		switch ($key) {
		}
		$_key=ucfirst($key);
		if (isset($this->data[$_key]))
			return $this->data[$_key];
		print "Error $key not found in get from Payment Account\n";
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';

		foreach ($this->data as $key=>$value) {


			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value,false);


		}



		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Payment Account Dimension` ($keys) values ($values)";
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
		} else {
			print "Error can not create payment account\n";
			exit;

		}
	}


	function in_site($site_key) {
		$is_in_site=false;
		$sql=sprintf("select count(*) as num from `Payment Account Site Bridge` where `Site Key`=%d and `Payment Account Key`=%d ",
			$site_key,
			$this->id
		);
		
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']) {
				$is_in_site=true;
			}
		}

		return $is_in_site;
	}
	
	
		function is_active_in_site($site_key) {
		$is_active_in_site=false;
		$sql=sprintf("select count(*) as num from `Payment Account Site Bridge` where `Site Key`=%d and `Payment Account Key`=%d and `Status`='Active'  ",
			$site_key,
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']) {
				$is_active_in_site=true;
			}
		}

		return $is_active_in_site;
	}
	
	
	
	
	

}
