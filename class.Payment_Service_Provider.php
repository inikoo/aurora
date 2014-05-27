<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 17:04:23 CEST, Malaga , Spain

 Version 2.0
*/


class Payment_Service_Provider extends DB_Table {


	function Payment_Service_Provider($arg1=false,$arg2=false) {

		$this->table_name='Payment Service Provider';
		$this->ignore_fields=array('Payment Service Provider Key');

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
			$sql=sprintf("select * from `Payment Service Provider Dimension` where `Payment Service Provider Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Payment Service Provider Key'];


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


		$fields=array('Payment Service Provider Code');

		$sql=sprintf("select * from `Payment Service Provider Dimension` where true  ");
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

				$this->get_data('id',$row['Payment Service Provider Key']);
				$this->found=true;
				$this->found_key=$row['Payment Service Provider Key'];

			} else {// Found in mora than one
			print("Warning several payment service providers $sql\n");
			$row=mysql_fetch_array($result, MYSQL_ASSOC);

			$this->get_data('id',$row['Payment Service Provider Key']);
			$this->found=true;
			$this->found_key=$row['Payment Service Provider Key'];


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
		print "Error $key not found in get from Payment Service Provider\n";
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';

		foreach ($this->data as $key=>$value) {
			if ($key=='Payment Service Provider XHTML Address')
				continue;
			
			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value,false);
			

		}



		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Payment Service Provider Dimension` ($keys) values ($values)";
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
		} else {
			print "Error can not create payment service provider\n";
			exit;

		}
	}

	

}
