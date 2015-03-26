<?php
/*

 This file contains the Voucher Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: Tue 17th March 2015 13:01, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
 include_once 'class.DB_Table.php';

 class Voucher extends DB_Table {

 	function Voucher($a1,$a2=false,$a3=false) {

 		$this->table_name='Voucher';
 		$this->ignore_fields=array('Voucher Key');

 		if (is_numeric($a1) and !$a2) {
 			$this->get_data('id',$a1);
 		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
 			$this->find($a2,'create');

 		}
 		elseif (preg_match('/find/i',$a1))
 			$this->find($a2,$a1);
 		else
 			$this->get_data($a1,$a2,$a3);

 	}

 	function get_data($tipo,$tag,$tag2=false) {

 		if ($tipo=='id')
 			$sql=sprintf("select * from `Voucher Dimension` where `Voucher Key`=%d",$tag);
 		elseif ($tipo=='code_store')
 			$sql=sprintf("select * from `Voucher Dimension` where `Voucher Code`=%s and `Voucher Store Key`=%d",
 				prepare_mysql($tag),
 				$tag2
 				);


 		$result=mysql_query($sql);

 		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
 			$this->id=$this->data['Voucher Key'];
 		}

 	}






 	function find($raw_data,$options) {

 		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
 			foreach ($raw_data['editor'] as $key=>$value) {

 				if (array_key_exists($key,$this->editor))
 					$this->editor[$key]=$value;

 			}
 		}

 		$this->candidate=array();
 		$this->found=false;
 		$this->found_key=0;
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
 				$data[$key]=$value;

 		}



 		$sql=sprintf("select `Voucher Key` from `Voucher Dimension` where  `Voucher Code`=%s and `Voucher Store Key`=%d ",
 			prepare_mysql($data['Voucher Code']),
 			$data['Voucher Store Key']
 			);



 		$result=mysql_query($sql);
 		$num_results=mysql_num_rows($result);
 		if ($num_results==1) {
 			$row=mysql_fetch_array($result, MYSQL_ASSOC);
 			$this->found=true;
 			$this->found_key=$row['Voucher Key'];

 		}
 		if ($this->found) {
 			$this->get_data('id',$this->found_key);
 		}


 		if ($create and !$this->found) {
 			$this->create($data);

 		}


 	}


 	function create($data) {

 		$keys="";

 		$values="";
 		foreach ($data as $key=>$value) {
 			$keys.="`$key`,";
 			if ($key=='Voucher Description') {
 				$values.=prepare_mysql($value,false).",";
 			}else {
 				$values.=prepare_mysql($value).",";
 			}
 		}
 		$keys=preg_replace('/,$/','',$keys);
 		$values=preg_replace('/,$/','',$values);



		// print_r($data);
 		$sql=sprintf("insert into `Voucher Dimension` (%s) values(%s)",$keys,$values);

 		if (mysql_query($sql)) {
 			$this->id = mysql_insert_id();
 			$this->get_data('id',$this->id);
 			$this->new=true;

 		} else {
 			print "Error can not create voucher:\n$sql\n";
 			exit;

 		}
 	}


 	function get($key='') {

 		if (isset($this->data[$key]))
 			return $this->data[$key];

 		switch ($key) {

 		}

 		return false;
 	}





 }

 ?>
