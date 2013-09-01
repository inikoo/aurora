<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo


*/
include_once 'class.DB_Table.php';


class ImportedRecords extends DB_Table {



	function ImportedRecords($a1,$a2=false,$a3=false) {

		$this->table_name='Imported Records';
		$this->ignore_fields=array('Imported Records Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		}
		elseif ($a1=='find') {
			$this->find($a2,$a3);

		}
		else
			$this->get_data($a1,$a2);
	}


	function get_data($key,$tag) {

		if ($key=='id') {
			//  $sql=sprintf("select `Imported Records Key`,`Imported Records Creation Date`,`Imported Records Start Date`,`Imported Records Finish Date`,`Imported Records Scope`,`Imported Records Scope Key`,`Original Records`,`Ignored Records`,`Imported Recordss`,`Error Records`,`Scope List Key` from `Imported Records Dimension` where `Imported Records Key`=%d",$tag);
			$sql=sprintf("select * from `Imported Records Dimension` where `Imported Records Key`=%d",$tag);

		}

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Imported Records Key'];
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


		//    print_r($raw_data);

		if ($data['Imported Records Scope']=='' ) {
			$this->error=true;
			$this->msg='Imported Records Scope empty';
			return;
		}




		$sql=sprintf("select `Imported Records Key` from `Imported Records Dimension` where `Imported Records Scope`=%s and `Imported Records Scope Key`=%s and `Imported Records File Checksum`=%s  and `Imported Records Start Date` is NULL",
			prepare_mysql($data['Imported Records Scope']),
			prepare_mysql($data['Imported Records Scope Key']),
			prepare_mysql($data['Imported Records File Checksum'])
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Imported Records Key'];
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

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Imported Records Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->msg=_("Imported Records");
			$this->get_data('id',$this->id);
			$this->new=true;


			return;
		} else {
			$this->msg=" Error can not create Imported Records";
		}
	}





	function append_not_imported_log($value) {



		$value=$this->data['Not Imported Log']."\n".$value;
		$this->update_field_switcher('Not Imported Log',$value);
	}





	function get($key,$data=false) {
		switch ($key) {

		case('To do'):
			return number($this->data['Original Records']-$this->data['Ignored Records']-$this->data['Imported Records']-$this->data['Error Records']);
			break;
		case('Ignored'):
			return number($this->data['Ignored Records']);
			break;
		case('Imported'):
			return number($this->data['Imported Records']);
			break;
		case('Error'):
		case('Errors'):
			return number($this->data['Error Records']);
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
			else
				return '';
		}
		return '';
	}


	function get_scope_list_link() {
		if ($this->data['Scope List Key']) {

			return sprintf("<a href='customers_list.php?id=%d'>%s</a>",
				$this->data['Scope List Key'],
				_('Imported customers list')
			);
		} else {
			return '';
		}
	}

	function get_not_imported_log_link() {

		if ($this->data['Not Imported Log']!='') {



			return sprintf('<a href="records_not_imported_log.php?id=%d" target="_blank">%s</a>',
				$this->id,
				_('Error Log'));

		} else {
			return '';
		}


	}

	function get_ignored_log_link() {

		if ($this->data['Error Records'] or $this->data['Ignored Records']==0) {
			return '';
		}

		return sprintf('<a href="records_not_imported_log.php?id=%d" target="_blank">%s</a>',
			$this->id,
			_('Ignored Log'));


	}



}
?>
