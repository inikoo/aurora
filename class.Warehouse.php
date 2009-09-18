<?php
/*
 File: Warehouse.php 

 This file contains the Warehouse Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.WarehouseArea.php');
include_once('class.Location.php');

class Warehouse extends DB_Table{

  var $areas=false;
  var $locations=false;
  
  function Warehouse($arg1=false,$arg2=false) {

    $this->table_name='Warehouse';
    $this->ignore_fields=array('Warehouse Key');

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



  function get_data($key,$tag){
    
    if($key=='id')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area` from `Warehouse Dimension` where `Warehouse Key`=%d",$tag);
    else if($key=='code')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area`  from `Warehouse Dimension` where `Warehouse Code`=%s ",prepare_mysql($tag));
    else if($key=='name')
      $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name`,`Address Key`,`Warehouse Total Area`  from `Warehouse Dimension` where `Warehouse Name`=%s ",prepare_mysql($tag));
    
    else
      return;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Warehouse Key'];
    }
      



  }
 


  function xupdate($data){
    foreach($data as $key =>$value)
      switch($key){
      case('name'):
	$name=_trim($value);
	
	if($name==''){
	  $this->msg=_('Wrong warehouse name');
	  $this->update_ok=false;
	  return;
	}

	if($name==$this->get($tipo)){
	  $this->msg=_('Nothing to change');
	  $this->update_ok=false;
	  return;
	}

	$location=new Warehouse('name',$value);
	if($location->id){
	  $this->msg=_('Another ware house has the same name');
	  $this->update_ok=false;
	  return;
	}
	$this->data['name']=$name;
	$this->msg=_('Warehouse name changed');
	$this->update_ok=true;
	break;
      }
    
    
  }


  function load($key=''){
    switch($key){
    case('areas'):
      $sql=sprintf("select * from `Warehouse Area Dimension` where `Warehouse Key`=%d ",$this->id);
      
      $result =mysql_query($sql);
      $this->areas=array();
      while($row=mysql_fetch_array($result)){
 	$this->areas[$row['id']]=array(
 				       'id'=>$row['`Warehouse Area Key`'],
				       'code'=>$row['Warehouse Area Code'],
 				       );
      }
      break;

    }
      

  }


  function get($key,$data=false){
    switch($key){
    case('num_areas'):
    case('number_areas'):
      if(!$this->areas)
	$this->load('areas');
      return count($this->areas);
      break;
    case('areas'):
      if(!$this->areas)
	$this->load('areas');
      return $this->areas;
      break;
    case('area'):
      if(!$this->areas)
	$this->load('areas');
      if(isset($this->areas[$data['id']]))
	return $this->areas[$data['id']];
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
    

 function add_area($data){
   // print_r($data);
    $this->new_area=false;
    $data['Warehouse Key']=$this->id;
    $area= new WarehouseArea('find',$data,'create');
    $this->new_area_msg=$area->msg;
    if($area->new)
      $this->new_area=true;

  }
  
}

?>