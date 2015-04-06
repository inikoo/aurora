<?php
/*
  This file contains the List Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Creates: 6 April 2015 14:31:11 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class SubjectList extends DB_Table{

	var $areas=false;
	var $locations=false;

	function SubjectList($a1,$a2=false,$a3=false) {

		$this->table_name='List';
		$this->ignore_fields=array('List Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		}elseif ($a1=='find') {
			$this->find($a2,$a3);

		}else
			$this->get_data($a1,$a2);
	}


	function get_data($key,$tag) {

		if ($key=='id')
			$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$tag);

		elseif ($key=='name')
			$sql=sprintf("select  *  from `List Dimension` where `List Name`=%s ",prepare_mysql($tag));

		else
			return;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['List Key'];

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






		$sql=sprintf("select `List Key` from `List Dimension` where `List Name`=%s and  `List Scope`=%s and `List Parent Key`=%d",
			prepare_mysql($data['List Name']),
			prepare_mysql($data['List Scope']),
			$data['List Parent Key']
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['List Key'];
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
		$sql=sprintf("insert into `List Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("List added");
			$this->get_data('id',$this->id);
			$this->new=true;


			return;
		}else {
			$this->msg="Error can not create list";
		}
	}







	function get($key,$data=false) {
		switch ($key) {
		
		case 'Formated Type':
		
		if($this->data['List Type']=='Static'){
			return _('Static');
		}else{
			return _('Dynamic');
		}
		
		break;
		default:

			if (array_key_exists($key,$this->data))
				return $this->data[$key];
			else {

				return $key;
			}
		}
		return '';
	}

}
?>
