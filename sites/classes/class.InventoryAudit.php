<?php
/*
 File: Page.php

 This file contains the Inventory Audit Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');

class InventoryAudit extends DB_Table {

    var $new=false;

    function InventoryAudit($arg1=false,$arg2=false,$arg3=false) {
        $this->table_name='Inventory Audit';
        $this->ignore_fields=array('Inventory Audit Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }

        if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
            $this->find($arg2,$arg3.' create');
            return;
        }
        if (  preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg3);
            return;
        }

        $this->get_data($arg1,$arg2);

    }

    function get_data($tipo,$tag) {


        $sql=sprintf("select * from `Inventory Audit Dimension` where  `Inventory Audit Key`=%d",$tag);


        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Inventory Audit Key'];
        }

    }

    function find($raw_data,$options) {
$this->found=false;
        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

        $create=false;
        $update=false;
        if (preg_match('/create/i',$options)) {
            $create=true;
        }
        if (preg_match('/update/i',$options)) {
            $update=true;
        }

        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);


        }
//print_r($data);
        $sql=sprintf("select `Inventory Audit Key` from `Inventory Audit Dimension` where  `Inventory Audit Date`=%s and `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d"
                     ,prepare_mysql($data['Inventory Audit Date'])
                     ,   $data['Inventory Audit Part SKU']
                     ,$data['Inventory Audit Location Key']
                    );

//print $sql;
        $result =mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found=$row['Inventory Audit Key'];

        }

        if ($create and !$this->found)
            $this->create($data);

    }
    
    
    function create($data){
  
  $keys='(';
  $values='values(';
  foreach($data as $key=>$value) {
    $keys.="`$key`,";
    if (preg_match('/Note|User Key|Quantity/i',$key))
	  $values.="'".addslashes($value)."',";
    else
      $values.=prepare_mysql($value).",";
  }
  $keys=preg_replace('/,$/',')',$keys);
  $values=preg_replace('/,$/',')',$values);
  $sql=sprintf("insert into `Inventory Audit Dimension` %s %s",$keys,$values);
 //print "\n\n$sql\n\n\n";
      if (mysql_query($sql)) {
	$this->id=mysql_insert_id();
	$this->get_data('id',$this->id);
	
	
	
	
	
      }else{
	$this->error=true;$this->msg='Can not insert Inventory Audit Dimension';
      }
     
     
  }

    
    
    
    
    
    
    

}