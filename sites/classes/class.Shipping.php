<?php
/*
 File: Shipping.php

 This file contains the Shipping Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');

class Shipping extends DB_Table {




    function Shipping($a1,$a2=false) {

        $this->table_name='Shipping';
        $this->ignore_fields=array('Shipping Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
           $this->find($a2,'create');

        } elseif(preg_match('/find/i',$a1))
            $this->find($a2,$a1);
        else
            $this->get_data($a1,$a2);

    }

    function get_data($tipo,$tag) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Shipping Dimension` where `Shipping Key`=%d",$tag);
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Shipping Dimension` where `Shipping Code`=%s",prepare_mysql($tag));
        // print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
	  //$this->calculate_shipping=create_function('$transaction_data,$customer_id,$date', $this->get('Shipping Metadata'));
            $this->id=$this->data['Shipping Key'];
        }
    }

    function find($raw_data,$options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

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


	if(count($raw_data)==1){
	  
	  foreach($raw_data as $key=>$value){
	    if(preg_match('/^(Country Name|Country Code|Country)$/i',$key)){
	      $country_code=Address::parse_country($value);
	      $country=new Country('code',$country_code);

	      $date=prepare_mysql(date("Y-m-d H:i:s"));
	      
	      $sql=sprintf("select `Shipping Key`  from `Shipping Dimension` where `Shipping Destination Type`='Country' and `Shipping Destination Code`=%s and (`Shipping Begin Date` is null or `Shipping Begin Date`<=%s )  and (`Shipping Expiration Date` is null or `Shipping Expiration Date`>=%s )  "
			   ,prepare_mysql($country->data['Country Code']),$date,$date);
	      $res=mysql_query($sql);
	


	      if($row=mysql_fetch_array($res)){
		$this->found=true;
		$this->found_key=$row['Shipping Key'];
	
	      }


	    }

	  }



	}else{
	
	  $data=$this->base_data();
	  foreach($raw_data as $key=>$value) {
	    
            if (array_key_exists($key,$data))
	      $data[$key]=$value;
	    
	  }
	  $fields=array();
	  foreach($data as $key=>$value){
	    if(!($key=='Shipping Begin Date' or  $key=='Shipping Expiration Date'))
	      $fields[]=$key;
	  }
	  
	  $sql="select `Shipping Key` from `Shipping Dimension` where  true ";
	  //print_r($fields);
	  foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
	  }
	  //print $sql;
	  $result=mysql_query($sql);
	  $num_results=mysql_num_rows($result);
	  if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
	    $this->found_key=$row['Shipping Key'];
	    
	  }
	}


        if($this->found){
	  $this->get_data('id',$this->found_key);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

       

      

        //print_r($data);

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Shipping Dimension` %s %s",$keys,$values);
      //   print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create shipping  $sql\n";
            exit;

        }
    }

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {
	case('Country Name'):
	  if($this->data['Shipping Destination Type']=='Country'){
	    $country=new Country ('code',$this->data['Shipping Destination Code']);

	    return $country->data['Country Name'];
	  }
	  break;
        }

        return false;
    }

}