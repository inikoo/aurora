<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 30 April 2014 15:10:05 CEST, Malaga Spain

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Material extends DB_Table{

	function Material($a1=false,$a2=false) {

		$this->table_name='Material';
	$this->ignore_fields=array('Material Key');
	
		if ($a1=='create') {
			$this->create($a2);

		}if ($a1=='find create') {
			$this->find($a2,$a1);

		}else
			$this->get_data($a1,$s2);
	}
	function get_data($tag,$key) {


		$sql=sprintf("select * from `Material Dimension` where `Material Key`=%d ",$key);


		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Material Key'];


		}




	}


	function find($raw_data,$options){
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


		//    print_r($raw_data);

		
		if ($data['Material Name']==''){
			return;
		}
			


		$sql=sprintf("select `Material Key` from `Material Dimension` where `Material Name`=%s  "
			,prepare_mysql($data['Material Name'])
		);
		//print $sql;

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Material Key'];
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
			if (array_key_exists($key,$base_data)){
				$base_data[$key]=_trim($value);
				
				
				}
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";

		if($key=='Material XHTML Description'){
								$values.=prepare_mysql($value,false).",";

				}else{
									$values.=prepare_mysql($value).",";

				}
				
		
		
				
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		
		$sql=sprintf("insert into `Material Dimension` %s %s",$keys,$values);
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Material added");
			$this->get_data('id',$this->id);
			$this->new=true;



			return;
		}else {
			$this->msg="Error can not create material\n";
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

		case 'Material Name':
			$this->update_company_name($value);
			break;
		case('Material Currency'):
			$this->update_currency($value);
			break;
		default:
			$this->company->update_field_switcher($field,$value,$options);
			break;
		}
	}


	function update_name($value) {


		$sql=sprintf("update `Material Dimension` set `Material Name`=%s",prepare_mysql($value));
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

			$sql=sprintf("update `Material Dimension` set `Material Currency`=%s",prepare_mysql($value));
			mysql_query($sql);

			$this->updated=true;
			$this->new_value=$value;

		}else {
			$this->error=true;
			$this->msg='Currency Code '.$value.' not valid';

		}
	}


	function add_account_history($history_key,$type=false) {
		$this->post_add_history($history_key,$type=false);
	}

	function post_add_history($history_key,$type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Material History Bridge` (`History Key`,`Type`) values (%d,%s)",
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);
		//print $sql;
	}

}

?>
