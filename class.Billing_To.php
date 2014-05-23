<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 23 May 2014 15:51:16 CEST, Malaga , Spain

 Version 2.0
*/


class Billing_To extends DB_Table {


	function Billing_To($arg1=false,$arg2=false) {

		$this->table_name='Billing To';
		$this->ignore_fields=array('Billing To Key');

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
			$sql=sprintf("select * from `Billing To Dimension` where `Billing To Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Billing To Key'];


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


		$fields=array('Billing To Email','Billing To Telephone','Billing To Company Name','Billing To Contact Name','Billing To Country Code','Billing To Postal Code','Billing To Town','Billing To Line 1','Billing To Line 2','Billing To Line 3','Billing To Line 4');

		$sql=sprintf("select * from `Billing To Dimension` where true  ");
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

				$this->get_data('id',$row['Billing To Key']);
				$this->found=true;
				$this->found_key=$row['Billing To Key'];

			} else {// Found in mora than one
			print("Warning to shipping addresses $sql\n");
			$row=mysql_fetch_array($result, MYSQL_ASSOC);

			$this->get_data('id',$row['Billing To Key']);
			$this->found=true;
			$this->found_key=$row['Billing To Key'];


		}

		if (!$this->found and $create) {
			$this->create($data);

		}


	}



	function get($key='') {


		if ($key=='World Region Code') {


			if ($this->data['Billing To Country Code']=='')return 'UNKN';

			$sql=sprintf("select `World Region Code` from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($this->data['Billing To Country Code']));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result))
				return $row['World Region Code']==''?'UNKN':$row['World Region Code'];
			else
				return 'UNKN';

		}


		if (isset($this->data[$key]))
			return $this->data[$key];

		switch ($key) {
		}
		$_key=ucfirst($key);
		if (isset($this->data[$_key]))
			return $this->data[$_key];
		print "Error $key not found in get from Billing To\n";
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';

		foreach ($this->data as $key=>$value) {
			if ($key=='Billing To XHTML Address')
				continue;
			//  if(preg_match('/Address Data Creation/i',$key) ){
			// $keys.=",`".$key."`";
			// $values.=', Now()';
			//}else{
			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value,false);
			// }

		}



		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Billing To Dimension` ($keys) values ($values)";
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
			$this->data['Billing To XHTML Address']=$this->get_xhtml_address();
			$sql=sprintf("update `Billing To Dimension` set `Billing To XHTML Address`=%s where `Billing To Key`=%d",prepare_mysql($this->data['Billing To XHTML Address']),$this->id);
			mysql_query($sql);
		} else {
			print "Error can not create address\n";
			exit;

		}
	}

	function display($tipo) {
		$separator='\n';

		if ($tipo=='xhtml')
			$separator='<br/>';



		$address='';
		if ($this->data['Billing To Line 1']!='')
			$address=_trim($this->data['Billing To Line 1']).$separator;
		if ($this->data['Billing To Line 2']!='')
			$address.=_trim($this->data['Billing To Line 2']).$separator;

		if ($this->data['Billing To Line 3']!='')
			$address.=_trim($this->data['Billing To Line 3']).$separator;
		$town_address=_trim($this->data['Billing To Town']);
		if ($town_address!='')
			$address.=$town_address.$separator;

		if ($this->data['Billing To Line 4']!='')
			$address.=_trim($this->data['Billing To Line 3']).$separator;
		$ps_address=_trim($this->data['Billing To Postal Code']);
		if ($ps_address!='')
			$address.=$ps_address.$separator;

		$address.=$this->data['Billing To Country Name'];

		return _trim($address);

	}


	function get_xhtml_address() {
		return $this->display('xhtml');

	}


}
