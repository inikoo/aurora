<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 26 March 2016 at 00:37:22 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'class.DB_Table.php';


class Upload extends DB_Table {



	function __construct($a1, $a2=false, $a3=false) {
		global $db;
		$this->db=$db;

		$this->table_name='Upload';
		$this->ignore_fields=array('Upload Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		}
		elseif ($a1=='create') {
			$this->create($a2);

		}
		else {
			$this->get_data($a1, $a2);
		}
	}


	function get_data($key, $tag) {

		if ($key=='id') {
			$sql=sprintf("select * from `Upload Dimension` where `Upload Key`=%d", $tag);

		}
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Upload Key'];
		}



	}



	function create($data) {
	
		$this->new=false;

		$data['Upload State']='Uploaded';

		$data['Upload Created']=gmdate('Y-m-d H:i:s');

		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Upload Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->get_data('id', $this->id);


			$this->new=true;






		}else {
			$this->error=true;
			$this->msg='Error inserting upload record';
		}




	}






	function append_log($value) {



		$value=$this->data['Upload Log']."\n".$value;
		$this->update_field_switcher('Upload Log', $value);
	}


	function is_in_process() {
		if (in_array($this->data['Upload State'], array('Finished', 'Cancelled'))) {
			return false;

		}else {
			return true;
		}
	}


	function get($key, $data=false) {

		if (!$this->id)
			return;

		switch ($key) {
		case ('Cancelled Date'):
		case ('Finish Date'):
		case ('Start Date'):
			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Upload '.$key].' +0:00'));
			break;

		case ('Filesize'):
			include_once 'utils/units_functions.php';
			return file_size($this->data['Upload File Size']);
			break;
		case('Todo'):
			return number($this->data['Imported Waiting Records']);
			break;
		case('Ignored'):
			return number($this->data['Imported Ignored Records']);
			break;
		case('Imported'):
			return number($this->data['Imported Imported Records']);
			break;
		case('Cancelled'):
			return number($this->data['Imported Cancelled Records']);
			break;
		case('Error'):
		case('Errors'):
			return number($this->data['Imported Error Records']);
			break;
		default:

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Upload '.$key, $this->data))
				return $this->data['Upload '.$key];
		}
		return '';
	}


	function get_subject_list_link() {
		if ($this->data['Upload Object List Key']) {

			switch ($this->data['Upload Object']) {
			case 'customers':
				return sprintf("<a href='customers_list.php?id=%d'>%s</a>",
					$this->data['Scope List Key'],
					_('Imported customers list')
				);
				break;
			default:
				return "";
			}
		} else {
			return '';
		}
	}


	function get_not_imported_log_link() {

		if ($this->data['Upload Log']!='') {



			return sprintf('<a href="records_not_imported_log.php?id=%d" target="_blank">%s</a>',
				$this->id,
				_('Error Log'));

		} else {
			return '';
		}


	}


	function get_log_link() {

		if ($this->data['Error Records'] or $this->data['Ignored Records']==0) {
			return '';
		}

		return sprintf('<a href="records_not_imported_log.php?id=%d" target="_blank">%s</a>',
			$this->id,
			_('Ignored Log'));


	}


	function update_records_numbers() {
		$records_numbers=array('Imported Ignored Records'=>0, 'Imported Imported Records'=>0, 'Imported Error Records'=>0, 'Imported Waiting Records'=>0, 'Imported Importing Records'=>0, 'Imported Cancelled Records'=>0);
		$sql=sprintf("select count(*) as num,`Imported Record Import State` from `Imported Record` where `Imported Record Parent Key`=%d group by  `Imported Record Import State`; ",
			$this->id
		);


		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {

			$records_numbers['Imported '.$row['Imported Record Import State'].' Records']=$row['num'];
		}


		$sql=sprintf("update `Upload Dimension` set
		`Imported Ignored Records`=%d ,
		`Imported Imported Records`=%d ,
		`Imported Error Records`=%d ,
		`Imported Waiting Records`=%d ,
		`Imported Importing Records`=%d,
		`Imported Cancelled Records`=%d
		where `Upload Key`=%d ",
			$records_numbers['Imported Ignored Records'],
			$records_numbers['Imported Imported Records'],
			$records_numbers['Imported Error Records'],
			$records_numbers['Imported Waiting Records'],
			$records_numbers['Imported Importing Records'],
			$records_numbers['Imported Cancelled Records'],
			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
		$this->data['Imported Ignored Records']=$records_numbers['Imported Ignored Records'];
		$this->data['Imported Imported Records']=$records_numbers['Imported Imported Records'];
		$this->data['Imported Error Records']=$records_numbers['Imported Error Records'];
		$this->data['Imported Waiting Records']=$records_numbers['Imported Waiting Records'];
		$this->data['Imported Importing Records']=$records_numbers['Imported Importing Records'];
		$this->data['Imported Cancelled Records']=$records_numbers['Imported Cancelled Records'];

	}



	function cancel() {
		$this->cancelled=false;
		if (in_array($this->data['Upload State'], array('InProcess', 'Queued'))) {

			$sql=sprintf("update `Upload Dimension` set `Upload State`='Cancelled',`Upload Finish Date`=NOW(),`Upload Cancelled Date`=NOW()  where `Upload Key`=%d ",
				$this->id);
			mysql_query($sql);

			$sql=sprintf("update `Imported Record` set `Imported Record Import State`='Cancelled'  where `Imported Record Import State`='Waiting' and  `Imported Record Parent Key`=%d ",
				$this->id);
			mysql_query($sql);

			$this->update_records_numbers();

			$sql=sprintf("update `Fork Dimension` set `Fork State`='Cancelled' where `Fork Key`=%d ", $this->data['Upload Fork Key']);
			mysql_query($sql);

			$this->cancelled=true;


		}elseif (in_array($this->data['Upload State'], array('Uploading', 'Review'))) {

			$sql=sprintf("delete from `Upload Dimension` where `Upload Key`=%d ",
				$this->id);
			mysql_query($sql);
			$this->clear_records();
			$this->cancelled=true;


		}else {

			$this->msg='can not cancel or delete '.$this->data['Upload State'];

		}
	}




	function delete() {
		$this->deleted=false;
		if (in_array($this->data['Upload State'], array('Uploading', 'Review', 'Queued'))) {

			$sql=sprintf("delete from `Upload Dimension` where `Upload Key`=%d ",
				$this->id);
			mysql_query($sql);
			$this->clear_records();
			$this->cancelled=true;


		}
	}


	function clear_records() {
		$sql=sprintf("delete from `Imported Record` where `Imported Record Parent Key`=%d ",
			$this->id);
		mysql_query($sql);

	}




}


?>
