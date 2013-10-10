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
			//  $sql=sprintf("select `Imported Records Key`,`Imported Records Creation Date`,`Imported Records Start Date`,`Imported Records Finish Date`,`Imported Records Parent`,`Imported Records Parent Key`,`Original Records`,`Ignored Records`,`Imported Recordss`,`Error Records`,`Scope List Key` from `Imported Records Dimension` where `Imported Records Key`=%d",$tag);
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

		if ($data['Imported Records Parent']=='' or $data['Imported Records Subject']=='') {
			$this->error=true;
			$this->msg='Imported Records Parent/Subject empty';
			return;
		}




		$sql=sprintf("select `Imported Records User Key`,`Imported Records Key` from `Imported Records Dimension` where `Imported Records Subject`=%s and `Imported Records Parent`=%s and `Imported Records Parent Key`=%d and `Imported Records File Checksum`=%s  and `Imported Records State`!='Finished' ",
			prepare_mysql($data['Imported Records Subject']),
			prepare_mysql($data['Imported Records Parent']),
			$data['Imported Records Parent Key'],

			prepare_mysql($data['Imported Records File Checksum']
			)
		);

		$result=mysql_query($sql);
		$this->found_items = mysql_num_rows($result);
		$this->found_in_users=array();
		$this->found_in_users_map=array();

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['Imported Records Key'];
			$this->found_in_users_map[$row['Imported Records Key']]=$row['Imported Records User Key'];
			$this->found_in_users[$row['Imported Records User Key']]=$row['Imported Records User Key'];

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





	function append_log($value) {



		$value=$this->data['Imported Records Log']."\n".$value;
		$this->update_field_switcher('Imported Records Log',$value);
	}





	function get($key,$data=false) {
		switch ($key) {

		case ('Filesize'):
			include_once 'common_units_functions.php';
			return formatSizeUnits($this->data['Imported Records File Size']);
			break;
		case('To do'):
			return number($this->data['Imported Waiting Records']);
			break;
		case('Ignored'):
			return number($this->data['Imported Ignored Records']);
			break;
		case('Imported'):
			return number($this->data['Imported Imported Records']);
			break;
		case('Error'):
		case('Errors'):
			return number($this->data['Imported Error Records']);
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
			else
				return '';
		}
		return '';
	}


	function get_subject_list_link() {
		if ($this->data['Imported Records Subject List Key']) {

			switch ($this->data['Imported Records Subject']) {
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

		if ($this->data['Imported Records Log']!='') {



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
		$records_numbers=array('Imported Ignored Records'=>0,'Imported Imported Records'=>0,'Imported Error Records'=>0,'Imported Waiting Records'=>0,'Imported Importing Records'=>0);
		$sql=sprintf("select count(*) as num,`Imported Record Import State` from `Imported Record` where `Imported Record Parent Key`=%d group by  `Imported Record Import State`; ",
			$this->id
		);
		
	//	print "$sql\n";

		
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {

			$records_numbers['Imported '.$row['Imported Record Import State'].' Records']=$row['num'];
		}


		$sql=sprintf("update `Imported Records Dimension` set
		`Imported Ignored Records`=%d ,
		`Imported Imported Records`=%d ,
		`Imported Error Records`=%d ,
		`Imported Waiting Records`=%d ,
		`Imported Importing Records`=%d

		where `Imported Records Key`=%d ",
			$records_numbers['Imported Ignored Records'],
			$records_numbers['Imported Imported Records'],
			$records_numbers['Imported Error Records'],
			$records_numbers['Imported Waiting Records'],
			$records_numbers['Imported Importing Records'],
			$this->id
		);
		mysql_query($sql);
//print "$sql\n";
		$this->data['Imported Ignored Records']=$records_numbers['Imported Ignored Records'];
		$this->data['Imported Imported Records']=$records_numbers['Imported Imported Records'];
		$this->data['Imported Error Records']=$records_numbers['Imported Error Records'];
		$this->data['Imported Waiting Records']=$records_numbers['Imported Waiting Records'];
		$this->data['Imported Importing Records']=$records_numbers['Imported Importing Records'];

	}








	function delete() {
		$this->deleted=false;
		if (in_array($this->data['Imported Records State'],array('Uploading','Review','Queued'))) {

			$sql=sprintf("delete from `Imported Records Dimension` where `Imported Records Key`=%d ",
				$this->id);
			mysql_query($sql);
			$this->clear_records();
			$this->deleted=true;


		}
	}
	function clear_records() {
		$sql=sprintf("delete from `Imported Record` where `Imported Record Parent Key`=%d ",
			$this->id);
		mysql_query($sql);

	}


}
?>
