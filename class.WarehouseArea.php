<?php
/*
 File: WarehouseArea.php 

 This file contains the Warehouse Area Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Warehouse.php');
include_once('class.Location.php');


class WarehouseArea extends DB_Table{
  

  var $locations=false;



  function WarehouseArea($arg1=false,$arg2=false) {

    $this->table_name='WarehouseArea';
    $this->ignore_fields=array('Warehouse Area Key');

     if(preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)){
       $this->create($arg2);
       return;
     }

     if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg1);
       return;
     }


     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
     $this->get_data($arg1,$arg2);
     


  }

  /*
   Method: find
   Find W Area with similar data
  */   
  
  function find($raw_data,$options){
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }
    $this->found=false;
    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    
    $data=$this->base_data();
    foreach($raw_data as $key=>$val){
      if(preg_match('/from supplier/',$options))
	$_key=preg_replace('/^Location /i','',$key);
      else
	$_key=$key;
      $data[$_key]=$val;
    }
    
    
    //look for areas with the same code in the same warehouse
    $sql=sprinf("select `Warehouse Area Key` from `Warehose Area Dimension` where `Warehouse Key`=%d and `Warehouse Area Code`=%s",$data['Warehouse Key'],$data['Warehouse Area Code']);
    $res=mysql_query($sql);
    if($row=mysql_getch_array($res)){
      $this->found=true;
      $this->found_key=$row['Warehouse Area Key'];
    }

    //what to do if found
    if($this->found){
      $this->get_data('id',$this->found_key);
    }
      
    if($create){
      if($this->found){
	$this->update($raw_data,$options);
      }else{
	$this->create($data,$options);

      }


  }


    function create ($data,$options=''){

      $this->data=$this->base_data();
      foreach($data as $key=>$value){
	if(array_key_exists($key,$this->data))
	  $this->data[$key]=_trim($value);
      }
      
      if($this->data['Warehouse Area Code']==''){
	$this->msg=('Wrong warehouse area name');
	$this->new=false;
	$this->error=true;
	return;
      }
      $warehouse=new Warehouse('id',$this->data['Warehouse Key']);
      if(!$warehouse->id){
	$this->msg=('Wrong warehouse key');
	$this->new=false;
	$this->error=true;
	return;

      }


      $keys='(';$values='values(';
    foreach($this->data as $key=>$value){

	$keys.="`$key`,";
	$values.=prepare_mysql($value,true).",";
      }
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);

    $sql=sprintf("insert into `Warehouse Area Dimension` %s %s",$keys,$values);
    //print "creating contact\n";

    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->new=true;
      $this->get_data('id',$this->id);
      $note=_('Warehouse Area Created');
      $details=_('Warehouse Area')." ".$this->data['Warehouse Area Code']." "._('created in')." ".$warehouse->data['Warehouse Name'];


    }else{
      // error
    }

    }

  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select * from `Warehouse Area Dimension` where `Warehouse Area Key`=%d",$tag);
    else if($key=='code')
      $sql=sprintf("select  *  from `Warehouse Area Dimension` where `Warehouse Area Code`=%s ",prepare_mysql($tag));
    else
      return;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Warehouse Area Key'];
    }
      



  }
 


  function xupdate($data){
    foreach($data as $key =>$value)
      switch($key){
      case('code'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong warehouse area code');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->data['Warehose Area Code']){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$WA=new WarehouseArea('code',$value);
	if($WA->id){
	  $this->msg=_('Another ware house has the same name');
	  $this->update_ok=false;
	  return;
	}
	$this->data['Wareahouse Area Code']=$name;
	$this->msg=_('Warehouse Area name changed');
	$this->update_ok=true;
	break;
      }
    
    
  }


  function load($key=''){
    switch($key){
    case('locations'):
      
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_locations'):
    case('number_locations'):
      if(!$this->areas)
	$this->load('areas');
      return count($this->areas);
      break;
    case('locations'):
      if(!$this->locations)
	$this->load('locations');
      return $this->locations;
      break;
    case('area'):
      if(!$this->locations)
	$this->load('locations');
      if(isset($this->locations[$data['id']]))
	return $this->locations[$data['id']];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
      
}

?>