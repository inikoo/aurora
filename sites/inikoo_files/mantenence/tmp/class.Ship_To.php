<?php
/*
 File: Ship_To.php

 This file contains the Ship To Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

/* class: Ship_To
   Class to manage the *Company Dimension* table
*/
class Ship_To extends DB_Table {


	/*
         Constructor: Ship_To

         Initializes the class, Search/Load or Create for the data set




       */
	function Ship_To($arg1=false,$arg2=false) {

		$this->table_name='Ship To';
		$this->ignore_fields=array('Ship To Key');

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


	// function get_unknown(){
	//   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
	//   $result=mysql_query($sql);
	//   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
	//     $this->id=$this->data['Store Key'];
	// }

	/*
        Function: get_data
        Obtiene los datos de la tabla Ship To Dimension de acuerdo al Id
    */
	// JFA

	function get_data($tipo,$tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Ship To Dimension` where `Ship To Key`=%d",$tag);
		else
			return;

		// print $sql;
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Ship To Key'];


	}

	/*
       Method: find


       Returns:
     Key of the Shipping Addreses found, if create is found in the options string  returns the new key
      */
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


		$fields=array('Ship To Email','Ship To Telephone','Ship To Company Name','Ship To Contact Name','Ship To Country Code','Ship To Postal Code','Ship To Town','Ship To Line 1','Ship To Line 2','Ship To Line 3','Ship To Line 4');

		$sql=sprintf("select * from `Ship To Dimension` where true  ");
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

				$this->get_data('id',$row['Ship To Key']);
				$this->found=true;
				$this->found_key=$row['Ship To Key'];

			} else {// Found in mora than one
			print("Warning to shipping addresses $sql\n");
			$row=mysql_fetch_array($result, MYSQL_ASSOC);

			$this->get_data('id',$row['Ship To Key']);
			$this->found=true;
			$this->found_key=$row['Ship To Key'];


		}

		if (!$this->found and $create) {
			$this->create($data);

		}


	}


	
	function get($key='') {


		if ($key=='World Region Code') {


			if ($this->data['Ship To Country Code']=='')return 'UNKN';

			$sql=sprintf("select `World Region Code` from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($this->data['Ship To Country Code']));
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
		print "Error $key not found in get from Ship TO\n";
		return false;

	}



	function create($data) {

		$this->data=$data;

		$keys='';
		$values='';

		foreach ($this->data as $key=>$value) {
			if ($key=='Ship To XHTML Address')
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

		$sql="insert into `Ship To Dimension` ($keys) values ($values)";
		//print $sql;
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
			$this->get_data('id',$this->id);
			$this->data['Ship To XHTML Address']=$this->get_xhtml_address();
			$sql=sprintf("update `Ship To Dimension` set `Ship To XHTML Address`=%s where `Ship To Key`=%d",prepare_mysql($this->data['Ship To XHTML Address']),$this->id);
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
		if ($this->data['Ship To Line 1']!='')
			$address=_trim($this->data['Ship To Line 1']).$separator;
		if ($this->data['Ship To Line 2']!='')
			$address.=_trim($this->data['Ship To Line 2']).$separator;

		if ($this->data['Ship To Line 3']!='')
			$address.=_trim($this->data['Ship To Line 3']).$separator;
		$town_address=_trim($this->data['Ship To Town']);
		if ($town_address!='')
			$address.=$town_address.$separator;

		if ($this->data['Ship To Line 4']!='')
			$address.=_trim($this->data['Ship To Line 3']).$separator;
		$ps_address=_trim($this->data['Ship To Postal Code']);
		if ($ps_address!='')
			$address.=$ps_address.$separator;

		$address.=$this->data['Ship To Country Name'];

		return _trim($address);

	}


	function get_xhtml_address() {
		return $this->display('xhtml');

	}


}
