<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2016 at 19:43:38 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Webpage extends DB_Table{

	var $areas=false;
	var $locations=false;

	function Webpage($a1, $a2=false, $a3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Webpage';
		$this->ignore_fields=array('Webpage Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}elseif ($a1=='find') {
			$this->find($a2, $a3);

		}else
			$this->get_data($a1, $a2);
	}


	function get_data($key, $tag) {

		if ($key=='id')
			$sql=sprintf("select * from `Webpage Dimension` where `Webpage Key`=%d", $tag);
		else if ($key=='code')
			$sql=sprintf("select  * from `Webpage Dimension` where `Webpage Code`=%s ", prepare_mysql($tag));
		else
			return;


		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Webpage Key'];
			$this->code=$this->data['Webpage Code'];
			$this->properties=json_decode($this->data['Webpage Properties'],true);
	
		}



	}



	function find($raw_data, $options) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;

		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}


		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data))
				$data[$key]=_trim($value);
		}



		if ($data['Webpage Code']=='' ) {
			$this->error=true;
			$this->msg='Webpage code empty';
			return;
		}

		if ($data['Webpage Name']=='' ) {
			$this->error=true;
			$this->msg='Webpage name empty';
			return;
		}


		$sql=sprintf("select `Webpage Key` from `Webpage Dimension` where `Webpage Website Node Key`=%d and  `Webpage Code`=%s",
			$data['Webpage Website Node Key'],
			prepare_mysql($data['Webpage Code'])
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->found=true;
				$this->found_key=$row['Webpage Key'];
				$this->get_data('id', $this->found_key);
				$this->duplicated_field='Webpage Code';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($create and !$this->found) {
			$this->create($data);
			return;
		}




	}


	function create($data) {
		$this->new=false;
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			//   if (preg_match('/^()$/i', $key))
			//    $values.=prepare_mysql($value, false).",";
			//   else
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Webpage Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->msg=_("Webpage created");
			$this->get_data('id', $this->id);
			$this->new=true;





			return;
		}else {
			$this->msg="Error can not create webpage";
			print $sql;
			exit;
		}
	}




	function get($key, $data=false) {

		if (!$this->id) {
			return '';
		}



		switch ($key) {

		default:




			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Webpage '.$key, $this->data))
				return $this->data['Webpage '.$key];


		}
		return '';
	}

    function get_property($key){
    
    	if (!$this->id or !$this->properties) {
			return '';
		}
    
        if(array_key_exists($key, $this->properties)){
            return $this->properties[$key];
        }else{
            return false;
        }
    
    }


	function update_field_switcher($field, $value, $options='', $metadata='') {


		if ($this->deleted)return;

		switch ($field) {
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field, $value, $options);
				}
			}





		}
	}





	function get_field_label($field) {

		switch ($field) {

		case 'Webpage Code':
			$label=_('code');
			break;
		case 'Webpage Name':
			$label=_('name');
			break;
		case 'Webpage Address':
			$label=_('address');
			break;

		default:


			$label=$field;

		}

		return $label;

	}


	function append_block($data, $position=0) {



		$sql=sprintf('insert into `Webpage Block Bridge` (`Webpage Block Website Key`,`Webpage Block Position`,`Webpage Block Template`,`Webpage Block Settings`) values (%d,%d,%s,%s) ',
			$this->id,
			(10*$position)+5,
			prepare_mysql($data['Webpage Block Template']),
			prepare_mysql(json_encode($data['Webpage Block Settings']))
		);

		$this->db->exec($sql);


		$sql="SET @ordering_inc = 10;SET @new_ordering = 0;";
		$sql.=sprintf("update  `Webpage Block Bridge` set `Webpage Block Position` = (@new_ordering := @new_ordering + @ordering_inc) where `Webpage Block Website Key`=%d order by `Webpage Block Position` desc",
			$this->id
		);
		$this->db->exec($sql);
	}


	function get_content($smarty) {


        $content='';

		$sql=sprintf('select `Webpage Block Template`,`Webpage Block Settings` from  `Webpage Block Bridge` where `Webpage Block Website Key`=%d  order by `Webpage Block Position` desc', $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
			    $smarty->assign('settings',json_decode($row['Webpage Block Settings'],true));
			
                $content.=$smarty->fetch($row['Webpage Block Template'].'.tpl');
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


        return $content;
	}


}


?>
