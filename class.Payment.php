<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 26 May 2014 16:55:05 CEST, Malaga , Spain

 Version 2.0
*/


class Payment extends DB_Table {


	function Payment($arg1=false,$arg2=false) {

		$this->table_name='Payment';
		$this->ignore_fields=array('Payment Key');

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
			$sql=sprintf("select * from `Payment Dimension` where `Payment Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Payment Key'];


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




$this->found=false;


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
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';




		foreach ($this->data as $key=>$value) {
			
			
			
			
			$keys.=",`".$key."`";
			
			
			if($key=='Payment Completed Date' or $key=='Payment Last Updated Date' ){
			$values.=','.prepare_mysql($value,true);

			}else{
			$values.=','.prepare_mysql($value,false);
			}

		}



		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Payment Dimension` ($keys) values ($values)";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
		} else {
			print "Error can not create payment\n";
			exit;

		}
	}

	


}
