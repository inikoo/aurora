<?php
/*
 File: Shelf.php 

 This file contains the Shelf Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Warehouse.php');
include_once('class.WarehouseArea.php');

include_once('class.Location.php');


class Shelf extends DB_Table{
  
  function Shelf($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Shelf';
    $this->ignore_fields=array('Shelf Key');

     if(preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)){
       $this->create($arg2);
       return;
     }
     if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg3);
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
      $_key=$key;
      $data[$_key]=$val;
    }
    
    
   
    $sql=sprintf("select `Shelf Key` from `Shelf Dimension` where  `Shelf Code`=%s"
		 ,prepare_mysql($data['Shelf Code']));
    
    // print $sql;
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Shelf Key'];
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
  }


    function create ($data,$options=''){



      $this->data=$this->base_data();
      foreach($data as $key=>$value){
	if(array_key_exists($key,$this->data))
	  $this->data[$key]=_trim($value);
      }
      
      if($this->data['Shelf Code']==''){
	$this->msg=('Shelf type code is a requeried value');
	$this->new=false;
	$this->error=true;
	return;
      }
     /*  $warehouse=new Warehouse('id',$this->data['Warehouse Key']); */
/*       if(!$warehouse->id){ */
/* 	$this->msg=('Wrong warehouse key'); */
/* 	$this->new=false; */
/* 	$this->error=true; */
/* 	return; */

/*       } */
      

      $keys='(';$values='values(';
      foreach($this->data as $key=>$value){

	$keys.="`$key`,";
	$_mode=true;
	if($key=='Shelf Description')
	  $_mode=false;
	$values.=prepare_mysql($value,$_mode).",";
      }
    
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);

    $sql=sprintf("insert into `Shelf Dimension` %s %s",$keys,$values);
    //print "$sql\n";
    // exit;
    if(mysql_query($sql)){
      $this->id= mysql_insert_id();
      $this->new=true;
      $this->get_data('id',$this->id);
      $note=_('Shelf Created');
      $details=_('Shelf')." ".$this->data['Shelf Code']." "._('created');


    }else{
      exit($sql);
    }

    }

  function get_data($key,$tag){
    // print "K: $key";
    if($key=='id')
      $sql=sprintf("select * from `Shelf Dimension` where `Shelf Key`=%d",$tag);
    else if($key=='name')
      $sql=sprintf("select  *  from `Shelf Dimension` where `Shelf Name`=%s ",prepare_mysql($tag));
    else
      return;

    //print $sql;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Shelf Key'];
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
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
 

 
  function add_location($data){
    $this->new_area=false;
    $data['Location Warehouse Key']=$this->data['Shelf Warehouse Key'];
    $data['Location Warehouse Area Key']=$this->data['Shelf Area Key'];
    $data['Location Shelf Key']=$this->id;

    $location= new Location('find',$data,'create');
    $this->new_location_msg=$location->msg;
    if($location->new)
      $this->new_location=true;
    else{
      if($location->found)
	$this->new_location_msg=_('Location Code already in the warehouse');
    }
  }

    function update_children(){
   $sql=sprintf('select count(*) as number from `Location Dimension` where `Location Shelf Key`=%d',$this->id);
        $res=mysql_query($sql);
        $number_locations=0;
        if ($row=mysql_fetch_array($res)) {
            $number_locations=$row['number'];
        }

        $sql=sprintf('update `Shelf Dimension` set `Shelf Number Locations`=%d where `Shelf Key`=%d'
        ,$number_locations
        
        ,$this->id
        );
        mysql_query($sql);
        $this->get_data('id',$this->id);
  }
     
}

?>